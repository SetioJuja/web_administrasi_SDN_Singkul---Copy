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
    max-width:950px;
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
    gap:15px;
}

.full{
    grid-column: span 2;
}

input,
textarea{
    width:100%;
    padding:10px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    font-size:14px;
    transition:0.2s;
}

textarea{
    min-height:90px;
    resize:none;
}

input:focus,
textarea:focus{
    border-color:var(--primary);
}

button{
    margin-top:20px;
    padding:12px;
    width:100%;
    border:none;
    border-radius:10px;
    background:var(--primary);
    color:white;
    font-weight:600;
    cursor:pointer;
    font-size:15px;
    transition:0.2s;
}

button:hover{
    opacity:0.9;
}

.alert{
    margin-top:15px;
    padding:12px;
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

.preview-box{
    margin-top:10px;
}

.preview-box img{
    width:100%;
    max-width:250px;
    height:180px;
    border-radius:12px;
    border:1px solid #ddd;
    object-fit:cover;
    background:#f8fafc;
}

label{
    font-size:14px;
    font-weight:600;
    color:#374151;
}

@media(max-width:768px){

    .full{
        grid-column: span 1;
    }

    .card{
        padding:18px;
    }
}
</style>

<div class="card">

    <h3>Konten Umum Sekolah</h3>

    <form id="formKonten" enctype="multipart/form-data">

        <input type="hidden" id="id_konten">

        <div class="form-grid">

            <!-- VISI -->
            <textarea 
                id="visi" 
                name="visi" 
                class="full" 
                placeholder="Visi Sekolah" 
                required
            ></textarea>

            <!-- MISI -->
            <textarea 
                id="misi" 
                name="misi" 
                class="full" 
                placeholder="Misi Sekolah" 
                required
            ></textarea>

            <!-- AKREDITASI -->
            <input 
                type="text" 
                id="akreditasi" 
                name="akreditasi" 
                placeholder="Akreditasi"
            >

            <!-- TELEPON -->
            <input 
                type="text" 
                id="telepon" 
                name="telepon" 
                placeholder="Telepon"
            >

            <!-- EMAIL -->
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Email"
            >

            <!-- JAM -->
            <input 
                type="text" 
                id="jam_operasional" 
                name="jam_operasional" 
                placeholder="Jam Operasional"
            >

            <!-- TOTAL GURU -->
            <input 
                type="number" 
                id="total_guru" 
                name="total_guru" 
                placeholder="Total Guru"
            >

            <!-- TOTAL SISWA -->
            <input 
                type="number" 
                id="total_siswa" 
                name="total_siswa" 
                placeholder="Total Siswa"
            >

            <!-- ALAMAT -->
            <textarea 
                id="alamat" 
                name="alamat" 
                class="full" 
                placeholder="Alamat Sekolah"
            ></textarea>

            <!-- GAMBAR LOGIN -->
            <div>

                <label>
                    Gambar Login
                </label>

                <input
                    type="file"
                    id="gambar_login"
                    name="gambar_login"
                    accept="image/*"
                >

                <div class="preview-box">

                    <img 
                        id="previewImage" 
                        src="" 
                        style="display:none;"
                    >

                </div>

            </div>

            <!-- GAMBAR BERANDA -->
            <div>

                <label>
                    Gambar Beranda
                </label>

                <input
                    type="file"
                    id="gambar_beranda"
                    name="gambar_beranda"
                    accept="image/*"
                >

                <div class="preview-box">

                    <img 
                        id="previewBeranda" 
                        src="" 
                        style="display:none;"
                    >

                </div>

            </div>

        </div>

        <button type="submit">
            Simpan Data
        </button>

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

const previewImage = document.getElementById('previewImage');
const previewBeranda = document.getElementById('previewBeranda');

let kontenId = null;

/* =========================================
   PREVIEW GAMBAR LOGIN
========================================= */

gambar_login.addEventListener('change', function(){

    const file = this.files[0];

    if(file){

        previewImage.src = URL.createObjectURL(file);
        previewImage.style.display = 'block';
    }
});

/* =========================================
   PREVIEW GAMBAR BERANDA
========================================= */

gambar_beranda.addEventListener('change', function(){

    const file = this.files[0];

    if(file){

        previewBeranda.src = URL.createObjectURL(file);
        previewBeranda.style.display = 'block';
    }
});

/* =========================================
   LOAD DATA
========================================= */

async function loadData(){

    try{

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

            // gambar login
            if(d.gambar_login){

                previewImage.src = d.gambar_login;
                previewImage.style.display = 'block';
            }

            // gambar beranda
            if(d.gambar_beranda){

                previewBeranda.src = d.gambar_beranda;
                previewBeranda.style.display = 'block';
            }
        }

    }catch(error){

        console.log('Gagal load data');
    }
}

/* =========================================
   SUBMIT
========================================= */

form.addEventListener('submit', async function(e){

    e.preventDefault();

    const formData = new FormData();

    formData.append('visi', visi.value);
    formData.append('misi', misi.value);
    formData.append('akreditasi', akreditasi.value);
    formData.append('alamat', alamat.value);
    formData.append('telepon', telepon.value);
    formData.append('email', email.value);
    formData.append('jam_operasional', jam_operasional.value);
    formData.append('total_guru', total_guru.value);
    formData.append('total_siswa', total_siswa.value);

    // gambar login
    if(gambar_login.files[0]){

        formData.append(
            'gambar_login',
            gambar_login.files[0]
        );
    }

    // gambar beranda
    if(gambar_beranda.files[0]){

        formData.append(
            'gambar_beranda',
            gambar_beranda.files[0]
        );
    }

    try{

        let url = '/api/konten-umum';

        let method = 'POST';

        if(kontenId){

            url = `/api/konten-umum/${kontenId}`;

            formData.append('_method', 'PUT');
        }

        const response = await fetch(url,{
            method: method,
            body: formData
        });

        const result = await response.json();

        if(response.ok){

            alertSuccess.style.display = 'block';
            alertSuccess.innerText =
                result.message || 'Berhasil disimpan';

            alertError.style.display = 'none';

            loadData();

        }else{

            alertError.style.display = 'block';

            alertError.innerText =
                result.message || 'Terjadi kesalahan';

            alertSuccess.style.display = 'none';
        }

    }catch(error){

        alertError.style.display = 'block';

        alertError.innerText =
            'Gagal terhubung ke server';

        alertSuccess.style.display = 'none';
    }
});

/* =========================================
   INIT
========================================= */

loadData();

</script>
@endsection