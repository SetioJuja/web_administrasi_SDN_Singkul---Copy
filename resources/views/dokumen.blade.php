@extends('layouts.app')

@section('title','Dokumen')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --success:#16a34a;
    --danger:#dc2626;
}

/* ===== CARD ===== */
.card{
    background:white;
    border-radius:16px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

/* ===== TITLE ===== */
h3{
    margin-bottom:20px;
    color:var(--primary);
}

/* ===== FORM ===== */
.form-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap:10px;
    margin-bottom:15px;
}

input,
select{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    width:100%;
}

input:focus,
select:focus{
    border-color:var(--primary);
}

/* ===== FILE INPUT ===== */
input[type="file"]{
    padding:6px;
    background:#f9fafb;
}

/* ===== BUTTON ===== */
button{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    color:white;
    transition:0.2s;
}

.btn-primary{
    background:var(--primary);
}

.btn-danger{
    background:var(--danger);
}

.btn-edit{
    background:#2563eb;
}

.btn-secondary{
    background:#64748b;
}

button:hover{
    opacity:0.9;
    transform:translateY(-1px);
}

/* ===== BUTTON AREA ===== */
.button-group{
    display:flex;
    gap:10px;
    margin-bottom:20px;
    flex-wrap:wrap;
}

/* ===== TABLE ===== */
.table-wrapper{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
    background:white;
}

thead{
    background:#f1f5f9;
}

th,
td{
    border:1px solid var(--border);
    padding:10px;
    text-align:center;
}

tbody tr:hover{
    background:#f9fafb;
}

/* ===== LINK FILE ===== */
.file-link{
    color:#2563eb;
    font-weight:500;
    text-decoration:none;
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

/* ===== EDIT MODE ===== */
.edit-mode{
    background:#fff7ed;
    border:1px solid #fdba74;
    padding:10px;
    border-radius:10px;
    margin-bottom:15px;
    font-size:13px;
    color:#9a3412;
}

/* ===== MODAL ===== */
.modal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
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

/* ===== NOTIF ICON ===== */
.notif-icon{
    width:70px;
    height:70px;
    margin:auto;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:32px;
    margin-bottom:15px;
    color:white;
}

/* ===== ANIMATION ===== */
@keyframes fade{
    from{
        opacity:0;
        transform:scale(0.95);
    }
    to{
        opacity:1;
        transform:scale(1);
    }
}
</style>

<div class="card">

<h3>Dokumen Administrasi</h3>

<div id="editInfo"
     style="display:none;"
     class="edit-mode">
    Sedang edit dokumen...
</div>

<!-- FORM -->
<div class="form-grid">

    <input
        id="judul"
        placeholder="Judul"
    >

    <input
        type="file"
        id="file"
    >

    <select id="tahun"></select>

    <input
        id="keterangan"
        placeholder="Keterangan"
    >

</div>

<!-- BUTTON -->
<div class="button-group">

    <button
        id="btnSimpan"
        class="btn-primary">
        Upload
    </button>

    <button 
        id="btnBatal" 
        class="btn-secondary"
        style="display:none;">
        Batal
    </button>

</div>

<!-- TABLE -->
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

</div>



<!-- ================= MODAL NOTIFIKASI ================= -->
<div id="modalNotif" class="modal">

<div class="modal-content">

    <div id="notifIcon"
         class="notif-icon">
        ✓
    </div>

    <h3 id="notifTitle">
        Berhasil
    </h3>

    <p id="notifText"
       style="
        margin-bottom:20px;
        color:#6b7280;
        font-size:14px;
        line-height:1.5;
       ">
       Data berhasil disimpan
    </p>

    <button
        onclick="tutupNotif()"
        class="btn-primary">
        OK
    </button>

</div>
</div>

@endsection


@section('script')
<script>

let editId = null;


// ================= INIT =================
document.addEventListener('DOMContentLoaded', () => {

    loadTahun();

    loadData();

    document.getElementById('btnSimpan')
        .addEventListener('click', simpan);

    document.getElementById('btnBatal')
        .addEventListener('click', resetForm);
});


// ================= LOAD TAHUN =================
function loadTahun(){

    fetch('/api/tahun-ajaran')

    .then(res => res.json())

    .then(res => {

        let html = `
            <option value="">
                Pilih Tahun
            </option>
        `;

        res.data.forEach(t => {

            html += `
                <option value="${t.id_tahun_ajaran}">
                    ${t.periode}
                </option>
            `;
        });

        tahun.innerHTML = html;
    });
}


// ================= LOAD DATA =================
function loadData(){

    fetch('/api/dokumen')

    .then(res => res.json())

    .then(res => {

        let html = '';

        if(!res.data.length){

            html = `
                <tr>
                    <td colspan="4" class="empty">
                        Belum ada dokumen
                    </td>
                </tr>
            `;
        }

        res.data.forEach(d => {

            html += `
            <tr>

                <td>
                    ${d.judul_dokumen}
                </td>

                <td>
                    ${
                        d.gambar
                        ? `
                        <a 
                            class="file-link"
                            href="/uploads/${d.gambar}"
                            target="_blank">
                            📄 Lihat
                        </a>
                        `
                        : '-'
                    }
                </td>

                <td>
                    ${d.tahun_ajaran?.periode ?? '-'}
                </td>

                <td>

                    <button 
                        class="btn-edit"
                        data-id="${d.id_dokumen}">
                        Edit
                    </button>

                    <button 
                        class="btn-danger"
                        data-id="${d.id_dokumen}">
                        Hapus
                    </button>

                </td>

            </tr>
            `;
        });

        data.innerHTML = html;

        document.querySelectorAll('.btn-edit')
        .forEach(btn => {

            btn.onclick = () =>
                edit(btn.dataset.id);
        });

        document.querySelectorAll('.btn-danger')
        .forEach(btn => {

            btn.onclick = () =>
                hapus(btn.dataset.id);
        });

    });
}


// ================= SIMPAN =================
function simpan(){

    if(!judul.value || !tahun.value){

        showNotif(
            'warning',
            'Judul & Tahun wajib diisi'
        );

        return;
    }

    const form = new FormData();

    form.append(
        'judul_dokumen',
        judul.value
    );

    form.append(
        'id_tahun_ajaran',
        tahun.value
    );

    form.append(
        'keterangan',
        keterangan.value
    );

    if(file.files[0]){

        form.append(
            'gambar',
            file.files[0]
        );
    }

    let url = '/api/dokumen';

    if(editId){

        url =
            '/api/dokumen/update/' + editId;
    }

    fetch(url,{

        method:'POST',

        body:form
    })

    .then(async res => {

        const data =
            await res.json();

        if(data.success){

            showNotif(
                editId ? 'edit' : 'success',
                editId
                    ? 'Dokumen berhasil diperbarui'
                    : 'Dokumen berhasil diupload'
            );

            resetForm();

            loadData();

        }else{

            showNotif(
                'danger',
                data.message
            );
        }
    })

    .catch(err => {

        console.error(err);

        showNotif(
            'danger',
            'Terjadi kesalahan server'
        );
    });
}


// ================= EDIT =================
function edit(id){

    editId = id;

    fetch('/api/dokumen/' + id)

    .then(res => res.json())

    .then(res => {

        let d = res.data;

        judul.value =
            d.judul_dokumen;

        tahun.value =
            d.id_tahun_ajaran;

        keterangan.value =
            d.keterangan;

        btnSimpan.innerText = 'Update';

        editInfo.style.display = 'block';

        btnBatal.style.display = 'inline-block';
    });
}


// ================= RESET =================
function resetForm(){

    editId = null;

    judul.value = '';

    keterangan.value = '';

    file.value = '';

    tahun.value = '';

    btnSimpan.innerText = 'Upload';

    editInfo.style.display = 'none';

    btnBatal.style.display = 'none';
}


// ================= HAPUS =================
function hapus(id){


        fetch('/api/dokumen/' + id,{

            method:'DELETE'
        })

        .then(res => res.json())

        .then(res => {

            showNotif(
                'delete',
                'Dokumen berhasil dihapus'
            );

            loadData();
        })

        .catch(err => {

            console.error(err);

            showNotif(
                'danger',
                'Gagal hapus dokumen'
            );
        });
    
}


// ================= MODAL NOTIF =================
function showNotif(type, message){

    let icon =
        document.getElementById('notifIcon');

    let title =
        document.getElementById('notifTitle');

    let text =
        document.getElementById('notifText');

    if(type === 'success'){

        icon.innerHTML = '✓';
        icon.style.background = '#16a34a';

        title.innerText = 'Berhasil';

    }else if(type === 'delete'){

        icon.innerHTML = '🗑';
        icon.style.background = '#dc2626';

        title.innerText = 'Data Dihapus';

    }else if(type === 'edit'){

        icon.innerHTML = '✎';
        icon.style.background = '#2563eb';

        title.innerText = 'Data Diperbarui';

    }else if(type === 'warning'){

        icon.innerHTML = '!';
        icon.style.background = '#f59e0b';

        title.innerText = 'Peringatan';

    }else if(type === 'danger'){

        icon.innerHTML = '✕';
        icon.style.background = '#dc2626';

        title.innerText = 'Gagal';
    }

    text.innerText = message;

    document.getElementById('modalNotif')
        .style.display = 'flex';
}


// ================= TUTUP NOTIF =================
function tutupNotif(){

    document.getElementById('modalNotif')
        .style.display = 'none';
}

</script>
@endsection