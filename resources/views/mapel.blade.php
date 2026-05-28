@extends('layouts.app')

@section('title','Data Mapel')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --danger:#dc2626;
}

/* ================= CARD ================= */
.card{
    background:white;
    border-radius:16px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

/* ================= FORM ================= */
.form-inline{
    display:flex;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
}

input{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    flex:1;
    outline:none;
}

input:focus{
    border-color:var(--primary);
}

/* ================= BUTTON ================= */
button{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    color:white;
    transition:.2s;
}

button:hover{
    opacity:0.9;
}

.btn-primary{
    background:var(--primary);
}

.btn-danger{
    background:var(--danger);
}

.btn-warning{
    background:#f59e0b;
}

.btn-secondary{
    background:#6b7280;
}

/* ================= TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
}

th,
td{
    border:1px solid var(--border);
    padding:10px;
    text-align:center;
}

thead{
    background:#f8fafc;
}

tbody tr:hover{
    background:#f9fafb;
}

/* ================= MODAL ================= */
.modal{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.45);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
}

.modal-content{
    background:white;
    padding:24px;
    border-radius:16px;
    width:400px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
    animation:fade .2s ease;
}

.modal.show{
    display:flex;
}

/* ================= NOTIF ================= */
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
        transform:scale(.95);
    }
    to{
        opacity:1;
        transform:scale(1);
    }
}
</style>



<div class="card">

    <h3 style="
        margin-bottom:20px;
        color:var(--primary);
    ">
        Data Mata Pelajaran
    </h3>

    <!-- ================= FORM TAMBAH ================= -->
    <div class="form-inline">

        <input
            id="nama_mapel"
            placeholder="Nama Mapel"
        >

        <input
            id="kode_mapel"
            placeholder="Kode Mapel"
        >

        <button
            id="btnTambah"
            class="btn-primary"
        >
            Tambah
        </button>

    </div>


    <!-- ================= TABLE ================= -->
    <table>

        <thead>

            <tr>

                <th>Nama</th>

                <th>Kode</th>

                <th>Aksi</th>

            </tr>

        </thead>

        <tbody id="data"></tbody>

    </table>

</div>



<!-- ================= MODAL EDIT ================= -->
<div class="modal" id="modalEdit">

    <div class="modal-content">

        <h3 style="
            margin-bottom:18px;
            color:var(--primary);
        ">
            Edit Mapel
        </h3>

        <input
            type="hidden"
            id="edit_id"
        >

        <input
            id="edit_nama"
            placeholder="Nama Mapel"
            style="margin-bottom:10px;"
        >

        <input
            id="edit_kode"
            placeholder="Kode Mapel"
            style="margin-bottom:15px;"
        >

        <button
            onclick="updateData()"
            class="btn-primary"
        >
            Update
        </button>

        <button
            onclick="closeModal()"
            class="btn-secondary"
            style="margin-top:8px;"
        >
            Batal
        </button>

    </div>

</div>



<!-- ================= MODAL NOTIFIKASI ================= -->
<div id="modalNotif" class="modal">

<div class="modal-content"
     style="
        width:340px;
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

let allData = [];


// ================= INIT =================
document.addEventListener(
    'DOMContentLoaded',
    () => {

        loadData();

        btnTambah.onclick = tambah;
    }
);


// ================= LOAD DATA =================
function loadData(){

    fetch('/api/mapel')

    .then(res => res.json())

    .then(res => {

        allData = res.data;

        let html = '';

        if(
            !res.data ||
            res.data.length === 0
        ){

            html = `
            <tr>
                <td colspan="3">
                    Belum ada data
                </td>
            </tr>
            `;

        }else{

            res.data.forEach(m => {

                html += `
                <tr>

                    <td>
                        ${m.nama_mapel}
                    </td>

                    <td>
                        ${m.kode_mapel}
                    </td>

                    <td>

                        <button
                            class="btn-warning"

                            onclick="
                                openModal(
                                    ${m.id_mapel}
                                )
                            "
                        >
                            Edit
                        </button>

                        <button
                            class="btn-danger"

                            onclick="
                                hapus(
                                    ${m.id_mapel}
                                )
                            "
                        >
                            Hapus
                        </button>

                    </td>

                </tr>
                `;
            });

        }

        data.innerHTML = html;
    });
}



// ================= TAMBAH =================
function tambah(){

    if(
        !nama_mapel.value ||
        !kode_mapel.value
    ){

        showNotif(
            'warning',
            'Semua field wajib diisi'
        );

        return;
    }

    fetch('/api/mapel',{

        method:'POST',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({

            nama_mapel:
                nama_mapel.value,

            kode_mapel:
                kode_mapel.value
        })
    })

    .then(res => res.json())

    .then(res => {

        showNotif(
            'success',
            'Berhasil tambah data'
        );

        nama_mapel.value = '';
        kode_mapel.value = '';

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



// ================= OPEN MODAL =================
function openModal(id){

    let m = allData.find(
        x => x.id_mapel == id
    );

    if(!m){

        showNotif(
            'danger',
            'Data tidak ditemukan'
        );

        return;
    }

    edit_id.value =
        m.id_mapel;

    edit_nama.value =
        m.nama_mapel;

    edit_kode.value =
        m.kode_mapel;

    modalEdit.classList.add('show');
}



// ================= CLOSE MODAL =================
function closeModal(){

    modalEdit.classList.remove('show');
}



// ================= UPDATE =================
function updateData(){

    let id = edit_id.value;

    if(!id){

        showNotif(
            'danger',
            'ID tidak ditemukan'
        );

        return;
    }

    fetch('/api/mapel/' + id,{

        method:'POST',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({

            _method:'PUT',

            nama_mapel:
                edit_nama.value,

            kode_mapel:
                edit_kode.value
        })
    })

    .then(async res => {

        let text = await res.text();

        try{

            JSON.parse(text);

            showNotif(
                'edit',
                'Berhasil update data'
            );

            closeModal();

            loadData();

        }catch{

            console.error(text);

            showNotif(
                'danger',
                'Server error'
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
        fetch('/api/mapel/' + id,{

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


function tutupNotif(){

    document.getElementById('modalNotif')
        .style.display = 'none';
}

</script>
@endsection