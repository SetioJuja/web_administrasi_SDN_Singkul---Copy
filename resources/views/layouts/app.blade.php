<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'SiAkad')</title>

<style>

/* ================= RESET ================= */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Segoe UI', Arial, sans-serif;
    display: flex;
    background: #f0f4f9;
    min-height: 100vh;
    color: #1a2a4a;
    font-size: 13px;
}

/* ============================================================
   SIDEBAR
============================================================ */
.sidebar {
    width: 235px;
    background: #1a3a6b;
    min-height: 100vh;
    position: fixed;
    top: 0; left: 0;
    display: flex;
    flex-direction: column;
    z-index: 100;
}

/* --- Header: Logo & User --- */
.sidebar-header {
    padding: 18px 14px 14px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    flex-shrink: 0;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
    text-decoration: none;
}

.sidebar-brand-icon {
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.12);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.sidebar-brand-name {
    font-size: 15px;
    font-weight: 700;
    color: white;
    line-height: 1.2;
    letter-spacing: 0.2px;
}

.sidebar-brand-sub {
    font-size: 10px;
    color: rgba(255,255,255,0.45);
    margin-top: 2px;
    letter-spacing: 0.1px;
}

.sidebar-user {
    display: flex;
    align-items: center;
    gap: 9px;
    background: rgba(0,0,0,0.15);
    border-radius: 6px;
    padding: 8px 10px;
}

.sidebar-user-av {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
    text-transform: uppercase;
}

.sidebar-user-name {
    font-size: 12px;
    font-weight: 600;
    color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar-user-role {
    font-size: 10px;
    color: rgba(255,255,255,0.45);
    margin-top: 1px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* --- Navigasi --- */
.sidebar-nav {
    flex: 1;
    padding: 8px 10px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.1) transparent;
}

.sidebar-nav::-webkit-scrollbar { width: 3px; }
.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.12);
    border-radius: 2px;
}

.menu-section {
    font-size: 9.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(255,255,255,0.3);
    padding: 12px 8px 4px;
}

.sidebar-nav a {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 8px 10px;
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    border-radius: 5px;
    font-size: 12.5px;
    font-weight: 500;
    margin-bottom: 1px;
}

.sidebar-nav a:hover {
    background: rgba(255,255,255,0.08);
    color: white;
}

.sidebar-nav a.active {
    background: rgba(255,255,255,0.15);
    color: white;
    font-weight: 600;
    border-left: 3px solid #7eb3ff;
    padding-left: 7px;
}

.sidebar-nav a .nav-icon {
    font-size: 14px;
    flex-shrink: 0;
    width: 18px;
    text-align: center;
}

/* --- Footer: Logout --- */
.sidebar-footer {
    padding: 8px 10px 14px;
    border-top: 1px solid rgba(255,255,255,0.08);
    flex-shrink: 0;
}

.sidebar-footer a {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 8px 10px;
    border-radius: 5px;
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    font-size: 12.5px;
    font-weight: 500;
}

.sidebar-footer a:hover {
    background: rgba(200,30,30,0.15);
    color: #ffaaaa;
}

.sidebar-footer a .nav-icon {
    font-size: 14px;
    width: 18px;
    text-align: center;
    flex-shrink: 0;
}

/* ============================================================
   CONTENT
============================================================ */
.content {
    margin-left: 235px;
    flex: 1;
    padding: 24px;
    background: #f0f4f9;
    min-height: 100vh;
}

/* ============================================================
   CARD — dipakai halaman lain
============================================================ */
.card {
    background: white;
    padding: 16px 18px;
    border-radius: 8px;
    border: 1px solid #dde6f5;
    margin-top: 14px;
}

</style>
</head>

<body>

<!-- ============================================================
     SIDEBAR
============================================================ -->
<div class="sidebar">

    <div class="sidebar-header">
        <a href="/" class="sidebar-brand">
            <div class="sidebar-brand-icon">📚</div>
            <div>
                <div class="sidebar-brand-name">SiAkad</div>
                <div class="sidebar-brand-sub">Manajemen Sekolah</div>
            </div>
        </a>

        <div class="sidebar-user">
            <div class="sidebar-user-av" id="user-av">??</div>
            <div style="min-width:0">
                <div class="sidebar-user-name" id="user-nama">-</div>
                <div class="sidebar-user-role" id="user-role">-</div>
            </div>
        </div>
    </div>

    <div class="sidebar-nav" id="menu"></div>

    <div class="sidebar-footer">
        <a href="#" onclick="logout()">
            <span class="nav-icon">🚪</span> Logout
        </a>
    </div>

</div>

<!-- ============================================================
     CONTENT
============================================================ -->
<div class="content">
    @yield('content')
</div>


<!-- ============================================================
     SCRIPT
============================================================ -->
<script>

// ================= AUTH =================
let user = JSON.parse(localStorage.getItem('user'));

if (!user) {
    alert('Harus login dulu');
    window.location.href = '/login';
}

// ================= ROLE =================
let roles = (user.roles || []).map(r => r.trim().toLowerCase());

function hasRole(role) {
    return roles.includes(role.toLowerCase());
}

function hasAnyRole(list) {
    return list.some(r => hasRole(r));
}

// ================= USER INFO =================
document.getElementById('user-nama').innerText = user.nama || '-';
document.getElementById('user-role').innerText = (user.roles || []).join(', ') || '-';

const words = (user.nama || 'U').trim().split(' ');
document.getElementById('user-av').innerText = words.length >= 2
    ? words[0][0] + words[1][0]
    : words[0].slice(0, 2);

// ================= MENU =================
function loadMenu() {
    let html = '';

    function section(title) {
        html += `<div class="menu-section">${title}</div>`;
    }

    function add(label, url, icon) {
        const active = location.pathname === url ? 'active' : '';
        html += `<a href="${url}" class="${active}">
                    <span class="nav-icon">${icon}</span>${label}
                 </a>`;
    }

    // OPERATOR
    if (hasRole('operator')) {
        section('Data Master');
        add('Data Pegawai',    '/pegawai',         '👨‍💼');
        add('Data Jabatan',    '/jabatan',         '🏷️');
        add('Data Mapel',      '/mapel',           '📘');
        add('Tahun Ajaran',    '/tahun_ajaran',    '📅');
        add('Data Kelas',      '/kelas',           '🏫');
        add('Data Dokumen',    '/dokumen',         '📂');
        add('Landing Page',    '/konten_umum',     '📢');


        section('Akademik');
        add('Jadwal Mengajar', '/jadwal_mengajar', '🗓️');
        add('Presensi Guru',   '/presensi_guru',   '✅');
        add('Kelola Pengumuman',      '/pengumuman',      '📢');
    }

    // GURU
    if (hasAnyRole(['kelas', 'mapel'])) {
        section('Saya');
        add('Pengumuman',      '/lihat_pengumuman',  '📢');
        add('Presensi Saya',  '/lihat_presensi_me', '🧾');
    }

    // WALI KELAS
    if (hasRole('kelas')) {
        section('Wali Kelas');
        add('Data Siswa',      '/siswa',           '👨‍🎓');
        add('Presensi Siswa',  '/presensi_siswa',  '📋');
        add('Data Nilai',      '/dsnilai',         '📊');
    }

    // MAPEL
    if (hasRole('mapel')) {
        section('Mengajar');
        add('Input Nilai',        '/snilai',      '✏️');
        add('Komponen Penilaian', '/mkomponen',   '⚙️');
        add('Jadwal Mengajar',    '/lihat_jadwal','🗓️');
        add('Tugas',              '/stugas',      '📄');
    }

    // KEPSEK
    if (hasAnyRole(['kepala', 'kepsek', 'kepala sekolah'])) {
        section('Kepala Sekolah');
        add('Pengumuman',      '/lihat_pengumuman',  '📢');
        add('Kelola Presensi Guru', '/presensi_guru',  '📊');
        add('Monitoring Presensi Guru', '/sp_guru',  '📊');
        add('Dokumen',             '/sdokumen', '📁');
        add('Data Pegawai',           '/sguru',    '👨‍💼');
        add('Data Kelas', '/skelas', '🏫');
        add('Kelola Pengumuman',      '/pengumuman',      '📢');

    }

    document.getElementById('menu').innerHTML = html;
}

// ================= LOGOUT =================
function logout() {
    if (confirm('Yakin ingin logout?')) {
        localStorage.removeItem('user');
        window.location.href = '/login';
    }
}

loadMenu();

</script>

@yield('script')

</body>
</html>