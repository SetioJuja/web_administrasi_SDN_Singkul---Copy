<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Pegawai</title>

<style>
:root {
  --navy:        #0a1f44;
  --blue-dark:   #1140a6;
  --blue-mid:    #1e5ccc;
  --blue:        #2d7fe0;
  --blue-soft:   #5ba3f0;
  --blue-light:  #ddeeff;
  --blue-pale:   #f0f6ff;
  --white:       #ffffff;
  --ink:         #0d1f3c;
  --muted:       #5a7393;
  --border:      rgba(29,92,204,0.10);
  --shadow:      0 10px 40px rgba(29,92,204,0.15);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    height: 100vh;
    background: linear-gradient(135deg, var(--navy), var(--blue-mid));
    display: flex;
    align-items: center;
    justify-content: center;
}

/* CONTAINER */
.container {
    width: 950px;
    height: 520px;
    background: var(--white);
    border-radius: 24px;
    display: flex;
    overflow: hidden;
    box-shadow: var(--shadow);
}

/* LEFT PANEL */
.left {
    width: 45%;
    background: linear-gradient(180deg, var(--blue-dark), var(--blue));
    color: white;
    padding: 50px;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.left h1 {
    font-size: 26px;
    margin-bottom: 15px;
}

.left p {
    font-size: 14px;
    opacity: 0.85;
}

/* WAVE EFFECT */
.left::after {
    content: "";
    position: absolute;
    right: -70px;
    top: 0;
    width: 140px;
    height: 100%;
    background: var(--white);
    border-radius: 50%;
}

/* RIGHT PANEL */
.right {
    width: 55%;
    padding: 60px;
    background: var(--white);
}

.right h2 {
    margin-bottom: 30px;
    color: var(--ink);
}

/* INPUT */
.input-group {
    margin-bottom: 18px;
}

.input-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--border);
    border-radius: 10px;
    outline: none;
    background: var(--blue-pale);
    transition: 0.3s;
}

.input-group input:focus {
    border-color: var(--blue);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(45,127,224,0.15);
}

/* BUTTON */
button {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, var(--blue-mid), var(--blue-soft));
    color: white;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}

button:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(45,127,224,0.3);
}

/* MESSAGE */
.msg {
    margin-top: 12px;
    text-align: center;
    font-size: 14px;
}
.error { color: red; }
.success { color: green; }

/* FOOT TEXT */
.footer-text {
    margin-top: 20px;
    text-align: center;
    font-size: 12px;
    color: var(--muted);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
        width: 92%;
        height: auto;
    }

    .left {
        width: 100%;
        height: 180px;
    }

    .left::after {
        display: none;
    }

    .right {
        width: 100%;
        padding: 30px;
    }
}
</style>

</head>

<body>

<div class="container">

    <!-- LEFT -->
    <div class="left">
        <h1>Selamat Datang 👋</h1>
        <p>Sistem Administrasi Guru Sekolah Dasar</p>
    </div>

    <!-- RIGHT -->
    <div class="right">
        <h2>Login Pegawai</h2>

        <div class="input-group">
            <input type="text" id="nip" placeholder="NIP">
        </div>

        <div class="input-group">
            <input type="password" id="password" placeholder="Password">
        </div>

        <button onclick="login()">Masuk</button>

        <div id="msg" class="msg"></div>

        <div class="footer-text">
            © 2026 Sistem Sekolah
        </div>
    </div>

</div>

<script>
function login() {

    const nip = document.getElementById('nip').value;
    const password = document.getElementById('password').value;
    const msg = document.getElementById('msg');

    msg.innerHTML = 'Loading...';
    msg.className = 'msg';

    fetch('/api/login', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ nip, password })
    })
    .then(res => res.json())
    .then(res => {

        if(res.success && res.data){

            const user = {
                id: res.data.id_guru || res.data.id,
                nama: res.data.nama,
                nip: res.data.nip,
                roles: res.data.roles || []
            };

            localStorage.setItem('user', JSON.stringify(user));

            msg.innerHTML = 'Login berhasil';
            msg.classList.add('success');

            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 800);

        } else {
            msg.innerHTML = res.message || 'Login gagal';
            msg.classList.add('error');
        }

    })
    .catch(() => {
        msg.innerHTML = 'Server error';
        msg.classList.add('error');
    });
}
</script>

</body>
</html>