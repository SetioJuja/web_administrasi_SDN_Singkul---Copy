@extends('layouts.app')

@section('title','Pengumuman Sekolah')

@section('content')

<div class="card">

    <h2 class="title-section">
        Pengumuman Sekolah
    </h2>

    <!-- FILTER -->
    <div class="filter-row">

        <input
            type="text"
            id="search"
            placeholder="Cari pengumuman..."
        >

        <select id="filter_bulan">
            <option value="">Semua Bulan</option>
            <option value="01">Januari</option>
            <option value="02">Februari</option>
            <option value="03">Maret</option>
            <option value="04">April</option>
            <option value="05">Mei</option>
            <option value="06">Juni</option>
            <option value="07">Juli</option>
            <option value="08">Agustus</option>
            <option value="09">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
        </select>

        <select id="filter_tahun"></select>

    </div>

    <!-- LIST -->
    <div
        id="listPengumuman"
        class="pengumuman-list"
    >
        <div class="loading">
            Memuat pengumuman...
        </div>
    </div>

</div>


<!-- MODAL -->
<div
    id="modalPengumuman"
    class="modal"
>

    <div class="modal-content">

        <button
            class="close-btn"
            onclick="closeModal()"
        >
            ×
        </button>

        <img
            id="modalImage"
            class="modal-image"
            src=""
            alt=""
        >

        <div class="modal-body">

            <div id="modalBadge"></div>

            <h2 id="modalTitle"></h2>

            <div
                id="modalDate"
                class="modal-date"
            ></div>

            <div
                id="modalIsi"
                class="modal-isi"
            ></div>

        </div>

    </div>

</div>

@endsection


@section('script')

<style>

/* ===== TITLE ===== */

.title-section{
    font-size:24px;
    font-weight:700;
    color:#1e5ccc;
    margin-bottom:20px;
}

/* ===== FILTER ===== */

.filter-row{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    margin-bottom:22px;
}

.filter-row input,
.filter-row select{
    padding:12px 14px;
    border:1px solid #ddd;
    border-radius:10px;
    background:#fff;
    min-width:180px;
    outline:none;
}

/* ===== LIST ===== */

.pengumuman-list{
    display:grid;
    gap:18px;
}

/* ===== CARD ===== */

.pengumuman-card{
    display:flex;
    gap:18px;
    background:#fff;
    border-radius:16px;
    padding:18px;
    border-left:5px solid #1e5ccc;
    box-shadow:0 5px 15px rgba(0,0,0,.05);
    cursor:pointer;
}

/* ===== HARI INI ===== */

.pengumuman-hari-ini{
    border-left-color:#e53935;
    background:
    linear-gradient(
        to right,
        rgba(229,57,53,.05),
        #fff
    );
}

/* ===== BADGE ===== */

.badge-wrapper{
    display:flex;
    gap:8px;
    margin-bottom:10px;
    flex-wrap:wrap;
}

.badge{
    padding:6px 12px;
    border-radius:999px;
    font-size:11px;
    font-weight:700;
    color:#fff;
}

.badge-hari{
    background:#e53935;
}

/* ===== CONTENT ===== */

.pengumuman-content{
    flex:1;
}

.pengumuman-title{
    font-size:18px;
    font-weight:700;
    color:#1e5ccc;
    margin-bottom:8px;
}

.pengumuman-date{
    font-size:13px;
    color:#888;
    margin-bottom:12px;
}

.pengumuman-isi{
    font-size:14px;
    color:#444;
    line-height:1.7;

    display:-webkit-box;
    -webkit-line-clamp:3;
    -webkit-box-orient:vertical;
    overflow:hidden;
}

/* ===== IMAGE ===== */

.pengumuman-img{
    width:150px;
    height:110px;
    object-fit:cover;
    border-radius:12px;
    flex-shrink:0;
}

/* ===== EMPTY ===== */

.empty{
    text-align:center;
    padding:40px;
    background:#fff;
    border-radius:14px;
    color:#777;
}

.loading{
    text-align:center;
    padding:30px;
    color:#888;
}

/* ===== MODAL ===== */

.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
    padding:20px;
}

.modal.show{
    display:flex;
}

.modal-content{
    background:#fff;
    width:100%;
    max-width:800px;
    border-radius:18px;
    overflow:hidden;
    position:relative;
}

.close-btn{
    position:absolute;
    top:14px;
    right:14px;
    width:38px;
    height:38px;
    border:none;
    border-radius:50%;
    background:#fff;
    font-size:22px;
    cursor:pointer;
    box-shadow:0 2px 10px rgba(0,0,0,.1);
    z-index:10;
}

.modal-image{
    width:100%;
    height:320px;
    object-fit:cover;
    display:none;
}

.modal-body{
    padding:24px;
}

.modal-body h2{
    color:#1e5ccc;
    margin-bottom:10px;
}

.modal-date{
    font-size:13px;
    color:#888;
    margin-bottom:18px;
}

.modal-isi{
    font-size:15px;
    color:#333;
    line-height:1.9;
    white-space:pre-line;
}

/* ===== RESPONSIVE ===== */

@media(max-width:700px){

    .pengumuman-card{
        flex-direction:column;
    }

    .pengumuman-img{
        width:100%;
        height:200px;
    }

    .filter-row input,
    .filter-row select{
        width:100%;
    }

    .modal-image{
        height:220px;
    }
}

</style>


<script>

let allData = [];


// ===== INIT =====

document.addEventListener(
    'DOMContentLoaded',
    async ()=>{

        await loadData();

        search.oninput = render;

        filter_bulan.onchange = render;

        filter_tahun.onchange = render;
    }
);


// ===== FORMAT TANGGAL =====

function formatTanggal(tgl){

    if(!tgl) return '-';

    return new Date(tgl)
    .toLocaleDateString(
        'id-ID',
        {
            day:'2-digit',
            month:'long',
            year:'numeric'
        }
    );
}


// ===== LOAD DATA =====

async function loadData(){

    const res =
        await fetch('/api/pengumuman');

    const json =
        await res.json();

    allData = json.data || [];

    // urut terbaru
    allData.sort((a,b)=>

        new Date(b.tanggal)
        -
        new Date(a.tanggal)
    );

    // tahun
    let tahunSet = new Set(

        allData.map(p=>

            new Date(p.tanggal)
            .getFullYear()
        )
    );

    let html =
        `<option value="">
            Semua Tahun
        </option>`;

    tahunSet.forEach(t=>{

        html += `
            <option value="${t}">
                ${t}
            </option>
        `;
    });

    filter_tahun.innerHTML = html;

    render();
}


// ===== RENDER =====

function render(){

    let data = [...allData];

    let keyword =
        search.value.toLowerCase();

    let bulan =
        filter_bulan.value;

    let tahun =
        filter_tahun.value;

    // SEARCH
    if(keyword){

        data = data.filter(p=>

            p.judul
            .toLowerCase()
            .includes(keyword)

            ||

            p.isi
            .toLowerCase()
            .includes(keyword)
        );
    }

    // BULAN
    if(bulan){

        data = data.filter(p=>

            p.tanggal
            .split('-')[1] === bulan
        );
    }

    // TAHUN
    if(tahun){

        data = data.filter(p=>

            new Date(p.tanggal)
            .getFullYear() == tahun
        );
    }

    // EMPTY
    if(data.length === 0){

        listPengumuman.innerHTML = `
            <div class="empty">
                Tidak ada pengumuman
            </div>
        `;

        return;
    }

    const today = new Date();

    let html = '';

    data.forEach((p,index)=>{

        const tgl =
            new Date(p.tanggal);

        // HARI INI
        const isToday =

            tgl.toDateString()
            ===
            today.toDateString();

        let cardClass = '';

        if(isToday){

            cardClass =
                'pengumuman-hari-ini';
        }

        html += `

        <div
            class="pengumuman-card ${cardClass}"
            data-index="${index}"
        >

            <div class="pengumuman-content">

                <div class="badge-wrapper">

                    ${
                        isToday
                        ?
                        `
                        <div class="badge badge-hari">
                            HARI INI
                        </div>
                        `
                        :
                        ''
                    }

                </div>

                <div class="pengumuman-title">
                    ${p.judul}
                </div>

                <div class="pengumuman-date">
                    ${formatTanggal(p.tanggal)}
                </div>

                <div class="pengumuman-isi">
                    ${p.isi}
                </div>

            </div>

            ${
                p.gambar
                ?
                `
                <img
                    src="/upload/${p.gambar}"
                    class="pengumuman-img"
                >
                `
                :
                ''
            }

        </div>

        `;
    });

    listPengumuman.innerHTML = html;

    // CLICK CARD
    document
    .querySelectorAll('.pengumuman-card')
    .forEach(card=>{

        card.addEventListener(
            'click',
            ()=>{

                const index =
                    card.dataset.index;

                openModal(data[index]);
            }
        );
    });
}


// ===== OPEN MODAL =====

function openModal(p){

    modalPengumuman
    .classList
    .add('show');

    modalTitle.innerText =
        p.judul || '-';

    modalDate.innerText =
        formatTanggal(p.tanggal);

    modalIsi.innerText =
        p.isi || '-';

    const tgl =
        new Date(p.tanggal);

    const today =
        new Date();

    const isToday =

        tgl.toDateString()
        ===
        today.toDateString();

    let badge = '';

    if(isToday){

        badge = `
            <div class="badge badge-hari">
                HARI INI
            </div>
        `;
    }

    modalBadge.innerHTML =
        badge;

    // IMAGE
    if(p.gambar){

        modalImage.style.display =
            'block';

        modalImage.src =
            `/upload/${p.gambar}`;
    }
    else{

        modalImage.style.display =
            'none';
    }
}


// ===== CLOSE =====

function closeModal(){

    modalPengumuman
    .classList
    .remove('show');
}


// ===== CLOSE KLIK LUAR =====

window.onclick = function(e){

    if(
        e.target ===
        modalPengumuman
    ){

        closeModal();
    }
}

</script>

@endsection