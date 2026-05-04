@extends('layouts.app')

@section('title','Data Pegawai')

@section('content')

<div class="card">

<h3 class="title">👨‍🏫 Data Pegawai</h3>

<!-- SEARCH -->
<div class="toolbar">
    <input type="text" id="search" placeholder="🔍 Cari nama / NIP / jabatan...">
</div>

<!-- TABLE -->
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Nama</th>
<th>NIP</th>
<th>JK</th>
<th>TTL</th>
<th>Kontak</th>
<th>Jabatan</th>
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

<h3>Edit Pegawai</h3>

<div class="form-grid">
    <input id="edit_nama" placeholder="Nama">
    <input id="edit_nip" placeholder="NIP">

    <select id="edit_jk">
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
    </select>

    <input id="edit_tempat" placeholder="Tempat Lahir">
    <input id="edit_tanggal" type="date">

    <input id="edit_alamat" placeholder="Alamat">
    <input id="edit_telp" placeholder="No Telepon">

    <input id="edit_email" placeholder="Email">
    <input id="edit_masuk" type="date">
</div>

<div class="jabatan-box" id="edit_jabatan"></div>

<div class="modal-action">
    <button id="btnUpdate" class="btn-primary">Update</button>
    <button id="btnTutup" class="btn-danger">Batal</button>
</div>

</div>
</div>

@endsection


@section('script')

<style>

.title{ margin-bottom:15px; color:#0a3d62; }

.toolbar{ margin-bottom:10px; }

input{
    padding:8px 10px;
    border:1px solid #ddd;
    border-radius:6px;
    width:100%;
}

/* TABLE */
.table-wrap{ overflow:auto; }

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

/* ROLE */
.role{
    background:#dbeafe;
    color:#1e40af;
    padding:3px 8px;
    border-radius:10px;
    font-size:12px;
    margin:2px;
    display:inline-block;
}

/* BUTTON */
button{
    padding:6px 10px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    color:white;
}

.btn-edit{background:#2563eb;}
.btn-delete{background:#dc2626;}
.btn-primary{background:#0a3d62;}
.btn-danger{background:#dc2626;}

button:hover{opacity:0.9;}

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
    width:500px;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:8px;
}

.jabatan-box{
    margin-top:10px;
    border:1px solid #eee;
    padding:10px;
    border-radius:8px;
}

.modal-action{
    margin-top:10px;
    display:flex;
    gap:10px;
}

</style>


<script>

let allData = [];
let editId = null;
let jabatanList = [];

// ================= INIT =================
document.addEventListener('DOMContentLoaded', init);

async function init(){
    await loadJabatan();
    await loadData();

    search.oninput = filterData;
    btnUpdate.onclick = update;
    btnTutup.onclick = tutup;
}


// ================= FORMAT TANGGAL =================
function formatTanggal(tgl){

    if(!tgl) return '-';

    const d = new Date(tgl);

    return d.toLocaleDateString('id-ID', {
        day:'2-digit',
        month:'long',
        year:'numeric'
    });
}


// ================= LOAD =================
async function loadJabatan(){
    const res = await fetch('/api/jabatan');
    jabatanList = (await res.json()).data;
}

async function loadData(){
    const res = await fetch('/api/pegawai');
    allData = (await res.json()).data;
    render(allData);
}


// ================= FILTER =================
function filterData(){

    const keyword = search.value.toLowerCase();

    const filtered = allData.filter(p => {

        let nama = p.nama_guru.toLowerCase();
        let nip = p.nip.toLowerCase();
        let jabatan = (p.jabatan || [])
            .map(j => j.nama_jabatan.toLowerCase())
            .join(' ');

        return nama.includes(keyword) ||
               nip.includes(keyword) ||
               jabatan.includes(keyword);
    });

    render(filtered);
}


// ================= RENDER =================
function render(data){

    if(data.length === 0){
        dataEl.innerHTML = `<tr><td colspan="7">Tidak ada data</td></tr>`;
        return;
    }

    let html = '';

    data.forEach(p => {

        let roles = (p.jabatan || []).map(j =>
            `<span class="role">${j.nama_jabatan}</span>`
        ).join('');

        html += `
        <tr>
            <td>${p.nama_guru}</td>
            <td>${p.nip}</td>
            <td>${p.jenis_kelamin}</td>

            <td>
                <b>${p.tempat_lahir ?? '-'}</b><br>
                <small>${formatTanggal(p.tanggal_lahir)}</small>
            </td>

            <td>
                ${p.no_telepon ?? '-'}<br>
                ${p.email ?? '-'}
            </td>

            <td>${roles}</td>

            <td>
                <button class="btn-edit" onclick="edit(${p.id_guru})">Edit</button>
                <button class="btn-delete" onclick="hapus(${p.id_guru})">Hapus</button>
            </td>
        </tr>`;
    });

    dataEl.innerHTML = html;
}


// ================= EDIT =================
function edit(id){

    editId = id;
    const p = allData.find(x => x.id_guru == id);

    edit_nama.value = p.nama_guru;
    edit_nip.value = p.nip;
    edit_jk.value = p.jenis_kelamin;
    edit_tempat.value = p.tempat_lahir;
    edit_tanggal.value = p.tanggal_lahir?.split('T')[0];
    edit_alamat.value = p.alamat ?? '';
    edit_telp.value = p.no_telepon ?? '';
    edit_email.value = p.email ?? '';
    edit_masuk.value = p.tanggal_masuk?.split('T')[0];

    let html = '';

    jabatanList.forEach(j => {

        let checked = (p.jabatan || []).some(r =>
            r.id_jabatan == j.id_jabatan
        ) ? 'checked' : '';

        html += `
        <label>
            <input type="checkbox" value="${j.id_jabatan}" ${checked}>
            ${j.nama_jabatan}
        </label><br>`;
    });

    edit_jabatan.innerHTML = html;

    modal.style.display = 'flex';
}


// ================= UPDATE =================
async function update(){

    const selected = [...document.querySelectorAll('#edit_jabatan input:checked')]
        .map(el => el.value);

    const res = await fetch('/api/pegawai/' + editId, {
        method:'PUT',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            nama_guru: edit_nama.value,
            nip: edit_nip.value,
            jenis_kelamin: edit_jk.value,
            tempat_lahir: edit_tempat.value,
            tanggal_lahir: edit_tanggal.value,
            alamat: edit_alamat.value,
            no_telepon: edit_telp.value,
            email: edit_email.value,
            tanggal_masuk: edit_masuk.value,
            jabatan: selected
        })
    });

    const data = await res.json();

    if(data.success){
        alert('Berhasil update');
        tutup();
        loadData();
    } else {
        alert(data.message);
    }
}


// ================= HAPUS =================
function hapus(id){
    if(confirm('Yakin hapus?')){
        fetch('/api/pegawai/'+id,{method:'DELETE'})
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