@extends('layouts.app')

@section('title', 'Presensi Siswa')

@section('content')

<div class="card">

    <div class="page-header">
        <div>
            <h3>Presensi Siswa</h3>
            <h4 id="infoPeriode"></h4>
        </div>
    </div>

    <div class="toolbar">
        <input type="month" id="filter_bulan">

        {{-- ── SEARCH ── --}}
        <div class="search-wrap">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none">
                <circle cx="11" cy="11" r="7" stroke="#aaa" stroke-width="1.7"/>
                <path d="M16.5 16.5L21 21" stroke="#aaa" stroke-width="1.7" stroke-linecap="round"/>
            </svg>
            <input type="text" id="search_siswa" placeholder="Cari nama siswa…" autocomplete="off">
            <button class="search-clear" id="search_clear" title="Hapus pencarian">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6L18 18" stroke="#aaa" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </button>
        </div>


        <button class="btn-download btn-pdf" id="btn_download_pdf" title="Download PDF Landscape">
            <svg viewBox="0 0 24 24" fill="none" width="13" height="13">
                <path d="M12 3v13M7 11l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4 20h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            PDF
        </button>

        <div class="legend">
            <span class="badge hadir">H = Hadir</span>
            <span class="badge izin">I = Izin</span>
            <span class="badge sakit">S = Sakit</span>
            <span class="badge alpa">A = Alpa</span>
        </div>
    </div>

    <div id="presensi_info"></div>

    <div class="table-wrapper">
        <table class="presensi-table">
            <thead>
                <tr id="header_presensi"></tr>
            </thead>
            <tbody id="body_presensi">
                <tr><td colspan="40" class="td-loading">⏳ Memuat data...</td></tr>
            </tbody>
        </table>
    </div>

</div>

<style>

.card {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.08);
}

.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 16px;
}

.page-header h3 { margin: 0; font-size: 18px; color: #1a237e; font-weight: 700; }

#infoPeriode { margin: 4px 0 0; font-size: 13px; color: #555; font-weight: 400; }

.toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.toolbar input[type="month"] {
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 13px;
    outline: none;
    transition: border-color .2s;
}

.toolbar input[type="month"]:focus { border-color: #1e5ccc; }

/* ── SEARCH ── */
.search-wrap { position: relative; display: flex; align-items: center; }

.search-icon {
    position: absolute;
    left: 9px;
    width: 15px;
    height: 15px;
    pointer-events: none;
}

#search_siswa {
    padding: 6px 30px 6px 30px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 13px;
    outline: none;
    width: 200px;
    transition: border-color .2s, box-shadow .2s, width .25s;
}

#search_siswa:focus {
    border-color: #1e5ccc;
    box-shadow: 0 0 0 3px rgba(30,92,204,0.10);
    width: 240px;
}

.search-clear {
    position: absolute;
    right: 7px;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    transition: background .15s;
}
.search-clear:hover { background: #f0f0f0; }
.search-clear svg { width: 12px; height: 12px; }

.row-hidden { display: none !important; }
.hl { background: #fff59d; border-radius: 2px; padding: 0 1px; }
.search-info { font-size: 12px; color: #888; margin-left: 2px; white-space: nowrap; }

/* ── DOWNLOAD BUTTONS ── */
.btn-download {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s, transform .1s;
    white-space: nowrap;
}
.btn-download:active { transform: scale(.97); }
.btn-pdf { background: #c0392b; }
.btn-pdf:hover { background: #96281b; }

/* ── LEGEND ── */
.legend { display: flex; gap: 6px; flex-wrap: wrap; }

#presensi_info { color: #888; font-size: 12px; margin-bottom: 6px; }

/* ── TABLE ── */
.table-wrapper {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    margin-top: 10px;
}

.presensi-table {
    border-collapse: collapse;
    width: 100%;
    min-width: 700px;
    font-size: 12px;
}

.presensi-table th,
.presensi-table td {
    border: 1px solid #e0e0e0;
    padding: 5px 4px;
    text-align: center;
    white-space: nowrap;
}

.presensi-table thead th {
    background: #0a3d62;
    color: #fff;
    font-weight: 600;
    position: sticky;
    top: 0;
    z-index: 3;
}

.presensi-table tbody tr:nth-child(even) { background: #fafbff; }
.presensi-table tbody tr:hover           { background: #eef3ff; }

.nama-col {
    position: sticky;
    left: 0;
    background: #f1f5ff;
    font-weight: bold;
    z-index: 2;
    min-width: 140px;
    text-align: left !important;
    padding-left: 8px !important;
}
thead .nama-col { background: #1565c0; z-index: 4; }
.aksi-col { background: #eef3ff; min-width: 90px; }
thead .aksi-col { background: #1565c0; }

/* ── BADGE ── */
.badge {
    padding: 2px 7px;
    border-radius: 4px;
    font-weight: bold;
    display: inline-block;
    font-size: 11px;
}
.hadir { background: #28a745; color: #fff; }
.izin  { background: #ffc107; color: #333; }
.sakit { background: #17a2b8; color: #fff; }
.alpa  { background: #dc3545; color: #fff; }

.cell-presensi { cursor: pointer; }
.cell-kosong { cursor: pointer; color: #ccc; font-size: 13px; display: inline-block; width: 100%; }
.cell-kosong:hover { color: #1e5ccc; }

.btn-aksi-presensi {
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 4px;
    background: #1e5ccc;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background .15s;
}
.btn-aksi-presensi:hover { background: #174aaa; }

/* ── DROPDOWN ── */
.dropdown-status {
    position: absolute;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 6px 16px rgba(0,0,0,.15);
    z-index: 9999;
    padding: 4px;
    min-width: 130px;
}
.dropdown-status .dd-label {
    padding: 5px 10px 4px;
    font-size: 10px;
    color: #888;
    border-bottom: 1px solid #eee;
    margin-bottom: 3px;
}
.dropdown-status button {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    border: none;
    background: none;
    padding: 7px 10px;
    cursor: pointer;
    text-align: left;
    font-size: 12px;
    border-radius: 5px;
    transition: background .1s;
}
.dropdown-status button:hover { background: #f0f4ff; }

.td-loading { text-align: center; color: #aaa; padding: 20px !important; font-style: italic; }

</style>

@endsection


@section('script')

{{-- jsPDF + AutoTable via CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<script>

let siswa       = [];
let presensi    = {};
let bulan       = new Date().toISOString().slice(0, 7);
let id_guru     = null;
let searchQuery = '';
let periodeInfo = { periode: '-', semester: '-' };


// ===================== INIT =====================
document.addEventListener('DOMContentLoaded', init);

async function init() {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) { alert('Login dulu'); location.href = '/login'; return; }
    id_guru = user.id;

    const filterBulan = document.getElementById('filter_bulan');
    filterBulan.value = bulan;
    filterBulan.addEventListener('change', function () { bulan = this.value; loadData(); });

    const searchEl = document.getElementById('search_siswa');
    const clearBtn = document.getElementById('search_clear');

    searchEl.addEventListener('input', function () {
        searchQuery = this.value.trim();
        clearBtn.style.display = searchQuery ? 'flex' : 'none';
        applySearch();
    });

    clearBtn.addEventListener('click', function () {
        searchEl.value = ''; searchQuery = '';
        clearBtn.style.display = 'none';
        searchEl.focus(); applySearch();
    });

    document.getElementById('btn_download_pdf').addEventListener('click', downloadPDF);

    await loadPeriode();
    await loadData();
}


// ===================== PERIODE =====================
async function loadPeriode() {
    try {
        const res  = await fetch('/api/tahun_ajaran/aktif1');
        const json = await res.json();
        const t    = json.data;
        periodeInfo = { periode: t?.periode ?? '-', semester: t?.semester ?? '-' };
        const el   = document.getElementById('infoPeriode');
        if (el) el.innerText = `Periode: ${periodeInfo.periode} | Semester: ${periodeInfo.semester}`;
    } catch (e) { console.error('Gagal load periode:', e); }
}


// ===================== LOAD DATA =====================
async function loadData() {
    setBodyLoading();
    try {
        const [resSiswa, resPresensi] = await Promise.all([
            fetch('/api/kelas-sayaP/' + id_guru),
            fetch('/api/presensi')
        ]);
        const dataSiswa    = await resSiswa.json();
        const dataPresensi = await resPresensi.json();

        siswa = (dataSiswa.data || []).map(s => ({ ...s, id_siswa: Number(s.id_siswa) }));

        const filtered = (dataPresensi.data || []).filter(p => p.tanggal.startsWith(bulan));
        presensi = groupPresensi(filtered);
        renderTable();
    } catch (e) {
        console.error('Gagal load data:', e);
        setBodyError();
    }
}

function setBodyLoading() {
    document.getElementById('body_presensi').innerHTML =
        `<tr><td colspan="40" class="td-loading">⏳ Memuat data...</td></tr>`;
}

function setBodyError() {
    document.getElementById('body_presensi').innerHTML =
        `<tr><td colspan="40" class="td-loading" style="color:#dc3545;">Gagal memuat data. Coba refresh halaman.</td></tr>`;
}


// ===================== GROUP PRESENSI =====================
function groupPresensi(data) {
    const result = {};
    data.forEach(p => {
        const sid = Number(p.id_siswa);
        if (!sid) return;
        if (!result[sid]) result[sid] = {};
        result[sid][p.tanggal] = p;
    });
    return result;
}


// ===================== RENDER TABLE =====================
function renderTable() {
    const header    = document.getElementById('header_presensi');
    const body      = document.getElementById('body_presensi');
    const info      = document.getElementById('presensi_info');
    const [year, month] = bulan.split('-');
    const totalHari = new Date(year, month, 0).getDate();
    const today     = new Date().toISOString().slice(0, 10);

    if (info) info.innerText = `Total siswa: ${siswa.length} | Bulan: ${getBulanLabel(bulan)}`;

    header.innerHTML = `<th class="nama-col">Nama Siswa</th>`;
    for (let i = 1; i <= totalHari; i++) {
        const tgl = bulan + '-' + String(i).padStart(2, '0');
        header.innerHTML += `<th style="${tgl === today ? 'background:#0d47a1;' : ''}">${i}</th>`;
    }
    header.innerHTML += `
        <th style="background:#1b5e20;">H</th>
        <th style="background:#e65100;">I</th>
        <th style="background:#006064;">S</th>
        <th style="background:#b71c1c;">A</th>
        <th class="aksi-col">Aksi Hari Ini</th>`;

    body.innerHTML = '';

    if (siswa.length === 0) {
        body.innerHTML = `<tr><td colspan="${totalHari + 6}" class="td-loading">Tidak ada siswa di kelas ini.</td></tr>`;
        return;
    }

    siswa.forEach((s, idx) => {
        const sid = Number(s.id_siswa);
        const tr  = document.createElement('tr');
        tr.dataset.nama = s.nama_siswa.toLowerCase();
        tr.dataset.idx  = idx + 1;

        const tdNama = document.createElement('td');
        tdNama.className = 'nama-col';
        tdNama.dataset.namaText = s.nama_siswa;
        tdNama.innerHTML = `<span style="color:#888;margin-right:4px;font-size:10px;">${idx+1}.</span>${s.nama_siswa}`;
        tr.appendChild(tdNama);

        let cH = 0, cI = 0, cS = 0, cA = 0;
        for (let i = 1; i <= totalHari; i++) {
            const tgl = bulan + '-' + String(i).padStart(2, '0');
            const td  = document.createElement('td');
            if (tgl === today) td.style.background = '#f0f8ff';
            td.innerHTML = renderCell(sid, tgl);
            tr.appendChild(td);
            const st = presensi[sid]?.[tgl]?.id_status;
            if (st === 1) cH++;
            else if (st === 2) cI++;
            else if (st === 3) cS++;
            else if (st === 4) cA++;
        }

        const mkRekap = (val, cls) => {
            const td = document.createElement('td');
            td.style.fontWeight = 'bold';
            td.innerHTML = val > 0
                ? `<span class="badge ${cls}" style="font-size:10px;">${val}</span>`
                : `<span style="color:#ccc;">0</span>`;
            return td;
        };
        tr.appendChild(mkRekap(cH, 'hadir'));
        tr.appendChild(mkRekap(cI, 'izin'));
        tr.appendChild(mkRekap(cS, 'sakit'));
        tr.appendChild(mkRekap(cA, 'alpa'));

        const tdAksi = document.createElement('td');
        tdAksi.className = 'aksi-col';
        tdAksi.innerHTML = renderAksiCell(sid, today);
        tr.appendChild(tdAksi);

        body.appendChild(tr);
    });

    attachListeners();
    applySearch();
}


// ===================== DOWNLOAD PDF LANDSCAPE - IMPROVED =====================
function downloadPDF() {
    if (siswa.length === 0) { alert('Tidak ada data siswa.'); return; }

    const { jsPDF }     = window.jspdf;
    const doc           = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
    const pageW         = doc.internal.pageSize.getWidth();   // 297mm
    const pageH         = doc.internal.pageSize.getHeight();  // 210mm
    const [year, month] = bulan.split('-');
    const totalHari     = new Date(year, month, 0).getDate();
    const bulanLabel    = getBulanLabel(bulan);
    const marginL = 12, marginR = 12;
    const usable  = pageW - marginL - marginR;

    // ══════════════════════════════════════════════
    //  HEADER AREA  — gradient bar + judul
    // ══════════════════════════════════════════════
    // Background bar
    doc.setFillColor(10, 61, 98);
    doc.roundedRect(marginL, 6, pageW - marginL - marginR, 18, 2, 2, 'F');

    // Judul utama (putih, di dalam bar)
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.setTextColor(255, 255, 255);
    doc.text('REKAP PRESENSI SISWA', pageW / 2, 17, { align: 'center' });

    // Sub‑info (strip kecil di bawah bar)
    doc.setFillColor(230, 237, 248);
    doc.rect(marginL, 24, pageW - marginL - marginR, 7, 'F');
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(7.5);
    doc.setTextColor(40, 60, 100);
    doc.text(
        `Periode: ${periodeInfo.periode}     Semester: ${periodeInfo.semester}     Bulan: ${bulanLabel}     Total Siswa: ${siswa.length}`,
        pageW / 2, 28.5, { align: 'center' }
    );

    // ══════════════════════════════════════════════
    //  HITUNG LEBAR KOLOM
    // ══════════════════════════════════════════════
    const noW   = 5.5;
    const namaW = 36;
    const rekW  = 7;
    const dayW  = (usable - noW - namaW - rekW * 4) / totalHari;

    // ══════════════════════════════════════════════
    //  BUILD DATA
    // ══════════════════════════════════════════════
    const STATUS_COLOR = {
        'H': { fill: [39, 174, 96],  text: [255, 255, 255] },
        'I': { fill: [243, 156, 18], text: [255, 255, 255] },
        'S': { fill: [41, 182, 246], text: [255, 255, 255] },
        'A': { fill: [231, 76, 60],  text: [255, 255, 255] },
    };

    const today = new Date().toISOString().slice(0, 10);

    const head = [[
        { content: 'No',  styles: { halign: 'center' } },
        { content: 'Nama Siswa', styles: { halign: 'left' } },
        ...Array.from({ length: totalHari }, (_, i) => {
            const tgl = bulan + '-' + String(i + 1).padStart(2, '0');
            return {
                content: String(i + 1),
                styles: {
                    halign: 'center',
                    fillColor: tgl === today ? [13, 71, 161] : [10, 61, 98],
                    fontStyle: tgl === today ? 'bold' : 'normal',
                }
            };
        }),
        { content: 'H', styles: { halign: 'center', fillColor: [27, 94, 32]   } },
        { content: 'I', styles: { halign: 'center', fillColor: [230, 81, 0]   } },
        { content: 'S', styles: { halign: 'center', fillColor: [0, 96, 100]   } },
        { content: 'A', styles: { halign: 'center', fillColor: [183, 28, 28]  } },
    ]];

    const bodyData = siswa.map((s, idx) => {
        const sid = Number(s.id_siswa);
        let cH = 0, cI = 0, cS = 0, cA = 0;
        const cols = [];
        for (let d = 1; d <= totalHari; d++) {
            const tgl = bulan + '-' + String(d).padStart(2, '0');
            const st  = presensi[sid]?.[tgl]?.id_status ?? null;
            const lbl = getLabel(st);
            cols.push(lbl === '-' ? '' : lbl);
            if (st === 1) cH++;
            else if (st === 2) cI++;
            else if (st === 3) cS++;
            else if (st === 4) cA++;
        }
        return [idx + 1, s.nama_siswa, ...cols,
            cH || '', cI || '', cS || '', cA || ''];
    });

    // ══════════════════════════════════════════════
    //  COLUMN STYLES
    // ══════════════════════════════════════════════
    const colStyles = {};
    colStyles[0] = { halign: 'center', cellWidth: noW,   cellPadding: { top: 1.5, bottom: 1.5, left: 1, right: 1 } };
    colStyles[1] = { halign: 'left',   cellWidth: namaW, fontStyle: 'bold',
                     cellPadding: { top: 1.5, bottom: 1.5, left: 3, right: 2 } };
    for (let i = 0; i < totalHari; i++) {
        colStyles[i + 2] = { halign: 'center', cellWidth: dayW,
                              cellPadding: { top: 1.5, bottom: 1.5, left: 0.5, right: 0.5 } };
    }
    const ri = totalHari + 2;
    const rekPad = { top: 1.5, bottom: 1.5, left: 1, right: 1 };
    colStyles[ri]   = { halign: 'center', cellWidth: rekW, fontStyle: 'bold', cellPadding: rekPad };
    colStyles[ri+1] = { halign: 'center', cellWidth: rekW, fontStyle: 'bold', cellPadding: rekPad };
    colStyles[ri+2] = { halign: 'center', cellWidth: rekW, fontStyle: 'bold', cellPadding: rekPad };
    colStyles[ri+3] = { halign: 'center', cellWidth: rekW, fontStyle: 'bold', cellPadding: rekPad };

    // ══════════════════════════════════════════════
    //  AUTOTABLE
    // ══════════════════════════════════════════════
    doc.autoTable({
        head,
        body: bodyData,
        startY: 33,
        margin: { left: marginL, right: marginR },
        columnStyles: colStyles,
        tableLineColor: [200, 210, 230],
        tableLineWidth: 0.1,

        headStyles: {
            fillColor: [10, 61, 98],
            textColor: [255, 255, 255],
            fontStyle: 'bold',
            fontSize: 6,
            cellPadding: { top: 2.5, bottom: 2.5, left: 1, right: 1 },
            halign: 'center',
            valign: 'middle',
            lineWidth: 0.1,
            lineColor: [255, 255, 255],
        },

        bodyStyles: {
            fontSize: 6,
            cellPadding: { top: 1.8, bottom: 1.8, left: 1.5, right: 1.5 },
            valign: 'middle',
            lineWidth: 0.1,
            lineColor: [210, 220, 235],
        },

        alternateRowStyles: { fillColor: [245, 249, 255] },

        didParseCell: function (data) {
            if (data.section !== 'body') return;
            const col = data.column.index;
            const val = String(data.cell.raw ?? '');

            // Kolom tanggal → hanya huruf yang berwarna
            if (col >= 2 && col <= totalHari + 1 && STATUS_COLOR[val]) {

                // background tetap putih
                data.cell.styles.fillColor = [255,255,255];

                // hanya teks berwarna
                data.cell.styles.textColor =
                    STATUS_COLOR[val].fill;

                data.cell.styles.fontStyle =
                    'bold';

                data.cell.styles.fontSize =
                    6;
            }

            // kosong
            if (col >= 2 && col <= totalHari + 1 && val === '') {

                data.cell.styles.fillColor =
                    [255,255,255];

                data.cell.styles.textColor =
                    [210,210,210];
            }

            // Kolom rekap: pewarnaan background + teks
            if (col === ri   && val !== '' && val !== '0') { data.cell.styles.fillColor = [200, 230, 201]; data.cell.styles.textColor = [27,94,32]; }
            if (col === ri+1 && val !== '' && val !== '0') { data.cell.styles.fillColor = [255, 224, 178]; data.cell.styles.textColor = [230,81,0]; }
            if (col === ri+2 && val !== '' && val !== '0') { data.cell.styles.fillColor = [178, 235, 242]; data.cell.styles.textColor = [0,96,100]; }
            if (col === ri+3 && val !== '' && val !== '0') { data.cell.styles.fillColor = [255, 205, 210]; data.cell.styles.textColor = [183,28,28]; }

            // Nilai 0 di kolom rekap → abu
            if (col >= ri && col <= ri + 3 && (val === '0' || val === '')) {
                data.cell.styles.textColor = [200, 200, 200];
                data.cell.text = [''];
            }
        },

        // Footer tiap halaman
        didDrawPage: function (hookData) {
            const pg     = doc.internal.getCurrentPageInfo().pageNumber;
            const totPg  = '{total_pages_count_string}';
            const y      = pageH - 5;

            // Strip bawah
            doc.setFillColor(230, 237, 248);
            doc.rect(marginL, y - 5, pageW - marginL - marginR, 6, 'F');

            doc.setFontSize(6);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(60, 80, 120);

            // Kiri: legend warna
            doc.setFont('helvetica', 'bold');
            doc.text('Keterangan:', marginL + 2, y - 1.5);
            doc.setFont('helvetica', 'normal');
            const badges = [
                { label: 'H = Hadir', color: [39, 174, 96]  },
                { label: 'I = Izin',  color: [243, 156, 18] },
                { label: 'S = Sakit', color: [41, 182, 246] },
                { label: 'A = Alpa',  color: [231, 76, 60]  },
            ];
            let bx = marginL + 24;
            badges.forEach(b => {
                doc.setFillColor(...b.color);
                doc.roundedRect(bx, y - 4.5, 20, 4, 0.8, 0.8, 'F');
                doc.setTextColor(255, 255, 255);
                doc.setFontSize(5.5);
                doc.text(b.label, bx + 10, y - 1.8, { align: 'center' });
                bx += 23;
            });

            // Kanan: halaman
            doc.setTextColor(60, 80, 120);
            doc.setFontSize(6);
            doc.setFont('helvetica', 'normal');
            doc.text(
                `Halaman ${pg}`,
                pageW - marginR - 2, y - 1.5, { align: 'right' }
            );
        },
    });

    // Ganti placeholder total halaman
    if (typeof doc.putTotalPages === 'function') {
        doc.putTotalPages('{total_pages_count_string}');
    }

    doc.save(`presensi_${bulanLabel.replace(' ', '_')}.pdf`);
}

// ===================== SEARCH =====================
function applySearch() {
    const rows  = document.querySelectorAll('#body_presensi tr[data-nama]');
    const q     = searchQuery.toLowerCase();
    let visible = 0;

    rows.forEach(tr => {
        const cocok = !q || tr.dataset.nama.includes(q);
        tr.classList.toggle('row-hidden', !cocok);
        if (cocok) {
            visible++;
            const tdNama = tr.querySelector('.nama-col');
            const asli   = tdNama?.dataset.namaText ?? '';
            if (tdNama) {
                tdNama.innerHTML =
                    `<span style="color:#888;margin-right:4px;font-size:10px;">${tr.dataset.idx}.</span>` +
                    highlightText(asli, q);
            }
        }
    });
    updateSearchInfo(visible, rows.length, q);
}

function highlightText(text, q) {
    if (!q) return text;
    return text.replace(new RegExp(`(${escapeRegex(q)})`, 'gi'), '<span class="hl">$1</span>');
}

function escapeRegex(str) { return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }

function updateSearchInfo(visible, total, q) {
    document.querySelector('.search-info')?.remove();
    if (!q) return;
    const info = document.createElement('span');
    info.className   = 'search-info';
    info.textContent = visible === 0 ? 'Tidak ditemukan' : `${visible} dari ${total} siswa`;
    document.querySelector('.search-wrap')?.after(info);
}


// ===================== RENDER CELL =====================
function renderCell(sid, tanggal) {
    const data = presensi[sid]?.[tanggal];
    if (!data) {
        return `<span class="cell-presensi cell-kosong" data-id="" data-sid="${sid}" data-tgl="${tanggal}" title="${tanggal}">–</span>`;
    }
    return `<span class="badge cell-presensi ${getClass(data.id_status)}" data-id="${data.id_presensi_siswa}" data-sid="${sid}" data-tgl="${tanggal}" title="${tanggal}">${getLabel(data.id_status)}</span>`;
}

function renderAksiCell(sid, tanggal) {
    const data = presensi[sid]?.[tanggal];
    const statusBadge = data
        ? `<span class="badge ${getClass(data.id_status)}">${getLabel(data.id_status)}</span>`
        : `<span style="color:#ccc;font-size:11px;">–</span>`;
    return `<div style="display:flex;gap:4px;justify-content:center;align-items:center;">
        ${statusBadge}
        <button class="btn-aksi-presensi"
            data-id="${data?.id_presensi_siswa??''}"
            data-sid="${sid}"
            data-tgl="${tanggal}"
            title="Input presensi hari ini">Input</button>
    </div>`;
}


// ===================== ATTACH LISTENERS =====================
function attachListeners() {
    document.querySelectorAll('.cell-presensi').forEach(el => {
        el.addEventListener('click', function (e) {
            openDropdown(e, this.dataset.id || null, Number(this.dataset.sid), this.dataset.tgl);
        });
    });
    document.querySelectorAll('.btn-aksi-presensi').forEach(el => {
        el.addEventListener('click', function (e) {
            e.stopPropagation();
            openDropdown(e, this.dataset.id || null, Number(this.dataset.sid), this.dataset.tgl);
        });
    });
}


// ===================== DROPDOWN =====================
function openDropdown(e, id, sid, tanggal) {
    document.querySelectorAll('.dropdown-status').forEach(el => el.remove());

    const div = document.createElement('div');
    div.className = 'dropdown-status';

    const lbl = document.createElement('div');
    lbl.className = 'dd-label';
    lbl.textContent = `📅 ${tanggal}`;
    div.appendChild(lbl);

    [{ label:'Hadir',icon:'🟢',val:1 },{ label:'Izin',icon:'🟡',val:2 },
     { label:'Sakit',icon:'🔵',val:3 },{ label:'Alpa',icon:'🔴',val:4 }]
    .forEach(({ label, icon, val }) => {
        const btn = document.createElement('button');
        btn.innerHTML = `${icon} ${label}`;
        btn.addEventListener('click', ev => { ev.stopPropagation(); selectStatus(id, sid, tanggal, val); });
        div.appendChild(btn);
    });

    document.body.appendChild(div);

    let x = e.pageX + 5, y = e.pageY + 5;
    if (x + 145 > window.innerWidth  + window.scrollX) x = e.pageX - 145;
    if (y + 200 > window.innerHeight + window.scrollY) y = e.pageY - 200;
    div.style.top = y + 'px';
    div.style.left = x + 'px';

    setTimeout(() => { document.addEventListener('click', closeDropdown, { once: true }); }, 10);
}

function closeDropdown() { document.querySelectorAll('.dropdown-status').forEach(el => el.remove()); }

function selectStatus(id, sid, tanggal, status) {
    closeDropdown();
    if (!id) setPresensi(sid, tanggal, status);
    else     updatePresensi(id, sid, tanggal, status);
}


// ===================== API PRESENSI =====================
async function setPresensi(id_siswa, tanggal, id_status) {
    try {
        const res  = await fetch('/api/presensi', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_siswa, tanggal, id_status })
        });
        const data = await res.json();
        if (!res.ok) { alert(data.message || 'Gagal simpan'); return; }
        loadData();
    } catch (e) { console.error('Gagal simpan presensi:', e); }
}

async function updatePresensi(id, id_siswa, tanggal, id_status) {
    try {
        const res  = await fetch('/api/presensi/' + id, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_siswa, tanggal, id_status })
        });
        const data = await res.json();
        if (!res.ok) { alert(data.message || 'Gagal update'); return; }
        loadData();
    } catch (e) { console.error('Gagal update presensi:', e); }
}


// ===================== HELPER =====================
function getLabel(s) { return ['','H','I','S','A'][s] ?? '-'; }
function getClass(s) { return ['','hadir','izin','sakit','alpa'][s] ?? ''; }

function getBulanLabel(ym) {
    const [y, m] = ym.split('-');
    const nama   = ['','Januari','Februari','Maret','April','Mei','Juni',
                    'Juli','Agustus','September','Oktober','November','Desember'];
    return `${nama[parseInt(m)]} ${y}`;
}

</script>
@endsection