<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Pegawai</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<style>

@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

:root{
    --primary:#4f63ff;
    --primary2:#6c7bff;
    --bg:#eef2ff;
    --white:#ffffff;
    --text:#202124;
    --muted:#7b7b7b;
    --border:#e5e7eb;
    --input:#f4f5f7;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:
        linear-gradient(
            135deg,
            #dbe7ff,
            #eef2ff
        );
    padding:20px;
}

/* ================= CARD ================= */

.login-card{
    width:1100px;
    min-height:650px;
    background:rgba(255,255,255,.9);
    backdrop-filter:blur(10px);
    border:1px solid rgba(255,255,255,.4);
    border-radius:40px;
    overflow:hidden;
    display:flex;
    box-shadow:
        0 20px 60px rgba(79,99,255,.12);
    transition:.3s;
}

.login-card:hover{
    transform:translateY(-3px);
}

/* ================= LEFT ================= */

.left{
    width:50%;
    padding:70px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    background:#fafafa;
}

.left h1{
    text-align:center;
    font-size:48px;
    margin-bottom:45px;
    color:var(--text);
    font-weight:700;
}

/* ================= FORM ================= */

.form-group{
    margin-bottom:24px;
}

.label-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
}

.label-row label{
    font-size:14px;
    color:#555;
    font-weight:600;
}

.input-box{
    position:relative;
}

.input-icon{
    position:absolute;
    left:18px;
    top:50%;
    transform:translateY(-50%);
    color:#9ca3af;
    font-size:16px;
}

.input-box input{
    width:100%;
    height:58px;
    border:none;
    outline:none;
    border-radius:14px;
    background:var(--input);
    padding:0 50px 0 50px;
    font-size:15px;
    transition:.25s;
}

.input-box input:focus{
    background:white;
    border:1.5px solid var(--primary);
    box-shadow:0 0 0 4px rgba(79,99,255,.08);
}

.eye{
    position:absolute;
    right:18px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:18px;
    color:#999;
    transition:.2s;
}

.eye:hover{
    color:var(--primary);
}

/* ================= BUTTON ================= */

.btn-login{
    width:100%;
    height:58px;
    border:none;
    border-radius:14px;
    background:linear-gradient(
        135deg,
        var(--primary),
        var(--primary2)
    );
    color:white;
    font-size:17px;
    font-weight:700;
    cursor:pointer;
    transition:.25s;
}

.btn-login:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 30px rgba(79,99,255,.22);
}

.btn-login:disabled{
    opacity:.8;
    cursor:not-allowed;
}

/* ================= MESSAGE ================= */

.msg{
    margin-top:18px;
    text-align:center;
    font-size:14px;
    font-weight:600;
}

.error{
    color:#ef4444;
}

.success{
    color:#22c55e;
}

/* ================= FOOTER ================= */

.bottom-text{
    margin-top:65px;
    text-align:center;
    font-size:14px;
    color:#666;
}

/* ================= RIGHT ================= */

.right{
    width:50%;
    background:
        linear-gradient(
            180deg,
            #f8f9ff,
            #eef2ff
        );
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    padding:50px;
    text-align:center;
}

.right img{
    width:75%;
    max-width:430px;
    margin-bottom:30px;
}

.right h2{
    font-size:34px;
    color:var(--text);
    margin-bottom:18px;
}

.right p{
    max-width:420px;
    line-height:1.8;
    color:var(--muted);
    font-size:15px;
}

/* ================= RESPONSIVE ================= */

@media(max-width:960px){

    .login-card{
        flex-direction:column;
        width:100%;
        max-width:550px;
    }

    .left,
    .right{
        width:100%;
    }

    .left{
        padding:45px 30px;
    }

    .right{
        padding:45px 30px;
    }

    .left h1{
        font-size:38px;
    }

    .right h2{
        font-size:28px;
    }
}

@media(max-width:500px){

    .left{
        padding:35px 22px;
    }

    .right{
        padding:35px 22px;
    }

    .left h1{
        font-size:32px;
    }
}

</style>
</head>

<body>

<div class="login-card">

    <!-- LEFT -->
    <div class="left">

        <h1>Login</h1>

        <!-- USERNAME -->
        <div class="form-group">

            <div class="label-row">
                <label>Username</label>
            </div>

            <div class="input-box">

                <i class="bi bi-person input-icon"></i>

                <input
                    type="text"
                    id="username"
                    placeholder="Masukkan username"
                >

            </div>

        </div>

        <!-- PASSWORD -->
        <div class="form-group">

            <div class="label-row">
                <label>Password</label>
            </div>

            <div class="input-box">

                <i class="bi bi-lock input-icon"></i>

                <input
                    type="password"
                    id="password"
                    placeholder="Masukkan password"
                >

                <span
                    class="eye bi bi-eye-fill"
                    id="toggleIcon"
                    onclick="togglePassword()"
                ></span>

            </div>

        </div>

        <!-- BUTTON -->
        <button
            class="btn-login"
            id="btnLogin"
            onclick="login()"
        >
            Login
        </button>

        <!-- MESSAGE -->
        <div id="msg" class="msg"></div>

        <!-- FOOTER -->
        <div class="bottom-text">
             © 2026 SD Negeri Singkul
        </div>

        <button
            onclick="location.href='/'"
            style="
                margin-top:10px;
                background:none;
                border:none;
                color:#0a3d62;
                font-size:14px;
                font-weight:600;
                cursor:pointer;
            "
        >
            Beranda
        </button>

    </div>

    <!-- RIGHT -->
    <div class="right">

        <img
            id="img-login"
            src=""
            alt="Education Illustration"
        >

        <h2>
            Sistem Administrasi SD Negeri Singkul
        </h2>

        <p>
            Kelola data guru, siswa, nilai,
            rapor, dan administrasi sekolah
            dengan tampilan modern dan mudah digunakan.
        </p>

    </div>

</div>

<script>

// ================= GAMBAR LOGIN =================

fetch('/api/konten-umum')

.then(res => res.json())

.then(res => {

    if(res.success && res.data){

        document.getElementById('img-login').src =

            res.data.gambar_login ||

            'https://cdn-icons-png.flaticon.com/512/4207/4207249.png';
    }
})

.catch(() => {

    document.getElementById('img-login').src =

        'https://cdn-icons-png.flaticon.com/512/4207/4207249.png';
});

// ================= PASSWORD =================

function togglePassword(){

    const password =
        document.getElementById('password');

    const icon =
        document.getElementById('toggleIcon');

    if(password.type === 'password'){

        password.type = 'text';

        icon.classList.remove('bi-eye-fill');
        icon.classList.add('bi-eye-slash-fill');

    }else{

        password.type = 'password';

        icon.classList.remove('bi-eye-slash-fill');
        icon.classList.add('bi-eye-fill');
    }
}

// ================= LOGIN =================

function login(){

    const username =
        document.getElementById('username').value;

    const password =
        document.getElementById('password').value;

    const msg =
        document.getElementById('msg');

    const btn =
        document.getElementById('btnLogin');

    btn.disabled = true;
    btn.innerHTML = 'Loading...';

    msg.innerHTML = 'Loading...';
    msg.className = 'msg';

    fetch('/api/login',{

        method:'POST',

        headers:{
            'Content-Type':'application/json'
        },

        body:JSON.stringify({
            username,
            password
        })

    })

    .then(res=>res.json())

    .then(res=>{

        if(res.success){

            localStorage.setItem(
                'user',
                JSON.stringify(res.data)
            );

            msg.innerHTML =
                'Login berhasil';

            msg.className =
                'msg success';

            setTimeout(()=>{

                window.location =
                    '/dashboard';

            },800);

        }else{

            msg.innerHTML =
                res.message || 'Login gagal';

            msg.className =
                'msg error';

            btn.disabled = false;
            btn.innerHTML = 'Login';
        }

    })

    .catch(()=>{

        msg.innerHTML =
            'Server error';

        msg.className =
            'msg error';

        btn.disabled = false;
        btn.innerHTML = 'Login';
    });
}

</script>

</body>
</html>