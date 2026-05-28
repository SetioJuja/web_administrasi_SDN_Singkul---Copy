@extends('layouts.app')

@section('title','Pengumuman')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
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
    grid-template-columns:1fr 1fr;
    gap:10px;
    margin-bottom:10px;
}

textarea{
    grid-column:span 2;
    min-height:90px;
}

input,
textarea{
    padding:10px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
}

input:focus,
textarea:focus{
    border-color:var(--primary);
}

/* ===== PREVIEW ===== */
.preview{
    margin-top:5px;
}

.preview img{
    max-width:120px;
    border-radius:8px;
}

/* ===== BUTTON ===== */
button{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    color:white;
    transition:.2s;
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
    background:#6b7280;
}

button:hover{
    opacity:0.9;
}

/* ===== TABLE ===== */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
    font-size:14px;
}

th,
td{
    border:1px solid var(--border);
    padding:10px;
    text-align:center;
}

thead{
    background:#f1f5f9;
}

tbody tr:hover{
    background:#f9fafb;
}

/* ===== IMAGE ===== */
.thumb{
    width:70px;
    border-radius:6px;
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
    width:360px;
    animation:fade .2s ease;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.modal-content input,
.modal-content textarea{
    width:100%;
    margin-bottom:10px;
}

.modal-content button{
    width:100%;
    margin-top:5px;
}

/* ===== NOTIF ===== */
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

/* ===== EDIT INFO ===== */
.edit-info{
    background:#fff7ed;
    border:1px solid #fdba74;
    padding:8px;
    border-radius:8px;
    margin-bottom:10px;
    display:none;
}

/* ===== ANIMATION ===== */
@keyframes fade{
    from{
        opacity:0;
        transform:scale(.95);
    }
    to{
        opacity:1;
        transform:scale(1);
    }
}
</style>

<div class="card">

<h3>Pengumuman</h3>

<div id="editInfo"
     class="edit-info">
    Mode Edit
</div>

<!-- ================= FORM ================= -->
<div class="form-grid">

    <input
        id="judul"
        placeholder="Judul"
    >

    <input
        type="date"
        id="tanggal"
    >

    <textarea
        id="isi"
        placeholder="Isi pengumuman"></textarea>

    <input
        type="file"
        id="gambar"
    >

    <div
        class="preview"
        id="preview"></div>

</div>

<button
    id="btnTambah"
    class="btn-primary">
    Simpan
</button>

<!-- ================= TABLE ================= -->
<table>

<thead>

<tr>

<th>Judul</th>

<th>Tanggal</th>

<th>Gambar</th>

<th>Aksi</th>

</tr>

</thead>

<tbody id="data"></tbody>

</table>

</div>



<!-- ================= MODAL EDIT ================= -->
<div id="modal"
     class="modal">

<div class="modal-content">

<h3 style="
    margin-bottom:18px;
    color:var(--primary);
">
    Edit Pengumuman
</h3>

<input id="edit_judul">

<textarea id="edit_isi"></textarea>

<input
    type="date"
    id="edit_tanggal">

<input
    type="file"
    id="edit_gambar">

<button
    id="btnUpdate"
    class="btn-primary">
    Update
</button>

<button
    id="btnTutup"
    class="btn-secondary">
    Batal
</button>

</div>
</div>



<!-- ================= MODAL NOTIFIKASI ================= -->
<div id="modalNotif"
     class="modal">

<div class="modal-content"
     style="text-align:center;">

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

    loadData();

    btnTambah.onclick = tambah;

    btnUpdate.onclick = update;

    btnTutup.onclick = tutup;

    gambar.onchange = previewImg;
});


// ================= PREVIEW =================
function previewImg(){

    const file =
        gambar.files[0];

    if(!file) return;

    const reader =
        new FileReader();

    reader.onload = e => {

        preview.innerHTML = `
            <img src="${e.target.result}">
        `;
    };

    reader.readAsDataURL(file);
}


// ================= LOAD DATA =================
function loadData(){

    fetch('/api/pengumuman')

    .then(res => res.json())

    .then(res => {

        let html = '';

        if(!res.data.length){

            html = `
                <tr>
                    <td colspan="4">
                        Belum ada pengumuman
                    </td>
                </tr>
            `;
        }

        res.data.forEach(p => {

            html += `
            <tr>

                <td>
                    ${p.judul}
                </td>

                <td>
                    ${p.tanggal}
                </td>

                <td>

                    ${
                        p.gambar
                        ? `
                        <img
                            class="thumb"
                            src="/upload/${p.gambar}">
                        `
                        : '-'
                    }

                </td>

                <td>

                    <button
                        class="btn-edit"
                        data-id="${p.id_pengumuman}">
                        Edit
                    </button>

                    <button
                        class="btn-danger"
                        data-id="${p.id_pengumuman}">
                        Hapus
                    </button>

                </td>

            </tr>`;
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


// ================= TAMBAH =================
function tambah(){

    if(
        !judul.value ||
        !tanggal.value
    ){

        showNotif(
            'warning',
            'Judul & tanggal wajib diisi'
        );

        return;
    }

    const form = new FormData();

    form.append(
        'judul',
        judul.value
    );

    form.append(
        'isi',
        isi.value
    );

    form.append(
        'tanggal',
        tanggal.value
    );

    if(gambar.files[0]){

        form.append(
            'gambar',
            gambar.files[0]
        );
    }

    fetch('/api/pengumuman',{

        method:'POST',

        body:form
    })

    .then(res => res.json())

    .then(res => {

        showNotif(
            'success',
            res.message
        );

        reset();

        loadData();
    })

    .catch(err => {

        console.error(err);

        showNotif(
            'danger',
            'Gagal tambah pengumuman'
        );
    });
}


// ================= EDIT =================
function edit(id){

    editId = id;

    fetch('/api/pengumuman/' + id)

    .then(res => res.json())

    .then(res => {

        let d = res.data;

        edit_judul.value =
            d.judul;

        edit_isi.value =
            d.isi;

        edit_tanggal.value =
            d.tanggal;

        modal.style.display = 'flex';
    });
}


// ================= UPDATE =================
function update(){

    const form = new FormData();

    form.append(
        'judul',
        edit_judul.value
    );

    form.append(
        'isi',
        edit_isi.value
    );

    form.append(
        'tanggal',
        edit_tanggal.value
    );

    if(edit_gambar.files[0]){

        form.append(
            'gambar',
            edit_gambar.files[0]
        );
    }

    fetch('/api/pengumuman/' + editId,{

        method:'POST',

        body:(() => {

            form.append('_method', 'PUT');

            return form;

        })()
    })

    .then(res => res.json())

    .then(res => {

        if(res.success){

            tutup();

            showNotif(
                'edit',
                'Pengumuman berhasil diperbarui'
            );

            loadData();

        }else{

            showNotif(
                'danger',
                res.message || 'Gagal update'
            );
        }
    })

    .catch(err => {

        console.error(err);

        showNotif(
            'danger',
            'Gagal update pengumuman'
        );
    });
}


// ================= HAPUS =================
function hapus(id){

    fetch('/api/pengumuman/' + id,{

        method:'DELETE'
    })

    .then(res => res.json())

    .then(res => {

        showNotif(
            'delete',
            'Pengumuman berhasil dihapus'
        );

        loadData();
    })

    .catch(err => {

        console.error(err);

        showNotif(
            'danger',
            'Gagal hapus pengumuman'
        );
    });
}


// ================= RESET =================
function reset(){

    editId = null;

    judul.value = '';

    isi.value = '';

    tanggal.value = '';

    gambar.value = '';

    preview.innerHTML = '';
}


// ================= TUTUP =================
function tutup(){

    modal.style.display = 'none';
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