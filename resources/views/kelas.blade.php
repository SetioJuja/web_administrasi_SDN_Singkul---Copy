@extends('layouts.app')

@section('title','Kelas')

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
.form-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
    gap:10px;
    margin-bottom:15px;
}

input, select{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
}

input:focus, select:focus{
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
.btn-edit{ background:#2563eb; }

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
    width:320px;
    animation:fade 0.2s ease;
}

.modal-content input,
.modal-content select{
    width:100%;
    margin-bottom:10px;
}

.modal-content button{
    width:100%;
    margin-top:5px;
}

/* ===== ANIMATION ===== */
@keyframes fade{
    from{opacity:0; transform:scale(0.95);}
    to{opacity:1; transform:scale(1);}
}
</style>

<div class="card">

<h3>🏫 Manajemen Kelas</h3>

<!-- FORM -->
<div class="form-grid">
    <input id="nama_kelas" type="number" placeholder="Nama Kelas">
    <input id="total_siswa" type="number" placeholder="Total Siswa">

    <select id="id_guru"></select>
    <select id="id_tahun_ajaran"></select>
</div>

<button id="btnTambah" class="btn-primary">Tambah</button>

<!-- TABLE -->
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

<!-- MODAL EDIT -->
<div id="modal" class="modal">
<div class="modal-content">

<h3>Edit Kelas</h3>

<input id="edit_nama" type="number">
<input id="edit_total" type="number">
<select id="edit_guru"></select>
<select id="edit_tahun"></select>

<button id="btnUpdate" class="btn-primary">Update</button>
<button id="btnTutup" class="btn-danger">Batal</button>

</div>
</div>

@endsection


@section('script')
<script>

let editId = null;

// ================= INIT =================
document.addEventListener('DOMContentLoaded', () => {

    loadGuru();
    loadTahun();
    loadData();

    document.getElementById('btnTambah')
        .addEventListener('click', tambah);

    document.getElementById('btnUpdate')
        .addEventListener('click', update);

    document.getElementById('btnTutup')
        .addEventListener('click', tutup);
});


// ================= LOAD GURU =================
function loadGuru(){
    fetch('/api/pegawai/guru-kelas')
    .then(res=>res.json())
    .then(res=>{

        let html = '<option value="">Pilih Wali Kelas</option>';

        res.data.forEach(g=>{
            html += `<option value="${g.id_guru}">${g.nama_guru}</option>`;
        });

        id_guru.innerHTML = html;
        edit_guru.innerHTML = html;
    });
}


// ================= LOAD TAHUN =================
function loadTahun(){
    fetch('/api/tahun-ajaran')
    .then(res=>res.json())
    .then(res=>{

        let html = '<option value="">Pilih Tahun</option>';

        res.data.forEach(t=>{
            html += `<option value="${t.id_tahun_ajaran}">
                ${t.periode} - ${t.semester}
            </option>`;
        });

        id_tahun_ajaran.innerHTML = html;
        edit_tahun.innerHTML = html;
    });
}


// ================= LOAD DATA =================
function loadData(){
    fetch('/api/kelas')
    .then(res=>res.json())
    .then(res=>{

        let html='';

        if(!res.data.length){
            html = `<tr><td colspan="5">Belum ada data</td></tr>`;
        }

        res.data.forEach(k=>{
            html+=`
            <tr>
                <td>${k.nama_kelas}</td>
                <td>${k.pegawai?.nama_guru ?? '-'}</td>
                <td>${k.tahun_ajaran?.periode ?? '-'}</td>
                <td><b>${k.total_siswa}</b></td>
                <td>
                    <button class="btn-edit" data-id="${k.id_kelas}">Edit</button>
                    <button class="btn-danger" data-id="${k.id_kelas}">Hapus</button>
                </td>
            </tr>`;
        });

        data.innerHTML = html;

        document.querySelectorAll('.btn-edit').forEach(btn=>{
            btn.onclick = () => edit(btn.dataset.id);
        });

        document.querySelectorAll('.btn-danger').forEach(btn=>{
            btn.onclick = () => hapus(btn.dataset.id);
        });

    });
}


// ================= TAMBAH =================
function tambah(){

    if(!nama_kelas.value || !total_siswa.value){
        alert('Isi semua data!');
        return;
    }

    fetch('/api/kelas',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            nama_kelas: nama_kelas.value,
            total_siswa: total_siswa.value,
            id_guru: id_guru.value,
            id_tahun_ajaran: id_tahun_ajaran.value
        })
    })
    .then(()=>{
        nama_kelas.value='';
        total_siswa.value='';
        loadData();
    });
}


// ================= EDIT =================
function edit(id){

    editId = id;

    fetch('/api/kelas/'+id)
    .then(res=>res.json())
    .then(res=>{

        let d = res.data;

        edit_nama.value = d.nama_kelas;
        edit_total.value = d.total_siswa;
        edit_guru.value = d.id_guru;
        edit_tahun.value = d.id_tahun_ajaran;

        modal.style.display='flex';
    });
}


// ================= UPDATE =================
function update(){
    fetch('/api/kelas/'+editId,{
        method:'PUT',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            nama_kelas: edit_nama.value,
            total_siswa: edit_total.value,
            id_guru: edit_guru.value,
            id_tahun_ajaran: edit_tahun.value
        })
    })
    .then(()=>{
        tutup();
        loadData();
    });
}


// ================= HAPUS =================
function hapus(id){
    if(confirm('Yakin hapus data?')){
        fetch('/api/kelas/'+id,{method:'DELETE'})
        .then(()=>loadData());
    }
}


// ================= TUTUP =================
function tutup(){
    modal.style.display='none';
}

</script>
@endsection