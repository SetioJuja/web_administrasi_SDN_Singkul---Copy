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
                <p class="pg-subtitle">Monitoring & Laporan Kehadiran Tenaga Pengajar</p>
            </div>
        </div>
        <div class="pg-header-actions">
            <button class="btn-export pdf" id="btnExportPDF" onclick="exportPDF()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                PDF
            </button>
        </div>
    </div>

    {{-- ALERT ALPA --}}
    <div id="alertAlpa" class="alert-alpa" style="display:none">
        <div class="alert-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="alert-body">
            <strong>Perhatian Kehadiran!</strong>
            <span id="alertAlpaText"></span>
        </div>
        <button class="alert-close" onclick="document.getElementById('alertAlpa').style.display='none'">✕</button>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="summary-grid" id="summaryGrid">
        <div class="sum-card h">
            <div class="sum-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div>
                <div class="sum-num" id="sumHadir">0</div>
                <div class="sum-lbl">Total Hadir</div>
                <div class="sum-pct" id="pctHadir">0%</div>
            </div>
        </div>
        <div class="sum-card i">
            <div class="sum-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div>
                <div class="sum-num" id="sumIzin">0</div>
                <div class="sum-lbl">Total Izin</div>
                <div class="sum-pct" id="pctIzin">0%</div>
            </div>
        </div>
        <div class="sum-card s">
            <div class="sum-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div>
                <div class="sum-num" id="sumSakit">0</div>
                <div class="sum-lbl">Total Sakit</div>
                <div class="sum-pct" id="pctSakit">0%</div>
            </div>
        </div>
        <div class="sum-card a">
            <div class="sum-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div>
                <div class="sum-num" id="sumAlpa">0</div>
                <div class="sum-lbl">Total Alpa</div>
                <div class="sum-pct" id="pctAlpa">0%</div>
            </div>
        </div>
    </div>

    {{-- GRAFIK --}}
    <div class="chart-row">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Distribusi Kehadiran</div>
                    <div class="chart-card-sub">Persentase status presensi seluruh guru</div>
                </div>
                <button class="btn-dl-chart" title="Download grafik" onclick="downloadChart('chartDonut','Distribusi_Kehadiran')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </button>
            </div>
            <div class="chart-wrap" style="height:200px">
                <canvas id="chartDonut" role="img" aria-label="Donut chart distribusi kehadiran guru"></canvas>
            </div>
            <div class="chart-legend" id="legendDonut"></div>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Tren Kehadiran per Guru</div>
                    <div class="chart-card-sub">Perbandingan jumlah hadir tiap guru</div>
                </div>
                <button class="btn-dl-chart" title="Download grafik" onclick="downloadChart('chartBar','Kehadiran_per_Guru')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </button>
            </div>
            <div class="chart-wrap" style="height:200px">
                <canvas id="chartBar" role="img" aria-label="Bar chart kehadiran per guru"></canvas>
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
            <button class="btn-reset" onclick="resetFilter()">Reset</button>
        </div>
    </div>

    <div class="pg-legend">
        <span class="leg-item"><span class="leg-dot h"></span>Hadir</span>
        <span class="leg-item"><span class="leg-dot i"></span>Izin</span>
        <span class="leg-item"><span class="leg-dot s"></span>Sakit</span>
        <span class="leg-item"><span class="leg-dot a"></span>Alpa</span>
        <span class="leg-item alpa-warn"><span class="leg-dot alpa-w"></span>Alpa ≥ 3 hari (peringatan)</span>
    </div>

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
    --w-c: #7c3aed; --w-bg: #ede9fe; --w-txt: #4c1d95;

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
.pg-header-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.pg-icon-wrap {
    width: 46px; height: 46px;
    background: linear-gradient(135deg, var(--navy), var(--navy-3));
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(15,45,82,.25);
    flex-shrink: 0;
}
.pg-title    { font-size: 22px; font-weight: 700; color: var(--navy); margin: 0; line-height: 1.2; }
.pg-subtitle { font-size: 13px; color: var(--muted); margin: 3px 0 0; }

/* ── EXPORT BUTTONS ── */
.btn-export {
    display: flex; align-items: center; gap: 6px;
    padding: 7px 14px;
    border: 1.5px solid #22c55e;
    border-radius: 8px;
    background: #f0fdf4;
    color: #15803d;
    font-size: 12.5px; font-weight: 600;
    cursor: pointer;
    transition: background .15s, box-shadow .15s;
    font-family: inherit;
}
.btn-export:hover { background: #dcfce7; box-shadow: 0 2px 8px rgba(34,197,94,.15); }
.btn-export.pdf   { border-color: #ef4444; background: #fef2f2; color: #b91c1c; }
.btn-export.pdf:hover { background: #fee2e2; box-shadow: 0 2px 8px rgba(239,68,68,.15); }

/* ── DOWNLOAD CHART BUTTON ── */
.btn-dl-chart {
    display: flex; align-items: center; justify-content: center;
    width: 30px; height: 30px;
    border: 1.5px solid var(--border);
    border-radius: 7px;
    background: var(--bg);
    color: var(--muted);
    cursor: pointer;
    transition: border-color .15s, color .15s, background .15s;
    flex-shrink: 0;
}
.btn-dl-chart:hover {
    border-color: var(--navy-3);
    color: var(--navy);
    background: #e8f0fb;
}

/* ── ALERT ALPA ── */
.alert-alpa {
    background: var(--w-bg);
    border: 1.5px solid #c4b5fd;
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 18px;
    display: flex; align-items: flex-start; gap: 12px;
    animation: fadeUp .3s ease;
}
.alert-icon  { color: var(--w-c); flex-shrink: 0; margin-top: 1px; }
.alert-body  { flex: 1; font-size: 13px; color: var(--w-txt); line-height: 1.5; }
.alert-body strong { display: block; font-weight: 700; margin-bottom: 3px; }
.alert-close {
    background: none; border: none; cursor: pointer;
    color: var(--muted); font-size: 16px; line-height: 1;
    padding: 2px 4px; border-radius: 4px;
}
.alert-close:hover { background: rgba(0,0,0,.06); }

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
.sum-pct { font-size: 11px; font-weight: 700; margin-top: 2px; }
.sum-card.h .sum-pct { color: var(--h-c); }
.sum-card.i .sum-pct { color: var(--i-c); }
.sum-card.s .sum-pct { color: var(--s-c); }
.sum-card.a .sum-pct { color: var(--a-c); }

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
.chart-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 14px;
}
.chart-card-title { font-size: 13.5px; font-weight: 700; color: var(--navy); margin-bottom: 2px; }
.chart-card-sub   { font-size: 11.5px; color: var(--muted); }
.chart-wrap       { position: relative; }
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
.search-wrap  { position: relative; flex: 1; min-width: 180px; }
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
.btn-reset {
    padding: 9px 14px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    background: #f7fafd;
    color: var(--muted);
    font-size: 13px; font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: border-color .15s, color .15s;
}
.btn-reset:hover { border-color: var(--navy-3); color: var(--navy); }

/* ── LEGEND ── */
.pg-legend {
    display: flex; gap: 16px; margin-bottom: 14px; flex-wrap: wrap;
}
.leg-item {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: var(--muted); font-weight: 500;
}
.leg-dot         { width: 11px; height: 11px; border-radius: 3px; }
.leg-dot.h       { background: var(--h-c); }
.leg-dot.i       { background: var(--i-c); }
.leg-dot.s       { background: var(--s-c); }
.leg-dot.a       { background: var(--a-c); }
.leg-dot.alpa-w  { background: var(--w-c); }
.leg-item.alpa-warn { color: var(--w-txt); font-weight: 600; }

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
.guru-card.alpa-warning {
    border-color: #c4b5fd;
    box-shadow: var(--shadow), 0 0 0 3px rgba(196,181,253,.2);
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
.guru-name   { display: flex; align-items: center; gap: 10px; }
.guru-avatar {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, var(--navy), var(--navy-3));
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 700; font-size: 14px; flex-shrink: 0;
}
.guru-avatar.warn { background: linear-gradient(135deg, var(--w-c), #a855f7); }
.guru-nama-text { font-weight: 700; font-size: 14.5px; color: var(--navy); }
.guru-rekap  { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
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
.mini-seg { height: 6px; border-radius: 3px; transition: width .4s ease; }
.mini-seg.h { background: var(--h-c); }
.mini-seg.i { background: var(--i-c); }
.mini-seg.s { background: var(--s-c); }
.mini-seg.a { background: var(--a-c); }

/* ── COLLAPSIBLE BODY ── */
.guru-body { max-height: 0; overflow: hidden; transition: max-height .35s ease; }
.guru-card.open .guru-body { max-height: 900px; }

/* ── GURU DETAIL ── */
.guru-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    padding: 14px 18px 0;
}
.gstat { background: var(--bg); border-radius: 10px; padding: 10px 12px; text-align: center; }
.gstat-num { font-size: 20px; font-weight: 700; }
.gstat-lbl { font-size: 10.5px; color: var(--muted); font-weight: 500; margin-top: 2px; }
.gstat.h .gstat-num { color: var(--h-c); }
.gstat.i .gstat-num { color: var(--i-c); }
.gstat.s .gstat-num { color: var(--s-c); }
.gstat.a .gstat-num { color: var(--a-c); }

/* ── TABLE ── */
.guru-table-wrap { overflow-x: auto; padding: 14px 18px 16px; }
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

/* ── PRINT ── */
@media print {
    .pg-header-actions, .filter-card,
    .btn-export, .btn-reset,
    .alert-close, .btn-dl-chart { display: none !important; }
    .guru-body   { max-height: none !important; }
    .guru-card   { break-inside: avoid; }
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
    .guru-stats-row { grid-template-columns: repeat(2,1fr); }
}
@media(max-width: 420px) {
    .summary-grid { grid-template-columns: 1fr 1fr; }
}

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>

/* ════════════════════════════════════
   STATE
════════════════════════════════════ */
let allData = [];
let chartDonut = null, chartBar = null;
const ALPA_THRESHOLD = 3;

const COLORS = {
    h: { fill: '#059669', bg: '#d1fae5', txt: '#065f46' },
    i: { fill: '#d97706', bg: '#fef3c7', txt: '#92400e' },
    s: { fill: '#2563eb', bg: '#dbeafe', txt: '#1e3a8a' },
    a: { fill: '#dc2626', bg: '#fee2e2', txt: '#7f1d1d' },
};

/* ════════════════════════════════════
   INIT
════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', async () => {
    await loadTahun();
    await loadData();

    document.getElementById('filter_tahun').addEventListener('change', render);
    document.getElementById('filterBulan').addEventListener('change', render);
    document.getElementById('searchGuru').addEventListener('input', render);
});

/* ════════════════════════════════════
   LOAD
════════════════════════════════════ */
async function loadTahun() {
    try {
        const res  = await fetch('/api/tahun-ajaran');
        const json = await res.json();
        const sel  = document.getElementById('filter_tahun');
        if (!sel) return;
        let html = '<option value="">Semua Tahun</option>';
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
                <div class="pg-empty-icon">⚠️</div>
                Gagal memuat data. Silakan coba lagi.
            </div>`;
    }
}

/* ════════════════════════════════════
   FILTER DATA
════════════════════════════════════ */
function getFilteredData() {
    let data    = [...allData];
    const tahun   = document.getElementById('filter_tahun').value;
    const bulan   = document.getElementById('filterBulan').value;
    const keyword = document.getElementById('searchGuru').value.toLowerCase().trim();

    if (tahun)   data = data.filter(d => d.id_tahun_ajaran == tahun);
    if (bulan)   data = data.filter(d => d.tanggal.split('-')[1] === bulan);
    if (keyword) data = data.filter(d => (d.guru?.nama_guru || '').toLowerCase().includes(keyword));

    return data;
}

function resetFilter() {
    document.getElementById('filter_tahun').value = '';
    document.getElementById('filterBulan').value  = '';
    document.getElementById('searchGuru').value   = '';
    render();
}

/* ════════════════════════════════════
   RENDER REKAP
════════════════════════════════════ */
function render() {
    const data      = getFilteredData();
    const container = document.getElementById('presensiContainer');

    if (!data.length) {
        container.innerHTML = `<div class="pg-empty"><div class="pg-empty-icon">🔍</div>Tidak ada data presensi yang cocok.</div>`;
        updateSummary(0, 0, 0, 0);
        updateCharts({}, []);
        hideAlert();
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

    let totH = 0, totI = 0, totS = 0, totA = 0;
    const barLabels = [], barHadir = [];
    const alpaGuru  = [];
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

        if (alpa >= ALPA_THRESHOLD) alpaGuru.push({ nama: g.nama, alpa });

        const total = hadir + izin + sakit + alpa || 1;
        const pH    = (hadir / total * 100).toFixed(1);
        const pI    = (izin  / total * 100).toFixed(1);
        const pS    = (sakit / total * 100).toFixed(1);
        const pA    = (alpa  / total * 100).toFixed(1);
        const initial = g.nama.charAt(0).toUpperCase();
        const isWarn  = alpa >= ALPA_THRESHOLD;

        let cells = '', headers = '';
        for (let i = 1; i <= 31; i++) {
            const st = map[i];
            headers += `<th>${i}</th>`;
            if (!st) cells += `<td class="kosong">·</td>`;
            else     cells += `<td class="${st}">${st.charAt(0).toUpperCase()}</td>`;
        }

        html += `
        <div class="guru-card${isWarn ? ' alpa-warning' : ''}" id="card-${idx}" style="animation-delay:${idx * 35}ms">
            <div class="guru-head" onclick="toggleCard(${idx})">
                <div class="guru-name">
                    <div class="guru-avatar${isWarn ? ' warn' : ''}">${initial}</div>
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
    showAlertAlpa(alpaGuru);
}

/* ════════════════════════════════════
   ALERT ALPA
════════════════════════════════════ */
function showAlertAlpa(list) {
    const el  = document.getElementById('alertAlpa');
    const txt = document.getElementById('alertAlpaText');
    if (!list.length) { el.style.display = 'none'; return; }
    const names = list.map(g => `<strong>${g.nama}</strong> (${g.alpa}× alpa)`).join(', ');
    txt.innerHTML = ` Guru berikut memiliki alpa ≥ ${ALPA_THRESHOLD} hari: ${names}. Perlu tindak lanjut.`;
    el.style.display = 'flex';
}
function hideAlert() { document.getElementById('alertAlpa').style.display = 'none'; }

/* ════════════════════════════════════
   TOGGLE CARD
════════════════════════════════════ */
function toggleCard(idx) {
    document.getElementById('card-' + idx).classList.toggle('open');
}

/* ════════════════════════════════════
   SUMMARY
════════════════════════════════════ */
function updateSummary(h, i, s, a) {
    const total = h + i + s + a || 1;
    document.getElementById('sumHadir').textContent = h;
    document.getElementById('sumIzin').textContent  = i;
    document.getElementById('sumSakit').textContent = s;
    document.getElementById('sumAlpa').textContent  = a;
    document.getElementById('pctHadir').textContent = ((h/total)*100).toFixed(1) + '%';
    document.getElementById('pctIzin').textContent  = ((i/total)*100).toFixed(1) + '%';
    document.getElementById('pctSakit').textContent = ((s/total)*100).toFixed(1) + '%';
    document.getElementById('pctAlpa').textContent  = ((a/total)*100).toFixed(1) + '%';
}

/* ════════════════════════════════════
   CHARTS — DONUT & BAR
════════════════════════════════════ */
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
    const total  = values.reduce((a, b) => a + b, 0) || 1;

    legend.innerHTML = labels.map((l, j) =>
        `<span class="cl-item">
            <span class="cl-box" style="background:${colors[j]}"></span>
            ${l} ${((values[j]/total)*100).toFixed(1)}%
        </span>`
    ).join('');

    if (chartDonut) {
        chartDonut.data.datasets[0].data = values;
        chartDonut.update();
        return;
    }

    chartDonut = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{ data: values, backgroundColor: colors, borderWidth: 2, borderColor: '#fff', hoverBorderWidth: 0 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw} (${((ctx.raw/total)*100).toFixed(1)}%)` }
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
            datasets: [{ label: 'Hadir', data: bar.data, backgroundColor: COLORS.h.bg, borderColor: COLORS.h.fill, borderWidth: 2, borderRadius: 6, borderSkipped: false }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` Hadir: ${ctx.raw} hari` } }
            },
            scales: {
                x: { ticks: { font: { size: 11 }, color: '#64748b', maxRotation: 35 }, grid: { display: false }, border: { display: false } },
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 }, color: '#64748b' }, grid: { color: '#f0f4f9' }, border: { display: false } }
            }
        }
    });
}

/* ════════════════════════════════════
   DOWNLOAD CHART (per grafik)
════════════════════════════════════ */
function downloadChart(canvasId, nama) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) { alert('Grafik belum tersedia.'); return; }

    const tmp  = document.createElement('canvas');
    tmp.width  = canvas.width;
    tmp.height = canvas.height;
    const ctx  = tmp.getContext('2d');
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, tmp.width, tmp.height);
    ctx.drawImage(canvas, 0, 0);

    const link    = document.createElement('a');
    link.download = `Grafik_${nama}_${new Date().toISOString().split('T')[0]}.png`;
    link.href     = tmp.toDataURL('image/png');
    link.click();
}

/* ════════════════════════════════════
   EXPORT PDF
════════════════════════════════════ */
async function exportPDF() {
    const data = getFilteredData();
    if (!data.length) { alert('Tidak ada data untuk diekspor.'); return; }

    const btn = document.getElementById('btnExportPDF');
    btn.textContent = 'Menyiapkan...';
    btn.disabled = true;

    try {
        const { jsPDF } = window.jspdf;
        const doc   = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
        const pageW = doc.internal.pageSize.getWidth();
        const pageH = doc.internal.pageSize.getHeight();
        const margin = 15;

        // ── Ambil nilai filter ─────────────────────────────────────────────
        const filterBulan  = document.getElementById('filterBulan')?.value  || '';
        const filterTahun  = document.getElementById('filter_tahun')?.value || '';

        // Label tahun ajaran dari teks option yang terpilih
        const selTahun   = document.getElementById('filter_tahun');
        const tahunLabel = selTahun?.options[selTahun.selectedIndex]?.text || '';

        const namaBulan    = filterBulan
            ? new Date(2000, parseInt(filterBulan) - 1).toLocaleString('id-ID', { month: 'long' })
            : '';
        const periodeLabel = [namaBulan, tahunLabel].filter(Boolean).join(' — ') || 'Semua Periode';

        // ── KOP LAPORAN FORMAL ─────────────────────────────────────────────
        const namaSekolah = (typeof NAMA_SEKOLAH !== 'undefined' ? NAMA_SEKOLAH : 'SD NEGERI SINGKUL');

        doc.setTextColor(0, 0, 0);
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(12);
        doc.text('LAPORAN PRESENSI GURU', margin, 14);

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(9);
        doc.text(namaSekolah.toUpperCase(), margin, 20);

        const tglCetak = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        doc.text('Dicetak : ' + tglCetak, pageW - margin, 14, { align: 'right' });
        doc.text('Periode  : ' + periodeLabel, pageW - margin, 20, { align: 'right' });

        // Garis ganda kop
        doc.setLineWidth(0.6); doc.setDrawColor(0, 0, 0);
        doc.line(margin, 24, pageW - margin, 24);
        doc.setLineWidth(0.2);
        doc.line(margin, 25.2, pageW - margin, 25.2);

        const grouped = {};
        data.forEach(p => {
            const nm = p.guru?.nama_guru || '-';
            if (!grouped[nm]) grouped[nm] = { h: 0, i: 0, s: 0, a: 0 };
            const st = (p.status?.nama_status || '').toLowerCase();
            if      (st === 'hadir') grouped[nm].h++;
            else if (st === 'izin')  grouped[nm].i++;
            else if (st === 'sakit') grouped[nm].s++;
            else if (st === 'alpa')  grouped[nm].a++;
        });
        
        const colW  = [(pageW - margin * 2) * 0.45, ...Array(5).fill((pageW - margin * 2) * 0.11)];
        const rowH  = 8;
        const heads = ['Nama Guru', 'Hadir', 'Izin', 'Sakit', 'Alpa', '% Hadir'];

        const drawTableHeader = (posY) => {
            doc.setLineWidth(0.3); doc.setDrawColor(0, 0, 0);
            doc.line(margin, posY, pageW - margin, posY);
            doc.line(margin, posY + rowH, pageW - margin, posY + rowH);
            doc.setFont('helvetica', 'bold'); doc.setFontSize(8.5);
            doc.setTextColor(0, 0, 0);
            let cx = margin;
            heads.forEach((h, i) => {
                doc.text(h, i === 0 ? cx + 3 : cx + colW[i] / 2, posY + 5.5, { align: i === 0 ? 'left' : 'center' });
                cx += colW[i];
            });
        };

        let y = 32;
        drawTableHeader(y);
        y += rowH;

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8.5);
        doc.setTextColor(0, 0, 0);
        let rowIdx = 0;

        Object.entries(grouped).forEach(([nm, r]) => {
            if (y + rowH > pageH - 22) {
                doc.addPage();
                y = 15;
                drawTableHeader(y);
                y += rowH;
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(8.5);
                doc.setTextColor(0, 0, 0);
            }

            const tot    = r.h + r.i + r.s + r.a || 1;
            const pct    = ((r.h / tot) * 100).toFixed(1) + '%';
            const isAlpa = r.a >= ALPA_THRESHOLD;

            if (isAlpa) {
                doc.setFillColor(235, 235, 235);
                doc.rect(margin, y, pageW - margin * 2, rowH, 'F');
            }

            let cx = margin;
            [nm, r.h, r.i, r.s, r.a, pct].forEach((v, i) => {
                doc.text(String(v), i === 0 ? cx + 3 : cx + colW[i] / 2, y + 5.5, { align: i === 0 ? 'left' : 'center' });
                cx += colW[i];
            });

            doc.setDrawColor(180, 180, 180);
            doc.setLineWidth(0.1);
            doc.line(margin, y + rowH, pageW - margin, y + rowH);

            y += rowH;
            rowIdx++;
        });

        let totH = 0, totI = 0, totS = 0, totA = 0;
        Object.values(grouped).forEach(r => { totH += r.h; totI += r.i; totS += r.s; totA += r.a; });
        const totAll = totH + totI + totS + totA || 1;

        if (y + rowH > pageH - 22) { doc.addPage(); y = 15; }

        doc.setLineWidth(0.3); doc.setDrawColor(0, 0, 0);
        doc.line(margin, y, pageW - margin, y);
        doc.line(margin, y + rowH, pageW - margin, y + rowH);

        doc.setFont('helvetica', 'bold');
        doc.setTextColor(0, 0, 0);
        let cx = margin;
        ['TOTAL / RATA-RATA', totH, totI, totS, totA, ((totH / totAll) * 100).toFixed(1) + '%'].forEach((v, i) => {
            doc.text(String(v), i === 0 ? cx + 3 : cx + colW[i] / 2, y + 5.5, { align: i === 0 ? 'left' : 'center' });
            cx += colW[i];
        });
        y += rowH;

        // ── KETERANGAN ────────────────────────────────────────────────────
        y += 6;
        if (y + 6 > pageH - 22) { doc.addPage(); y = 15; }
        doc.setFont('helvetica', 'italic');
        doc.setFontSize(7.5);
        doc.setTextColor(80, 80, 80);
        doc.setFillColor(210, 210, 210);
        doc.rect(margin, y, 4, 4, 'F');
        doc.text(`= Alpa lebih dari 3 hari `, margin + 6, y + 3);

        // ── FOOTER TIAP HALAMAN ───────────────────────────────────────────
        const totalPages = doc.internal.getNumberOfPages();
        for (let pg = 1; pg <= totalPages; pg++) {
            doc.setPage(pg);
            doc.setDrawColor(0, 0, 0);
            doc.setLineWidth(0.2);
            doc.line(margin, pageH - 15, pageW - margin, pageH - 15);
            doc.setTextColor(0, 0, 0);
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(8);
            doc.text(`Dokumen Internal — Laporan Presensi ${namaSekolah}  |  Periode: ${periodeLabel}`, margin, pageH - 9);
            doc.text(`Halaman ${pg} dari ${totalPages}`, pageW - margin, pageH - 9, { align: 'right' });
        }

        // ── SIMPAN ────────────────────────────────────────────────────────
        const periodeFile = [
            filterTahun || '',
            filterBulan ? filterBulan.padStart(2, '0') : ''
        ].filter(Boolean).join('-') || 'semua';

        doc.save(`Laporan_Presensi_Guru_${periodeFile}.pdf`);

    } catch (e) {
        console.error(e);
        alert('Gagal mengekspor laporan.');
    } finally {
        btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg> PDF`;
        btn.disabled = false;
    }
}

</script>
@endsection