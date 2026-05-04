@extends('layouts.app')

@section('title', 'Presensi Guru')

@section('content')

<div class="card">

    <div class="page-header">
        <div>
            <h3>📅 Presensi Guru</h3>
            <h4 id="infoPeriode"></h4>
        </div>
    </div>

    <div class="toolbar">
        <input type="month" id="filter_bulan">
        <div class="legend">
            <span class="badge hadir">H = Hadir</span>
            <span class="badge izin">I = Izin</span>
            <span class="badge sakit">S = Sakit</span>
            <span class="badge alpa">A = Alpa</span>
        </div>
    </div>

    <div id="presensi_info"></div>

    <div class="table-wrapper">
        <table class="presensi-table">
            <thead>
                <tr id="header_presensi"></tr>
            </thead>
            <tbody id="body_presensi">
                <tr><td colspan="35" class="td-loading">⏳ Memuat data...</td></tr>
            </tbody>
        </table>
    </div>

</div>

<style>

/* ── CARD ── */
.card {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.08);
}

/* ── HEADER ── */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 16px;
}

.page-header h3 {
    margin: 0;
    font-size: 18px;
    color: #1a237e;
    font-weight: 700;
}

#infoPeriode {
    margin: 4px 0 0;
    font-size: 13px;
    color: #555;
    font-weight: 400;
}

/* ── TOOLBAR ── */
.toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.toolbar input[type="month"] {
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 13px;
    outline: none;
    transition: border-color .2s;
}

.toolbar input[type="month"]:focus {
    border-color: #1e5ccc;
}

.legend {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

/* ── PRESENSI INFO ── */
#presensi_info {
    color: #888;
    font-size: 12px;
    margin-bottom: 6px;
}

/* ── TABLE WRAPPER ── */
.table-wrapper {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    margin-top: 10px;
}

/* ── TABLE ── */
.presensi-table {
    border-collapse: collapse;
    width: 100%;
    min-width: 700px;
    font-size: 12px;
}

.presensi-table th,
.presensi-table td {
    border: 1px solid #e0e0e0;
    padding: 5px 4px;
    text-align: center;
    white-space: nowrap;
}

.presensi-table thead th {
    background: #0a3d62;
    color: #fff;
    font-weight: 600;
    position: sticky;
    top: 0;
    z-index: 3;
}

.presensi-table tbody tr:nth-child(even) { background: #fafbff; }
.presensi-table tbody tr:hover           { background: #eef3ff; }

/* ── STICKY COLUMNS ── */
.nama-col {
    position: sticky;
    left: 0;
    background: #f1f5ff;
    font-weight: bold;
    z-index: 2;
    min-width: 160px;
    text-align: left !important;
    padding-left: 8px !important;
}

thead .nama-col {
    background: #1565c0;
    z-index: 4;
}

.aksi-col {
    background: #eef3ff;
    min-width: 90px;
}

thead .aksi-col { background: #1565c0; }

/* ── BADGE STATUS ── */
.badge {
    padding: 2px 7px;
    border-radius: 4px;
    font-weight: bold;
    display: inline-block;
    font-size: 11px;
}

.hadir { background: #28a745; color: #fff; }
.izin  { background: #ffc107; color: #333; }
.sakit { background: #17a2b8; color: #fff; }
.alpa  { background: #dc3545; color: #fff; }

/* ── CELL ── */
.cell-presensi {
    cursor: pointer;
}

.cell-kosong {
    cursor: pointer;
    color: #ccc;
    font-size: 13px;
    display: inline-block;
    width: 100%;
}

.cell-kosong:hover { color: #1e5ccc; }

/* ── AKSI BUTTON ── */
.btn-aksi-presensi {
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 4px;
    background: #1e5ccc;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background .15s;
}

.btn-aksi-presensi:hover { background: #174aaa; }

/* ── DROPDOWN ── */
.dropdown-status {
    position: absolute;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 6px 16px rgba(0,0,0,.15);
    z-index: 9999;
    padding: 4px;
    min-width: 130px;
}

.dropdown-status .dd-label {
    padding: 5px 10px 4px;
    font-size: 10px;
    color: #888;
    border-bottom: 1px solid #eee;
    margin-bottom: 3px;
}

.dropdown-status button {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    border: none;
    background: none;
    padding: 7px 10px;
    cursor: pointer;
    text-align: left;
    font-size: 12px;
    border-radius: 5px;
    transition: background .1s;
}

.dropdown-status button:hover { background: #f0f4ff; }

/* ── LOADING / EMPTY ── */
.td-loading {
    text-align: center;
    color: #aaa;
    padding: 20px !important;
    font-style: italic;
}

</style>

@endsection


@section('script')
<script>

let guru     = [];
let presensi = {};
let tahunAktif = null;
let bulan    = new Date().toISOString().slice(0, 7);


// ================= INIT =================
document.addEventListener('DOMContentLoaded', init);

async function init() {

    const filterBulan = document.getElementById('filter_bulan');
    filterBulan.value = bulan;
    filterBulan.addEventListener('change', function () {
        bulan = this.value;
        renderTable();
    });

    await loadPeriode();
    await loadData();
}


// ================= PERIODE =================
async function loadPeriode() {
    try {
        const res  = await fetch('/api/tahun-ajaran/aktif');
        const json = await res.json();

        // Ambil tahun ajaran pertama sebagai aktif
        const t = (json.data || [])[0];
        tahunAktif = t?.id_tahun_ajaran ?? null;

        const el = document.getElementById('infoPeriode');
        if (el && t) el.innerText = `Periode: ${t?.periode ?? '-'} | Semester: ${t?.semester ?? '-'}`;
    } catch (e) {
        console.error('Gagal load periode:', e);
    }
}


// ================= LOAD DATA =================
async function loadData() {

    setBodyLoading();

    try {
        const [resGuru, resPresensi] = await Promise.all([
            fetch('/api/pegawai'),
            fetch('/api/presensi-guru')
        ]);

        const dataGuru     = await resGuru.json();
        const dataPresensi = await resPresensi.json();

        guru = (dataGuru.data || []).map(g => ({
            id_guru   : Number(g.id_guru),
            nama_guru : g.nama_guru
        }));

        presensi = groupPresensi(dataPresensi.data || []);

        renderTable();

    } catch (e) {
        console.error('Gagal load data:', e);
        setBodyError();
    }
}

function setBodyLoading() {
    const body = document.getElementById('body_presensi');
    if (body) body.innerHTML = `<tr><td colspan="35" class="td-loading">⏳ Memuat data...</td></tr>`;
}

function setBodyError() {
    const body = document.getElementById('body_presensi');
    if (body) body.innerHTML = `<tr><td colspan="35" class="td-loading" style="color:#dc3545;">Gagal memuat data. Coba refresh halaman.</td></tr>`;
}


// ================= GROUP PRESENSI =================
// Kelompokkan: presensi[id_guru][tanggal] = record
function groupPresensi(data) {
    const result = {};
    data.forEach(p => {
        const gid = Number(p.id_guru);
        if (!gid) return;
        if (!result[gid]) result[gid] = {};
        result[gid][p.tanggal] = p;
    });
    return result;
}


// ================= RENDER TABLE =================
function renderTable() {

    const header = document.getElementById('header_presensi');
    const body   = document.getElementById('body_presensi');
    const info   = document.getElementById('presensi_info');

    const [year, month] = bulan.split('-');
    const totalHari     = new Date(year, month, 0).getDate();
    const today         = new Date().toISOString().slice(0, 10);

    if (info) info.innerText = `Total guru: ${guru.length} | Bulan: ${getBulanLabel(bulan)}`;

    // ── Header ──
    header.innerHTML = `<th class="nama-col">Nama Guru</th>`;

    for (let i = 1; i <= totalHari; i++) {
        const tgl     = bulan + '-' + String(i).padStart(2, '0');
        const isToday = tgl === today;
        header.innerHTML += `<th style="${isToday ? 'background:#0d47a1;' : ''}">${i}</th>`;
    }

    header.innerHTML += `<th class="aksi-col">Aksi Hari Ini</th>`;

    // ── Body ──
    body.innerHTML = '';

    if (guru.length === 0) {
        body.innerHTML = `<tr><td colspan="${totalHari + 2}" class="td-loading">Tidak ada data guru.</td></tr>`;
        return;
    }

    guru.forEach((g, idx) => {

        const gid = Number(g.id_guru);
        const tr  = document.createElement('tr');

        // Kolom nama
        const tdNama = document.createElement('td');
        tdNama.className = 'nama-col';
        tdNama.innerHTML = `<span style="color:#888; margin-right:4px; font-size:10px;">${idx + 1}.</span>${g.nama_guru}`;
        tr.appendChild(tdNama);

        // Kolom per tanggal
        for (let i = 1; i <= totalHari; i++) {
            const tgl     = bulan + '-' + String(i).padStart(2, '0');
            const isToday = tgl === today;
            const td      = document.createElement('td');
            if (isToday) td.style.background = '#f0f8ff';
            td.innerHTML  = renderCell(gid, tgl);
            tr.appendChild(td);
        }

        // Kolom aksi hari ini
        const tdAksi = document.createElement('td');
        tdAksi.className = 'aksi-col';
        tdAksi.innerHTML = renderAksiCell(gid, today);
        tr.appendChild(tdAksi);

        body.appendChild(tr);
    });

    attachListeners();
}


// ================= RENDER CELL =================
function renderCell(gid, tanggal) {

    const data = presensi[gid]?.[tanggal];

    if (!data) {
        return `<span class="cell-presensi cell-kosong"
                    data-id=""
                    data-gid="${gid}"
                    data-tgl="${tanggal}"
                    title="${tanggal}">–</span>`;
    }

    return `<span class="badge cell-presensi ${getClass(data.id_status)}"
                data-id="${data.id_presensi_guru}"
                data-gid="${gid}"
                data-tgl="${tanggal}"
                title="${tanggal}">
                ${getLabel(data.id_status)}
            </span>`;
}


// ================= RENDER CELL AKSI =================
function renderAksiCell(gid, tanggal) {

    const data = presensi[gid]?.[tanggal];

    const statusBadge = data
        ? `<span class="badge ${getClass(data.id_status)}">${getLabel(data.id_status)}</span>`
        : `<span style="color:#ccc; font-size:11px;">–</span>`;

    return `
        <div style="display:flex; gap:4px; justify-content:center; align-items:center;">
            ${statusBadge}
            <button class="btn-aksi-presensi"
                data-id="${data?.id_presensi_guru ?? ''}"
                data-gid="${gid}"
                data-tgl="${tanggal}"
                title="Input presensi hari ini">✏️</button>
        </div>`;
}


// ================= ATTACH LISTENERS =================
function attachListeners() {

    document.querySelectorAll('.cell-presensi').forEach(el => {
        el.addEventListener('click', function (e) {
            openDropdown(e,
                this.dataset.id  || null,
                Number(this.dataset.gid),
                this.dataset.tgl
            );
        });
    });

    document.querySelectorAll('.btn-aksi-presensi').forEach(el => {
        el.addEventListener('click', function (e) {
            e.stopPropagation();
            openDropdown(e,
                this.dataset.id  || null,
                Number(this.dataset.gid),
                this.dataset.tgl
            );
        });
    });
}


// ================= DROPDOWN =================
function openDropdown(e, id, gid, tanggal) {

    document.querySelectorAll('.dropdown-status').forEach(el => el.remove());

    const div = document.createElement('div');
    div.className = 'dropdown-status';

    const lbl = document.createElement('div');
    lbl.className   = 'dd-label';
    lbl.textContent = `📅 ${tanggal}`;
    div.appendChild(lbl);

    const statuses = [
        { label: 'Hadir', icon: '🟢', val: 1 },
        { label: 'Izin',  icon: '🟡', val: 2 },
        { label: 'Sakit', icon: '🔵', val: 3 },
        { label: 'Alpa',  icon: '🔴', val: 4 },
    ];

    statuses.forEach(({ label, icon, val }) => {
        const btn = document.createElement('button');
        btn.innerHTML = `${icon} ${label}`;
        btn.addEventListener('click', ev => {
            ev.stopPropagation();
            selectStatus(id, gid, tanggal, val);
        });
        div.appendChild(btn);
    });

    document.body.appendChild(div);

    let x = e.pageX + 5;
    let y = e.pageY + 5;
    if (x + 145 > window.innerWidth  + window.scrollX) x = e.pageX - 145;
    if (y + 200 > window.innerHeight + window.scrollY) y = e.pageY - 200;

    div.style.top  = y + 'px';
    div.style.left = x + 'px';

    setTimeout(() => {
        document.addEventListener('click', closeDropdown, { once: true });
    }, 10);
}

function closeDropdown() {
    document.querySelectorAll('.dropdown-status').forEach(el => el.remove());
}


// ================= PILIH STATUS =================
function selectStatus(id, gid, tanggal, status) {
    closeDropdown();
    if (!id) {
        createPresensi(gid, tanggal, status);
    } else {
        updatePresensi(id, gid, tanggal, status);
    }
}


// ================= CREATE =================
async function createPresensi(id_guru, tanggal, id_status) {
    try {
        const body = { id_guru, tanggal, id_status };
        if (tahunAktif) body.id_tahun_ajaran = tahunAktif;

        const res  = await fetch('/api/presensi-guru', {
            method : 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body   : JSON.stringify(body)
        });
        const data = await res.json();
        if (!res.ok) { alert(data.message || 'Gagal simpan'); return; }
        await loadData();
    } catch (e) {
        console.error('Gagal simpan presensi guru:', e);
        alert('Terjadi kesalahan saat menyimpan.');
    }
}


// ================= UPDATE =================
async function updatePresensi(id, id_guru, tanggal, id_status) {
    try {
        const body = { id_guru, tanggal, id_status };
        if (tahunAktif) body.id_tahun_ajaran = tahunAktif;

        const res  = await fetch('/api/presensi-guru/' + id, {
            method : 'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body   : JSON.stringify(body)
        });
        const data = await res.json();
        if (!res.ok) { alert(data.message || 'Gagal update'); return; }
        await loadData();
    } catch (e) {
        console.error('Gagal update presensi guru:', e);
        alert('Terjadi kesalahan saat mengupdate.');
    }
}


// ================= HELPER =================
function getLabel(s) { return ['', 'H', 'I', 'S', 'A'][s] ?? '-'; }
function getClass(s) { return ['', 'hadir', 'izin', 'sakit', 'alpa'][s] ?? ''; }

function getBulanLabel(ym) {
    const [y, m] = ym.split('-');
    const nama   = ['','Januari','Februari','Maret','April','Mei','Juni',
                    'Juli','Agustus','September','Oktober','November','Desember'];
    return `${nama[parseInt(m)]} ${y}`;
}

</script>
@endsection