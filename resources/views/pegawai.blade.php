@extends('layouts.app')

@section('title','Data Pegawai')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --success:#16a34a;
    --danger:#dc2626;
    --bg:#f4f7fb;
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

/* ===== GRID FORM ===== */
.form-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap:12px;
    margin-bottom:15px;
}

/* ===== INPUT ===== */
input{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
}

input:focus{
    border-color:var(--primary);
}

/* ===== CHECKBOX JABATAN ===== */
.checkbox-group{
    grid-column: span 2;
}

#jabatan{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    margin-top:8px;
}

#jabatan label{
    background:#f1f5f9;
    padding:6px 10px;
    border-radius:8px;
    cursor:pointer;
    font-size:12px;
    transition:0.2s;
}

#jabatan input{
    margin-right:5px;
}

#jabatan label:hover{
    background:#e2e8f0;
}

/* ===== BUTTON ===== */
button{
    padding:10px 16px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    background:var(--primary);
    color:white;
    margin-bottom:15px;
}

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

/* ===== BADGE ===== */
.badge{
    display:inline-block;
    padding:4px 8px;
    background:#e0f2fe;
    color:#0369a1;
    border-radius:6px;
    font-size:11px;
    margin:2px;
}

/* ===== BUTTON DELETE ===== */
.btn-danger{
    background:var(--danger);
    color:white;
    padding:6px 10px;
    border-radius:6px;
    border:none;
    cursor:pointer;
}

.btn-danger:hover{
    opacity:0.85;
}

</style>

<div class="card">

<h3>👨‍💼 Manajemen Pegawai</h3>

<!-- FORM -->
<div class="form-grid">
    <input id="nama_guru" placeholder="Nama">
    <input id="nip" placeholder="NIP">
    
    <input id="jenis_kelamin" placeholder="L / P">
    <input id="tempat_lahir" placeholder="Tempat Lahir">

    <input id="tanggal_lahir" type="date">
    <input id="tanggal_masuk" type="date">

    <input id="alamat" placeholder="Alamat">
    <input id="no_telepon" placeholder="No Telepon">

    <input id="email" placeholder="Email">
    <input id="password" placeholder="Password">

    <div class="checkbox-group">
        <b>Jabatan:</b>
        <div id="jabatan"></div>
    </div>
</div>

<button id="btnSimpan">💾 Simpan</button>

<!-- TABLE -->
<table>
<thead>
<tr>
<th>Nama</th>
<th>NIP</th>
<th>Jabatan</th>
<th>Aksi</th>
</tr>
</thead>
<tbody id="data"></tbody>
</table>

</div>

@endsection


@section('script')
<script>

// ================= INIT =================
document.addEventListener('DOMContentLoaded', () => {
    loadJabatan();
    loadData();

    document.getElementById('btnSimpan')
        .addEventListener('click', tambah);
});


// ================= LOAD JABATAN =================
function loadJabatan(){
    fetch('/api/jabatan')
    .then(res => res.json())
    .then(res => {

        let html = '';

        res.data.forEach(j => {
            html += `
                <label>
                    <input type="checkbox" value="${j.id_jabatan}" class="jabatan">
                    ${j.nama_jabatan}
                </label>
            `;
        });

        document.getElementById('jabatan').innerHTML = html;
    });
}


// ================= GET ROLE =================
function getSelectedJabatan(){
    let selected = [];

    document.querySelectorAll('.jabatan:checked')
        .forEach(el => selected.push(parseInt(el.value)));

    return selected;
}


// ================= LOAD DATA =================
function loadData(){
    fetch('/api/pegawai')
    .then(res => res.json())
    .then(res => {

        let html = '';

        res.data.forEach(p => {

            let roles = '-';

            if(p.jabatan?.length){
                roles = p.jabatan.map(j => 
                    `<span class="badge">${j.nama_jabatan}</span>`
                ).join('');
            }

            html += `
                <tr>
                    <td>${p.nama_guru}</td>
                    <td>${p.nip}</td>
                    <td>${roles}</td>
                    <td>
                        <button class="btn-danger" data-id="${p.id_guru}">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        });

        document.getElementById('data').innerHTML = html;

        document.querySelectorAll('.btn-danger').forEach(btn => {
            btn.onclick = () => hapus(btn.dataset.id);
        });
    });
}


// ================= TAMBAH =================
function tambah(){

    fetch('/api/pegawai', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({
            nama_guru: nama_guru.value,
            nip: nip.value,
            jenis_kelamin: jenis_kelamin.value,
            tempat_lahir: tempat_lahir.value,
            tanggal_lahir: tanggal_lahir.value,
            alamat: alamat.value || null,
            no_telepon: no_telepon.value || null,
            email: email.value || null,
            tanggal_masuk: tanggal_masuk.value,
            password: password.value,
            jabatan: getSelectedJabatan()
        })
    })
    .then(res => res.json())
    .then(res => {

        if(res.success){
            alert('Berhasil');

            document.querySelectorAll('input').forEach(i => i.value = '');
            document.querySelectorAll('.jabatan').forEach(c => c.checked = false);

            loadData();
        } else {
            alert('Gagal');
        }
    });
}


// ================= HAPUS =================
function hapus(id){
    if(confirm('Yakin hapus data?')){
        fetch('/api/pegawai/' + id, {method:'DELETE'})
        .then(() => loadData());
    }
}

</script>
@endsection