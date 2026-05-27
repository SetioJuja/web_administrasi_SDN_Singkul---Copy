@extends('layouts.app')

@section('title','Data Pegawai')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}
body{
    background:#f8fafc;
    font-family:'DM Sans',sans-serif;
    color:#334155;
    font-size:14px;
}
.page-wrapper{
    max-width:1100px;
    margin:auto;
    padding:20px;
}
.page-title{
    margin-bottom:20px;
}
.page-title h1{
    font-size:22px;
    margin-bottom:4px;
}
.page-title p{
    color:#64748b;
    font-size:13px;
}
.card{
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:10px;
    margin-bottom:20px;
    overflow:hidden;
}
.card-header{
    padding:15px 18px;
    border-bottom:1px solid #e2e8f0;
}
.card-header h2{
    font-size:16px;
    margin-bottom:3px;
}
.card-header p{
    font-size:12px;
    color:#64748b;
}
.card-body{
    padding:18px;
}
.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:14px;
}
.form-group{
    display:flex;
    flex-direction:column;
    gap:5px;
}
.form-group label{
    font-size:12px;
    font-weight:500;
}
.form-group label .optional{
    font-size:11px;
    color:#94a3b8;
    font-weight:400;
    margin-left:4px;
}
input,
select{
    width:100%;
    padding:10px 12px;
    border:1px solid #cbd5e1;
    border-radius:8px;
    outline:none;
    font-size:13px;
    font-family:inherit;
}
input:focus,
select:focus{
    border-color:#2563eb;
}
input.error,
select.error{
    border-color:#ef4444;
}
.field-error{
    font-size:11px;
    color:#ef4444;
    margin-top:2px;
}
.button-group{
    display:flex;
    gap:10px;
    margin-top:20px;
    flex-wrap:wrap;
}
.btn{
    border:none;
    border-radius:8px;
    padding:9px 14px;
    font-size:13px;
    cursor:pointer;
    font-family:inherit;
    transition:.2s;
}
.btn:hover{
    opacity:.9;
}
.btn-primary{
    background:#2563eb;
    color:#fff;
}
.btn-warning{
    background:#f59e0b;
    color:#fff;
}
.btn-danger{
    background:#ef4444;
    color:#fff;
}
.btn-info{
    background:#0f766e;
    color:#fff;
}
.btn-secondary{
    background:#e2e8f0;
    color:#334155;
}
.btn-sm{
    padding:6px 10px;
    font-size:12px;
}
.jabatan-title{
    margin:18px 0 10px;
    font-size:13px;
    font-weight:600;
}
#jabatan{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}
.jabatan-item{
    border:1px solid #cbd5e1;
    padding:8px 12px;
    border-radius:30px;
    font-size:12px;
    cursor:pointer;
}
.jabatan-item input{
    display:none;
}
.jabatan-item:has(input:checked){
    background:#dbeafe;
    border-color:#2563eb;
    color:#2563eb;
}
.table-top{
    margin-bottom:15px;
}
.search{
    width:100%;
    max-width:320px;
}
.table-wrap{
    overflow-x:auto;
}
table{
    width:100%;
    border-collapse:collapse;
    min-width:700px;
}
thead{
    background:#f8fafc;
}
th,
td{
    padding:12px;
    border-bottom:1px solid #e2e8f0;
    text-align:left;
}
th{
    font-size:12px;
    color:#64748b;
}
.badge{
    display:inline-block;
    padding:4px 8px;
    border-radius:20px;
    background:#dbeafe;
    color:#2563eb;
    font-size:11px;
    margin:2px;
}
.action{
    display:flex;
    gap:6px;
}
.empty{
    text-align:center;
    padding:30px;
    color:#94a3b8;
}
.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.4);
    display:none;
    justify-content:center;
    align-items:center;
    padding:20px;
    z-index:999;
}
.modal-box{
    width:100%;
    max-width:500px;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
}
.modal-head{
    padding:15px 18px;
    border-bottom:1px solid #e2e8f0;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.modal-head h3{
    font-size:16px;
}
.modal-close{
    border:none;
    background:none;
    font-size:22px;
    cursor:pointer;
}
.modal-body{
    padding:18px;
}
.detail-table{
    width:100%;
}
.detail-table td{
    padding:10px 0;
    vertical-align:top;
}
.detail-table td:first-child{
    width:150px;
    color:#64748b;
    font-weight:500;
}
/* Toast Notification */
.toast-container{
    position:fixed;
    top:20px;
    right:20px;
    z-index:9999;
    display:flex;
    flex-direction:column;
    gap:10px;
    pointer-events:none;
}
.toast{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 16px;
    border-radius:10px;
    background:#fff;
    box-shadow:0 4px 20px rgba(0,0,0,.12);
    border-left:4px solid #2563eb;
    min-width:280px;
    max-width:380px;
    pointer-events:all;
    animation:slideIn .3s ease;
    font-size:13px;
}
.toast.success{ border-color:#22c55e; }
.toast.error{ border-color:#ef4444; }
.toast.warning{ border-color:#f59e0b; }
.toast-icon{
    font-size:18px;
    flex-shrink:0;
}
.toast-text{ flex:1; }
.toast-title{ font-weight:600; font-size:13px; margin-bottom:2px; }
.toast-msg{ font-size:12px; color:#64748b; }
.toast-close{
    border:none;
    background:none;
    cursor:pointer;
    color:#94a3b8;
    font-size:16px;
    line-height:1;
    padding:0;
}
.toast.hiding{
    animation:slideOut .3s ease forwards;
}
@keyframes slideIn{
    from{ transform:translateX(120%); opacity:0; }
    to{ transform:translateX(0); opacity:1; }
}
@keyframes slideOut{
    from{ transform:translateX(0); opacity:1; }
    to{ transform:translateX(120%); opacity:0; }
}
@media(max-width:600px){
    .page-wrapper{
        padding:14px;
    }
    .form-grid{
        grid-template-columns:1fr;
    }
    .search{
        max-width:100%;
    }
    .toast{
        min-width:unset;
        max-width:calc(100vw - 40px);
    }
    .toast-container{
        right:10px;
        left:10px;
    }
}
</style>

<div class="page-wrapper">
    <div class="page-title">
        <h1>Data Pegawai</h1>
        <p>Kelola data pegawai dan jabatan.</p>
    </div>
    <!-- FORM -->
    <div class="card">
        <div class="card-header">
            <h2>Tambah / Edit Pegawai</h2>
            <p>Isi data pegawai di bawah ini. Kolom bertanda <span style="color:#94a3b8;font-size:11px;">(opsional)</span> tidak wajib diisi.</p>
        </div>
        <div class="card-body">
            <input type="hidden" id="id_guru">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Pegawai</label>
                    <input id="nama_guru" placeholder="Nama lengkap">
                    <span class="field-error" id="err_nama"></span>
                </div>

                <div class="form-group">
                    <label>NIP <span class="optional">(opsional)</span></label>
                    <input id="nip" placeholder="Nomor induk pegawai">
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select id="jenis_kelamin">
                        <option value="">Pilih</option>
                        <option value="Laki-laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    <span class="field-error" id="err_jk"></span>
                </div>

                <div class="form-group">
                    <label>Tempat Lahir <span class="optional"></span></label>
                    <input id="tempat_lahir">
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir <span class="optional"></span></label>
                    <input type="date" id="tanggal_lahir">
                </div>

                <div class="form-group">
                    <label>Alamat <span class="optional">(opsional)</span></label>
                    <input id="alamat">
                </div>

                <div class="form-group">
                    <label>No Telepon <span class="optional">(opsional)</span></label>
                    <input id="no_telepon">
                </div>

                <div class="form-group">
                    <label>Email<span class="optional">(opsional)</span></label>
                    <input id="email" placeholder="contoh@email.com">
                    <span class="field-error" id="err_email"></span>
                </div>

                <div class="form-group">
                    <label>Golongan <span class="optional">(opsional)</span></label>
                    <select id="golongan">
                        <option value="">Pilih Golongan</option>
                        <optgroup label="Golongan I (Juru) — SD s/d SMP">
                            <option value="Ia">Ia — Juru Muda</option>
                            <option value="Ib">Ib — Juru Muda Tingkat I</option>
                            <option value="Ic">Ic — Juru</option>
                            <option value="Id">Id — Juru Tingkat I</option>
                        </optgroup>
                        <optgroup label="Golongan II (Pengatur) — SMA/SMK s/d D3">
                            <option value="IIa">IIa — Pengatur Muda</option>
                            <option value="IIb">IIb — Pengatur Muda Tingkat I</option>
                            <option value="IIc">IIc — Pengatur</option>
                            <option value="IId">IId — Pengatur Tingkat I</option>
                        </optgroup>
                        <optgroup label="Golongan III (Penata) — D4/S1 s/d S3">
                            <option value="IIIa">IIIa — Penata Muda</option>
                            <option value="IIIb">IIIb — Penata Muda Tingkat I</option>
                            <option value="IIIc">IIIc — Penata</option>
                            <option value="IIId">IIId — Penata Tingkat I</option>
                        </optgroup>
                        <optgroup label="Golongan IV (Pembina) — Promosi & Evaluasi Kinerja">
                            <option value="IVa">IVa — Pembina</option>
                            <option value="IVb">IVb — Pembina Tingkat I</option>
                            <option value="IVc">IVc — Pembina Utama Muda</option>
                            <option value="IVd">IVd — Pembina Utama Madya</option>
                            <option value="IVe">IVe — Pembina Utama</option>
                        </optgroup>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pendidikan <span class="optional"></span></label>
                    <select id="pendidikan_tertinggi">
                        <option value="">Pilih Pendidikan</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA/SMK">SMA/SMK</option>
                        <option value="D1">D1</option>
                        <option value="D2">D2</option>
                        <option value="D3">D3</option>
                        <option value="D4/S1">D4/S1</option>
                        <option value="S2">S2 (Magister)</option>
                        <option value="S3">S3 (Doktoral)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status Kepegawaian <span class="optional"></span></label>
                    <select id="status_kepegawaian">
                        <option value="">Pilih Status</option>
                        <option value="PNS">PNS</option>
                        <option value="PPPK">PPPK</option>
                        <option value="Honorer">Honorer</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Magang">Magang</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Masuk <span class="optional"></span></label>
                    <input type="date" id="tanggal_masuk">
                </div>

                <div class="form-group">
                    <label>Username <span class="optional">minimal 6 karakter</span></label>
                    <input id="username">
                </div>

                <div class="form-group">
                    <label>Password <span class="optional">(opsional saat edit)</span></label>
                    <input type="password" id="password" placeholder="Kosongkan jika tidak diubah">
                    <span class="field-error" id="err_password"></span>
                </div>

            </div>

            <div class="jabatan-title">Jabatan <span style="color:#94a3b8;font-size:11px;font-weight:400;"></span></div>

            <div id="jabatan"></div>

            <div class="button-group">
                <button class="btn btn-primary" id="btnSimpan">Simpan</button>
                <button class="btn btn-secondary" onclick="resetForm()">Reset</button>
            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="card">

        <div class="card-header">
            <h2>Daftar Pegawai</h2>
            <p>Seluruh data pegawai</p>
        </div>

        <div class="card-body">

            <div class="table-top">
                <input type="text" id="search" class="search" placeholder="Cari pegawai...">
            </div>

            <div class="table-wrap">

                <table>

                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jenis Kelamin</th>
                            <th>Email</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="data">
                        <tr>
                            <td colspan="6" class="empty">Memuat data...</td>
                        </tr>
                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- MODAL -->
<div class="modal" id="modalDetail">

    <div class="modal-box">

        <div class="modal-head">
            <h3>Detail Pegawai</h3>
            <button class="modal-close" onclick="tutupModal()">&times;</button>
        </div>

        <div class="modal-body">
            <table class="detail-table" id="detailData"></table>
        </div>

    </div>

</div>

<!-- TOAST CONTAINER -->
<div class="toast-container" id="toastContainer"></div>

@endsection

@section('script')

<script>

let allData = [];

/* ===================== TOAST ===================== */
function showToast(type, title, msg, duration = 3500){

    const icons = {
        success: '',
        error: '',
        warning: '',
        info: ''
    };

    const container = document.getElementById('toastContainer');

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <div class="toast-icon">${icons[type] || ''}</div>
        <div class="toast-text">
            <div class="toast-title">${title}</div>
            ${msg ? `<div class="toast-msg">${msg}</div>` : ''}
        </div>
        <button class="toast-close" onclick="removeToast(this.parentElement)">×</button>
    `;

    container.appendChild(toast);

    setTimeout(() => removeToast(toast), duration);

}

function removeToast(el){
    if(!el || el.classList.contains('hiding')) return;
    el.classList.add('hiding');
    setTimeout(() => el.remove(), 300);
}

/* ===================== INIT ===================== */
document.addEventListener('DOMContentLoaded', () => {

    loadJabatan();
    loadData();

    btnSimpan.onclick = simpan;
    search.oninput = renderData;

});

/* ===================== UTILS ===================== */
function formatTanggal(t){
    if(!t) return '-';
    return t.split('T')[0];
}

function labelGolongan(val){
    const map = {
        'Ia':'Ia — Juru Muda','Ib':'Ib — Juru Muda Tingkat I','Ic':'Ic — Juru','Id':'Id — Juru Tingkat I',
        'IIa':'IIa — Pengatur Muda','IIb':'IIb — Pengatur Muda Tingkat I','IIc':'IIc — Pengatur','IId':'IId — Pengatur Tingkat I',
        'IIIa':'IIIa — Penata Muda','IIIb':'IIIb — Penata Muda Tingkat I','IIIc':'IIIc — Penata','IIId':'IIId — Penata Tingkat I',
        'IVa':'IVa — Pembina','IVb':'IVb — Pembina Tingkat I','IVc':'IVc — Pembina Utama Muda','IVd':'IVd — Pembina Utama Madya','IVe':'IVe — Pembina Utama'
    };
    return map[val] || val || '-';
}

/* ===================== VALIDATION ===================== */
function clearErrors(){
    document.querySelectorAll('.field-error').forEach(e => e.textContent = '');
    document.querySelectorAll('input.error, select.error').forEach(e => e.classList.remove('error'));
}

function setError(fieldId, errId, msg){
    const field = document.getElementById(fieldId);
    const err   = document.getElementById(errId);
    if(field) field.classList.add('error');
    if(err)   err.textContent = msg;
}

function isValidEmail(email){
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function isEmailTaken(email, excludeId = null){
    return allData.some(p =>
        (p.email || '').toLowerCase() === email.toLowerCase() &&
        p.id_guru != excludeId
    );
}

function validateForm(){

    clearErrors();
    let valid = true;

    if(!nama_guru.value.trim()){
        setError('nama_guru','err_nama','Nama pegawai wajib diisi');
        valid = false;
    }

    if(!jenis_kelamin.value){
        setError('jenis_kelamin','err_jk','Jenis kelamin wajib dipilih');
        valid = false;
    }

    const emailVal = email.value.trim();
    if(!emailVal){
        setError('email','err_email','Email wajib diisi');
        valid = false;
    } else if(!isValidEmail(emailVal)){
        setError('email','err_email','Format email tidak valid');
        valid = false;
    } else if(isEmailTaken(emailVal, id_guru.value || null)){
        setError('email','err_email','Email sudah digunakan oleh pegawai lain');
        valid = false;
    }

    const isNew = !id_guru.value;
    if(isNew && !password.value){
        setError('password','err_password','Password wajib diisi untuk pegawai baru');
        valid = false;
    }

    if(!valid){
        showToast('error','Validasi Gagal','Periksa kembali isian form');
    }

    return valid;

}

/* ===================== JABATAN ===================== */
function loadJabatan(){

    fetch('/api/jabatan')
    .then(res => res.json())
    .then(res => {

        jabatan.innerHTML = res.data.map(j => `
            <label class="jabatan-item">
                <input 
                    type="checkbox"
                    value="${j.id_jabatan}"
                    class="jabatan">
                ${j.nama_jabatan}
            </label>
        `).join('');

    });

}

function getSelectedJabatan(){
    return [...document.querySelectorAll('.jabatan:checked')]
    .map(x => parseInt(x.value));
}

/* ===================== LOAD DATA ===================== */
function loadData(){

    fetch('/api/pegawai')
    .then(res => res.json())
    .then(res => {
        allData = res.data;
        renderData();
    });

}

/* ===================== RENDER TABLE ===================== */
function renderData(){

    let key = search.value.toLowerCase();

    let filtered = allData.filter(p =>
        (p.nama_guru || '').toLowerCase().includes(key) ||
        (p.nip || '').toLowerCase().includes(key) ||
        (p.email || '').toLowerCase().includes(key)
    );

    if(!filtered.length){
        data.innerHTML = `<tr><td colspan="6" class="empty">Tidak ada data</td></tr>`;
        return;
    }

    data.innerHTML = filtered.map(p => {

        let roles = p.jabatan?.map(j => `<span class="badge">${j.nama_jabatan}</span>`).join('') || '-';

        return `
        <tr>
            <td>${p.nama_guru ?? '-'}</td>
            <td>${p.nip ?? '-'}</td>
            <td>${p.jenis_kelamin ?? '-'}</td>
            <td>${p.email ?? '-'}</td>
            <td>${roles}</td>
            <td>
                <div class="action">
                    <button class="btn btn-info btn-sm" onclick="detail(${p.id_guru})">Detail</button>
                    <button class="btn btn-warning btn-sm" onclick="editData(${p.id_guru})">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="hapus(${p.id_guru})">Hapus</button>
                </div>
            </td>
        </tr>`;

    }).join('');

}

/* ===================== DETAIL MODAL ===================== */
function detail(id){

    let p = allData.find(x => x.id_guru == id);

    let roles = p.jabatan?.map(j => `<span class="badge">${j.nama_jabatan}</span>`).join('') || '-';

    let fields = [
        ['Username', p.username],
        ['Nama', p.nama_guru],
        ['NIP', p.nip],
        ['Jenis Kelamin', p.jenis_kelamin],
        ['Tempat Lahir', p.tempat_lahir],
        ['Tanggal Lahir', formatTanggal(p.tanggal_lahir)],
        ['Alamat', p.alamat],
        ['No Telepon', p.no_telepon],
        ['Email', p.email],
        ['Golongan', labelGolongan(p.golongan)],
        ['Pendidikan', p.pendidikan_tertinggi],
        ['Status', p.status_kepegawaian],
        ['Tanggal Masuk', formatTanggal(p.tanggal_masuk)]
    ];

    detailData.innerHTML =
        fields.map(([k,v]) => `
            <tr>
                <td>${k}</td>
                <td>${v ?? '-'}</td>
            </tr>
        `).join('')
        +
        `<tr><td>Jabatan</td><td>${roles}</td></tr>`;

    modalDetail.style.display = 'flex';

}

function tutupModal(){
    modalDetail.style.display = 'none';
}

/* ===================== SIMPAN ===================== */
function simpan(){

    if(!validateForm()) return;

    let id = id_guru.value;
    let isEdit = !!id;

    let payload = {
        _method: isEdit ? 'PUT' : 'POST',
        nama_guru: nama_guru.value.trim(),
        nip: nip.value.trim(),
        jenis_kelamin: jenis_kelamin.value,
        tempat_lahir: tempat_lahir.value.trim(),
        tanggal_lahir: tanggal_lahir.value,
        alamat: alamat.value.trim(),
        no_telepon: no_telepon.value.trim(),
        email: email.value.trim(),
        golongan: golongan.value,
        pendidikan_tertinggi: pendidikan_tertinggi.value,
        status_kepegawaian: status_kepegawaian.value,
        tanggal_masuk: tanggal_masuk.value,
        username: username.value,
        jabatan: getSelectedJabatan()
    };

    if(password.value){
        payload.password = password.value;
    }

    btnSimpan.disabled = true;
    btnSimpan.textContent = 'Menyimpan...';

    fetch('/api/pegawai' + (id ? '/' + id : ''), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })

    .then(res => res.json())

    .then(res => {

        if(res.success){

            showToast(
                'success',
                isEdit ? 'Data Berhasil Diperbarui' : 'Data Berhasil Ditambahkan',
                `Pegawai "${nama_guru.value.trim()}" telah ${isEdit ? 'diperbarui' : 'ditambahkan'}.`
            );

            resetForm();
            loadData();

        } else {

            // Handle server-side errors (e.g. email duplicate from server)
            if(res.errors?.email){
                setError('email','err_email', res.errors.email[0] || 'Email sudah digunakan');
            }

            showToast('error', 'Gagal Menyimpan', res.message || 'Terjadi kesalahan pada server');

        }

    })

    .catch(() => {
        showToast('error','Kesalahan Jaringan','Tidak dapat terhubung ke server');
    })

    .finally(() => {
        btnSimpan.disabled = false;
        btnSimpan.textContent = 'Simpan';
    });

}

/* ===================== EDIT ===================== */
function editData(id){

    let p = allData.find(x => x.id_guru == id);

    id_guru.value            = p.id_guru;
    nama_guru.value          = p.nama_guru ?? '';
    nip.value                = p.nip ?? '';
    jenis_kelamin.value      = p.jenis_kelamin ?? '';
    tempat_lahir.value       = p.tempat_lahir ?? '';
    tanggal_lahir.value      = p.tanggal_lahir?.split('T')[0] ?? '';
    alamat.value             = p.alamat ?? '';
    no_telepon.value         = p.no_telepon ?? '';
    email.value              = p.email ?? '';
    golongan.value           = p.golongan ?? '';
    pendidikan_tertinggi.value = p.pendidikan_tertinggi ?? '';
    status_kepegawaian.value = p.status_kepegawaian ?? '';
    tanggal_masuk.value      = p.tanggal_masuk?.split('T')[0] ?? '';
    username.value           = p.username ?? '';
    password.value           = '';

    document.querySelectorAll('.jabatan').forEach(c => {
        c.checked = p.jabatan?.some(j => j.id_jabatan == c.value) ?? false;
    });

    clearErrors();

    showToast('info','Mode Edit',`Mengedit data pegawai "${p.nama_guru}"`);

    window.scrollTo({ top: 0, behavior: 'smooth' });

}

/* ===================== RESET ===================== */
function resetForm(){

    document.querySelectorAll('input').forEach(i => i.value = '');

    jenis_kelamin.value        = '';
    golongan.value             = '';
    pendidikan_tertinggi.value = '';
    status_kepegawaian.value   = '';

    document.querySelectorAll('.jabatan').forEach(c => c.checked = false);

    id_guru.value = '';

    clearErrors();

}

/* ===================== HAPUS ===================== */
function hapus(id){

    let p = allData.find(x => x.id_guru == id);

    if(confirm(`Yakin ingin menghapus data pegawai "${p?.nama_guru ?? ''}"?`)){
        fetch('/api/pegawai/' + id, {

            method:'DELETE'
        })
        .then(res => res.json())
        .then(res => {
            // jika gagal
            if(!res.success){
                showToast(
                    'warning',
                    'Data Tidak Bisa Dihapus',
                    res.message || 'Data masih digunakan'
                );
                return;
            }
            // jika berhasil
            showToast(
                'success',
                'Data Dihapus',
                `Pegawai "${p?.nama_guru}" berhasil dihapus`
            );
            loadData();
        })
        .catch(() => {
            showToast(
                'error',
                'Gagal Menghapus',
                'Terjadi kesalahan saat menghapus data'
            );

        });
    }
}
</script>

@endsection