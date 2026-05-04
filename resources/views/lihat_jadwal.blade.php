@extends('layouts.app')

@section('title','Jadwal Mengajar')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --bg:#f8fafc;
}

/* CARD */
.card{
    background:white;
    border-radius:14px;
    padding:25px;
    box-shadow:0 8px 25px rgba(0,0,0,0.05);
}

/* TITLE */
.card h2{
    margin-bottom:20px;
    color:var(--primary);
}

/* CONTAINER */
#jadwalContainer{
    display:flex;
    flex-direction:column;
    gap:20px;
}

/* HARI */
.jadwal-hari{
    background:#f9fafb;
    border-radius:12px;
    padding:15px;
}

/* TITLE HARI */
.jadwal-title{
    font-weight:bold;
    color:var(--primary);
    margin-bottom:10px;
    font-size:16px;
}

/* ITEM */
.jadwal-item{
    display:flex;
    gap:15px;
    padding:12px;
    border-radius:10px;
    background:white;
    margin-bottom:10px;
    border:1px solid var(--border);
    align-items:center;
}

/* JAM */
.jam{
    min-width:120px;
    font-weight:bold;
    color:var(--primary);
}

/* MAPEL */
.mapel{
    flex:1;
    font-weight:600;
}

/* INFO */
.info{
    font-size:12px;
    color:#555;
}

/* RESPONSIVE */
@media(max-width:768px){
    .jadwal-item{
        flex-direction:column;
        align-items:flex-start;
    }

    .jam{
        min-width:auto;
    }
}
</style>

<div class="card">

<h2>📅 Jadwal Mengajar</h2>

<div id="jadwalContainer">Loading...</div>

</div>

@endsection


@section('script')
<script>

// INIT
document.addEventListener('DOMContentLoaded', loadJadwal);


// LOAD JADWAL
function loadJadwal(){

    const user = JSON.parse(localStorage.getItem('user'));

    fetch('/api/jadwal')
    .then(res => res.json())
    .then(res => {

        let data = res.data;

        if(!data || data.length === 0){
            jadwalContainer.innerHTML = 'Tidak ada jadwal';
            return;
        }

        // FILTER GURU LOGIN
        if(user){
            data = data.filter(j => j.id_guru == user.id);
        }

        // GROUP BY HARI
        let hariMap = {};

        data.forEach(j => {
            if(!hariMap[j.hari]){
                hariMap[j.hari] = [];
            }
            hariMap[j.hari].push(j);
        });

        let html = '';

        Object.keys(hariMap).forEach(hari => {

            html += `
            <div class="jadwal-hari">
                <div class="jadwal-title">📌 ${hari}</div>
            `;

            hariMap[hari].forEach(j => {

                html += `
                <div class="jadwal-item">

                    <div class="jam">
                        ⏰ ${j.jam_mulai} - ${j.jam_selesai}
                    </div>

                    <div class="mapel">
                        📘 ${j.mapel?.nama_mapel ?? '-'}
                    </div>

                    <div class="info">
                        👨‍🏫 ${j.guru?.nama_guru ?? '-'} <br>
                        🏫 ${j.kelas?.nama_kelas ?? '-'} <br>
                        📅 ${j.tahun_ajaran?.periode ?? '-'}
                    </div>

                </div>
                `;
            });

            html += `</div>`;
        });

        jadwalContainer.innerHTML = html;
    })
    .catch(() => {
        jadwalContainer.innerHTML = 'Gagal load data';
    });
}

</script>
@endsection