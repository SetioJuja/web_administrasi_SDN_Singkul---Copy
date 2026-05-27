@extends('layouts.app')

@section('title','Tugas & Nilai')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --success:#16a34a;
    --danger:#dc2626;
    --bg:#f4f7fb;
}

.card{
    background:white;
    border-radius:16px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

h3{
    margin-bottom:20px;
    color:var(--primary);
}

.tab{
    display:flex;
    gap:10px;
    margin-bottom:20px;
}

.tab-btn{
    padding:10px 18px;
    border-radius:10px;
    border:none;
    cursor:pointer;
    background:#f1f5f9;
    color:#333;
    font-weight:500;
    transition:0.3s;
}

.tab-btn.active{
    background:var(--primary);
    color:white;
}

.tab-btn:hover{
    transform:translateY(-2px);
}

input, select{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    margin-bottom:10px;
    width:100%;
}

input:focus, select:focus{
    border-color:var(--primary);
}

.form-row{
    display:flex;
    gap:10px;
}

button{
    padding:10px 16px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    background:var(--primary);
    color:white;
    transition:0.3s;
    white-space:nowrap;
}

button:hover{
    opacity:0.9;
}

.btn-danger{
    background:var(--danger);
}

.btn-warning{
    background:#d97706;
}

.btn-success{
    background:var(--success);
}

.btn-sm{
    padding:5px 10px;
    font-size:12px;
    border-radius:6px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
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

th{
    font-weight:600;
}

tbody tr:hover{
    background:#f9fafb;
}

.nilai{
    width:70px;
    text-align:center;
    padding:6px;
}

.avg{
    font-weight:bold;
    color:var(--success);
}

.table-wrap{
    overflow:auto;
}

.section{
    animation:fade 0.3s ease;
}

@keyframes fade{
    from{opacity:0; transform:translateY(5px);}
    to{opacity:1; transform:translateY(0);}
}

/* ===== MODAL ===== */
.modal-overlay{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.4);
    z-index:1000;
    align-items:center;
    justify-content:center;
}

.modal-overlay.show{
    display:flex;
}

.modal{
    background:white;
    border-radius:16px;
    padding:28px;
    width:100%;
    max-width:440px;
    box-shadow:0 20px 60px rgba(0,0,0,0.15);
    animation:fade 0.2s ease;
}

.modal h4{
    margin-bottom:16px;
    color:var(--primary);
    font-size:18px;
}

.modal input,
.modal select{
    margin-bottom:12px;
}

.modal-footer{
    display:flex;
    gap:10px;
    justify-content:flex-end;
    margin-top:16px;
}

.modal-footer button{
    min-width:90px;
}
</style>

<div class="card">

<h3>Tugas & Nilai</h3>

<div class="tab">
<button class="tab-btn active" data-tab="tugas">Kelola Tugas</button>
<button class="tab-btn" data-tab="nilai">Input Nilai</button>
</div>

<!-- ===== TUGAS ===== -->
<div id="tugas" class="section" style="display:block">

<select id="kelas_tugas"></select>
<select id="id_komponen"></select>

<div class="form-row">
<input id="judul" placeholder="Judul Tugas">
<input id="tanggal" type="date">
<button id="btnTambahTugas">Tambah</button>
</div>

<table>
<thead>
<tr>
<th>Mapel</th>
<th>Judul</th>
<th>Tanggal</th>
<th>Aksi</th>
</tr>
</thead>
<tbody id="tugasData"></tbody>
</table>

</div>

<!-- ===== NILAI ===== -->
<div id="nilai" class="section">

<select id="filter_kelas"></select>
<h4 id="infoMapel"></h4>

<div class="table-wrap">
<table>
<thead>
<tr id="header"></tr>
</thead>
<tbody id="body"></tbody>
</table>
</div>

</div>

</div>

<!-- ===== MODAL EDIT ===== -->
<div class="modal-overlay" id="modalEdit">
<div class="modal">
    <h4>Edit Tugas</h4>
    <input id="edit_judul" placeholder="Judul Tugas">
    <input id="edit_tanggal" type="date">
    <select id="edit_komponen"></select>
    <div class="modal-footer">
        <button class="btn-danger" id="btnBatalEdit" onclick="closeModal()">Batal</button>
        <button class="btn-success" id="btnSimpanEdit">Simpan</button>
    </div>
</div>
</div>

<!-- ===== MODAL HAPUS ===== -->
<div class="modal-overlay" id="modalHapus">
<div class="modal">
    <h4>Hapus Tugas</h4>
    <p id="konfirmasiHapus" style="margin-bottom:16px;color:#555;"></p>
    <div class="modal-footer">
        <button onclick="closeModal()">Batal</button>
        <button class="btn-danger" id="btnKonfirmasiHapus">Hapus</button>
    </div>
</div>
</div>

@endsection


@section('script')
<script>

let id_guru;
let siswa = [];
let tugas = [];
let nilaiTugas = {};
let id_kelas, id_mapel, id_komponen;
let komponen = [];
let editId = null;
let hapusId = null;

// ================= INIT =================
document.addEventListener('DOMContentLoaded', init);

async function init(){

    const user = JSON.parse(localStorage.getItem('user'));

    if(!user){
        alert('Login dulu');
        location.href='/login';
        return;
    }

    id_guru = user.id;

    bindTab();
    await loadKomponen();
    await loadKelas();
    await loadKelasTugas();
    await loadTugas();

    document.getElementById('kelas_tugas')
        .addEventListener('change', function(){
            id_kelas = this.value;
            loadTugas();
        });

    document.getElementById('id_komponen')
        .addEventListener('change', changeKomponen);

    document.getElementById('filter_kelas')
        .addEventListener('change', loadSiswa);

    document.getElementById('btnTambahTugas')
        .addEventListener('click', tambahTugas);

    document.getElementById('btnSimpanEdit')
        .addEventListener('click', simpanEdit);

    document.getElementById('btnKonfirmasiHapus')
        .addEventListener('click', konfirmasiHapus);
}


// ================= TAB =================
function bindTab(){
    document.querySelectorAll('.tab-btn').forEach(btn=>{
        btn.onclick = () => {
            document.querySelectorAll('.section').forEach(s=>s.style.display='none');
            document.getElementById(btn.dataset.tab).style.display='block';
            document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
        };
    });
}


// ================= LOAD KOMPONEN =================
async function loadKomponen(){

    const res = await fetch('/api/komponen-penilaian-guru/' + id_guru);
    const json = await res.json();

    komponen = json.data || [];

    let html='';

    komponen.forEach(k=>{
        html += `<option value="${k.id_komponen}" data-mapel="${k.id_mapel}">
            ${k.nama_komponen} (${k.mapel?.nama_mapel ?? '-'})
        </option>`;
    });

    document.getElementById('id_komponen').innerHTML = html;
    document.getElementById('edit_komponen').innerHTML = html;

    if(komponen.length){
        id_komponen = komponen[0].id_komponen;
        id_mapel = komponen[0].id_mapel;
    }
}


// ================= CHANGE =================
function changeKomponen(){

    let el = document.getElementById('id_komponen');

    id_komponen = el.value;
    id_mapel = el.selectedOptions[0].dataset.mapel;

    loadTugas();
}

async function loadKelasTugas(){

    const res = await fetch('/api/jadwal-guru/' + id_guru);
    const json = await res.json();

    let html = '';

    (json.data || []).forEach(j=>{
        html += `<option value="${j.id_kelas}">
            ${j.kelas.nama_kelas} - ${j.mapel.nama_mapel}
        </option>`;
    });

    document.getElementById('kelas_tugas').innerHTML = html;

    if(json.data.length){
        id_kelas = json.data[0].id_kelas;
    }
}


// ================= LOAD TUGAS =================
async function loadTugas(){

    if(!id_mapel) return;

    id_kelas = document.getElementById('kelas_tugas')?.value || id_kelas;

    const res = await fetch(`/api/tugas-by-mapel/${id_mapel}?id_guru=${id_guru}&id_kelas=${id_kelas}`);
    const json = await res.json();

    tugas = json.data || [];

    let html='';

    tugas.forEach(t=>{
        html += `
        <tr>
            <td>${t.komponen?.mapel?.nama_mapel ?? '-'}</td>
            <td>${t.judul_tugas}</td>
            <td>${t.tanggal}</td>
            <td style="white-space:nowrap">
                <button class="btn-warning btn-sm" onclick="bukaEdit(${t.id_tugas})">Edit</button>
                <button class="btn-danger btn-sm" onclick="bukaHapus(${t.id_tugas}, '${t.judul_tugas.replace(/'/g,"\\'")}')">Hapus</button>
            </td>
        </tr>`;
    });

    document.getElementById('tugasData').innerHTML = html;
}


// ================= LOAD KELAS =================
async function loadKelas(){

    const res = await fetch('/api/jadwal-guru/' + id_guru);
    const json = await res.json();

    let html = '<option value="">Pilih Kelas</option>';

    json.data.forEach(j=>{
        html += `<option value="${j.id_kelas}|${j.id_mapel}">
            ${j.kelas.nama_kelas} - ${j.mapel.nama_mapel}
        </option>`;
    });

    document.getElementById('filter_kelas').innerHTML = html;
}


// ================= LOAD SISWA =================
async function loadSiswa(){

    let val = document.getElementById('filter_kelas').value;
    if(!val) return;

    [id_kelas,id_mapel] = val.split('|');

    let s = await fetch('/api/siswa-by-kelas/'+id_kelas).then(r=>r.json());
    siswa = s.data || [];

    let t = await fetch(`/api/tugas-by-mapel/${id_mapel}?id_guru=${id_guru}&id_kelas=${id_kelas}`)
    .then(r=>r.json());
    tugas = t.data || [];

    await loadNilaiTugas();

    render();
}


// ================= LOAD NILAI =================
async function loadNilaiTugas(){

    nilaiTugas = {};

    const res = await fetch(`/api/nilai-tugas-kelas/${id_kelas}/${id_mapel}`);
    const json = await res.json();

    json.data.forEach(n=>{

        if(!nilaiTugas[n.id_siswa]){
            nilaiTugas[n.id_siswa] = {};
        }

        nilaiTugas[n.id_siswa][n.id_tugas] = n.nilai;
    });
}


// ================= RENDER =================
function render(){

    let header = '<th>Nama</th>';

    tugas.forEach(t=>{
        header += `<th>${t.judul_tugas}</th>`;
    });

    header += '<th>AVG</th>';

    document.getElementById('header').innerHTML = header;

    let html='';

    siswa.forEach(s=>{

        let row = `<tr data-id="${s.id_siswa}">
            <td>${s.nama_siswa}</td>`;

        tugas.forEach(t=>{
            let val = nilaiTugas[s.id_siswa]?.[t.id_tugas] ?? '';

            row += `<td>
                <input class="nilai"
                    data-siswa="${s.id_siswa}"
                    data-tugas="${t.id_tugas}"
                    value="${val}">
            </td>`;
        });

        row += `<td class="avg">${hitungAvg(s.id_siswa).toFixed(2)}</td>`;
        row += '</tr>';

        html += row;
    });

    document.getElementById('body').innerHTML = html;

    bindInput();
}


// ================= INPUT EVENT =================
function bindInput(){

    document.querySelectorAll('.nilai').forEach(inp=>{

        inp.addEventListener('input', async (e)=>{

            let id_siswa = e.target.dataset.siswa;
            let id_tugas = e.target.dataset.tugas;
            let val = e.target.value;

            if(val < 0 || val > 100) return;

            await fetch('/api/nilai-tugas',{
                method:'POST',
                headers:{'Content-Type':'application/json'},
                body: JSON.stringify({id_siswa,id_tugas,nilai:val})
            });

            if(!nilaiTugas[id_siswa]) nilaiTugas[id_siswa] = {};
            nilaiTugas[id_siswa][id_tugas] = val;

            updateAvg(id_siswa);
        });

    });
}


// ================= UPDATE AVG =================
function updateAvg(id){

    let tr = document.querySelector(`tr[data-id="${id}"]`);
    tr.querySelector('.avg').innerText = hitungAvg(id).toFixed(2);
}


// ================= HITUNG =================
function hitungAvg(id){

    let arr = Object.values(nilaiTugas[id] || {});
    if(arr.length === 0) return 0;

    return arr.reduce((a,b)=>a+Number(b),0) / arr.length;
}


// ================= TAMBAH =================
async function tambahTugas(){

    let judul = document.getElementById('judul').value;
    let tanggal = document.getElementById('tanggal').value;

    if(!judul || !tanggal){
        alert('Isi semua');
        return;
    }

    await fetch('/api/tugas',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            id_komponen,
            id_kelas,
            judul_tugas:judul,
            tanggal,
            id_guru
        })
    });

    document.getElementById('judul').value='';
    document.getElementById('tanggal').value='';

    loadTugas();
}


// ================= EDIT =================
function bukaEdit(id){

    const t = tugas.find(x => x.id_tugas === id);
    if(!t) return;

    editId = id;

    document.getElementById('edit_judul').value = t.judul_tugas;
    document.getElementById('edit_tanggal').value = t.tanggal;
    document.getElementById('edit_komponen').value = t.id_komponen;

    document.getElementById('modalEdit').classList.add('show');
}

async function simpanEdit(){

    const judul = document.getElementById('edit_judul').value;
    const tanggal = document.getElementById('edit_tanggal').value;
    const id_komp = document.getElementById('edit_komponen').value;

    if(!judul || !tanggal){
        alert('Isi semua field');
        return;
    }

    await fetch(`/api/tugas/${editId}`, {
        method: 'PUT',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            judul_tugas: judul,
            tanggal,
            id_komponen: id_komp
        })
    });

    closeModal();
    loadTugas();
}


// ================= HAPUS =================
function bukaHapus(id, judul){

    hapusId = id;

    document.getElementById('konfirmasiHapus').innerText =
        `Yakin ingin menghapus tugas "${judul}"? Semua nilai terkait juga akan dihapus.`;

    document.getElementById('modalHapus').classList.add('show');
}

async function konfirmasiHapus(){

    await fetch(`/api/tugas/${hapusId}`, {
        method: 'DELETE'
    });

    closeModal();
    loadTugas();
}


// ================= MODAL =================
function closeModal(){
    document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('show'));
    editId = null;
    hapusId = null;
}

// Tutup modal klik di luar
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e){
        if(e.target === this) closeModal();
    });
});

</script>
@endsection