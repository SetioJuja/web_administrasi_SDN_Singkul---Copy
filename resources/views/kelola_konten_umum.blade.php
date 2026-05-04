@extends('layouts.app')

@section('title','Konten Umum Sekolah')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --success:#16a34a;
    --danger:#dc2626;
}

.card{
    max-width:900px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

h3{
    margin-bottom:20px;
    color:var(--primary);
}

.form-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(250px,1fr));
    gap:12px;
}

.full{
    grid-column: span 2;
}

input, textarea{
    width:100%;
    padding:10px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
}

textarea{
    min-height:90px;
}

input:focus, textarea:focus{
    border-color:var(--primary);
}

button{
    margin-top:15px;
    padding:12px;
    width:100%;
    border:none;
    border-radius:8px;
    background:var(--primary);
    color:white;
    font-weight:600;
    cursor:pointer;
}

button:hover{
    opacity:0.9;
}

.alert{
    margin-top:10px;
    padding:10px;
    border-radius:8px;
    display:none;
    font-size:13px;
}

.success{
    background:#dcfce7;
    color:#166534;
}

.error{
    background:#fee2e2;
    color:#991b1b;
}
</style>

<div class="card">

<h3>🏫 Konten Umum Sekolah</h3>

<form id="formKonten">

    <!-- ID untuk update -->
    <input type="hidden" id="id_konten">

    <div class="form-grid">

        <textarea id="visi" name="visi" class="full" placeholder="Visi Sekolah" required></textarea>

        <textarea id="misi" name="misi" class="full" placeholder="Misi Sekolah" required></textarea>

        <input type="text" id="akreditasi" name="akreditasi" placeholder="Akreditasi">

        <input type="text" id="telepon" name="telepon" placeholder="Telepon">

        <input type="email" id="email" name="email" placeholder="Email">

        <input type="text" id="jam_operasional" name="jam_operasional" placeholder="Jam Operasional">

        <input type="number" id="total_guru" name="total_guru" placeholder="Total Guru">

        <input type="number" id="total_siswa" name="total_siswa" placeholder="Total Siswa">

        <textarea id="alamat" name="alamat" class="full" placeholder="Alamat Sekolah"></textarea>

    </div>

    <button type="submit">💾 Simpan</button>

</form>

<div id="alertSuccess" class="alert success"></div>
<div id="alertError" class="alert error"></div>

</div>

@endsection


@section('script')
<script>

const form = document.getElementById('formKonten');
const alertSuccess = document.getElementById('alertSuccess');
const alertError = document.getElementById('alertError');

let kontenId = null;

/* ================= LOAD DATA ================= */
async function loadData() {
    try {
        const res = await fetch('/api/konten-umum');
        const result = await res.json();

        if(result.data){

            const d = result.data;

            kontenId = d.id;

            document.getElementById('id_konten').value = d.id;
            visi.value = d.visi || '';
            misi.value = d.misi || '';
            akreditasi.value = d.akreditasi || '';
            telepon.value = d.telepon || '';
            email.value = d.email || '';
            jam_operasional.value = d.jam_operasional || '';
            total_guru.value = d.total_guru || '';
            total_siswa.value = d.total_siswa || '';
            alamat.value = d.alamat || '';
        }

    } catch (e) {
        console.log('Gagal load data');
    }
}

/* ================= SUBMIT ================= */
form.addEventListener('submit', async function(e) {
    e.preventDefault();

    const data = {
        visi: visi.value,
        misi: misi.value,
        akreditasi: akreditasi.value,
        alamat: alamat.value,
        telepon: telepon.value,
        email: email.value,
        jam_operasional: jam_operasional.value,
        total_guru: total_guru.value,
        total_siswa: total_siswa.value
    };

    try {

        let url = '/api/konten-umum';
        let method = 'POST';

        // 👉 jika ada ID = UPDATE
        if(kontenId){
            url = `/api/konten-umum/${kontenId}`;
            method = 'PUT';
        }

        const response = await fetch(url, {
            method: method,
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if(response.ok){

            alertSuccess.style.display = 'block';
            alertSuccess.innerText = result.message || 'Berhasil disimpan';

            alertError.style.display = 'none';

            loadData(); // refresh data

        } else {

            alertError.style.display = 'block';
            alertError.innerText = result.message || 'Terjadi kesalahan';

            alertSuccess.style.display = 'none';
        }

    } catch (error) {

        alertError.style.display = 'block';
        alertError.innerText = 'Gagal terhubung ke server';
    }
});

/* ================= INIT ================= */
loadData();

</script>
@endsection