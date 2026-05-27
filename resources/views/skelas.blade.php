@extends('layouts.app')

@section('title','Data Kelas')

@section('content')

<div class="card">

<h3 class="title">Data Kelas</h3>

<!-- SEARCH -->
<div class="toolbar">
    <input id="search" placeholder="Cari kelas / wali...">
</div>

<!-- FORM -->
<div class="form-grid">
    <input id="nama_kelas" type="number" placeholder="Nama Kelas">
    <input id="total_siswa" type="number" placeholder="Total Siswa">

    <select id="id_guru"></select>
    <select id="id_tahun"></select>
</div>

<button class="btn-primary" onclick="tambah()">Tambah</button>

<!-- TABLE -->
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Kelas</th>
<th>Wali</th>
<th>Tahun</th>
<th>Total</th>
<th>Aksi</th>
</tr>
</thead>
<tbody id="data"></tbody>
</table>
</div>

</div>

<!-- MODAL -->
<div id="modal" class="modal">
<div class="modal-content">

<h3>Edit Kelas</h3>

<div class="form-grid">
    <input id="edit_nama" type="number">
    <input id="edit_total" type="number">

    <select id="edit_guru"></select>
    <select id="edit_tahun"></select>
</div>

<div class="modal-action">
    <button class="btn-primary" onclick="update()">Update</button>
    <button class="btn-danger" onclick="tutup()">Batal</button>
</div>

</div>
</div>

@endsection


@section('script')

<style>

/* TITLE */
.title{
    margin-bottom:15px;
    color:#0a3d62;
}

/* TOOLBAR */
.toolbar{
    margin-bottom:10px;
}

/* INPUT */
input, select{
    padding:8px 10px;
    border:1px solid #ddd;
    border-radius:6px;
    width:100%;
}

/* FORM GRID */
.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:8px;
    margin-bottom:10px;
}

/* BUTTON */
button{
    padding:8px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    color:white;
}

.btn-primary{background:#0a3d62;}
.btn-danger{background:#dc2626;}
.btn-edit{background:#f59e0b;}
.btn-delete{background:#dc2626;}

button:hover{opacity:0.9;}

/* TABLE */
.table-wrap{
    overflow:auto;
    margin-top:10px;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

th, td{
    border:1px solid #eee;
    padding:10px;
    text-align:center;
}

th{
    background:#0a3d62;
    color:white;
}

tbody tr:hover{
    background:#f9fafb;
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
    justify-content:center;
    align-items:center;
}

.modal-content{
    background:white;
    padding:20px;
    border-radius:12px;
    width:400px;
}

/* MODAL ACTION */
.modal-action{
    margin-top:10px;
    display:flex;
    gap:10px;
}

</style>


<script>

let allData = [];
let editId = null;

// ================= INIT =================
document.addEventListener('DOMContentLoaded', async ()=>{
    await loadGuru();
    await loadTahun();
    await loadData();

    search.oninput = filterData;
});


// ================= LOAD =================
async function loadGuru(){
    const res = await fetch('/api/pegawai/guru-kelas');
    const data = await res.json();

    let html = '<option value="">Pilih Wali</option>';

    data.data.forEach(g=>{
        html += `<option value="${g.id_guru}">${g.nama_guru}</option>`;
    });

    id_guru.innerHTML = html;
    edit_guru.innerHTML = html;
}

async function loadTahun(){
    const res = await fetch('/api/tahun-ajaran');
    const data = await res.json();

    let html = '';

    data.data.forEach(t=>{
        html += `<option value="${t.id_tahun_ajaran}">
            ${t.periode} - ${t.semester}
        </option>`;
    });

    id_tahun.innerHTML = html;
    edit_tahun.innerHTML = html;
}

async function loadData(){
    const res = await fetch('/api/kelas');
    const data = await res.json();

    allData = data.data || [];
    render(allData);
}


// ================= FILTER =================
function filterData(){

    let keyword = search.value.toLowerCase();

    let filtered = allData.filter(k => {

        let nama = (k.nama_kelas+'').toLowerCase();
        let wali = (k.pegawai?.nama_guru || '').toLowerCase();

        return nama.includes(keyword) || wali.includes(keyword);
    });

    render(filtered);
}


// ================= RENDER =================
function render(data){

    if(data.length === 0){
        dataEl.innerHTML = `<tr><td colspan="5">Tidak ada data</td></tr>`;
        return;
    }

    let html = '';

    data.forEach(k => {

        html += `
        <tr>
            <td><b>${k.nama_kelas}</b></td>
            <td>${k.pegawai?.nama_guru ?? '-'}</td>
            <td>${k.tahun_ajaran?.periode ?? '-'}</td>
            <td>${k.total_siswa}</td>
            <td>
                <button class="btn-edit" onclick="edit(${k.id_kelas})">Edit</button>
                <button class="btn-delete" onclick="hapus(${k.id_kelas})">Hapus</button>
            </td>
        </tr>`;
    });

    dataEl.innerHTML = html;
}


// ================= TAMBAH =================
async function tambah(){

    const res = await fetch('/api/kelas', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            nama_kelas: nama_kelas.value,
            total_siswa: total_siswa.value,
            id_guru: id_guru.value,
            id_tahun_ajaran: id_tahun.value
        })
    });

    const data = await res.json();

    if(data.success){
        alert('Berhasil tambah');
        loadData();
    }else{
        alert(data.message);
    }
}


// ================= EDIT =================
function edit(id){

    editId = id;

    let k = allData.find(x => x.id_kelas == id);

    edit_nama.value = k.nama_kelas;
    edit_total.value = k.total_siswa;
    edit_guru.value = k.id_guru;
    edit_tahun.value = k.id_tahun_ajaran;

    modal.style.display = 'flex';
}


// ================= UPDATE =================
async function update(){

    const res = await fetch('/api/kelas/' + editId, {
        method:'PUT',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            nama_kelas: edit_nama.value,
            total_siswa: edit_total.value,
            id_guru: edit_guru.value,
            id_tahun_ajaran: edit_tahun.value
        })
    });

    const data = await res.json();

    if(data.success){
        alert('Berhasil update');
        tutup();
        loadData();
    }else{
        alert(data.message);
    }
}


// ================= HAPUS =================
function hapus(id){
    if(confirm('Yakin hapus?')){
        fetch('/api/kelas/'+id,{method:'DELETE'})
        .then(()=>loadData());
    }
}


// ================= TUTUP =================
function tutup(){
    modal.style.display='none';
}

const dataEl = document.getElementById('data');

</script>

@endsection