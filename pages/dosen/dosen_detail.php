<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Dosen</title>
    <style>
        body { margin: 0; font-family: Arial; background: #f4f4f4; }
        .sidebar {
            width: 220px; height: 100vh; background: #1e3a8a;
            color: white; position: fixed; padding-top: 20px;
        }
        .sidebar a { color: white; padding: 12px 20px; display: block; text-decoration:none; }
        .sidebar a:hover { background: #0f2167; }

        .main { margin-left: 220px; padding: 25px; }

        .card {
            background: white; padding: 20px; border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 20px;
        }

        table {
            width: 100%; border-collapse: collapse; margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #ddd; padding: 10px; text-align: left;
        }
        table th {
            background: #1e3a8a; color: white;
        }

        .backbtn {
            padding: 8px 12px; background: #1e40af; color: white;
            border-radius: 6px; text-decoration:none;
        }
        .backbtn:hover { background: #16328c; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="../dashboard.php">🏠 Dashboard</a>
    <a href="../bimbingan_list.php">📄 Riwayat Bimbingan</a>
    <a href="dosen_list.php">👨‍🏫 Data Dosen</a>
    <a href="../mahasiswa/mahasiswa_list.php">🎓 Data Mahasiswa</a>
    <a href="#" onclick="logoutAdmin()">🚪 Logout</a>
</div>


<!-- MAIN -->
<div class="main">
    <a href="dosen_list.php" class="backbtn">⬅ Kembali</a>
    <h2>Detail Dosen</h2>

    <!-- Detail Dosen -->
    <div class="card">
        <h3>Informasi Dosen</h3>

        <p><b>Nama:</b> <span id="namaDosen"></span></p>
        <p><b>NIP:</b> <span id="nipDosen"></span></p>
        <p><b>Email:</b> <span id="emailDosen"></span></p>
        <p><b>Prodi:</b> <span id="prodiDosen"></span></p>
    </div>

    <!-- Mahasiswa Bimbingan -->
    <div class="card">
        <h3>Mahasiswa Bimbingan</h3>
        <p><b>Total:</b> <span id="jumlahBimbingan">0</span></p>

        <table>
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Judul TA</th>
                    <th>Status</th>
                    <th>Tanggal Submit</th>
                </tr>
            </thead>
            <tbody id="listBimbingan"></tbody>
        </table>
    </div>
</div>


<!-- FIREBASE -->
<script type="module">

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { 
    getFirestore,
    doc,
    getDoc,
    collection,
    query,
    where,
    getDocs
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

import { 
    getAuth,
    signOut
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

// FIREBASE CONFIG
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


// ====================================================
// 📌 Ambil ID dosen dari URL
// ====================================================
const urlParams = new URLSearchParams(window.location.search);
const dosenId = urlParams.get("id");

if (!dosenId) {
    alert("ID dosen tidak ditemukan!");
    window.location.href = "dosen_list.php";
}


// ====================================================
// 📌 LOAD DETAIL DOSEN
// ====================================================
async function loadDosenDetail() {
    const docRef = doc(db, "dosen", dosenId);
    const snap = await getDoc(docRef);

    if (!snap.exists()) {
        alert("Data dosen tidak ditemukan!");
        return;
    }

    let d = snap.data();

    document.getElementById("namaDosen").innerText  = d.nama;
    document.getElementById("nipDosen").innerText   = d.nip;
    document.getElementById("emailDosen").innerText = d.email;
    document.getElementById("prodiDosen").innerText = d.prodi;
}


// ====================================================
// 📌 LOAD MAHASISWA BIMBINGAN (dari topik_TA)
// ====================================================
async function loadMahasiswaBimbingan() {

    const q1 = query(
        collection(db, "topik_TA"),
        where("dosen_id", "==", dosenId)
    );

    const results = await getDocs(q1);

    let listBody = document.getElementById("listBimbingan");
    listBody.innerHTML = "";

    let count = 0;

    for (let t of results.docs) {
        let dataTA = t.data();

        // Ambil detail mahasiswa
        let mRef = doc(db, "mahasiswa", dataTA.mahasiswa_id);
        let mSnap = await getDoc(mRef);

        let m = mSnap.exists() ? mSnap.data() : { nama: "Tidak ditemukan" };

        listBody.innerHTML += `
            <tr>
                <td>${m.nama}</td>
                <td>${dataTA.judul}</td>
                <td>${dataTA.status ? "✔ Valid" : "❌ Belum Valid"}</td>
                <td>${dataTA.tanggal_submit}</td>
            </tr>
        `;

        count++;
    }

    document.getElementById("jumlahBimbingan").innerText = count;
}


loadDosenDetail();
loadMahasiswaBimbingan();


// ====================================================
// 🔴 LOGOUT
// ====================================================
window.logoutAdmin = function () {
    signOut(auth).then(() => {
        window.location.href = "../../index.php";
    });
};

</script>

</body>
</html>