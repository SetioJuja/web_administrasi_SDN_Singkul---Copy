<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SD Negeri Singkul — Sistem Administrasi</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --navy:       #05122b;
  --navy2:      #0b2150;
  --blue:       #1547c0;
  --blue-mid:   #1e5ccc;
  --blue-soft:  #4a85e8;
  --sky:        #c8dcff;
  --pale:       #f0f5ff;
  --white:      #ffffff;
  --ink:        #07152b;
  --muted:      #4d6a8a;
  --line:       rgba(20,72,192,0.12);
  --gold:       #e8b84b;
}

html { scroll-behavior: smooth; }
body { font-family: 'DM Sans', sans-serif; background: var(--pale); color: var(--ink); overflow-x: hidden; }

/* ── TOPBAR ── */
.topbar {
  height: 3px;
  background: linear-gradient(90deg, var(--navy), var(--blue-mid) 40%, var(--gold) 70%, var(--sky));
}

/* ── NAV ── */
nav {
  position: fixed; top: 3px; left: 0; right: 0; z-index: 200;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 56px; height: 64px;
  background: rgba(5,18,43,0.92);
  backdrop-filter: blur(18px);
  border-bottom: 1px solid rgba(255,255,255,0.06);
}

.nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.nav-shield {
  width: 38px; height: 38px; border-radius: 10px;
  background: var(--blue);
  display: flex; align-items: center; justify-content: center;
  border: 1px solid rgba(255,255,255,0.15);
}
.nav-shield svg { width: 18px; height: 18px; }
.nav-name { font-family: 'Playfair Display', serif; font-size: 17px; color: #fff; font-weight: 700; }
.nav-login {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--white); color: var(--blue);
  padding: 8px 22px; border-radius: 100px;
  font-size: 13px; font-weight: 600; text-decoration: none;
  transition: opacity 0.15s;
}
.nav-login:hover { opacity: 0.88; }

/* ── HERO ── */
.hero {
  margin-top: 67px;
  min-height: 600px;
  background: var(--navy);
  position: relative; overflow: hidden;
  display: flex; align-items: center;
}

.hero-geo {
  position: absolute; inset: 0; pointer-events: none;
}

/* Big circle top-right */
.hero-geo::before {
  content: '';
  position: absolute; top: -200px; right: -160px;
  width: 560px; height: 560px; border-radius: 50%;
  border: 1px solid rgba(200,220,255,0.09);
}
.hero-geo::after {
  content: '';
  position: absolute; top: -120px; right: -80px;
  width: 380px; height: 380px; border-radius: 50%;
  border: 1px solid rgba(200,220,255,0.13);
}

.hero-dot-grid {
  position: absolute; inset: 0;
  background-image: radial-gradient(circle, rgba(255,255,255,0.045) 1px, transparent 1px);
  background-size: 28px 28px;
}

.hero-glow {
  position: absolute; top: -60px; right: -60px;
  width: 520px; height: 400px;
  background: radial-gradient(ellipse at top right, rgba(30,92,204,0.35) 0%, transparent 65%);
}

.hero-line-left {
  position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
  background: linear-gradient(to bottom, transparent, var(--gold) 40%, var(--blue-soft) 70%, transparent);
}

.hero-inner {
  position: relative; z-index: 2;
  max-width: 1100px; margin: 0 auto; width: 100%;
  padding: 88px 56px 108px;
  display: grid; grid-template-columns: 1.1fr 1fr; gap: 64px; align-items: center;
}

.hero-badge {
  display: inline-flex; align-items: center; gap: 8px;
  border: 1px solid rgba(232,184,75,0.45);
  background: rgba(232,184,75,0.08);
  color: var(--gold); font-size: 11px; font-weight: 600;
  letter-spacing: 1.5px; text-transform: uppercase;
  padding: 5px 14px; border-radius: 4px; margin-bottom: 28px;
}
.hero-badge-dot { width: 5px; height: 5px; background: var(--gold); border-radius: 50%; }

.hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: 50px; font-weight: 800;
  color: #fff; line-height: 1.1; letter-spacing: -0.5px;
  margin-bottom: 22px;
}
.hero h1 em {
  font-style: normal;
  color: var(--sky);
}

.hero-desc {
  font-size: 15px; font-weight: 300;
  color: rgba(255,255,255,0.55); line-height: 1.9;
  max-width: 420px; margin-bottom: 40px;
}

.hero-cta { display: flex; gap: 14px; align-items: center; flex-wrap: wrap; }

.btn-primary {
  display: inline-flex; align-items: center; gap: 10px;
  background: var(--white); color: var(--navy);
  padding: 13px 28px; border-radius: 100px;
  font-size: 14px; font-weight: 600; text-decoration: none;
  transition: transform 0.15s;
}
.btn-primary:hover { transform: translateY(-2px); }
.btn-primary svg { width: 14px; height: 14px; }

.btn-ghost {
  display: inline-flex; align-items: center; gap: 8px;
  color: rgba(255,255,255,0.55); font-size: 14px; font-weight: 400;
  text-decoration: none; transition: color 0.2s;
}
.btn-ghost:hover { color: rgba(255,255,255,0.9); }
.btn-ghost::after { content: '↓'; }

/* STAT PANEL */
.stat-panel {
  display: flex; flex-direction: column; gap: 16px;
}

.stat-row {
  background: rgba(255,255,255,0.055);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 16px; padding: 24px 28px;
  display: flex; align-items: center; gap: 20px;
  transition: background 0.2s;
}
.stat-row:hover { background: rgba(255,255,255,0.09); }

.stat-icon-box {
  width: 46px; height: 46px; border-radius: 12px;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.12);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.stat-icon-box svg { width: 20px; height: 20px; }

.stat-label { font-size: 12px; color: rgba(255,255,255,0.38); margin-bottom: 4px; }
.stat-number {
  font-family: 'Playfair Display', serif;
  font-size: 32px; font-weight: 700; color: #fff; line-height: 1;
}

/* shimmer */
@keyframes shimmer {
  0%   { background-position: -300px 0; }
  100% { background-position: 300px 0; }
}
.skel {
  display: inline-block; width: 64px; height: 30px; border-radius: 6px;
  background: linear-gradient(90deg, rgba(255,255,255,0.07) 25%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.07) 75%);
  background-size: 300px 100%; animation: shimmer 1.5s infinite;
}
.skel-line {
  display: block; height: 13px; border-radius: 4px; margin-bottom: 8px;
  background: linear-gradient(90deg, rgba(29,92,204,0.07) 25%, rgba(29,92,204,0.14) 50%, rgba(29,92,204,0.07) 75%);
  background-size: 300px 100%; animation: shimmer 1.5s infinite;
}

/* HERO WAVE */
.hero-wave { position: absolute; bottom: -2px; left: 0; right: 0; z-index: 1; }

/* ── MAIN ── */
.main { max-width: 1100px; margin: 0 auto; padding: 90px 56px 100px; }

.section-head { text-align: center; margin-bottom: 56px; }
.section-eyebrow {
  display: inline-block; font-size: 11px; font-weight: 600;
  letter-spacing: 2.5px; text-transform: uppercase;
  color: var(--blue); margin-bottom: 10px;
}
.section-title {
  font-family: 'Playfair Display', serif;
  font-size: 34px; font-weight: 700; color: var(--ink);
  letter-spacing: -0.3px; margin-bottom: 12px;
}
.section-sub { font-size: 15px; color: var(--muted); line-height: 1.8; max-width: 480px; margin: 0 auto; }

/* ── VISI MISI GRID ── */
.vm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }

/* VISI */
.visi-card {
  background: var(--navy);
  border-radius: 24px; padding: 44px 40px;
  position: relative; overflow: hidden;
}

.visi-card-stripe {
  position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, var(--gold), var(--blue-soft));
}
.visi-card-circle {
  position: absolute; bottom: -80px; right: -80px;
  width: 280px; height: 280px; border-radius: 50%;
  border: 1px solid rgba(255,255,255,0.06);
}
.visi-card-circle2 {
  position: absolute; bottom: -40px; right: -40px;
  width: 180px; height: 180px; border-radius: 50%;
  border: 1px solid rgba(255,255,255,0.09);
}

.card-eyebrow {
  font-size: 10px; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase;
  color: var(--gold); margin-bottom: 12px;
}

.visi-icon {
  width: 48px; height: 48px; border-radius: 14px;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.13);
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 22px;
}
.visi-icon svg { width: 22px; height: 22px; }

.visi-card .card-title {
  font-family: 'Playfair Display', serif;
  font-size: 21px; font-weight: 700; color: #fff; margin-bottom: 18px;
}
.visi-card p { font-size: 14px; color: rgba(255,255,255,0.58); line-height: 1.9; position: relative; z-index: 1; }
.visi-card .skel { background: linear-gradient(90deg, rgba(255,255,255,0.06) 25%, rgba(255,255,255,0.14) 50%, rgba(255,255,255,0.06) 75%); background-size: 300px 100%; width: 100%; height: 13px; margin-bottom: 9px; }

/* MISI */
.misi-card {
  background: var(--white);
  border: 1px solid var(--line);
  border-radius: 24px; padding: 44px 40px;
  position: relative; overflow: hidden;
}
.misi-card-stripe {
  position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, var(--blue), var(--blue-soft));
}
.misi-card .card-eyebrow { color: var(--blue); }
.misi-icon {
  width: 48px; height: 48px; border-radius: 14px;
  background: #e9efff; border: 1px solid var(--line);
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 22px;
}
.misi-icon svg { width: 22px; height: 22px; }

.misi-card .card-title {
  font-family: 'Playfair Display', serif;
  font-size: 21px; font-weight: 700; color: var(--ink); margin-bottom: 24px;
}
.misi-list { list-style: none; }
.misi-item {
  display: flex; align-items: flex-start; gap: 14px;
  padding: 12px 0; border-bottom: 1px solid var(--line);
}
.misi-item:last-child { border-bottom: none; padding-bottom: 0; }
.misi-num {
  width: 26px; height: 26px; border-radius: 7px; flex-shrink: 0;
  background: #e9efff; color: var(--blue);
  font-size: 12px; font-weight: 600;
  display: flex; align-items: center; justify-content: center; margin-top: 2px;
}
.misi-text { font-size: 14px; color: var(--ink); line-height: 1.75; }
.misi-skel { display: block; height: 13px; border-radius: 4px; width: 100%; }

/* ── FOOTER ── */
footer { background: var(--navy); }

.footer-top {
  max-width: 1100px; margin: 0 auto;
  padding: 64px 56px 48px;
  display: grid; grid-template-columns: 1.6fr 1fr 1fr; gap: 56px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
}

.footer-logo-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
.footer-shield {
  width: 36px; height: 36px; border-radius: 9px;
  background: var(--blue); display: flex; align-items: center; justify-content: center;
}
.footer-shield svg { width: 16px; height: 16px; }
.footer-school-name { font-family: 'Playfair Display', serif; font-size: 18px; color: #fff; font-weight: 700; }
.footer-tagline { font-size: 13px; color: rgba(255,255,255,0.3); line-height: 1.8; margin-bottom: 22px; max-width: 280px; }

.footer-akred {
  display: inline-flex; align-items: center; gap: 6px;
  border: 1px solid rgba(232,184,75,0.3);
  background: rgba(232,184,75,0.06);
  color: var(--gold); font-size: 12px; font-weight: 600;
  padding: 5px 14px; border-radius: 4px;
}
.footer-akred svg { width: 12px; height: 12px; }

.footer-col-label {
  font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
  color: rgba(255,255,255,0.22); margin-bottom: 24px;
}

.contact-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 20px; }
.contact-icon {
  width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
  background: rgba(30,92,204,0.2);
  display: flex; align-items: center; justify-content: center;
}
.contact-icon svg { width: 14px; height: 14px; }
.contact-lbl { font-size: 11px; color: rgba(255,255,255,0.25); margin-bottom: 3px; }
.contact-val { font-size: 13px; color: rgba(255,255,255,0.6); line-height: 1.65; }

.maps-link {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 12px; color: var(--sky); text-decoration: none; margin-top: 5px;
  opacity: 0.8; transition: opacity 0.2s;
}
.maps-link:hover { opacity: 1; }

.footer-bottom {
  max-width: 1100px; margin: 0 auto;
  padding: 20px 56px;
  display: flex; align-items: center; justify-content: space-between;
}
.footer-copy { font-size: 12px; color: rgba(255,255,255,0.2); }
.footer-copy b { color: rgba(255,255,255,0.35); font-weight: 400; }

/* ── RESPONSIVE ── */
@media (max-width: 960px) {
  nav { padding: 0 22px; }
  .hero-inner { grid-template-columns: 1fr; padding: 68px 22px 90px; gap: 44px; }
  .hero h1 { font-size: 36px; }
  .main { padding: 60px 22px 72px; }
  .vm-grid { grid-template-columns: 1fr; }
  .footer-top { grid-template-columns: 1fr; gap: 36px; padding: 44px 22px 36px; }
  .footer-bottom { flex-direction: column; gap: 6px; text-align: center; padding: 18px 22px; }
}
</style>
</head>
<body>

<div class="topbar"></div>

<!-- NAV -->
<nav>
  <a href="#" class="nav-brand">
    <div class="nav-shield">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M12 3L4 7V13C4 17.4 7.4 21.5 12 22.5C16.6 21.5 20 17.4 20 13V7L12 3Z" fill="white"/>
      </svg>
    </div>
    <span class="nav-name">SD Negeri Singkul</span>
  </a>
  <a href="/login" class="nav-login">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
      <path d="M15 3H19C20.1 3 21 3.9 21 5V19C21 20.1 20.1 21 19 21H15M10 17L15 12M15 12L10 7M15 12H3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    Masuk
  </a>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-geo"></div>
  <div class="hero-dot-grid"></div>
  <div class="hero-glow"></div>
  <div class="hero-line-left"></div>

  <div class="hero-inner">
    <!-- Left -->
    <div>
      <div class="hero-badge">
        <span class="hero-badge-dot"></span>
        Sistem Administrasi Digital
      </div>
      <h1>Kelola Sekolah dengan <em>Lebih Cerdas</em></h1>
      <p class="hero-desc">Platform administrasi guru yang terintegrasi — data, absensi, dan laporan dalam satu sistem yang efisien dan mudah digunakan.</p>
      <div class="hero-cta">
        <a href="/login" class="btn-primary">
          Mulai Sekarang
          <svg viewBox="0 0 16 16" fill="none">
            <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
        <a href="#konten" class="btn-ghost">Pelajari lebih </a>
      </div>
    </div>

    <!-- Right: Stats -->
    <div class="stat-panel">
      <div class="stat-row">
        <div class="stat-icon-box">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M17 21V19C17 17.9 16.1 17 15 17H9C7.9 17 7 17.9 7 19V21M12 11C14.2 11 16 9.2 16 7C16 4.8 14.2 3 12 3C9.8 3 8 4.8 8 7C8 9.2 9.8 11 12 11Z" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
        </div>
        <div>
          <div class="stat-label">Total Guru</div>
          <div class="stat-number" id="total-guru"><span class="skel"></span></div>
        </div>
      </div>

      <div class="stat-row">
        <div class="stat-icon-box">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M17 21V19C17 17.9 16.1 17 15 17H5C3.9 17 3 17.9 3 19V21M21 21V19C21 17.9 20.1 17 19 17H17M16 3.13C17.8 3.63 19 5.27 19 7C19 8.73 17.8 10.37 16 10.87M13 7C13 9.2 11.2 11 9 11C6.8 11 5 9.2 5 7C5 4.8 6.8 3 9 3C11.2 3 13 4.8 13 7Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div>
          <div class="stat-label">Total Siswa Terdaftar</div>
          <div class="stat-number" id="total-siswa"><span class="skel"></span></div>
        </div>
      </div>
    </div>
  </div>

  <svg class="hero-wave" viewBox="0 0 1440 56" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="height:50px;">
    <path d="M0,18 C360,52 1080,0 1440,28 L1440,56 L0,56 Z" fill="#f0f5ff"/>
  </svg>
</section>

<!-- MAIN -->
<div class="main" id="konten">
  <div class="section-head">
    <div class="section-eyebrow">Arah &amp; Tujuan</div>
    <div class="section-title">Visi &amp; Misi Sekolah</div>
    <p class="section-sub">Landasan kami dalam membimbing setiap langkah menuju pendidikan yang bermakna dan berdaya saing.</p>
  </div>

  <div class="vm-grid">

    <!-- VISI -->
    <div class="visi-card">
      <div class="visi-card-stripe"></div>
      <div class="visi-card-circle"></div>
      <div class="visi-card-circle2"></div>
      <div class="visi-icon">
        <svg viewBox="0 0 24 24" fill="none">
          <circle cx="12" cy="12" r="3" stroke="white" stroke-width="1.5"/>
          <path d="M12 5C7 5 2.7 8.1 1 12.5C2.7 16.9 7 20 12 20C17 20 21.3 16.9 23 12.5C21.3 8.1 17 5 12 5Z" stroke="white" stroke-width="1.5"/>
        </svg>
      </div>
      <div class="card-eyebrow">Visi</div>
      <div class="card-title">Unggulan &amp; Berkarakter</div>
      <p id="visi">
        <span class="skel" style="display:block;width:100%;height:13px;margin-bottom:9px;"></span>
        <span class="skel" style="display:block;width:88%;height:13px;margin-bottom:9px;"></span>
        <span class="skel" style="display:block;width:70%;height:13px;"></span>
      </p>
    </div>

    <!-- MISI -->
    <div class="misi-card">
      <div class="misi-card-stripe"></div>
      <div class="misi-icon">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M9 11L12 14L22 4M3 12V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V12" stroke="#1547c0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div class="card-eyebrow">Misi</div>
      <div class="card-title">Langkah Kami</div>
      <ul class="misi-list" id="misi-list">
        <li class="misi-item">
          <span class="misi-num" style="background:#e9efff;"></span>
          <span class="misi-skel skel-line"></span>
        </li>
        <li class="misi-item">
          <span class="misi-num" style="background:#e9efff;"></span>
          <span class="misi-skel skel-line"></span>
        </li>
        <li class="misi-item">
          <span class="misi-num" style="background:#e9efff;"></span>
          <span class="misi-skel skel-line" style="width:75%;"></span>
        </li>
      </ul>
    </div>

  </div>
</div>

<!-- FOOTER -->
<footer>
  <div class="footer-top">

    <!-- Brand -->
    <div>
      <div class="footer-logo-row">
        <div class="footer-shield">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 3L4 7V13C4 17.4 7.4 21.5 12 22.5C16.6 21.5 20 17.4 20 13V7L12 3Z" fill="white"/>
          </svg>
        </div>
        <span class="footer-school-name">SD Negeri Singkul</span>
      </div>
      <p class="footer-tagline">Sistem Administrasi Guru yang modern dan terintegrasi untuk mendukung operasional sekolah sehari-hari secara efisien.</p>
      <div class="footer-akred" id="akreditasi">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M12 3L4 7V13C4 17.4 7.4 21.5 12 22.5C16.6 21.5 20 17.4 20 13V7L12 3Z" stroke="#e8b84b" stroke-width="1.5"/>
        </svg>
        Terakreditasi —
      </div>
    </div>

    <!-- Alamat -->
    <div>
      <div class="footer-col-label">Alamat</div>
      <div class="contact-item">
        <div class="contact-icon">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 2C8.1 2 5 5.1 5 9C5 14.2 12 22 12 22C12 22 19 14.2 19 9C19 5.1 15.9 2 12 2ZM12 11.5C10.6 11.5 9.5 10.4 9.5 9C9.5 7.6 10.6 6.5 12 6.5C13.4 6.5 14.5 7.6 14.5 9C14.5 10.4 13.4 11.5 12 11.5Z" fill="#4a85e8"/>
          </svg>
        </div>
        <div>
          <div class="contact-lbl">Alamat Sekolah</div>
          <div class="contact-val" id="alamat">Memuat...</div>
          <a href="https://www.google.com/maps/place/SDN+Singkul/@-8.3530575,120.2934728,862m/" class="maps-link" id="maps-link" target="_blank" rel="noopener">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none">
              <path d="M18 13V19C18 20.1 17.1 21 16 21H5C3.9 21 3 20.1 3 19V8C3 6.9 3.9 6 5 6H11M15 3H21V9M10 14L21 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Lihat di Google Maps
          </a>
        </div>
      </div>
    </div>

    <!-- Kontak -->
    <div>
      <div class="footer-col-label">Kontak Kami</div>

      <div class="contact-item">
        <div class="contact-icon">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M6.6 10.8C7.8 13.2 9.8 15.1 12.2 16.4L14.1 14.5C14.4 14.3 14.7 14.2 14.9 14.4C15.7 14.7 16.6 14.9 17.5 14.9C17.8 14.9 18 15.1 18 15.4V17.5C18 17.8 17.8 18 17.5 18C10.1 18 4 11.9 4 4.5C4 4.2 4.2 4 4.5 4H6.6C6.9 4 7.1 4.2 7.1 4.5C7.1 5.4 7.3 6.3 7.6 7.1C7.7 7.4 7.6 7.7 7.4 7.9L6.6 10.8Z" fill="#4a85e8"/>
          </svg>
        </div>
        <div>
          <div class="contact-lbl">Telepon</div>
          <div class="contact-val" id="telepon">Memuat...</div>
        </div>
      </div>

      <div class="contact-item">
        <div class="contact-icon">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z" fill="#4a85e8"/>
          </svg>
        </div>
        <div>
          <div class="contact-lbl">Email</div>
          <div class="contact-val" id="email">Memuat...</div>
        </div>
      </div>

      <div class="contact-item">
        <div class="contact-icon">
          <svg viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="9" stroke="#4a85e8" stroke-width="1.5"/>
            <path d="M12 7V12L15 14" stroke="#4a85e8" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
        </div>
        <div>
          <div class="contact-lbl">Jam Operasional</div>
          <div class="contact-val" id="jam">Memuat...</div>
        </div>
      </div>
    </div>

  </div>

  <div class="footer-bottom">
    <p class="footer-copy">© 2026 <b>SD Negeri Singkul</b> · Sistem Administrasi Guru</p>
    <p class="footer-copy">Dibuat dengan penuh semangat untuk dunia pendidikan</p>
  </div>
</footer>

<script>
fetch('/api/konten-umum')
  .then(r => r.json())
  .then(res => {
    const d = res.data;

    document.getElementById('total-guru').innerText  = d?.total_guru  ?? '0';
    document.getElementById('total-siswa').innerText = d?.total_siswa ?? '0';

    document.getElementById('visi').innerText = d?.visi || '-';

    const lines = d?.misi ? d.misi.split('\n').filter(l => l.trim()) : [];
    document.getElementById('misi-list').innerHTML = lines.length
      ? lines.map((l, i) => `
          <li class="misi-item">
            <span class="misi-num">${i + 1}</span>
            <span class="misi-text">${l.trim()}</span>
          </li>`).join('')
      : '<li class="misi-item"><span class="misi-text">-</span></li>';

    document.getElementById('alamat').innerText  = d?.alamat          || '-';
    document.getElementById('telepon').innerText = d?.telepon         || '-';
    document.getElementById('email').innerText   = d?.email           || '-';
    document.getElementById('jam').innerText     = d?.jam_operasional || '-';

    const akr = d?.akreditasi || '-';
    document.getElementById('akreditasi').innerHTML = `
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
        <path d="M12 3L4 7V13C4 17.4 7.4 21.5 12 22.5C16.6 21.5 20 17.4 20 13V7L12 3Z" stroke="#e8b84b" stroke-width="1.5"/>
      </svg>
      Terakreditasi ${akr}`;

    if (d?.maps_url) document.getElementById('maps-link').href = d.maps_url;
  })
  .catch(() => {
    ['total-guru','total-siswa'].forEach(id => document.getElementById(id).innerText = '0');
    document.getElementById('visi').innerText = 'Data tidak tersedia.';
    document.getElementById('misi-list').innerHTML = '<li class="misi-item"><span class="misi-text">Data tidak tersedia.</span></li>';
    ['alamat','telepon','email','jam'].forEach(id => document.getElementById(id).innerText = '-');
  });
</script>

</body>
</html>