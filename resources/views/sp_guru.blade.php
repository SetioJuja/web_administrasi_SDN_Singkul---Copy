@extends('layouts.app')

@section('title','Presensi Guru')

@section('content')

<div class="card">

<h3 style="margin-bottom:15px;color:#0a3d62;">📊 Presensi Guru</h3>

<!-- FILTER -->
<div class="filter-row">
    <input type="text" id="searchGuru" placeholder="Cari guru...">

    <select id="filter_tahun"></select>

    <select id="filterBulan">
        <option value="">Semua Bulan</option>
        <option value="01">Jan</option>
        <option value="02">Feb</option>
        <option value="03">Mar</option>
        <option value="04">Apr</option>
        <option value="05">Mei</option>
        <option value="06">Jun</option>
        <option value="07">Jul</option>
        <option value="08">Agt</option>
        <option value="09">Sep</option>
        <option value="10">Okt</option>
        <option value="11">Nov</option>
        <option value="12">Des</option>
    </select>
</div>

<div id="presensiContainer">
    <div class="empty">Memuat data...</div>
</div>

</div>

@endsection


@section('script')

<style>

.filter-row{
    display:flex;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
}

input, select{
    padding:8px 10px;
    border:1px solid #ddd;
    border-radius:6px;
}

/* GURU */
.guru{
    margin-bottom:25px;
}

.nama{
    font-weight:bold;
    margin-bottom:5px;
}

/* REKAP */
.rekap{
    display:flex;
    gap:5px;
    margin-bottom:5px;
}

.r{
    padding:5px 8px;
    border-radius:6px;
    font-size:12px;
}

.h{background:#dcfce7;}
.i{background:#fef9c3;}
.s{background:#dbeafe;}
.a{background:#fee2e2;}

/* TABLE */
table{
    border-collapse:collapse;
    font-size:12px;
}

td, th{
    border:1px solid #eee;
    padding:4px;
    text-align:center;
}

th{
    background:#0a3d62;
    color:white;
}

.kosong{color:#ccc;}
.hadir{background:#dcfce7;}
.izin{background:#fef9c3;}
.sakit{background:#dbeafe;}
.alpa{background:#fee2e2;}

.empty{
    padding:20px;
    text-align:center;
    color:#999;
}

</style>


<script>

let allData = [];

document.addEventListener('DOMContentLoaded', async ()=>{
    await loadTahun();
    await loadData();

    filter_tahun.onchange = render;
    filterBulan.onchange = render;
    searchGuru.oninput = render;
});


// ================= LOAD =================
async function loadTahun(){
    const res = await fetch('/api/tahun-ajaran');
    const json = await res.json();

    let html = '<option value="">Semua Tahun</option>';

    json.data.forEach(t=>{
        html += `<option value="${t.id_tahun_ajaran}">
            ${t.periode} - ${t.semester}
        </option>`;
    });

    filter_tahun.innerHTML = html;
}

async function loadData(){
    const res = await fetch('/api/presensi-guru');
    const json = await res.json();

    allData = json.data || [];

    render();
}


// ================= RENDER =================
function render(){

    let data = [...allData];

    let tahun = filter_tahun.value;
    let bulan = filterBulan.value;
    let keyword = searchGuru.value.toLowerCase();

    if(tahun){
        data = data.filter(d=>d.id_tahun_ajaran == tahun);
    }

    if(bulan){
        data = data.filter(d=>d.tanggal.split('-')[1] === bulan);
    }

    if(keyword){
        data = data.filter(d =>
            (d.guru?.nama_guru || '').toLowerCase().includes(keyword)
        );
    }

    if(data.length === 0){
        presensiContainer.innerHTML = '<div class="empty">Tidak ada data</div>';
        return;
    }

    let grouped = {};

    data.forEach(p=>{
        let id = p.id_guru;
        if(!grouped[id]){
            grouped[id] = {
                nama:p.guru?.nama_guru || '-',
                list:[]
            };
        }
        grouped[id].list.push(p);
    });

    let html='';

    Object.values(grouped).forEach(g=>{

        let hadir=0, izin=0, sakit=0, alpa=0;

        g.list.forEach(p=>{
            let s = (p.status?.nama_status || '').toLowerCase();
            if(s==='hadir') hadir++;
            else if(s==='izin') izin++;
            else if(s==='sakit') sakit++;
            else if(s==='alpa') alpa++;
        });

        html += `<div class="guru">
            <div class="nama">${g.nama}</div>

            <div class="rekap">
                <div class="r h">H: ${hadir}</div>
                <div class="r i">I: ${izin}</div>
                <div class="r s">S: ${sakit}</div>
                <div class="r a">A: ${alpa}</div>
            </div>

            <table><tr>`;
        
        for(let i=1;i<=31;i++){
            html += `<th>${i}</th>`;
        }

        html += `</tr><tr>`;

        let map = {};

        g.list.forEach(p=>{
            let d = p.tanggal.split('-')[2];
            map[parseInt(d)] = (p.status?.nama_status || '').toLowerCase();
        });

        for(let i=1;i<=31;i++){
            let st = map[i];

            if(!st){
                html += `<td class="kosong">-</td>`;
            }else{
                html += `<td class="${st}">${st[0].toUpperCase()}</td>`;
            }
        }

        html += `</tr></table></div>`;
    });

    presensiContainer.innerHTML = html;
}

</script>

@endsection