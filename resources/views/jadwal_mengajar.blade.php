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

input, select{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
}

input:focus, select:focus{
    border-color:var(--primary);
}

/* ===== BUTTON ===== */
button{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    color:white;
}

.btn-primary{ background:var(--primary); }
.btn-danger{ background:var(--danger); }

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

th, td{
    border:1px solid var(--border);
    padding:10px;
    text-align:center;
}

tbody tr:hover{
    background:#f9fafb;
}

/* ===== EMPTY ===== */
.empty{
    text-align:center;
    padding:20px;
    color:#999;
}
</style>

<div class="card">

<h3>🗓️ Jadwal Mengajar</h3>

<!-- FORM -->
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

    <input id="jam_mulai" type="time">
    <input id="jam_selesai" type="time">

    <select id="guru"></select>
    <select id="kelas"></select>
    <select id="mapel"></select>
    <select id="tahun"></select>

</div>

<button id="btnTambah" class="btn-primary">Tambah</button>

<!-- TABLE -->
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

@endsection


@section('script')
<script>

// ================= INIT =================
document.addEventListener('DOMContentLoaded', () => {

    loadGuru();
    loadKelas();
    loadMapel();
    loadTahunAktif();
    loadData();

    btnTambah.addEventListener('click', tambah);
});


// ================= LOAD GURU =================
function loadGuru(){
    fetch('/api/pegawai/guru-mapel')
    .then(res=>res.json())
    .then(res=>{

        let html='<option value="">Pilih Guru</option>';

        res.data.forEach(g=>{
            html+=`<option value="${g.id_guru}">
                ${g.nama_guru}
            </option>`;
        });

        guru.innerHTML = html;
    });
}


// ================= LOAD KELAS =================
function loadKelas(){
    fetch('/api/kelas')
    .then(res=>res.json())
    .then(res=>{

        let html='<option value="">Pilih Kelas</option>';

        res.data.forEach(k=>{
            html+=`<option value="${k.id_kelas}">
                ${k.nama_kelas}
            </option>`;
        });

        kelas.innerHTML = html;
    });
}


// ================= LOAD MAPEL =================
function loadMapel(){
    fetch('/api/mapel')
    .then(res=>res.json())
    .then(res=>{

        let html='<option value="">Pilih Mapel</option>';

        res.data.forEach(m=>{
            html+=`<option value="${m.id_mapel}">
                ${m.nama_mapel}
            </option>`;
        });

        mapel.innerHTML = html;
    });
}


// ================= LOAD TAHUN AKTIF =================
function loadTahunAktif(){
    fetch('/api/tahun-ajaran/aktif')
    .then(res=>res.json())
    .then(res=>{

        let html='<option value="">Pilih Tahun</option>';

        res.data.forEach(t=>{
            html+=`<option value="${t.id_tahun_ajaran}">
                ${t.periode} (${t.semester})
            </option>`;
        });

        tahun.innerHTML = html;
    });
}


// ================= LOAD DATA =================
function loadData(){
    fetch('/api/jadwal')
    .then(res=>res.json())
    .then(res=>{

        let html='';

        if(!res.data.length){
            html = `<tr><td colspan="7" class="empty">Belum ada jadwal</td></tr>`;
        }

        res.data.forEach(j=>{
            html+=`
            <tr>
                <td>${j.hari}</td>
                <td>${j.jam_mulai} - ${j.jam_selesai}</td>
                <td>${j.guru?.nama_guru ?? '-'}</td>
                <td>${j.kelas?.nama_kelas ?? '-'}</td>
                <td>${j.mapel?.nama_mapel ?? '-'}</td>
                <td>${j.tahun_ajaran?.periode ?? '-'}</td>
                <td>
                    <button class="btn-danger" data-id="${j.id_jadwal}">
                        Hapus
                    </button>
                </td>
            </tr>`;
        });

        data.innerHTML = html;

        document.querySelectorAll('.btn-danger').forEach(btn=>{
            btn.onclick = () => hapus(btn.dataset.id);
        });

    });
}


// ================= TAMBAH =================
function tambah(){

    if(!hari.value || !jam_mulai.value || !jam_selesai.value ||
       !guru.value || !kelas.value || !mapel.value || !tahun.value){
        alert('Semua field wajib diisi!');
        return;
    }

    fetch('/api/jadwal',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'Accept':'application/json'
        },
        body: JSON.stringify({
            hari: hari.value,
            jam_mulai: jam_mulai.value,
            jam_selesai: jam_selesai.value,
            id_guru: guru.value,
            id_kelas: kelas.value,
            id_mapel: mapel.value,
            id_tahun_ajaran: tahun.value
        })
    })
    .then(res=>res.json())
    .then(res=>{

        if(res.success){
            alert('Berhasil tambah');
            loadData();
        }else{
            alert('Gagal: ' + (res.message || 'Unknown error'));
        }

    })
    .catch(()=>{
        alert('Server error');
    });
}


// ================= HAPUS =================
function hapus(id){
    if(confirm('Yakin hapus jadwal?')){
        fetch('/api/jadwal/'+id,{method:'DELETE'})
        .then(()=>loadData());
    }
}

</script>
@endsection