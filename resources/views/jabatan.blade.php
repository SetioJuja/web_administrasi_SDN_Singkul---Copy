@extends('layouts.app')

@section('title','Manajemen Jabatan')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --success:#16a34a;
    --danger:#dc2626;
}

/* ===== CARD ===== */
.card{
    background:white;
    border-radius:16px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

/* ===== TITLE ===== */
h3{
    margin-bottom:20px;
    color:var(--primary);
}

/* ===== FORM ===== */
.form-inline{
    display:flex;
    gap:10px;
    margin-bottom:15px;
}

input{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    flex:1;
}

input:focus{
    border-color:var(--primary);
}

/* ===== BUTTON ===== */
button{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    color:white;
}

.btn-primary{ background:var(--primary); }
.btn-danger{ background:var(--danger); }
.btn-info{ background:#2563eb; }
.btn-secondary{ background:#6b7280; }

button:hover{
    opacity:0.9;
}

/* ===== TABLE ===== */
table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

thead{
    background:#f1f5f9;
}

th, td{
    border:1px solid var(--border);
    padding:10px;
    text-align:center;
}

tbody tr:hover{
    background:#f9fafb;
}

/* ===== MODAL ===== */
.modal{
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
    justify-content:center;
    align-items:center;
    z-index:999;
}

.modal-content{
    background:white;
    padding:20px;
    border-radius:12px;
    width:300px;
    text-align:center;
    animation:fade 0.2s ease;
}

.modal-content h3{
    margin-bottom:15px;
}

.modal-content input{
    width:100%;
    margin-bottom:10px;
}

.modal-content button{
    width:100%;
    margin-top:5px;
}

/* ===== LIST ===== */
#detailList{
    text-align:left;
    margin-bottom:10px;
}

#detailList li{
    padding:6px;
    border-bottom:1px solid var(--border);
}

@keyframes fade{
    from{opacity:0; transform:scale(0.95);}
    to{opacity:1; transform:scale(1);}
}
</style>

<div class="card">

<h3>Manajemen Jabatan</h3>

<!-- FORM -->
<div class="form-inline">
    <input id="nama_jabatan" placeholder="Nama Jabatan">
    <button id="btnTambah" class="btn-primary">Tambah</button>
</div>

<!-- TABLE -->
<table>
<thead>
<tr>
<th>Nama Jabatan</th>
<th>Jumlah Pegawai</th>
<th>Aksi</th>
</tr>
</thead>
<tbody id="data"></tbody>
</table>

</div>

<!-- MODAL EDIT -->
<div id="modalEdit" class="modal">
<div class="modal-content">
<h3>Edit Jabatan</h3>

<input id="edit_nama">

<button id="btnUpdate" class="btn-primary">Update</button>
<button id="btnTutup" class="btn-secondary">Batal</button>

</div>
</div>

<!-- MODAL DETAIL -->
<div id="modalDetail" class="modal">
<div class="modal-content">
<h3>Detail Pegawai</h3>

<ul id="detailList"></ul>

<button id="btnTutupDetail" class="btn-secondary">Tutup</button>

</div>
</div>

@endsection


@section('script')
<script>

let editId = null;

// ================= INIT =================
document.addEventListener('DOMContentLoaded', () => {

    loadData();

    document.getElementById('btnTambah')
        .addEventListener('click', tambah);

    document.getElementById('btnUpdate')
        .addEventListener('click', update);

    document.getElementById('btnTutup')
        .addEventListener('click', tutup);

    document.getElementById('btnTutupDetail')
        .addEventListener('click', tutupDetail);
});


// ================= LOAD DATA =================
function loadData(){
    fetch('/api/jabatan')
    .then(res=>res.json())
    .then(res=>{

        let html='';

        res.data.forEach(j=>{
            html+=`
            <tr>
                <td>${j.nama_jabatan}</td>
                <td><b>${j.pegawai_count}</b></td>
                <td>
                    <button class="btn-info" data-id="${j.id_jabatan}">Detail</button>
                    <button class="btn-primary" data-id="${j.id_jabatan}" data-nama="${j.nama_jabatan}">Edit</button>
                    <button class="btn-danger" data-id="${j.id_jabatan}">Hapus</button>
                </td>
            </tr>`;
        });

        document.getElementById('data').innerHTML = html;

        document.querySelectorAll('.btn-info').forEach(btn=>{
            btn.onclick = () => detail(btn.dataset.id);
        });

        document.querySelectorAll('.btn-primary').forEach(btn=>{
            if(btn.dataset.id){
                btn.onclick = () => edit(btn.dataset.id, btn.dataset.nama);
            }
        });

        document.querySelectorAll('.btn-danger').forEach(btn=>{
            btn.onclick = () => hapus(btn.dataset.id);
        });

    });
}


// ================= TAMBAH =================
function tambah(){

    let nama = document.getElementById('nama_jabatan').value;

    if(!nama){
        alert('Nama jabatan wajib diisi');
        return;
    }

    fetch('/api/jabatan',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ nama_jabatan: nama })
    })
    .then(res=>res.json())
    .then(res=>{
        alert(res.message);
        document.getElementById('nama_jabatan').value = '';
        loadData();
    });
}


// ================= EDIT =================
function edit(id, nama){
    editId = id;

    document.getElementById('edit_nama').value = nama;
    document.getElementById('modalEdit').style.display='flex';
}


// ================= UPDATE =================
function update(){

    fetch('/api/jabatan/'+editId,{
        method:'PUT',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            nama_jabatan: document.getElementById('edit_nama').value
        })
    })
    .then(res=>res.json())
    .then(res=>{
        alert(res.message);
        tutup();
        loadData();
    });
}


// ================= HAPUS =================
function hapus(id){
    if(confirm('Yakin hapus?')){
        fetch('/api/jabatan/'+id,{method:'DELETE'})
        .then(res=>res.json())
        .then(res=>{
            alert(res.message);
            loadData();
        });
    }
}


// ================= DETAIL =================
function detail(id){
    fetch('/api/jabatan/'+id)
    .then(res=>res.json())
    .then(res=>{

        let html='';

        if(res.data.pegawai.length === 0){
            html = '<li>Tidak ada pegawai</li>';
        } else {
            res.data.pegawai.forEach(p=>{
                html += `<li>${p.nama_guru}</li>`;
            });
        }

        document.getElementById('detailList').innerHTML = html;
        document.getElementById('modalDetail').style.display='flex';
    });
}


// ================= TUTUP =================
function tutup(){
    document.getElementById('modalEdit').style.display='none';
}

function tutupDetail(){
    document.getElementById('modalDetail').style.display='none';
}

</script>
@endsection