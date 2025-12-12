<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>
    <style>
        body { margin: 0; font-family: Arial; background: #f4f4f4; }
        .sidebar {
            width: 220px; height: 100vh;
            background: #1e3a8a; color: white;
            position: fixed; padding-top: 20px;
        }
        .sidebar a {
            color: white; padding: 12px 20px; display: block; text-decoration:none;
        }
        .sidebar a:hover { background: #0f2167; }

        .main {
            margin-left: 220px; padding: 20px;
        }

        .btn-add {
            padding: 10px 15px;
            background: #1e40af;
            color: white; border: none;
            border-radius: 6px; cursor: pointer;
            text-decoration: none;
        }
        .btn-add:hover { background: #16328c; }

        table {
            width: 100%; border-collapse: collapse;
            background: white; margin-top: 20px;
            border-radius: 10px; overflow: hidden;
            box-shadow: 0 0 5px rgba(0,0,0,0.15);
        }
        th, td {
            padding: 12px; border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th { background: #1e3a8a; color: white; }
        tr:hover { background: #f1f5ff; }

        .btn-detail {
            padding: 6px 12px;
            background: #2563eb; color: white;
            border-radius: 5px; text-decoration: none;
        }
        .btn-detail:hover { background: #1d4ed8; }
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
    <h2>Data Mahasiswa</h2>

    <a href="mahasiswa_add.php" class="btn-add">+ Tambah Mahasiswa</a>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Prodi</th>
                <th>Angkatan</th>
                <th>Pembimbing</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="mahasiswaTable">
            <tr><td colspan="6">Memuat data...</td></tr>
        </tbody>
    </table>
</div>


<!-- FIREBASE -->
<script type="module">

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { 
    getFirestore,
    collection,
    getDocs
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
//    AMBIL DATA MAHASISWA
// ======================================================
async function loadMahasiswa() {
    const tbody = document.getElementById("mahasiswaTable");
    tbody.innerHTML = "<tr><td colspan='6'>Loading...</td></tr>";

    const getMhs = await getDocs(collection(db, "mahasiswa"));

    let html = "";

    getMhs.forEach(doc => {
        let m = doc.data();

        html += `
            <tr>
                <td>${m.nama}</td>
                <td>${m.email}</td>
                <td>${m.prodi}</td>
                <td>${m.angkatan}</td>
                <td>${m.dosenPembimbingId || "-"}</td>

                <td>
                    <a class="btn-detail" href="mahasiswa_detail.php?id=${doc.id}">
                        Detail
                    </a>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html || "<tr><td colspan='6'>Belum ada data mahasiswa.</td></tr>";
}

loadMahasiswa();


// ======================================================
//  LOGOUT ADMIN
// ======================================================
window.logoutAdmin = function() {
    signOut(auth).then(() => {
        window.location.href = "../index.php";
    });
};

</script>

</body>
</html>