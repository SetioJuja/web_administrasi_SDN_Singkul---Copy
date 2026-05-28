@extends('layouts.app')

@section('title','Jadwal Mengajar')

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

.form-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
    gap:10px;
    margin-bottom:15px;
}

input,
select{
    padding:10px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
}

button{
    padding:10px;
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

.btn-secondary{
    background:#64748b;
}

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
    background:#f1f5f9;
}

tbody tr:hover{
    background:#f9fafb;
}

/* ===== MODAL ===== */
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
    animation:fade .2s ease;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.modal.show{
    display:flex;
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

<h3>Jadwal Mengajar</h3>

<div class="form-grid">

<select id="hari">
<option value="">Pilih Hari</option>
<option>Senin</option>
<option>Selasa</option>
<option>Rabu</option>
<option>Kamis</option>
<option>Jumat</option>
<option>Sabtu</option>
</select>

<select id="jam_mulai">
<option value="">Pilih Jam Mulai</option>

<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>

</select>

<select id="jam_selesai">
<option value="">Pilih Jam Selesai</option>

<option value="06:00">06:00</option>
<option value="07:00">07:00</option>
<option value="08:00">08:00</option>
<option value="09:00">09:00</option>
<option value="10:00">10:00</option>
<option value="11:00">11:00</option>
<option value="12:00">12:00</option>
<option value="13:00">13:00</option>
<option value="14:00">14:00</option>
<option value="15:00">15:00</option>
<option value="16:00">16:00</option>
<option value="17:00">17:00</option>
<option value="18:00">18:00</option>

</select>

<select id="guru"></select>

<select id="kelas"></select>

<select id="mapel"></select>

<select id="tahun"></select>

</div>

<button
    id="btnTambah"
    class="btn-primary"
    style="margin-bottom:20px;">
    Tambah
</button>

<table>

<thead>

<tr>
<th>Hari</th>
<th>Jam</th>
<th>Guru</th>
<th>Kelas</th>
<th>Mapel</th>
<th>Tahun</th>
<th>Aksi</th>
</tr>

</thead>

<tbody id="data"></tbody>

</table>

</div>



<!-- ================= MODAL EDIT ================= -->
<div class="modal" id="modalEdit">

<div class="modal-content">

<h4 style="
    margin-bottom:18px;
    color:var(--primary);
">
    Edit Jadwal
</h4>

<input type="hidden" id="edit_id">

<select id="edit_hari">
<option>Senin</option>
<option>Selasa</option>
<option>Rabu</option>
<option>Kamis</option>
<option>Jumat</option>
<option>Sabtu</option>
</select>

<br><br>

<input id="edit_jam_mulai" type="time">

<br><br>

<input id="edit_jam_selesai" type="time">

<br><br>

<select id="edit_guru"></select>

<br><br>

<select id="edit_kelas"></select>

<br><br>

<select id="edit_mapel"></select>

<br><br>

<select id="edit_tahun"></select>

<br><br>

<button
    onclick="updateData()"
    class="btn-primary">
    Update
</button>

<button
    onclick="closeModal()"
    class="btn-secondary"
    style="margin-top:8px;">
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

let allData = [];

document.addEventListener('DOMContentLoaded', () => {

    loadGuru();
    loadKelas();
    loadMapel();
    loadTahunAktif();
    loadData();

    btnTambah.onclick = tambah;
});


// ================= LOAD MASTER =================
function loadGuru(){

    fetch('/api/pegawai/guru-mapel')

    .then(res => res.json())

    .then(res => {

        let html =
            '<option value="">Pilih Guru</option>';

        res.data.forEach(g => {

            html += `
                <option value="${g.id_guru}">
                    ${g.nama_guru}
                </option>
            `;
        });

        guru.innerHTML = html;
        edit_guru.innerHTML = html;
    });
}


function loadKelas(){

    fetch('/api/kelas')

    .then(res => res.json())

    .then(res => {

        let html =
            '<option value="">Pilih Kelas</option>';

        res.data.forEach(k => {

            html += `
                <option value="${k.id_kelas}">
                    ${k.nama_kelas}
                </option>
            `;
        });

        kelas.innerHTML = html;
        edit_kelas.innerHTML = html;
    });
}


function loadMapel(){

    fetch('/api/mapel')

    .then(res => res.json())

    .then(res => {

        let html =
            '<option value="">Pilih Mapel</option>';

        res.data.forEach(m => {

            html += `
                <option value="${m.id_mapel}">
                    ${m.nama_mapel}
                </option>
            `;
        });

        mapel.innerHTML = html;
        edit_mapel.innerHTML = html;
    });
}


function loadTahunAktif(){

    fetch('/api/tahun-ajaran/aktif')

    .then(res => res.json())

    .then(res => {

        let html =
            '<option value="">Pilih Tahun</option>';

        res.data.forEach(t => {

            html += `
                <option value="${t.id_tahun_ajaran}">
                    ${t.periode} (${t.semester})
                </option>
            `;
        });

        tahun.innerHTML = html;
        edit_tahun.innerHTML = html;
    });
}


// ================= LOAD DATA =================
function loadData(){

    fetch('/api/jadwal')

    .then(res => res.json())

    .then(res => {

        allData = res.data;

        const urutanHari = {
            Senin:1,
            Selasa:2,
            Rabu:3,
            Kamis:4,
            Jumat:5,
            Sabtu:6
        };

        allData.sort((a,b)=>{

            if(
                urutanHari[a.hari]
                !==
                urutanHari[b.hari]
            ){

                return (
                    urutanHari[a.hari]
                    -
                    urutanHari[b.hari]
                );
            }

            return a.jam_mulai
                .localeCompare(b.jam_mulai);
        });

        let html = '';

        if(!allData.length){

            html = `
                <tr>
                    <td colspan="7">
                        Belum ada jadwal
                    </td>
                </tr>
            `;
        }

        allData.forEach(j => {

            html += `
            <tr>

                <td>${j.hari}</td>

                <td>
                    ${j.jam_mulai}
                    -
                    ${j.jam_selesai}
                </td>

                <td>
                    ${j.guru?.nama_guru ?? '-'}
                </td>

                <td>
                    ${j.kelas?.nama_kelas ?? '-'}
                </td>

                <td>
                    ${j.mapel?.nama_mapel ?? '-'}
                </td>

                <td>
                    ${j.tahun_ajaran?.periode ?? '-'}
                </td>

                <td>

                    <button
                        onclick="openModal(${j.id_jadwal})"
                        class="btn-primary">
                        Edit
                    </button>

                    <button
                        onclick="hapus(${j.id_jadwal})"
                        class="btn-danger">
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

    fetch('/api/jadwal',{

        method:'POST',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({

            hari:
                hari.value,

            jam_mulai:
                jam_mulai.value,

            jam_selesai:
                jam_selesai.value,

            id_guru:
                guru.value,

            id_kelas:
                kelas.value,

            id_mapel:
                mapel.value,

            id_tahun_ajaran:
                tahun.value
        })
    })

    .then(res => res.json())

    .then(res => {

        showNotif(
            'success',
            'Jadwal berhasil ditambahkan'
        );

        loadData();
    })

    .catch(err => {

        console.error(err);

        showNotif(
            'danger',
            'Gagal tambah jadwal'
        );
    });
}


// ================= EDIT =================
function openModal(id){

    let j =
        allData.find(
            x => x.id_jadwal == id
        );

    edit_id.value =
        j.id_jadwal;

    edit_hari.value =
        j.hari;

    edit_jam_mulai.value =
        j.jam_mulai;

    edit_jam_selesai.value =
        j.jam_selesai;

    edit_guru.value =
        j.id_guru;

    edit_kelas.value =
        j.id_kelas;

    edit_mapel.value =
        j.id_mapel;

    edit_tahun.value =
        j.id_tahun_ajaran;

    modalEdit.classList.add('show');
}


function closeModal(){

    modalEdit.classList.remove('show');
}


// ================= UPDATE =================
function updateData(){

    fetch('/api/jadwal/' + edit_id.value,{

        method:'POST',

        headers:{
            'Content-Type':'application/json'
        },

        body: JSON.stringify({

            _method:'PUT',

            hari:
                edit_hari.value,

            jam_mulai:
                edit_jam_mulai.value,

            jam_selesai:
                edit_jam_selesai.value,

            id_guru:
                edit_guru.value,

            id_kelas:
                edit_kelas.value,

            id_mapel:
                edit_mapel.value,

            id_tahun_ajaran:
                edit_tahun.value
        })
    })

    .then(res => res.json())

    .then(res => {

        closeModal();

        showNotif(
            'edit',
            'Jadwal berhasil diperbarui'
        );

        loadData();
    })

    .catch(err => {

        console.error(err);

        showNotif(
            'danger',
            'Gagal update jadwal'
        );
    });
}


// ================= HAPUS =================
function hapus(id){

    fetch('/api/jadwal/' + id,{

        method:'DELETE'
    })

    .then(res => res.json())

    .then(res => {

        showNotif(
            'delete',
            'Jadwal berhasil dihapus'
        );

        loadData();
    })

    .catch(err => {

        console.error(err);

        showNotif(
            'danger',
            'Gagal hapus jadwal'
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