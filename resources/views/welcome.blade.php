<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SD Negeri Singkul — Sistem Administrasi</title>
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ── RESET & BASE ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --navy:       #0f2057;
  --blue:       #1a56db;
  --blue-mid:   #2563eb;
  --blue-soft:  #60a5fa;
  --sky:        #dbeafe;
  --pale:       #f8faff;
  --white:      #ffffff;
  --ink:        #0f172a;
  --slate:      #475569;
  --muted:      #64748b;
  --line:       rgba(37,99,235,0.10);
  --gold:       #f59e0b;
  --gold-light: #fef3c7;
  --green:      #059669;
}

html { scroll-behavior: smooth; }
body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background: var(--pale);
  color: var(--ink);
  overflow-x: hidden;
}

/* ── ACCENT BAR ── */
.accent-bar {
  height: 4px;
  background: linear-gradient(90deg, var(--blue) 0%, var(--blue-soft) 50%, var(--gold) 100%);
}

/* ── NAV ── */
nav {
  position: fixed; top: 4px; left: 0; right: 0; z-index: 200;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 60px; height: 62px;
  background: rgba(255,255,255,0.92);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(37,99,235,0.08);
  box-shadow: 0 1px 20px rgba(15,32,87,0.06);
}

.nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.nav-shield {
  width: 36px; height: 36px; border-radius: 10px;
  background: linear-gradient(135deg, var(--blue), #3b82f6);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 2px 8px rgba(37,99,235,0.3);
  overflow: hidden; flex-shrink: 0;
}
.nav-shield svg { width: 17px; height: 17px; }
.nav-shield img { width: 100%; height: 100%; object-fit: cover; display: block; }
.nav-shield.has-img { background: none; box-shadow: 0 2px 8px rgba(37,99,235,0.15); padding: 0; }
.nav-name { font-family: 'Lora', serif; font-size: 16px; color: var(--ink); font-weight: 700; }
.nav-sub  { font-size: 10px; color: var(--muted); font-weight: 500; letter-spacing: 0.5px; display: block; margin-top: -2px; }

.nav-login {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--blue); color: white;
  padding: 9px 22px; border-radius: 100px;
  font-size: 13px; font-weight: 600; text-decoration: none;
  transition: all 0.2s; box-shadow: 0 2px 10px rgba(37,99,235,0.25);
}
.nav-login:hover { background: var(--navy); transform: translateY(-1px); }
.nav-login svg { width: 14px; height: 14px; }

/* ── HERO ── */
.hero {
  margin-top: 66px;
  position: relative;
  overflow: hidden;
  padding: 80px 60px 100px;
  background: var(--pale);
}

/* Orbs */
.hero-orb-right {
  position: absolute; top: -120px; right: -100px;
  width: 600px; height: 600px; border-radius: 50%;
  background: radial-gradient(ellipse, rgba(37,99,235,0.07) 0%, transparent 70%);
  pointer-events: none; z-index: 0;
}
.hero-orb-left {
  position: absolute; bottom: -80px; left: -80px;
  width: 400px; height: 400px; border-radius: 50%;
  background: radial-gradient(ellipse, rgba(245,158,11,0.06) 0%, transparent 70%);
  pointer-events: none; z-index: 0;
}

.hero-inner {
  max-width: 1100px; margin: 0 auto;
  position: relative; z-index: 2;
  display: grid; grid-template-columns: 1fr 1.05fr; gap: 64px; align-items: center;
}

/* LEFT: text */
.hero-left {}

.hero-tag {
  display: inline-flex; align-items: center; gap: 7px;
  background: var(--sky); color: var(--blue);
  font-size: 11px; font-weight: 700; letter-spacing: 1.2px;
  text-transform: uppercase; padding: 5px 14px; border-radius: 100px;
  margin-bottom: 24px; border: 1px solid rgba(37,99,235,0.15);
}
.hero-tag-dot { width: 6px; height: 6px; background: var(--blue); border-radius: 50%; animation: pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(0.8)} }

.hero h1 {
  font-family: 'Lora', serif;
  font-size: 46px; font-weight: 700;
  color: var(--ink); line-height: 1.18; letter-spacing: -0.5px;
  margin-bottom: 18px;
}
.hero h1 .accent  { color: var(--blue); }
.hero h1 .accent2 { color: var(--gold); }

.hero-desc {
  font-size: 15px; font-weight: 400;
  color: var(--slate); line-height: 1.85;
  max-width: 400px; margin-bottom: 32px;
}

/* STAT CARDS below text */
.stat-grid {
  display: flex; gap: 14px; flex-wrap: wrap;
  margin-bottom: 32px;
}
.stat-card {
  background: var(--white);
  border: 1px solid rgba(37,99,235,0.12);
  border-radius: 16px;
  padding: 14px 18px;
  display: flex; align-items: center; gap: 12px;
  transition: all 0.25s;
  box-shadow: 0 2px 10px rgba(15,32,87,0.05);
  min-width: 160px;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(15,32,87,0.10); border-color: rgba(37,99,235,0.2); }
.stat-icon {
  width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
}
.stat-icon.blue  { background: linear-gradient(135deg, #eff6ff, #dbeafe); }
.stat-icon.green { background: linear-gradient(135deg, #ecfdf5, #d1fae5); }
.stat-icon svg { width: 20px; height: 20px; }
.stat-label  { font-size: 11px; color: var(--muted); font-weight: 500; margin-bottom: 4px; }
.stat-number {
  font-family: 'Lora', serif;
  font-size: 24px; font-weight: 600; color: var(--ink); line-height: 1;
}
.stat-trend {
  display: none; align-items: center; gap: 4px;
  font-size: 11px; font-weight: 600; color: var(--green);
  background: #ecfdf5; padding: 2px 8px; border-radius: 20px; margin-top: 5px;
}

/* CTA */
.hero-cta { display: flex; gap: 14px; align-items: center; flex-wrap: wrap; }
.btn-primary {
  display: inline-flex; align-items: center; gap: 9px;
  background: var(--blue); color: white;
  padding: 13px 26px; border-radius: 100px;
  font-size: 14px; font-weight: 600; text-decoration: none;
  transition: all 0.2s; box-shadow: 0 4px 14px rgba(37,99,235,0.3);
}
.btn-primary:hover { background: var(--navy); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.35); }
.btn-primary svg { width: 15px; height: 15px; }
.btn-outline {
  display: inline-flex; align-items: center; gap: 8px;
  border: 1.5px solid rgba(37,99,235,0.25); color: var(--blue);
  padding: 12px 22px; border-radius: 100px;
  font-size: 14px; font-weight: 500; text-decoration: none;
  transition: all 0.2s; background: white;
}
.btn-outline:hover { border-color: var(--blue); background: var(--sky); }
.btn-outline svg { width: 14px; height: 14px; }

/* ── HERO IMAGE CARD (RIGHT) ── */
.hero-right {
  position: relative;
  display: flex; align-items: center; justify-content: center;
}

/* Floating decoration rings */
.hero-right::before {
  content: '';
  position: absolute;
  inset: -20px;
  border-radius: 32px;
  background: linear-gradient(135deg, rgba(37,99,235,0.08) 0%, rgba(245,158,11,0.06) 100%);
  z-index: 0;
}

.hero-img-frame {
  position: relative;
  z-index: 1;
  width: 100%;
  border-radius: 24px;
  overflow: hidden;

  /* Clean border */
  border: 1.5px solid rgba(255,255,255,0.9);
  box-shadow:
    0 0 0 1px rgba(37,99,235,0.10),
    0 20px 60px rgba(15,32,87,0.14),
    0 4px 16px rgba(15,32,87,0.08);

  background: #e8f0fe; /* fallback */
  aspect-ratio: 4 / 3;
}

.hero-img-frame img {
  width: 100%; height: 100%;
  object-fit: cover;
  object-position: center 30%;
  display: block;
  transition: transform 0.6s ease;
}
.hero-img-frame:hover img { transform: scale(1.03); }

/* Overlay bawah dengan info badge */
.hero-img-badge {
  position: absolute;
  bottom: 16px; left: 16px; right: 16px;
  background: rgba(255,255,255,0.90);
  backdrop-filter: blur(16px);
  border: 1px solid rgba(255,255,255,0.9);
  border-radius: 14px;
  padding: 12px 16px;
  display: flex; align-items: center; justify-content: space-between;
  box-shadow: 0 4px 20px rgba(15,32,87,0.12);
  z-index: 2;
}
.badge-left { display: flex; align-items: center; gap: 10px; }
.badge-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: #22c55e;
  box-shadow: 0 0 0 3px rgba(34,197,94,0.2);
  animation: pulse-green 2s infinite;
  flex-shrink: 0;
}
@keyframes pulse-green { 0%,100%{box-shadow:0 0 0 3px rgba(34,197,94,0.2)} 50%{box-shadow:0 0 0 5px rgba(34,197,94,0.1)} }
.badge-text { font-size: 12px; font-weight: 600; color: var(--ink); }
.badge-sub  { font-size: 10.5px; color: var(--muted); font-weight: 400; }
.badge-year {
  font-family: 'Lora', serif;
  font-size: 16px; font-weight: 700;
  color: var(--blue);
}

/* Corner accent dots */
.hero-img-corner {
  position: absolute;
  width: 80px; height: 80px;
  border-radius: 50%;
  pointer-events: none;
  z-index: 0;
}
.hero-img-corner.tr {
  top: -20px; right: -20px;
  background: radial-gradient(ellipse, rgba(245,158,11,0.15) 0%, transparent 70%);
}
.hero-img-corner.bl {
  bottom: -20px; left: -20px;
  background: radial-gradient(ellipse, rgba(37,99,235,0.12) 0%, transparent 70%);
}

/* ── Skeleton ── */
@keyframes shimmer {
  0%   { background-position: -400px 0; }
  100% { background-position:  400px 0; }
}
.skel {
  display: inline-block; height: 30px; border-radius: 8px;
  background: linear-gradient(90deg, #e8eef7 25%, #d5dff0 50%, #e8eef7 75%);
  background-size: 400px 100%; animation: shimmer 1.6s infinite;
}
.skel-sm {
  display: block; height: 11px; border-radius: 4px; margin-bottom: 7px;
  background: linear-gradient(90deg, #e8eef7 25%, #d5dff0 50%, #e8eef7 75%);
  background-size: 400px 100%; animation: shimmer 1.6s infinite;
}
/* Image skeleton */
.img-skeleton {
  width: 100%; height: 100%;
  background: linear-gradient(90deg, #e8eef7 25%, #dce6f5 50%, #e8eef7 75%);
  background-size: 600px 100%;
  animation: shimmer 1.8s infinite;
  display: flex; align-items: center; justify-content: center;
}
.img-skeleton svg { width: 48px; height: 48px; opacity: 0.2; }

/* ── MAIN CONTENT ── */
.main { max-width: 1100px; margin: 0 auto; padding: 96px 60px 104px; }

.section-head { text-align: center; margin-bottom: 60px; }
.section-eyebrow {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 11px; font-weight: 700;
  letter-spacing: 2px; text-transform: uppercase;
  color: var(--blue); margin-bottom: 12px;
}
.section-eyebrow::before, .section-eyebrow::after {
  content: ''; display: block; width: 22px; height: 1.5px; background: var(--blue-soft); opacity: 0.5;
}
.section-title {
  font-family: 'Lora', serif;
  font-size: 36px; font-weight: 700; color: var(--ink);
  letter-spacing: -0.4px; margin-bottom: 14px;
}
.section-sub { font-size: 15px; color: var(--muted); line-height: 1.85; max-width: 460px; margin: 0 auto; }

/* ── FEATURE STRIP ── */
.feature-strip {
  background: linear-gradient(135deg, var(--navy) 0%, #1e3a8a 100%);
  border-radius: 28px; padding: 52px 56px;
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px;
  position: relative; overflow: hidden;
  margin-bottom: 80px;
}
.feature-strip::before {
  content: '';
  position: absolute; top: -60px; right: -60px;
  width: 300px; height: 300px; border-radius: 50%;
  background: radial-gradient(ellipse, rgba(96,165,250,0.15), transparent 70%);
}
.feature-item { position: relative; z-index: 1; }
.feature-num {
  font-family: 'Lora', serif; font-size: 48px; font-weight: 700;
  color: rgba(255,255,255,0.10); line-height: 1; margin-bottom: 4px;
}
.feature-title { font-size: 15px; font-weight: 700; color: white; margin-bottom: 8px; }
.feature-desc  { font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.75; }
.feature-divider {
  position: absolute; right: 0; top: 0; bottom: 0; width: 1px;
  background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.1) 30%, rgba(255,255,255,0.1) 70%, transparent);
}
.feature-item:last-child .feature-divider { display: none; }

/* ── VM GRID ── */
.vm-grid { display: grid; grid-template-columns: 1fr; gap: 24px; }

/* VISI */
.visi-card {
  background: var(--white); border: 1px solid var(--line);
  border-radius: 24px; padding: 40px 36px;
  position: relative; overflow: hidden;
  box-shadow: 0 2px 20px rgba(15,32,87,0.05);
  transition: box-shadow 0.2s; text-align: center;
}
.visi-card:hover { box-shadow: 0 8px 30px rgba(15,32,87,0.09); }
.visi-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, var(--gold), #fbbf24);
}
.visi-card::after {
  content: '';
  position: absolute; bottom: -60px; right: -60px;
  width: 200px; height: 200px; border-radius: 50%;
  background: radial-gradient(ellipse, rgba(245,158,11,0.06), transparent 70%);
}
.card-icon-wrap {
  width: 50px; height: 50px; border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 22px;
}
.card-icon-wrap.gold { background: var(--gold-light); border: 1px solid rgba(245,158,11,0.2); }
.card-icon-wrap.blue { background: var(--sky); border: 1px solid rgba(37,99,235,0.15); }
.card-icon-wrap svg { width: 22px; height: 22px; }
.card-eyebrow { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px; }
.card-eyebrow.gold { color: #b45309; }
.card-eyebrow.blue { color: var(--blue); }
.card-title { font-family: 'Lora', serif; font-size: 22px; font-weight: 700; color: var(--ink); margin-bottom: 18px; }
.visi-text  { font-size: 14.5px; color: var(--slate); line-height: 1.9; position: relative; z-index: 1; }

/* MISI */
.misi-card {
  background: var(--white); border: 1px solid var(--line);
  border-radius: 24px; padding: 40px 36px;
  position: relative; overflow: hidden;
  box-shadow: 0 2px 20px rgba(15,32,87,0.05);
  transition: box-shadow 0.2s;
}
.misi-card:hover { box-shadow: 0 8px 30px rgba(15,32,87,0.09); }
.misi-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, var(--blue), var(--blue-soft));
}
.misi-list { list-style: none; }
.misi-item {
  display: flex; align-items: flex-start; gap: 14px;
  padding: 13px 0; border-bottom: 1px solid rgba(37,99,235,0.07);
}
.misi-item:first-child { padding-top: 0; }
.misi-item:last-child  { border-bottom: none; padding-bottom: 0; }
.misi-num {
  min-width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
  background: var(--sky); color: var(--blue);
  font-size: 12px; font-weight: 700;
  display: flex; align-items: center; justify-content: center; margin-top: 1px;
}
.misi-text { font-size: 14px; color: var(--slate); line-height: 1.8; }

/* ── FOOTER ── */
footer { background: white; border-top: 1px solid rgba(37,99,235,0.08); }
.footer-top {
  max-width: 1100px; margin: 0 auto;
  padding: 64px 60px 48px;
  display: grid; grid-template-columns: 1.6fr 1fr 1fr; gap: 60px;
  border-bottom: 1px solid rgba(37,99,235,0.08);
}
.footer-logo-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
.footer-shield {
  width: 36px; height: 36px; border-radius: 10px;
  background: linear-gradient(135deg, var(--blue), #3b82f6);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 2px 8px rgba(37,99,235,0.25); overflow: hidden; flex-shrink: 0;
}
.footer-shield svg { width: 16px; height: 16px; }
.footer-shield img { width: 100%; height: 100%; object-fit: cover; display: block; }
.footer-shield.has-img { background: none; box-shadow: 0 2px 8px rgba(37,99,235,0.15); padding: 0; }
.footer-school-name { font-family: 'Lora', serif; font-size: 18px; color: var(--ink); font-weight: 700; }
.footer-tagline { font-size: 13.5px; color: var(--muted); line-height: 1.85; margin-bottom: 22px; max-width: 280px; }
.footer-akred {
  display: inline-flex; align-items: center; gap: 7px;
  background: var(--gold-light); color: #92400e;
  font-size: 12px; font-weight: 700; padding: 6px 14px;
  border-radius: 8px; border: 1px solid rgba(245,158,11,0.25);
}
.footer-akred svg { width: 12px; height: 12px; }
.footer-col-label {
  font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
  color: var(--muted); margin-bottom: 24px;
}
.contact-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 18px; }
.contact-icon {
  width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
  background: var(--sky); display: flex; align-items: center; justify-content: center;
}
.contact-icon svg { width: 14px; height: 14px; }
.contact-lbl { font-size: 11px; color: var(--muted); margin-bottom: 3px; font-weight: 500; }
.contact-val { font-size: 13.5px; color: var(--slate); line-height: 1.65; }
.maps-link {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 12px; color: var(--blue); text-decoration: none; margin-top: 5px;
  font-weight: 500; transition: opacity 0.2s;
}
.maps-link:hover { opacity: 0.75; }
.footer-bottom {
  max-width: 1100px; margin: 0 auto; padding: 20px 60px;
  display: flex; align-items: center; justify-content: space-between;
}
.footer-copy { font-size: 12px; color: var(--muted); }
.footer-copy b { color: var(--slate); font-weight: 600; }

/* ── RESPONSIVE ── */
@media (max-width: 960px) {
  nav { padding: 0 22px; }
  .hero { padding: 52px 22px 72px; }
  .hero-inner { grid-template-columns: 1fr; gap: 36px; }
  .hero h1 { font-size: 34px; }
  /* On mobile, image goes above text */
  .hero-right { order: -1; }
  .hero-img-frame { aspect-ratio: 16/9; }
  .main { padding: 60px 22px 72px; }
  .vm-grid { grid-template-columns: 1fr; }
  .feature-strip { grid-template-columns: 1fr; padding: 36px 28px; gap: 24px; }
  .feature-divider { display: none; }
  .footer-top { grid-template-columns: 1fr; gap: 36px; padding: 44px 22px 36px; }
  .footer-bottom { flex-direction: column; gap: 6px; text-align: center; padding: 18px 22px; }
}
</style>
</head>
<body>

<div class="accent-bar"></div>

<!-- ── NAV ── -->
<nav>
  <a href="#" class="nav-brand">
    <div class="nav-shield" id="nav-logo-wrap">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M12 3L4 7V13C4 17.4 7.4 21.5 12 22.5C16.6 21.5 20 17.4 20 13V7L12 3Z" fill="white"/>
      </svg>
    </div>
    <div>
      <span class="nav-name">SD Negeri Singkul</span>
      <span class="nav-sub">Sistem Administrasi Guru</span>
    </div>
  </a>
  <a href="/login" class="nav-login">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="M15 3H19C20.1 3 21 3.9 21 5V19C21 20.1 20.1 21 19 21H15M10 17L15 12M15 12L10 7M15 12H3"
        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    Masuk ke Sistem
  </a>
</nav>

<!-- ── HERO ── -->
<section class="hero">
  <div class="hero-orb-right"></div>
  <div class="hero-orb-left"></div>

  <div class="hero-inner">
    <!-- LEFT: Text + Stats + CTA -->
    <div class="hero-left">
      <h1>Kelola Sekolah<br>dengan lebih cerdas</h1>
      <p class="hero-desc">
        Platform administrasi guru yang terintegrasi — data siswa, absensi,
        dan laporan dalam satu sistem yang efisien dan mudah digunakan.
      </p>

      <div class="stat-grid">
        <div class="stat-card">
          <div class="stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M20 21V19C20 17.9 19.1 17 18 17H6C4.9 17 4 17.9 4 19V21" stroke="#2563eb" stroke-width="1.6" stroke-linecap="round"/>
              <circle cx="12" cy="9" r="4" stroke="#2563eb" stroke-width="1.6"/>
            </svg>
          </div>
          <div>
            <div class="stat-label">Guru Aktif</div>
            <div class="stat-number" id="total-guru"><span class="skel" style="width:60px;"></span></div>
            <div class="stat-trend" id="trend-guru"></div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M17 21V19C17 17.9 16.1 17 15 17H5C3.9 17 3 17.9 3 19V21M21 21V19C21 17.9 20.1 17 19 17H17M16 3.13C17.8 3.63 19 5.27 19 7C19 8.73 17.8 10.37 16 10.87M13 7C13 9.2 11.2 11 9 11C6.8 11 5 9.2 5 7C5 4.8 6.8 3 9 3C11.2 3 13 4.8 13 7Z"
                stroke="#059669" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div>
            <div class="stat-label">Siswa Terdaftar</div>
            <div class="stat-number" id="total-siswa"><span class="skel" style="width:60px;"></span></div>
            <div class="stat-trend" id="trend-siswa"></div>
          </div>
        </div>
      </div>

      <div class="hero-cta">
        <a href="/login" class="btn-primary">
          Mulai Sekarang
          <svg viewBox="0 0 16 16" fill="none">
            <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
        <a href="#konten" class="btn-outline">
          <svg viewBox="0 0 16 16" fill="none">
            <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/>
            <path d="M8 5.5V8.5L10 10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
          </svg>
          Pelajari Lebih
        </a>
      </div>
    </div>

    <!-- RIGHT: Clean Image Card -->
    <div class="hero-right">
      <div class="hero-img-corner tr"></div>
      <div class="hero-img-corner bl"></div>

      <div class="hero-img-frame" id="hero-img-frame">
        <!-- Skeleton saat loading -->
        <div class="img-skeleton" id="img-skeleton">
          <svg viewBox="0 0 24 24" fill="none">
            <rect x="3" y="3" width="18" height="18" rx="3" stroke="#2563eb" stroke-width="1.5"/>
            <circle cx="8.5" cy="8.5" r="1.5" stroke="#2563eb" stroke-width="1.5"/>
            <path d="M21 15L16 10L5 21" stroke="#2563eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>

        <!-- Foto sekolah -->
        <img id="hero-img" src="" alt="SD Negeri Singkul" style="display:none; width:100%; height:100%; object-fit:cover; object-position:center 30%;">

        <!-- Badge di bawah foto -->
      </div>
    </div>
  </div>
</section>

<!-- ── FEATURE STRIP ── -->
<div class="main" style="padding-top:80px; padding-bottom:0;">
  <div class="feature-strip">
    <div class="feature-item">
      <div class="feature-num">01</div>
      <div class="feature-title">Data Terintegrasi</div>
      <div class="feature-desc">Seluruh data guru dan siswa tersimpan terpusat, aman, dan mudah diakses kapan saja.</div>
      <div class="feature-divider"></div>
    </div>
    <div class="feature-item">
      <div class="feature-num">02</div>
      <div class="feature-title">Absensi Digital</div>
      <div class="feature-desc">Rekam kehadiran guru dan siswa secara digital, real-time, dan akurat setiap harinya.</div>
      <div class="feature-divider"></div>
    </div>
    <div class="feature-item">
      <div class="feature-num">03</div>
      <div class="feature-title">Laporan Otomatis</div>
      <div class="feature-desc">Laporan administrasi tersaji otomatis, siap cetak, dan dapat dipantau kapan saja.</div>
    </div>
  </div>
</div>

<!-- ── VISI MISI ── -->
<div class="main" id="konten" style="padding-top:0;">
  <div class="section-head">
    <div class="section-eyebrow">Arah &amp; Tujuan</div>
    <div class="section-title">Visi &amp; Misi Sekolah</div>
    <p class="section-sub">Landasan kami dalam membimbing setiap langkah menuju pendidikan yang bermakna dan berdaya saing.</p>
  </div>

  <div class="vm-grid">
    <div class="visi-card">
      <div class="card-icon-wrap gold">
        <svg viewBox="0 0 24 24" fill="none">
          <circle cx="12" cy="12" r="3.5" stroke="#b45309" stroke-width="1.6"/>
          <path d="M12 5C7 5 2.7 8.1 1 12.5C2.7 16.9 7 20 12 20C17 20 21.3 16.9 23 12.5C21.3 8.1 17 5 12 5Z" stroke="#b45309" stroke-width="1.6"/>
        </svg>
      </div>
      <div class="card-eyebrow gold">Visi Sekolah</div>
      <div class="card-title">Unggulan &amp; Berkarakter</div>
      <div class="visi-text" id="visi">
        <span class="skel-sm" style="width:100%;"></span>
        <span class="skel-sm" style="width:88%;"></span>
        <span class="skel-sm" style="width:70%;"></span>
      </div>
    </div>

    <div class="misi-card">
      <div class="card-icon-wrap blue">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M9 11L12 14L22 4M3 12V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V12"
            stroke="#1a56db" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div class="card-eyebrow blue">Misi Sekolah</div>
      <div class="card-title">Langkah Kami</div>
      <ul class="misi-list" id="misi-list">
        <li class="misi-item">
          <span class="misi-num" style="background:#e8eef7;"></span>
          <span class="skel-sm" style="flex:1;margin-top:8px;"></span>
        </li>
        <li class="misi-item">
          <span class="misi-num" style="background:#e8eef7;"></span>
          <span class="skel-sm" style="flex:1;margin-top:8px;"></span>
        </li>
        <li class="misi-item">
          <span class="misi-num" style="background:#e8eef7;"></span>
          <span class="skel-sm" style="flex:1;width:75%;margin-top:8px;"></span>
        </li>
      </ul>
    </div>
  </div>
</div>

<!-- ── FOOTER ── -->
<footer>
  <div class="footer-top">
    <div>
      <div class="footer-logo-row">
        <div class="footer-shield" id="footer-logo-wrap">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 3L4 7V13C4 17.4 7.4 21.5 12 22.5C16.6 21.5 20 17.4 20 13V7L12 3Z" fill="white"/>
          </svg>
        </div>
        <span class="footer-school-name">SD Negeri Singkul</span>
      </div>
      <p class="footer-tagline">Sistem Administrasi Guru yang modern dan terintegrasi untuk mendukung operasional sekolah sehari-hari secara efisien.</p>
      <div class="footer-akred" id="akreditasi">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#f59e0b"/>
        </svg>
        Terakreditasi —
      </div>
    </div>

    <div>
      <div class="footer-col-label">Alamat Sekolah</div>
      <div class="contact-item">
        <div class="contact-icon">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 2C8.1 2 5 5.1 5 9C5 14.2 12 22 12 22C12 22 19 14.2 19 9C19 5.1 15.9 2 12 2ZM12 11.5C10.6 11.5 9.5 10.4 9.5 9C9.5 7.6 10.6 6.5 12 6.5C13.4 6.5 14.5 7.6 14.5 9C14.5 10.4 13.4 11.5 12 11.5Z" fill="#2563eb"/>
          </svg>
        </div>
        <div>
          <div class="contact-lbl">Lokasi</div>
          <div class="contact-val" id="alamat">Memuat...</div>
          <a href="#" class="maps-link" id="maps-link" target="_blank" rel="noopener">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none">
              <path d="M18 13V19C18 20.1 17.1 21 16 21H5C3.9 21 3 20.1 3 19V8C3 6.9 3.9 6 5 6H11M15 3H21V9M10 14L21 3"
                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Lihat di Google Maps
          </a>
        </div>
      </div>
    </div>

    <div>
      <div class="footer-col-label">Kontak Kami</div>
      <div class="contact-item">
        <div class="contact-icon">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M6.6 10.8C7.8 13.2 9.8 15.1 12.2 16.4L14.1 14.5C14.4 14.3 14.7 14.2 14.9 14.4C15.7 14.7 16.6 14.9 17.5 14.9C17.8 14.9 18 15.1 18 15.4V17.5C18 17.8 17.8 18 17.5 18C10.1 18 4 11.9 4 4.5C4 4.2 4.2 4 4.5 4H6.6C6.9 4 7.1 4.2 7.1 4.5C7.1 5.4 7.3 6.3 7.6 7.1C7.7 7.4 7.6 7.7 7.4 7.9L6.6 10.8Z" fill="#2563eb"/>
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
            <path d="M20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z" fill="#2563eb"/>
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
            <circle cx="12" cy="12" r="9" stroke="#2563eb" stroke-width="1.6"/>
            <path d="M12 7V12L15 14" stroke="#2563eb" stroke-width="1.6" stroke-linecap="round"/>
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

<!-- ── SCRIPT ── -->
<script>
  const HERO_FALLBACK = 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1200&q=80';

  /** Tampilkan gambar di card kanan hero */
  function setHeroImage(url) {
    const skeleton = document.getElementById('img-skeleton');
    const img      = document.getElementById('hero-img');
    const loader   = new Image();

    loader.onload = () => {
      img.src = url;
      img.style.display = 'block';
      skeleton.style.display = 'none';
    };
    loader.onerror = () => {
      const fb = new Image();
      fb.onload = () => {
        img.src = HERO_FALLBACK;
        img.style.display = 'block';
        skeleton.style.display = 'none';
      };
      fb.src = HERO_FALLBACK;
    };
    loader.src = url;
  }

  /** Set logo di nav & footer */
  function setLogo(src) {
    ['nav-logo-wrap', 'footer-logo-wrap'].forEach(id => {
      const el = document.getElementById(id);
      if (!el) return;
      const img = document.createElement('img');
      img.src = src; img.alt = 'Logo Sekolah';
      img.onerror = function () {
        this.parentElement.classList.remove('has-img');
        this.remove();
      };
      el.classList.add('has-img');
      el.innerHTML = '';
      el.appendChild(img);
    });
  }

  // ── Fetch /api/konten-umum ──
  fetch('/api/konten-umum')
    .then(r => r.json())
    .then(res => {
      const d = res.data;

      if (d?.gambar_beranda) {
        setHeroImage(d.gambar_beranda);
        setLogo(d.gambar_beranda);
      } else {
        setHeroImage(HERO_FALLBACK);
      }

      document.getElementById('total-guru').innerText  = d?.total_guru  ?? '0';
      document.getElementById('total-siswa').innerText = d?.total_siswa ?? '0';
      if (d?.total_guru)  { document.getElementById('trend-guru').style.display  = 'inline-flex'; }
      if (d?.total_siswa) { document.getElementById('trend-siswa').style.display = 'inline-flex'; }

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
          <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#f59e0b"/>
        </svg>
        Terakreditasi ${akr}`;

      if (d?.maps_url) document.getElementById('maps-link').href = d.maps_url;
    })
    .catch(() => {
      setHeroImage(HERO_FALLBACK);
      ['total-guru', 'total-siswa'].forEach(id =>
        document.getElementById(id).innerText = '0'
      );
      document.getElementById('visi').innerText = 'Data tidak tersedia.';
      document.getElementById('misi-list').innerHTML =
        '<li class="misi-item"><span class="misi-text">Data tidak tersedia.</span></li>';
      ['alamat', 'telepon', 'email', 'jam'].forEach(id =>
        document.getElementById(id).innerText = '-'
      );
    });
</script>
</body>
</html>