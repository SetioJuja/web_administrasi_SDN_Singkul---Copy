@extends('layouts.app')

@section('title','Komponen Penilaian')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --danger:#dc2626;
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
    margin-bottom:15px;
    color:var(--primary);
}

/* TOP BAR */
.top-bar{
    display:flex;
    justify-content:space-between;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
}

/* INPUT */
input, select{
    padding:10px;
    border-radius:8px;
    border:1px solid var(--border);
    outline:none;
}

/* FORM GRID */
.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:10px;
    margin-bottom:15px;
}

/* BUTTON */
.btn-primary{
    background:var(--primary);
    color:white;
    border:none;
    padding:10px 16px;
    border-radius:8px;
    cursor:pointer;
}

.btn-delete{
    background:var(--danger);
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
    font-size:14px;
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
</style>

<div class="card">

<h3>📊 Komponen Penilaian</h3>

<!-- SEARCH -->
<div class="top-bar">
    <input id="search" placeholder="🔍 Cari komponen...">
</div>

<!-- FORM -->
<div class="form-grid">
    <select id="id_mapel"></select>
    <input id="nama_komponen" placeholder="Nama Komponen">
    <input id="bobot" placeholder="Bobot (%)">
</div>

<p style="margin-bottom:10px;"><b>👨‍🏫 Guru: otomatis dari login</b></p>

<button id="btnTambah" class="btn-primary">+ Tambah Komponen</button>

<!-- TABLE -->
<table>
<thead>
<tr>
<th>Mapel</th>
<th>Guru</th>
<th>Komponen</th>
<th>Bobot</th>
<th>Aksi</th>
</tr>
</thead>
<tbody id="data"></tbody>
</table>

</div>

@endsection


@section('script')
<script>

let id_guru;
let allData = [];

// INIT
document.addEventListener('DOMContentLoaded', init);

async function init(){

    const user = JSON.parse(localStorage.getItem('user'));

    if(!user){
        location.href = '/login';
        return;
    }

    id_guru = user.id;

    await loadMapel();
    await loadData();

    document.getElementById('search')
        .addEventListener('input', filterData);

    document.getElementById('btnTambah')
        .addEventListener('click', tambah);
}


// LOAD MAPEL
async function loadMapel(){

    const res = await fetch('/api/mapel');
    const data = await res.json();

    let html='';

    data.data.forEach(m=>{
        html+=`<option value="${m.id_mapel}">
            ${m.nama_mapel}
        </option>`;
    });

    document.getElementById('id_mapel').innerHTML = html;
}


// LOAD DATA
async function loadData(){

    const res = await fetch('/api/komponen-penilaian-guru/' + id_guru);
    const data = await res.json();

    allData = data.data;
    render(allData);
}


// FILTER
function filterData(){

    const key = document.getElementById('search')
        .value.toLowerCase();

    const filtered = allData.filter(d =>
        d.nama_komponen.toLowerCase().includes(key)
    );

    render(filtered);
}


// RENDER
function render(data){

    let html='';

    data.forEach(d=>{
        html+=`
        <tr>
            <td>${d.mapel?.nama_mapel ?? '-'}</td>
            <td>${d.guru?.nama_guru ?? '-'}</td>
            <td>${d.nama_komponen}</td>
            <td>${d.bobot}%</td>
            <td>
                <button class="btn-delete" data-id="${d.id_komponen}">
                    Hapus
                </button>
            </td>
        </tr>
        `;
    });

    document.getElementById('data').innerHTML = html;

    document.querySelectorAll('.btn-delete').forEach(btn=>{
        btn.onclick = () => hapus(btn.dataset.id);
    });
}


// TAMBAH
async function tambah(){

    const res = await fetch('/api/komponen-penilaian',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            id_mapel: id_mapel.value,
            id_guru: id_guru,
            nama_komponen: nama_komponen.value,
            bobot: bobot.value
        })
    });

    const data = await res.json();

    if(data.success){
        alert('Berhasil');

        nama_komponen.value = '';
        bobot.value = '';

        loadData();
    } else {
        alert(data.message);
    }
}


// HAPUS
function hapus(id){
    if(confirm('Yakin hapus?')){
        fetch('/api/komponen-penilaian/'+id,{method:'DELETE'})
        .then(()=>loadData());
    }
}

</script>
@endsection