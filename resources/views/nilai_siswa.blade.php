@extends('layouts.app')

@section('title','Input Nilai')

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
    margin-bottom:15px;
    color:var(--primary);
}

/* FILTER */
.top-bar{
    margin-bottom:15px;
}

select{
    padding:10px;
    border-radius:8px;
    border:1px solid var(--border);
}

/* TABLE */
.table-wrap{
    overflow:auto;
}

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
    padding:8px;
    border-bottom:1px solid var(--border);
    text-align:center;
}

tr:hover{
    background:#f9fafb;
}

/* INPUT NILAI */
input{
    width:60px;
    padding:5px;
    border:1px solid var(--border);
    border-radius:6px;
    text-align:center;
}

/* BUTTON */
.btn-simpan{
    background:var(--primary);
    color:white;
    border:none;
    padding:6px 10px;
    border-radius:6px;
    cursor:pointer;
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
</style>

<div class="card">

<h3>📝 Input Nilai Siswa</h3>

<div class="top-bar">
    <select id="filter"></select>
</div>

<div class="table-wrap">
<table>
<thead>
<tr id="header"></tr>
</thead>
<tbody id="data"></tbody>
</table>
</div>

</div>

@endsection


@section('script')
<script>

let siswa = [];
let tugas = [];
let nilai = {};
let nilaiSiswa = {};

let id_kelas, id_mapel;
let id_guru;


// INIT
document.addEventListener('DOMContentLoaded', init);

async function init(){

    const user = JSON.parse(localStorage.getItem('user'));

    if(!user){
        location.href='/login';
        return;
    }

    id_guru = user.id;

    await loadJadwal();

    document.getElementById('filter')
        .addEventListener('change', loadSiswa);
}


// LOAD JADWAL
async function loadJadwal(){

    const res = await fetch('/api/jadwal-guru/' + id_guru);
    const json = await res.json();

    let html = '<option value="">Pilih Kelas & Mapel</option>';

    json.data.forEach(j=>{
        html += `
        <option value="${j.id_kelas}|${j.id_mapel}">
            ${j.kelas.nama_kelas} - ${j.mapel.nama_mapel}
        </option>`;
    });

    document.getElementById('filter').innerHTML = html;
}


// LOAD SISWA
async function loadSiswa(){

    const val = document.getElementById('filter').value;
    if(!val) return;

    [id_kelas, id_mapel] = val.split('|');

    const s = await fetch('/api/siswa-by-kelas/'+id_kelas).then(r=>r.json());
    siswa = s.data;

    const t = await fetch('/api/tugas-by-mapel/'+id_mapel).then(r=>r.json());
    tugas = t.data;

    await loadNilai();

    const ns = await fetch(`/api/nilai-siswa/${id_kelas}/${id_mapel}`).then(r=>r.json());

    nilaiSiswa = {};
    ns.data.forEach(n=>{
        nilaiSiswa[n.id_siswa] = n;
    });

    render();
}


// LOAD NILAI (sementara masih loop)
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

    const arr = Object.values(nilai[id] || {});
    if(arr.length === 0) return 0;

    return arr.reduce((a,b)=>a+Number(b),0) / arr.length;
}

function hitungTotal(tugas, uts, uas){

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

    header += '<th>AVG</th><th>UTS</th><th>UAS</th><th>TOTAL</th><th>Aksi</th>';

    document.getElementById('header').innerHTML = header;

    let html='';

    siswa.forEach(s=>{

        let avg = hitungAvg(s.id_siswa);

        let uts = nilaiSiswa[s.id_siswa]?.nilai_uts ?? '';
        let uas = nilaiSiswa[s.id_siswa]?.nilai_uas ?? '';
        let total = hitungTotal(avg, uts, uas);

        let row = `<tr data-id="${s.id_siswa}">
        <td style="text-align:left">${s.nama_siswa}</td>`;

        tugas.forEach(t=>{

            let val = nilai[s.id_siswa]?.[t.id_tugas] ?? '';

            row += `<td>
                <input class="nilai-tugas"
                    data-siswa="${s.id_siswa}"
                    data-tugas="${t.id_tugas}"
                    value="${val}">
            </td>`;
        });

        row += `
        <td>${avg.toFixed(2)}</td>
        <td><input class="uts" value="${uts}"></td>
        <td><input class="uas" value="${uas}"></td>
        <td class="total ${total >= 75 ? 'good':'bad'}">${total}</td>
        <td><button class="btn-simpan">Simpan</button></td>
        </tr>`;

        html += row;
    });

    document.getElementById('data').innerHTML = html;

    bindEvent();
}


// EVENT
function bindEvent(){

    document.querySelectorAll('.nilai-tugas').forEach(inp=>{
        inp.addEventListener('input', async (e)=>{

            const id_siswa = e.target.dataset.siswa;
            const id_tugas = e.target.dataset.tugas;
            const val = e.target.value;

            await fetch('/api/nilai-tugas',{
                method:'POST',
                headers:{'Content-Type':'application/json'},
                body: JSON.stringify({id_siswa,id_tugas,nilai:val})
            });

            updateTotalRow(id_siswa);
        });
    });

    document.querySelectorAll('.btn-simpan').forEach(btn=>{
        btn.onclick = async () => {

            const tr = btn.closest('tr');
            const id = tr.dataset.id;

            const uts = tr.querySelector('.uts').value;
            const uas = tr.querySelector('.uas').value;

            await fetch('/api/nilai-siswa',{
                method:'POST',
                headers:{'Content-Type':'application/json'},
                body: JSON.stringify({
                    id_siswa:id,
                    id_mapel,
                    id_kelas,
                    nilai_uts:uts,
                    nilai_uas:uas
                })
            });

            updateTotalRow(id);
        };
    });
}


// UPDATE TOTAL
function updateTotalRow(id){

    const tr = document.querySelector(`tr[data-id="${id}"]`);

    const uts = tr.querySelector('.uts').value;
    const uas = tr.querySelector('.uas').value;

    const avg = hitungAvg(id);
    const total = hitungTotal(avg, uts, uas);

    const el = tr.querySelector('.total');

    el.innerText = total;
    el.className = 'total ' + (total >= 75 ? 'good':'bad');
}

</script>
@endsection