<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Pegawai</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:sans-serif;font-size:14px;color:#334155;background:#f8fafc}
.wrap{max-width:1000px;margin:0 auto;padding:16px}
h1{font-size:20px;font-weight:600;margin-bottom:4px}
.sub{color:#64748b;font-size:13px;margin-bottom:16px}
.card{background:#fff;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:16px;overflow:hidden}
.card-head{padding:12px 16px;border-bottom:1px solid #e2e8f0}
.card-head h2{font-size:15px;font-weight:600}
.card-body{padding:16px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px}
.fg{display:flex;flex-direction:column;gap:4px}
.fg label{font-size:12px;color:#64748b;font-weight:500}
input,select{width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;font-family:inherit;color:#334155;background:#fff}
input:focus,select:focus{outline:none;border-color:#2563eb}
input.err,select.err{border-color:#ef4444}
.ferr{font-size:11px;color:#ef4444}
.jab-wrap{display:flex;flex-wrap:wrap;gap:8px;margin-top:6px}
.jab-item{padding:5px 12px;border:1px solid #cbd5e1;border-radius:20px;font-size:12px;cursor:pointer;user-select:none;color:#64748b}
.jab-item.active{background:#dbeafe;border-color:#2563eb;color:#2563eb}
.btn-row{display:flex;gap:8px;margin-top:16px;flex-wrap:wrap}
button{padding:8px 14px;border-radius:8px;border:1px solid #cbd5e1;font-size:13px;cursor:pointer;font-family:inherit;background:#fff;color:#334155}
button:hover{opacity:.9}
.btn-primary{background:#2563eb;border-color:#2563eb;color:#fff}
.btn-danger{background:#ef4444;border-color:#ef4444;color:#fff}
.btn-warning{background:#f59e0b;border-color:#f59e0b;color:#fff}
.btn-success{background:#0f766e;border-color:#0f766e;color:#fff}
.btn-sm{padding:5px 10px;font-size:12px}
.search{max-width:300px;margin-bottom:12px}
table{width:100%;border-collapse:collapse;min-width:600px;font-size:13px}
.tbl-wrap{overflow-x:auto}
th,td{padding:10px 12px;border-bottom:1px solid #e2e8f0;text-align:left}
th{font-size:11px;color:#64748b;font-weight:600;background:#f8fafc}
.badge{display:inline-block;padding:3px 8px;border-radius:20px;background:#dbeafe;color:#2563eb;font-size:11px;margin:2px}
.act{display:flex;gap:6px}
.empty{text-align:center;padding:24px;color:#94a3b8;font-size:13px}
.toast-c{position:fixed;top:16px;right:16px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none}
.toast{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;background:#fff;border:1px solid #e2e8f0;border-left:3px solid #2563eb;min-width:240px;font-size:13px;pointer-events:all;box-shadow:0 4px 16px rgba(0,0,0,.08);animation:sIn .25s ease}
.toast.s{border-left-color:#22c55e}.toast.e{border-left-color:#ef4444}.toast.w{border-left-color:#f59e0b}
.toast-t{font-weight:600;font-size:13px}.toast-m{font-size:12px;color:#64748b}
.toast-x{border:none;background:none;cursor:pointer;color:#94a3b8;font-size:18px;margin-left:auto;padding:0;line-height:1}
.toast.out{animation:sOut .25s ease forwards}
@keyframes sIn{from{transform:translateX(110%);opacity:0}to{transform:translateX(0);opacity:1}}
@keyframes sOut{from{transform:translateX(0);opacity:1}to{transform:translateX(110%);opacity:0}}
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.35);display:none;align-items:center;justify-content:center;padding:20px;z-index:999}
.modal-box{background:#fff;border-radius:10px;border:1px solid #e2e8f0;width:100%;max-width:480px;overflow:hidden}
.modal-head{padding:12px 16px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center}
.modal-head h3{font-size:15px;font-weight:600}
.modal-close{border:none;background:none;font-size:22px;cursor:pointer;color:#64748b;line-height:1}
.modal-body{padding:16px;max-height:70vh;overflow-y:auto}
.dtbl{width:100%;font-size:13px}
.dtbl td{padding:7px 0;border-bottom:1px solid #f1f5f9;vertical-align:top}
.dtbl td:first-child{width:140px;color:#64748b;font-weight:500}
</style>
</head>
<body>

<div class="wrap">
  <h1>Data Pegawai</h1>
  <p class="sub">Kelola data pegawai dan jabatan.</p>

  <!-- FORM -->
  <div class="card">
    <div class="card-head"><h2 id="formTitle">Tambah Pegawai</h2></div>
    <div class="card-body">
      <input type="hidden" id="id_guru">
      <div class="grid">
        <div class="fg">
          <label>Nama Pegawai</label>
          <input id="nama_guru" placeholder="Nama lengkap">
          <span class="ferr" id="e_nama"></span>
        </div>
        <div class="fg">
          <label>NIP <em style="font-style:normal;font-size:11px;color:#94a3b8">(opsional)</em></label>
          <input id="nip" placeholder="Nomor induk pegawai">
        </div>
        <div class="fg">
          <label>Jenis Kelamin</label>
          <select id="jenis_kelamin">
            <option value="">Pilih</option>
            <option value="Laki-laki">Laki-Laki</option>
            <option value="Perempuan">Perempuan</option>
          </select>
          <span class="ferr" id="e_jk"></span>
        </div>
        <div class="fg">
          <label>Tempat Lahir</label>
          <input id="tempat_lahir">
        </div>
        <div class="fg">
          <label>Tanggal Lahir</label>
          <input type="date" id="tanggal_lahir">
        </div>
        <div class="fg">
          <label>Alamat <em style="font-style:normal;font-size:11px;color:#94a3b8">(opsional)</em></label>
          <input id="alamat">
        </div>
        <div class="fg">
          <label>No Telepon <em style="font-style:normal;font-size:11px;color:#94a3b8">(opsional)</em></label>
          <input id="no_telepon">
        </div>
        <div class="fg">
          <label>Email</label>
          <input id="email" placeholder="contoh@email.com">
          <span class="ferr" id="e_email"></span>
        </div>
        <div class="fg">
          <label>Golongan <em style="font-style:normal;font-size:11px;color:#94a3b8">(opsional)</em></label>
          <select id="golongan">
            <option value="">Pilih Golongan</option>
            <optgroup label="Golongan I (Juru)">
              <option value="Ia">Ia — Juru Muda</option>
              <option value="Ib">Ib — Juru Muda Tingkat I</option>
              <option value="Ic">Ic — Juru</option>
              <option value="Id">Id — Juru Tingkat I</option>
            </optgroup>
            <optgroup label="Golongan II (Pengatur)">
              <option value="IIa">IIa — Pengatur Muda</option>
              <option value="IIb">IIb — Pengatur Muda Tingkat I</option>
              <option value="IIc">IIc — Pengatur</option>
              <option value="IId">IId — Pengatur Tingkat I</option>
            </optgroup>
            <optgroup label="Golongan III (Penata)">
              <option value="IIIa">IIIa — Penata Muda</option>
              <option value="IIIb">IIIb — Penata Muda Tingkat I</option>
              <option value="IIIc">IIIc — Penata</option>
              <option value="IIId">IIId — Penata Tingkat I</option>
            </optgroup>
            <optgroup label="Golongan IV (Pembina)">
              <option value="IVa">IVa — Pembina</option>
              <option value="IVb">IVb — Pembina Tingkat I</option>
              <option value="IVc">IVc — Pembina Utama Muda</option>
              <option value="IVd">IVd — Pembina Utama Madya</option>
              <option value="IVe">IVe — Pembina Utama</option>
            </optgroup>
          </select>
        </div>
        <div class="fg">
          <label>Pendidikan</label>
          <select id="pendidikan_tertinggi">
            <option value="">Pilih</option>
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
        <div class="fg">
          <label>Status Kepegawaian</label>
          <select id="status_kepegawaian">
            <option value="">Pilih</option>
            <option value="PNS">PNS</option>
            <option value="PPPK">PPPK</option>
            <option value="Honorer">Honorer</option>
            <option value="Kontrak">Kontrak</option>
            <option value="Magang">Magang</option>
          </select>
        </div>
        <div class="fg">
          <label>Tanggal Masuk</label>
          <input type="date" id="tanggal_masuk">
        </div>
        <div class="fg">
          <label>Username</label>
          <input id="username">
        </div>
        <div class="fg">
          <label>Password <em style="font-style:normal;font-size:11px;color:#94a3b8">(kosongkan jika tidak diubah)</em></label>
          <input type="password" id="password">
          <span class="ferr" id="e_pw"></span>
        </div>
      </div>

      <div style="margin-top:14px">
        <label style="font-size:12px;color:#64748b;font-weight:500">Jabatan</label>
        <div class="jab-wrap" id="jabatanWrap"><span style="font-size:12px;color:#94a3b8">Memuat jabatan...</span></div>
      </div>

      <div class="btn-row">
        <button class="btn-primary" id="btnSimpan">Simpan</button>
        <button id="btnReset">Reset</button>
      </div>
    </div>
  </div>

  <!-- TABLE -->
  <div class="card">
    <div class="card-head"><h2>Daftar Pegawai</h2></div>
    <div class="card-body">
      <input class="search" id="search" placeholder="Cari nama / NIP / email...">
      <div class="tbl-wrap">
        <table>
          <thead>
            <tr>
              <th>Nama</th><th>NIP</th><th>JK</th><th>Email</th><th>Jabatan</th><th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tbody">
            <tr><td colspan="6" class="empty">Memuat data...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- MODAL DETAIL -->
<div class="modal-bg" id="modal">
  <div class="modal-box">
    <div class="modal-head">
      <h3>Detail Pegawai</h3>
      <button class="modal-close" id="btnTutup">&times;</button>
    </div>
    <div class="modal-body">
      <table class="dtbl" id="detailTbl"></table>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast-c" id="toastC"></div>

<script>
let allData = [];

/* ---- TOAST ---- */
function toast(type, title, msg, dur = 3200) {
  const c = document.getElementById('toastC');
  const t = document.createElement('div');
  t.className = 'toast ' + ({ success: 's', error: 'e', warning: 'w' }[type] || '');
  t.innerHTML = `<div><div class="toast-t">${title}</div>${msg ? `<div class="toast-m">${msg}</div>` : ''}</div>
    <button class="toast-x" onclick="rmToast(this.parentElement)">×</button>`;
  c.appendChild(t);
  setTimeout(() => rmToast(t), dur);
}
function rmToast(el) {
  if (!el || el.classList.contains('out')) return;
  el.classList.add('out');
  setTimeout(() => el.remove(), 250);
}

/* ---- VALIDASI ---- */
function clearErr() {
  document.querySelectorAll('.ferr').forEach(e => e.textContent = '');
  document.querySelectorAll('.err').forEach(e => e.classList.remove('err'));
}
function setErr(fid, eid, msg) {
  document.getElementById(fid)?.classList.add('err');
  const e = document.getElementById(eid);
  if (e) e.textContent = msg;
}
function validate() {
  clearErr();
  let ok = true;
  const id  = document.getElementById('id_guru').value;
  const nm  = document.getElementById('nama_guru').value.trim();
  const jk  = document.getElementById('jenis_kelamin').value;
  const em  = document.getElementById('email').value.trim();
  const pw  = document.getElementById('password').value;

  if (!nm) { setErr('nama_guru', 'e_nama', 'Nama wajib diisi'); ok = false; }
  if (!jk) { setErr('jenis_kelamin', 'e_jk', 'Pilih jenis kelamin'); ok = false; }
  if (!em) {
    setErr('email', 'e_email', 'Email wajib diisi'); ok = false;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)) {
    setErr('email', 'e_email', 'Format email tidak valid'); ok = false;
  } else if (allData.some(p => (p.email || '').toLowerCase() === em.toLowerCase() && p.id_guru != id)) {
    setErr('email', 'e_email', 'Email sudah digunakan'); ok = false;
  }
  if (!id && !pw) { setErr('password', 'e_pw', 'Password wajib diisi untuk pegawai baru'); ok = false; }
  if (!ok) toast('error', 'Validasi gagal', 'Periksa kembali isian form');
  return ok;
}

/* ---- JABATAN ---- */
function loadJabatan() {
  fetch('/api/jabatan')
    .then(r => r.json())
    .then(r => {
      const wrap = document.getElementById('jabatanWrap');
      wrap.innerHTML = '';
      (r.data || []).forEach(j => {
        const el = document.createElement('div');
        el.className = 'jab-item';
        el.textContent = j.nama_jabatan;
        el.dataset.id = j.id_jabatan;
        el.onclick = () => el.classList.toggle('active');
        wrap.appendChild(el);
      });
    })
    .catch(() => {
      document.getElementById('jabatanWrap').innerHTML =
        '<span style="font-size:12px;color:#ef4444">Gagal memuat jabatan</span>';
    });
}
function getJabatan() {
  return [...document.querySelectorAll('.jab-item.active')].map(e => parseInt(e.dataset.id));
}
function setJabatan(ids) {
  document.querySelectorAll('.jab-item').forEach(el => {
    el.classList.toggle('active', (ids || []).some(j => j.id_jabatan == el.dataset.id));
  });
}

/* ---- LOAD DATA ---- */
function loadData() {
  fetch('/api/pegawai')
    .then(r => r.json())
    .then(r => { allData = r.data || []; renderTable(); })
    .catch(() => toast('error', 'Gagal memuat data', ''));
}

/* ---- RENDER TABEL ---- */
function renderTable() {
  const key = document.getElementById('search').value.toLowerCase();
  const filtered = allData.filter(p =>
    (p.nama_guru || '').toLowerCase().includes(key) ||
    (p.nip || '').toLowerCase().includes(key) ||
    (p.email || '').toLowerCase().includes(key)
  );
  const tb = document.getElementById('tbody');
  if (!filtered.length) {
    tb.innerHTML = `<tr><td colspan="6" class="empty">Tidak ada data</td></tr>`;
    return;
  }
  tb.innerHTML = filtered.map(p => {
    const jab = (p.jabatan || []).map(j => `<span class="badge">${j.nama_jabatan}</span>`).join('') || '-';
    return `<tr>
      <td>${p.nama_guru ?? '-'}</td>
      <td>${p.nip ?? '-'}</td>
      <td>${p.jenis_kelamin ?? '-'}</td>
      <td>${p.email ?? '-'}</td>
      <td>${jab}</td>
      <td><div class="act">
        <button class="btn-success btn-sm" onclick="showDetail(${p.id_guru})">Detail</button>
        <button class="btn-warning btn-sm" onclick="editData(${p.id_guru})">Edit</button>
        <button class="btn-danger btn-sm" onclick="hapus(${p.id_guru})">Hapus</button>
      </div></td>
    </tr>`;
  }).join('');
}

/* ---- DETAIL ---- */
function showDetail(id) {
  const p = allData.find(x => x.id_guru == id);
  if (!p) return;
  const jab = (p.jabatan || []).map(j => `<span class="badge">${j.nama_jabatan}</span>`).join('') || '-';
  const fields = [
    ['Username', p.username], ['Nama', p.nama_guru], ['NIP', p.nip],
    ['Jenis Kelamin', p.jenis_kelamin], ['Tempat Lahir', p.tempat_lahir],
    ['Tanggal Lahir', (p.tanggal_lahir || '').split('T')[0] || '-'],
    ['Alamat', p.alamat], ['No Telepon', p.no_telepon], ['Email', p.email],
    ['Golongan', p.golongan], ['Pendidikan', p.pendidikan_tertinggi],
    ['Status', p.status_kepegawaian],
    ['Tanggal Masuk', (p.tanggal_masuk || '').split('T')[0] || '-']
  ];
  document.getElementById('detailTbl').innerHTML =
    fields.map(([k, v]) => `<tr><td>${k}</td><td>${v ?? '-'}</td></tr>`).join('') +
    `<tr><td>Jabatan</td><td>${jab}</td></tr>`;
  document.getElementById('modal').style.display = 'flex';
}

/* ---- EDIT ---- */
function editData(id) {
  const p = allData.find(x => x.id_guru == id);
  if (!p) return;
  const g = n => document.getElementById(n);
  g('id_guru').value = p.id_guru;
  g('nama_guru').value = p.nama_guru ?? '';
  g('nip').value = p.nip ?? '';
  g('jenis_kelamin').value = p.jenis_kelamin ?? '';
  g('tempat_lahir').value = p.tempat_lahir ?? '';
  g('tanggal_lahir').value = (p.tanggal_lahir || '').split('T')[0];
  g('alamat').value = p.alamat ?? '';
  g('no_telepon').value = p.no_telepon ?? '';
  g('email').value = p.email ?? '';
  g('golongan').value = p.golongan ?? '';
  g('pendidikan_tertinggi').value = p.pendidikan_tertinggi ?? '';
  g('status_kepegawaian').value = p.status_kepegawaian ?? '';
  g('tanggal_masuk').value = (p.tanggal_masuk || '').split('T')[0];
  g('username').value = p.username ?? '';
  g('password').value = '';
  setJabatan(p.jabatan || []);
  clearErr();
  document.getElementById('formTitle').textContent = 'Edit Pegawai';
  window.scrollTo({ top: 0, behavior: 'smooth' });
  toast('', 'Mode Edit', `Mengedit "${p.nama_guru}"`);
}

/* ---- RESET ---- */
function resetForm() {
  document.querySelectorAll('input').forEach(i => i.value = '');
  ['jenis_kelamin', 'golongan', 'pendidikan_tertinggi', 'status_kepegawaian']
    .forEach(id => document.getElementById(id).value = '');
  document.querySelectorAll('.jab-item').forEach(e => e.classList.remove('active'));
  document.getElementById('id_guru').value = '';
  document.getElementById('formTitle').textContent = 'Tambah Pegawai';
  clearErr();
}

/* ---- SIMPAN ---- */
function simpan() {
  if (!validate()) return;
  const id = document.getElementById('id_guru').value;
  const isEdit = !!id;
  const g = n => document.getElementById(n).value;
  const payload = {
    _method: isEdit ? 'PUT' : 'POST',
    nama_guru: g('nama_guru').trim(),
    nip: g('nip').trim(),
    jenis_kelamin: g('jenis_kelamin'),
    tempat_lahir: g('tempat_lahir').trim(),
    tanggal_lahir: g('tanggal_lahir'),
    alamat: g('alamat').trim(),
    no_telepon: g('no_telepon').trim(),
    email: g('email').trim(),
    golongan: g('golongan'),
    pendidikan_tertinggi: g('pendidikan_tertinggi'),
    status_kepegawaian: g('status_kepegawaian'),
    tanggal_masuk: g('tanggal_masuk'),
    username: g('username'),
    jabatan: getJabatan()
  };
  if (g('password')) payload.password = g('password');

  const btn = document.getElementById('btnSimpan');
  btn.disabled = true;
  btn.textContent = 'Menyimpan...';

  fetch('/api/pegawai' + (id ? '/' + id : ''), {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(r => r.json())
  .then(r => {
    if (r.success) {
      toast('success',
        isEdit ? 'Data diperbarui' : 'Data ditambahkan',
        `"${payload.nama_guru}" berhasil ${isEdit ? 'diperbarui' : 'ditambahkan'}`
      );
      resetForm();
      loadData();
    } else {
      if (r.errors?.email) setErr('email', 'e_email', r.errors.email[0]);
      toast('error', 'Gagal menyimpan', r.message || 'Terjadi kesalahan');
    }
  })
  .catch(() => toast('error', 'Kesalahan jaringan', 'Tidak dapat terhubung ke server'))
  .finally(() => { btn.disabled = false; btn.textContent = 'Simpan'; });
}

/* ---- HAPUS ---- */
function hapus(id) {
  const p = allData.find(x => x.id_guru == id);
  if (!confirm(`Hapus pegawai "${p?.nama_guru ?? ''}"?`)) return;
  fetch('/api/pegawai/' + id, { method: 'DELETE' })
    .then(r => r.json())
    .then(r => {
      if (r.success) {
        toast('success', 'Data dihapus', `"${p?.nama_guru}" berhasil dihapus`);
        loadData();
      } else {
        toast('warning', 'Tidak bisa dihapus', r.message || 'Data masih digunakan');
      }
    })
    .catch(() => toast('error', 'Gagal menghapus', ''));
}

/* ---- EVENT ---- */
document.getElementById('btnSimpan').onclick = simpan;
document.getElementById('btnReset').onclick = resetForm;
document.getElementById('search').oninput = renderTable;
document.getElementById('btnTutup').onclick = () => document.getElementById('modal').style.display = 'none';
document.getElementById('modal').onclick = function(e) { if (e.target === this) this.style.display = 'none'; };

/* ---- INIT ---- */
loadJabatan();
loadData();
</script>
</body>
</html>