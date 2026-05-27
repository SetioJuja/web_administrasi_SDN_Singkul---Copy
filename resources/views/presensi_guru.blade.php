@extends('layouts.app')

@section('title', 'Presensi Guru')

@section('content')

<div class="card">

    <div class="page-header">
        <div>
            <h3>Presensi Guru</h3>
            <h4 id="infoPeriode"></h4>
        </div>
    </div>

    <div class="toolbar">
        <input type="month" id="filter_bulan">
        <button class="btn-download-pdf" id="btn_download_pdf" onclick="downloadPDF()">Download PDF</button>
        <div class="legend">
            <span class="badge hadir">H = Hadir</span>
            <span class="badge izin">I = Izin</span>
            <span class="badge sakit">S = Sakit</span>
            <span class="badge alpa">A = Alpa</span>
        </div>
    </div>

    <div id="presensi_info"></div>

    <div class="table-wrapper">
        <table class="presensi-table" id="tabel_presensi">
            <thead>
                <tr id="header_presensi"></tr>
            </thead>
            <tbody id="body_presensi">
                <tr><td colspan="35" class="td-loading"> Memuat data...</td></tr>
            </tbody>
        </table>
    </div>

</div>

<!-- Modal Konfirmasi Hapus -->
<div id="modal-hapus" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-icon"></div>
        <h4 class="modal-title">Hapus Semua Presensi?</h4>
        <p class="modal-desc" id="modal-desc-text"></p>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="tutupModal()">Batal</button>
            <button class="btn-modal-hapus" id="btn-modal-konfirmasi">Ya, Hapus</button>
        </div>
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

/* ── DOWNLOAD BUTTON ── */
.btn-download-pdf {
    padding: 6px 14px;
    background: #c0392b;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    font-weight: 600;
    transition: background .15s;
}

.btn-download-pdf:hover { background: #a93226; }
.btn-download-pdf:disabled { background: #aaa; cursor: not-allowed; }

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
    min-width: 130px;
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

/* ── HAPUS BUTTON ── */
.btn-hapus-presensi {
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 4px;
    background: #dc3545;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background .15s;
    font-weight: 600;
}

.btn-hapus-presensi:hover    { background: #b02a37; }
.btn-hapus-presensi:disabled { background: #aaa; cursor: not-allowed; }

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

/* ── MODAL HAPUS ── */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-box {
    background: #fff;
    border-radius: 14px;
    padding: 32px 28px 24px;
    max-width: 360px;
    width: 90%;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,.2);
    animation: modalIn .18s ease;
}

@keyframes modalIn {
    from { transform: scale(.92); opacity: 0; }
    to   { transform: scale(1);   opacity: 1; }
}

.modal-icon {
    font-size: 38px;
    margin-bottom: 10px;
}

.modal-title {
    margin: 0 0 8px;
    font-size: 16px;
    color: #1a237e;
    font-weight: 700;
}

.modal-desc {
    margin: 0 0 20px;
    font-size: 13px;
    color: #555;
    line-height: 1.5;
}

.modal-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.btn-modal-cancel {
    padding: 8px 22px;
    border-radius: 7px;
    border: 1px solid #ddd;
    background: #f5f5f5;
    color: #555;
    font-size: 13px;
    cursor: pointer;
    font-weight: 600;
    transition: background .15s;
}

.btn-modal-cancel:hover { background: #e9e9e9; }

.btn-modal-hapus {
    padding: 8px 22px;
    border-radius: 7px;
    border: none;
    background: #dc3545;
    color: #fff;
    font-size: 13px;
    cursor: pointer;
    font-weight: 600;
    transition: background .15s;
}

.btn-modal-hapus:hover    { background: #b02a37; }
.btn-modal-hapus:disabled { background: #aaa; cursor: not-allowed; }

</style>

@endsection


@section('script')
<!-- jsPDF & AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<script>

let guru     = [];
let presensi = {};
let tahunAktif = null;
let periodeLabel = '';
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

    // Tutup modal jika klik overlay
    document.getElementById('modal-hapus').addEventListener('click', function (e) {
        if (e.target === this) tutupModal();
    });

    await loadPeriode();
    await loadData();
}


// ================= PERIODE =================
async function loadPeriode() {
    try {
        const res  = await fetch('/api/tahun-ajaran/aktif');
        const json = await res.json();

        const t = (json.data || [])[0];
        tahunAktif = t?.id_tahun_ajaran ?? null;

        const el = document.getElementById('infoPeriode');
        if (el && t) {
            periodeLabel = `Periode: ${t?.periode ?? '-'} | Semester: ${t?.semester ?? '-'}`;
            el.innerText = periodeLabel;
        }
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
    if (body) body.innerHTML = `<tr><td colspan="35" class="td-loading"> Memuat data...</td></tr>`;
}

function setBodyError() {
    const body = document.getElementById('body_presensi');
    if (body) body.innerHTML = `<tr><td colspan="35" class="td-loading" style="color:#dc3545;">Gagal memuat data. Coba refresh halaman.</td></tr>`;
}


// ================= GROUP PRESENSI =================
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

    header.innerHTML += `<th class="aksi-col">Aksi</th>`;

    // ── Body ──
    body.innerHTML = '';

    if (guru.length === 0) {
        body.innerHTML = `<tr><td colspan="${totalHari + 2}" class="td-loading">Tidak ada data guru.</td></tr>`;
        return;
    }

    guru.forEach((g, idx) => {

        const gid = Number(g.id_guru);
        const tr  = document.createElement('tr');

        const tdNama = document.createElement('td');
        tdNama.className = 'nama-col';
        tdNama.innerHTML = `<span style="color:#888; margin-right:4px; font-size:10px;">${idx + 1}.</span>${g.nama_guru}`;
        tr.appendChild(tdNama);

        for (let i = 1; i <= totalHari; i++) {
            const tgl     = bulan + '-' + String(i).padStart(2, '0');
            const isToday = tgl === today;
            const td      = document.createElement('td');
            if (isToday) td.style.background = '#f0f8ff';
            td.innerHTML  = renderCell(gid, tgl);
            tr.appendChild(td);
        }

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

    // Hitung total presensi guru di bulan yang sedang ditampilkan
    const totalBulanIni = Object.keys(presensi[gid] || {})
        .filter(tgl => tgl.startsWith(bulan)).length;

    const btnHapus = totalBulanIni > 0
        ? `<button class="btn-hapus-presensi"
                data-gid="${gid}"
                data-nama="${getNamaGuru(gid)}"
                title="Hapus semua presensi bulan ini (${totalBulanIni} data)">
                 Hapus
            </button>`
        : '';

    return `
        <div style="display:flex; gap:4px; justify-content:center; align-items:center; flex-wrap:wrap;">
            ${statusBadge}
            <button class="btn-aksi-presensi"
                data-id="${data?.id_presensi_guru ?? ''}"
                data-gid="${gid}"
                data-tgl="${tanggal}"
                title="Input presensi hari ini">Input</button>
            ${btnHapus}
        </div>`;
}


// ================= HELPER NAMA GURU =================
function getNamaGuru(gid) {
    return guru.find(g => Number(g.id_guru) === Number(gid))?.nama_guru ?? '-';
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

    document.querySelectorAll('.btn-hapus-presensi').forEach(el => {
        el.addEventListener('click', function (e) {
            e.stopPropagation();
            const gid  = Number(this.dataset.gid);
            const nama = this.dataset.nama;
            bukaBukaModal(gid, nama);
        });
    });
}


// ================= MODAL HAPUS =================
let pendingHapusGid = null;

function bukaBukaModal(gid, namaGuru) {
    pendingHapusGid = gid;

    const totalData = Object.keys(presensi[gid] || {})
        .filter(tgl => tgl.startsWith(bulan)).length;

    document.getElementById('modal-desc-text').innerHTML =
        `Anda akan menghapus <strong>${totalData} data presensi</strong> milik<br>
         <strong>${namaGuru}</strong><br>
         pada bulan <strong>${getBulanLabel(bulan)}</strong>.<br><br>
         <span style="color:#dc3545; font-size:12px;">Tindakan ini tidak dapat dibatalkan.</span>`;

    const btnKonfirmasi = document.getElementById('btn-modal-konfirmasi');
    btnKonfirmasi.disabled    = false;
    btnKonfirmasi.textContent = 'Ya, Hapus';
    btnKonfirmasi.onclick     = () => hapusSemuaPresensi(gid);

    document.getElementById('modal-hapus').style.display = 'flex';
}

function tutupModal() {
    pendingHapusGid = null;
    document.getElementById('modal-hapus').style.display = 'none';
}


// ================= HAPUS SEMUA PRESENSI =================
async function hapusSemuaPresensi(gid) {

    const btnKonfirmasi = document.getElementById('btn-modal-konfirmasi');
    btnKonfirmasi.disabled    = true;
    btnKonfirmasi.textContent = ' Menghapus...';

    // Kumpulkan semua id_presensi_guru milik guru ini di bulan aktif
    const dataGuru = presensi[gid] || {};
    const ids = Object.entries(dataGuru)
        .filter(([tgl]) => tgl.startsWith(bulan))
        .map(([, p]) => p.id_presensi_guru)
        .filter(Boolean);

    if (ids.length === 0) {
        alert('Tidak ada data presensi untuk dihapus.');
        tutupModal();
        return;
    }

    let berhasil = 0;
    let gagal    = 0;

    for (const id of ids) {
        try {
            const res = await fetch('/api/presensi-guru/' + id, {
                method : 'DELETE',
                headers: { 'Accept': 'application/json' }
            });
            if (res.ok) {
                berhasil++;
            } else {
                gagal++;
                console.warn('Gagal hapus id:', id);
            }
        } catch (e) {
            gagal++;
            console.error('Error hapus id:', id, e);
        }
    }

    tutupModal();

    if (gagal === 0) {
        showToast(` ${berhasil} data presensi berhasil dihapus.`, 'success');
    } else {
        showToast(` ${berhasil} berhasil, ${gagal} gagal dihapus.`, 'warning');
    }

    await loadData();
}


// ================= TOAST NOTIFIKASI =================
function showToast(pesan, tipe = 'success') {
    const existing = document.getElementById('toast-notif');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'toast-notif';

    const bg = tipe === 'success' ? '#28a745'
             : tipe === 'warning' ? '#e67e22'
             : '#dc3545';

    toast.style.cssText = `
        position: fixed;
        bottom: 28px;
        right: 28px;
        background: ${bg};
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 4px 16px rgba(0,0,0,.2);
        z-index: 99999;
        animation: toastIn .25s ease;
    `;

    // Inject keyframe sekali saja
    if (!document.getElementById('toast-style')) {
        const s = document.createElement('style');
        s.id = 'toast-style';
        s.textContent = `
            @keyframes toastIn  { from { transform:translateY(16px); opacity:0; } to { transform:translateY(0); opacity:1; } }
            @keyframes toastOut { from { opacity:1; } to { opacity:0; } }
        `;
        document.head.appendChild(s);
    }

    toast.textContent = pesan;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'toastOut .3s ease forwards';
        setTimeout(() => toast.remove(), 320);
    }, 3000);
}


// ================= DROPDOWN =================
function openDropdown(e, id, gid, tanggal) {

    document.querySelectorAll('.dropdown-status').forEach(el => el.remove());

    const div = document.createElement('div');
    div.className = 'dropdown-status';

    const lbl = document.createElement('div');
    lbl.className   = 'dd-label';
    lbl.textContent = `${tanggal}`;
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


// ================= DOWNLOAD PDF =================
function downloadPDF() {
    if (guru.length === 0) {
        alert('Tidak ada data untuk diexport.');
        return;
    }

    const btn = document.getElementById('btn_download_pdf');
    btn.disabled = true;
    btn.textContent = '⏳ Membuat PDF...';

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });

    const [year, month] = bulan.split('-');
    const totalHari     = new Date(year, month, 0).getDate();
    const bulanLabel    = getBulanLabel(bulan);

    // ── Judul ──
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.setTextColor(10, 61, 98);
    doc.text('PRESENSI GURU', doc.internal.pageSize.getWidth() / 2, 14, { align: 'center' });

    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(80, 80, 80);
    doc.text(`Bulan: ${bulanLabel}`, doc.internal.pageSize.getWidth() / 2, 20, { align: 'center' });
    if (periodeLabel) {
        doc.text(periodeLabel, doc.internal.pageSize.getWidth() / 2, 25, { align: 'center' });
    }

    // ── Kolom header ──
    const head = [['No', 'Nama Guru']];
    for (let i = 1; i <= totalHari; i++) head[0].push(String(i));
    head[0].push('H', 'I', 'S', 'A');

    // ── Baris data ──
    const body = [];
    guru.forEach((g, idx) => {
        const gid = Number(g.id_guru);
        const row = [idx + 1, g.nama_guru];

        let h = 0, iz = 0, s = 0, a = 0;

        for (let i = 1; i <= totalHari; i++) {
            const tgl  = bulan + '-' + String(i).padStart(2, '0');
            const data = presensi[gid]?.[tgl];
            const lbl  = data ? getLabel(data.id_status) : '-';
            row.push(lbl);
            if (data) {
                if (data.id_status === 1) h++;
                if (data.id_status === 2) iz++;
                if (data.id_status === 3) s++;
                if (data.id_status === 4) a++;
            }
        }

        row.push(h, iz, s, a);
        body.push(row);
    });

    // ── Lebar kolom ──
    const pageWidth   = doc.internal.pageSize.getWidth() - 20;
    const fixedNo     = 8;
    const fixedNama   = 40;
    const fixedRekap  = 6;
    const sisaWidth   = pageWidth - fixedNo - fixedNama - (fixedRekap * 4);
    const hariWidth   = Math.max(4, parseFloat((sisaWidth / totalHari).toFixed(2)));

    const colWidths = [fixedNo, fixedNama, ...Array(totalHari).fill(hariWidth), fixedRekap, fixedRekap, fixedRekap, fixedRekap];

    doc.autoTable({
        head        : head,
        body        : body,
        startY      : periodeLabel ? 30 : 25,
        margin      : { left: 10, right: 10 },
        tableWidth  : 'auto',
        styles      : {
            fontSize   : 6.5,
            cellPadding: 1.5,
            halign     : 'center',
            valign     : 'middle',
            lineColor  : [200, 200, 200],
            lineWidth  : 0.1,
        },
        headStyles  : {
            fillColor  : [10, 61, 98],
            textColor  : [255, 255, 255],
            fontStyle  : 'bold',
            fontSize   : 6.5,
        },
        columnStyles: {
            0: { cellWidth: fixedNo },
            1: { cellWidth: fixedNama, halign: 'left', fontStyle: 'bold' },
            ...Object.fromEntries(
                Array.from({ length: totalHari }, (_, i) => [i + 2, { cellWidth: hariWidth }])
            ),
            [totalHari + 2]: { cellWidth: fixedRekap, fillColor: [232, 245, 233], textColor: [27, 94, 32],  fontStyle: 'bold' },
            [totalHari + 3]: { cellWidth: fixedRekap, fillColor: [255, 248, 225], textColor: [102, 60, 0],  fontStyle: 'bold' },
            [totalHari + 4]: { cellWidth: fixedRekap, fillColor: [225, 245, 254], textColor: [1, 87, 155],  fontStyle: 'bold' },
            [totalHari + 5]: { cellWidth: fixedRekap, fillColor: [255, 235, 238], textColor: [183, 28, 28], fontStyle: 'bold' },
        },
        alternateRowStyles: { fillColor: [248, 250, 255] },
        didParseCell(data) {
            if (data.section === 'body' && data.column.index >= 2 && data.column.index <= totalHari + 1) {
                const val = data.cell.raw;
                if (val === 'H') { data.cell.styles.fillColor = [40, 167, 69];  data.cell.styles.textColor = [255,255,255]; data.cell.styles.fontStyle = 'bold'; }
                if (val === 'I') { data.cell.styles.fillColor = [255, 193, 7];  data.cell.styles.textColor = [50,50,50];   data.cell.styles.fontStyle = 'bold'; }
                if (val === 'S') { data.cell.styles.fillColor = [23, 162, 184]; data.cell.styles.textColor = [255,255,255]; data.cell.styles.fontStyle = 'bold'; }
                if (val === 'A') { data.cell.styles.fillColor = [220, 53, 69];  data.cell.styles.textColor = [255,255,255]; data.cell.styles.fontStyle = 'bold'; }
            }
        },
    });

    // ── Footer tanggal cetak ──
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(7);
        doc.setTextColor(150, 150, 150);
        doc.text(
            `Dicetak pada: ${new Date().toLocaleString('id-ID')}   |   Hal ${i} dari ${pageCount}`,
            doc.internal.pageSize.getWidth() / 2,
            doc.internal.pageSize.getHeight() - 5,
            { align: 'center' }
        );
    }

    doc.save(`Presensi_Guru_${bulan}.pdf`);

    btn.disabled = false;
    btn.textContent = 'Download PDF';
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