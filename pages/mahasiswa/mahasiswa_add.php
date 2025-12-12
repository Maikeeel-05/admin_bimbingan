<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mahasiswa</title>
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
            background: white; padding: 20px; width: 450px;
            border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, select {
            width: 100%; padding: 10px; margin-top: 8px;
            border: 1px solid #aaa; border-radius: 5px;
        }
        button {
            margin-top: 15px; padding: 10px; width: 100%;
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
    <a href="../dashboard.php">🏠 Dashboard</a>
    <a href="../bimbingan_list.php">📄 Riwayat Bimbingan</a>
    <a href="../dosen/dosen_list.php">👨‍🏫 Data Dosen</a>
    <a href="mahasiswa_list.php">🎓 Data Mahasiswa</a>
    <a href="#" onclick="logoutAdmin()">🚪 Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="main">
    <h2>Tambah Mahasiswa</h2>

    <div class="container">
        <label>Nama Mahasiswa</label>
        <input type="text" id="nama" placeholder="Nama lengkap">

        <label>Email</label>
        <input type="email" id="email" placeholder="Email mahasiswa">

        <label>Program Studi</label>
        <select id="prodi">
            <option value="">-- Pilih Prodi --</option>
            <option value="Teknik Informatika">Teknik Informatika</option>
            <option value="Teknik Elektro">Teknik Elektro</option>
            <option value="Teknik Arsitektur">Teknik Arsitektur</option>
            <option value="Teknik Sipil">Teknik Sipil</option>
        </select>

        <label>Angkatan</label>
        <input type="text" id="angkatan" placeholder="Contoh: 2022">

        <button onclick="tambahMahasiswa()">Tambah Mahasiswa</button>
    </div>
</div>


<!-- FIREBASE -->
<script type="module">

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { 
    getFirestore,
    collection,
    addDoc
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
// 🟢 TAMBAH MAHASISWA (versi tanpa dosen pembimbing)
// ======================================================
window.tambahMahasiswa = async function () {
    let nama = document.getElementById("nama").value;
    let email = document.getElementById("email").value;
    let prodi = document.getElementById("prodi").value;
    let angkatan = document.getElementById("angkatan").value;

    if (!nama || !email || !prodi || !angkatan) {
        alert("Semua field harus diisi!");
        return;
    }

    await addDoc(collection(db, "mahasiswa"), {
        nama,
        email,
        prodi,
        angkatan,
        dosenPembimbingId: "",  // sekarang kosong, mahasiswa isi sendiri lewat aplikasi
        topikId: ""             // default kosong
    });

    alert("Mahasiswa berhasil ditambahkan!");
    window.location.href = "mahasiswa_list.php";
};


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
