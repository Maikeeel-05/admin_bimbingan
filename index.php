<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Bimbingan - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 350px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 5px;
            border: 1px solid #999;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .switch {
            text-align: center;
            margin-top: 10px;
        }
        .switch a {
            color: #007bff;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- LOGIN FORM -->
    <div id="loginBox">
        <h2>Login Admin</h2>
        <input type="email" id="loginEmail" placeholder="Email Admin">
        <input type="password" id="loginPassword" placeholder="Password">
        <button onclick="loginAdmin()">Login</button>

        <div class="switch">
            Belum punya akun? <a onclick="showRegister()">Register</a>
        </div>
    </div>

    <!-- REGISTER FORM -->
    <div id="registerBox" style="display:none;">
        <h2>Register Admin</h2>
        <input type="text" id="regNama" placeholder="Nama Admin">
        <input type="email" id="regEmail" placeholder="Email Admin">
        <input type="password" id="regPassword" placeholder="Password">
        <button onclick="registerAdmin()">Daftar</button>

        <div class="switch">
            Sudah punya akun? <a onclick="showLogin()">Login</a>
        </div>
    </div>
</div>

<!-- FIREBASE SDK -->
<script type="module">

// =====================================
// 🔥 IMPORT FIREBASE MODULAR SDK
// =====================================
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { 
    getAuth, 
    signInWithEmailAndPassword,
    createUserWithEmailAndPassword 
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

import { 
    getFirestore,
    setDoc,
    doc 
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

// =====================================
// 🔥 KONFIGURASI FIREBASE
// =====================================
const firebaseConfig = {
    apiKey: "AIzaSyB2myHLdJ15MNh0dij7yjF6dpEVehBkN-s",
    authDomain: "bimbinganskripsi-f9540.firebaseapp.com",
    projectId: "bimbinganskripsi-f9540",
    storageBucket: "bimbinganskripsi-f9540.firebasestorage.app",
    messagingSenderId: "801351593012",
    appId: "1:801351593012:web:6b2540f69f49d322e42e61",
    measurementId: "G-1H0X2L2HK1"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const db   = getFirestore(app);

// =====================================
// 🔐 LOGIN ADMIN
// =====================================
window.loginAdmin = function() {
    let email = document.getElementById("loginEmail").value;
    let pass  = document.getElementById("loginPassword").value;

    signInWithEmailAndPassword(auth, email, pass)
    .then(user => {
        window.location.href = "pages/dashboard.php";
    })
    .catch(err => {
        alert("Login gagal: " + err.message);
    });
}

// =====================================
// 📝 REGISTER ADMIN
// =====================================
window.registerAdmin = function() {
    let nama = document.getElementById("regNama").value;
    let email = document.getElementById("regEmail").value;
    let pass  = document.getElementById("regPassword").value;

    createUserWithEmailAndPassword(auth, email, pass)
    .then(user => {
        let uid = user.user.uid;

        // Simpan detail admin ke Firestore
        return setDoc(doc(db, "admin", uid), {
            nama: nama,
            email: email,
            role: "admin",
            createdAt: new Date()
        });
    })
    .then(() => {
        alert("Akun admin berhasil dibuat.");
        showLogin();
    })
    .catch(err => {
        alert("Gagal daftar: " + err.message);
    });
}

// =====================================
// 🔄 SWITCH FORM LOGIN / REGISTER
// =====================================
window.showRegister = function() {
    document.getElementById("loginBox").style.display = "none";
    document.getElementById("registerBox").style.display = "block";
}

window.showLogin = function() {
    document.getElementById("loginBox").style.display = "block";
    document.getElementById("registerBox").style.display = "none";
}

</script>

</body>
</html>