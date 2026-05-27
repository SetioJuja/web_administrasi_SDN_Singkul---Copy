@extends('layouts.app')

@section('title','Data Pegawai')

@section('content')

<style>

:root{
    --primary:#0a3d62;
    --danger:#dc2626;
    --border:#e5e7eb;
    --bg:#f8fafc;
}

.title{
    margin-bottom:20px;
    color:var(--primary);
}

.toolbar{
    margin-bottom:20px;
}

.toolbar input{
    width:100%;
    padding:12px 14px;
    border:1px solid var(--border);
    border-radius:12px;
    outline:none;
    font-size:14px;
}

.toolbar input:focus{
    border-color:var(--primary);
}

/* GRID */
.card-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:18px;
}

/* CARD */
.pegawai-card{
    background:white;
    border-radius:18px;
    padding:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
    border:1px solid #f1f5f9;
    transition:0.2s;
}

.pegawai-card:hover{
    transform:translateY(-4px);
}

.card-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom:15px;
}

.nama{
    font-size:18px;
    font-weight:700;
    color:#111827;
}

.nip{
    color:#64748b;
    font-size:13px;
    margin-top:4px;
}

.badge-jk{
    background:#dbeafe;
    color:#1d4ed8;
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.role-wrap{
    display:flex;
    flex-wrap:wrap;
    gap:6px;
    margin-top:10px;
}

.role{
    background:#e0f2fe;
    color:#0369a1;
    padding:5px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.card-info{
    margin-top:12px;
    color:#475569;
    font-size:14px;
    line-height:1.6;
}

.action{
    display:flex;
    gap:10px;
    margin-top:18px;
}

button{
    border:none;
    border-radius:10px;
    cursor:pointer;
    padding:10px 14px;
    color:white;
    font-weight:600;
}

.btn-detail{
    flex:1;
    background:var(--primary);
}

.btn-edit{
    background:#2563eb;
}

.btn-delete{
    background:var(--danger);
}

button:hover{
    opacity:0.9;
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.45);
    justify-content:center;
    align-items:center;
    z-index:999;
    padding:20px;
}

.modal-content{
    background:white;
    width:100%;
    max-width:850px;
    border-radius:20px;
    padding:25px;
    max-height:90vh;
    overflow:auto;
}

.modal-title{
    font-size:22px;
    font-weight:700;
    color:var(--primary);
    margin-bottom:20px;
}

.detail-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:15px;
}

.detail-item{
    background:var(--bg);
    border-radius:14px;
    padding:14px;
}

.label{
    font-size:11px;
    color:#64748b;
    text-transform:uppercase;
    margin-bottom:5px;
}

.value{
    font-size:15px;
    color:#111827;
    word-break:break-word;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.form-grid input,
.form-grid select{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:10px;
    outline:none;
}

.jabatan-box{
    margin-top:15px;
    border:1px solid var(--border);
    border-radius:12px;
    padding:15px;
}

.modal-action{
    margin-top:20px;
    display:flex;
    gap:10px;
}

.btn-primary{
    background:var(--primary);
}

.btn-close{
    background:#64748b;
}

.empty{
    text-align:center;
    padding:50px;
    color:#64748b;
}

</style>

<div class="card">

<h3 class="title">Data Pegawai</h3>

<div class="toolbar">
    <input type="text"
           id="search"
           placeholder="Cari nama / NIP / email / jabatan...">
</div>

<div id="data" class="card-grid"></div>

</div>


<!-- MODAL DETAIL -->
<div id="detailModal" class="modal">

<div class="modal-content">

<div class="modal-title">
    Detail Pegawai
</div>

<div id="detailContent"></div>

<div class="modal-action">
    <button class="btn-close"
            onclick="tutupDetail()">

        Tutup

    </button>
</div>

</div>

</div>


<!-- MODAL EDIT -->
<div id="modal" class="modal">

<div class="modal-content">

<h3 class="modal-title">Edit Pegawai</h3>

<div class="form-grid">

    <input id="edit_nama" placeholder="Nama">

    <input id="edit_nip" placeholder="NIP">

    <select id="edit_jk">
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
    </select>

    <input id="edit_tempat" placeholder="Tempat Lahir">

    <input id="edit_tanggal" type="date">

    <input id="edit_alamat" placeholder="Alamat">

    <input id="edit_telp" placeholder="No Telepon">

    <input id="edit_email" placeholder="Email">

    <input id="edit_golongan" placeholder="Golongan">

    <input id="edit_pendidikan" placeholder="Pendidikan Tertinggi">

    <input id="edit_status" placeholder="Status Kepegawaian">

    <input id="edit_masuk" type="date">

</div>

<div class="jabatan-box" id="edit_jabatan"></div>

<div class="modal-action">

    <button id="btnUpdate" class="btn-primary">
        Update
    </button>

    <button id="btnTutup" class="btn-delete">
        Batal
    </button>

</div>

</div>

</div>

@endsection


@section('script')

<script>

let allData = [];
let editId = null;
let jabatanList = [];

document.addEventListener('DOMContentLoaded', init);

async function init(){

    await loadJabatan();
    await loadData();

    search.oninput = filterData;

    btnUpdate.onclick = update;

    btnTutup.onclick = tutup;
}


// ================= FORMAT =================
function formatTanggal(tgl){

    if(!tgl) return '-';

    const d = new Date(tgl);

    return d.toLocaleDateString('id-ID',{
        day:'2-digit',
        month:'long',
        year:'numeric'
    });
}


// ================= LOAD =================
async function loadJabatan(){

    const res = await fetch('/api/jabatan');

    jabatanList = (await res.json()).data;
}

async function loadData(){

    const res = await fetch('/api/pegawai');

    allData = (await res.json()).data;

    render(allData);
}


// ================= FILTER =================
function filterData(){

    const keyword = search.value.toLowerCase();

    const filtered = allData.filter(p => {

        let nama = (p.nama_guru || '').toLowerCase();

        let nip = (p.nip || '').toLowerCase();

        let email = (p.email || '').toLowerCase();

        let jabatan = (p.jabatan || [])
            .map(j => j.nama_jabatan.toLowerCase())
            .join(' ');

        return nama.includes(keyword) ||
               nip.includes(keyword) ||
               email.includes(keyword) ||
               jabatan.includes(keyword);
    });

    render(filtered);
}


// ================= RENDER =================
function render(data){

    if(data.length === 0){

        dataEl.innerHTML = `
        <div class="empty">
            Tidak ada data pegawai
        </div>`;

        return;
    }

    let html = '';

    data.forEach(p => {

        let roles = (p.jabatan || []).map(j => `
            <span class="role">
                ${j.nama_jabatan}
            </span>
        `).join('');

        html += `

        <div class="pegawai-card">

            <div class="card-top">

                <div>

                    <div class="nama">
                        ${p.nama_guru ?? '-'}
                    </div>

                    <div class="nip">
                        NIP : ${p.nip ?? '-'}
                    </div>

                </div>

                <div class="badge-jk">
                    ${p.jenis_kelamin ?? '-'}
                </div>

            </div>

            <div class="card-info">

                <div>
                   Email : ${p.email ?? '-'}
                </div>

                <div>
                   Telepon : ${p.no_telepon ?? '-'}
                </div>

                <div>
                   Pendidikan : ${p.pendidikan_tertinggi ?? '-'}
                </div>

            </div>

            <div class="role-wrap">
                ${roles}
            </div>

            <div class="action">

                <button class="btn-detail"
                        onclick="showDetail(${p.id_guru})">

                    Detail

                </button>

                <button class="btn-edit"
                        onclick="edit(${p.id_guru})">

                    Edit

                </button>

                <button class="btn-delete"
                        onclick="hapus(${p.id_guru})">

                    Hapus

                </button>

            </div>

        </div>
        `;
    });

    dataEl.innerHTML = html;
}


// ================= DETAIL =================
function showDetail(id){

    const p = allData.find(x => x.id_guru == id);

    let roles = (p.jabatan || []).map(j => `
        <span class="role">
            ${j.nama_jabatan}
        </span>
    `).join('');

    detailContent.innerHTML = `

    <div class="detail-grid">

        <div class="detail-item">
            <div class="label">Nama</div>
            <div class="value">${p.nama_guru ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">NIP</div>
            <div class="value">${p.nip ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Jenis Kelamin</div>
            <div class="value">${p.jenis_kelamin ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Tempat Lahir</div>
            <div class="value">${p.tempat_lahir ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Tanggal Lahir</div>
            <div class="value">${formatTanggal(p.tanggal_lahir)}</div>
        </div>

        <div class="detail-item">
            <div class="label">Alamat</div>
            <div class="value">${p.alamat ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">No Telepon</div>
            <div class="value">${p.no_telepon ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Email</div>
            <div class="value">${p.email ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Golongan</div>
            <div class="value">${p.golongan ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Pendidikan Tertinggi</div>
            <div class="value">${p.pendidikan_tertinggi ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Status Kepegawaian</div>
            <div class="value">${p.status_kepegawaian ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Tanggal Masuk</div>
            <div class="value">${formatTanggal(p.tanggal_masuk)}</div>
        </div>

        <div class="detail-item" style="grid-column:1/-1;">
            <div class="label">Jabatan</div>

            <div class="role-wrap">
                ${roles || '-'}
            </div>
        </div>

    </div>
    `;

    detailModal.style.display = 'flex';
}

function tutupDetail(){

    detailModal.style.display = 'none';
}


// ================= EDIT =================
function edit(id){

    editId = id;

    const p = allData.find(x => x.id_guru == id);

    edit_nama.value = p.nama_guru ?? '';
    edit_nip.value = p.nip ?? '';
    edit_jk.value = p.jenis_kelamin ?? '';
    edit_tempat.value = p.tempat_lahir ?? '';
    edit_tanggal.value = p.tanggal_lahir?.split('T')[0] ?? '';
    edit_alamat.value = p.alamat ?? '';
    edit_telp.value = p.no_telepon ?? '';
    edit_email.value = p.email ?? '';
    edit_golongan.value = p.golongan ?? '';
    edit_pendidikan.value = p.pendidikan_tertinggi ?? '';
    edit_status.value = p.status_kepegawaian ?? '';
    edit_masuk.value = p.tanggal_masuk?.split('T')[0] ?? '';

    let html = '';

    jabatanList.forEach(j => {

        let checked = (p.jabatan || []).some(r =>
            r.id_jabatan == j.id_jabatan
        ) ? 'checked' : '';

        html += `
        <label>
            <input type="checkbox"
                   value="${j.id_jabatan}"
                   ${checked}>

            ${j.nama_jabatan}
        </label><br>`;
    });

    edit_jabatan.innerHTML = html;

    modal.style.display = 'flex';
}


// ================= UPDATE =================
async function update(){

    const selected =
        [...document.querySelectorAll('#edit_jabatan input:checked')]
        .map(el => el.value);

    const res = await fetch('/api/pegawai/' + editId, {

        method:'PUT',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({

            nama_guru: edit_nama.value,
            nip: edit_nip.value,
            jenis_kelamin: edit_jk.value,
            tempat_lahir: edit_tempat.value,
            tanggal_lahir: edit_tanggal.value,
            alamat: edit_alamat.value,
            no_telepon: edit_telp.value,
            email: edit_email.value,
            golongan: edit_golongan.value,
            pendidikan_tertinggi: edit_pendidikan.value,
            status_kepegawaian: edit_status.value,
            tanggal_masuk: edit_masuk.value,
            jabatan: selected
        })
    });

    const data = await res.json();

    if(data.success){

        alert('Berhasil update');

        tutup();

        loadData();

    }else{

        alert(data.message);
    }
}


// ================= HAPUS =================
function hapus(id){

    if(confirm('Yakin hapus data?')){

        fetch('/api/pegawai/' + id,{
            method:'DELETE'
        }).then(()=>loadData());
    }
}


// ================= TUTUP =================
function tutup(){

    modal.style.display = 'none';
}

const dataEl = document.getElementById('data');

</script>

@endsection