@extends('layouts.app')

@section('title','Manajemen Jabatan')

@section('content')

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

h3{
    margin-bottom:20px;
    color:var(--primary);
}

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

button{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    color:white;
    transition:.2s;
}

.btn-primary{ background:var(--primary); }
.btn-danger{ background:var(--danger); }
.btn-info{ background:#2563eb; }
.btn-secondary{ background:#6b7280; }

button:hover{
    opacity:0.9;
}

table{
    width:100%;
    border-collapse:collapse;
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

tbody tr:hover{
    background:#f9fafb;
}

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

.modal-content h3{
    margin-bottom:15px;
}

.modal-content input{
    width:100%;
    margin-bottom:10px;
}

.modal-content button{
    width:100%;
    margin-top:5px;
}

#detailList{
    text-align:left;
    margin-bottom:10px;
}

#detailList li{
    padding:6px;
    border-bottom:1px solid var(--border);
}

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

<h3>Manajemen Jabatan</h3>

<!-- FORM -->
<div class="form-inline">

    <input id="nama_jabatan"
           placeholder="Nama Jabatan">

    <button id="btnTambah"
            class="btn-primary">
        Tambah
    </button>

</div>

<table>

<thead>
<tr>
<th>Nama Jabatan</th>
<th>Jumlah Pegawai</th>
<th>Aksi</th>
</tr>
</thead>

<tbody id="data"></tbody>

</table>

</div>

<div id="modalEdit" class="modal">

<div class="modal-content">

<h3>Edit Jabatan</h3>

<input id="edit_nama">

<button id="btnUpdate"
        class="btn-primary">
    Update
</button>

<button id="btnTutup"
        class="btn-secondary">
    Batal
</button>

</div>
</div>

<div id="modalDetail" class="modal">

<div class="modal-content">

<h3>Detail Pegawai</h3>

<ul id="detailList"></ul>

<button id="btnTutupDetail"
        class="btn-secondary">
    Tutup
</button>

</div>
</div>

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

    <button onclick="tutupNotif()"
            class="btn-primary">
        OK
    </button>

</div>
</div>

@endsection


@section('script')
<script>

let editId = null;

document.addEventListener('DOMContentLoaded', () => {

    loadData();

    document.getElementById('btnTambah')
        .addEventListener('click', tambah);

    document.getElementById('btnUpdate')
        .addEventListener('click', update);

    document.getElementById('btnTutup')
        .addEventListener('click', tutup);

    document.getElementById('btnTutupDetail')
        .addEventListener('click', tutupDetail);
});

function loadData(){

    fetch('/api/jabatan')

    .then(res => res.json())

    .then(res => {

        let html = '';

        res.data.forEach(j => {

            html += `
            <tr>

                <td>
                    ${j.nama_jabatan}
                </td>

                <td>
                    <b>${j.pegawai_count}</b>
                </td>

                <td>

                    <button class="btn-info"
                            data-id="${j.id_jabatan}">
                        Detail
                    </button>

                    <button class="btn-primary"
                            data-id="${j.id_jabatan}"
                            data-nama="${j.nama_jabatan}">
                        Edit
                    </button>

                    <button class="btn-danger"
                            data-id="${j.id_jabatan}">
                        Hapus
                    </button>

                </td>

            </tr>`;
        });

        document.getElementById('data').innerHTML = html;

        // DETAIL
        document.querySelectorAll('.btn-info')
        .forEach(btn => {

            btn.onclick = () =>
                detail(btn.dataset.id);
        });

        // EDIT
        document.querySelectorAll('.btn-primary')
        .forEach(btn => {

            if(btn.dataset.id){

                btn.onclick = () =>
                    edit(
                        btn.dataset.id,
                        btn.dataset.nama
                    );
            }
        });

        // HAPUS
        document.querySelectorAll('.btn-danger')
        .forEach(btn => {

            btn.onclick = () =>
                hapus(btn.dataset.id);
        });

    });
}

function tambah(){

    let nama =
        document.getElementById('nama_jabatan').value;

    if(!nama){

        showNotif(
            'danger',
            'Nama jabatan wajib diisi'
        );

        return;
    }

    fetch('/api/jabatan',{

        method:'POST',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({
            nama_jabatan: nama
        })
    })

    .then(res => res.json())

    .then(res => {

        showNotif(
            'success',
            res.message
        );

        document.getElementById('nama_jabatan')
            .value = '';

        loadData();
    });
}

function edit(id, nama){

    editId = id;

    document.getElementById('edit_nama')
        .value = nama;

    document.getElementById('modalEdit')
        .style.display = 'flex';
}

function update(){

    fetch('/api/jabatan/' + editId,{

        method:'PUT',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({
            nama_jabatan:
                document.getElementById('edit_nama').value
        })
    })

    .then(res => res.json())

    .then(res => {

        showNotif(
            'edit',
            res.message
        );

        tutup();

        loadData();
    });
}

function hapus(id){

        fetch('/api/jabatan/' + id,{

            method:'DELETE'
        })

        .then(res => res.json())

        .then(res => {

            showNotif(
                'delete',
                res.message
            );

            loadData();
        });
    
}

function detail(id){

    fetch('/api/jabatan/' + id)

    .then(res => res.json())

    .then(res => {

        let html = '';

        if(res.data.pegawai.length === 0){

            html = `
                <li>
                    Tidak ada pegawai
                </li>
            `;

        } else {

            res.data.pegawai.forEach(p => {

                html += `
                    <li>
                        ${p.nama_guru}
                    </li>
                `;
            });
        }

        document.getElementById('detailList')
            .innerHTML = html;

        document.getElementById('modalDetail')
            .style.display = 'flex';
    });
}

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

    }else if(type === 'danger'){

        icon.innerHTML = '!';
        icon.style.background = '#f59e0b';

        title.innerText = 'Peringatan';
    }

    text.innerText = message;

    document.getElementById('modalNotif')
        .style.display = 'flex';
}

function tutupNotif(){

    document.getElementById('modalNotif')
        .style.display = 'none';
}

function tutup(){

    document.getElementById('modalEdit')
        .style.display = 'none';
}

function tutupDetail(){

    document.getElementById('modalDetail')
        .style.display = 'none';
}

</script>
@endsection