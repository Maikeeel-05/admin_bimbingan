<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Mahasiswa</title>
    <style>
        body { margin: 0; font-family: Arial; background: #f4f4f4; }
        .sidebar {
            width: 220px; height: 100vh; background: #1e3a8a;
            color: white; position: fixed; padding-top: 20px;
        }
        .sidebar a {
            color: white; padding: 12px 20px; display: block;
            text-decoration:none;
        }
        .sidebar a:hover { background: #0f2167; }

        .main { margin-left: 220px; padding: 25px; }

        .container {
            background: white; padding: 20px; width: 500px;
            border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .row { margin-bottom: 12px; }
        .label { font-weight: bold; color: #333; }
        .value { margin-top: 4px; }

        button {
            margin-top: 20px; padding: 10px; width: 100%;
            background: #1e40af; color: white;
            border: none; border-radius: 6px; cursor: pointer;
        }
        button:hover { background: #16328c; }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2 style="text-align:center;">ADMIN</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="bimbingan_list.php">📄 Riwayat Bimbingan</a>
    <a href="dosen_list.php">👨‍🏫 Data Dosen</a>
    <a href="mahasiswa_list.php">🎓 Data Mahasiswa</a>
    <a href="#" onclick="logoutAdmin()">🚪 Logout</a>
</div>


<!-- MAIN CONTENT -->
<div class="main">
    <h2>Detail Mahasiswa</h2>

    <div class="container">
        <div class="row">
            <div class="label">Nama</div>
            <div class="value" id="nama">Memuat...</div>
        </div>

        <div class="row">
            <div class="label">Email</div>
            <div class="value" id="email">Memuat...</div>
        </div>

        <div class="row">
            <div class="label">Program Studi</div>
            <div class="value" id="prodi">Memuat...</div>
        </div>

        <div class="row">
            <div class="label">Angkatan</div>
            <div class="value" id="angkatan">Memuat...</div>
        </div>

        <div class="row">
            <div class="label">Dosen Pembimbing</div>
            <div class="value" id="dosenPembimbing">Memuat...</div>
        </div>

        <div class="row">
            <div class="label">Topik TA</div>
            <div class="value" id="topikTA">Memuat...</div>
        </div>

        <button onclick="window.location.href='mahasiswa_list.php'">Kembali</button>
    </div>
</div>



<!-- FIREBASE -->
<script type="module">

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { 
    getFirestore,
    doc,
    getDoc
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

import { 
    getAuth,
    signOut
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";


// KONFIGURASI FIREBASE
const firebaseConfig = {
    apiKey: "AIzaSyB2myHLdJ15MNh0dij7yjF6dpEVehBkN-s",
    authDomain: "bimbinganskripsi-f9540.firebaseapp.com",
    projectId: "bimbinganskripsi-f9540",
    storageBucket: "bimbinganskripsi-f9540.firebasestorage.app",
    messagingSenderId: "801351593012",
    appId: "1:801351593012:web:6b2540f69f49d322e42e61"
};

const app = initializeApp(firebaseConfig);
const db  = getFirestore(app);
const auth = getAuth(app);


// ======================================================
// 🟦 GET PARAM ID
// ======================================================
const urlParams = new URLSearchParams(window.location.search);
const mahasiswaId = urlParams.get("id");

if (!mahasiswaId) {
    alert("ID mahasiswa tidak ditemukan!");
    window.location.href = "mahasiswa_list.php";
}


// ======================================================
// 🟢 LOAD DETAIL MAHASISWA
// ======================================================
async function loadMahasiswa() {

    const docRef = doc(db, "mahasiswa", mahasiswaId);
    const snap = await getDoc(docRef);

    if (!snap.exists()) {
        alert("Data mahasiswa tidak ditemukan!");
        window.location.href = "mahasiswa_list.php";
        return;
    }

    const m = snap.data();

    document.getElementById("nama").innerText = m.nama;
    document.getElementById("email").innerText = m.email;
    document.getElementById("prodi").innerText = m.prodi;
    document.getElementById("angkatan").innerText = m.angkatan;


    // ======================================================
    // 🔵 LOAD DOSEN PEMBIMBING (if exists)
    // ======================================================
    if (m.dosenPembimbingId && m.dosenPembimbingId !== "") {
        const dosenRef = doc(db, "dosen", m.dosenPembimbingId);
        const dosenSnap = await getDoc(dosenRef);

        if (dosenSnap.exists()) {
            let d = dosenSnap.data();
            document.getElementById("dosenPembimbing").innerText =
                d.nama + " (" + d.nip + ")";
        } else {
            document.getElementById("dosenPembimbing").innerText = "Tidak ditemukan";
        }
    } else {
        document.getElementById("dosenPembimbing").innerText = "Belum memilih pembimbing";
    }


    // ======================================================
    // 🟣 LOAD TOPIK TA (if exists)
    // ======================================================
    if (m.topikId && m.topikId !== "") {
        const topikRef = doc(db, "topik", m.topikId);
        const topikSnap = await getDoc(topikRef);

        if (topikSnap.exists()) {
            let t = topikSnap.data();
            document.getElementById("topikTA").innerText = t.judul ?? "(tanpa judul)";
        } else {
            document.getElementById("topikTA").innerText = "Tidak ditemukan";
        }
    } else {
        document.getElementById("topikTA").innerText = "Belum mengisi topik";
    }
}

loadMahasiswa();


// ======================================================
// 🔴 LOGOUT
// ======================================================
window.logoutAdmin = function () {
    signOut(auth).then(() => {
        window.location.href = "../index.php";
    });
};

</script>

</body>
</html>