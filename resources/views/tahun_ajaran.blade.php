@extends('layouts.app')

@section('title','Tahun Ajaran')

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
.form-inline{
    display:flex;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
}

input, select{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    min-width:180px;
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

/* ===== BADGE STATUS ===== */
.badge{
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:500;
}

.badge-active{
    background:#dcfce7;
    color:#15803d;
}

.badge-nonaktif{
    background:#fee2e2;
    color:#b91c1c;
}

/* ===== EMPTY ===== */
.empty{
    padding:20px;
    text-align:center;
    color:#999;
}
</style>

<div class="card">

<h3>📅 Tahun Ajaran</h3>

<!-- FORM -->
<div class="form-inline">
    <input id="periode" placeholder="Contoh: 2024/2025">

    <select id="semester">
        <option value="">Semester</option>
        <option value="ganjil">Ganjil</option>
        <option value="genap">Genap</option>
    </select>

    <select id="status">
        <option value="nonaktif">Nonaktif</option>
        <option value="aktif">Aktif</option>
    </select>

    <button id="btnTambah" class="btn-primary">Simpan</button>
</div>

<!-- TABLE -->
<table>
<thead>
<tr>
<th>Periode</th>
<th>Semester</th>
<th>Status</th>
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
    loadData();

    document.getElementById('btnTambah')
        .addEventListener('click', tambah);
});


// ================= LOAD DATA =================
function loadData(){
    fetch('/api/tahun-ajaran')
    .then(res => res.json())
    .then(res => {

        let html = '';

        if(!res.data || res.data.length === 0){
            html = `<tr><td colspan="4" class="empty">Belum ada data</td></tr>`;
        } else {

            res.data.forEach(d => {

                let badge = d.status === 'aktif'
                    ? `<span class="badge badge-active">Aktif</span>`
                    : `<span class="badge badge-nonaktif">Nonaktif</span>`;

                html += `
                    <tr>
                        <td>${d.periode}</td>
                        <td>${d.semester}</td>
                        <td>${badge}</td>
                        <td>
                            <button class="btn-danger" data-id="${d.id_tahun_ajaran}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

        document.getElementById('data').innerHTML = html;

        document.querySelectorAll('.btn-danger').forEach(btn => {
            btn.onclick = () => hapus(btn.dataset.id);
        });

    })
    .catch(err => {
        console.error('Load error:', err);
    });
}


// ================= TAMBAH =================
function tambah(){

    const periodeEl = document.getElementById('periode');
    const semesterEl = document.getElementById('semester');
    const statusEl = document.getElementById('status');

    if(!periodeEl.value || !semesterEl.value){
        alert('Periode & Semester wajib diisi!');
        return;
    }

    fetch('/api/tahun-ajaran', {
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'Accept':'application/json'
        },
        body: JSON.stringify({
            periode: periodeEl.value,
            semester: semesterEl.value,
            status: statusEl.value
        })
    })
    .then(async res => {

        const data = await res.json();

        if(!res.ok){
            alert(data.message || JSON.stringify(data.errors));
            return;
        }

        alert('Berhasil tambah');

        periodeEl.value = '';
        semesterEl.value = '';

        loadData();
    })
    .catch(err => {
        console.error(err);
        alert('Gagal koneksi ke server');
    });
}


// ================= HAPUS =================
function hapus(id){
    if(confirm('Yakin hapus data?')){
        fetch('/api/tahun-ajaran/' + id, {method:'DELETE'})
        .then(() => loadData())
        .catch(err => console.error(err));
    }
}

</script>
@endsection