@extends('layouts.app')

@section('title','Data Siswa')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --primary-soft:#e6f2ff;
    --border:#e5e7eb;
    --text:#0f172a;
    --muted:#64748b;
    --bg:#f8fafc;
}

/* PAGE */
body{
    background:var(--bg);
}

/* CARD */
.card{
    background:white;
    border-radius:20px;
    padding:22px;
    box-shadow:0 8px 30px rgba(15,23,42,0.06);
}

/* TITLE */
.card h3{
    margin-bottom:18px;
    color:var(--primary);
    font-size:24px;
    font-weight:700;
}

/* SEARCH */
.search-box{
    width:260px;
    margin-bottom:16px;
}

/* FORM */
.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:12px;
    margin-bottom:18px;
}

/* INPUT */
input,
select{
    width:100%;
    padding:10px 12px;
    border-radius:12px;
    border:1px solid var(--border);
    outline:none;
    font-size:13px;
    color:var(--text);
    background:white;
    transition:0.25s;
    box-sizing:border-box;
}

input:focus,
select:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 3px rgba(10,61,98,0.08);
}

/* BUTTON */
.btn-primary{
    background:var(--primary);
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:10px;
    cursor:pointer;
    font-size:13px;
    font-weight:600;
    transition:0.25s;
}

.btn-primary:hover{
    transform:translateY(-1px);
    opacity:0.95;
}

.btn-warning{
    background:#f59e0b;
    color:white;
    border:none;
    padding:7px 12px;
    border-radius:8px;
    cursor:pointer;
    font-size:12px;
    font-weight:600;
}

.btn-danger{
    background:#ef4444;
    color:white;
    border:none;
    padding:7px 12px;
    border-radius:8px;
    cursor:pointer;
    font-size:12px;
    font-weight:600;
}

/* TABLE */
.table-wrapper{
    overflow-x:auto;
    margin-top:18px;
    border-radius:16px;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:12px;
    background:white;
    overflow:hidden;
}

thead{
    background:var(--primary);
    color:white;
}

th{
    padding:14px 10px;
    text-align:left;
    font-size:12px;
    font-weight:600;
    white-space:nowrap;
}

td{
    padding:12px 10px;
    border-bottom:1px solid #f1f5f9;
    color:#334155;
    white-space:nowrap;
}

tbody tr{
    transition:0.2s;
}

tbody tr:hover{
    background:#f8fafc;
}

/* MODAL */
.modal{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(15,23,42,0.45);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
    padding:20px;
}

.modal-content{
    background:white;
    padding:24px;
    border-radius:20px;
    width:100%;
    max-width:450px;
    box-shadow:0 10px 40px rgba(0,0,0,0.15);
}

.modal-content h3{
    margin-bottom:16px;
    color:var(--primary);
}

.modal-content input,
.modal-content select{
    margin-bottom:12px;
}

/* NOTIF */
.notif{
    position:fixed;
    top:20px;
    right:20px;
    background:#16a34a;
    color:white;
    padding:12px 18px;
    border-radius:12px;
    font-size:13px;
    box-shadow:0 8px 20px rgba(0,0,0,0.12);
    opacity:0;
    transform:translateY(-20px);
    transition:0.3s;
    z-index:9999;
}

.notif.show{
    opacity:1;
    transform:translateY(0);
}

.notif.error{
    background:#ef4444;
}

/* RESPONSIVE */
@media(max-width:768px){

    .card{
        padding:16px;
    }

    .card h3{
        font-size:20px;
    }

    .search-box{
        width:100%;
    }

    .form-grid{
        grid-template-columns:1fr;
    }

    table{
        font-size:11px;
    }

    th,
    td{
        padding:10px 8px;
    }
}
</style>

<!-- NOTIF -->
<div id="notif" class="notif"></div>

<div class="card">

    <h3>Data Siswa (Kelas Saya)</h3>

    <input id="search" class="search-box" placeholder="Cari siswa...">

    <div class="form-grid">
        <input id="nama_siswa" placeholder="Nama Siswa">
        <input id="nis" placeholder="NIS">

        <select id="jenis_kelamin">
            <option value="">Jenis Kelamin</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>

        <input id="tempat_lahir" placeholder="Tempat Lahir">
        <input id="tanggal_lahir" type="date">
        <input id="alamat" placeholder="Alamat">
        <input id="nama_ayah" placeholder="Nama Ayah">
        <input id="nama_ibu" placeholder="Nama Ibu">
        <input id="no_telepon" placeholder="No Telepon">

        <select id="penghasilan">
            <option value="">Penghasilan</option>
            <option value="< 1.000.000">< 1.000.000</option>
            <option value="1.000.000 - 2.500.000">1.000.000 - 2.500.000</option>
            <option value="2.500.000 - 5.000.000">2.500.000 - 5.000.000</option>
            <option value="> 5.000.000">> 5.000.000</option>
        </select>
    </div>

    <button id="btnTambah" class="btn-primary">
        + Tambah Data Siswa
    </button>

    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th>Nama</th>
                <th>NIS</th>
                <th>JK</th>
                <th>Tempat</th>
                <th>Tanggal</th>
                <th>Alamat</th>
                <th>Ayah</th>
                <th>Ibu</th>
                <th>HP</th>
                <th>Penghasilan</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
            </thead>

            <tbody id="data"></tbody>
        </table>
    </div>

</div>

<!-- MODAL -->
<div id="modalEdit" class="modal">

    <div class="modal-content">

        <h3>Edit Data Siswa</h3>

        <input type="hidden" id="edit_id">

        <input id="edit_nama" placeholder="Nama">
        <input id="edit_nis" placeholder="NIS">

        <select id="edit_jk">
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>

        <input id="edit_tempat" placeholder="Tempat Lahir">
        <input id="edit_tanggal" type="date">
        <input id="edit_alamat" placeholder="Alamat">
        <input id="edit_ayah" placeholder="Nama Ayah">
        <input id="edit_ibu" placeholder="Nama Ibu">
        <input id="edit_hp" placeholder="No Telepon">

        <select id="edit_penghasilan">
            <option value="">Penghasilan</option>
            <option value="< 1.000.000">< 1.000.000</option>
            <option value="1.000.000 - 2.500.000">1.000.000 - 2.500.000</option>
            <option value="2.500.000 - 5.000.000">2.500.000 - 5.000.000</option>
            <option value="> 5.000.000">> 5.000.000</option>
        </select>

        <div style="display:flex; gap:10px; margin-top:10px;">
            <button onclick="updateData()" class="btn-primary">
                Simpan
            </button>

            <button onclick="tutupModal()" class="btn-danger">
                Batal
            </button>
        </div>

    </div>

</div>

@endsection

@section('script')
<script>

let id_kelas_saya = null;
let semuaData = [];

/* NOTIF */
function showNotif(text, type='success'){
    const n = document.getElementById('notif');

    n.innerText = text;
    n.className = 'notif show';

    if(type === 'error'){
        n.classList.add('error');
    }

    setTimeout(()=>{
        n.classList.remove('show');
    },2500);
}

/* INIT */
document.addEventListener('DOMContentLoaded', init);

async function init(){

    const user = JSON.parse(localStorage.getItem('user'));

    if(!user){
        return location.href='/login';
    }

    await loadKelasSaya(user.id);

    loadData(user.id);

    btnTambah.onclick = ()=> tambah(user.id);

    search.addEventListener('input', filterData);
}

/* LOAD KELAS */
async function loadKelasSaya(id){

    const res = await fetch('/api/kelas-saya/' + id);

    const d = await res.json();

    if(d.data.length){
        id_kelas_saya = d.data[0].id_kelas;
    }
}

/* LOAD DATA */
async function loadData(id){

    const res = await fetch('/api/siswa-kelas-saya/' + id);

    const d = await res.json();

    semuaData = d.data;

    render(semuaData, id);
}

/* RENDER */
function render(data, id){

    dataEl = document.getElementById('data');

    dataEl.innerHTML = data.map(s => `
        <tr>
            <td>${s.nama_siswa}</td>
            <td>${s.nis}</td>
            <td>${s.jenis_kelamin}</td>
            <td>${s.tempat_lahir ?? '-'}</td>
            <td>${s.tanggal_lahir ?? '-'}</td>
            <td>${s.alamat ?? '-'}</td>
            <td>${s.nama_ayah ?? '-'}</td>
            <td>${s.nama_ibu ?? '-'}</td>
            <td>${s.no_telepon ?? '-'}</td>
            <td>${s.penghasilan ?? '-'}</td>
            <td>${s.kelas?.nama_kelas ?? '-'}</td>

            <td style="display:flex; gap:6px;">
                <button class="btn-warning" onclick="edit(${s.id_siswa})">
                    Edit
                </button>

                <button class="btn-danger" onclick="hapus(${s.id_siswa},${id})">
                    Hapus
                </button>
            </td>
        </tr>
    `).join('');
}

/* SEARCH */
function filterData(){

    const k = search.value.toLowerCase();

    render(
        semuaData.filter(s =>
            s.nama_siswa.toLowerCase().includes(k) ||
            s.nis.toLowerCase().includes(k)
        )
    );
}

/* TAMBAH */
async function tambah(id){

    const res = await fetch('/api/siswa',{

        method:'POST',

        headers:{
            'Content-Type':'application/json'
        },

        body:JSON.stringify({

            nama_siswa:nama_siswa.value,
            nis:nis.value,
            jenis_kelamin:jenis_kelamin.value,
            tempat_lahir:tempat_lahir.value,
            tanggal_lahir:tanggal_lahir.value,
            alamat:alamat.value,
            nama_ayah:nama_ayah.value,
            nama_ibu:nama_ibu.value,
            no_telepon:no_telepon.value,
            penghasilan:penghasilan.value,
            id_kelas:id_kelas_saya
        })
    });

    const d = await res.json();

    if(d.success){

        showNotif('Data berhasil ditambahkan');

        loadData(id);

    }else{

        showNotif('Gagal tambah data','error');
    }
}

/* EDIT */
function edit(id){

    const s = semuaData.find(x => x.id_siswa == id);

    edit_id.value = id;
    edit_nama.value = s.nama_siswa;
    edit_nis.value = s.nis;
    edit_jk.value = s.jenis_kelamin;
    edit_tempat.value = s.tempat_lahir;
    edit_tanggal.value = s.tanggal_lahir;
    edit_alamat.value = s.alamat;
    edit_ayah.value = s.nama_ayah;
    edit_ibu.value = s.nama_ibu;
    edit_hp.value = s.no_telepon;
    edit_penghasilan.value = s.penghasilan;

    modalEdit.style.display = 'flex';
}

/* UPDATE */
async function updateData(){

    const id = edit_id.value;

    const res = await fetch('/api/siswa/' + id,{

        method:'PUT',

        headers:{
            'Content-Type':'application/json'
        },

        body:JSON.stringify({

            nama_siswa:edit_nama.value,
            nis:edit_nis.value,
            jenis_kelamin:edit_jk.value,
            tempat_lahir:edit_tempat.value,
            tanggal_lahir:edit_tanggal.value,
            alamat:edit_alamat.value,
            nama_ayah:edit_ayah.value,
            nama_ibu:edit_ibu.value,
            no_telepon:edit_hp.value,
            penghasilan:edit_penghasilan.value,
            id_kelas:id_kelas_saya
        })
    });

    const d = await res.json();

    if(d.success){

        showNotif('Data berhasil diupdate');

    }else{

        showNotif('Gagal update data','error');
    }

    tutupModal();

    init();
}

/* DELETE */
function hapus(id,id_guru){

    if(confirm('Yakin hapus data siswa ini?')){

        fetch('/api/siswa/' + id,{

            method:'DELETE'

        })
        .then(res => res.json())
        .then(d => {

            if(d.success){

                showNotif('Data berhasil dihapus');

                loadData(id_guru);

            }else{

                showNotif('Gagal hapus data','error');
            }
        });
    }
}

/* TUTUP MODAL */
function tutupModal(){

    modalEdit.style.display = 'none';
}

</script>
@endsection