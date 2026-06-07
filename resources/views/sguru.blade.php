@extends('layouts.app')

@section('title','Data Pegawai')

@section('content')

<style>

:root{
    --primary:#0a3d62;
    --danger:#dc2626;
    --border:#e5e7eb;
    --bg:#f8fafc;
}

.title{
    margin-bottom:20px;
    color:var(--primary);
}

.toolbar{
    margin-bottom:20px;
    display:flex;
    gap:12px;
    align-items:center;
}

.btn-pdf{
    background:white;
    color:black;
    border:none;
    border-radius:10px;
    padding:10px 18px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    display:flex;
    align-items:center;
    gap:6px;
    white-space:nowrap;
    transition:0.2s;
}

.btn-pdf:hover{
    background:#333;
}

.toolbar input{
    width:100%;
    padding:12px 14px;
    border:1px solid var(--border);
    border-radius:12px;
    outline:none;
    font-size:14px;
}

.toolbar input:focus{
    border-color:var(--primary);
}

/* TABLE STYLE */
.table-responsive{
    width:100%;
    overflow-x:auto;
    border-radius:12px;
    border:1px solid var(--border);
    background:white;
}

.pegawai-table{
    width:100%;
    border-collapse:collapse;
    text-align:left;
    font-size:14px;
}

.pegawai-table th{
    background:var(--bg);
    color:#475569;
    font-weight:600;
    padding:14px 16px;
    border-bottom:2px solid var(--border);
    white-space:nowrap;
}

.pegawai-table td{
    padding:14px 16px;
    border-bottom:1px solid var(--border);
    color:#334155;
    vertical-align:middle;
}

.pegawai-table tbody tr:hover{
    background-color:#f8fafc;
    transition:background-color 0.2s;
}

.td-nama{
    font-weight:600;
    color:#0f172a;
}

.badge-jk{
    display:inline-block;
    padding:4px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.badge-jk.jk-l{
    background:#e0f2fe;
    color:#0369a1;
}

.badge-jk.jk-p{
    background:#fce7f3;
    color:#be185d;
}

.role-wrap{
    display:flex;
    flex-wrap:wrap;
    gap:6px;
}

.role{
    background:#e0f2fe;
    color:#0369a1;
    padding:4px 8px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

button{
    border:none;
    border-radius:10px;
    cursor:pointer;
    padding:10px 14px;
    color:white;
    font-weight:600;
}

.btn-detail{
    background:var(--primary);
    padding:8px 12px;
    font-size:13px;
    transition:0.2s;
}

button:hover{
    opacity:0.9;
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.45);
    justify-content:center;
    align-items:center;
    z-index:999;
    padding:20px;
}

.modal-content{
    background:white;
    width:100%;
    max-width:850px;
    border-radius:20px;
    padding:25px;
    max-height:90vh;
    overflow:auto;
}

.modal-title{
    font-size:22px;
    font-weight:700;
    color:var(--primary);
    margin-bottom:20px;
}

.detail-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:15px;
}

.detail-item{
    background:var(--bg);
    border-radius:14px;
    padding:14px;
}

.label{
    font-size:11px;
    color:#64748b;
    text-transform:uppercase;
    margin-bottom:5px;
}

.value{
    font-size:15px;
    color:#111827;
    word-break:break-word;
}

.modal-action{
    margin-top:20px;
    display:flex;
    gap:10px;
}

.btn-close{
    background:#64748b;
}

.empty{
    text-align:center;
    padding:50px;
    color:#64748b;
}

</style>

<div class="card">

<h3 class="title">Data Pegawai</h3>

<div class="toolbar">
    <input type="text"
           id="search"
           placeholder="Cari nama / NIP / email / jabatan..."
           style="flex:1;">

    <button class="btn-pdf" onclick="downloadPDF()">
        <i class="bi bi-file-earmark-pdf"></i> Download PDF
    </button>
</div>

<div class="table-responsive">
    <table class="pegawai-table">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center;">No</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Jenis Kelamin</th>
                <th>Jabatan</th>
                <th>Status Kepgawaian</th>
                <th style="width: 100px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody id="data"></tbody>
    </table>
</div>

</div>


<!-- MODAL DETAIL -->
<div id="detailModal" class="modal">

<div class="modal-content">

<div class="modal-title">
    Detail Pegawai
</div>

<div id="detailContent"></div>

<div class="modal-action">
    <button class="btn-close"
            onclick="tutupDetail()">

        Tutup

    </button>
</div>

</div>

</div>




@endsection


@section('script')

<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>

<script>

let allData = [];
let jabatanList = [];

document.addEventListener('DOMContentLoaded', init);

async function init(){

    await loadJabatan();
    await loadData();

    search.oninput = filterData;
}


// ================= FORMAT =================
function formatTanggal(tgl){

    if(!tgl) return '-';

    const d = new Date(tgl);

    return d.toLocaleDateString('id-ID',{
        day:'2-digit',
        month:'long',
        year:'numeric'
    });
}


// ================= LOAD =================
async function loadJabatan(){

    const res = await fetch('/api/jabatan');

    jabatanList = (await res.json()).data;
}

async function loadData(){

    const res = await fetch('/api/pegawai');

    allData = (await res.json()).data;

    render(allData);
}


// ================= FILTER =================
function filterData(){

    const keyword = search.value.toLowerCase();

    const filtered = allData.filter(p => {

        let nama = (p.nama_guru || '').toLowerCase();

        let nip = (p.nip || '').toLowerCase();

        let email = (p.email || '').toLowerCase();

        let jabatan = (p.jabatan || [])
            .map(j => j.nama_jabatan.toLowerCase())
            .join(' ');

        return nama.includes(keyword) ||
               nip.includes(keyword) ||
               email.includes(keyword) ||
               jabatan.includes(keyword);
    });

    render(filtered);
}


// ================= RENDER =================
function render(data){

    if(data.length === 0){

        dataEl.innerHTML = `
        <tr>
            <td colspan="7" class="empty">
                Tidak ada data pegawai
            </td>
        </tr>`;

        return;
    }

    let html = '';

    data.forEach((p, index) => {

        let roles = (p.jabatan || []).map(j => `
            <span class="role">
                ${j.nama_jabatan}
            </span>
        `).join('');

        let jkBadge = '';
        if(p.jenis_kelamin === 'Laki-laki'){
            jkBadge = '<span class="badge-jk jk-l">Laki-laki</span>';
        } else if(p.jenis_kelamin === 'Perempuan'){
            jkBadge = '<span class="badge-jk jk-p">Perempuan</span>';
        } else {
            jkBadge = p.jenis_kelamin ?? '-';
        }

        html += `
        <tr>
            <td style="text-align: center;">${index + 1}</td>
            <td class="td-nama">${p.nama_guru ?? '-'}</td>
            <td>${p.nip ?? '-'}</td>
            <td>${jkBadge}</td>
            <td>
                <div class="role-wrap">
                    ${roles || '-'}
                </div>
            </td>
            <td class="td-status">${p.status_kepegawaian ?? '-'}</td>
            <td style="text-align: center;">
                <button class="btn-detail"
                        onclick="showDetail(${p.id_guru})">
                    Detail
                </button>
            </td>
        </tr>
        `;
    });

    dataEl.innerHTML = html;
}


// ================= DETAIL =================
function showDetail(id){

    const p = allData.find(x => x.id_guru == id);

    let roles = (p.jabatan || []).map(j => `
        <span class="role">
            ${j.nama_jabatan}
        </span>
    `).join('');

    detailContent.innerHTML = `

    <div class="detail-grid">

        <div class="detail-item">
            <div class="label">Nama</div>
            <div class="value">${p.nama_guru ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">NIP</div>
            <div class="value">${p.nip ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Jenis Kelamin</div>
            <div class="value">${p.jenis_kelamin ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Tempat Lahir</div>
            <div class="value">${p.tempat_lahir ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Tanggal Lahir</div>
            <div class="value">${formatTanggal(p.tanggal_lahir)}</div>
        </div>

        <div class="detail-item">
            <div class="label">Alamat</div>
            <div class="value">${p.alamat ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">No Telepon</div>
            <div class="value">${p.no_telepon ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Email</div>
            <div class="value">${p.email ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Golongan</div>
            <div class="value">${p.golongan ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Pendidikan Tertinggi</div>
            <div class="value">${p.pendidikan_tertinggi ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Status Kepegawaian</div>
            <div class="value">${p.status_kepegawaian ?? '-'}</div>
        </div>

        <div class="detail-item">
            <div class="label">Tanggal Masuk</div>
            <div class="value">${formatTanggal(p.tanggal_masuk)}</div>
        </div>

        <div class="detail-item" style="grid-column:1/-1;">
            <div class="label">Jabatan</div>

            <div class="role-wrap">
                ${roles || '-'}
            </div>
        </div>

    </div>
    `;

    detailModal.style.display = 'flex';
}

function tutupDetail(){

    detailModal.style.display = 'none';
}




// ================= DOWNLOAD PDF =================
function downloadPDF(){

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('landscape','mm','a4');

    const pageW = doc.internal.pageSize.getWidth();
    const pageH = doc.internal.pageSize.getHeight();

    // ======= KOP SURAT =======
    doc.setFont('helvetica','bold');
    doc.setFontSize(14);
    doc.text('PEMERINTAH KABUPATEN MANGGARAI', pageW / 2, 15, { align: 'center' });

    doc.setFontSize(12);
    doc.text('DINAS PENDIDIKAN DAN KEBUDAYAAN', pageW / 2, 21, { align: 'center' });

    doc.setFontSize(16);
    doc.text('SDN SINGKUL', pageW / 2, 28, { align: 'center' });

    doc.setFont('helvetica','normal');
    doc.setFontSize(9);

    // Garis kop
    doc.setLineWidth(0.8);
    doc.line(14, 36, pageW - 14, 36);
    doc.setLineWidth(0.3);
    doc.line(14, 37, pageW - 14, 37);

    // Judul laporan
    doc.setFont('helvetica','bold');
    doc.setFontSize(13);
    doc.text('DATA PEGAWAI', pageW / 2, 44, { align: 'center' });

    // Tanggal cetak
    doc.setFont('helvetica','normal');
    doc.setFontSize(9);
    const tglCetak = new Date().toLocaleDateString('id-ID', {
        day: '2-digit', month: 'long', year: 'numeric'
    });
    doc.text('Dicetak: ' + tglCetak, pageW - 14, 44, { align: 'right' });

    // ======= DATA TABEL =======
    const headers = [
        'No',
        'Nama',
        'NIP',
        'Jenis Kelamin',
        'Tempat Lahir',
        'Tgl. Lahir',
        'Alamat',
        'No. Telp',
        'Email',
        'Gol.',
        'Pendidikan',
        'Status',
        'Tgl. Masuk',
        'Jabatan'
    ];

    const rows = allData.map((p, i) => [
        i + 1,
        p.nama_guru ?? '-',
        p.nip ?? '-',
        p.jenis_kelamin ?? '-',
        p.tempat_lahir ?? '-',
        formatTanggal(p.tanggal_lahir),
        p.alamat ?? '-',
        p.no_telepon ?? '-',
        p.email ?? '-',
        p.golongan ?? '-',
        p.pendidikan_tertinggi ?? '-',
        p.status_kepegawaian ?? '-',
        formatTanggal(p.tanggal_masuk),
        (p.jabatan || []).map(j => j.nama_jabatan).join(', ') || '-'
    ]);

    doc.autoTable({
        head: [headers],
        body: rows,
        startY: 48,
        theme: 'grid',
        styles: {
            font: 'helvetica',
            fontSize: 7,
            textColor: [0, 0, 0],
            lineColor: [0, 0, 0],
            lineWidth: 0.2,
            cellPadding: 2,
            valign: 'middle'
        },
        headStyles: {
            fillColor: [220, 220, 220],
            textColor: [0, 0, 0],
            fontStyle: 'bold',
            halign: 'center',
            lineColor: [0, 0, 0],
            lineWidth: 0.3
        },
        bodyStyles: {
            fillColor: [255, 255, 255]
        },
        alternateRowStyles: {
            fillColor: [245, 245, 245]
        },
        columnStyles: {
            0: { halign: 'center', cellWidth: 8 },
            3: { halign: 'center', cellWidth: 18 }
        },
        margin: { left: 14, right: 14 },
        didDrawPage: function(data){
            // Footer nomor halaman
            doc.setFontSize(8);
            doc.setFont('helvetica','normal');
            doc.text(
                'Halaman ' + doc.internal.getNumberOfPages(),
                pageW / 2,
                pageH - 8,
                { align: 'center' }
            );
        }
    });

    // ======= TANDA TANGAN =======
    const finalY = doc.lastAutoTable.finalY + 15;
    const ttdX = pageW - 80;

    // Cari pegawai dengan jabatan Kepala
    const kepsek = allData.find(p =>
        (p.jabatan || []).some(j => j.nama_jabatan.trim().toLowerCase().includes('kepala'))
    );
    const namaKepsek = kepsek ? (kepsek.nama_guru || '_________________________') : '_________________________';
    const nipKepsek = kepsek ? (kepsek.nip || '............................') : '............................';

    doc.setFont('helvetica','normal');
    doc.setFontSize(9);
    doc.text('Singkul, ' + tglCetak, ttdX, finalY);
    doc.text('Kepala Sekolah,', ttdX, finalY + 6);

    doc.setFont('helvetica','bold');
    doc.text(namaKepsek, ttdX, finalY + 28);
    doc.setFont('helvetica','normal');
    doc.text('NIP. ' + nipKepsek, ttdX, finalY + 33);

    doc.save('Data_Pegawai_SDN_Singkul.pdf');
}

const dataEl = document.getElementById('data');

</script>

@endsection