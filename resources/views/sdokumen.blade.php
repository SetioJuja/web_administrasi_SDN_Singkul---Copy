@extends('layouts.app')

@section('title','Dokumen Administrasi')

@section('content')

<div class="da-wrapper">

    {{-- HEADER --}}
    <div class="da-header">
        <div class="da-header-left">
            <div class="da-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div>
                <h2 class="da-title">Dokumen Administrasi</h2>
                <p class="da-subtitle">Kelola dan lihat dokumen administrasi</p>
            </div>
        </div>
        <div class="da-badge" id="totalBadge">— dokumen</div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="summary-grid" id="summaryGrid">
        <div class="sum-card" id="sumTotal">
            <div class="sum-icon total">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div><div class="sum-num" id="numTotal">0</div><div class="sum-lbl">Total Dokumen</div></div>
        </div>
        <div class="sum-card" id="sumGambar">
            <div class="sum-icon gambar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <div><div class="sum-num" id="numGambar">0</div><div class="sum-lbl">File Gambar</div></div>
        </div>
        <div class="sum-card" id="sumFile">
            <div class="sum-icon file">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
            </div>
            <div><div class="sum-num" id="numFile">0</div><div class="sum-lbl">File Lainnya</div></div>
        </div>
        <div class="sum-card" id="sumTahun">
            <div class="sum-icon tahun">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div><div class="sum-num" id="numTahun">0</div><div class="sum-lbl">Tahun Ajaran</div></div>
        </div>
    </div>

    {{-- CHART --}}
    <div class="chart-row">
        <div class="chart-card">
            <div class="chart-card-title">Dokumen per Tahun Ajaran</div>
            <div class="chart-card-sub" id="chartSub">Distribusi dokumen berdasarkan tahun ajaran</div>
            <div class="chart-wrap" style="height:200px">
                <canvas id="chartBar" role="img" aria-label="Bar chart dokumen per tahun ajaran">Jumlah dokumen per tahun ajaran.</canvas>
            </div>
        </div>
        <div class="chart-card">
            <div class="chart-card-title">Distribusi Tipe File</div>
            <div class="chart-card-sub">Persentase gambar vs file lainnya</div>
            <div class="chart-wrap" style="height:200px">
                <canvas id="chartDonut" role="img" aria-label="Donut chart tipe file dokumen">Distribusi tipe file dokumen.</canvas>
            </div>
            <div class="chart-legend" id="legendDonut"></div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-card">
        <div class="filter-inner">
            <div class="search-wrap">
                <svg class="search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="searchInput" placeholder="Cari judul dokumen...">
            </div>
            <select id="filter_tahun"></select>
            <select id="filter_bulan">
                <option value="">Semua Bulan</option>
                <option value="1">Januari</option><option value="2">Februari</option>
                <option value="3">Maret</option><option value="4">April</option>
                <option value="5">Mei</option><option value="6">Juni</option>
                <option value="7">Juli</option><option value="8">Agustus</option>
                <option value="9">September</option><option value="10">Oktober</option>
                <option value="11">November</option><option value="12">Desember</option>
            </select>
            <select id="filter_minggu">
                <option value="">Semua Minggu</option>
                <option value="1">Minggu 1</option><option value="2">Minggu 2</option>
                <option value="3">Minggu 3</option><option value="4">Minggu 4</option>
                <option value="5">Minggu 5</option>
            </select>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <div class="table-wrap">
            <table class="da-table">
                <thead>
                    <tr>
                        <th class="th-no">No</th>
                        <th class="th-judul">Judul Dokumen</th>
                        <th class="th-tgl">Tanggal Upload</th>
                        <th class="th-tahun">Tahun Ajaran</th>
                        <th class="th-file">File</th>
                        <th class="th-ket">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="data"></tbody>
            </table>
        </div>
    </div>

</div>

{{-- MODAL --}}
<div id="modalImg" class="modal-overlay" onclick="tutupGambar()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="tutupGambar()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <img id="imgPreview" src="" alt="Preview Dokumen">
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

    --c1: #2563eb; --c1-bg: #dbeafe;
    --c2: #059669; --c2-bg: #d1fae5;
    --c3: #d97706; --c3-bg: #fef3c7;
    --c4: #7c3aed; --c4-bg: #ede9fe;

    --radius: 14px;
    --shadow: 0 1px 3px rgba(15,45,82,.05), 0 4px 16px rgba(15,45,82,.07);
}

* { box-sizing: border-box; }

.da-wrapper {
    font-family: 'Segoe UI', system-ui, sans-serif;
    color: var(--text);
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 4px 48px;
}

/* ── HEADER ── */
.da-header {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-bottom: 22px; flex-wrap: wrap; gap: 12px;
}
.da-header-left { display: flex; align-items: center; gap: 14px; }
.da-icon-wrap {
    width: 46px; height: 46px;
    background: linear-gradient(135deg, var(--navy), var(--navy-3));
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(15,45,82,.25); flex-shrink: 0;
}
.da-title    { font-size: 22px; font-weight: 700; color: var(--navy); margin: 0; line-height: 1.2; }
.da-subtitle { font-size: 13px; color: var(--muted); margin: 3px 0 0; }
.da-badge {
    background: var(--navy); color: #fff;
    font-size: 12.5px; font-weight: 600;
    padding: 7px 16px; border-radius: 999px; letter-spacing: .3px;
}

/* ── SUMMARY ── */
.summary-grid {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 12px; margin-bottom: 18px;
}
.sum-card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 16px;
    display: flex; align-items: center; gap: 12px;
    box-shadow: var(--shadow); position: relative; overflow: hidden;
}
.sum-card::before {
    content: ''; position: absolute;
    left: 0; top: 0; bottom: 0; width: 4px; border-radius: 4px 0 0 4px;
}
#sumTotal::before  { background: var(--c1); }
#sumGambar::before { background: var(--c2); }
#sumFile::before   { background: var(--c3); }
#sumTahun::before  { background: var(--c4); }
.sum-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.sum-icon.total  { background: var(--c1-bg); color: var(--c1); }
.sum-icon.gambar { background: var(--c2-bg); color: var(--c2); }
.sum-icon.file   { background: var(--c3-bg); color: var(--c3); }
.sum-icon.tahun  { background: var(--c4-bg); color: var(--c4); }
.sum-num { font-size: 26px; font-weight: 700; line-height: 1; }
#sumTotal  .sum-num { color: var(--c1); }
#sumGambar .sum-num { color: var(--c2); }
#sumFile   .sum-num { color: var(--c3); }
#sumTahun  .sum-num { color: var(--c4); }
.sum-lbl { font-size: 11px; color: var(--muted); margin-top: 4px; font-weight: 500; }

/* ── CHARTS ── */
.chart-row {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 14px; margin-bottom: 18px;
}
.chart-card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 18px 20px; box-shadow: var(--shadow);
}
.chart-card-title { font-size: 13.5px; font-weight: 700; color: var(--navy); margin-bottom: 2px; }
.chart-card-sub   { font-size: 11.5px; color: var(--muted); margin-bottom: 14px; }
.chart-wrap { position: relative; }
.chart-legend { display: flex; flex-wrap: wrap; gap: 10px 16px; margin-top: 12px; }
.cl-item { display: flex; align-items: center; gap: 5px; font-size: 11.5px; color: var(--muted); font-weight: 500; }
.cl-box  { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }

/* ── FILTER ── */
.filter-card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 13px 16px;
    margin-bottom: 16px; box-shadow: var(--shadow);
}
.filter-inner { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.search-wrap  { position: relative; flex: 1; min-width: 180px; }
.search-icon  { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }
.filter-inner input,
.filter-inner select {
    font-family: inherit; padding: 9px 12px;
    border: 1.5px solid var(--border); border-radius: 8px;
    font-size: 13px; color: var(--text); background: #f7fafd; outline: none;
    transition: border-color .18s, box-shadow .18s;
}
.search-wrap input { width: 100%; padding-left: 34px; }
.filter-inner input:focus,
.filter-inner select:focus {
    border-color: var(--navy-3);
    box-shadow: 0 0 0 3px rgba(37,99,168,.1);
    background: #fff;
}

/* ── TABLE ── */
.table-card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden;
}
.table-wrap { overflow-x: auto; }
.da-table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 800px; }
.da-table thead tr { background: var(--navy); }
.da-table th {
    color: #fff; padding: 12px 14px; text-align: left;
    font-size: 12px; font-weight: 600; letter-spacing: .3px; white-space: nowrap;
}
.da-table th.th-no     { width: 48px; text-align: center; }
.da-table th.th-file   { width: 110px; text-align: center; }
.da-table th.th-tgl,
.da-table th.th-tahun  { width: 140px; }
.da-table td {
    padding: 12px 14px; border-bottom: 1px solid var(--border);
    vertical-align: middle; color: var(--text);
}
.da-table td.td-no  { text-align: center; color: var(--muted); font-size: 12px; }
.da-table td.td-file { text-align: center; }
.da-table tbody tr:last-child td { border-bottom: none; }
.da-table tbody tr:hover td { background: #f7fafd; }

/* ── JUDUL ── */
.doc-judul {
    font-weight: 600; font-size: 13px; color: var(--navy); margin-bottom: 2px;
}

/* ── BADGE TAHUN ── */
.tahun-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: #eff6ff; color: var(--navy-3);
    padding: 3px 10px; border-radius: 999px;
    font-size: 11.5px; font-weight: 600;
}

/* ── FILE PREVIEW ── */
.doc-img {
    width: 64px; height: 48px; object-fit: cover;
    border-radius: 8px; cursor: pointer;
    border: 1.5px solid var(--border);
    transition: transform .18s, box-shadow .18s;
    display: block; margin: 0 auto;
}
.doc-img:hover { transform: scale(1.08); box-shadow: 0 4px 12px rgba(15,45,82,.15); }

.file-link {
    display: inline-flex; align-items: center; gap: 5px;
    color: var(--navy-3); font-weight: 600; font-size: 12px;
    text-decoration: none;
    padding: 5px 10px; border-radius: 7px;
    background: var(--c1-bg);
    transition: background .15s;
}
.file-link:hover { background: #bfdbfe; }

.no-file {
    color: var(--muted); font-size: 12px;
}

/* ── KETERANGAN ── */
.ket-text {
    color: var(--muted); font-size: 12px;
    max-width: 200px; line-height: 1.5;
}

/* ── EMPTY ── */
.da-empty { text-align: center; padding: 50px 20px; color: var(--muted); font-size: 14px; }
.da-empty-icon { font-size: 36px; margin-bottom: 10px; }

/* ── LOADING ── */
.da-loading {
    display: flex; align-items: center; justify-content: center;
    gap: 12px; padding: 50px; color: var(--muted); font-size: 14px;
}
.da-spinner {
    width: 22px; height: 22px;
    border: 3px solid var(--border); border-top-color: var(--navy);
    border-radius: 50%; animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── MODAL ── */
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(10,20,40,.82);
    justify-content: center; align-items: center;
    z-index: 999; padding: 20px;
    animation: fadeIn .2s ease;
}
.modal-overlay.open { display: flex; }
@keyframes fadeIn { from{opacity:0} to{opacity:1} }
.modal-box {
    position: relative; max-width: 88vw; max-height: 88vh;
    border-radius: 14px; overflow: hidden;
    box-shadow: 0 24px 64px rgba(0,0,0,.5);
    animation: scaleIn .22s ease;
}
@keyframes scaleIn { from{transform:scale(.92)} to{transform:scale(1)} }
.modal-box img { display: block; max-width: 88vw; max-height: 82vh; object-fit: contain; }
.modal-close {
    position: absolute; top: 10px; right: 10px;
    width: 32px; height: 32px; border-radius: 50%;
    background: rgba(255,255,255,.15); backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s;
}
.modal-close:hover { background: rgba(255,255,255,.28); }

/* ── RESPONSIVE ── */
@media(max-width: 700px) {
    .summary-grid { grid-template-columns: repeat(2, 1fr); }
    .chart-row    { grid-template-columns: 1fr; }
    .da-title     { font-size: 18px; }
}

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>

<script>

let allData = [];
let chartBar   = null;
let chartDonut = null;

const IMG_EXT = ['jpg','jpeg','png','gif','webp'];

document.addEventListener('DOMContentLoaded', init);

/* ── INIT ── */
async function init() {
    await loadTahun();
    await loadData();

    document.getElementById('filter_tahun').addEventListener('change', filterData);
    document.getElementById('filter_bulan').addEventListener('change', filterData);
    document.getElementById('filter_minggu').addEventListener('change', filterData);
    document.getElementById('searchInput').addEventListener('input', filterData);
}

/* ── LOAD TAHUN ── */
async function loadTahun() {
    try {
        const res  = await fetch('/api/tahun-ajaran');
        const json = await res.json();
        let html   = '<option value="">Semua Tahun</option>';
        json.data.forEach(t => {
            html += `<option value="${t.id_tahun_ajaran}">${t.periode} – ${t.semester}</option>`;
        });
        document.getElementById('filter_tahun').innerHTML = html;
    } catch(e) { console.error(e); }
}

/* ── LOAD DATA ── */
async function loadData() {
    document.getElementById('data').innerHTML = `
        <tr><td colspan="6">
            <div class="da-loading"><div class="da-spinner"></div> Memuat data...</div>
        </td></tr>`;
    try {
        const res  = await fetch('/api/dokumen');
        const json = await res.json();
        allData = json.data || [];
        render(allData);
    } catch(e) {
        document.getElementById('data').innerHTML = `
            <tr><td colspan="6">
                <div class="da-empty"><div class="da-empty-icon">⚠️</div>Gagal memuat data.</div>
            </td></tr>`;
    }
}

/* ── FILTER ── */
function filterData() {
    const tahun   = document.getElementById('filter_tahun').value;
    const bulan   = document.getElementById('filter_bulan').value;
    const minggu  = document.getElementById('filter_minggu').value;
    const keyword = document.getElementById('searchInput').value.toLowerCase().trim();

    const data = allData.filter(d => {
        const tgl = new Date(d.tanggal_upload);
        if (tahun  && d.id_tahun_ajaran != tahun) return false;
        if (bulan  && (tgl.getMonth() + 1) != bulan) return false;
        if (minggu && Math.ceil(tgl.getDate() / 7) != minggu) return false;
        if (keyword && !(d.judul_dokumen || '').toLowerCase().includes(keyword)) return false;
        return true;
    });

    render(data);
}

/* ── RENDER ── */
function render(data) {
    const badge = document.getElementById('totalBadge');
    badge.textContent = `${data.length} dokumen`;

    /* summary */
    let totGambar = 0, totFile = 0;
    const tahunSet = new Set();
    data.forEach(d => {
        if (d.gambar) {
            const ext = d.gambar.split('.').pop().toLowerCase();
            IMG_EXT.includes(ext) ? totGambar++ : totFile++;
        }
        if (d.tahun_ajaran?.periode) tahunSet.add(d.tahun_ajaran.periode);
    });

    document.getElementById('numTotal').textContent  = data.length;
    document.getElementById('numGambar').textContent = totGambar;
    document.getElementById('numFile').textContent   = totFile;
    document.getElementById('numTahun').textContent  = tahunSet.size;

    updateCharts(data, tahunSet);

    if (!data.length) {
        document.getElementById('data').innerHTML = `
            <tr><td colspan="6">
                <div class="da-empty"><div class="da-empty-icon"></div>Tidak ada dokumen yang cocok.</div>
            </td></tr>`;
        return;
    }

    let html = '';
    data.forEach((d, i) => {
        const ext  = (d.gambar || '').split('.').pop().toLowerCase();
        const isImg = d.gambar && IMG_EXT.includes(ext);

        let fileHtml = `<span class="no-file">—</span>`;
        if (d.gambar) {
            fileHtml = isImg
                ? `<img src="/uploads/${d.gambar}" class="doc-img" onclick="lihatGambar('/uploads/${d.gambar}')" alt="${d.judul_dokumen}">`
                : `<a href="/uploads/${d.gambar}" target="_blank" class="file-link">
                       <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                       Download
                   </a>`;
        }

        const periode   = d.tahun_ajaran?.periode   ?? '—';
        const semester  = d.tahun_ajaran?.semester  ?? '—';
        const tglFmt    = d.tanggal_upload
            ? new Date(d.tanggal_upload).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })
            : '—';

        html += `
        <tr>
            <td class="td-no">${i + 1}</td>
            <td><div class="doc-judul">${d.judul_dokumen || '—'}</div></td>
            <td>${tglFmt}</td>
            <td><span class="tahun-badge">${periode} <span style="opacity:.5">·</span> ${semester}</span></td>
            <td class="td-file">${fileHtml}</td>
            <td><div class="ket-text">${d.keterangan || '—'}</div></td>
        </tr>`;
    });

    document.getElementById('data').innerHTML = html;
}

/* ── CHARTS ── */
function updateCharts(data, tahunSet) {
    updateBarChart(data);
    updateDonutChart(data);
}

function updateBarChart(data) {
    /* hitung dokumen per tahun ajaran */
    const map = {};
    data.forEach(d => {
        const key = d.tahun_ajaran?.periode
            ? `${d.tahun_ajaran.periode} (${d.tahun_ajaran.semester})`
            : 'Tanpa Tahun';
        map[key] = (map[key] || 0) + 1;
    });

    const labels = Object.keys(map);
    const values = Object.values(map);
    const colors = ['#2563eb','#059669','#d97706','#7c3aed','#dc2626','#0891b2'];

    if (chartBar) {
        chartBar.data.labels = labels;
        chartBar.data.datasets[0].data = values;
        chartBar.data.datasets[0].backgroundColor = labels.map((_, i) => colors[i % colors.length] + '33');
        chartBar.data.datasets[0].borderColor      = labels.map((_, i) => colors[i % colors.length]);
        chartBar.update();
        return;
    }

    chartBar = new Chart(document.getElementById('chartBar'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Dokumen',
                data: values,
                backgroundColor: labels.map((_, i) => colors[i % colors.length] + '33'),
                borderColor:     labels.map((_, i) => colors[i % colors.length]),
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
                tooltip: { callbacks: { label: ctx => ` ${ctx.raw} dokumen` } }
            },
            scales: {
                x: {
                    ticks: { font: { size: 11 }, color: '#64748b', maxRotation: 30 },
                    grid: { display: false }, border: { display: false }
                },
                y: {
                    beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 }, color: '#64748b' },
                    grid: { color: '#f0f4f9' }, border: { display: false }
                }
            }
        }
    });
}

function updateDonutChart(data) {
    let gambar = 0, lainnya = 0;
    data.forEach(d => {
        if (!d.gambar) return;
        const ext = d.gambar.split('.').pop().toLowerCase();
        IMG_EXT.includes(ext) ? gambar++ : lainnya++;
    });

    const total  = gambar + lainnya || 1;
    const colors = ['#059669', '#d97706'];
    const labels = ['Gambar', 'File Lainnya'];
    const values = [gambar, lainnya];

    document.getElementById('legendDonut').innerHTML = labels.map((l, i) =>
        `<span class="cl-item">
            <span class="cl-box" style="background:${colors[i]}"></span>
            ${l} ${((values[i]/total)*100).toFixed(1)}%
        </span>`
    ).join('');

    if (chartDonut) {
        chartDonut.data.datasets[0].data = values;
        chartDonut.update();
        return;
    }

    chartDonut = new Chart(document.getElementById('chartDonut'), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff',
                hoverBorderWidth: 0,
            }]
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

/* ── MODAL ── */
function lihatGambar(src) {
    document.getElementById('imgPreview').src = src;
    document.getElementById('modalImg').classList.add('open');
}

function tutupGambar() {
    document.getElementById('modalImg').classList.remove('open');
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') tutupGambar();
});

</script>

@endsection