@extends('layouts.app')

@section('title','Data Mapel')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --danger:#dc2626;
}

/* CARD */
.card{
    background:white;
    border-radius:16px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

/* FORM */
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
}

/* BUTTON */
button{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    color:white;
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

/* TABLE */
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

/* MODAL */
.modal{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
    display:none;
    justify-content:center;
    align-items:center;
}

.modal-content{
    background:white;
    padding:20px;
    border-radius:12px;
    width:400px;
}

.modal.show{
    display:flex;
}
</style>



<div class="card">

    <h3>Data Mata Pelajaran</h3>

    <!-- FORM TAMBAH -->
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


    <!-- TABLE -->
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

        <h4>Edit Mapel</h4>

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
            class="btn-danger"
        >
            Batal
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

        alert(
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

        alert('Berhasil tambah');

        nama_mapel.value = '';
        kode_mapel.value = '';

        loadData();
    })

    .catch(err => {

        console.error(err);

        alert('Gagal tambah data');
    });
}



// ================= OPEN MODAL =================
function openModal(id){

    let m = allData.find(
        x => x.id_mapel == id
    );

    if(!m){

        alert('Data tidak ditemukan');

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

        alert('ID tidak ditemukan');

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

            alert('Berhasil update');

            closeModal();

            loadData();

        }catch{

            console.error(text);

            alert('Server error');
        }
    })

    .catch(err => {

        console.error(err);

        alert('Gagal update data');
    });
}



// ================= HAPUS =================
function hapus(id){

    if(
        confirm('Yakin hapus data?')
    ){

        fetch('/api/mapel/' + id,{

            method:'DELETE'
        })

        .then(res => res.json())

        .then(res => {

            alert('Berhasil hapus');

            loadData();
        })

        .catch(err => {

            console.error(err);

            alert('Gagal hapus data');
        });
    }
}

</script>
@endsection