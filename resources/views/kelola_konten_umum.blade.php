@extends('layouts.app')

@section('title', 'Konten Umum Sekolah')

@section('content')

<style>
:root {
    --primary: #185FA5;
    --primary-50: #E6F1FB;
    --primary-100: #B5D4F4;
    --primary-800: #0C447C;
    --success-bg: #EAF3DE;
    --success-text: #27500A;
    --danger-bg: #FCEBEB;
    --danger-text: #791F1F;
}

* { box-sizing: border-box; }

.ku-wrapper {
    max-width: 960px;
    margin: 0 auto;
    padding: 32px 20px 60px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* PAGE HEADER */
.ku-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 36px;
}

.ku-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.ku-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: var(--primary-50);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ku-icon-box svg {
    width: 24px;
    height: 24px;
    stroke: var(--primary);
    fill: none;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.ku-title {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 4px;
}

.ku-subtitle {
    font-size: 13px;
    color: #6B7280;
    margin: 0;
}

/* SECTION CARDS */
.ku-section {
    background: #fff;
    border: 0.5px solid #E5E7EB;
    border-radius: 18px;
    padding: 28px;
    margin-bottom: 20px;
}

.ku-section-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #9CA3AF;
    margin: 0 0 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ku-section-label::after {
    content: '';
    flex: 1;
    height: 0.5px;
    background: #E5E7EB;
}

/* GRID */
.ku-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 18px;
}

.ku-grid-full {
    grid-column: 1 / -1;
}

/* FORM ELEMENTS */
.ku-field {
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.ku-label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

.ku-input,
.ku-textarea {
    width: 100%;
    padding: 10px 13px;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 14px;
    color: #111827;
    background: #FAFAFA;
    transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
    outline: none;
    font-family: inherit;
}

.ku-input:focus,
.ku-textarea:focus {
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(24, 95, 165, 0.1);
}

.ku-textarea {
    resize: vertical;
    min-height: 100px;
    line-height: 1.6;
}

/* FILE INPUT */
.ku-file-wrapper {
    border: 1px dashed #D1D5DB;
    border-radius: 12px;
    padding: 16px;
    background: #FAFAFA;
    transition: border-color 0.15s;
}

.ku-file-wrapper:hover {
    border-color: var(--primary-100);
}

.ku-file-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.ku-file-title {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

.ku-file-input {
    width: 100%;
    font-size: 13px;
    color: #6B7280;
}

.ku-preview {
    width: 100%;
    height: 180px;
    border-radius: 10px;
    overflow: hidden;
    background: #F3F4F6;
    border: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 12px;
}

.ku-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.ku-preview-empty {
    font-size: 13px;
    color: #9CA3AF;
    text-align: center;
}

/* FOOTER */
.ku-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 28px;
    padding-top: 24px;
    border-top: 0.5px solid #E5E7EB;
}

/* ALERTS */
.ku-alert {
    display: none;
    font-size: 13px;
    padding: 10px 14px;
    border-radius: 10px;
    align-items: center;
    gap: 8px;
}

.ku-alert.show { display: flex; }

.ku-alert-success {
    background: var(--success-bg);
    color: var(--success-text);
}

.ku-alert-danger {
    background: var(--danger-bg);
    color: var(--danger-text);
}

/* BUTTON */
.ku-btn-save {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 24px;
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.15s, transform 0.1s;
    font-family: inherit;
}

.ku-btn-save:hover { background: var(--primary-800); }
.ku-btn-save:active { transform: scale(0.98); }

.ku-btn-save svg {
    width: 16px;
    height: 16px;
    stroke: #fff;
    fill: none;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

/* SPINNER */
.ku-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
    display: none;
}

@keyframes spin { to { transform: rotate(360deg); } }

.ku-btn-save.loading .ku-spinner { display: block; }
.ku-btn-save.loading .ku-btn-icon { display: none; }

@media (max-width: 600px) {
    .ku-section { padding: 20px; }
    .ku-title { font-size: 18px; }
}
</style>

<div class="ku-wrapper">

    <!-- HEADER -->
    <div class="ku-header">
        <div class="ku-header-left">
            <div class="ku-icon-box">
                <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div>
                <h2 class="ku-title">Konten Umum Sekolah</h2>
                <p class="ku-subtitle">Kelola informasi utama yang tampil di halaman login &amp; landing page</p>
            </div>
        </div>
    </div>

    <form id="formKonten" enctype="multipart/form-data">
        <input type="hidden" id="id_konten">

        <!-- VISI & MISI -->
        <div class="ku-section">
            <div class="ku-section-label">Visi &amp; Misi</div>
            <div class="ku-grid">
                <div class="ku-field ku-grid-full">
                    <label class="ku-label" for="visi">Visi Sekolah</label>
                    <textarea class="ku-textarea" id="visi" name="visi" placeholder="Masukkan visi sekolah..."></textarea>
                </div>
                <div class="ku-field ku-grid-full">
                    <label class="ku-label" for="misi">Misi Sekolah</label>
                    <textarea class="ku-textarea" id="misi" name="misi" placeholder="Masukkan misi sekolah..."></textarea>
                </div>
            </div>
        </div>

        <!-- INFORMASI KONTAK -->
        <div class="ku-section">
            <div class="ku-section-label">Informasi Kontak</div>
            <div class="ku-grid">
                <div class="ku-field">
                    <label class="ku-label" for="telepon">Telepon</label>
                    <input class="ku-input" type="text" id="telepon" name="telepon" placeholder="08xxxxxxxxxx">
                </div>
                <div class="ku-field">
                    <label class="ku-label" for="email">Email</label>
                    <input class="ku-input" type="email" id="email" name="email" placeholder="email@sekolah.sch.id">
                </div>
                <div class="ku-field">
                    <label class="ku-label" for="jam_operasional">Jam Operasional</label>
                    <input class="ku-input" type="text" id="jam_operasional" name="jam_operasional" placeholder="07.00 – 15.00">
                </div>
                <div class="ku-field">
                    <label class="ku-label" for="akreditasi">Akreditasi</label>
                    <input class="ku-input" type="text" id="akreditasi" name="akreditasi" placeholder="Contoh: A">
                </div>
                <div class="ku-field">
                    <label class="ku-label" for="total_guru">Total Guru</label>
                    <input class="ku-input" type="number" id="total_guru" name="total_guru" placeholder="0">
                </div>
                <div class="ku-field">
                    <label class="ku-label" for="total_siswa">Total Siswa</label>
                    <input class="ku-input" type="number" id="total_siswa" name="total_siswa" placeholder="0">
                </div>
                <div class="ku-field ku-grid-full">
                    <label class="ku-label" for="alamat">Alamat Sekolah</label>
                    <textarea class="ku-textarea" id="alamat" name="alamat" placeholder="Masukkan alamat sekolah..."></textarea>
                </div>
            </div>
        </div>

        <!-- GAMBAR -->
        <div class="ku-section">
            <div class="ku-section-label">Gambar</div>
            <div class="ku-grid">

                <!-- LOGIN -->
                <div class="ku-file-wrapper">
                    <div class="ku-file-header">
                        <span class="ku-file-title">Gambar Login</span>
                    </div>
                    <input class="ku-file-input" type="file" id="gambar_login" name="gambar_login" accept="image/*">
                    <div class="ku-preview" id="boxLogin">
                        <img id="previewImage" src="" style="display:none;" alt="Preview gambar login">
                        <div class="ku-preview-empty" id="emptyLogin">Belum ada gambar</div>
                    </div>
                </div>

                <!-- BERANDA -->
                <div class="ku-file-wrapper">
                    <div class="ku-file-header">
                        <span class="ku-file-title">Gambar Beranda</span>
                    </div>
                    <input class="ku-file-input" type="file" id="gambar_beranda" name="gambar_beranda" accept="image/*">
                    <div class="ku-preview" id="boxBeranda">
                        <img id="previewBeranda" src="" style="display:none;" alt="Preview gambar beranda">
                        <div class="ku-preview-empty" id="emptyBeranda">Belum ada gambar</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- FOOTER -->
        <div class="ku-footer">
            <div>
                <div class="ku-alert ku-alert-success" id="alertSuccess">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span id="alertSuccessText"></span>
                </div>
                <div class="ku-alert ku-alert-danger" id="alertError">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span id="alertErrorText"></span>
                </div>
            </div>
            <button type="submit" class="ku-btn-save" id="btnSave">
                <svg class="ku-btn-icon" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                <div class="ku-spinner" id="spinner"></div>
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

@endsection

@section('script')
<script>
(function () {

    const $ = id => document.getElementById(id);

    const form          = $('formKonten');
    const btnSave       = $('btnSave');
    const alertSuccess  = $('alertSuccess');
    const alertError    = $('alertError');
    const successText   = $('alertSuccessText');
    const errorText     = $('alertErrorText');
    const previewImage  = $('previewImage');
    const previewBeranda= $('previewBeranda');
    const emptyLogin    = $('emptyLogin');
    const emptyBeranda  = $('emptyBeranda');

    let kontenId = null;

    /* ---- helpers ---- */
    function showAlert(type, msg) {
        if (type === 'success') {
            successText.textContent = msg;
            alertSuccess.classList.add('show');
            alertError.classList.remove('show');
        } else {
            errorText.textContent = msg;
            alertError.classList.add('show');
            alertSuccess.classList.remove('show');
        }
    }

    function setLoading(on) {
        btnSave.classList.toggle('loading', on);
        btnSave.disabled = on;
    }

    function setPreview(imgEl, emptyEl, src) {
        imgEl.src = src;
        imgEl.style.display = 'block';
        emptyEl.style.display = 'none';
    }

    /* ---- preview handlers ---- */
    $('gambar_login').addEventListener('change', function () {
        if (this.files[0]) setPreview(previewImage, emptyLogin, URL.createObjectURL(this.files[0]));
    });

    $('gambar_beranda').addEventListener('change', function () {
        if (this.files[0]) setPreview(previewBeranda, emptyBeranda, URL.createObjectURL(this.files[0]));
    });

    /* ---- safe fetch (fix SyntaxError: Unexpected token '<') ---- */
    async function apiFetch(url, options = {}) {
        const headers = Object.assign({ 'Accept': 'application/json' }, options.headers || {});
        const res = await fetch(url, Object.assign({}, options, { headers }));

        const contentType = res.headers.get('Content-Type') || '';
        if (!contentType.includes('application/json')) {
            throw new Error('Server tidak mengembalikan JSON. Status: ' + res.status);
        }

        const data = await res.json();
        return { res, data };
    }

    /* ---- load data ---- */
    async function loadData() {
        try {
            const { res, data } = await apiFetch('/api/konten-umum');

            if (!res.ok) {
                console.warn('Gagal memuat data:', data.message);
                return;
            }

            const d = data.data;
            if (!d) return;

            kontenId = d.id_konten;

            const fields = ['visi','misi','akreditasi','telepon','email',
                            'jam_operasional','total_guru','total_siswa','alamat'];
            fields.forEach(f => {
                const el = $(f);
                if (el) el.value = d[f] || '';
            });

            if (d.gambar_login)   setPreview(previewImage,   emptyLogin,   d.gambar_login);
            if (d.gambar_beranda) setPreview(previewBeranda, emptyBeranda, d.gambar_beranda);

        } catch (err) {
            console.error('loadData error:', err.message);
        }
    }

    /* ---- submit ---- */
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        setLoading(true);
        alertSuccess.classList.remove('show');
        alertError.classList.remove('show');

        const fd = new FormData();
        ['visi','misi','akreditasi','alamat','telepon','email',
         'jam_operasional','total_guru','total_siswa'].forEach(f => {
            fd.append(f, $(f).value);
        });

        const loginFile   = $('gambar_login').files[0];
        const berandaFile = $('gambar_beranda').files[0];
        if (loginFile)   fd.append('gambar_login',   loginFile);
        if (berandaFile) fd.append('gambar_beranda',  berandaFile);

        let url    = '/api/konten-umum';
        let method = 'POST';

        if (kontenId) {
            url = '/api/konten-umum/' + kontenId;
            fd.append('_method', 'PUT');
        }

        try {
            const { res, data } = await apiFetch(url, { method, body: fd });

            if (res.ok) {
                showAlert('success', data.message || 'Berhasil disimpan.');
                await loadData();
            } else {
                const msg = data.errors
                    ? Object.values(data.errors).flat().join(' ')
                    : (data.message || 'Terjadi kesalahan.');
                showAlert('error', msg);
            }

        } catch (err) {
            showAlert('error', err.message || 'Gagal terhubung ke server.');
        } finally {
            setLoading(false);
        }
    });

    loadData();

})();
</script>
@endsection