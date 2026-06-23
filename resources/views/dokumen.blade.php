@extends('layouts.app')

@section('title','Dokumen')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --success:#16a34a;
    --danger:#dc2626;
}
.card{
    background:white;
    border-radius:16px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
}
h3{ margin-bottom:20px; color:var(--primary); }

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:10px;
    margin-bottom:15px;
}
input, select{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    width:100%;
}
input:focus, select:focus{ border-color:var(--primary); }
input[type="file"]{ padding:6px; background:#f9fafb; }

button{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    color:white;
    transition:0.2s;
}
.btn-primary{ background:var(--primary); }
.btn-danger{ background:var(--danger); }
.btn-edit{ background:#2563eb; }
.btn-secondary{ background:#64748b; }
button:hover{ opacity:0.9; transform:translateY(-1px); }

.button-group{ display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap; }
.table-wrapper{ overflow-x:auto; }
table{ width:100%; border-collapse:collapse; font-size:14px; background:white; }
thead{ background:#f1f5f9; }
th, td{ border:1px solid var(--border); padding:10px; text-align:center; }
tbody tr:hover{ background:#f9fafb; }

.file-link{ color:#2563eb; font-weight:500; text-decoration:none; }
.file-link:hover{ text-decoration:underline; }
.empty{ padding:20px; text-align:center; color:#999; }

.edit-mode{
    background:#fff7ed;
    border:1px solid #fdba74;
    padding:10px;
    border-radius:10px;
    margin-bottom:15px;
    font-size:13px;
    color:#9a3412;
}

.modal{
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.45);
    justify-content:center;
    align-items:center;
    z-index:999;
}
.modal-content{
    background:white;
    padding:24px;
    border-radius:16px;
    width:340px;
    text-align:center;
    animation:fade 0.2s ease;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}
.notif-icon{
    width:70px; height:70px;
    margin:auto;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
    margin-bottom:15px;
    color:white;
}
@keyframes fade{
    from{ opacity:0; transform:scale(0.95); }
    to{ opacity:1; transform:scale(1); }
}

.lemari-tahun{
    background:white;
    border:1px solid var(--border);
    border-radius:12px;
    margin-bottom:10px;
    overflow:hidden;
}
.lemari-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:14px 18px;
    cursor:pointer;
    background:#f8fafc;
    font-weight:600;
    color:var(--primary);
}
.lemari-head:hover{ background:#eef2f7; }
.lemari-body{ display:none; padding:10px 18px 18px; }
.lemari-body.open{ display:block; }
.lemari-count{
    background:var(--primary);
    color:white;
    border-radius:20px;
    padding:2px 10px;
    font-size:12px;
}
</style>

<div class="card">

    <h3>Dokumen Administrasi</h3>

    <div id="editInfo" style="display:none;" class="edit-mode">
        Sedang edit dokumen...
    </div>

    <div class="form-grid">
        <input id="judul" placeholder="Judul">
        <input type="file" id="file">
        <select id="tahun"></select>
        <input id="keterangan" placeholder="Keterangan">
    </div>

    <div class="button-group">
        <button id="btnSimpan" class="btn-primary">
            <i class="bi bi-upload"></i> Upload
        </button>
        <button id="btnBatal" class="btn-secondary" style="display:none;">
            <i class="bi bi-x"></i> Batal
        </button>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>File</th>
                    <th>Tahun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="data"></tbody>
        </table>
    </div>

    <div style="margin-top:30px;">
        <h3 style="font-size:16px;"><i class="bi bi-archive"></i> Lemari Dokumen Lainnya</h3>
        <div id="lemari"></div>
    </div>

</div>

<div id="modalNotif" class="modal">
    <div class="modal-content">
        <div id="notifIcon" class="notif-icon"><i class="bi bi-check-lg"></i></div>
        <h3 id="notifTitle">Berhasil</h3>
        <p id="notifText" style="margin-bottom:20px; color:#6b7280; font-size:14px; line-height:1.5;">
            Data berhasil disimpan
        </p>
        <button onclick="tutupNotif()" class="btn-primary">OK</button>
    </div>
</div>

@endsection


@section('script')
<script>

let editId = null;
let semuaDokumen = [];

document.addEventListener('DOMContentLoaded', () => {
    loadTahun();
    loadData();

    document.getElementById('btnSimpan').addEventListener('click', simpan);
    document.getElementById('btnBatal').addEventListener('click', resetForm);

    document.addEventListener('click', e => {
        const editBtn = e.target.closest('.btn-edit');
        if(editBtn){ edit(editBtn.dataset.id); return; }

        const delBtn = e.target.closest('.btn-danger');
        if(delBtn){ hapus(delBtn.dataset.id); return; }

        const head = e.target.closest('.lemari-head');
        if(head){
            document.getElementById('body-' + head.dataset.tahun)
                .classList.toggle('open');
        }
    });
});

function loadTahun(){
    fetch('/api/tahun-ajaran')
    .then(res => res.json())
    .then(res => {
        let html = `<option value="">Pilih Tahun</option>`;

        res.data.forEach(t => {
            const aktif = t.status === 'aktif' || t.is_aktif == 1;
            html += `
                <option value="${t.id_tahun_ajaran}">
                    ${t.periode} - ${t.semester}${aktif ? ' - Aktif' : ''}
                </option>
            `;
        });

        tahun.innerHTML = html;
    });
}

function loadData(){
    fetch('/api/dokumen')
    .then(res => res.json())
    .then(res => {
        semuaDokumen = [...res.data].sort((a,b) => b.id_dokumen - a.id_dokumen);
        renderTabelTerbaru();
        renderLemari();
    });
}

function rowDokumen(d){
    return `
        <tr>
            <td>${d.judul_dokumen}</td>
            <td>
                ${d.gambar
                    ? `<a class="file-link" href="/uploads/${d.gambar}" target="_blank">
                         <i class="bi bi-file-earmark-text"></i> Lihat
                       </a>`
                    : '-'}
            </td>
            <td>${d.tahun_ajaran?.periode ?? '-'}</td>
            <td>
                <button class="btn-edit" data-id="${d.id_dokumen}">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
                <button class="btn-danger" data-id="${d.id_dokumen}">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </td>
        </tr>
    `;
}

function renderTabelTerbaru(){
    const terbaru = semuaDokumen.slice(0, 10);
    data.innerHTML = terbaru.length
        ? terbaru.map(rowDokumen).join('')
        : `<tr><td colspan="4" class="empty">Belum ada dokumen</td></tr>`;
}

function renderLemari(){
    const sisa = semuaDokumen.slice(10);
    const lemariEl = document.getElementById('lemari');

    if(!sisa.length){
        lemariEl.innerHTML = `<p class="empty">Tidak ada dokumen lain</p>`;
        return;
    }

    const grup = {};
    sisa.forEach(d => {
        const key = d.tahun_ajaran?.periode ?? 'Tanpa Tahun';
        (grup[key] ??= []).push(d);
    });

    lemariEl.innerHTML = Object.keys(grup).map(tahun => `
        <div class="lemari-tahun">
            <div class="lemari-head" data-tahun="${tahun}">
                <span><i class="bi bi-folder"></i> Tahun ${tahun}</span>
                <span class="lemari-count">${grup[tahun].length} dokumen</span>
            </div>
            <div class="lemari-body" id="body-${tahun}">
                <table>
                    <thead>
                        <tr><th>Judul</th><th>File</th><th>Tahun</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>${grup[tahun].map(rowDokumen).join('')}</tbody>
                </table>
            </div>
        </div>
    `).join('');
}

function simpan(){
    if(!judul.value || !tahun.value){
        showNotif('warning','Judul & Tahun wajib diisi');
        return;
    }

    const form = new FormData();
    form.append('judul_dokumen', judul.value);
    form.append('id_tahun_ajaran', tahun.value);
    form.append('keterangan', keterangan.value);
    if(file.files[0]){ form.append('gambar', file.files[0]); }

    let url = '/api/dokumen';
    if(editId){ url = '/api/dokumen/update/' + editId; }

    fetch(url, { method:'POST', body:form })
    .then(async res => {
        const data = await res.json();
        if(data.success){
            showNotif(
                editId ? 'edit' : 'success',
                editId ? 'Dokumen berhasil diperbarui' : 'Dokumen berhasil diupload'
            );
            resetForm();
            loadData();
        } else {
            showNotif('danger', data.message);
        }
    })
    .catch(err => {
        console.error(err);
        showNotif('danger','Terjadi kesalahan server');
    });
}

function edit(id){
    editId = id;
    fetch('/api/dokumen/' + id)
    .then(res => res.json())
    .then(res => {
        let d = res.data;
        judul.value = d.judul_dokumen;
        tahun.value = d.id_tahun_ajaran;
        keterangan.value = d.keterangan;
        btnSimpan.innerHTML = '<i class="bi bi-upload"></i> Update';
        editInfo.style.display = 'block';
        btnBatal.style.display = 'inline-block';
    });
}

function resetForm(){
    editId = null;
    judul.value = '';
    keterangan.value = '';
    file.value = '';
    tahun.value = '';
    btnSimpan.innerHTML = '<i class="bi bi-upload"></i> Upload';
    editInfo.style.display = 'none';
    btnBatal.style.display = 'none';
}

function hapus(id){
    fetch('/api/dokumen/' + id, { method:'DELETE' })
    .then(res => res.json())
    .then(() => {
        showNotif('delete','Dokumen berhasil dihapus');
        loadData();
    })
    .catch(err => {
        console.error(err);
        showNotif('danger','Gagal hapus dokumen');
    });
}

function showNotif(type, message){
    const icon  = document.getElementById('notifIcon');
    const title = document.getElementById('notifTitle');
    const text  = document.getElementById('notifText');

    const map = {
        success:{ i:'bi-check-lg', bg:'#16a34a', t:'Berhasil' },
        delete: { i:'bi-trash',    bg:'#dc2626', t:'Data Dihapus' },
        edit:   { i:'bi-pencil',   bg:'#2563eb', t:'Data Diperbarui' },
        warning:{ i:'bi-exclamation-triangle', bg:'#f59e0b', t:'Peringatan' },
        danger: { i:'bi-x-lg',     bg:'#dc2626', t:'Gagal' },
    };

    const m = map[type] ?? map.danger;
    icon.innerHTML = `<i class="bi ${m.i}"></i>`;
    icon.style.background = m.bg;
    title.innerText = m.t;
    text.innerText  = message;
    document.getElementById('modalNotif').style.display = 'flex';
}

function tutupNotif(){
    document.getElementById('modalNotif').style.display = 'none';
}

</script>
@endsection