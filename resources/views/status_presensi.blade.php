@extends('layouts.app')

@section('title','Status Presensi')

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
.form-inline{
    display:flex;
    gap:10px;
    margin-bottom:15px;
}

input{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    flex:1;
}

input:focus{
    border-color:var(--primary);
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
    font-size:14px;
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
    animation:fade .2s ease;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.modal-content input{
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

<h3>Status Presensi</h3>

<!-- FORM -->
<div class="form-inline">

    <input
        id="nama_status"
        placeholder="Nama Status"
    >

    <button
        id="btnTambah"
        class="btn-primary">
        Tambah
    </button>

</div>

<!-- TABLE -->
<table>

<thead>
<tr>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>

<tbody id="data"></tbody>

</table>

</div>



<!-- ================= MODAL EDIT ================= -->
<div id="modalEdit" class="modal">

<div class="modal-content">

<h3 style="
    margin-bottom:18px;
    color:var(--primary);
">
    Edit Status
</h3>

<input id="edit_status">

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
<div id="modalNotif" class="modal">

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

    document.getElementById('btnTambah')
        .addEventListener('click', tambah);

    document.getElementById('btnUpdate')
        .addEventListener('click', update);

    document.getElementById('btnTutup')
        .addEventListener('click', tutup);
});


// ================= LOAD DATA =================
function loadData(){

    fetch('/api/status-presensi')

    .then(res => res.json())

    .then(res => {

        let html = '';

        if(!res.data.length){

            html = `
                <tr>
                    <td colspan="2">
                        Belum ada data
                    </td>
                </tr>
            `;
        }

        res.data.forEach(s => {

            html += `
            <tr>

                <td>
                    ${s.nama_status}
                </td>

                <td>

                    <button
                        class="btn-edit"
                        onclick="edit(${s.id_status})">
                        Edit
                    </button>

                    <button
                        class="btn-danger"
                        onclick="hapus(${s.id_status})">
                        Hapus
                    </button>

                </td>

            </tr>
            `;
        });

        data.innerHTML = html;
    });
}


// ================= TAMBAH =================
function tambah(){

    if(!nama_status.value){

        showNotif(
            'warning',
            'Nama status wajib diisi'
        );

        return;
    }

    fetch('/api/status-presensi',{

        method:'POST',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({
            nama_status: nama_status.value
        })
    })

    .then(async res => {

        const data = await res.json();

        if(res.ok){

            showNotif(
                'success',
                data.message
            );

            nama_status.value = '';

            loadData();

        }else{

            showNotif(
                'danger',
                data.message || 'Gagal tambah data'
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

    fetch('/api/status-presensi/' + id)

    .then(res => res.json())

    .then(res => {

        edit_status.value =
            res.data.nama_status;

        modalEdit.style.display = 'flex';
    });
}


// ================= UPDATE =================
function update(){

    fetch('/api/status-presensi/' + editId,{

        method:'PUT',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({
            nama_status: edit_status.value
        })
    })

    .then(async res => {

        const data = await res.json();

        if(res.ok){

            tutup();

            showNotif(
                'edit',
                data.message
            );

            loadData();

        }else{

            showNotif(
                'danger',
                data.message || 'Gagal update data'
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


// ================= HAPUS =================
function hapus(id){

    fetch('/api/status-presensi/' + id,{

        method:'DELETE'
    })

    .then(async res => {

        const data = await res.json();

        if(res.ok){

            showNotif(
                'delete',
                data.message
            );

            loadData();

        }else{

            showNotif(
                'danger',
                data.message || 'Gagal hapus data'
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


// ================= TUTUP =================
function tutup(){

    modalEdit.style.display = 'none';
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

