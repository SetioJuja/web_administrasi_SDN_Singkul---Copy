@extends('layouts.app')

@section('title', 'Dokumen Administrasi')

@section('content')

<div class="da-wrapper">

    {{-- HEADER --}}
    <div class="da-header">
        <div class="da-header-left">
            <div class="da-icon-wrap">
                <i class="bi bi-file-earmark-text text-white" style="font-size: 22px;"></i>
            </div>
            <div>
                <h2 class="da-title">Dokumen Administrasi</h2>
                <p class="da-subtitle">Kelola dan lihat dokumen administrasi</p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <button class="btn-laporan" onclick="cetakLaporan()">
                <i class="bi bi-printer" style="margin-right: 6px;"></i>
                Cetak Laporan
            </button>
            <div class="da-badge" id="totalBadge">— dokumen</div>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="summary-grid">
        <div class="sum-card" id="sumTotal">
            <div class="sum-icon total">
                <i class="bi bi-file-earmark" style="font-size: 20px;"></i>
            </div>
            <div><div class="sum-num" id="numTotal">0</div><div class="sum-lbl">Total Dokumen</div></div>
        </div>
        <div class="sum-card" id="sumGambar">
            <div class="sum-icon gambar">
                <i class="bi bi-image" style="font-size: 20px;"></i>
            </div>
            <div><div class="sum-num" id="numGambar">0</div><div class="sum-lbl">File Gambar</div></div>
        </div>
        <div class="sum-card" id="sumFile">
            <div class="sum-icon file">
                <i class="bi bi-file-earmark-text" style="font-size: 20px;"></i>
            </div>
            <div><div class="sum-num" id="numFile">0</div><div class="sum-lbl">File Lainnya</div></div>
        </div>
        <div class="sum-card" id="sumTahun">
            <div class="sum-icon tahun">
                <i class="bi bi-calendar3" style="font-size: 20px;"></i>
            </div>
            <div><div class="sum-num" id="numTahun">0</div><div class="sum-lbl">Tahun Ajaran</div></div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-card">
        <div class="filter-inner">
            <div class="search-wrap">
                <i class="bi bi-search search-icon" style="font-size: 14px;"></i>
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
                        <th class="th-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody id="data"></tbody>
            </table>
        </div>
    </div>

</div>

{{-- MODAL PREVIEW GAMBAR --}}
<div id="modalImg" class="modal-overlay" onclick="tutupGambar()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="tutupGambar()">
            <i class="bi bi-x-lg" style="font-size: 16px;"></i>
        </button>
        <div class="modal-img-title" id="modalImgTitle"></div>
        <img id="imgPreview" src="" alt="Preview Dokumen">
        <div class="modal-img-footer">
            <a id="btnDlImg" href="#" download class="btn-modal-dl">
                <i class="bi bi-download" style="margin-right: 6px;"></i> Unduh Gambar
            </a>
        </div>
    </div>
</div>

{{-- MODAL VIEW FILE (PDF/LAINNYA) --}}
<div id="modalFile" class="modal-overlay" onclick="tutupFile()">
    <div class="modal-file-box" onclick="event.stopPropagation()">
        <div class="modal-file-header">
            <div class="modal-file-title" id="modalFileTitle">Preview Dokumen</div>
            <div style="display:flex;gap:8px;align-items:center;">
                <a id="btnDlFile" href="#" download class="btn-modal-dl">
                    <i class="bi bi-download" style="margin-right: 6px;"></i> Download
                </a>
                <button class="modal-close modal-close-file" onclick="tutupFile()">
                    <i class="bi bi-x-lg" style="font-size: 16px;"></i>
                </button>
            </div>
        </div>
        <iframe id="fileFrame" src="" class="modal-iframe"></iframe>
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

/* ── BTN LAPORAN ── */
.btn-laporan {
    display: inline-flex; align-items: center; gap: 7px;
    background: #fff; color: var(--navy);
    border: 1.5px solid var(--border);
    padding: 8px 16px; border-radius: 9px;
    font-size: 13px; font-weight: 600; cursor: pointer;
    font-family: inherit;
    box-shadow: 0 1px 4px rgba(15,45,82,.07);
    transition: background .15s, border-color .15s, box-shadow .15s;
}
.btn-laporan:hover {
    background: #f0f4f9; border-color: var(--navy-3);
    box-shadow: 0 2px 8px rgba(15,45,82,.12);
}

/* ── SUMMARY ── */
.summary-grid {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 12px; margin-bottom: 22px;
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

/* ── FILTER ── */
.filter-card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 13px 16px;
    margin-bottom: 16px; box-shadow: var(--shadow);
}
.filter-inner { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.search-wrap  { position: relative; flex: 1; min-width: 180px; }
.search-icon  { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }
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
.da-table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 860px; }
.da-table thead tr { background: var(--navy); }
.da-table th {
    color: #fff; padding: 12px 14px; text-align: left;
    font-size: 12px; font-weight: 600; letter-spacing: .3px; white-space: nowrap;
}
.da-table th.th-no     { width: 48px; text-align: center; }
.da-table th.th-file   { width: 110px; text-align: center; }
.da-table th.th-aksi   { width: 120px; text-align: center; }
.da-table th.th-tgl,
.da-table th.th-tahun  { width: 140px; }
.da-table td {
    padding: 12px 14px; border-bottom: 1px solid var(--border);
    vertical-align: middle; color: var(--text);
}
.da-table td.td-no   { text-align: center; color: var(--muted); font-size: 12px; }
.da-table td.td-file { text-align: center; }
.da-table td.td-aksi { text-align: center; }
.da-table tbody tr:last-child td { border-bottom: none; }
.da-table tbody tr:hover td { background: #f7fafd; }

.doc-judul {
    font-weight: 600; font-size: 13px; color: var(--navy); margin-bottom: 2px;
}
.tahun-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: #eff6ff; color: var(--navy-3);
    padding: 3px 10px; border-radius: 999px;
    font-size: 11.5px; font-weight: 600;
}
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
.no-file { color: var(--muted); font-size: 12px; }

/* ── AKSI BUTTONS ── */
.aksi-wrap {
    display: flex; align-items: center; justify-content: center; gap: 5px;
}
.btn-aksi {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 5px 9px; border-radius: 6px;
    font-size: 11.5px; font-weight: 600; cursor: pointer;
    border: 1px solid transparent; font-family: inherit;
    transition: background .15s;
    text-decoration: none; white-space: nowrap;
}
.btn-aksi-view {
    background: #ede9fe; color: #7c3aed; border-color: #ddd6fe;
}
.btn-aksi-view:hover { background: #ddd6fe; }
.btn-aksi-dl {
    background: #d1fae5; color: #059669; border-color: #a7f3d0;
}
.btn-aksi-dl:hover { background: #a7f3d0; }
.btn-aksi-nofile {
    background: #f1f5f9; color: var(--muted); border-color: #e2e8f0; cursor: default;
}
.ket-text {
    color: var(--muted); font-size: 12px;
    max-width: 200px; line-height: 1.5;
}

/* ── STATES ── */
.da-empty { text-align: center; padding: 50px 20px; color: var(--muted); font-size: 14px; }
.da-empty-icon { font-size: 36px; margin-bottom: 10px; }
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

/* ── MODAL OVERRIDES (Integrasi dengan layout.app) ── */
#modalImg, #modalFile {
    background: rgba(15, 23, 42, 0.85); /* slate overlay */
    padding: 20px;
}

#modalImg .modal-box {
    background: #1e293b;
    max-width: 85vw;
    width: auto;
    padding: 0;
    text-align: left;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    position: relative;
    border: 1px solid rgba(255,255,255,0.05);
}

.modal-img-title {
    padding: 14px 48px 12px 18px;
    font-size: 13px; font-weight: 600; color: #f1f5f9;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

#modalImg img {
    display: block;
    max-width: 85vw;
    max-height: 70vh;
    object-fit: contain;
    margin: 0 auto;
}

.modal-img-footer {
    padding: 12px 18px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    display: flex; justify-content: flex-end;
    background: #0f172a;
}

.modal-close {
    position: absolute; top: 10px; right: 10px;
    width: 30px; height: 30px; border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.15s; z-index: 5;
}
.modal-close:hover { background: rgba(255, 255, 255, 0.28); }

/* Modal File Box (PDF) */
.modal-file-box {
    background: #fff; border-radius: 12px;
    width: 90%; max-width: 950px; height: 85vh;
    display: flex; flex-direction: column;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    margin: auto;
    position: relative;
    transform: scale(.95);
    transition: transform .22s ease;
}
.modal-overlay.show .modal-file-box {
    transform: scale(1);
}
.modal-file-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 18px; background: var(--navy); color: #fff;
    flex-shrink: 0;
}
.modal-file-title { font-size: 14px; font-weight: 600; }
.modal-close-file {
    position: static;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.modal-iframe {
    flex: 1; border: none; width: 100%;
}

/* ── RESPONSIVE ── */
@media(max-width: 700px) {
    .summary-grid { grid-template-columns: repeat(2, 1fr); }
    .da-title     { font-size: 18px; }
}

/* ── PRINT AREA STYLES ── */
@media print {
    @page {
        size: A4 portrait;
        margin: 20mm 18mm 20mm 25mm;
    }
    body * { visibility: hidden; }
    #printArea, #printArea * { visibility: visible; }
    #printArea {
        position: fixed; inset: 0;
        font-family: 'Times New Roman', serif;
        color: #000; background: #fff;
    }
    .print-kop {
        display: flex !important; align-items: center; gap: 14px;
        border-bottom: 4px double #000; padding-bottom: 10px; margin-bottom: 12px;
    }
    .print-kop-logo { width: 64px; height: 64px; object-fit: contain; }
    .print-kop-text h1 { font-size: 14pt; font-weight: 700; margin: 0 0 2px; text-transform: uppercase; letter-spacing: .5px; }
    .print-kop-text p  { font-size: 9pt; margin: 0; }
    .print-judul { text-align: center; margin: 14px 0 4px; font-size: 13pt; font-weight: 700; text-transform: uppercase; text-decoration: underline; }
    .print-subjudul { text-align: center; font-size: 10pt; margin-bottom: 12px; }
    .print-info { font-size: 9.5pt; margin-bottom: 10px; border: 1px solid #000; padding: 6px 10px; }
    .print-info table { border-collapse: collapse; width: 100%; }
    .print-info td { padding: 2px 8px 2px 0; vertical-align: top; }
    .print-summary { display: grid !important; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-bottom: 14px; }
    .print-sum-item { border: 1px solid #000; padding: 7px 10px; text-align: center; }
    .print-sum-num { font-size: 18pt; font-weight: 700; }
    .print-sum-lbl { font-size: 8.5pt; }
    .print-table { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
    .print-table th {
        background: #000 !important; color: #fff !important; padding: 6px 8px; text-align: left; font-size: 9pt; font-weight: 700; border: 1px solid #000;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .print-table th.c { text-align: center; }
    .print-table td { padding: 5px 8px; border: 1px solid #888; vertical-align: top; }
    .print-table tr:nth-child(even) td { background: #f2f2f2; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .print-ttd { margin-top: 32px; display: flex !important; justify-content: flex-end; }
    .print-ttd-box { text-align: center; font-size: 10pt; }
    .print-ttd-box .ttd-nama { font-weight: 700; text-decoration: underline; margin-top: 56px; }
    .print-ttd-box .ttd-nip  { font-size: 9pt; margin-top: 2px; }
    .print-footer {
        position: fixed; bottom: 12mm; left: 0; right: 0; text-align: center; font-size: 8pt; color: #555;
        border-top: 1px solid #ccc; padding-top: 5px;
    }
}
</style>
<div id="printArea" style="display:none">
    <div class="print-kop" style="display:none">
        <img class="print-kop-logo" src="/images/logo-sekolah.png" onerror="this.style.display='none'" alt="Logo">
        <div class="print-kop-text">
            <h1>SDN SINGKUL</h1>
    </div>

    <div class="print-judul" id="printJudul">LAPORAN DOKUMEN ADMINISTRASI</div>
    <div class="print-subjudul" id="printSubjudul"></div>
    <div class="print-info" id="printInfo"></div>
    <div class="print-summary" style="display:none" id="printSummary"></div>
    <table class="print-table" id="printTable">
        <thead id="printThead"></thead>
        <tbody id="printTbody"></tbody>
    </table>

    <div class="print-ttd" style="display:none" id="printTtd">
        <div class="print-ttd-box">
            <div id="printTtdLokasi"></div>
        </div>
    </div>
    <div class="print-footer">SIMAG-S – SDN Singkul &nbsp;|&nbsp; Dokumen Administrasi &nbsp;|&nbsp; Dicetak: <span id="printTgl"></span></div>
</div>

<script>

let allData = [];
let filteredData = [];

const IMG_EXT = ['jpg','jpeg','png','gif','webp'];

document.addEventListener('DOMContentLoaded', init);

/* ── INIT ── */
async function init() {
    await loadTahun();
    await loadData();

    document.getElementById('filter_tahun').addEventListener('change', filterData);
    document.getElementById('filter_bulan').addEventListener('change', filterData);
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
        <tr><td colspan="7">
            <div class="da-loading"><div class="da-spinner"></div> Memuat data...</div>
        </td></tr>`;
    try {
        const res  = await fetch('/api/dokumen');
        const json = await res.json();
        allData = json.data || [];
        render(allData);
    } catch(e) {
        document.getElementById('data').innerHTML = `
            <tr><td colspan="7">
                <div class="da-empty"><div class="da-empty-icon">⚠️</div>Gagal memuat data.</div>
            </td></tr>`;
    }
}

/* ── FILTER ── */
function filterData() {
    const tahun   = document.getElementById('filter_tahun').value;
    const bulan   = document.getElementById('filter_bulan').value;
    const keyword = document.getElementById('searchInput').value.toLowerCase().trim();

    const data = allData.filter(d => {
        const tgl = new Date(d.tanggal_upload);
        if (tahun  && d.id_tahun_ajaran != tahun) return false;
        if (bulan  && (tgl.getMonth() + 1) != bulan) return false;
        if (keyword && !(d.judul_dokumen || '').toLowerCase().includes(keyword)) return false;
        return true;
    });

    render(data);
}

/* ── RENDER ── */
function render(data) {
    filteredData = data;
    document.getElementById('totalBadge').textContent = `${data.length} dokumen`;

    /* summary totals */
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

    if (!data.length) {
        document.getElementById('data').innerHTML = `
            <tr><td colspan="7">
                <div class="da-empty"><div class="da-empty-icon">📭</div>Tidak ada dokumen yang cocok.</div>
            </td></tr>`;
        return;
    }

    let html = '';
    data.forEach((d, i) => {
        const ext   = (d.gambar || '').split('.').pop().toLowerCase();
        const isImg = d.gambar && IMG_EXT.includes(ext);

        let fileHtml = `<span class="no-file">—</span>`;
        if (d.gambar) {
            fileHtml = isImg
                ? `<img src="/uploads/${d.gambar}" class="doc-img" onclick="lihatGambarByIndex(${i})" alt="${escHtml(d.judul_dokumen)}">`
                : `<a href="/uploads/${d.gambar}" target="_blank" class="file-link">
                       <i class="bi bi-file-earmark-arrow-up" style="font-size: 13px; margin-right: 4px;"></i>
                       ${ext.toUpperCase()}
                   </a>`;
        }

        let aksiHtml = '';
        if (!d.gambar) {
            aksiHtml = `<span class="btn-aksi btn-aksi-nofile">Tidak ada file</span>`;
        } else {
            aksiHtml = `
                <div class="aksi-wrap">
                    <button class="btn-aksi btn-aksi-view" onclick="${isImg ? 'lihatGambarByIndex' : 'lihatFileByIndex'}(${i})">
                        <i class="bi bi-eye" style="margin-right: 4px;"></i> Lihat
                    </button>
                    <a href="/uploads/${d.gambar}" download class="btn-aksi btn-aksi-dl">
                        <i class="bi bi-download" style="margin-right: 4px;"></i> Unduh
                    </a>
                </div>`;
        }

        const periode  = d.tahun_ajaran?.periode  ?? '—';
        const semester = d.tahun_ajaran?.semester ?? '—';
        const tglFmt   = d.tanggal_upload
            ? new Date(d.tanggal_upload).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })
            : '—';

        html += `
        <tr>
            <td class="td-no">${i + 1}</td>
            <td><div class="doc-judul">${escHtml(d.judul_dokumen)}</div></td>
            <td>${tglFmt}</td>
            <td><span class="tahun-badge">${periode} <span style="opacity:.5">·</span> ${semester}</span></td>
            <td class="td-file">${fileHtml}</td>
            <td><div class="ket-text">${escHtml(d.keterangan)}</div></td>
            <td class="td-aksi">${aksiHtml}</td>
        </tr>`;
    });

    document.getElementById('data').innerHTML = html;
}

/* ── INDEX-BASED HANDLERS ── */
function lihatGambarByIndex(i) {
    const d = filteredData[i];
    if (d) lihatGambar(`/uploads/${d.gambar}`, d.judul_dokumen);
}

function lihatFileByIndex(i) {
    const d = filteredData[i];
    if (d) lihatFile(`/uploads/${d.gambar}`, d.judul_dokumen);
}

/* ── MODALS SHOW/HIDE ── */
function lihatGambar(src, judul) {
    document.getElementById('imgPreview').src = src;
    document.getElementById('modalImgTitle').textContent = judul || 'Preview Gambar';
    document.getElementById('btnDlImg').href = src;
    document.getElementById('btnDlImg').download = judul || 'dokumen';
    document.getElementById('modalImg').classList.add('show');
}
function tutupGambar() {
    document.getElementById('modalImg').classList.remove('show');
    document.getElementById('imgPreview').src = '';
}

function lihatFile(src, judul) {
    document.getElementById('modalFileTitle').textContent = judul || 'Preview Dokumen';
    document.getElementById('fileFrame').src  = src;
    document.getElementById('btnDlFile').href = src;
    document.getElementById('btnDlFile').download = judul || 'dokumen';
    document.getElementById('modalFile').classList.add('show');
}
function tutupFile() {
    document.getElementById('modalFile').classList.remove('show');
    document.getElementById('fileFrame').src = '';
}

/* ── CETAK LAPORAN ── */
function cetakLaporan() {
    const tahunEl  = document.getElementById('filter_tahun');
    const bulanEl  = document.getElementById('filter_bulan');
    const keyword  = document.getElementById('searchInput').value.trim();

    const tahunTxt  = tahunEl.options[tahunEl.selectedIndex]?.text  || 'Semua';
    const bulanTxt  = bulanEl.options[bulanEl.selectedIndex]?.text  || 'Semua';

    /* info filter */
    document.getElementById('printSubjudul').textContent =
        `Tahun Ajaran: ${tahunTxt}  |  Bulan: ${bulanTxt}`;

    const now = new Date();
    const tglStr = now.toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' });
    document.getElementById('printTgl').textContent = tglStr;

    document.getElementById('printInfo').innerHTML = `
        <table>
            <tr><td><b>Dicetak pada</b></td><td>: ${tglStr}</td>
                <td width="30"></td>
                <td><b>Total Dokumen</b></td><td>: ${filteredData.length} dokumen</td>
            </tr>
            <tr><td><b>Tahun Ajaran</b></td><td>: ${tahunTxt}</td>
                <td></td>
                <td><b>Filter Bulan</b></td><td>: ${bulanTxt}</td>
            </tr>
        </table>`;

    /* Summary */
    let totGambar = 0, totFile = 0;
    const tahunSet = new Set();
    filteredData.forEach(d => {
        if (d.gambar) {
            const ext = d.gambar.split('.').pop().toLowerCase();
            IMG_EXT.includes(ext) ? totGambar++ : totFile++;
        }
        if (d.tahun_ajaran?.periode) tahunSet.add(d.tahun_ajaran.periode);
    });
    document.getElementById('printSummary').style.display = 'grid';
    document.getElementById('printSummary').innerHTML = `
        <div class="print-sum-item"><div class="print-sum-num">${filteredData.length}</div><div class="print-sum-lbl">Total Dokumen</div></div>
        <div class="print-sum-item"><div class="print-sum-num">${totGambar}</div><div class="print-sum-lbl">File Gambar</div></div>
        <div class="print-sum-item"><div class="print-sum-num">${totFile}</div><div class="print-sum-lbl">File Lainnya</div></div>
        <div class="print-sum-item"><div class="print-sum-num">${tahunSet.size}</div><div class="print-sum-lbl">Tahun Ajaran</div></div>`;

    /* Table */
    document.getElementById('printThead').innerHTML = `
        <tr>
            <th class="c" style="width:34px">No</th>
            <th>Judul Dokumen</th>
            <th style="width:105px">Tanggal Upload</th>
            <th style="width:140px">Tahun Ajaran</th>
            <th style="width:80px">Tipe File</th>
            <th>Keterangan</th>
        </tr>`;

    let tbody = '';
    filteredData.forEach((d, i) => {
        const ext   = (d.gambar || '').split('.').pop().toLowerCase();
        const tipe  = d.gambar ? ext.toUpperCase() : '—';
        const tglFmt = d.tanggal_upload
            ? new Date(d.tanggal_upload).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })
            : '—';
        const periode  = d.tahun_ajaran?.periode  ?? '—';
        const semester = d.tahun_ajaran?.semester ?? '—';

        tbody += `
            <tr>
                <td style="text-align:center">${i + 1}</td>
                <td>${escHtml(d.judul_dokumen)}</td>
                <td>${tglFmt}</td>
                <td>${periode} – ${semester}</td>
                <td style="text-align:center">${tipe}</td>
                <td>${escHtml(d.keterangan)}</td>
            </tr>`;
    });
    document.getElementById('printTbody').innerHTML = tbody;

    /* TTD */
    document.getElementById('printTtd').style.display = 'flex';
    document.getElementById('printTtdLokasi').textContent =
        `Depok, ${now.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' })}`;

    /* print dialog */
    const printArea = document.getElementById('printArea');
    printArea.style.display = 'block';
    setTimeout(() => {
        window.print();
        printArea.style.display = 'none';
    }, 120);
}

/* ESC close modals */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        tutupGambar();
        tutupFile();
    }
});

/* Safe HTML escaping */
function escHtml(str) {
    if (!str) return '';
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

</script>

@endsection