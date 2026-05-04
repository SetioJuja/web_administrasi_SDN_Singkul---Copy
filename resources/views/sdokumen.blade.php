@extends('layouts.app')

@section('title','Dokumen Administrasi')

@section('content')

<div class="card">

<h3 class="title">📂 Dokumen Administrasi</h3>

<!-- FILTER -->
<div class="filter-row">
    <select id="filter_tahun"></select>

    <select id="filter_bulan">
        <option value="">Semua Bulan</option>
        <option value="1">Jan</option>
        <option value="2">Feb</option>
        <option value="3">Mar</option>
        <option value="4">Apr</option>
        <option value="5">Mei</option>
        <option value="6">Jun</option>
        <option value="7">Jul</option>
        <option value="8">Agu</option>
        <option value="9">Sep</option>
        <option value="10">Okt</option>
        <option value="11">Nov</option>
        <option value="12">Des</option>
    </select>

    <select id="filter_minggu">
        <option value="">Semua Minggu</option>
        <option value="1">M1</option>
        <option value="2">M2</option>
        <option value="3">M3</option>
        <option value="4">M4</option>
        <option value="5">M5</option>
    </select>
</div>

<!-- TABLE -->
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Judul</th>
<th>Tanggal</th>
<th>Tahun</th>
<th>File</th>
<th>Keterangan</th>
</tr>
</thead>
<tbody id="data"></tbody>
</table>
</div>

</div>

<!-- MODAL GAMBAR -->
<div id="modalImg" class="modal-img">
    <span class="close" onclick="tutupGambar()">×</span>
    <img id="imgPreview">
</div>

@endsection


@section('script')

<style>

/* ===== TITLE ===== */
.title{
    margin-bottom:15px;
    color:#0a3d62;
}

/* ===== FILTER ===== */
.filter-row{
    display:flex;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
}

select{
    padding:7px 10px;
    border-radius:6px;
    border:1px solid #ddd;
}

/* ===== TABLE ===== */
.table-wrap{
    overflow:auto;
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

/* ===== FILE ===== */
.doc-img{
    width:70px;
    border-radius:6px;
    cursor:pointer;
    transition:0.2s;
}

.doc-img:hover{
    transform:scale(1.1);
}

.file-link{
    color:#2563eb;
    text-decoration:none;
    font-weight:500;
}

.file-link:hover{
    text-decoration:underline;
}

/* ===== EMPTY ===== */
.empty{
    padding:20px;
    text-align:center;
    color:#999;
}

/* ===== MODAL ===== */
.modal-img{
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.85);
    justify-content:center;
    align-items:center;
    z-index:999;
}

.modal-img img{
    max-width:85%;
    max-height:85%;
    border-radius:10px;
    box-shadow:0 10px 30px rgba(0,0,0,0.5);
}

.close{
    position:absolute;
    top:20px;
    right:30px;
    color:white;
    font-size:30px;
    cursor:pointer;
}

</style>


<script>

let allData = [];

// ================= INIT =================
document.addEventListener('DOMContentLoaded', init);

async function init(){

    await loadTahun();
    await loadData();

    filter_tahun.onchange = filterData;
    filter_bulan.onchange = filterData;
    filter_minggu.onchange = filterData;
}


// ================= LOAD TAHUN =================
async function loadTahun(){

    const res = await fetch('/api/tahun-ajaran');
    const json = await res.json();

    let html = '<option value="">Semua Tahun</option>';

    json.data.forEach(t=>{
        html += `<option value="${t.id_tahun_ajaran}">
            ${t.periode} - ${t.semester}
        </option>`;
    });

    filter_tahun.innerHTML = html;
}


// ================= LOAD DATA =================
async function loadData(){

    const res = await fetch('/api/dokumen');
    const json = await res.json();

    allData = json.data || [];

    render(allData);
}


// ================= FILTER =================
function filterData(){

    const tahun = filter_tahun.value;
    const bulan = filter_bulan.value;
    const minggu = filter_minggu.value;

    let data = allData.filter(d => {

        const tgl = new Date(d.tanggal_upload);

        let ok = true;

        if(tahun && d.id_tahun_ajaran != tahun) ok = false;

        if(bulan && (tgl.getMonth()+1) != bulan) ok = false;

        if(minggu){
            const week = Math.ceil(tgl.getDate()/7);
            if(week != minggu) ok = false;
        }

        return ok;
    });

    render(data);
}


// ================= RENDER =================
function render(data){

    if(data.length === 0){
        dataEl.innerHTML = `<tr><td colspan="5" class="empty">Tidak ada data</td></tr>`;
        return;
    }

    let html = '';

    data.forEach(d => {

        let file = '-';

        if(d.gambar){

            let ext = d.gambar.split('.').pop().toLowerCase();

            if(['jpg','jpeg','png'].includes(ext)){
                file = `
                    <img src="/uploads/${d.gambar}" 
                         class="doc-img"
                         onclick="lihatGambar('/uploads/${d.gambar}')">
                `;
            } else {
                file = `
                    <a href="/uploads/${d.gambar}" target="_blank" class="file-link">
                        📄 Download
                    </a>
                `;
            }
        }

        html += `
        <tr>
            <td>${d.judul_dokumen}</td>
            <td>${d.tanggal_upload}</td>
            <td>
                ${d.tahun_ajaran?.periode ?? '-'} 
                (${d.tahun_ajaran?.semester ?? '-'})
            </td>
            <td>${file}</td>
            <td>${d.keterangan ?? '-'}</td>
        </tr>
        `;
    });

    dataEl.innerHTML = html;
}


// ================= PREVIEW GAMBAR =================
function lihatGambar(src){
    modalImg.style.display = 'flex';
    imgPreview.src = src;
}

function tutupGambar(){
    modalImg.style.display = 'none';
}

const dataEl = document.getElementById('data');

</script>

@endsection