@extends('layouts.app')

@section('title','Kelas')

@section('content')

<style>
:root{
    --primary:#0a3d62;
    --border:#e5e7eb;
    --success:#16a34a;
    --danger:#dc2626;
    --warning:#f59e0b;
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
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:10px;
    margin-bottom:15px;
}

.filter-box{
    background:#f8fafc;
    border:1px solid var(--border);
    border-radius:12px;
    padding:15px;
    margin:15px 0 20px;
}

.filter-box label{
    display:block;
    font-size:14px;
    font-weight:600;
    margin-bottom:8px;
    color:#374151;
}

input,
select{
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    width:100%;
    background:white;
}

input:focus,
select:focus{
    border-color:var(--primary);
}

button{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    color:white;
    transition:0.2s;
}

.btn-primary{
    background:var(--primary);
}

.btn-danger{
    background:var(--danger);
}

.btn-edit{
    background:#2563eb;
}

.btn-secondary{
    background:#6b7280;
}

.btn-success{
    background:var(--success);
}

.btn-warning{
    background:var(--warning);
}

button:hover{
    opacity:0.9;
    transform:translateY(-1px);
}

#btnTambah{
    margin-bottom:10px;
}

.table-wrapper{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

thead{
    background:#f1f5f9;
}

th,
td{
    border:1px solid var(--border);
    padding:12px;
    text-align:center;
}

tbody tr:hover{
    background:#f9fafb;
}

.badge-total{
    display:inline-block;
    background:#0a3d62;
    color:white;
    padding:3px 10px;
    border-radius:20px;
    font-size:13px;
    cursor:pointer;
}

.badge-total:hover{
    background:#1a5276;
}

.modal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.45);
    justify-content:center;
    align-items:center;
    z-index:999;
}

.modal-content{
    background:white;
    padding:24px;
    border-radius:16px;
    width:340px;
    animation:fade 0.2s ease;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.modal-content input,
.modal-content select{
    width:100%;
    margin-bottom:10px;
}

.modal-content button{
    width:100%;
    margin-top:5px;
}

#modalSiswa .modal-content{
    width:600px;
    max-width:95vw;
    max-height:80vh;
    overflow-y:auto;
}

#modalSiswa table{
    font-size:13px;
}

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

@keyframes fade{
    from{
        opacity:0;
        transform:scale(0.95);
    }
    to{
        opacity:1;
        transform:scale(1);
    }
}
</style>

<div class="card">

    <h3>Manajemen Kelas</h3>

    <div class="form-grid">
        <input id="nama_kelas" type="number" placeholder="Nama Kelas">

        <select id="id_guru">
            <option value="">Memuat guru...</option>
        </select>

        <select id="id_tahun_ajaran">
            <option value="">Memuat tahun ajaran...</option>
        </select>
    </div>

    <button id="btnTambah" class="btn-primary">Tambah</button>

    <div class="filter-box">
        <label for="filter_tahun_ajaran">Tampilkan Data Berdasarkan Tahun Ajaran / Semester</label>
        <select id="filter_tahun_ajaran">
            <option value="">Semua Tahun Ajaran</option>
        </select>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Wali Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Total Siswa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="data">
                <tr>
                    <td colspan="5">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>


<!-- Modal Edit -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h3>Edit Kelas</h3>

        <input id="edit_nama" type="number" placeholder="Nama Kelas">

        <select id="edit_guru">
            <option value="">Memuat guru...</option>
        </select>

        <select id="edit_tahun">
            <option value="">Memuat tahun ajaran...</option>
        </select>

        <button id="btnUpdate" class="btn-primary">Update</button>
        <button id="btnTutup" class="btn-secondary">Batal</button>
    </div>
</div>


<!-- Modal Siswa -->
<div id="modalSiswa" class="modal">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h3 style="margin:0;" id="judulSiswa">Daftar Siswa</h3>

            <button class="btn-success" onclick="downloadExcel()" style="width:auto; padding:8px 14px;">
                ⬇ Excel
            </button>
        </div>

        <div class="table-wrapper">
            <table id="tblSiswa">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Tempat Lahir</th>
                        <th>Tgl Lahir</th>
                    </tr>
                </thead>
                <tbody id="dataSiswa">
                    <tr>
                        <td colspan="6">Belum ada data</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button onclick="tutupSiswa()" class="btn-secondary" style="margin-top:15px;">Tutup</button>
    </div>
</div>


<!-- Modal Notif -->
<div id="modalNotif" class="modal">
    <div class="modal-content" style="text-align:center;">
        <div id="notifIcon" class="notif-icon">✓</div>

        <h3 id="notifTitle">Berhasil</h3>

        <p id="notifText" style="margin-bottom:20px; color:#6b7280; font-size:14px; line-height:1.5;">
            Data berhasil disimpan
        </p>

        <button onclick="tutupNotif()" class="btn-primary">OK</button>
    </div>
</div>

@endsection


@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
let editId = null;
let namaKelasAktif = '';

document.addEventListener('DOMContentLoaded', () => {
    loadGuru();
    loadTahun();
    loadData();

    document.getElementById('btnTambah').addEventListener('click', tambah);
    document.getElementById('btnUpdate').addEventListener('click', update);
    document.getElementById('btnTutup').addEventListener('click', tutup);

    document.getElementById('filter_tahun_ajaran').addEventListener('change', () => {
        loadData();
    });
});


function loadGuru(){
    fetch('/api/pegawai/guru-kelas')
    .then(r => r.json())
    .then(res => {
        let html = '<option value="">-- Tanpa Wali Kelas --</option>';

        if(res.success && res.data.length){
            res.data.forEach(g => {
                html += `<option value="${g.id_guru}">${g.nama_guru}</option>`;
            });
        }

        document.getElementById('id_guru').innerHTML = html;
        document.getElementById('edit_guru').innerHTML = html;
    })
    .catch(() => {
        showNotif('danger', 'Gagal memuat data guru');
    });
}


function loadTahun(){
    fetch('/api/tahun-ajaran')
    .then(r => r.json())
    .then(res => {
        let htmlForm = '<option value="">Pilih Tahun Ajaran</option>';
        let htmlFilter = '<option value="">Semua Tahun Ajaran</option>';

        if(res.success && res.data.length){
            res.data.forEach(t => {
                htmlForm += `<option value="${t.id_tahun_ajaran}">${t.periode} - ${t.semester}</option>`;
                htmlFilter += `<option value="${t.id_tahun_ajaran}">${t.periode} - ${t.semester}</option>`;
            });
        }

        document.getElementById('id_tahun_ajaran').innerHTML = htmlForm;
        document.getElementById('edit_tahun').innerHTML = htmlForm;
        document.getElementById('filter_tahun_ajaran').innerHTML = htmlFilter;
    })
    .catch(() => {
        showNotif('danger', 'Gagal memuat data tahun ajaran');
    });
}


function loadData(){
    const filterTahun = document.getElementById('filter_tahun_ajaran').value;
    let url = '/api/kelas';

    if(filterTahun){
        url += '?id_tahun_ajaran=' + filterTahun;
    }

    document.getElementById('data').innerHTML = `
        <tr>
            <td colspan="5">Memuat data...</td>
        </tr>
    `;

    fetch(url)
    .then(r => r.json())
    .then(async res => {
        if(!res.success){
            document.getElementById('data').innerHTML = `
                <tr>
                    <td colspan="5">${res.message || 'Gagal memuat data'}</td>
                </tr>
            `;
            return;
        }

        if(!res.data.length){
            document.getElementById('data').innerHTML = `
                <tr>
                    <td colspan="5">Belum ada data</td>
                </tr>
            `;
            return;
        }

        const counts = await Promise.all(
            res.data.map(k =>
                fetch('/api/siswa-by-kelas/' + k.id_kelas)
                .then(r => r.json())
                .then(r => ({
                    id: k.id_kelas,
                    total: (r.data ?? []).length
                }))
                .catch(() => ({
                    id: k.id_kelas,
                    total: 0
                }))
            )
        );

        const countMap = {};
        counts.forEach(c => {
            countMap[c.id] = c.total;
        });

        let html = '';

        res.data.forEach(k => {
            const total = countMap[k.id_kelas] ?? 0;

            const tahun = k.tahun_ajaran
                ? `${k.tahun_ajaran.periode} - ${k.tahun_ajaran.semester}`
                : '-';

            html += `
                <tr>
                    <td>${k.nama_kelas}</td>
                    <td>${k.pegawai?.nama_guru ?? '-'}</td>
                    <td>${tahun}</td>
                    <td>
                        <span class="badge-total" data-id="${k.id_kelas}" data-nama="${k.nama_kelas}">
                            ${total} siswa
                        </span>
                    </td>
                    <td>
                        <button class="btn-edit" data-id="${k.id_kelas}">Edit</button>
                        <button class="btn-danger" data-id="${k.id_kelas}">Hapus</button>
                    </td>
                </tr>
            `;
        });

        document.getElementById('data').innerHTML = html;

        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.onclick = () => edit(btn.dataset.id);
        });

        document.querySelectorAll('.btn-danger').forEach(btn => {
            btn.onclick = () => hapus(btn.dataset.id);
        });

        document.querySelectorAll('.badge-total').forEach(badge => {
            badge.onclick = () => lihatSiswa(badge.dataset.id, badge.dataset.nama);
        });
    })
    .catch(() => {
        document.getElementById('data').innerHTML = `
            <tr>
                <td colspan="5">Gagal memuat data</td>
            </tr>
        `;
    });
}


function tambah(){
    const namaKelas = document.getElementById('nama_kelas').value;
    const idGuru = document.getElementById('id_guru').value;
    const idTahunAjaran = document.getElementById('id_tahun_ajaran').value;

    if(!namaKelas){
        showNotif('warning', 'Isi nama kelas!');
        return;
    }

    if(!idTahunAjaran){
        showNotif('warning', 'Pilih tahun ajaran!');
        return;
    }

    fetch('/api/kelas', {
        method:'POST',
        headers:{
            'Content-Type':'application/json'
        },
        body: JSON.stringify({
            nama_kelas: namaKelas,
            id_guru: idGuru || null,
            id_tahun_ajaran: idTahunAjaran
        })
    })
    .then(r => r.json())
    .then(res => {
        if(res.success){
            document.getElementById('nama_kelas').value = '';
            document.getElementById('id_guru').value = '';
            document.getElementById('id_tahun_ajaran').value = '';

            showNotif('success', res.message || 'Berhasil tambah data kelas');
            loadData();
        } else {
            showNotif('danger', res.message || 'Gagal tambah data kelas');
        }
    })
    .catch(() => {
        showNotif('danger', 'Gagal tambah data kelas');
    });
}


function edit(id){
    editId = id;

    fetch('/api/kelas/' + id)
    .then(r => r.json())
    .then(res => {
        if(!res.success){
            showNotif('danger', res.message || 'Data kelas tidak ditemukan');
            return;
        }

        let d = res.data;

        document.getElementById('edit_nama').value = d.nama_kelas;
        document.getElementById('edit_guru').value = d.id_guru ?? '';
        document.getElementById('edit_tahun').value = d.id_tahun_ajaran;

        document.getElementById('modal').style.display = 'flex';
    })
    .catch(() => {
        showNotif('danger', 'Gagal mengambil data kelas');
    });
}


function update(){
    const editNama = document.getElementById('edit_nama').value;
    const editGuru = document.getElementById('edit_guru').value;
    const editTahun = document.getElementById('edit_tahun').value;

    if(!editNama){
        showNotif('warning', 'Isi nama kelas!');
        return;
    }

    if(!editTahun){
        showNotif('warning', 'Pilih tahun ajaran!');
        return;
    }

    fetch('/api/kelas/' + editId, {
        method:'PUT',
        headers:{
            'Content-Type':'application/json'
        },
        body: JSON.stringify({
            nama_kelas: editNama,
            id_guru: editGuru || null,
            id_tahun_ajaran: editTahun
        })
    })
    .then(r => r.json())
    .then(res => {
        if(res.success){
            tutup();
            showNotif('edit', res.message || 'Data kelas berhasil diperbarui');
            loadData();
        } else {
            showNotif('danger', res.message || 'Gagal update data kelas');
        }
    })
    .catch(() => {
        showNotif('danger', 'Gagal update data kelas');
    });
}


function hapus(id){
    if(confirm('Yakin hapus data?')){
        fetch('/api/kelas/' + id, {
            method:'DELETE'
        })
        .then(r => r.json())
        .then(res => {
            if(res.success){
                showNotif('delete', res.message || 'Berhasil hapus data kelas');
                loadData();
            } else {
                showNotif('danger', res.message || 'Gagal hapus data kelas');
            }
        })
        .catch(() => {
            showNotif('danger', 'Gagal hapus data kelas');
        });
    }
}


function lihatSiswa(id_kelas, nama){
    namaKelasAktif = nama;

    document.getElementById('judulSiswa').textContent = `Siswa Kelas ${nama}`;
    document.getElementById('dataSiswa').innerHTML = `
        <tr>
            <td colspan="6">Memuat...</td>
        </tr>
    `;
    document.getElementById('modalSiswa').style.display = 'flex';

    fetch('/api/siswa-by-kelas/' + id_kelas)
    .then(r => r.json())
    .then(res => {
        let html = '';

        if(!res.success || !res.data.length){
            html = `
                <tr>
                    <td colspan="6">Belum ada siswa</td>
                </tr>
            `;
        } else {
            res.data.forEach((s, i) => {
                html += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${s.nis ?? '-'}</td>
                        <td style="text-align:left">${s.nama_siswa ?? '-'}</td>
                        <td>${s.jenis_kelamin ?? '-'}</td>
                        <td>${s.tempat_lahir ?? '-'}</td>
                        <td>${s.tanggal_lahir ?? '-'}</td>
                    </tr>
                `;
            });
        }

        document.getElementById('dataSiswa').innerHTML = html;
    })
    .catch(() => {
        document.getElementById('dataSiswa').innerHTML = `
            <tr>
                <td colspan="6">Gagal memuat data siswa</td>
            </tr>
        `;
    });
}


function tutupSiswa(){
    document.getElementById('modalSiswa').style.display = 'none';
}


function downloadExcel(){
    const rows = [
        ['No', 'NIS', 'Nama Siswa', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir']
    ];

    document.querySelectorAll('#dataSiswa tr').forEach(tr => {
        const cols = tr.querySelectorAll('td');

        if(cols.length > 1){
            rows.push(Array.from(cols).map(td => td.textContent.trim()));
        }
    });

    if(rows.length === 1){
        showNotif('warning', 'Tidak ada data siswa untuk diunduh');
        return;
    }

    const ws = XLSX.utils.aoa_to_sheet(rows);

    ws['!cols'] = [
        {wch:5},
        {wch:12},
        {wch:28},
        {wch:15},
        {wch:18},
        {wch:15}
    ];

    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, `Kelas ${namaKelasAktif}`);
    XLSX.writeFile(wb, `Siswa_Kelas_${namaKelasAktif}.xlsx`);
}


function showNotif(type, message){
    const icon = document.getElementById('notifIcon');
    const title = document.getElementById('notifTitle');
    const text = document.getElementById('notifText');

    const map = {
        success:{
            i:'✓',
            bg:'#16a34a',
            t:'Berhasil'
        },
        delete:{
            i:'🗑',
            bg:'#dc2626',
            t:'Data Dihapus'
        },
        edit:{
            i:'✎',
            bg:'#2563eb',
            t:'Data Diperbarui'
        },
        warning:{
            i:'!',
            bg:'#f59e0b',
            t:'Peringatan'
        },
        danger:{
            i:'✕',
            bg:'#dc2626',
            t:'Gagal'
        }
    };

    const m = map[type] ?? map.danger;

    icon.innerHTML = m.i;
    icon.style.background = m.bg;
    title.innerText = m.t;
    text.innerText = message;

    document.getElementById('modalNotif').style.display = 'flex';
}


function tutupNotif(){
    document.getElementById('modalNotif').style.display = 'none';
}


function tutup(){
    document.getElementById('modal').style.display = 'none';
}
</script>
@endsection