<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Dosen</title>
    <style>
        body { margin: 0; font-family: Arial; background: #f4f4f4; }
        .sidebar {
            width: 220px;
            height: 100vh;
            background: #1e3a8a;
            color: white;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #0f2167;
        }
        .main { margin-left: 220px; padding: 20px; }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.15);
        }

        table {
            width: 100%; border-collapse: collapse; margin-top: 15px;
        }
        th, td {
            padding: 10px; border-bottom: 1px solid #ddd; text-align: left;
        }
        th { background: #1e40af; color: white; }

        .btn-add {
            display: inline-block;
            padding: 10px 15px;
            background: #1e40af;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
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
    <h2>Data Dosen</h2>

    <a class="btn-add" href="dosen_add.php">+ Tambah Dosen</a>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Email</th>
                    <th>Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="listDosen"></tbody>
        </table>
    </div>
</div>

<!-- FIREBASE -->
<script type="module">

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getFirestore, collection, getDocs } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
import { getAuth, signOut } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

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

// ===============================
//  LOAD DATA DOSEN
// ===============================
async function loadDosen() {
    const ref = collection(db, "dosen");
    const snap = await getDocs(ref);

    const tbody = document.getElementById("listDosen");
    tbody.innerHTML = "";

    snap.forEach(doc => {
        let d = doc.data();
        tbody.innerHTML += `
            <tr>
                <td>${d.nama}</td>
                <td>${d.nip}</td>
                <td>${d.email}</td>
                <td>${d.prodi}</td>
                <td>
                    <a href="dosen_detail.php?id=${doc.id}"
                       style="padding:6px 12px; background:#2563eb; color:white; border-radius:5px; text-decoration:none;">
                       Detail
                    </a>
                </td>
            </tr>
        `;
    });
}

loadDosen();

// LOGOUT
window.logoutAdmin = function() {
    signOut(auth).then(() => {
        window.location.href = "../index.php";
    });
};

</script>

</body>
</html>
