@extends('layouts.app')

@section('title','Presensi Guru')

@section('content')

<div class="pg-wrapper">

    {{-- HEADER --}}
    <div class="pg-header">
        <div class="pg-header-left">
            <div class="pg-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <h2 class="pg-title">Presensi Guru</h2>
                <p class="pg-subtitle">Rekap kehadiran tenaga pengajar</p>
            </div>
        </div>
        <div class="pg-badge" id="totalBadge">— guru</div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="summary-grid" id="summaryGrid">
        <div class="sum-card h">
            <div class="sum-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div><div class="sum-num" id="sumHadir">0</div><div class="sum-lbl">Total Hadir</div></div>
        </div>
        <div class="sum-card i">
            <div class="sum-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div><div class="sum-num" id="sumIzin">0</div><div class="sum-lbl">Total Izin</div></div>
        </div>
        <div class="sum-card s">
            <div class="sum-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div><div class="sum-num" id="sumSakit">0</div><div class="sum-lbl">Total Sakit</div></div>
        </div>
        <div class="sum-card a">
            <div class="sum-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div><div class="sum-num" id="sumAlpa">0</div><div class="sum-lbl">Total Alpa</div></div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="chart-row">
        <div class="chart-card">
            <div class="chart-card-title">Distribusi Kehadiran</div>
            <div class="chart-card-sub">Persentase status presensi seluruh guru</div>
            <div class="chart-wrap" style="height:200px">
                <canvas id="chartDonut" role="img" aria-label="Donut chart distribusi kehadiran guru">Distribusi status kehadiran guru.</canvas>
            </div>
            <div class="chart-legend" id="legendDonut"></div>
        </div>
        <div class="chart-card">
            <div class="chart-card-title">Tren Kehadiran per Guru</div>
            <div class="chart-card-sub">Perbandingan jumlah hadir tiap guru</div>
            <div class="chart-wrap" style="height:200px">
                <canvas id="chartBar" role="img" aria-label="Bar chart kehadiran per guru">Jumlah kehadiran per guru.</canvas>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-card">
        <div class="filter-inner">
            <div class="search-wrap">
                <svg class="search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="searchGuru" placeholder="Cari nama guru...">
            </div>
            <select id="filter_tahun"><option value="">Semua Tahun</option></select>
            <select id="filterBulan">
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

    {{-- LEGEND --}}
    <div class="pg-legend">
        <span class="leg-item"><span class="leg-dot h"></span>Hadir</span>
        <span class="leg-item"><span class="leg-dot i"></span>Izin</span>
        <span class="leg-item"><span class="leg-dot s"></span>Sakit</span>
        <span class="leg-item"><span class="leg-dot a"></span>Alpa</span>
    </div>

    {{-- CONTENT --}}
    <div id="presensiContainer">
        <div class="pg-loading">
            <div class="pg-spinner"></div>
            <span>Memuat data presensi...</span>
        </div>
    </div>

</div>

@endsection

@section('script')
<style>

:root {
    --navy:      #0f2d52;
    --navy-2:    #1a4276;
    --navy-3:    #2563a8;
    --bg:        #f0f4f9;
    --card:      #ffffff;
    --border:    #e4eaf3;
    --text:      #0f2d52;
    --muted:     #64748b;

    --h-c: #059669; --h-bg: #d1fae5; --h-txt: #065f46;
    --i-c: #d97706; --i-bg: #fef3c7; --i-txt: #92400e;
    --s-c: #2563eb; --s-bg: #dbeafe; --s-txt: #1e3a8a;
    --a-c: #dc2626; --a-bg: #fee2e2; --a-txt: #7f1d1d;

    --radius: 14px;
    --shadow: 0 1px 3px rgba(15,45,82,.05), 0 4px 16px rgba(15,45,82,.07);
}

* { box-sizing: border-box; }

.pg-wrapper {
    font-family: 'Segoe UI', system-ui, sans-serif;
    color: var(--text);
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 4px 48px;
}

/* ── HEADER ── */
.pg-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 12px;
}
.pg-header-left { display: flex; align-items: center; gap: 14px; }
.pg-icon-wrap {
    width: 46px; height: 46px;
    background: linear-gradient(135deg, var(--navy), var(--navy-3));
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(15,45,82,.25);
    flex-shrink: 0;
}
.pg-title { font-size: 22px; font-weight: 700; color: var(--navy); margin: 0; line-height: 1.2; }
.pg-subtitle { font-size: 13px; color: var(--muted); margin: 3px 0 0; }
.pg-badge {
    background: var(--navy);
    color: #fff;
    font-size: 12.5px;
    font-weight: 600;
    padding: 7px 16px;
    border-radius: 999px;
    letter-spacing: .3px;
}

/* ── SUMMARY CARDS ── */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 18px;
}
.sum-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
}
.sum-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    border-radius: 4px 0 0 4px;
}
.sum-card.h::before { background: var(--h-c); }
.sum-card.i::before { background: var(--i-c); }
.sum-card.s::before { background: var(--s-c); }
.sum-card.a::before { background: var(--a-c); }

.sum-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.sum-card.h .sum-icon { background: var(--h-bg); color: var(--h-c); }
.sum-card.i .sum-icon { background: var(--i-bg); color: var(--i-c); }
.sum-card.s .sum-icon { background: var(--s-bg); color: var(--s-c); }
.sum-card.a .sum-icon { background: var(--a-bg); color: var(--a-c); }

.sum-num { font-size: 26px; font-weight: 700; line-height: 1; }
.sum-card.h .sum-num { color: var(--h-c); }
.sum-card.i .sum-num { color: var(--i-c); }
.sum-card.s .sum-num { color: var(--s-c); }
.sum-card.a .sum-num { color: var(--a-c); }
.sum-lbl { font-size: 11px; color: var(--muted); margin-top: 4px; font-weight: 500; }

/* ── CHARTS ── */
.chart-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 18px;
}
.chart-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 20px;
    box-shadow: var(--shadow);
}
.chart-card-title { font-size: 13.5px; font-weight: 700; color: var(--navy); margin-bottom: 2px; }
.chart-card-sub   { font-size: 11.5px; color: var(--muted); margin-bottom: 14px; }
.chart-wrap { position: relative; }
.chart-legend {
    display: flex; flex-wrap: wrap;
    gap: 10px 16px;
    margin-top: 12px;
}
.cl-item {
    display: flex; align-items: center; gap: 5px;
    font-size: 11.5px; color: var(--muted); font-weight: 500;
}
.cl-box { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }

/* ── FILTER ── */
.filter-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 13px 16px;
    margin-bottom: 14px;
    box-shadow: var(--shadow);
}
.filter-inner { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.search-wrap { position: relative; flex: 1; min-width: 180px; }
.search-icon {
    position: absolute; left: 10px; top: 50%;
    transform: translateY(-50%);
    color: var(--muted); pointer-events: none;
}
.filter-inner input,
.filter-inner select {
    font-family: inherit;
    padding: 9px 12px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: 13px;
    color: var(--text);
    background: #f7fafd;
    outline: none;
    transition: border-color .18s, box-shadow .18s;
}
.search-wrap input { width: 100%; padding-left: 34px; }
.filter-inner input:focus,
.filter-inner select:focus {
    border-color: var(--navy-3);
    box-shadow: 0 0 0 3px rgba(37,99,168,.1);
    background: #fff;
}

/* ── LEGEND ── */
.pg-legend {
    display: flex; gap: 16px; margin-bottom: 14px; flex-wrap: wrap;
}
.leg-item {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: var(--muted); font-weight: 500;
}
.leg-dot { width: 11px; height: 11px; border-radius: 3px; }
.leg-dot.h { background: var(--h-c); }
.leg-dot.i { background: var(--i-c); }
.leg-dot.s { background: var(--s-c); }
.leg-dot.a { background: var(--a-c); }

/* ── GURU CARD ── */
.guru-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    margin-bottom: 14px;
    overflow: hidden;
    box-shadow: var(--shadow);
    animation: fadeUp .3s ease both;
}
@keyframes fadeUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

.guru-head {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap; gap: 10px;
    cursor: pointer; user-select: none;
    transition: background .15s;
}
.guru-head:hover { background: #f7fafd; }

.guru-name { display: flex; align-items: center; gap: 10px; }
.guru-avatar {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, var(--navy), var(--navy-3));
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 700; font-size: 14px; flex-shrink: 0;
}
.guru-nama-text { font-weight: 700; font-size: 14.5px; color: var(--navy); }

.guru-rekap { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
.chip {
    display: flex; align-items: center; gap: 4px;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11.5px; font-weight: 600;
}
.chip.h { background: var(--h-bg); color: var(--h-txt); }
.chip.i { background: var(--i-bg); color: var(--i-txt); }
.chip.s { background: var(--s-bg); color: var(--s-txt); }
.chip.a { background: var(--a-bg); color: var(--a-txt); }
.chip-dot { width: 6px; height: 6px; border-radius: 50%; }
.chip.h .chip-dot { background: var(--h-c); }
.chip.i .chip-dot { background: var(--i-c); }
.chip.s .chip-dot { background: var(--s-c); }
.chip.a .chip-dot { background: var(--a-c); }

.toggle-icon {
    font-size: 18px; color: var(--muted);
    transition: transform .25s; flex-shrink: 0;
    line-height: 1;
}
.guru-card.open .toggle-icon { transform: rotate(180deg); }

/* ── MINI BAR ── */
.mini-bar-wrap {
    padding: 10px 18px 0;
    display: flex; gap: 2px; height: 6px;
}
.mini-seg {
    height: 6px; border-radius: 3px;
    transition: width .4s ease;
}
.mini-seg.h { background: var(--h-c); }
.mini-seg.i { background: var(--i-c); }
.mini-seg.s { background: var(--s-c); }
.mini-seg.a { background: var(--a-c); }

/* ── COLLAPSIBLE BODY ── */
.guru-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height .35s ease;
}
.guru-card.open .guru-body { max-height: 800px; }

/* ── TABLE ── */
.guru-table-wrap {
    overflow-x: auto;
    padding: 14px 18px 16px;
}
.guru-table {
    border-collapse: collapse;
    font-size: 11.5px;
    min-width: 680px;
    width: 100%;
}
.guru-table th {
    background: var(--navy);
    color: #fff;
    padding: 6px 4px;
    text-align: center;
    font-weight: 600;
    min-width: 26px;
}
.guru-table th:first-child { text-align: left; padding-left: 10px; min-width: 60px; }
.guru-table td {
    border: 1px solid #edf2f7;
    padding: 6px 3px;
    text-align: center;
    font-weight: 700;
    font-size: 10.5px;
    transition: filter .1s;
}
.guru-table td.kosong { color: #d1d5db; font-weight: 400; }
.guru-table td.hadir  { background: var(--h-bg); color: var(--h-txt); }
.guru-table td.izin   { background: var(--i-bg); color: var(--i-txt); }
.guru-table td.sakit  { background: var(--s-bg); color: var(--s-txt); }
.guru-table td.alpa   { background: var(--a-bg); color: var(--a-txt); }
.guru-table tr:hover td { filter: brightness(.96); }
.row-label {
    background: #f4f7fc !important;
    color: var(--muted) !important;
    font-size: 10.5px; font-weight: 600;
    text-align: left !important;
    padding-left: 10px !important;
    border-right: 2px solid var(--border) !important;
}

/* ── STATES ── */
.pg-loading {
    display: flex; align-items: center; justify-content: center;
    gap: 12px; padding: 70px 20px;
    color: var(--muted); font-size: 14px;
}
.pg-spinner {
    width: 22px; height: 22px;
    border: 3px solid var(--border);
    border-top-color: var(--navy);
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.pg-empty { text-align: center; padding: 60px 20px; color: var(--muted); font-size: 14px; }
.pg-empty-icon { font-size: 38px; margin-bottom: 10px; }

/* ── RESPONSIVE ── */
@media(max-width: 700px) {
    .summary-grid { grid-template-columns: repeat(2, 1fr); }
    .chart-row    { grid-template-columns: 1fr; }
    .pg-title     { font-size: 18px; }
}
@media(max-width: 420px) {
    .summary-grid { grid-template-columns: 1fr 1fr; }
}

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>

<script>

let allData = [];
let chartDonut = null;
let chartBar   = null;

const COLORS = {
    h: { fill: '#059669', bg: '#d1fae5', txt: '#065f46' },
    i: { fill: '#d97706', bg: '#fef3c7', txt: '#92400e' },
    s: { fill: '#2563eb', bg: '#dbeafe', txt: '#1e3a8a' },
    a: { fill: '#dc2626', bg: '#fee2e2', txt: '#7f1d1d' },
};

document.addEventListener('DOMContentLoaded', async () => {
    await loadTahun();
    await loadData();
    document.getElementById('filter_tahun').addEventListener('change', render);
    document.getElementById('filterBulan').addEventListener('change', render);
    document.getElementById('searchGuru').addEventListener('input', render);
});

/* ── LOAD ── */
async function loadTahun() {
    try {
        const res  = await fetch('/api/tahun-ajaran');
        const json = await res.json();
        const sel  = document.getElementById('filter_tahun');
        let html   = '<option value="">Semua Tahun</option>';
        json.data.forEach(t => {
            html += `<option value="${t.id_tahun_ajaran}">${t.periode} – ${t.semester}</option>`;
        });
        sel.innerHTML = html;
    } catch(e) { console.error('Gagal memuat tahun ajaran', e); }
}

async function loadData() {
    try {
        const res  = await fetch('/api/presensi-guru');
        const json = await res.json();
        allData = json.data || [];
        render();
    } catch(e) {
        document.getElementById('presensiContainer').innerHTML = `
            <div class="pg-empty">
                <div class="pg-empty-icon"></div>
                Gagal memuat data. Silakan coba lagi.
            </div>`;
    }
}

/* ── RENDER ── */
function render() {
    let data    = [...allData];
    const tahun   = document.getElementById('filter_tahun').value;
    const bulan   = document.getElementById('filterBulan').value;
    const keyword = document.getElementById('searchGuru').value.toLowerCase().trim();

    if (tahun)   data = data.filter(d => d.id_tahun_ajaran == tahun);
    if (bulan)   data = data.filter(d => d.tanggal.split('-')[1] === bulan);
    if (keyword) data = data.filter(d => (d.guru?.nama_guru || '').toLowerCase().includes(keyword));

    const container = document.getElementById('presensiContainer');
    const badge     = document.getElementById('totalBadge');

    if (!data.length) {
        container.innerHTML = `
            <div class="pg-empty">
                <div class="pg-empty-icon"></div>
                Tidak ada data presensi yang cocok.
            </div>`;
        badge.textContent = '0 guru';
        updateSummary(0, 0, 0, 0);
        updateCharts({}, []);
        return;
    }

    /* group by guru */
    const grouped = {};
    data.forEach(p => {
        const id = p.id_guru;
        if (!grouped[id]) grouped[id] = { nama: p.guru?.nama_guru || '-', list: [] };
        grouped[id].list.push(p);
    });

    const guruList = Object.values(grouped);
    badge.textContent = `${guruList.length} guru`;

    let totH = 0, totI = 0, totS = 0, totA = 0;
    const barLabels = [], barHadir = [];

    let html = '';

    guruList.forEach((g, idx) => {
        let hadir = 0, izin = 0, sakit = 0, alpa = 0;
        const map = {};

        g.list.forEach(p => {
            const day = parseInt(p.tanggal.split('-')[2]);
            const st  = (p.status?.nama_status || '').toLowerCase();
            map[day]  = st;
            if      (st === 'hadir') hadir++;
            else if (st === 'izin')  izin++;
            else if (st === 'sakit') sakit++;
            else if (st === 'alpa')  alpa++;
        });

        totH += hadir; totI += izin; totS += sakit; totA += alpa;
        barLabels.push(g.nama.split(' ')[0]);
        barHadir.push(hadir);

        const total = hadir + izin + sakit + alpa || 1;
        const pH = (hadir / total * 100).toFixed(1);
        const pI = (izin  / total * 100).toFixed(1);
        const pS = (sakit / total * 100).toFixed(1);
        const pA = (alpa  / total * 100).toFixed(1);

        const initial = g.nama.charAt(0).toUpperCase();

        let cells = '', headers = '';
        for (let i = 1; i <= 31; i++) {
            const st = map[i];
            headers += `<th>${i}</th>`;
            if (!st) cells += `<td class="kosong">·</td>`;
            else cells += `<td class="${st}">${st.charAt(0).toUpperCase()}</td>`;
        }

        html += `
        <div class="guru-card" id="card-${idx}" style="animation-delay:${idx * 35}ms">
            <div class="guru-head" onclick="toggleCard(${idx})">
                <div class="guru-name">
                    <div class="guru-avatar">${initial}</div>
                    <span class="guru-nama-text">${g.nama}</span>
                </div>
                <div class="guru-rekap">
                    <span class="chip h"><span class="chip-dot"></span>${hadir} Hadir</span>
                    <span class="chip i"><span class="chip-dot"></span>${izin} Izin</span>
                    <span class="chip s"><span class="chip-dot"></span>${sakit} Sakit</span>
                    <span class="chip a"><span class="chip-dot"></span>${alpa} Alpa</span>
                    <span class="toggle-icon">▾</span>
                </div>
            </div>
            <div class="mini-bar-wrap">
                <div class="mini-seg h" style="width:${pH}%"></div>
                <div class="mini-seg i" style="width:${pI}%"></div>
                <div class="mini-seg s" style="width:${pS}%"></div>
                <div class="mini-seg a" style="width:${pA}%"></div>
            </div>
            <div class="guru-body">
                <div class="guru-table-wrap">
                    <table class="guru-table">
                        <tr><th class="row-label">Tgl</th>${headers}</tr>
                        <tr><td class="row-label">Status</td>${cells}</tr>
                    </table>
                </div>
            </div>
        </div>`;
    });

    container.innerHTML = html;
    updateSummary(totH, totI, totS, totA);
    updateCharts({ h: totH, i: totI, s: totS, a: totA }, { labels: barLabels, data: barHadir });
}

/* ── TOGGLE ── */
function toggleCard(idx) {
    document.getElementById('card-' + idx).classList.toggle('open');
}

/* ── SUMMARY ── */
function updateSummary(h, i, s, a) {
    document.getElementById('sumHadir').textContent = h;
    document.getElementById('sumIzin').textContent  = i;
    document.getElementById('sumSakit').textContent = s;
    document.getElementById('sumAlpa').textContent  = a;
}

/* ── CHARTS ── */
function updateCharts(totals, bar) {
    updateDonut(totals);
    updateBar(bar);
}

function updateDonut(t) {
    const canvas = document.getElementById('chartDonut');
    const legend = document.getElementById('legendDonut');
    const labels = ['Hadir', 'Izin', 'Sakit', 'Alpa'];
    const values = [t.h || 0, t.i || 0, t.s || 0, t.a || 0];
    const colors = [COLORS.h.fill, COLORS.i.fill, COLORS.s.fill, COLORS.a.fill];
    const bgs    = [COLORS.h.bg,   COLORS.i.bg,   COLORS.s.bg,   COLORS.a.bg];
    const total  = values.reduce((a, b) => a + b, 0) || 1;

    /* legend */
    legend.innerHTML = labels.map((l, j) =>
        `<span class="cl-item">
            <span class="cl-box" style="background:${colors[j]}"></span>
            ${l} ${((values[j]/total)*100).toFixed(1)}%
        </span>`
    ).join('');

    if (chartDonut) { chartDonut.data.datasets[0].data = values; chartDonut.update(); return; }

    chartDonut = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{ data: values, backgroundColor: colors, hoverBackgroundColor: colors, borderWidth: 2, borderColor: '#fff', hoverBorderWidth: 0 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.raw} (${((ctx.raw/total)*100).toFixed(1)}%)`
                    }
                }
            }
        }
    });
}

function updateBar(bar) {
    const canvas = document.getElementById('chartBar');
    if (!bar.labels || !bar.labels.length) {
        if (chartBar) { chartBar.data.labels = []; chartBar.data.datasets[0].data = []; chartBar.update(); }
        return;
    }

    const maxBar = Math.max(...(bar.data || [0]));

    if (chartBar) {
        chartBar.data.labels = bar.labels;
        chartBar.data.datasets[0].data = bar.data;
        chartBar.update();
        return;
    }

    chartBar = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: bar.labels,
            datasets: [{
                label: 'Hadir',
                data: bar.data,
                backgroundColor: COLORS.h.bg,
                borderColor: COLORS.h.fill,
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` Hadir: ${ctx.raw} hari` } }
            },
            scales: {
                x: {
                    ticks: { font: { size: 11 }, color: '#64748b', maxRotation: 35 },
                    grid: { display: false },
                    border: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 11 }, color: '#64748b' },
                    grid: { color: '#f0f4f9' },
                    border: { display: false, dash: [4,4] }
                }
            }
        }
    });
}

</script>

@endsection