@extends('layouts.app')

@section('title','Rekap Nilai Siswa')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --bg:#f8fafc;
    --text:#1f2937;
}

body{
    background:var(--bg);
}

.card{
    background:white;
    border-radius:14px;
    padding:22px;
    border:1px solid var(--border);
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:18px;
    flex-wrap:wrap;
    gap:12px;
}

.header h3{
    margin:0;
    color:var(--primary);
    font-size:22px;
}

.subtitle{
    font-size:13px;
    color:#64748b;
    margin-top:4px;
}

.top-bar{
    display:flex;
    gap:10px;
    margin-bottom:18px;
    flex-wrap:wrap;
    align-items:center;
}

select,
input{
    height:42px;
    padding:0 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    background:white;
    font-size:13px;
    color:var(--text);
}

select:focus,
input:focus{
    border-color:#2563eb;
}

.btn{
    height:42px;
    border:none;
    padding:0 16px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
    font-weight:600;
    color:white;
}

.btn-primary{ background:#0a3d62; }
.btn-primary:hover{ background:#083150; }

.btn-success{ background:#16a34a; }
.btn-success:hover{ background:#15803d; }

.info{
    margin-bottom:14px;
    font-size:13px;
    color:#64748b;
}

.table-wrap{
    overflow:auto;
    border:1px solid var(--border);
    border-radius:10px;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:900px;
    font-size:13px;
}

thead{
    background:var(--primary);
    color:white;
}

th{
    padding:11px 10px;
    text-align:center;
    white-space:nowrap;
    font-weight:600;
    border:1px solid rgba(255,255,255,0.15);
}

td{
    padding:9px 10px;
    border:1px solid #e5e7eb;
    text-align:center;
    color:var(--text);
    vertical-align:middle;
}

tr.row-first td{
    border-top:2px solid #0a3d62 !important;
}

tr.row-last td{
    border-bottom:2px solid #0a3d62 !important;
}

td.nama-siswa{
    text-align:left;
    font-weight:600;
    background:#f0f4f8;
    color:var(--primary);
    min-width:160px;
    border-left:3px solid var(--primary) !important;
}

td.nama-mapel{
    text-align:left;
    min-width:160px;
}

td.nilai{
    min-width:70px;
}

.loading{
    padding:30px;
    text-align:center;
    color:#64748b;
    display:none;
}

.empty-state{
    text-align:center;
    padding:35px;
    color:#64748b;
}

.btn-lihat{
    background:#2563eb;
    border:none;
    border-radius:7px;
    padding:5px 12px;
    font-size:12px;
    cursor:pointer;
    color:white;
    display:inline-flex;
    align-items:center;
    gap:4px;
}
.btn-lihat:hover{
    background:#1d4ed8;
}

.modal-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:9999;
}

.modal-overlay.show{
    display:flex;
}

.modal-box{
    width:520px;
    max-width:95vw;
    background:white;
    border-radius:18px;
    padding:24px;
    max-height:90vh;
    overflow-y:auto;
}

.modal-box .modal-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.modal-box .modal-header h4{
    margin:0;
    color:var(--primary);
    font-size:17px;
    font-weight:700;
}

.modal-box .modal-header .judul-sub{
    font-size:12px;
    color:#64748b;
    margin-top:3px;
}

.modal-close{
    font-size:24px;
    cursor:pointer;
    color:#94a3b8;
    line-height:1;
    border:none;
    background:none;
    padding:0;
}

.modal-close:hover{
    color:#0a3d62;
}

.modal-card{
    border:1px solid var(--border);
    border-radius:10px;
    margin-bottom:14px;
    overflow:hidden;
}

.modal-card-header{
    background:#f1f5f9;
    padding:9px 14px;
    font-size:13px;
    font-weight:600;
    color:var(--primary);
    border-bottom:1px solid var(--border);
}

.modal-card-body{
    padding:14px;
    font-size:13px;
    color:var(--text);
    line-height:1.7;
    min-height:50px;
    white-space:pre-wrap;
}

.modal-card-body.empty-text{
    color:#94a3b8;
    font-style:italic;
}

@media(max-width:768px){
    .top-bar{ flex-direction:column; align-items:stretch; }
    select, input, .btn{ width:100%; }
}
</style>

<div class="modal-overlay" id="modalDeskripsi">
    <div class="modal-box">

        <div class="modal-header">
            <div>
                <h4>Detail Deskripsi</h4>
                <div class="judul-sub" id="modalSubjudul"></div>
            </div>
            <button class="modal-close" onclick="tutupModal()">&times;</button>
        </div>

        <div class="modal-card">
            <div class="modal-card-header">Deskripsi Pengetahuan</div>
            <div class="modal-card-body" id="deskripsiPengetahuan"></div>
        </div>

        <div class="modal-card">
            <div class="modal-card-header">Deskripsi Keterampilan</div>
            <div class="modal-card-body" id="deskripsiKeterampilan"></div>
        </div>

    </div>
</div>


<div class="card">

    <div class="header">
        <div>
            <h3>Rekap Nilai Siswa</h3>
            <div class="subtitle" id="subtitle">Pilih kelas untuk menampilkan data</div>
        </div>
        <button class="btn btn-success" onclick="location.href='/rapor'">
            Kelola Rapor
        </button>
    </div>

    <div class="top-bar">
        <input id="search" placeholder="Cari nama siswa..." style="max-width:220px;">
    </div>

    <div class="info" id="info"></div>

    <div class="loading" id="loading">Memuat data...</div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Mata Pelajaran</th>
                    <th>Rata-rata Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Total</th>
                    <th>Keterampilan</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody id="data">
                <tr>
                    <td colspan="8" class="empty-state">Pilih kelas untuk menampilkan data</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection


@section('script')
<script>

let rawData = [];
let id_kelas;
let namaKelas = '';
let id_guru;

const deskripsiStore = {};

document.addEventListener('DOMContentLoaded', init);

async function init(){
    const user = JSON.parse(localStorage.getItem('user'));
    if(!user){ location.href = '/login'; return; }
    id_guru = user.id;

    await loadKelasSaya();

    document.getElementById('search').addEventListener('input', filterRender);

    // tutup modal klik backdrop
    document.getElementById('modalDeskripsi')
        .addEventListener('click', function(e){
            if(e.target === this) tutupModal();
        });
}

async function loadKelasSaya(){
    const res  = await fetch('/api/kelas-saya/' + id_guru);
    const json = await res.json();

    if(!json.data || json.data.length === 0){
        alert('Tidak punya kelas');
        return;
    }

    id_kelas  = json.data[0].id_kelas;
    namaKelas = json.data[0].nama_kelas;
    loadData();
}

async function loadData(){
    if(!id_kelas) return;

    showLoading(true);
    clearTable();

    const res  = await fetch('/api/nilai-siswa-semua/' + id_kelas);
    const json = await res.json();

    rawData = json.data || [];

    filterRender();
    showLoading(false);
}

function filterRender(){
    const keyword = document.getElementById('search').value.toLowerCase().trim();

    const grouped = {};
    const order   = [];

    rawData.forEach(row => {
        if(!grouped[row.id_siswa]){
            grouped[row.id_siswa] = { nama: row.nama_siswa, mapel: [] };
            order.push(row.id_siswa);
        }
        grouped[row.id_siswa].mapel.push(row);
    });

    const filtered = order
        .filter(id => !keyword || grouped[id].nama.toLowerCase().includes(keyword))
        .map(id => ({ id, ...grouped[id] }));

    document.getElementById('subtitle').innerText =
        namaKelas ? 'Kelas : ' + namaKelas : '';

    render(filtered);
}

function render(data){

    if(data.length === 0){
        document.getElementById('data').innerHTML =
            `<tr><td colspan="8" class="empty-state">Data tidak ditemukan</td></tr>`;
        document.getElementById('info').innerText = '';
        return;
    }

    Object.keys(deskripsiStore).forEach(k => delete deskripsiStore[k]);

    let html    = '';
    let deskKey = 0;

    data.forEach(siswa => {
        const mapelList = siswa.mapel;
        const rowspan   = mapelList.length || 1;

        if(mapelList.length === 0){
            html += `
            <tr class="row-first row-last">
                <td class="nama-siswa">${esc(siswa.nama)}</td>
                <td colspan="7" class="empty-state" style="padding:10px;">Belum ada data nilai</td>
            </tr>`;
            return;
        }

        mapelList.forEach((m, i) => {
            const isFirst  = (i === 0);
            const isLast   = (i === mapelList.length - 1);
            const rowClass = [
                isFirst ? 'row-first' : '',
                isLast  ? 'row-last'  : ''
            ].filter(Boolean).join(' ');

            const avg          = fmt(m.nilai_tugas);
            const uts          = m.nilai_uts          ?? '-';
            const uas          = m.nilai_uas          ?? '-';
            const total        = m.total              ?? '-';
            const keterampilan = m.nilai_keterampilan ?? '-';

            const key = deskKey;
            deskripsiStore[key] = {
                subjudul    : siswa.nama + ' — ' + m.nama_mapel,
                pengetahuan : m.deskripsi_pengetahuan  || '',
                keterampilan: m.deskripsi_keterampilan || ''
            };
            deskKey++;

            html += `<tr class="${rowClass}">`;

            if(isFirst){
                html += `<td class="nama-siswa" rowspan="${rowspan}">${esc(siswa.nama)}</td>`;
            }

            html += `
                <td class="nama-mapel">${esc(m.nama_mapel)}</td>
                <td class="nilai">${avg}</td>
                <td class="nilai">${uts}</td>
                <td class="nilai">${uas}</td>
                <td class="nilai" style="font-weight:700;">${total !== '-' ? Number(total).toFixed(2) : '-'}</td>
                <td class="nilai">${keterampilan}</td>
                <td style="text-align:center;min-width:110px;">
                    <button class="btn-lihat" onclick="bukaDeskripsi(${key})">
                        <i class='bx bx-notepad'></i> Lihat
                    </button>
                </td>
            </tr>`;
        });
    });

    document.getElementById('data').innerHTML = html;
    document.getElementById('info').innerText = `Menampilkan ${data.length} siswa`;
}

function bukaDeskripsi(key){
    const d = deskripsiStore[key];
    if(!d) return;

    document.getElementById('modalSubjudul').innerText = d.subjudul;

    const elP = document.getElementById('deskripsiPengetahuan');
    const elK = document.getElementById('deskripsiKeterampilan');

    elP.textContent = d.pengetahuan  || '-';
    elK.textContent = d.keterampilan || '-';

    elP.className = 'modal-card-body' + (!d.pengetahuan  ? ' empty-text' : '');
    elK.className = 'modal-card-body' + (!d.keterampilan ? ' empty-text' : '');

    document.getElementById('modalDeskripsi').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function tutupModal(){
    document.getElementById('modalDeskripsi').classList.remove('show');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
    if(e.key === 'Escape') tutupModal();
});

function fmt(val){
    if(val === null || val === undefined || val === '') return '-';
    const n = Number(val);
    return isNaN(n) ? '-' : n.toFixed(2);
}

function esc(str){
    if(!str) return '';
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;');
}

function clearTable(){
    document.getElementById('data').innerHTML =
        `<tr><td colspan="8" class="empty-state">Memuat...</td></tr>`;
    document.getElementById('info').innerText = '';
}

function showLoading(status=true){
    document.getElementById('loading').style.display = status ? 'block' : 'none';
}

</script>
@endsection