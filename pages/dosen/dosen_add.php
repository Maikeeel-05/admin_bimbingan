<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Dosen</title>
    <style>
        body { margin: 0; font-family: Arial; background: #f4f4f4; }
        .sidebar {
            width: 220px; height: 100vh; background: #1e3a8a;
            color: white; position: fixed; padding-top: 20px;
        }
        .sidebar a { color: white; display: block; padding: 12px 20px; text-decoration: none; }
        .sidebar a:hover { background: #0f2167; }
        .main { margin-left: 220px; padding: 20px; }
        h2 { margin-bottom: 15px; }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 0 5px rgba(0,0,0,0.15);
        }

        input, select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }

        .btn-save {
            width: 100%;
            padding: 12px;
            background: #1e40af;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-save:hover {
            background: #16328c;
        }
        .btn-back {
            display: inline-block;
            margin-bottom: 15px;
            color: #1e40af;
            text-decoration: none;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>ADMIN</h2>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="bimbingan_list.php">📄 Riwayat Bimbingan</a>
    <a href="dosen_list.php">👨‍🏫 Data Dosen</a>
    <a href="#" onclick="logoutAdmin()">🚪 Logout</a>
</div>

<!-- MAIN -->
<div class="main">
    <a class="btn-back" href="dosen_list.php">⬅ Kembali ke Data Dosen</a>
    <h2>Tambah Dosen Baru</h2>

    <div class="card">
        <input type="text" id="nama" placeholder="Nama Dosen">
        <input type="text" id="nip" placeholder="NIP">
        <input type="email" id="email" placeholder="Email Dosen">

        <select id="prodi">
            <option value="">-- Pilih Prodi --</option>
            <option value="Teknik Informatika">Teknik Informatika</option>
            <option value="Teknik Elektro">Teknik Elektro</option>
            <option value="Teknik Arsitektur">Teknik Arsitektur</option>
            <option value="Teknik Sipil">Teknik Sipil</option>
        </select>

        <button class="btn-save" onclick="simpanDosen()">Simpan</button>
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
//  SIMPAN DOSEN KE FIRESTORE
// ======================================================
window.simpanDosen = async function() {

    let nama = document.getElementById("nama").value.trim();
    let nip  = document.getElementById("nip").value.trim();
    let email = document.getElementById("email").value.trim();
    let prodi = document.getElementById("prodi").value;

    if (!nama || !nip || !email || !prodi) {
        alert("Semua field wajib diisi!");
        return;
    }

    try {
        await addDoc(collection(db, "dosen"), {
            nama: nama,
            nip: nip,
            email: email,
            prodi: prodi
        });

        alert("Dosen berhasil ditambahkan!");
        window.location.href = "dosen_list.php";

    } catch (err) {
        console.error(err);
        alert("Gagal menyimpan data dosen.");
    }
};


// LOGOUT ADMIN
window.logoutAdmin = function() {
    signOut(auth).then(() => {
        window.location.href = "../index.php";
    });
};

</script>

</body>
</html>