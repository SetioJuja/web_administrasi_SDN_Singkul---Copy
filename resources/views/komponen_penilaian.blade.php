@extends('layouts.app')

@section('title','Komponen Penilaian')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --primary-light:#1a5276;
    --border:#e5e7eb;
    --danger:#dc2626;
    --warning:#d97706;
    --success:#16a34a;
    --text-muted:#6b7280;
}

.card{
    background:white;
    border-radius:16px;
    padding:28px;
    box-shadow:0 4px 20px rgba(0,0,0,0.06);
    border:1px solid var(--border);
}

.card-header{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:20px;
}

.card-header .icon{
    width:40px;
    height:40px;
    background:var(--primary);
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}

.card-header .icon svg{
    width:20px;
    height:20px;
    fill:white;
}

.card-header h3{
    margin:0;
    color:var(--primary);
    font-size:17px;
    font-weight:700;
}

.card-header p{
    margin:0;
    font-size:12px;
    color:var(--text-muted);
}

/* FORM TAMBAH */
.form-section{
    background:#f9fafb;
    border:1px solid var(--border);
    border-radius:12px;
    padding:16px 20px;
    margin-bottom:20px;
}

.form-section .form-label{
    font-size:12px;
    font-weight:600;
    color:var(--text-muted);
    margin-bottom:10px;
    display:block;
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:10px;
    margin-bottom:12px;
}

.input-group{
    display:flex;
    flex-direction:column;
    gap:4px;
}

.input-group label{
    font-size:11px;
    font-weight:600;
    color:var(--text-muted);
    text-transform:uppercase;
    letter-spacing:.4px;
}

input,
select{
    padding:9px 12px;
    border-radius:8px;
    border:1px solid var(--border);
    outline:none;
    font-size:13px;
    color:#111827;
    background:white;
    transition:border-color .2s;
}

input:focus,
select:focus{
    border-color:var(--primary);
}

input::placeholder{
    color:#9ca3af;
}

.form-footer{
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:10px;
}

.guru-info{
    display:flex;
    align-items:center;
    gap:6px;
    font-size:12px;
    color:var(--text-muted);
}

.guru-info svg{
    width:14px;
    height:14px;
    fill:var(--text-muted);
}

.guru-info span{
    font-weight:600;
    color:var(--primary);
}

/* BUTTONS */
.btn-primary{
    display:flex;
    align-items:center;
    gap:6px;
    background:var(--primary);
    color:white;
    border:none;
    padding:9px 18px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
    font-weight:600;
    transition:background .2s, transform .1s;
}

.btn-primary:hover{ background:var(--primary-light); }
.btn-primary:active{ transform:scale(0.97); }

.btn-primary svg{
    width:15px;
    height:15px;
    fill:white;
}

.btn-edit{
    display:inline-flex;
    align-items:center;
    gap:4px;
    background:#fef3c7;
    color:#92400e;
    border:1px solid #fde68a;
    padding:5px 10px;
    border-radius:6px;
    cursor:pointer;
    font-size:12px;
    font-weight:600;
    transition:.2s;
}

.btn-edit:hover{ background:#fde68a; }

.btn-delete{
    display:inline-flex;
    align-items:center;
    gap:4px;
    background:#fee2e2;
    color:#991b1b;
    border:1px solid #fecaca;
    padding:5px 10px;
    border-radius:6px;
    cursor:pointer;
    font-size:12px;
    font-weight:600;
    transition:.2s;
}

.btn-delete:hover{ background:#fecaca; }

.btn-edit svg,
.btn-delete svg{
    width:13px;
    height:13px;
    fill:currentColor;
}

/* TOP BAR */
.top-bar{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:12px;
    flex-wrap:wrap;
}

.search-box{
    display:flex;
    align-items:center;
    gap:8px;
    background:#f9fafb;
    border:1px solid var(--border);
    border-radius:10px;
    padding:7px 12px;
}

.search-box svg{
    width:15px;
    height:15px;
    fill:var(--text-muted);
    flex-shrink:0;
}

.search-box input{
    border:none;
    background:transparent;
    outline:none;
    font-size:13px;
    width:200px;
    padding:0;
}

.info-bar{
    font-size:12px;
    color:var(--text-muted);
    margin-bottom:10px;
}

.info-bar b{ color:var(--primary); }

/* TABLE */
.table-wrapper{
    border:1px solid var(--border);
    border-radius:10px;
    overflow:hidden;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

thead{
    background:var(--primary);
    color:white;
}

th{
    padding:11px 14px;
    font-weight:600;
    font-size:12px;
    letter-spacing:.3px;
    text-align:left;
    white-space:nowrap;
}

td{
    padding:11px 14px;
    border-bottom:1px solid #f3f4f6;
    color:#374151;
    vertical-align:middle;
}

tbody tr:last-child td{ border-bottom:none; }
tbody tr:hover td{ background:#f0f7ff; }

.actions{
    display:flex;
    gap:6px;
}

/* BADGE BOBOT */
.badge-bobot{
    display:inline-block;
    background:#e0f2fe;
    color:#0369a1;
    font-weight:700;
    padding:3px 10px;
    border-radius:20px;
    font-size:12px;
}

/* STATE */
.state-row td{
    padding:48px 0;
    text-align:center;
    color:var(--text-muted);
}

.state-row svg{
    display:block;
    margin:0 auto 10px;
    width:34px;
    height:34px;
    fill:#d1d5db;
}

.state-row p{ margin:0; font-size:13px; }

.spinner{
    width:26px;
    height:26px;
    border:3px solid #e5e7eb;
    border-top-color:var(--primary);
    border-radius:50%;
    animation:spin .7s linear infinite;
    margin:0 auto 10px;
}

@keyframes spin{ to{ transform:rotate(360deg); } }

/* MODAL */
.modal-overlay{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.4);
    z-index:1000;
    align-items:center;
    justify-content:center;
}

.modal-overlay.show{ display:flex; }

.modal{
    background:white;
    border-radius:16px;
    padding:28px;
    width:100%;
    max-width:440px;
    box-shadow:0 20px 60px rgba(0,0,0,0.15);
    animation:fadeUp .2s ease;
}

.modal-title{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:20px;
}

.modal-title .icon{
    width:36px;
    height:36px;
    background:var(--primary);
    border-radius:8px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.modal-title .icon svg{
    width:18px;
    height:18px;
    fill:white;
}

.modal-title h4{
    margin:0;
    color:var(--primary);
    font-size:16px;
    font-weight:700;
}

.modal .input-group{
    margin-bottom:12px;
}

.modal .input-group input,
.modal .input-group select{
    width:100%;
    box-sizing:border-box;
}

.modal-footer{
    display:flex;
    gap:10px;
    justify-content:flex-end;
    margin-top:20px;
    padding-top:16px;
    border-top:1px solid var(--border);
}

.btn-success{
    display:flex;
    align-items:center;
    gap:6px;
    background:var(--success);
    color:white;
    border:none;
    padding:9px 18px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
    font-weight:600;
    transition:.2s;
}

.btn-success:hover{ background:#15803d; }

.btn-success svg{
    width:15px;
    height:15px;
    fill:white;
}

.btn-cancel{
    background:#f3f4f6;
    color:#374151;
    border:1px solid var(--border);
    padding:9px 18px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
    font-weight:600;
    transition:.2s;
}

.btn-cancel:hover{ background:#e5e7eb; }

@keyframes fadeUp{
    from{ opacity:0; transform:translateY(8px); }
    to{ opacity:1; transform:translateY(0); }
}
</style>


<div class="card">

    {{-- HEADER --}}
    <div class="card-header">
        <div class="icon">
            <svg viewBox="0 0 24 24"><path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5C3.89 4 3 4.9 3 6v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
        </div>
        <div>
            <h3>Komponen Penilaian</h3>
            <p>Kelola komponen dan bobot penilaian per mata pelajaran</p>
        </div>
    </div>

    {{-- FORM TAMBAH --}}
    <div class="form-section">
        <span class="form-label">Tambah Komponen Baru</span>

        <div class="form-grid">
            <div class="input-group">
                <label>Mata Pelajaran</label>
                <select id="id_mapel"></select>
            </div>

            <div class="input-group">
                <label>Nama Komponen</label>
                <input id="nama_komponen" placeholder="cth: Ulangan Harian">
            </div>

            <div class="input-group">
                <label>Bobot (%)</label>
                <input id="bobot" placeholder="cth: 30" type="number" min="1" max="100">
            </div>
        </div>

        <div class="form-footer">
            <div class="guru-info">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                Guru: <span id="nama-guru-info">-</span>
            </div>

            <button class="btn-primary" id="btnTambah">
                <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Tambah Komponen
            </button>
        </div>
    </div>

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="search-box">
            <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
            <input id="search" placeholder="Cari nama komponen...">
        </div>
    </div>

    <div class="info-bar" id="info-bar"></div>

    {{-- TABLE --}}
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kelas — Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Nama Komponen</th>
                    <th>Bobot</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="data"></tbody>
        </table>
    </div>

</div>


{{-- MODAL EDIT --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal">

        <div class="modal-title">
            <div class="icon">
                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
            </div>
            <h4>Edit Komponen Penilaian</h4>
        </div>

        <div class="input-group">
            <label>Mata Pelajaran</label>
            <select id="edit_mapel"></select>
        </div>

        <div class="input-group">
            <label>Nama Komponen</label>
            <input id="edit_nama" placeholder="Nama Komponen">
        </div>

        <div class="input-group">
            <label>Bobot (%)</label>
            <input id="edit_bobot" placeholder="Bobot (%)" type="number" min="1" max="100">
        </div>

        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Batal</button>
            <button class="btn-success" id="btnSimpanEdit">
                <svg viewBox="0 0 24 24"><path d="M17 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                Simpan Perubahan
            </button>
        </div>

    </div>
</div>

@endsection


@section('script')
<script>

let id_guru;
let namaGuru;
let allData  = [];
let dataMapel = [];
let editId   = null;


// ================= INIT =================

document.addEventListener('DOMContentLoaded', init);

async function init(){

    const user = JSON.parse(localStorage.getItem('user'));

    if(!user){
        location.href = '/login';
        return;
    }

    id_guru  = user.id;
    namaGuru = user.nama ?? user.name ?? '-';

    document.getElementById('nama-guru-info').textContent = namaGuru;

    await loadMapel();
    await loadData();

    document.getElementById('search')
        .addEventListener('input', filterData);

    document.getElementById('btnTambah')
        .addEventListener('click', tambah);

    document.getElementById('btnSimpanEdit')
        .addEventListener('click', simpanEdit);

    document.getElementById('modalEdit')
        .addEventListener('click', function(e){
            if(e.target === this) closeModal();
        });
}


// ================= LOAD MAPEL =================

async function loadMapel(){

    const res  = await fetch('/api/jadwal-guru-mapel/' + id_guru);
    const json = await res.json();

    dataMapel = json.data;

    let html = '';

    dataMapel.forEach(m => {
        const kelas = m.kelas?.nama_kelas  ?? '-';
        const mapel = m.mapel?.nama_mapel  ?? '-';
        html += `<option value="${m.id_mapel}">${kelas} — ${mapel}</option>`;
    });

    document.getElementById('id_mapel').innerHTML   = html;
    document.getElementById('edit_mapel').innerHTML = html;
}


// ================= LOAD DATA =================

async function loadData(){

    showLoading();

    const res  = await fetch('/api/komponen-penilaian-guru/' + id_guru);
    const json = await res.json();

    allData = json.data;

    render(allData);
}


// ================= FILTER =================

function filterData(){

    const key = document.getElementById('search').value.toLowerCase();

    const filtered = allData.filter(d =>
        d.nama_komponen.toLowerCase().includes(key)
    );

    render(filtered);
}


// ================= RENDER =================

function render(data){

    updateInfoBar(data.length);

    if(data.length === 0){
        showEmpty();
        return;
    }

    let html = '';

    data.forEach((d, i) => {

        // ambil nama kelas & mapel dari dataMapel
        const mapelInfo = dataMapel.find(m => m.id_mapel == d.id_mapel);
        const namaKelas = mapelInfo?.kelas?.nama_kelas ?? '-';
        const namaMapel = mapelInfo?.mapel?.nama_mapel ?? '-';

        // nama guru dari relasi atau fallback ke login
        const guru = d.guru?.nama_guru ?? namaGuru;

        html += `
        <tr>
            <td style="color:#9ca3af;font-size:12px;">${i + 1}</td>
            <td>${namaKelas} &mdash; ${namaMapel}</td>
            <td>${guru}</td>
            <td>${d.nama_komponen}</td>
            <td><span class="badge-bobot">${d.bobot}%</span></td>
            <td>
                <div class="actions">
                    <button class="btn-edit" onclick="bukaEdit(${d.id_komponen})">
                        <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        Edit
                    </button>
                    <button class="btn-delete" onclick="hapus(${d.id_komponen})">
                        <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.13-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                        Hapus
                    </button>
                </div>
            </td>
        </tr>`;
    });

    document.getElementById('data').innerHTML = html;
}


// ================= TAMBAH =================

async function tambah(){

    const id_mapel_val    = document.getElementById('id_mapel').value;
    const nama_komponen_val = document.getElementById('nama_komponen').value.trim();
    const bobot_val       = document.getElementById('bobot').value;

    if(!nama_komponen_val || !bobot_val){
        alert('Lengkapi nama komponen dan bobot terlebih dahulu.');
        return;
    }

    const res  = await fetch('/api/komponen-penilaian', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id_mapel:       id_mapel_val,
            id_guru:        id_guru,
            nama_komponen:  nama_komponen_val,
            bobot:          bobot_val
        })
    });

    const json = await res.json();

    if(json.success){
        document.getElementById('nama_komponen').value = '';
        document.getElementById('bobot').value = '';
        await loadData();
    } else {
        alert(json.message || 'Gagal menambah komponen.');
    }
}


// ================= BUKA MODAL EDIT =================

function bukaEdit(id){

    const item = allData.find(d => d.id_komponen == id);
    if(!item) return;

    editId = id;

    document.getElementById('edit_mapel').value = item.id_mapel;
    document.getElementById('edit_nama').value  = item.nama_komponen;
    document.getElementById('edit_bobot').value = item.bobot;

    document.getElementById('modalEdit').classList.add('show');
}


// ================= SIMPAN EDIT =================

async function simpanEdit(){

    const id_mapel_val = document.getElementById('edit_mapel').value;
    const nama         = document.getElementById('edit_nama').value.trim();
    const bobot_val    = document.getElementById('edit_bobot').value;

    if(!nama || !bobot_val){
        alert('Isi semua field terlebih dahulu.');
        return;
    }

    const res  = await fetch(`/api/komponen-penilaian/${editId}`, {
        method:  'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id_mapel:      id_mapel_val,
            nama_komponen: nama,
            bobot:         bobot_val
        })
    });

    const json = await res.json();

    if(json.success){
        closeModal();
        await loadData();
    } else {
        alert(json.message || 'Gagal menyimpan perubahan.');
    }
}


// ================= HAPUS =================

function hapus(id){

    if(!confirm('Yakin ingin menghapus komponen ini?')) return;

    fetch('/api/komponen-penilaian/' + id, { method: 'DELETE' })
        .then(() => loadData());
}


// ================= CLOSE MODAL =================

function closeModal(){
    document.getElementById('modalEdit').classList.remove('show');
    editId = null;
}


// ================= HELPERS =================

function showLoading(){
    document.getElementById('data').innerHTML = `
        <tr class="state-row">
            <td colspan="6">
                <div class="spinner"></div>
                <p>Memuat data...</p>
            </td>
        </tr>`;
}

function showEmpty(){
    document.getElementById('data').innerHTML = `
        <tr class="state-row">
            <td colspan="6">
                <svg viewBox="0 0 24 24"><path d="M20 6h-2.18c.07-.44.18-.88.18-1.35C18 2.53 15.87.5 13.35.5c-1.3 0-2.43.52-3.28 1.33L9 2.9 7.93 1.83C7.08 1.02 5.95.5 4.65.5 2.13.5 0 2.53 0 4.65c0 .47.11.91.18 1.35H0v2h20V6zM3.12 6c-.06-.23-.12-.45-.12-.69C3 3.64 3.73 2.5 4.65 2.5c.54 0 1.03.23 1.4.59L8 5l2.06-2.06c.37-.36.86-.59 1.4-.59C12.27 2.5 13 3.64 13 4.97c0 .24-.06.46-.12.69H3.12zM0 8v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8H0zm9 9.5L5 13l1.41-1.41L9 14.67l6.59-6.58L17 9.5l-8 8z"/></svg>
                <p>Belum ada komponen penilaian</p>
            </td>
        </tr>`;
}

function updateInfoBar(count){
    const total = allData.length;
    document.getElementById('info-bar').innerHTML =
        count === total
            ? `Total <b>${total}</b> komponen penilaian`
            : `Menampilkan <b>${count}</b> dari <b>${total}</b> komponen`;
}

</script>
@endsection