@extends('layouts.app')

@section('title','Rapor Siswa')

@section('content')

<style>

:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
}

/* ================= CARD ================= */
.card{
    background:white;
    border-radius:14px;
    padding:25px;
    box-shadow:0 8px 25px rgba(0,0,0,0.05);
}

.card h3{
    margin-bottom:20px;
    color:var(--primary);
}

/* ================= TOP BAR ================= */
.top-bar{
    display:flex;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
    align-items:center;
}

select,
input{
    padding:10px;
    border-radius:8px;
    border:1px solid var(--border);
    outline:none;
}

/* ================= BUTTON ================= */
.btn-back{
    background:#64748b;
    color:white;
    border:none;
    padding:8px 14px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    display:inline-block;
}

.btn-back:hover{ background:#475569; }

.btn-rapor{
    background:#16a34a;
    color:white;
    border:none;
    padding:8px 14px;
    border-radius:8px;
    cursor:pointer;
    font-size:12px;
    font-weight:600;
}

.btn-rapor:hover{ background:#15803d; }

/* ================= TABLE ================= */
.table-wrap{ overflow-x:auto; }

table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

thead{
    background:var(--primary);
    color:white;
}

th,td{
    padding:10px;
    border-bottom:1px solid var(--border);
    text-align:center;
}

tr:hover{ background:#f9fafb; }

td:first-child,
th:first-child{
    text-align:left;
    font-weight:500;
}

/* ================= LOADING ================= */
#loading{
    text-align:center;
    padding:40px;
    color:#64748b;
    font-size:15px;
}

/* ================= MODAL ================= */
.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
    padding:20px;
}

.modal-content{
    background:white;
    width:100%;
    max-width:1500px;
    border-radius:14px;
    overflow:hidden;
}

.modal-header{
    background:var(--primary);
    color:white;
    padding:15px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.modal-body{
    padding:20px;
    max-height:90vh;
    overflow:auto;
}

.close{
    background:none;
    border:none;
    color:white;
    font-size:26px;
    cursor:pointer;
}

/* ================= RAPOR ================= */
.rapor{
    background:white;
    color:#111;
}

.rapor-title{
    text-align:center;
    margin-bottom:20px;
}

.rapor-title h2{
    margin:0;
    font-size:24px;
}

.info-rapor{
    width:100%;
    margin-bottom:15px;
    font-size:12px;
}

.info-rapor td{
    border:none !important;
    padding:2px 6px 2px 0 !important;
    line-height:1.6;
    text-align:left;
    vertical-align:top;
    white-space:nowrap;
}

.info-rapor td:nth-child(2),
.info-rapor td:nth-child(5){ padding-right:4px !important; padding-left:0 !important; }

.info-rapor td:nth-child(3),
.info-rapor td:nth-child(6){ white-space:normal; padding-right:20px !important; }

.rapor-table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
    font-size:11px;
}

.rapor-table th,
.rapor-table td{
    border:1.5px solid #000000;
    padding:5px;
    vertical-align:top;
    line-height:1.4;
}

.rapor-table th{
    background:#f8fafc;
    text-align:center;
    font-weight:700;
}

.rapor-table td{ text-align:left; }

.rapor-table textarea{
    width:100%;
    min-height:55px;
    border:none;
    outline:none;
    resize:none;
    padding:0;
    margin:0;
    background:transparent;
    font-size:11px;
    line-height:1.4;
    font-family:inherit;
    box-sizing:border-box;
    display:block;
}

.input-manual{
    width:50px;
    border:none;
    background:transparent;
    text-align:center;
    font-size:11px;
    outline:none;
    padding:0;
}

.print-text,
.print-input{ display:none; }

/* ================= PRINT ================= */
@media print{

    body *{ visibility:hidden; }

    #printArea,
    #printArea *{ visibility:visible; }

    #printArea{
        position:absolute;
        top:0; left:0;
        width:100%;
        background:white;
        padding:10px;
        overflow:visible !important;
    }

    .modal{
        position:static !important;
        background:none !important;
        display:block !important;
        padding:0 !important;
        overflow:visible !important;
        height:auto !important;
    }

    .modal-content{
        max-width:100% !important;
        border-radius:0 !important;
        overflow:visible !important;
        height:auto !important;
    }

    .modal-body{
        max-height:none !important;
        overflow:visible !important;
        height:auto !important;
        padding:0 !important;
    }

    .modal-header,
    .btn-print,
    .close{ display:none !important; }

    #printArea textarea{
        display:none !important;
        visibility:hidden !important;
    }

    #printArea input.input-manual{
        display:none !important;
        visibility:hidden !important;
    }

    #printArea .print-text{
        display:block !important;
        visibility:visible !important;
        white-space:pre-wrap;
        word-break:break-word;
        font-size:11px;
        line-height:1.4;
        font-family:inherit;
        min-height:20px;
    }

    #printArea .print-input{
        display:inline-block !important;
        visibility:visible !important;
        font-size:11px;
        line-height:1.4;
        font-family:inherit;
        text-align:center;
        min-width:40px;
    }

    .rapor-table tr{ page-break-inside:avoid; }

    @page{
        size:215mm 330mm portrait;
        margin:8mm;
    }
}

</style>


<div class="card">

    <h3>Rapor Siswa</h3>

    <div class="top-bar">
        <select id="kelas" disabled></select>
        <input id="search" placeholder="Cari nama siswa...">
    </div>

    <div id="loading">⏳ Memuat data siswa...</div>

    <div class="table-wrap" id="tableWrap" style="display:none;">
        <table>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="data"></tbody>
        </table>
    </div>

</div>


<!-- ================= MODAL RAPOR ================= -->
<div class="modal" id="modalRapor">

    <div class="modal-content">

        <div class="modal-header">
            <h3>📄 Cetak Rapor</h3>
            <div>
                <button class="btn-rapor btn-print" onclick="printRapor()">
                    🖨️ Cetak
                </button>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
        </div>

        <div class="modal-body">
            <div id="printArea"></div>
        </div>

    </div>

</div>

@endsection


@section('script')
<script>

let siswa         = [];
let siswaFiltered = [];
let semuaMapel    = [];
let id_kelas;
let id_guru;
let namaKelas     = '';

let dataTahunAjaran = null;
let dataSekolah     = null;
let deskripsiRapor  = {};


// ================= INIT =================
document.addEventListener('DOMContentLoaded', init);

async function init(){

    const user = JSON.parse(localStorage.getItem('user'));

    if(!user){
        location.href = '/login';
        return;
    }

    id_guru = user.id;

    await loadDataSekolah();
    await loadKelasSaya();
    await loadMapel();
    await loadSiswa();

    document.getElementById('search').addEventListener('input', filterData);
}


// ================= LOAD DATA SEKOLAH =================
async function loadDataSekolah(){
    try{
        const tahun   = await fetch('/api/tahun_ajaran/aktif1').then(r => r.json());
        dataTahunAjaran = tahun.data;

        const sekolah = await fetch('/api/konten-umum').then(r => r.json());
        dataSekolah   = sekolah.data;
    }catch(e){
        console.error(e);
    }
}


// ================= LOAD KELAS =================
async function loadKelasSaya(){
    const res  = await fetch('/api/kelas-saya/' + id_guru);
    const json = await res.json();
    const k    = json.data[0];

    id_kelas  = k.id_kelas;
    namaKelas = k.nama_kelas;

    document.getElementById('kelas').innerHTML = `<option>${namaKelas}</option>`;
}


// ================= LOAD SEMUA MAPEL =================
async function loadMapel(){
    const res  = await fetch('/api/mapel');
    const json = await res.json();
    semuaMapel = json.data;
}


// ================= LOAD SISWA =================
async function loadSiswa(){
    const res  = await fetch('/api/siswa-by-kelas/' + id_kelas);
    const json = await res.json();

    siswa         = json.data;
    siswaFiltered = siswa;

    document.getElementById('loading').style.display    = 'none';
    document.getElementById('tableWrap').style.display  = '';

    render();
}


// ================= RENDER TABLE =================
function render(){

    let html = '';

    siswaFiltered.forEach(s => {
        html += `
        <tr>
            <td>${s.nama_siswa}</td>
            <td style="text-align:center;">${s.nis ?? '-'}</td>
            <td style="text-align:center;">
                <button class="btn-rapor" onclick="openRapor(${s.id_siswa})">
                    Lihat Rapor
                </button>
            </td>
        </tr>`;
    });

    document.getElementById('data').innerHTML = html;
}


// ================= SEARCH =================
function filterData(){
    const kw = document.getElementById('search').value.toLowerCase();
    siswaFiltered = siswa.filter(s => s.nama_siswa.toLowerCase().includes(kw));
    render();
}


// ================= HITUNG AVG =================
function hitungAvg(nilaiArr){
    const arr = Object.values(nilaiArr || {});
    if(!arr.length) return 0;
    return arr.reduce((a, b) => a + Number(b), 0) / arr.length;
}



// ================= PREDIKAT =================
function predikat(n){
    n = Number(n);
    if(n >= 90) return 'A';
    if(n >= 80) return 'B';
    if(n >= 70) return 'C';
    return 'D';
}


// ===================================================
// HELPER — textarea + div.print-text
// ===================================================
function buatTextarea(key, field, defaultVal, styleExtra){
    const val = (deskripsiRapor[key]?.[field] ?? defaultVal).trim();
    const st  = styleExtra ? `style="${styleExtra}"` : '';
    return `
        <textarea ${st}
            data-key="${key}" data-field="${field}"
            oninput="autoResize(this)"
            onchange="updateDeskripsi('${key}','${field}',this.value)"
        >${val}</textarea>
        <div class="print-text"
             data-key="${key}" data-field="${field}"
        >${escHtml(val)}</div>`;
}


// ===================================================
// HELPER — input + span.print-input
// ===================================================
function buatInput(key, field, defaultVal){
    const val = String(deskripsiRapor[key]?.[field] ?? defaultVal);
    return `
        <input class="input-manual"
            value="${escAttr(val)}"
            data-key="${key}" data-field="${field}"
            onchange="updateDeskripsi('${key}','${field}',this.value);syncPrintInput(this);"
        >
        <span class="print-input"
              data-key="${key}" data-field="${field}"
        >${escHtml(val)}</span>`;
}


// ===================================================
// HELPER — escape
// ===================================================
function escHtml(s){
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
function escAttr(s){
    return String(s).replace(/"/g,'&quot;');
}


// ================= OPEN RAPOR =================
async function openRapor(id_siswa){

    const s = siswa.find(x => x.id_siswa == id_siswa);
    if(!s) return;

    let rows = '';
    let totalPengetahuan = 0;
    let totalKeterampilan = 0;

    for(let i = 0; i < semuaMapel.length; i++){

        const m = semuaMapel[i];

        // nilai tugas
        const nilaiJson = await fetch(
            `/api/nilai-tugas-kelas/${id_kelas}/${m.id_mapel}`
        ).then(r => r.json());

        let totalTugas = 0, jumlahTugas = 0;
        nilaiJson.data.forEach(n => {
            if(n.id_siswa == id_siswa){ totalTugas += Number(n.nilai); jumlahTugas++; }
        });
        const avg = jumlahTugas > 0 ? totalTugas / jumlahTugas : 0;

        // uts / uas
        const nsJson = await fetch(
            `/api/nilai-siswa/${id_kelas}/${m.id_mapel}`
        ).then(r => r.json());

        const ns = nsJson.data.find(x => x.id_siswa == id_siswa);

        // 🔥 langsung dari database
        const total = Number(ns?.total ?? 0);

        const pred = predikat(total);
        const key   = `${id_siswa}_${m.id_mapel}`;

        if(!deskripsiRapor[key]){

            deskripsiRapor[key] = {

                nilai_pengetahuan : total,

                // 🔥 dari database
                pengetahuan :
                    ns?.deskripsi_pengetahuan ??
                    `${s.nama_siswa} cukup baik dalam memahami materi ${m.nama_mapel}`,

                // 🔥 dari database
                keterampilan :
                    ns?.deskripsi_keterampilan ??
                    `${s.nama_siswa} cukup baik dalam keterampilan mata pelajaran ${m.nama_mapel}`,

                // 🔥 dari database
                nilai_keterampilan :
                    ns?.nilai_keterampilan ?? total,

                // predikat tetap otomatis
                predikat_keterampilan :
                    predikat(ns?.nilai_keterampilan ?? total)
            };
        }

        totalPengetahuan  += Number(deskripsiRapor[key].nilai_pengetahuan ?? total);
        totalKeterampilan += Number(deskripsiRapor[key].nilai_keterampilan ?? total);

        rows += `
<tr>

    <td>${i+1}</td>

    <td>${m.nama_mapel}</td>

    <td style="text-align:center;">
        ${total}
    </td>

    <td style="text-align:center;">
        ${pred}
    </td>

    <!-- DESKRIPSI PENGETAHUAN -->
    <td>
        <div
            style="
                min-height:55px;
                white-space:pre-wrap;
                word-break:break-word;
                line-height:1.4;
            "
        >
            ${deskripsiRapor[key].pengetahuan}
        </div>
    </td>

    <!-- NILAI KETERAMPILAN -->
    <td style="text-align:center;">
        <div
            style="
                min-height:20px;
                display:flex;
                align-items:center;
                justify-content:center;
            "
        >
            ${deskripsiRapor[key].nilai_keterampilan}
        </div>
    </td>

    <!-- PREDIKAT KETERAMPILAN -->
    <td style="text-align:center;">
        <div
            style="
                min-height:20px;
                display:flex;
                align-items:center;
                justify-content:center;
            "
        >
            ${deskripsiRapor[key].predikat_keterampilan}
        </div>
    </td>

    <!-- DESKRIPSI KETERAMPILAN -->
    <td>
        <div
            style="
                min-height:55px;
                white-space:pre-wrap;
                word-break:break-word;
                line-height:1.4;
            "
        >
            ${deskripsiRapor[key].keterampilan}
        </div>
    </td>

</tr>
`;
    }

    const rataP = semuaMapel.length ? (totalPengetahuan  / semuaMapel.length).toFixed(0) : 0;
    const rataK = semuaMapel.length ? (totalKeterampilan / semuaMapel.length).toFixed(0) : 0;

    // ==================== HTML RAPOR ====================
    document.getElementById('printArea').innerHTML = `
<div class="rapor">

    <div class="rapor-title">
        <h2>RAPOR DAN PROFIL PESERTA DIDIK</h2>
    </div>

    <table class="info-rapor" style="width:100%;border-collapse:collapse;font-size:11px;">
        <tr>
            <td style="width:160px;font-weight:bold;">Nama Peserta Didik</td>
            <td style="width:10px;">:</td>
            <td style="width:320px;">${s.nama_siswa}</td>
            <td style="width:120px;">Kelas</td>
            <td style="width:10px;">:</td>
            <td>${namaKelas}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;">NIS</td>
            <td>:</td>
            <td>${s.nis ?? '-'}</td>
            <td>Semester</td>
            <td>:</td>
            <td>${dataTahunAjaran?.semester ?? '-'}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Nama Sekolah</td>
            <td>:</td>
            <td>SDN SINGKUL</td>
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td>${dataTahunAjaran?.periode ?? '-'}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Alamat Sekolah</td>
            <td>:</td>
            <td colspan="4">${dataSekolah?.alamat ?? '-'}</td>
        </tr>
    </table>


    <!-- A. SIKAP -->
    <div style="margin-top:20px;margin-bottom:10px;font-weight:bold;font-size:14px;">A. Kompetensi Sikap</div>
    <table class="rapor-table">
        <thead>
            <tr>
                <th style="color:#000;font-weight:bold;background:#fff;" width="40">No</th>
                <th style="color:#000;font-weight:bold;background:#fff;" width="200">Sikap</th>
                <th style="color:#000;font-weight:bold;background:#fff;">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:center;">1</td>
                <td>Sikap Spiritual</td>
                <td>${buatTextarea(`sikap_${id_siswa}`,'spiritual',`${s.nama_siswa} menunjukkan sikap spiritual yang baik dalam kegiatan sehari-hari.`)}</td>
            </tr>
            <tr>
                <td style="text-align:center;">2</td>
                <td>Sikap Sosial</td>
                <td>${buatTextarea(`sikap_${id_siswa}`,'sosial',`${s.nama_siswa} menunjukkan sikap sosial yang baik terhadap teman dan guru.`)}</td>
            </tr>
        </tbody>
    </table>


    <!-- B. PENGETAHUAN & KETERAMPILAN -->
    <div style="margin-top:25px;margin-bottom:8px;font-weight:bold;font-size:14px;">B. Kompetensi Pengetahuan dan Keterampilan</div>
    <div style="margin-bottom:10px;font-size:13px;">KKM Satuan Pendidikan = 70</div>
    <table class="rapor-table">
        <thead>
            <tr>
                <th style="color:#000;font-weight:bold;background:#fff;" rowspan="2">No</th>
                <th style="color:#000;font-weight:bold;background:#fff;" rowspan="2">Muatan Pelajaran</th>
                <th style="color:#000;font-weight:bold;background:#fff;" colspan="3">Pengetahuan</th>
                <th style="color:#000;font-weight:bold;background:#fff;" colspan="3">Keterampilan</th>
            </tr>
            <tr>
                <th style="color:#000;font-weight:bold;background:#fff;">Nilai</th>
                <th style="color:#000;font-weight:bold;background:#fff;">Predikat</th>
                <th style="color:#000;font-weight:bold;background:#fff;">Deskripsi</th>
                <th style="color:#000;font-weight:bold;background:#fff;">Nilai</th>
                <th style="color:#000;font-weight:bold;background:#fff;">Predikat</th>
                <th style="color:#000;font-weight:bold;background:#fff;">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            ${rows}
            <tr>
                <td colspan="2" style="text-align:center;font-weight:bold;">Jumlah</td>
                <td style="text-align:center;font-weight:bold;">${totalPengetahuan}</td>
                <td></td><td></td>
                <td style="text-align:center;font-weight:bold;">${totalKeterampilan}</td>
                <td></td><td></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;font-weight:bold;">Rata-Rata</td>
                <td style="text-align:center;font-weight:bold;">${rataP}</td>
                <td></td><td></td>
                <td style="text-align:center;font-weight:bold;">${rataK}</td>
                <td></td><td></td>
            </tr>
        </tbody>
    </table>


    <!-- C. EKSTRAKURIKULER -->
    <div style="margin-top:25px;margin-bottom:8px;font-weight:bold;font-size:14px;">C. Ekstrakurikuler</div>
    <table class="rapor-table">
        <thead>
            <tr>
                <th style="color:#000;font-weight:bold;background:#fff;" width="50">No</th>
                <th style="color:#000;font-weight:bold;background:#fff;" width="250">Kegiatan</th>
                <th style="color:#000;font-weight:bold;background:#fff;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:center;">1</td>
                <td>${buatTextarea(`extra_${id_siswa}`,'kegiatan1','')}</td>
                <td>${buatTextarea(`extra_${id_siswa}`,'keterangan1','')}</td>
            </tr>
            <tr>
                <td style="text-align:center;">2</td>
                <td>${buatTextarea(`extra_${id_siswa}`,'kegiatan2','')}</td>
                <td>${buatTextarea(`extra_${id_siswa}`,'keterangan2','')}</td>
            </tr>
        </tbody>
    </table>


    <!-- D. SARAN -->
    <div style="margin-top:25px;margin-bottom:8px;font-weight:bold;font-size:14px;color:#000;">D. Saran-Saran</div>
    <table class="rapor-table">
        <tbody>
            <tr>
                <td>${buatTextarea(`saran_${id_siswa}`,'isi',`${s.nama_siswa} diharapkan terus meningkatkan semangat belajar dan mempertahankan prestasi yang telah dicapai.`,'min-height:120px;')}</td>
            </tr>
        </tbody>
    </table>


    <!-- E. TINGGI & BERAT BADAN -->
    <div style="margin-top:25px;margin-bottom:8px;font-weight:bold;font-size:14px;color:#000;">E. Tinggi dan Berat Badan</div>
    <table class="rapor-table">
        <thead>
            <tr>
                <th style="color:#000;font-weight:bold;background:#fff;" rowspan="2" width="50">No</th>
                <th style="color:#000;font-weight:bold;background:#fff;" rowspan="2">Aspek yang dinilai</th>
                <th style="color:#000;font-weight:bold;background:#fff;" colspan="2">Semester</th>
            </tr>
            <tr>
                <th style="color:#000;font-weight:bold;background:#fff;" width="120">1</th>
                <th style="color:#000;font-weight:bold;background:#fff;" width="120">2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:center;">1</td>
                <td>Tinggi Badan</td>
                <td>${buatTextarea(`tb_${id_siswa}`,'semester1','')}</td>
                <td>${buatTextarea(`tb_${id_siswa}`,'semester2','')}</td>
            </tr>
            <tr>
                <td style="text-align:center;">2</td>
                <td>Berat Badan</td>
                <td>${buatTextarea(`bb_${id_siswa}`,'semester1','')}</td>
                <td>${buatTextarea(`bb_${id_siswa}`,'semester2','')}</td>
            </tr>
        </tbody>
    </table>


    <!-- F. KONDISI KESEHATAN -->
    <div style="margin-top:25px;margin-bottom:8px;font-weight:bold;font-size:14px;">F. Kondisi Kesehatan</div>
    <table class="rapor-table">
        <thead>
            <tr>
                <th style="color:#000;font-weight:bold;background:#fff;" width="50">No</th>
                <th style="color:#000;font-weight:bold;background:#fff;">Aspek Fisik</th>
                <th style="color:#000;font-weight:bold;background:#fff;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:center;">1</td><td>Pendengaran</td>
                <td>${buatTextarea(`kesehatan_${id_siswa}`,'pendengaran','')}</td>
            </tr>
            <tr>
                <td style="text-align:center;">2</td><td>Penglihatan</td>
                <td>${buatTextarea(`kesehatan_${id_siswa}`,'penglihatan','')}</td>
            </tr>
            <tr>
                <td style="text-align:center;">3</td><td>Gigi</td>
                <td>${buatTextarea(`kesehatan_${id_siswa}`,'gigi','')}</td>
            </tr>
        </tbody>
    </table>


    <!-- G. PRESTASI -->
    <div style="margin-top:25px;margin-bottom:8px;font-weight:bold;font-size:14px;">G. Prestasi</div>
    <table class="rapor-table">
        <thead>
            <tr>
                <th style="color:#000;font-weight:bold;background:#fff;" width="50">No</th>
                <th style="color:#000;font-weight:bold;background:#fff;">Jenis Prestasi</th>
                <th style="color:#000;font-weight:bold;background:#fff;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:center;">1</td>
                <td>${buatTextarea(`prestasi_${id_siswa}`,'prestasi1','')}</td>
                <td>${buatTextarea(`prestasi_${id_siswa}`,'keterangan1','')}</td>
            </tr>
            <tr>
                <td style="text-align:center;">2</td>
                <td>${buatTextarea(`prestasi_${id_siswa}`,'prestasi2','')}</td>
                <td>${buatTextarea(`prestasi_${id_siswa}`,'keterangan2','')}</td>
            </tr>
        </tbody>
    </table>


    <!-- H. KETIDAKHADIRAN -->
    <div style="margin-top:25px;margin-bottom:8px;font-weight:bold;font-size:14px;">H. Ketidakhadiran</div>
    <table class="rapor-table">
        <tbody>
            <tr>
                <td width="50" style="text-align:center;">1</td>
                <td width="200">Sakit</td>
                <td>${buatTextarea(`absen_${id_siswa}`,'sakit','')}</td>
            </tr>
            <tr>
                <td style="text-align:center;">2</td>
                <td>Izin</td>
                <td>${buatTextarea(`absen_${id_siswa}`,'izin','')}</td>
            </tr>
            <tr>
                <td style="text-align:center;">3</td>
                <td>Tanpa Keterangan</td>
                <td>${buatTextarea(`absen_${id_siswa}`,'alpha','')}</td>
            </tr>
        </tbody>
    </table>


    <!-- TTD -->
    <div style="margin-top:50px;display:flex;justify-content:space-between;padding:0 10px;">

        <!-- Orang Tua -->
        <div style="width:200px;text-align:left;font-size:11px;">
            <p style="margin:0 0 6px 0;">Orang Tua / Wali Murid,</p>
            <br><br><br><br>
            ${buatTextarea(`ttd_${id_siswa}`,'ortu','','text-align:left;min-height:18px;font-size:11px;width:100%;')}
        </div>

        <!-- Wali Kelas -->
        <div style="width:200px;text-align:left;font-size:11px;">
            <p style="margin:0 0 2px 0;">
                SDN Singkul,
                <span
                    contenteditable="true"
                    oninput="updateDeskripsi('ttd_${id_siswa}','tanggal',this.innerText)"
                    style="display:inline;min-width:70px;outline:none;"
                >${deskripsiRapor[`ttd_${id_siswa}`]?.tanggal ?? '-- Bulan ----'}</span>
            </p>

            <p style="margin:4px 0 0 0;">Guru Wali Kelas,</p>

            <br><br><br><br>

            <div style="padding-top:4px;text-align:left;">
                <div style="display:inline-block;">
                    <div style="font-size:11px;font-weight:bold;min-height:18px;">
                        <span
                            contenteditable="true"
                            oninput="updateDeskripsi('ttd_${id_siswa}','guru',this.innerText)"
                            style="outline:none;display:inline-block;white-space:nowrap;"
                        >${deskripsiRapor[`ttd_${id_siswa}`]?.guru ?? 'Nama Guru Kelas'}</span>
                    </div>
                    <div style="border-top:1px solid #000;width:100%;margin:2px 0;"></div>
                </div>
                <p style="margin:0;font-size:11px;text-align:left;">
                    NIP.<span
                        contenteditable="true"
                        oninput="updateDeskripsi('ttd_${id_siswa}','nip',this.innerText)"
                        style="outline:none;display:inline;"
                    >${deskripsiRapor[`ttd_${id_siswa}`]?.nip ?? 'Nip Guru'}</span>
                </p>
            </div>
        </div>

    </div>

</div>`;

    document.getElementById('modalRapor').style.display = 'flex';
}


// ================= UPDATE DESKRIPSI =================
function updateDeskripsi(key, field, value){
    if(!deskripsiRapor[key]) deskripsiRapor[key] = {};
    deskripsiRapor[key][field] = value;

    document.querySelectorAll(
        `.print-text[data-key="${key}"][data-field="${field}"]`
    ).forEach(div => { div.innerText = value; });
}


// ================= SYNC INPUT → SPAN =================
function syncPrintInput(inputEl){
    const key   = inputEl.dataset.key;
    const field = inputEl.dataset.field;
    document.querySelectorAll(
        `.print-input[data-key="${key}"][data-field="${field}"]`
    ).forEach(span => { span.innerText = inputEl.value; });
}


// ================= AUTO RESIZE =================
function autoResize(el){
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
}


// ================= PRINT =================
function printRapor(){

    document.querySelectorAll('#printArea textarea').forEach(t => {
        const key = t.dataset.key, field = t.dataset.field;
        if(!key || !field) return;
        document.querySelectorAll(
            `.print-text[data-key="${key}"][data-field="${field}"]`
        ).forEach(div => { div.innerText = t.value; });
    });

    document.querySelectorAll('#printArea input.input-manual').forEach(inp => {
        const key = inp.dataset.key, field = inp.dataset.field;
        if(!key || !field) return;
        document.querySelectorAll(
            `.print-input[data-key="${key}"][data-field="${field}"]`
        ).forEach(span => { span.innerText = inp.value; });
    });

    window.print();
}


// ================= CLOSE =================
function closeModal(){
    document.getElementById('modalRapor').style.display = 'none';
}


// ================= GLOBAL AUTO RESIZE =================
document.addEventListener('input', function(e){
    if(e.target.tagName === 'TEXTAREA') autoResize(e.target);
});

</script>
@endsection