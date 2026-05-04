@extends('layouts.app')

@section('title','Pengumuman Sekolah')

@section('content')

<div class="card">

    <h2 class="title-section">📢 Pengumuman Sekolah</h2>

    <!-- FILTER -->
    <div class="filter-row">
        <input type="text" id="search" placeholder="🔍 Cari pengumuman...">

        <select id="filter_bulan">
            <option value="">Semua Bulan</option>
            <option value="01">Jan</option>
            <option value="02">Feb</option>
            <option value="03">Mar</option>
            <option value="04">Apr</option>
            <option value="05">Mei</option>
            <option value="06">Jun</option>
            <option value="07">Jul</option>
            <option value="08">Agt</option>
            <option value="09">Sep</option>
            <option value="10">Okt</option>
            <option value="11">Nov</option>
            <option value="12">Des</option>
        </select>

        <select id="filter_tahun"></select>
    </div>

    <div id="listPengumuman" class="pengumuman-list">
        <div class="loading">Memuat pengumuman...</div>
    </div>

</div>

@endsection


@section('script')

<style>

/* ===== FILTER ===== */
.filter-row{
    display:flex;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
}

.filter-row input, .filter-row select{
    padding:8px 10px;
    border:1px solid #ddd;
    border-radius:6px;
}

/* ===== TITLE ===== */
.title-section {
    font-size: 20px;
    margin-bottom: 15px;
    color: #1e5ccc;
}

/* ===== LIST ===== */
.pengumuman-list {
    display: grid;
    gap: 15px;
}

/* ===== CARD ===== */
.pengumuman-card {
    display: flex;
    gap: 15px;
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.05);
    border-left: 4px solid #1e5ccc;
}

/* ===== CONTENT ===== */
.pengumuman-content {
    flex: 1;
}

.pengumuman-title {
    font-size: 16px;
    font-weight: 600;
    color: #1e5ccc;
}

.pengumuman-date {
    font-size: 12px;
    color: #888;
    margin: 5px 0;
}

.pengumuman-isi {
    font-size: 14px;
    color: #333;
}

/* ===== IMAGE ===== */
.pengumuman-img {
    width: 120px;
    height: 90px;
    object-fit: cover;
    border-radius: 8px;
}

/* ===== EMPTY ===== */
.empty {
    text-align: center;
    padding: 30px;
    color: #777;
}

.loading {
    text-align: center;
    color: #999;
}

</style>


<script>

let allData = [];

// ================= INIT =================
document.addEventListener('DOMContentLoaded', async ()=>{

    await loadData();

    search.oninput = render;
    filter_bulan.onchange = render;
    filter_tahun.onchange = render;
});


// ================= FORMAT =================
function formatTanggal(tgl){
    if(!tgl) return '-';

    return new Date(tgl).toLocaleDateString('id-ID',{
        day:'2-digit',
        month:'long',
        year:'numeric'
    });
}


// ================= LOAD =================
async function loadData(){

    const res = await fetch('/api/pengumuman');
    const json = await res.json();

    allData = json.data || [];

    // isi dropdown tahun
    let tahunSet = new Set(
        allData.map(p => new Date(p.tanggal).getFullYear())
    );

    let html = '<option value="">Semua Tahun</option>';
    tahunSet.forEach(t=>{
        html += `<option value="${t}">${t}</option>`;
    });

    filter_tahun.innerHTML = html;

    render();
}


// ================= RENDER =================
function render(){

    let data = [...allData];

    let keyword = search.value.toLowerCase();
    let bulan = filter_bulan.value;
    let tahun = filter_tahun.value;

    if(keyword){
        data = data.filter(p =>
            p.judul.toLowerCase().includes(keyword) ||
            p.isi.toLowerCase().includes(keyword)
        );
    }

    if(bulan){
        data = data.filter(p =>
            p.tanggal.split('-')[1] === bulan
        );
    }

    if(tahun){
        data = data.filter(p =>
            new Date(p.tanggal).getFullYear() == tahun
        );
    }

    if(data.length === 0){
        listPengumuman.innerHTML = `
            <div class="empty">📭 Tidak ada pengumuman</div>`;
        return;
    }

    let html = '';

    data.forEach(p => {

        html += `
        <div class="pengumuman-card">

            <div class="pengumuman-content">

                <div class="pengumuman-title">${p.judul}</div>

                <div class="pengumuman-date">
                    📅 ${formatTanggal(p.tanggal)}
                </div>

                <div class="pengumuman-isi">${p.isi}</div>

            </div>

            ${
                p.gambar 
                ? `<img src="/upload/${p.gambar}" class="pengumuman-img">`
                : ''
            }

        </div>`;
    });

    listPengumuman.innerHTML = html;
}

</script>

@endsection