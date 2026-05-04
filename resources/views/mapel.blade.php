@extends('layouts.app')

@section('title','Data Mapel')

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
    flex-wrap:wrap;
}

input{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    flex:1;
    min-width:200px;
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

/* ===== EMPTY ===== */
.empty{
    padding:20px;
    text-align:center;
    color:#999;
}
</style>

<div class="card">

<h3>📘 Data Mata Pelajaran</h3>

<!-- FORM -->
<div class="form-inline">
    <input id="nama_mapel" placeholder="Nama Mapel">
    <input id="kode_mapel" placeholder="Kode Mapel">
    <button id="btnTambah" class="btn-primary">Tambah</button>
</div>

<!-- TABLE -->
<table>
<thead>
<tr>
<th>Nama</th>
<th>Kode</th>
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
    loadData();

    document.getElementById('btnTambah')
        .addEventListener('click', tambah);
});


// ================= LOAD DATA =================
function loadData(){
    fetch('/api/mapel')
    .then(res => res.json())
    .then(res => {

        let html = '';

        if(!res.data || res.data.length === 0){
            html = `<tr><td colspan="3" class="empty">Belum ada data</td></tr>`;
        } else {

            res.data.forEach(m => {
                html += `
                    <tr>
                        <td>${m.nama_mapel}</td>
                        <td>${m.kode_mapel}</td>
                        <td>
                            <button class="btn-danger" data-id="${m.id}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                `;
            });

        }

        document.getElementById('data').innerHTML = html;

        document.querySelectorAll('.btn-danger').forEach(btn => {
            btn.onclick = () => hapus(btn.dataset.id);
        });
    });
}


// ================= TAMBAH =================
function tambah(){

    if(!nama_mapel.value || !kode_mapel.value){
        alert('Semua field wajib diisi');
        return;
    }

    fetch('/api/mapel', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({
            nama_mapel: nama_mapel.value,
            kode_mapel: kode_mapel.value
        })
    })
    .then(async res => {

        const data = await res.json();

        if (!res.ok) {
            alert(JSON.stringify(data.errors || data.message));
            return;
        }

        alert('Berhasil tambah');

        nama_mapel.value = '';
        kode_mapel.value = '';

        loadData();
    });
}


// ================= HAPUS =================
function hapus(id){
    if(confirm('Yakin hapus data?')){
        fetch('/api/mapel/' + id, {method:'DELETE'})
        .then(() => loadData());
    }
}

</script>
@endsection