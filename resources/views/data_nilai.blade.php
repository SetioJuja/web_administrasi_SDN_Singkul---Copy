@extends('layouts.app')

@section('title','Nilai Siswa')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --bg:#f8fafc;
    --text:#1f2937;
}

body{
    background:var(--bg);
}

/* CARD */
.card{
    background:white;
    border-radius:14px;
    padding:22px;
    border:1px solid var(--border);
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:18px;
    flex-wrap:wrap;
    gap:12px;
}

.header h3{
    margin:0;
    color:var(--primary);
    font-size:22px;
}

.subtitle{
    font-size:13px;
    color:#64748b;
    margin-top:4px;
}

/* TOP BAR */
.top-bar{
    display:flex;
    gap:10px;
    margin-bottom:18px;
    flex-wrap:wrap;
    align-items:center;
}

.top-bar .right{
    margin-left:auto;
}

select,
input{
    height:42px;
    padding:0 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    background:white;
    font-size:13px;
    color:var(--text);
}

select:focus,
input:focus{
    border-color:#2563eb;
}

/* BUTTON */
.btn-rapor{
    height:42px;
    border:none;
    background:#16a34a;
    color:white;
    padding:0 16px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
    font-weight:600;
}

.btn-rapor:hover{
    background:#15803d;
}

/* INFO */
.info{
    margin-bottom:14px;
    font-size:13px;
    color:#64748b;
}

/* TABLE */
.table-wrap{
    overflow:auto;
    border:1px solid var(--border);
    border-radius:10px;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:800px;
    font-size:13px;
}

thead{
    background:var(--primary);
    color:white;
}

th{
    padding:11px 10px;
    text-align:center;
    white-space:nowrap;
    font-weight:600;
}

td{
    padding:10px;
    border-bottom:1px solid #f1f5f9;
    text-align:center;
    color:var(--text);
}

tbody tr:nth-child(even){
    background:#fafafa;
}

td:first-child,
th:first-child{
    text-align:left;
    min-width:220px;
}

.empty{
    color:#94a3b8;
}

.total{
    font-weight:700;
}

/* LOADING */
.loading{
    padding:30px;
    text-align:center;
    color:#64748b;
    display:none;
}

/* EMPTY */
.empty-state{
    text-align:center;
    padding:35px;
    color:#64748b;
}

/* MOBILE */
@media(max-width:768px){

    .top-bar{
        flex-direction:column;
        align-items:stretch;
    }

    .top-bar .right{
        margin-left:0;
    }

    select,
    input,
    .btn-rapor{
        width:100%;
    }
}
</style>


<div class="card">

    <div class="header">

        <div>

            <h3>Data Nilai Siswa</h3>

            <div
                class="subtitle"
                id="subtitle"
            >
                Pilih mata pelajaran
            </div>

        </div>

        <button
            class="btn-rapor"
            onclick="location.href='/rapor'"
        >
            Halaman Rapor
        </button>

    </div>


    <div class="top-bar">

        <select id="kelas" disabled></select>

        <select id="mapel"></select>

        <input
            id="search"
            placeholder="Cari nama siswa..."
        >

    </div>


    <div
        class="info"
        id="info"
    ></div>


    <div
        class="loading"
        id="loading"
    >
        Memuat data...
    </div>


    <div class="table-wrap">

        <table>

            <thead>
                <tr id="headerTable"></tr>
            </thead>

            <tbody id="data">

                <tr>
                    <td
                        colspan="99"
                        class="empty-state"
                    >
                        Pilih mata pelajaran
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

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


// ================= INIT =================
document.addEventListener(
    'DOMContentLoaded',
    init
);

async function init(){

    const user = JSON.parse(
        localStorage.getItem('user')
    );

    if(!user){

        location.href = '/login';

        return;
    }

    id_guru = user.id;

    await loadKelasSaya();

    document
        .getElementById('mapel')
        .addEventListener(
            'change',
            loadData
        );

    document
        .getElementById('search')
        .addEventListener(
            'input',
            filterData
        );
}


// ================= LOAD KELAS =================
async function loadKelasSaya(){

    const res = await fetch(
        '/api/kelas-saya/' + id_guru
    );

    const json = await res.json();

    if(json.data.length === 0){

        alert('Tidak punya kelas');

        return;
    }

    const k = json.data[0];

    id_kelas = k.id_kelas;

    document.getElementById(
        'kelas'
    ).innerHTML =
        `<option>${k.nama_kelas}</option>`;

    loadMapel();
}


// ================= LOAD MAPEL =================
async function loadMapel(){

    const res = await fetch(
        '/api/mapel'
    );

    const json = await res.json();

    let html =
        '<option value="">Pilih Mata Pelajaran</option>';

    json.data.forEach(m=>{

        html += `
        <option value="${m.id_mapel}">
            ${m.nama_mapel}
        </option>
        `;
    });

    document.getElementById(
        'mapel'
    ).innerHTML = html;
}


// ================= LOAD DATA =================
async function loadData(){

    id_mapel =
        document.getElementById(
            'mapel'
        ).value;

    if(!id_mapel){

        resetTable();

        return;
    }

    showLoading(true);

    // siswa
    const s = await fetch(
        '/api/siswa-by-kelas/' + id_kelas
    ).then(r=>r.json());

    siswa = s.data;

    siswaFiltered = siswa;

    // tugas
    const t = await fetch(
        '/api/tugas-by-mapel/' + id_mapel
    ).then(r=>r.json());

    tugas = t.data;

    // nilai tugas
    await loadNilai();

    // nilai siswa
    const ns = await fetch(
        `/api/nilai-siswa/${id_kelas}/${id_mapel}`
    ).then(r=>r.json());

    nilaiSiswa = {};

    ns.data.forEach(n=>{

        nilaiSiswa[n.id_siswa] = n;
    });

    // subtitle
    const namaMapel =
        document.getElementById(
            'mapel'
        ).selectedOptions[0].text;

    document.getElementById(
        'subtitle'
    ).innerText =
        `Mata Pelajaran : ${namaMapel}`;

    render();

    showLoading(false);
}


// ================= LOAD NILAI =================
async function loadNilai(){

    nilai = {};

    const res = await fetch(
        `/api/nilai-tugas-kelas/${id_kelas}/${id_mapel}`
    );

    const json = await res.json();

    json.data.forEach(n=>{

        if(!nilai[n.id_siswa]){

            nilai[n.id_siswa] = {};
        }

        nilai[n.id_siswa][n.id_tugas] =
            n.nilai;
    });
}


// ================= AVG =================
function hitungAvg(id){

    const data =
        nilai[id] || {};

    const arr =
        Object.values(data);

    if(arr.length === 0){

        return 0;
    }

    const total = arr.reduce(
        (a,b)=>a+Number(b),
        0
    );

    return total / arr.length;
}


// ================= RENDER =================
function render(){

    renderHeader();

    renderBody();

    renderInfo();
}


// ================= HEADER =================
function renderHeader(){

    let header =
        '<th>Nama Siswa</th>';

    tugas.forEach(t=>{

        header += `
        <th title="${t.judul_tugas}">
            ${shortText(
                t.judul_tugas,
                12
            )}
        </th>
        `;
    });

    header += `
        <th>Rata rata Tugas</th>
        <th>UTS</th>
        <th>UAS</th>
        <th>Total</th>
        <th>Keterampilan</th>
    `;

    document.getElementById(
        'headerTable'
    ).innerHTML = header;
}


// ================= BODY =================
function renderBody(){

    let html = '';

    if(siswaFiltered.length === 0){

        html = `
        <tr>
            <td
                colspan="99"
                class="empty-state"
            >
                Data tidak ditemukan
            </td>
        </tr>
        `;

        document.getElementById(
            'data'
        ).innerHTML = html;

        return;
    }

    siswaFiltered.forEach(s=>{

        let avg =
            hitungAvg(s.id_siswa);

        let uts =
            nilaiSiswa[s.id_siswa]
            ?.nilai_uts ?? '-';

        let uas =
            nilaiSiswa[s.id_siswa]
            ?.nilai_uas ?? '-';

        let total =
            Number(
                nilaiSiswa[s.id_siswa]
                ?.total ?? 0
            );

        let keterampilan =
            nilaiSiswa[s.id_siswa]
            ?.nilai_keterampilan ?? '-';

        let row = `
        <tr>

            <td>
                ${s.nama_siswa}
            </td>
        `;

        // nilai tugas
        tugas.forEach(t=>{

            let val =
                nilai[s.id_siswa]
                ?.[t.id_tugas] ?? '-';

            row += `
            <td class="${
                val === '-'
                ? 'empty'
                : ''
            }">
                ${val}
            </td>
            `;
        });

        row += `

        <td>
            ${avg.toFixed(2)}
        </td>

        <td>
            ${uts}
        </td>

        <td>
            ${uas}
        </td>

        <td class="total">
            ${total.toFixed(2)}
        </td>

        <td>
            ${keterampilan}
        </td>

        </tr>
        `;

        html += row;
    });

    document.getElementById(
        'data'
    ).innerHTML = html;
}


// ================= INFO =================
function renderInfo(){

    document.getElementById(
        'info'
    ).innerHTML =
        `Menampilkan ${siswaFiltered.length} siswa`;
}


// ================= SEARCH =================
function filterData(){

    const keyword =
        document.getElementById(
            'search'
        )
        .value
        .toLowerCase();

    const rows =
        document.querySelectorAll(
            '#data tr'
        );

    let visible = 0;

    rows.forEach(row=>{

        const nama =
            row.children[0]
            ?.innerText
            ?.toLowerCase() || '';

        const show =
            nama.includes(keyword);

        row.style.display =
            show ? '' : 'none';

        if(show){
            visible++;
        }
    });

    document.getElementById(
        'info'
    ).innerHTML =
        `Menampilkan ${visible} siswa`;
}


// ================= SHORT TEXT =================
function shortText(text, limit){

    if(text.length <= limit){

        return text;
    }

    return text.substring(0,limit) + '...';
}


// ================= RESET =================
function resetTable(){

    document.getElementById(
        'subtitle'
    ).innerText =
        'Pilih mata pelajaran';

    document.getElementById(
        'headerTable'
    ).innerHTML = '';

    document.getElementById(
        'info'
    ).innerHTML = '';

    document.getElementById(
        'data'
    ).innerHTML = `
        <tr>
            <td
                colspan="99"
                class="empty-state"
            >
                Pilih mata pelajaran
            </td>
        </tr>
    `;
}


// ================= LOADING =================
function showLoading(status=true){

    document.getElementById(
        'loading'
    ).style.display =
        status ? 'block':'none';
}

</script>

@endsection