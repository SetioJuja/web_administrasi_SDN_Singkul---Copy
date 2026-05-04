@extends('layouts.app')

@section('title','Nilai Siswa')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --success:#16a34a;
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
    margin-bottom:20px;
    color:var(--primary);
}

/* SELECT + SEARCH */
.top-bar{
    display:flex;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
}

select, input{
    padding:10px;
    border-radius:8px;
    border:1px solid var(--border);
    outline:none;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

thead{
    background:var(--primary);
    color:white;
}

th, td{
    padding:10px;
    border-bottom:1px solid var(--border);
    text-align:center;
}

tr:hover{
    background:#f9fafb;
}

/* NILAI */
.good{
    color:var(--success);
    font-weight:bold;
}

.bad{
    color:var(--danger);
    font-weight:bold;
}

td:first-child, th:first-child{
    text-align:left;
    font-weight:500;
}
</style>



<div class="card">

<h3>📊 Data Nilai Siswa</h3>

<div class="top-bar">
    <select id="kelas" disabled></select>
    <select id="mapel"></select>
    <input id="search" placeholder="🔍 Cari nama siswa...">
</div>

<table>
<thead>
<tr id="header"></tr>
</thead>
<tbody id="data"></tbody>
</table>

</div>

@endsection


@section('script')
<script>

let siswa = [];
let siswaFiltered = [];
let tugas = [];
let nilai = {};
let nilaiSiswa = {};

let id_kelas;
let id_mapel;
let id_guru;


// INIT
document.addEventListener('DOMContentLoaded', init);

async function init(){

    const user = JSON.parse(localStorage.getItem('user'));

    if(!user){
        location.href = '/login';
        return;
    }

    id_guru = user.id;

    await loadKelasSaya();

    document.getElementById('mapel')
        .addEventListener('change', loadData);

    document.getElementById('search')
        .addEventListener('input', filterData);
}


// LOAD KELAS
async function loadKelasSaya(){

    const res = await fetch('/api/kelas-saya/' + id_guru);
    const json = await res.json();

    if(json.data.length === 0){
        alert('Tidak punya kelas');
        return;
    }

    const k = json.data[0];
    id_kelas = k.id_kelas;

    document.getElementById('kelas').innerHTML =
        `<option>${k.nama_kelas}</option>`;

    loadMapel();
}


// LOAD MAPEL
async function loadMapel(){

    const res = await fetch('/api/mapel');
    const json = await res.json();

    let html = '<option value="">Pilih Mapel</option>';

    json.data.forEach(m=>{
        html += `<option value="${m.id_mapel}">
            ${m.nama_mapel}
        </option>`;
    });

    document.getElementById('mapel').innerHTML = html;
}


// LOAD DATA
async function loadData(){

    id_mapel = document.getElementById('mapel').value;
    if(!id_mapel) return;

    const s = await fetch('/api/siswa-by-kelas/'+id_kelas).then(r=>r.json());
    siswa = s.data;
    siswaFiltered = siswa;

    const t = await fetch('/api/tugas-by-mapel/'+id_mapel).then(r=>r.json());
    tugas = t.data;

    await loadNilai();

    const ns = await fetch(`/api/nilai-siswa/${id_kelas}/${id_mapel}`)
        .then(r=>r.json());

    nilaiSiswa = {};
    ns.data.forEach(n=>{
        nilaiSiswa[n.id_siswa] = n;
    });

    render();
}


// LOAD NILAI
async function loadNilai(){

    nilai = {};

    const res = await fetch(`/api/nilai-tugas-kelas/${id_kelas}/${id_mapel}`);
    const json = await res.json();

    json.data.forEach(n=>{
        if(!nilai[n.id_siswa]){
            nilai[n.id_siswa] = {};
        }

        nilai[n.id_siswa][n.id_tugas] = n.nilai;
    });
}


// HITUNG
function hitungAvg(id){

    const data = nilai[id] || {};
    const arr = Object.values(data);

    if(arr.length === 0) return 0;

    const total = arr.reduce((a,b)=>a+Number(b),0);
    return total / arr.length;
}

function hitungTotal(tugas, uts, uas){

    tugas = Number(tugas) || 0;
    uts   = Number(uts) || 0;
    uas   = Number(uas) || 0;

    return (
        (tugas * 0.3) +
        (uts * 0.35) +
        (uas * 0.35)
    ).toFixed(2);
}


// RENDER
function render(){

    let header = '<th>Nama</th>';

    tugas.forEach(t=>{
        header += `<th>${t.judul_tugas}</th>`;
    });

    header += '<th>AVG</th><th>UTS</th><th>UAS</th><th>TOTAL</th>';

    document.getElementById('header').innerHTML = header;

    let html='';

    siswaFiltered.forEach(s=>{

        let row = `<tr><td>${s.nama_siswa}</td>`;

        tugas.forEach(t=>{
            let val = nilai[s.id_siswa]?.[t.id_tugas] ?? '-';
            row += `<td>${val}</td>`;
        });

        let avg = hitungAvg(s.id_siswa);
        let uts = nilaiSiswa[s.id_siswa]?.nilai_uts ?? 0;
        let uas = nilaiSiswa[s.id_siswa]?.nilai_uas ?? 0;

        let total = hitungTotal(avg, uts, uas);

        row += `
        <td>${avg.toFixed(2)}</td>
        <td>${uts}</td>
        <td>${uas}</td>
        <td class="${total >= 75 ? 'good':'bad'}">${total}</td>
        `;

        row += '</tr>';

        html += row;
    });

    document.getElementById('data').innerHTML = html;
}


// SEARCH
function filterData(){

    const keyword = document.getElementById('search').value.toLowerCase();

    siswaFiltered = siswa.filter(s =>
        s.nama_siswa.toLowerCase().includes(keyword)
    );

    render();
}

</script>
@endsection