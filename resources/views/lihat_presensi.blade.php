@extends('layouts.app')

@section('title','Presensi Saya')

@section('content')

<div class="ps-wrapper">

    {{-- HEADER --}}
    <div class="ps-header">
        <div class="ps-header-left">
            <div class="ps-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div>
                <h2 class="ps-title">Presensi Saya</h2>
                <p class="ps-subtitle">Riwayat kehadiran pegawai</p>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-card">
        <div class="filter-inner">
            <select id="filterBulan" onchange="renderPresensi()">
                <option value="">Semua Bulan</option>
                <option value="01">Januari</option><option value="02">Februari</option>
                <option value="03">Maret</option><option value="04">April</option>
                <option value="05">Mei</option><option value="06">Juni</option>
                <option value="07">Juli</option><option value="08">Agustus</option>
                <option value="09">September</option><option value="10">Oktober</option>
                <option value="11">November</option><option value="12">Desember</option>
            </select>
        </div>
    </div>

    {{-- CONTENT --}}
    <div id="presensiContainer">
        <div class="ps-loading">
            <div class="ps-spinner"></div>
            <span>Memuat data presensi...</span>
        </div>
    </div>

</div>

@endsection

@section('script')
<style>

:root {
    --navy:   #0f2d52;
    --navy-2: #1a4276;
    --navy-3: #2563a8;
    --bg:     #f0f4f9;
    --card:   #ffffff;
    --border: #e4eaf3;
    --text:   #0f2d52;
    --muted:  #64748b;

    --h-c: #059669; --h-bg: #d1fae5; --h-txt: #065f46;
    --i-c: #d97706; --i-bg: #fef3c7; --i-txt: #92400e;
    --s-c: #2563eb; --s-bg: #dbeafe; --s-txt: #1e3a8a;
    --a-c: #dc2626; --a-bg: #fee2e2; --a-txt: #7f1d1d;

    --radius: 14px;
    --shadow: 0 1px 3px rgba(15,45,82,.05), 0 4px 16px rgba(15,45,82,.07);
}

* { box-sizing: border-box; }

.ps-wrapper {
    font-family: 'Segoe UI', system-ui, sans-serif;
    color: var(--text);
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 4px 48px;
}

/* ── HEADER ── */
.ps-header {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    flex-wrap: wrap; gap: 12px;
}
.ps-header-left { display: flex; align-items: center; gap: 14px; }
.ps-icon-wrap {
    width: 46px; height: 46px;
    background: linear-gradient(135deg, var(--navy), var(--navy-3));
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(15,45,82,.25); flex-shrink: 0;
}
.ps-title    { font-size: 22px; font-weight: 700; color: var(--navy); margin: 0; line-height: 1.2; }
.ps-subtitle { font-size: 13px; color: var(--muted); margin: 3px 0 0; }

/* ── FILTER ── */
.filter-card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 13px 16px;
    margin-bottom: 20px; box-shadow: var(--shadow);
}
.filter-inner { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.filter-inner select {
    font-family: inherit; padding: 9px 14px;
    border: 1.5px solid var(--border); border-radius: 8px;
    font-size: 13px; color: var(--text); background: #f7fafd; outline: none;
    transition: border-color .18s, box-shadow .18s; min-width: 180px;
}
.filter-inner select:focus {
    border-color: var(--navy-3);
    box-shadow: 0 0 0 3px rgba(37,99,168,.1);
    background: #fff;
}

/* ── TAHUN SECTION ── */
.tahun-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 22px 22px 16px;
    margin-bottom: 20px;
    box-shadow: var(--shadow);
    animation: fadeUp .3s ease both;
}
@keyframes fadeUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

/* ── TAHUN LABEL ── */
.tahun-label-wrap {
    display: flex; align-items: center;
    justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
    margin-bottom: 18px;
}
.tahun-label {
    display: inline-flex; align-items: center; gap: 8px;
    background: #eff6ff; color: var(--navy);
    padding: 8px 14px; border-radius: 10px;
    font-size: 13px; font-weight: 600;
}
.tahun-label svg { opacity: .7; }

/* ── CHART DONUT (per tahun) ── */
.tahun-charts {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 20px;
    align-items: center;
    margin-bottom: 22px;
}
.donut-wrap { position: relative; height: 160px; }
.donut-center {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    pointer-events: none;
}
.donut-total { font-size: 22px; font-weight: 700; color: var(--navy); line-height: 1; }
.donut-lbl   { font-size: 10px; color: var(--muted); margin-top: 3px; font-weight: 500; }

/* ── REKAP CARDS ── */
.rekap-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}
.rekap-card {
    border-radius: 12px; padding: 14px 16px;
    display: flex; align-items: center; gap: 12px;
    position: relative; overflow: hidden;
}
.rekap-card::before {
    content: ''; position: absolute;
    left: 0; top: 0; bottom: 0; width: 4px;
    border-radius: 4px 0 0 4px;
}
.rekap-card.h { background: var(--h-bg); }
.rekap-card.i { background: var(--i-bg); }
.rekap-card.s { background: var(--s-bg); }
.rekap-card.a { background: var(--a-bg); }
.rekap-card.h::before { background: var(--h-c); }
.rekap-card.i::before { background: var(--i-c); }
.rekap-card.s::before { background: var(--s-c); }
.rekap-card.a::before { background: var(--a-c); }
.rekap-icon {
    width: 34px; height: 34px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rekap-card.h .rekap-icon { background: rgba(5,150,105,.15); color: var(--h-c); }
.rekap-card.i .rekap-icon { background: rgba(217,119,6,.15);  color: var(--i-c); }
.rekap-card.s .rekap-icon { background: rgba(37,99,235,.15);  color: var(--s-c); }
.rekap-card.a .rekap-icon { background: rgba(220,38,38,.15);  color: var(--a-c); }
.rekap-angka { font-size: 22px; font-weight: 700; line-height: 1; }
.rekap-card.h .rekap-angka { color: var(--h-c); }
.rekap-card.i .rekap-angka { color: var(--i-c); }
.rekap-card.s .rekap-angka { color: var(--s-c); }
.rekap-card.a .rekap-angka { color: var(--a-c); }
.rekap-label { font-size: 11px; font-weight: 500; color: var(--muted); margin-top: 3px; }

/* ── MINI BAR ── */
.mini-bar-wrap { display: flex; height: 6px; border-radius: 4px; overflow: hidden; gap: 2px; margin-bottom: 20px; }
.mini-seg { height: 100%; border-radius: 3px; transition: width .4s ease; }
.mini-seg.h { background: var(--h-c); }
.mini-seg.i { background: var(--i-c); }
.mini-seg.s { background: var(--s-c); }
.mini-seg.a { background: var(--a-c); }

/* ── BULAN ── */
.bulan-title {
    font-size: 13px; font-weight: 600;
    color: var(--navy); margin: 20px 0 8px;
    display: flex; align-items: center; gap: 7px;
}
.bulan-title::before {
    content: ''; display: inline-block;
    width: 4px; height: 14px;
    background: var(--navy-3); border-radius: 2px;
}

/* ── TABLE ── */
.kal-wrap {
    overflow-x: auto;
    border: 1px solid var(--border);
    border-radius: 10px;
    margin-bottom: 6px;
}
.kal-table {
    width: 100%; border-collapse: collapse; min-width: 900px;
}
.kal-table th {
    background: var(--navy); color: #fff;
    padding: 7px 4px; font-size: 11px; font-weight: 600;
    text-align: center; min-width: 26px;
}
.kal-table th:first-child { text-align: left; padding-left: 12px; min-width: 70px; }
.kal-table td {
    border: 1px solid #edf2f7; padding: 7px 3px;
    text-align: center; font-size: 10.5px; font-weight: 700;
}
.kal-table td.kosong { color: #d1d5db; font-weight: 400; }
.kal-table td.hadir  { background: var(--h-bg); color: var(--h-txt); }
.kal-table td.izin   { background: var(--i-bg); color: var(--i-txt); }
.kal-table td.sakit  { background: var(--s-bg); color: var(--s-txt); }
.kal-table td.alpa   { background: var(--a-bg); color: var(--a-txt); }
.kal-table tr:hover td { filter: brightness(.97); }
.row-label {
    background: #f4f7fc !important; color: var(--muted) !important;
    font-size: 10.5px; font-weight: 600;
    text-align: left !important; padding-left: 12px !important;
    border-right: 2px solid var(--border) !important;
}

/* ── STATES ── */
.ps-loading {
    display: flex; align-items: center; justify-content: center;
    gap: 12px; padding: 70px 20px; color: var(--muted); font-size: 14px;
}
.ps-spinner {
    width: 22px; height: 22px;
    border: 3px solid var(--border); border-top-color: var(--navy);
    border-radius: 50%; animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.ps-empty { text-align: center; padding: 60px 20px; color: var(--muted); font-size: 14px; }
.ps-empty-icon { font-size: 38px; margin-bottom: 10px; }

/* ── RESPONSIVE ── */
@media(max-width: 640px) {
    .tahun-charts { grid-template-columns: 1fr; }
    .donut-wrap   { height: 140px; }
    .rekap-grid   { grid-template-columns: repeat(2, 1fr); }
    .ps-title     { font-size: 18px; }
}

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>

<script>

let allPresensi = [];
const donutCharts = {};

const namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                   'Juli','Agustus','September','Oktober','November','Desember'];

const COLORS = {
    h: { fill: '#059669', bg: '#d1fae5' },
    i: { fill: '#d97706', bg: '#fef3c7' },
    s: { fill: '#2563eb', bg: '#dbeafe' },
    a: { fill: '#dc2626', bg: '#fee2e2' },
};

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', loadPresensiSaya);

/* ── LOAD ── */
function loadPresensiSaya() {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) {
        document.getElementById('presensiContainer').innerHTML =
            `<div class="ps-empty"><div class="ps-empty-icon"></div>User tidak ditemukan.</div>`;
        return;
    }

    fetch('/api/presensi-saya/' + user.id)
        .then(r => r.json())
        .then(r => { allPresensi = r.data || []; renderPresensi(); })
        .catch(() => {
            document.getElementById('presensiContainer').innerHTML =
                `<div class="ps-empty"><div class="ps-empty-icon"></div>Gagal memuat data.</div>`;
        });
}

/* ── RENDER ── */
function renderPresensi() {
    const bulanFilter = document.getElementById('filterBulan').value;

    let data = allPresensi;
    if (bulanFilter) {
        data = data.filter(p => p.tanggal.split('-')[1] === bulanFilter);
    }

    if (!data.length) {
        document.getElementById('presensiContainer').innerHTML =
            `<div class="ps-empty"><div class="ps-empty-icon"></div>Tidak ada data presensi.</div>`;
        return;
    }

    /* group by tahun ajaran */
    const grouped = {};
    data.forEach(p => {
        const key = p.tahun_ajaran?.periode || 'Tanpa Tahun';
        if (!grouped[key]) grouped[key] = [];
        grouped[key].push(p);
    });

    let html = '';
    let tahunIdx = 0;

    Object.keys(grouped).forEach(tahun => {
        const list = grouped[tahun];
        let hadir = 0, izin = 0, sakit = 0, alpa = 0;

        list.forEach(p => {
            const s = (p.status?.nama_status || '').toLowerCase();
            if      (s === 'hadir') hadir++;
            else if (s === 'izin')  izin++;
            else if (s === 'sakit') sakit++;
            else if (s === 'alpa')  alpa++;
        });

        const total = hadir + izin + sakit + alpa || 1;
        const pH = (hadir / total * 100).toFixed(1);
        const pI = (izin  / total * 100).toFixed(1);
        const pS = (sakit / total * 100).toFixed(1);
        const pA = (alpa  / total * 100).toFixed(1);
        const chartId = 'donut-' + tahunIdx;

        /* group by bulan */
        const bulanMap = {};
        list.forEach(p => {
            const [y, m, d] = p.tanggal.split('-');
            const bk = y + '-' + m;
            if (!bulanMap[bk]) bulanMap[bk] = {};
            bulanMap[bk][parseInt(d)] = (p.status?.nama_status || '').toLowerCase();
        });

        /* bulan tables */
        let bulanHtml = '';
        Object.keys(bulanMap).sort().forEach(bKey => {
            const bulanAngka = parseInt(bKey.split('-')[1]);
            let headers = '', cells = '';
            for (let i = 1; i <= 31; i++) {
                headers += `<th>${i}</th>`;
                const st = bulanMap[bKey][i];
                if (!st) cells += `<td class="kosong">·</td>`;
                else     cells += `<td class="${st}">${st.charAt(0).toUpperCase()}</td>`;
            }
            bulanHtml += `
            <div class="bulan-title">${namaBulan[bulanAngka]}</div>
            <div class="kal-wrap">
                <table class="kal-table">
                    <tr><th class="row-label">Tgl</th>${headers}</tr>
                    <tr><td class="row-label">Status</td>${cells}</tr>
                </table>
            </div>`;
        });

        html += `
        <div class="tahun-section" style="animation-delay:${tahunIdx * 60}ms">

            <div class="tahun-label-wrap">
                <div class="tahun-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Tahun Ajaran ${tahun}
                </div>
            </div>

            {{-- CHART + REKAP --}}
            <div class="tahun-charts">
                <div>
                    <div class="donut-wrap">
                        <canvas id="${chartId}" role="img" aria-label="Donut chart presensi tahun ${tahun}">H:${hadir} I:${izin} S:${sakit} A:${alpa}</canvas>
                        <div class="donut-center">
                            <div class="donut-total">${total}</div>
                            <div class="donut-lbl">Total Hari</div>
                        </div>
                    </div>
                </div>
                <div class="rekap-grid">
                    <div class="rekap-card h">
                        <div class="rekap-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div><div class="rekap-angka">${hadir}</div><div class="rekap-label">Hadir</div></div>
                    </div>
                    <div class="rekap-card i">
                        <div class="rekap-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <div><div class="rekap-angka">${izin}</div><div class="rekap-label">Izin</div></div>
                    </div>
                    <div class="rekap-card s">
                        <div class="rekap-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        </div>
                        <div><div class="rekap-angka">${sakit}</div><div class="rekap-label">Sakit</div></div>
                    </div>
                    <div class="rekap-card a">
                        <div class="rekap-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        </div>
                        <div><div class="rekap-angka">${alpa}</div><div class="rekap-label">Alpa</div></div>
                    </div>
                </div>
            </div>

            {{-- MINI BAR --}}
            <div class="mini-bar-wrap">
                <div class="mini-seg h" style="width:${pH}%"></div>
                <div class="mini-seg i" style="width:${pI}%"></div>
                <div class="mini-seg s" style="width:${pS}%"></div>
                <div class="mini-seg a" style="width:${pA}%"></div>
            </div>

            {{-- TABEL BULAN --}}
            ${bulanHtml}

        </div>`;

        tahunIdx++;
    });

    document.getElementById('presensiContainer').innerHTML = html;

    /* render donut per tahun setelah DOM siap */
    let idx = 0;
    Object.keys(grouped).forEach(tahun => {
        const list = grouped[tahun];
        let h = 0, i = 0, s = 0, a = 0;
        list.forEach(p => {
            const st = (p.status?.nama_status || '').toLowerCase();
            if      (st === 'hadir') h++;
            else if (st === 'izin')  i++;
            else if (st === 'sakit') s++;
            else if (st === 'alpa')  a++;
        });

        const cid = 'donut-' + idx;
        if (donutCharts[cid]) { donutCharts[cid].destroy(); }

        donutCharts[cid] = new Chart(document.getElementById(cid), {
            type: 'doughnut',
            data: {
                labels: ['Hadir','Izin','Sakit','Alpa'],
                datasets: [{
                    data: [h, i, s, a],
                    backgroundColor: [COLORS.h.fill, COLORS.i.fill, COLORS.s.fill, COLORS.a.fill],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverBorderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                const tot = ctx.dataset.data.reduce((a,b)=>a+b,0)||1;
                                return ` ${ctx.label}: ${ctx.raw} (${(ctx.raw/tot*100).toFixed(1)}%)`;
                            }
                        }
                    }
                }
            }
        });

        idx++;
    });
}

</script>

@endsection