<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List Bimbingan</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }
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
        .container {
            margin-left: 220px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #1e3a8a;
            color: white;
        }
        .status-true {
            color: green;
            font-weight: bold;
        }
        .status-false {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="bimbingan_list.php">📄 Riwayat Bimbingan</a>
    <a href="dosen/dosen_list.php">👨‍🏫 Data Dosen</a>
    <a href="mahasiswa/mahasiswa_list.php">🎓 Data Mahasiswa</a>
    <a href="#" onclick="logoutAdmin()">🚪 Logout</a>
</div>

<div class="container">
    <h2>📄 Daftar Riwayat Bimbingan</h2>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Mahasiswa ID</th>
                <th>Dosen ID</th>
                <th>Catatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="bimbinganTable">
            <tr>
                <td colspan="5" style="text-align:center;">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- FIREBASE SDK -->
<script type="module">

// 🔥 Import Firebase Modular SDK
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { 
    getAuth, 
    onAuthStateChanged 
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

import { 
    getFirestore,
    collection,
    getDocs 
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

// 🔥 Firebase Config
const firebaseConfig = {
    apiKey: "AIzaSyB2myHLdJ15MNh0dij7yjF6dpEVehBkN-s",
    authDomain: "bimbinganskripsi-f9540.firebaseapp.com",
    projectId: "bimbinganskripsi-f9540",
    storageBucket: "bimbinganskripsi-f9540.firebasestorage.app",
    messagingSenderId: "801351593012",
    appId: "1:801351593012:web:6b2540f69f49d322e42e61",
    measurementId: "G-1H0X2L2HK1"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const db   = getFirestore(app);


// ===============================
// 🟦 CEK LOGIN
// ===============================
onAuthStateChanged(auth, (user) => {
    if (!user) {
        window.location.href = "../index.php";
        return;
    }

    loadBimbinganList();
});


// ===============================
// 📄 LOAD LIST BIMBINGAN
// ===============================
async function loadBimbinganList() {

    const tableBody = document.getElementById("bimbinganTable");
    tableBody.innerHTML = "<tr><td colspan='5' style='text-align:center;'>Loading data...</td></tr>";

    const querySnapshot = await getDocs(collection(db, "riwayat"));

    if (querySnapshot.empty) {
        tableBody.innerHTML = "<tr><td colspan='5' style='text-align:center;'>Tidak ada data bimbingan.</td></tr>";
        return;
    }

    tableBody.innerHTML = "";

    querySnapshot.forEach(docData => {
        const data = docData.data();

        let row = `
            <tr>
                <td>${data.tanggal}</td>
                <td>${data.mahasiwa_id}</td>
                <td>${data.dosen_id}</td>
                <td>${data.catatan}</td>
                <td class="${data.status ? 'status-true' : 'status-false'}">
                    ${data.status ? "Selesai" : "Belum"}
                </td>
            </tr>
        `;

        tableBody.innerHTML += row;
    });
}

</script>

</body>
</html>
