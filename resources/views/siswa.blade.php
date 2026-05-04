@extends('layouts.app')

@section('title','Data Siswa')

@section('content')

<style>
:root{
    --primary:#0a3d62;      /* biru laut */
    --primary-soft:#e6f2ff;
    --border:#e5e7eb;
}

/* CARD */
.card{
    background:white;
    border-radius:14px;
    padding:25px;
    box-shadow:0 8px 25px rgba(0,0,0,0.05);
}

/* TITLE */
.card h3{
    margin-bottom:20px;
    color:var(--primary);
}

/* FORM GRID */
.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
    margin-bottom:20px;
}

/* INPUT */
input, select{
    padding:10px;
    border-radius:8px;
    border:1px solid var(--border);
    outline:none;
}

input:focus, select:focus{
    border-color:var(--primary);
}

/* BUTTON */
.btn-primary{
    background:var(--primary);
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:8px;
    cursor:pointer;
}

.btn-warning{
    background:#f59e0b;
    color:white;
    border:none;
    padding:6px 10px;
    border-radius:6px;
    cursor:pointer;
}

.btn-danger{
    background:#ef4444;
    color:white;
    border:none;
    padding:6px 10px;
    border-radius:6px;
    cursor:pointer;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
    font-size:13px;
}

thead{
    background:var(--primary);
    color:white;
}

th, td{
    padding:10px;
    border-bottom:1px solid var(--border);
}

tr:hover{
    background:#f9fafb;
}

/* SEARCH */
.search-box{
    margin-bottom:15px;
}

/* MODAL */
.modal{
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.4);
    display:none;
    justify-content:center;
    align-items:center;
}

.modal-content{
    background:white;
    padding:20px;
    border-radius:12px;
    width:400px;
}
</style>

<div class="card">

<h3>📘 Data Siswa (Kelas Saya)</h3>

<!-- SEARCH -->
<input id="search" class="search-box" placeholder="🔍 Cari siswa...">

<!-- FORM -->
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
</div>

<button id="btnTambah" class="btn-primary">+ Tambah</button>

<!-- TABLE -->
<table>
<thead>
<tr>
<th>Nama</th>
<th>NIS</th>
<th>JK</th>
<th>Tempat Lahir</th>
<th>Tanggal Lahir</th>
<th>Alamat</th>
<th>Nama Ayah</th>
<th>Nama Ibu</th>
<th>No HP</th>
<th>Kelas</th>
<th>Aksi</th>
</tr>
</thead>
<tbody id="data"></tbody>
</table>

</div>

<!-- MODAL EDIT -->
<div id="modalEdit" class="modal">
<div class="modal-content">

<h3>Edit Siswa</h3>

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
<input id="edit_ayah" placeholder="Ayah">
<input id="edit_ibu" placeholder="Ibu">
<input id="edit_hp" placeholder="No HP">

<br><br>

<button onclick="updateData()" class="btn-primary">Simpan</button>
<button onclick="tutupModal()" class="btn-danger">Batal</button>

</div>
</div>

@endsection


@section('script')
<script>

let id_kelas_saya = null;
let semuaData = [];

// INIT
document.addEventListener('DOMContentLoaded', init);

async function init(){

    const user = JSON.parse(localStorage.getItem('user'));
    if(!user){
        location.href='/login';
        return;
    }

    await loadKelasSaya(user.id);
    loadData(user.id);

    document.getElementById('btnTambah').onclick = () => tambah(user.id);
    document.getElementById('search').addEventListener('input', filterData);
}

// LOAD KELAS
async function loadKelasSaya(id){
    const res = await fetch('/api/kelas-saya/'+id);
    const data = await res.json();
    if(data.data.length){
        id_kelas_saya = data.data[0].id_kelas;
    }
}

// LOAD DATA
async function loadData(id){
    const res = await fetch('/api/siswa-kelas-saya/'+id);
    const data = await res.json();

    semuaData = data.data;
    render(semuaData, id);
}

// RENDER
function render(data, id){
    let html = '';

    data.forEach(s=>{
        html += `
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
            <td>${s.kelas?.nama_kelas ?? '-'}</td>
            <td>
                <button class="btn-warning" onclick="edit(${s.id_siswa})">Edit</button>
                <button class="btn-danger" onclick="hapus(${s.id_siswa}, ${id})">Hapus</button>
            </td>
        </tr>`;
    });

    document.getElementById('data').innerHTML = html;
}

// SEARCH
function filterData(){
    const keyword = document.getElementById('search').value.toLowerCase();

    const hasil = semuaData.filter(s =>
        s.nama_siswa.toLowerCase().includes(keyword) ||
        s.nis.toLowerCase().includes(keyword)
    );

    render(hasil);
}

// TAMBAH
async function tambah(id){
    const res = await fetch('/api/siswa',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({
            nama_siswa: nama_siswa.value,
            nis: nis.value,
            jenis_kelamin: jenis_kelamin.value,
            tempat_lahir: tempat_lahir.value,
            tanggal_lahir: tanggal_lahir.value,
            alamat: alamat.value,
            nama_ayah: nama_ayah.value,
            nama_ibu: nama_ibu.value,
            no_telepon: no_telepon.value,
            id_kelas: id_kelas_saya
        })
    });

    const data = await res.json();
    if(data.success){
        loadData(id);
    }
}

// EDIT
function edit(id){
    const s = semuaData.find(x=>x.id_siswa == id);

    edit_id.value = s.id_siswa;
    edit_nama.value = s.nama_siswa;
    edit_nis.value = s.nis;
    edit_jk.value = s.jenis_kelamin;
    edit_tempat.value = s.tempat_lahir;
    edit_tanggal.value = s.tanggal_lahir;
    edit_alamat.value = s.alamat;
    edit_ayah.value = s.nama_ayah;
    edit_ibu.value = s.nama_ibu;
    edit_hp.value = s.no_telepon;

    document.getElementById('modalEdit').style.display='flex';
}

// UPDATE
async function updateData(){

    const id = edit_id.value;

    await fetch('/api/siswa/'+id,{
        method:'PUT',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({
            nama_siswa: edit_nama.value,
            nis: edit_nis.value,
            jenis_kelamin: edit_jk.value,
            tempat_lahir: edit_tempat.value,
            tanggal_lahir: edit_tanggal.value,
            alamat: edit_alamat.value,
            nama_ayah: edit_ayah.value,
            nama_ibu: edit_ibu.value,
            no_telepon: edit_hp.value,
            id_kelas: id_kelas_saya
        })
    });

    tutupModal();
    init();
}

// HAPUS
function hapus(id, id_guru){
    if(confirm('Yakin?')){
        fetch('/api/siswa/'+id,{method:'DELETE'})
        .then(()=>loadData(id_guru));
    }
}

// MODAL
function tutupModal(){
    document.getElementById('modalEdit').style.display='none';
}

</script>
@endsection