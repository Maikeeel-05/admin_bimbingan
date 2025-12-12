<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body {
            margin: 0;
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
        .main {
            margin-left: 220px;
            padding: 20px;
        }
        .topbar {
            background: white;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .logout-btn {
            padding: 8px 15px;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background: #b91c1c;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="bimbingan_list.php">📄 Riwayat Bimbingan</a>
    <a href="dosen/dosen_list.php">👨‍🏫 Data Dosen</a>
    <a href="mahasiswa_list.php">🎓 Data Mahasiswa</a>
    <a href="#" onclick="logoutAdmin()">🚪 Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="main">
    <div class="topbar">
        <h3>Selamat Datang, <span id="namaAdmin">Loading...</span></h3>
        <button class="logout-btn" onclick="logoutAdmin()">Logout</button>
    </div>

    <div style="margin-top:20px;">
        <h2>Dashboard Admin</h2>
        <p>Silakan pilih menu di sidebar untuk mengelola data bimbingan.</p>
    </div>

    <!-- 📘 RINGKASAN RIWAYAT BIMBINGAN -->
    <div style="margin-top:30px;">
        <h3>📘 Riwayat Bimbingan Terbaru</h3>

        <table border="1" width="100%" cellpadding="8" 
               style="background:white; border-collapse: collapse;">
            <thead>
                <tr style="background:#e5e7eb;">
                    <th>Mahasiswa</th>
                    <th>Dosen</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="listRiwayat">
                <tr><td colspan="4" style="text-align:center;">Loading...</td></tr>
            </tbody>
        </table>
    </div>

</div>


<!-- FIREBASE SDK -->
<script type="module">

// IMPORT FIREBASE
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { 
    getAuth, 
    onAuthStateChanged,
    signOut
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

import { 
    getFirestore,
    doc,
    getDoc,
    collection,
    getDocs,
    orderBy,
    query,
    limit
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";


// KONFIGURASI FIREBASE
const firebaseConfig = {
    apiKey: "AIzaSyB2myHLdJ15MNh0dij7yjF6dpEVehBkN-s",
    authDomain: "bimbinganskripsi-f9540.firebaseapp.com",
    projectId: "bimbinganskripsi-f9540",
    storageBucket: "bimbinganskripsi-f9540.firebasestorage.app",
    messagingSenderId: "801351593012",
    appId: "1:801351593012:web:6b2540f69f49d322e42e61",
    measurementId: "G-1H0X2L2HK1"
};

// Initialize
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const db   = getFirestore(app);


// ======================================================
// 🟦 CEK ADMIN YANG LOGIN
// ======================================================
onAuthStateChanged(auth, async (user) => {

    if (!user) {
        window.location.href = "../index.php";
        return;
    }

    const docRef = doc(db, "admin", user.uid);
    const snap = await getDoc(docRef);

    if (snap.exists()) {
        document.getElementById("namaAdmin").innerText = snap.data().nama;
    } else {
        document.getElementById("namaAdmin").innerText = "Admin Tidak Dikenal";
    }

    // 🔥 setelah admin terverifikasi → load data riwayat
    loadRiwayat();
});


// ======================================================
// 📘 FUNGSI AMBIL 5 RIWAYAT TERBARU
// ======================================================
async function loadRiwayat() {
    const tbody = document.getElementById("listRiwayat");
    tbody.innerHTML = "<tr><td colspan='4'>Loading...</td></tr>";

    const q = query(
        collection(db, "riwayat"),
        orderBy("tanggal", "desc"),
        limit(5)
    );

    const snapshot = await getDocs(q);

    if (snapshot.empty) {
        tbody.innerHTML = "<tr><td colspan='4' style='text-align:center;'>Belum ada data bimbingan.</td></tr>";
        return;
    }

    let html = "";

    snapshot.forEach(doc => {
        const d = doc.data();

        html += `
            <tr>
                <td>${d.mahasiwa_id || "-"}</td>
                <td>${d.dosen_id || "-"}</td>
                <td>${d.tanggal || "-"}</td>
                <td style="color:${d.status ? 'green':'red'};">
                    ${d.status ? "Selesai" : "Pending"}
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
}


// ======================================================
// 🟥 LOGOUT ADMIN
// ======================================================
window.logoutAdmin = function() {
    signOut(auth).then(() => {
        window.location.href = "../index.php";
    });
}

</script>

</body>
</html>
