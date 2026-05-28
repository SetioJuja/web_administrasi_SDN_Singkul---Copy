<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>@yield('title', 'SiAkad')</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

/* =========================================================
   RESET
========================================================= */
*,
*::before,
*::after{
    box-sizing:border-box;
    margin:0;
    padding:0;
}

html,
body{
    height:100%;
}

body{
    font-family:'Segoe UI', Arial, sans-serif;
    display:flex;
    background:#f0f4f9;
    color:#1a2a4a;
    font-size:13px;
    overflow-x:hidden;
}

/* =========================================================
   SIDEBAR
========================================================= */
.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    top:0;
    left:0;

    display:flex;
    flex-direction:column;

    background:linear-gradient(180deg,#17345f 0%, #1d4379 100%);

    box-shadow:4px 0 20px rgba(0,0,0,0.08);

    z-index:100;

    overflow:hidden;
}

/* =========================================================
   HEADER
========================================================= */
.sidebar-header{
    padding:18px 15px 14px;
    border-bottom:1px solid rgba(255,255,255,0.08);

    flex-shrink:0;
}

.sidebar-brand{
    display:flex;
    align-items:center;
    gap:12px;

    margin-bottom:15px;

    text-decoration:none;
}

.sidebar-brand-icon{
    width:42px;
    height:42px;

    border-radius:10px;

    background:rgba(255,255,255,0.12);

    display:flex;
    align-items:center;
    justify-content:center;

    color:white;
    font-size:20px;

    flex-shrink:0;
}

.sidebar-brand-name{
    font-size:16px;
    font-weight:700;
    color:white;
    line-height:1.1;
}

.sidebar-brand-sub{
    font-size:10px;
    color:rgba(255,255,255,0.5);
    margin-top:2px;
    letter-spacing:.4px;
}

/* =========================================================
   USER
========================================================= */
.sidebar-user{
    display:flex;
    align-items:center;
    gap:10px;

    background:rgba(255,255,255,0.06);

    border-radius:10px;

    padding:10px;
}

.sidebar-user-av{
    width:34px;
    height:34px;

    border-radius:50%;

    background:rgba(255,255,255,0.15);

    display:flex;
    align-items:center;
    justify-content:center;

    color:white;

    font-size:12px;
    font-weight:700;

    flex-shrink:0;

    text-transform:uppercase;
}

.sidebar-user-name{
    font-size:12px;
    font-weight:600;
    color:white;

    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.sidebar-user-role{
    font-size:10px;
    color:rgba(255,255,255,0.45);

    margin-top:2px;

    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

/* =========================================================
   NAV
========================================================= */
.sidebar-nav{
    flex:1;

    min-height:0;

    overflow-y:auto;

    padding:10px;
}

/* SCROLLBAR */
.sidebar-nav::-webkit-scrollbar{
    width:5px;
}

.sidebar-nav::-webkit-scrollbar-track{
    background:transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb{
    background:rgba(255,255,255,0.18);
    border-radius:10px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover{
    background:rgba(255,255,255,0.28);
}

/* =========================================================
   MENU
========================================================= */
.menu-section{
    font-size:10px;
    font-weight:700;

    text-transform:uppercase;

    color:rgba(255,255,255,0.35);

    padding:14px 10px 6px;

    letter-spacing:1px;
}

.sidebar-nav a{
    display:flex;
    align-items:center;
    gap:12px;

    padding:11px 12px;

    border-radius:10px;

    color:rgba(255,255,255,0.78);

    text-decoration:none;

    margin-bottom:3px;

    transition:all .18s ease;

    font-size:13px;
    font-weight:500;
}

.sidebar-nav a:hover{
    background:rgba(255,255,255,0.09);

    color:white;

    transform:translateX(2px);
}

.sidebar-nav a.active{
    background:white;

    color:#1a3a6b;

    font-weight:600;

    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

.sidebar-nav a.active .nav-icon{
    color:#1a3a6b;
}

.nav-icon{
    width:20px;

    font-size:16px;

    display:flex;
    align-items:center;
    justify-content:center;

    flex-shrink:0;
}

/* =========================================================
   FOOTER
========================================================= */
.sidebar-footer{
    padding:10px;

    border-top:1px solid rgba(255,255,255,0.08);

    flex-shrink:0;
}

.sidebar-footer a{
    display:flex;
    align-items:center;
    gap:12px;

    padding:11px 12px;

    border-radius:10px;

    color:rgba(255,255,255,0.6);

    text-decoration:none;

    transition:.2s;

    font-size:13px;
    font-weight:500;
}

.sidebar-footer a:hover{
    background:rgba(255,0,0,0.12);
    color:#ffbaba;
}

/* =========================================================
   CONTENT
========================================================= */
.content{
    margin-left:240px;
    flex:1;
    min-height:100vh;
    min-width:0;
    overflow-x:auto;
    padding:24px;
    background:#f0f4f9;
}

/* =========================================================
   MODAL LOGOUT
========================================================= */
.modal-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.45);
    z-index:999;
    display:flex;
    align-items:center;
    justify-content:center;
    opacity:0;
    visibility:hidden;
    transition:opacity .22s ease, visibility .22s ease;
}

.modal-overlay.show{
    opacity:1;
    visibility:visible;
}

.modal-box{
    background:white;
    border-radius:16px;
    padding:32px 28px 24px;
    width:100%;
    max-width:360px;
    box-shadow:0 20px 60px rgba(0,0,0,0.18);
    text-align:center;
    transform:scale(.92) translateY(10px);
    transition:transform .22s ease;
}

.modal-overlay.show .modal-box{
    transform:scale(1) translateY(0);
}

.modal-icon{
    width:60px;
    height:60px;
    border-radius:50%;
    background:#fff3f3;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 16px;
    font-size:26px;
    color:#e03e3e;
}

.modal-title{
    font-size:16px;
    font-weight:700;
    color:#1a2a4a;
    margin-bottom:8px;
}

.modal-desc{
    font-size:13px;
    color:#6b7a99;
    line-height:1.6;
    margin-bottom:24px;
}

.modal-actions{
    display:flex;
    gap:10px;
}

.btn-cancel{
    flex:1;
    padding:10px;
    border-radius:10px;
    border:1.5px solid #dfe7f4;
    background:white;
    color:#1a2a4a;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:.18s;
}

.btn-cancel:hover{
    background:#f0f4f9;
}

.btn-logout{
    flex:1;
    padding:10px;
    border-radius:10px;
    border:none;
    background:linear-gradient(135deg,#e03e3e,#c0392b);
    color:white;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:.18s;
    box-shadow:0 4px 12px rgba(224,62,62,0.3);
}

.btn-logout:hover{
    background:linear-gradient(135deg,#c0392b,#a93226);
    box-shadow:0 6px 16px rgba(224,62,62,0.4);
}

/* =========================================================
   RESPONSIVE
========================================================= */
@media(max-width:768px){

    .sidebar{
        width:220px;
    }

    .content{
        margin-left:220px;
        padding:18px;
    }
}

</style>
</head>

<body>

<!-- =========================================================
     SIDEBAR
========================================================= -->
<div class="sidebar">

    <!-- HEADER -->
    <div class="sidebar-header">

        <a href="/" class="sidebar-brand">

            <div class="sidebar-brand-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div>

                <div class="sidebar-brand-name">
                    SIMAG-S
                </div>

                <div class="sidebar-brand-sub">
                    Sistem Manajemen Administrasi Guru SDN Singkul
                </div>

            </div>

        </a>

        <div class="sidebar-user">

            <div class="sidebar-user-av" id="user-av">
                ??
            </div>

            <div style="min-width:0">

                <div class="sidebar-user-name" id="user-nama">
                    -
                </div>

                <div class="sidebar-user-role" id="user-role">
                    -
                </div>

            </div>

        </div>

    </div>

    <!-- MENU -->
    <div class="sidebar-nav" id="menu"></div>

    <!-- FOOTER -->
    <div class="sidebar-footer">

        <a href="#" onclick="showLogoutModal()">

            <span class="nav-icon">
                <i class="bi bi-box-arrow-right"></i>
            </span>

            Logout

        </a>

    </div>

</div>

<!-- =========================================================
     CONTENT
========================================================= -->
<div class="content">
    @yield('content')
</div>

<!-- =========================================================
     MODAL LOGOUT
========================================================= -->
<div class="modal-overlay" id="logoutModal" onclick="handleOverlayClick(event)">

    <div class="modal-box">

        <div class="modal-icon">
            <i class="bi bi-box-arrow-right"></i>
        </div>

        <div class="modal-title">Konfirmasi Logout</div>

        <div class="modal-desc">
            Apakah Anda yakin ingin keluar?<br>
            Sesi Anda akan diakhiri.
        </div>

        <div class="modal-actions">

            <button class="btn-cancel" onclick="hideLogoutModal()">
                Batal
            </button>

            <button class="btn-logout" onclick="doLogout()">
                <i class="bi bi-box-arrow-right"></i>
                Ya, Logout
            </button>

        </div>

    </div>

</div>

<!-- =========================================================
     SCRIPT
========================================================= -->
<script>

// ================= AUTH =================
let user = JSON.parse(localStorage.getItem('user'));

if (!user) {

    alert('Harus login dulu');

    window.location.href = '/login';
}

// ================= ROLE =================
let roles = (user.roles || []).map(r =>
    r.trim().toLowerCase()
);

function hasRole(role) {
    return roles.includes(role.toLowerCase());
}

function hasAnyRole(list) {
    return list.some(r => hasRole(r));
}

// ================= AUTO REDIRECT DASHBOARD =================
const currentPath = window.location.pathname;

if(
    currentPath === '/' ||
    currentPath === '/dashboard'
){

    let target = '/lihat_pengumuman';

    if(currentPath !== target){

        window.location.replace(target);
    }
}

// ================= USER =================
document.getElementById('user-nama').innerText =
    user.nama || '-';

document.getElementById('user-role').innerText =
    (user.roles || []).join(', ') || '-';

const words =
    (user.nama || 'U').trim().split(' ');

document.getElementById('user-av').innerText =
    words.length >= 2
        ? words[0][0] + words[1][0]
        : words[0].slice(0,2);

// ================= MENU =================
function loadMenu(){

    let html = '';

    function section(title){

        html += `
            <div class="menu-section">
                ${title}
            </div>
        `;
    }

    function add(label, url, icon){

        const active =
            location.pathname === url
                ? 'active'
                : '';

        html += `
            <a href="${url}" class="${active}">
                <span class="nav-icon">
                    <i class="${icon}"></i>
                </span>

                ${label}
            </a>
        `;
    }

    // =====================================================
    // OPERATOR
    // =====================================================
    if(hasRole('operator')){

        section('Data Master');

        add('Data Pegawai',
            '/pegawai',
            'bi bi-people-fill');

        add('Data Jabatan',
            '/jabatan',
            'bi bi-person-badge-fill');

        add('Data Mapel',
            '/mapel',
            'bi bi-book-fill');

        add('Tahun Ajaran',
            '/tahun_ajaran',
            'bi bi-calendar-event-fill');

        add('Data Kelas',
            '/kelas',
            'bi bi-building');

        add('Data Dokumen',
            '/dokumen',
            'bi bi-folder-fill');

        add('Landing Page',
            '/konten_umum',
            'bi bi-window');

        section('Akademik');

        add('Jadwal Mengajar',
            '/jadwal_mengajar',
            'bi bi-calendar-week-fill');

        add('Status Presensi',
            '/status_presensi',
            'bi bi-clipboard-data');

        add('Presensi Guru',
            '/presensi_guru',
            'bi bi-check2-square');

        add('Kelola Pengumuman',
            '/pengumuman',
            'bi bi-megaphone-fill');
    }

    // =====================================================
    // GURU
    // =====================================================
    if(hasAnyRole(['kelas','mapel', 'operator', 'kepala'])){

        section('Saya');

        add('Pengumuman',
            '/lihat_pengumuman',
            'bi bi-megaphone-fill');

        add('Presensi Saya',
            '/lihat_presensi_me',
            'bi bi-receipt');
    }

    // =====================================================
    // WALI KELAS
    // =====================================================
    if(hasRole('kelas')){

        section('Wali Kelas');

        add('Data Siswa',
            '/siswa',
            'bi bi-mortarboard-fill');

        add('Presensi Siswa',
            '/presensi_siswa',
            'bi bi-clipboard-check-fill');

        add('Data Nilai',
            '/dsnilai',
            'bi bi-bar-chart-fill');
    }

    // =====================================================
    // MAPEL
    // =====================================================
    if(hasRole('mapel')){

        section('Mengajar');

        add('Input Nilai',
            '/snilai',
            'bi bi-pencil-square');

        add('Komponen Penilaian',
            '/mkomponen',
            'bi bi-gear-fill');

        add('Jadwal Mengajar',
            '/lihat_jadwal',
            'bi bi-calendar2-week-fill');

        add('Tugas',
            '/stugas',
            'bi bi-file-earmark-text-fill');
    }

    // =====================================================
    // KEPSEK
    // =====================================================
    if(hasAnyRole([
        'kepala',
        'kepsek',
        'kepala sekolah'
    ])){

        section('Kepala Sekolah');

        add('Kelola Presensi Guru',
            '/presensi_guru',
            'bi bi-bar-chart-fill');

        add('Monitoring Presensi Guru',
            '/sp_guru',
            'bi bi-graph-up-arrow');

        add('Dokumen',
            '/sdokumen',
            'bi bi-folder-fill');

        add('Data Pegawai',
            '/sguru',
            'bi bi-people-fill');

        add('Kelola Pengumuman',
            '/pengumuman',
            'bi bi-megaphone-fill');
    }

    document.getElementById('menu').innerHTML =
        html;
}

// ================= MODAL LOGOUT =================
function showLogoutModal(){
    document.getElementById('logoutModal').classList.add('show');
}

function hideLogoutModal(){
    document.getElementById('logoutModal').classList.remove('show');
}

function handleOverlayClick(e){
    if(e.target === document.getElementById('logoutModal')){
        hideLogoutModal();
    }
}

// tutup modal dengan tombol Escape
document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
        hideLogoutModal();
    }
});

function doLogout(){
    localStorage.removeItem('user');
    window.location.href = '/login';
}

// ================= LOGOUT (lama — tidak dipakai) =================
function logout(){
    showLogoutModal();
}

loadMenu();

</script>

@yield('script')

</body>
</html>