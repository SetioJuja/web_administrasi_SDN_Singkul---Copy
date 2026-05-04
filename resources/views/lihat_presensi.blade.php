@extends('layouts.app')

@section('title','Presensi Saya')

@section('content')

<div class="card">

    <h2 class="title-section">📊 Presensi Saya</h2>

    <div class="filter-row">
        <label>Filter Bulan:</label>
        <select id="filterBulan" onchange="renderPresensi()">
            <option value="">Semua Bulan</option>
            <option value="01">Januari</option>
            <option value="02">Februari</option>
            <option value="03">Maret</option>
            <option value="04">April</option>
            <option value="05">Mei</option>
            <option value="06">Juni</option>
            <option value="07">Juli</option>
            <option value="08">Agustus</option>
            <option value="09">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
        </select>
    </div>

    <div id="presensiContainer">
        <div class="empty-state">Memuat data...</div>
    </div>

</div>

@endsection


@section('script')

<style>

/* ===== TITLE ===== */
.title-section {
    font-size: 20px;
    margin-bottom: 15px;
    color: #0a3d62;
}

/* ===== FILTER ===== */
.filter-row {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.filter-row label {
    font-size: 13px;
    color: #6b7280;
}

.filter-row select {
    padding: 7px 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

/* ===== SECTION ===== */
.tahun-section {
    margin-bottom: 30px;
}

.tahun-label {
    background: #0a3d62;
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    margin-bottom: 15px;
    display: inline-block;
}

/* ===== REKAP ===== */
.rekap-row {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.rekap-card {
    flex: 1;
    text-align: center;
    padding: 10px;
    border-radius: 10px;
}

.rekap-angka {
    font-size: 22px;
    font-weight: bold;
}

.rekap-label {
    font-size: 11px;
}

/* warna */
.r-hadir { background: #dcfce7; color:#15803d; }
.r-izin  { background: #fef9c3; color:#a16207; }
.r-sakit { background: #dbeafe; color:#1d4ed8; }
.r-alpa  { background: #fee2e2; color:#b91c1c; }

/* ===== TABLE ===== */
.kal-wrap {
    overflow-x: auto;
}

.kal-table {
    border-collapse: collapse;
    font-size: 12px;
}

.kal-table th {
    background: #0a3d62;
    color: white;
    padding: 6px;
}

.kal-table td {
    width: 32px;
    height: 32px;
    text-align: center;
    border: 1px solid #eee;
}

/* status */
.s-hadir { background:#dcfce7; }
.s-izin  { background:#fef9c3; }
.s-sakit { background:#dbeafe; }
.s-alpa  { background:#fee2e2; }

.kosong { color:#ccc; }

/* ===== EMPTY ===== */
.empty-state {
    text-align: center;
    padding: 30px;
    color: #999;
}

</style>


<script>

let allPresensi = [];

// ================= INIT =================
document.addEventListener('DOMContentLoaded', loadPresensiSaya);


// ================= LOAD =================
function loadPresensiSaya(){

    const user = JSON.parse(localStorage.getItem('user'));

    if(!user){
        document.getElementById('presensiContainer').innerHTML =
            '<div class="empty-state">User tidak ditemukan</div>';
        return;
    }

    fetch('/api/presensi-saya/' + user.id)
    .then(res => res.json())
    .then(res => {
        allPresensi = res.data || [];
        renderPresensi();
    })
    .catch(() => {
        document.getElementById('presensiContainer').innerHTML =
            '<div class="empty-state">Gagal load data</div>';
    });
}


// ================= RENDER =================
function renderPresensi(){

    const bulan = document.getElementById('filterBulan').value;

    let data = allPresensi;

    if(bulan){
        data = data.filter(p => p.tanggal.split('-')[1] === bulan);
    }

    if(data.length === 0){
        document.getElementById('presensiContainer').innerHTML =
            '<div class="empty-state">Tidak ada data</div>';
        return;
    }

    let grouped = {};

    data.forEach(p => {
        let key = p.tahun_ajaran?.periode || 'Tanpa Tahun';
        if(!grouped[key]) grouped[key] = [];
        grouped[key].push(p);
    });

    let html = '';

    Object.keys(grouped).forEach(tahun => {

        let list = grouped[tahun];

        let hadir=0, izin=0, sakit=0, alpa=0;

        list.forEach(p => {
            let s = (p.status?.nama_status || '').toLowerCase();
            if(s==='hadir') hadir++;
            else if(s==='izin') izin++;
            else if(s==='sakit') sakit++;
            else if(s==='alpa') alpa++;
        });

        html += `
        <div class="tahun-section">

            <div class="tahun-label">📘 ${tahun}</div>

            <div class="rekap-row">
                ${card('r-hadir', hadir, 'Hadir')}
                ${card('r-izin', izin, 'Izin')}
                ${card('r-sakit', sakit, 'Sakit')}
                ${card('r-alpa', alpa, 'Alpa')}
            </div>
        `;

        let bulanMap = {};

        list.forEach(p=>{
            let [y,m,d] = p.tanggal.split('-');
            let key = y+'-'+m;

            if(!bulanMap[key]) bulanMap[key] = {};
            bulanMap[key][parseInt(d)] =
                (p.status?.nama_status || '').toLowerCase();
        });

        Object.keys(bulanMap).forEach(bKey => {

            html += `<div class="kal-wrap"><table class="kal-table"><tr>`;

            for(let i=1;i<=31;i++){
                html += `<th>${i}</th>`;
            }

            html += `</tr><tr>`;

            for(let i=1;i<=31;i++){
                let st = bulanMap[bKey][i];

                if(!st){
                    html += `<td class="kosong">-</td>`;
                }else{
                    html += `<td class="s-${st}">${st[0].toUpperCase()}</td>`;
                }
            }

            html += `</tr></table></div>`;
        });

        html += `</div>`;
    });

    document.getElementById('presensiContainer').innerHTML = html;
}


// ================= HELPER =================
function card(cls, angka, label){
    return `
    <div class="rekap-card ${cls}">
        <div class="rekap-angka">${angka}</div>
        <div class="rekap-label">${label}</div>
    </div>`;
}

</script>

@endsection