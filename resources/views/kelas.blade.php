@extends('layouts.app')

@section('title','Kelas')

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
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
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
    background:#6b7280;
}

button:hover{
    opacity:0.9;
    transform:translateY(-1px);
}

/* ===== BUTTON TAMBAH ===== */
#btnTambah{
    margin-bottom:20px;
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
    padding:12px;
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
    animation:fade 0.2s ease;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.modal-content input,
.modal-content select{
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
        transform:scale(0.95);
    }
    to{
        opacity:1;
        transform:scale(1);
    }
}
</style>

<div class="card">

    <h3>Manajemen Kelas</h3>

    <!-- FORM -->
    <div class="form-grid">

        <input
            id="nama_kelas"
            type="number"
            placeholder="Nama Kelas"
        >

        <input
            id="total_siswa"
            type="number"
            placeholder="Total Siswa"
        >

        <select id="id_guru"></select>

        <select id="id_tahun_ajaran"></select>

    </div>

    <!-- BUTTON -->
    <button
        id="btnTambah"
        class="btn-primary">
        Tambah
    </button>

    <!-- TABLE -->
    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    <th>Kelas</th>

                    <th>Wali</th>

                    <th>Tahun</th>

                    <th>Total</th>

                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody id="data"></tbody>

        </table>

    </div>

</div>



<!-- ================= MODAL EDIT ================= -->
<div id="modal" class="modal">

    <div class="modal-content">

        <h3>Edit Kelas</h3>

        <input
            id="edit_nama"
            type="number"
        >

        <input
            id="edit_total"
            type="number"
        >

        <select id="edit_guru"></select>

        <select id="edit_tahun"></select>

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
     style="
        text-align:center;
     ">

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

    loadGuru();
    loadTahun();
    loadData();

    document.getElementById('btnTambah')
        .addEventListener('click', tambah);

    document.getElementById('btnUpdate')
        .addEventListener('click', update);

    document.getElementById('btnTutup')
        .addEventListener('click', tutup);
});


// ================= LOAD GURU =================
function loadGuru(){

    fetch('/api/pegawai/guru-kelas')

    .then(res => res.json())

    .then(res => {

        let html =
            '<option value="">-- Tanpa Wali Kelas --</option>';

        res.data.forEach(g => {

            html += `
                <option value="${g.id_guru}">
                    ${g.nama_guru}
                </option>
            `;
        });

        id_guru.innerHTML   = html;
        edit_guru.innerHTML = html;
    });
}


// ================= LOAD TAHUN =================
function loadTahun(){

    fetch('/api/tahun-ajaran')

    .then(res => res.json())

    .then(res => {

        let html =
            '<option value="">Pilih Tahun</option>';

        res.data.forEach(t => {

            html += `
                <option value="${t.id_tahun_ajaran}">
                    ${t.periode} - ${t.semester}
                </option>
            `;
        });

        id_tahun_ajaran.innerHTML = html;
        edit_tahun.innerHTML = html;
    });
}


// ================= LOAD DATA =================
function loadData(){

    fetch('/api/kelas')

    .then(res => res.json())

    .then(res => {

        let html = '';

        if(!res.data.length){

            html = `
                <tr>
                    <td colspan="5">
                        Belum ada data
                    </td>
                </tr>
            `;
        }

        res.data.forEach(k => {

            html += `
                <tr>

                    <td>
                        ${k.nama_kelas}
                    </td>

                    <td>
                        ${k.pegawai?.nama_guru ?? '-'}
                    </td>

                    <td>
                        ${k.tahun_ajaran?.periode ?? '-'}
                    </td>

                    <td>
                        <b>${k.total_siswa}</b>
                    </td>

                    <td>

                        <button 
                            class="btn-edit" 
                            data-id="${k.id_kelas}">
                            Edit
                        </button>

                        <button 
                            class="btn-danger" 
                            data-id="${k.id_kelas}">
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


// ================= TAMBAH =================
function tambah(){

    if(
        !nama_kelas.value ||
        !total_siswa.value
    ){

        showNotif(
            'warning',
            'Isi semua data!'
        );

        return;
    }

    fetch('/api/kelas',{

        method:'POST',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({

            nama_kelas:
                nama_kelas.value,

            total_siswa:
                total_siswa.value,

            id_guru:
                id_guru.value || null,

            id_tahun_ajaran:
                id_tahun_ajaran.value
        })
    })

    .then(res => res.json())

    .then(res => {

        nama_kelas.value = '';
        total_siswa.value = '';

        showNotif(
            'success',
            'Berhasil tambah data kelas'
        );

        loadData();
    })

    .catch(err => {

        console.error(err);

        showNotif(
            'danger',
            'Gagal tambah data'
        );
    });
}


// ================= EDIT =================
function edit(id){

    editId = id;

    fetch('/api/kelas/' + id)

    .then(res => res.json())

    .then(res => {

        let d = res.data;

        edit_nama.value =
            d.nama_kelas;

        edit_total.value =
            d.total_siswa;

        edit_guru.value =
            d.id_guru ?? '';

        edit_tahun.value =
            d.id_tahun_ajaran;

        modal.style.display = 'flex';
    });
}


// ================= UPDATE =================
function update(){

    fetch('/api/kelas/' + editId,{

        method:'PUT',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({

            nama_kelas:
                edit_nama.value,

            total_siswa:
                edit_total.value,

            id_guru:
                edit_guru.value || null,

            id_tahun_ajaran:
                edit_tahun.value
        })
    })

    .then(res => res.json())

    .then(res => {

        if(res.success){

            tutup();

            showNotif(
                'edit',
                'Data kelas berhasil diperbarui'
            );

            loadData();

        }else{

            showNotif(
                'danger',
                res.message
            );
        }
    })

    .catch(err => {

        console.error(err);

        showNotif(
            'danger',
            'Gagal update data'
        );
    });
}


// ================= HAPUS =================
function hapus(id){

    if(confirm('Yakin hapus data?')){

        fetch('/api/kelas/' + id,{

            method:'DELETE'

        })

        .then(res => res.json())

        .then(res => {

            showNotif(
                'delete',
                'Berhasil hapus data'
            );

            loadData();
        })

        .catch(err => {

            console.error(err);

            showNotif(
                'danger',
                'Gagal hapus data'
            );
        });
    }
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


// ================= TUTUP =================
function tutup(){

    modal.style.display = 'none';
}

</script>
@endsection