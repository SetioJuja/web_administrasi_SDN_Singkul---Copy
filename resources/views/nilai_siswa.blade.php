@extends('layouts.app')

@section('title','Input Nilai')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<style>

:root{
    --primary:#0a3d62;
    --border:#e2e8f0;
    --success:#16a34a;
    --danger:#dc2626;
    --warning:#f59e0b;
}

.card{
    background:white;
    border-radius:18px;
    padding:20px;
    box-shadow:0 10px 30px rgba(0,0,0,.05);
}

.card h3{
    margin-bottom:18px;
    color:var(--primary);
    font-size:22px;
}

.top-bar{
    display:flex;
    gap:10px;
    margin-bottom:16px;
}

select,
#search{
    border:1px solid var(--border);
    border-radius:10px;
    padding:10px;
    background:white;
}

#search{
    width:100%;
}

.info-bobot{
    display:flex;
    gap:8px;
    margin-bottom:16px;
    flex-wrap:wrap;
}

.badge-bobot{
    background:#eff6ff;
    color:#1d4ed8;
    padding:8px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
}

.table-wrap{
    overflow:auto;
    border:1px solid var(--border);
    border-radius:14px;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:1100px;
}

thead{
    background:var(--primary);
    color:white;
}

th{
    padding:10px 6px;
    font-size:11px;
    text-transform:uppercase;
    letter-spacing:.3px;
    white-space:nowrap;
}

td{
    padding:6px;
    border-bottom:1px solid var(--border);
    text-align:center;
    background:white;
}

tbody tr:hover td{
    background:#f8fafc;
}

td:first-child,
th:first-child{
    position:sticky;
    left:0;
    z-index:3;
}

th:first-child{
    background:var(--primary);
}

td:first-child{
    background:white;
}

.input-nilai{
    width:55px;
    height:34px;
    border:1px solid var(--border);
    border-radius:7px;
    text-align:center;
    outline:none;
    font-size:12px;
}

.input-nilai:focus{
    border-color:#2563eb;
}

.total{
    font-weight:bold;
}

.good{
    color:var(--success);
}

.bad{
    color:var(--danger);
}

.action-group{
    display:flex;
    justify-content:center;
    gap:6px;
}

.btn{
    height:34px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    color:white;
    font-size:13px;
    padding:0 12px;
    display:flex;
    align-items:center;
    gap:5px;
}

.btn-detail{
    background:#2563eb;
}

.btn-simpan{
    background:var(--success);
}

.status{
    font-size:11px;
    font-weight:600;
    padding:5px 10px;
    border-radius:20px;
    display:inline-block;
}

.status-unsaved{
    background:#fef3c7;
    color:#92400e;
}

.status-saved{
    background:#dcfce7;
    color:#166534;
}

.unsaved-row td{
    background:#fff7ed !important;
}

.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
}

.modal-content{
    width:500px;
    background:white;
    border-radius:18px;
    padding:24px;
}

.modal-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.modal-header h4{
    margin:0;
    color:var(--primary);
}

.close{
    font-size:24px;
    cursor:pointer;
}

.form-group{
    margin-bottom:16px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
}

textarea{
    width:100%;
    border:1px solid var(--border);
    border-radius:10px;
    padding:12px;
    resize:none;
    min-height:120px;
    outline:none;
}

.btn-modal-save{
    width:100%;
    border:none;
    background:var(--primary);
    color:white;
    padding:12px;
    border-radius:10px;
    font-weight:600;
    cursor:pointer;
}

</style>

<div class="card">

    <h3>Input Nilai Siswa</h3>

    <div class="top-bar">

        <select id="filter"></select>

        <input
            id="search"
            placeholder="Cari nama siswa..."
        >

    </div>

    <div
        class="info-bobot"
        id="infoBobot"
    ></div>

    <div class="table-wrap">

        <table>

            <thead>
                <tr id="header"></tr>
            </thead>

            <tbody id="data"></tbody>

        </table>

    </div>

</div>


<div class="modal" id="modalDesk">

    <div class="modal-content">

        <div class="modal-header">

            <h4>Deskripsi Nilai</h4>

            <span
                class="close"
                onclick="closeModal()"
            >
                &times;
            </span>

        </div>

        <div class="form-group">

            <label>
                Deskripsi Pengetahuan
            </label>

            <textarea
                id="modalDeskPengetahuan"
            ></textarea>

        </div>

        <div class="form-group">

            <label>
                Deskripsi Keterampilan
            </label>

            <textarea
                id="modalDeskKeterampilan"
            ></textarea>

        </div>

        <button
            class="btn-modal-save"
            onclick="saveModalData()"
        >
            Simpan Deskripsi
        </button>

    </div>

</div>

@endsection

@section('script')

<script>

let siswa = [];
let tugas = [];
let nilai = {};
let nilaiSiswa = {};
let komponen = [];

let currentSiswa = null;

let bobot = {
    tugas:0,
    uts:0,
    uas:0
};

let id_kelas;
let id_mapel;
let id_guru;

document.addEventListener(
    'DOMContentLoaded',
    init
);

async function init(){

    const user = JSON.parse(
        localStorage.getItem('user')
    );

    if(!user){
        location.href='/login';
        return;
    }

    id_guru = user.id;

    await loadJadwal();

    document.getElementById('filter')
        .addEventListener(
            'change',
            loadSiswa
        );

    document.getElementById('search')
        .addEventListener(
            'input',
            filterSiswa
        );
}

async function loadJadwal(){

    const res = await fetch(
        '/api/jadwal-guru/' + id_guru
    );

    const json = await res.json();

    let html =
        '<option value="">Pilih Kelas & Mapel</option>';

    json.data.forEach(j=>{

        html += `
        <option value="${j.id_kelas}|${j.id_mapel}">
            ${j.kelas.nama_kelas}
            -
            ${j.mapel.nama_mapel}
        </option>
        `;
    });

    document.getElementById('filter')
        .innerHTML = html;
}

async function loadSiswa(){

    const val =
        document.getElementById('filter')
        .value;

    if(!val) return;

    [id_kelas, id_mapel] =
        val.split('|');

    const s = await fetch(
        '/api/siswa-by-kelas/' + id_kelas
    ).then(r=>r.json());

    siswa = s.data;

    const t = await fetch(
        `/api/tugas-by-mapel/${id_mapel}?id_guru=${id_guru}&id_kelas=${id_kelas}`
    ).then(r=>r.json());

    tugas = t.data || [];

    const k = await fetch(
        '/api/komponen-penilaian-guru/' + id_guru
    ).then(r=>r.json());

    komponen = k.data.filter(x =>
        x.id_mapel == id_mapel
    );

    bobot = {
        tugas:0,
        uts:0,
        uas:0
    };

    komponen.forEach(k=>{

        let nama =
            k.nama_komponen.toLowerCase();

        if(nama.includes('tugas')){
            bobot.tugas = k.bobot / 100;
        }

        if(nama.includes('uts')){
            bobot.uts = k.bobot / 100;
        }

        if(nama.includes('uas')){
            bobot.uas = k.bobot / 100;
        }
    });

    renderBobot();

    await loadNilai();

    const ns = await fetch(
        `/api/nilai-siswa/${id_kelas}/${id_mapel}`
    ).then(r=>r.json());

    nilaiSiswa = {};

    ns.data.forEach(n=>{

        nilaiSiswa[n.id_siswa] = n;
    });

    console.log(nilaiSiswa);

    render();
}

function renderBobot(){

    let html = '';

    komponen.forEach(k=>{

        html += `
        <div class="badge-bobot">
            ${k.nama_komponen}
            :
            ${k.bobot}%
        </div>
        `;
    });

    document.getElementById(
        'infoBobot'
    ).innerHTML = html;
}

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

function hitungAvg(id){

    const arr =
        Object.values(nilai[id] || {});

    if(arr.length === 0) return 0;

    return arr.reduce((a,b)=>
        a + Number(b),0
    ) / arr.length;
}

function hitungTotal(avg, uts, uas){

    avg = Number(avg) || 0;
    uts = Number(uts) || 0;
    uas = Number(uas) || 0;

    return (
        (avg * bobot.tugas) +
        (uts * bobot.uts) +
        (uas * bobot.uas)
    ).toFixed(2);
}

function render(){

    let header = `
    <th>Nama Siswa</th>
    `;

    tugas.forEach(t=>{

        header += `
        <th>${t.judul_tugas}</th>
        `;
    });

    header += `
    <th>Rata-rata</th>
    <th>UTS</th>
    <th>UAS</th>
    <th>Total</th>
    <th>Keterampilan</th>
    <th>Status</th>
    <th>Aksi</th>
    `;

    document.getElementById(
        'header'
    ).innerHTML = header;

    let html = '';

    siswa.forEach(s=>{

        let avg =
            hitungAvg(s.id_siswa);

        let uts =
            nilaiSiswa[s.id_siswa]
            ?.nilai_uts ?? 0;

        let uas =
            nilaiSiswa[s.id_siswa]
            ?.nilai_uas ?? 0;

        let total =
            hitungTotal(avg, uts, uas);

        let nilaiKeterampilan =
            nilaiSiswa[s.id_siswa]
            ?.nilai_keterampilan ?? '';

        let row = `
        <tr data-id="${s.id_siswa}">

            <td
                style="
                    text-align:left;
                    font-weight:600;
                "
            >
                ${s.nama_siswa}
            </td>
        `;

        tugas.forEach(t=>{

            let val =
                nilai[s.id_siswa]
                ?.[t.id_tugas] ?? '';

            row += `
            <td>

                <input
                    class="
                        input-nilai
                        nilai-tugas
                    "
                    data-siswa="${s.id_siswa}"
                    data-tugas="${t.id_tugas}"
                    value="${val}"
                >

            </td>
            `;
        });

        row += `

        <td>
            ${avg.toFixed(2)}
        </td>

        <td>

            <input
                class="
                    input-nilai
                    uts
                "
                value="${uts}"
            >

        </td>

        <td>

            <input
                class="
                    input-nilai
                    uas
                "
                value="${uas}"
            >

        </td>

        <td class="
            total
            ${total >= 75 ? 'good':'bad'}
        ">
            ${total}
        </td>

        <td>

            <input
                class="
                    input-nilai
                    nilai-keterampilan
                "
                value="${nilaiKeterampilan}"
            >

        </td>

        <td>

            <span class="
                status
                status-saved
            ">
                Tersimpan
            </span>

        </td>

        <td>

            <div class="action-group">

                <button
                    class="
                        btn
                        btn-detail
                    "
                    onclick="
                        openModal(
                            ${s.id_siswa}
                        )
                    "
                >
                    <i class='bx bx-notepad'></i>
                    Deskripsi
                </button>

                <button class="
                    btn
                    btn-simpan
                ">
                    <i class='bx bx-save'></i>
                    Simpan
                </button>

            </div>

        </td>

        </tr>
        `;

        html += row;
    });

    document.getElementById(
        'data'
    ).innerHTML = html;

    bindEvent();
}

function filterSiswa(){

    const key =
        document.getElementById('search')
        .value
        .toLowerCase();

    document
    .querySelectorAll('#data tr')
    .forEach(tr=>{

        const nama =
            tr.children[0]
            .innerText
            .toLowerCase();

        tr.style.display =
            nama.includes(key)
            ? ''
            : 'none';
    });
}

function bindEvent(){

    document
    .querySelectorAll('input')
    .forEach(inp => {

        inp.addEventListener(
            'input',
            () => {

            const tr =
                inp.closest('tr');

            if(!tr) return;

            tr.classList.add(
                'unsaved-row'
            );

            tr.querySelector('.status')
                .className =
                'status status-unsaved';

            tr.querySelector('.status')
                .innerText =
                'Belum Disimpan';

        });

    });

    document
    .querySelectorAll('.btn-simpan')
    .forEach(btn => {

        btn.onclick = async () => {

            const tr =
                btn.closest('tr');

            const id =
                tr.dataset.id;

            const uts =
                tr.querySelector('.uts')
                .value;

            const uas =
                tr.querySelector('.uas')
                .value;

            const nilai_keterampilan =
                tr.querySelector(
                    '.nilai-keterampilan'
                ).value;

            const dataDesk =
                nilaiSiswa[id] || {};

            await fetch(
                '/api/nilai-siswa',
            {

                method:'POST',

                headers:{
                    'Content-Type':
                    'application/json'
                },

                body: JSON.stringify({

                    id_siswa:id,
                    id_mapel,
                    id_kelas,

                    nilai_uts:uts,
                    nilai_uas:uas,

                    nilai_keterampilan,

                    deskripsi_pengetahuan:
                        dataDesk
                        .deskripsi_pengetahuan || '',

                    deskripsi_keterampilan:
                        dataDesk
                        .deskripsi_keterampilan || ''
                })
            });

            tr.classList.remove(
                'unsaved-row'
            );

            tr.querySelector('.status')
                .className =
                'status status-saved';

            tr.querySelector('.status')
                .innerText =
                'Tersimpan';

            updateTotalRow(id);
        };

    });

}

function updateTotalRow(id){

    const tr =
        document.querySelector(
            `tr[data-id="${id}"]`
        );

    const uts =
        tr.querySelector('.uts')
        .value;

    const uas =
        tr.querySelector('.uas')
        .value;

    const avg =
        hitungAvg(id);

    const total =
        hitungTotal(avg, uts, uas);

    const el =
        tr.querySelector('.total');

    el.innerText = total;

    el.className =
        'total ' +
        (
            total >= 75
            ? 'good'
            : 'bad'
        );
}

function openModal(id){

    currentSiswa = id;

    const data =
        nilaiSiswa[id] || {};

    document.getElementById(
        'modalDeskPengetahuan'
    ).value =
        data.deskripsi_pengetahuan || '';

    document.getElementById(
        'modalDeskKeterampilan'
    ).value =
        data.deskripsi_keterampilan || '';

    document.getElementById(
        'modalDesk'
    ).style.display = 'flex';
}

function closeModal(){

    document.getElementById(
        'modalDesk'
    ).style.display = 'none';
}

function saveModalData(){

    if(!currentSiswa) return;

    nilaiSiswa[currentSiswa] = {

        ...(nilaiSiswa[currentSiswa] || {}),

        deskripsi_pengetahuan:
            document.getElementById(
                'modalDeskPengetahuan'
            ).value,

        deskripsi_keterampilan:
            document.getElementById(
                'modalDeskKeterampilan'
            ).value
    };

    const tr =
        document.querySelector(
            `tr[data-id="${currentSiswa}"]`
        );

    tr.classList.add(
        'unsaved-row'
    );

    tr.querySelector('.status')
        .className =
        'status status-unsaved';

    tr.querySelector('.status')
        .innerText =
        'Belum Disimpan';

    closeModal();
}

</script>

@endsection