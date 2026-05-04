@extends('layouts.app')

@section('title', 'Presensi Siswa')

@section('content')

<div class="card">

    <div class="page-header">
        <div>
            <h3>📅 Presensi Siswa</h3>
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
    min-width: 140px;
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

let siswa    = [];
let presensi = {};
let bulan    = new Date().toISOString().slice(0, 7);
let id_guru  = null;


// ================= INIT =================
document.addEventListener('DOMContentLoaded', init);

async function init() {

    const user = JSON.parse(localStorage.getItem('user'));

    if (!user) {
        alert('Login dulu');
        location.href = '/login';
        return;
    }

    id_guru = user.id;

    const filterBulan = document.getElementById('filter_bulan');
    filterBulan.value = bulan;
    filterBulan.addEventListener('change', function () {
        bulan = this.value;
        loadData();
    });

    await loadPeriode();
    await loadData();
}


// ================= PERIODE =================
async function loadPeriode() {
    try {
        const res  = await fetch('/api/tahun_ajaran/aktif1');
        const json = await res.json();
        const t    = json.data;
        const el   = document.getElementById('infoPeriode');
        if (el) el.innerText = `Periode: ${t?.periode ?? '-'} | Semester: ${t?.semester ?? '-'}`;
    } catch (e) {
        console.error('Gagal load periode:', e);
    }
}


// ================= LOAD DATA =================
async function loadData() {

    setBodyLoading();

    try {
        const [resSiswa, resPresensi] = await Promise.all([
            fetch('/api/kelas-sayaP/' + id_guru),
            fetch('/api/presensi')
        ]);

        const dataSiswa    = await resSiswa.json();
        const dataPresensi = await resPresensi.json();

        siswa = (dataSiswa.data || []).map(s => ({
            ...s,
            id_siswa: Number(s.id_siswa)
        }));

        // Filter presensi hanya untuk bulan aktif
        const filtered = (dataPresensi.data || []).filter(p =>
            p.tanggal.startsWith(bulan)
        );

        presensi = groupPresensi(filtered);
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
function groupPresensi(data) {
    const result = {};
    data.forEach(p => {
        const sid = Number(p.id_siswa);
        if (!sid) return;
        if (!result[sid]) result[sid] = {};
        result[sid][p.tanggal] = p;
    });
    return result;
}


// ================= RENDER TABLE =================
function renderTable() {

    const header = document.getElementById('header_presensi');
    const body   = document.getElementById('body_presensi');
    const info   = document.getElementById('presensi_info');

    const [year, month] = bulan.split('-');
    const totalHari     = new Date(year, month, 0).getDate();   // jumlah hari dalam bulan
    const today         = new Date().toISOString().slice(0, 10);

    // Info
    if (info) info.innerText = `Total siswa: ${siswa.length} | Bulan: ${getBulanLabel(bulan)}`;

    // ── Header ──
    header.innerHTML = `<th class="nama-col">Nama Siswa</th>`;

    for (let i = 1; i <= totalHari; i++) {
        const tgl     = bulan + '-' + String(i).padStart(2, '0');
        const isToday = tgl === today;
        header.innerHTML += `<th style="${isToday ? 'background:#0d47a1;' : ''}">${i}</th>`;
    }

    header.innerHTML += `<th class="aksi-col">Aksi Hari Ini</th>`;

    // ── Body ──
    body.innerHTML = '';

    if (siswa.length === 0) {
        body.innerHTML = `<tr><td colspan="${totalHari + 2}" class="td-loading">Tidak ada siswa di kelas ini.</td></tr>`;
        return;
    }

    siswa.forEach((s, idx) => {

        const sid = Number(s.id_siswa);
        const tr  = document.createElement('tr');

        // Kolom nama
        const tdNama = document.createElement('td');
        tdNama.className = 'nama-col';
        tdNama.innerHTML = `<span style="color:#888; margin-right:4px; font-size:10px;">${idx + 1}.</span>${s.nama_siswa}`;
        tr.appendChild(tdNama);

        // Kolom per tanggal
        for (let i = 1; i <= totalHari; i++) {
            const tgl     = bulan + '-' + String(i).padStart(2, '0');
            const isToday = tgl === today;
            const td      = document.createElement('td');
            if (isToday) td.style.background = '#f0f8ff';
            td.innerHTML  = renderCell(sid, tgl);
            tr.appendChild(td);
        }

        // Kolom aksi hari ini
        const tdAksi = document.createElement('td');
        tdAksi.className = 'aksi-col';
        tdAksi.innerHTML = renderAksiCell(sid, today);
        tr.appendChild(tdAksi);

        body.appendChild(tr);
    });

    attachListeners();
}


// ================= RENDER CELL =================
function renderCell(sid, tanggal) {

    const data = presensi[sid]?.[tanggal];

    if (!data) {
        return `<span class="cell-presensi cell-kosong"
                    data-id=""
                    data-sid="${sid}"
                    data-tgl="${tanggal}"
                    title="${tanggal}">–</span>`;
    }

    return `<span class="badge cell-presensi ${getClass(data.id_status)}"
                data-id="${data.id_presensi_siswa}"
                data-sid="${sid}"
                data-tgl="${tanggal}"
                title="${tanggal}">
                ${getLabel(data.id_status)}
            </span>`;
}


// ================= RENDER CELL AKSI =================
function renderAksiCell(sid, tanggal) {

    const data = presensi[sid]?.[tanggal];

    const statusBadge = data
        ? `<span class="badge ${getClass(data.id_status)}">${getLabel(data.id_status)}</span>`
        : `<span style="color:#ccc; font-size:11px;">–</span>`;

    return `
        <div style="display:flex; gap:4px; justify-content:center; align-items:center;">
            ${statusBadge}
            <button class="btn-aksi-presensi"
                data-id="${data?.id_presensi_siswa ?? ''}"
                data-sid="${sid}"
                data-tgl="${tanggal}"
                title="Input presensi hari ini">✏️</button>
        </div>`;
}


// ================= ATTACH LISTENERS =================
function attachListeners() {

    document.querySelectorAll('.cell-presensi').forEach(el => {
        el.addEventListener('click', function (e) {
            openDropdown(e,
                this.dataset.id || null,
                Number(this.dataset.sid),
                this.dataset.tgl
            );
        });
    });

    document.querySelectorAll('.btn-aksi-presensi').forEach(el => {
        el.addEventListener('click', function (e) {
            e.stopPropagation();
            openDropdown(e,
                this.dataset.id || null,
                Number(this.dataset.sid),
                this.dataset.tgl
            );
        });
    });
}


// ================= DROPDOWN =================
function openDropdown(e, id, sid, tanggal) {

    // Tutup dropdown lain
    document.querySelectorAll('.dropdown-status').forEach(el => el.remove());

    const div = document.createElement('div');
    div.className = 'dropdown-status';

    // Label tanggal
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
            selectStatus(id, sid, tanggal, val);
        });
        div.appendChild(btn);
    });

    document.body.appendChild(div);

    // Posisi agar tidak keluar layar
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
function selectStatus(id, sid, tanggal, status) {
    closeDropdown();
    if (!id) {
        setPresensi(sid, tanggal, status);
    } else {
        updatePresensi(id, sid, tanggal, status);
    }
}


// ================= CREATE =================
async function setPresensi(id_siswa, tanggal, id_status) {
    try {
        const res  = await fetch('/api/presensi', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_siswa, tanggal, id_status })
        });
        const data = await res.json();
        if (!res.ok) { alert(data.message || 'Gagal simpan'); return; }
        loadData();
    } catch (e) {
        console.error('Gagal simpan presensi:', e);
    }
}


// ================= UPDATE =================
async function updatePresensi(id, id_siswa, tanggal, id_status) {
    try {
        const res  = await fetch('/api/presensi/' + id, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_siswa, tanggal, id_status })
        });
        const data = await res.json();
        if (!res.ok) { alert(data.message || 'Gagal update'); return; }
        loadData();
    } catch (e) {
        console.error('Gagal update presensi:', e);
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