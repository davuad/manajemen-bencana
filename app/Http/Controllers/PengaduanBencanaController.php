<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\FotoPengaduan;
use App\Models\KategoriBencana;
use App\Models\KebutuhanPengaduan;
use App\Models\PengaduanBencana;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;

class PengaduanBencanaController extends Controller
{
    public function boot()
    {
        View::composer('*', function ($view) {

            $user = Auth::user();

            $view->with('user', $user);
        });
    }

    public function foto()
    {
        return $this->hasMany(
            FotoPengaduan::class,
            'pengaduan_bencana_id',
            'id'
        );
    }

    public function index(Request $request)
    {
        $query = PengaduanBencana::with([
            'user',
            'kategori',
            'foto',
            'kebutuhan'
        ]);
        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('desa', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qUser) use ($search) {
                        $qUser->where('nama', 'like', "%{$search}%");
                    });
            });
        }
        // FILTER KATEGORI
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status_pengaduan', $request->status);
        }
        // AMBIL DATA
        $data = $query->orderBy('created_at', 'desc')->get();
        // TAMPILKAN VIEW
        return view(
            'pengaduan_bencana.index',
            compact('data')
        );
    }

    // form tambah
    public function create()
    {
        $kategori = KategoriBencana::all();
        $user = User::all();
        $desa = Desa::all();

        return view('pengaduan_bencana.create', compact(
            'kategori',
            'user',
            'desa'
        ));
    }

public function store(Request $request)
{
    $request->validate([
        'user_id'      => 'required|exists:user,id',
        'kategori_id'  => 'required|exists:kategori_bencana,id',
        'desa_id'      => 'required|exists:desa,id',
        'deskripsi'    => 'required|string',

        'lampiran.*'   => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    DB::beginTransaction();

    try {

        // Ambil nama desa berdasarkan ID
        $desa = Desa::findOrFail($request->desa_id);

        // Simpan pengaduan
        $pengaduan = PengaduanBencana::create([
            'user_id'            => $request->user_id,
            'kategori_id'        => $request->kategori_id,
            'desa'               => $desa->nama_desa,
            'deskripsi'          => $request->deskripsi,
            'status_pengaduan'   => 'BELUM_DITANGANI',
        ]);

        // Upload lampiran
        if ($request->hasFile('lampiran')) {

            foreach ($request->file('lampiran') as $file) {

                $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('lampiran_pengaduan'), $namaFile);

                FotoPengaduan::create([
                    'pengaduan_bencana_id' => $pengaduan->id,
                    'file_foto'            => $namaFile,
                    'keterangan'           => $request->keterangan_foto,
                ]);
            }
        }

        // Simpan kebutuhan jika ada
        if ($request->filled('kebutuhan')) {

            KebutuhanPengaduan::create([
                'pengaduan_bencana_id' => $pengaduan->id,
                'kebutuhan'            => $request->kebutuhan,
            ]);
        }

        DB::commit();

        return redirect()
            ->route('admin.pengaduan_bencana.index')
            ->with('success', 'Pengaduan berhasil ditambahkan.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}
public function update(Request $request, $id)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'kategori_id' => 'required|exists:kategori_bencana,id',
        'desa_id' => 'required|exists:desa,id',
        'deskripsi' => 'required',

        'lampiran.*' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    DB::beginTransaction();

    try {

        $pengaduan = PengaduanBencana::findOrFail($id);

        $tanggal_selesai = $request->status_pengaduan == 'SELESAI'
            ? $request->tanggal_selesai
            : null;

        $pengaduan->update([
            'user_id' => $request->user_id,
            'kategori_id' => $request->kategori_id,
            'desa_id' => $request->desa_id,
            'deskripsi' => $request->deskripsi,
            'status_pengaduan' => $request->status_pengaduan,
            'tanggal_selesai' => $tanggal_selesai,
        ]);

        if ($request->hasFile('lampiran')) {

            foreach ($request->file('lampiran') as $file) {

                $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(
                    public_path('lampiran_pengaduan'),
                    $namaFile
                );

                FotoPengaduan::create([
                    'pengaduan_bencana_id' => $pengaduan->id,
                    'file_foto' => $namaFile,
                    'keterangan' => $request->keterangan_foto,
                ]);
            }
        }

        KebutuhanPengaduan::updateOrCreate(
            [
                'pengaduan_bencana_id' => $pengaduan->id
            ],
            [
                'dapur_umum' => $request->dapur_umum ?? 'Tidak',
                'psikososial' => $request->psikososial ?? 'Tidak',
                'logistik_rentan' => $request->logistik_rentan ?? 'Tidak',
                'logistik_makanan' => $request->logistik_makanan ?? 'Tidak',
                'logistik_penampungan' => $request->logistik_penampungan ?? 'Tidak',
                'keterangan' => $request->keterangan_kebutuhan
            ]
        );

        DB::commit();

        return redirect()
            ->route('admin.pengaduan.index')
            ->with('success', 'Data berhasil diperbarui.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}
public function show($id)
{
    $data = PengaduanBencana::with([
        'foto',
        'kebutuhan'
    ])->findOrFail($id);

    $kategori = KategoriBencana::all();
    $user = User::all();
    $desa = Desa::all();

    return view('pengaduan_bencana.edit', compact(
        'data',
        'kategori',
        'user',
        'desa'
    ));
}

    // detail kebutuhan
    public function detailKebutuhan($id)
    {
        $data = KebutuhanPengaduan::with('pengaduan')->findOrFail($id);
        return view('pengaduan_bencana.detail_kebutuhan', compact('data'));
    }

    //detail foto
    public function detailFoto($id)
    {
        $pengaduan = PengaduanBencana::with([
            'foto',
            'kategori',
            'user'
        ])->findOrFail($id);

        return view('pengaduan_bencana.detail_foto', compact('pengaduan'));
    }

    public function hapusFoto($id)
    {
        $foto = FotoPengaduan::findOrFail($id);

        // hapus file dari folder
        $path = public_path('foto/' . $foto->file_foto);
        if (file_exists($path)) {
            unlink($path);
        }

        $pengaduanId = $foto->pengaduan_bencana_id;

        $foto->delete();

        return redirect('/admin/pengaduan')
            ->with('success', 'Foto berhasil dihapus');
    }

    public function destroy($id)
    {
        $pengaduan = PengaduanBencana::findOrFail($id);
        $fotos = FotoPengaduan::where('pengaduan_bencana_id', $id)->get();
        foreach ($fotos as $foto) {
            $filePath = public_path('foto/' . $foto->file_foto);
            if (file_exists($filePath) && !is_dir($filePath)) {
                unlink($filePath);
            }
        }
        FotoPengaduan::where('pengaduan_bencana_id', $id)->delete();
        KebutuhanPengaduan::where('pengaduan_bencana_id', $id)->delete();
        $pengaduan->delete();
        return redirect('/admin/pengaduan')->with('success', 'Data pengaduan beserta seluruh lampiran fotonya berhasil dihapus secara permanen.');
    }

    // =====================================
    // KABID - DATA PENGADUAN
    // =====================================

    public function kabidIndex(Request $request)
    {
        $query = PengaduanBencana::with([
            'user',
            'kategori',
            'foto',
            'kebutuhan'
        ]);

        // ===========================
        // PENCARIAN
        // ===========================
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('desa', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {

                        $u->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // ===========================
        // FILTER STATUS
        // ===========================
        if ($request->filled('status')) {

            $query->where(
                'status_pengaduan',
                $request->status
            );
        }

        // ===========================
        // FILTER BULAN
        // ===========================
        if ($request->filled('bulan')) {

            $query->whereMonth(
                'created_at',
                $request->bulan
            );
        }

        // ===========================
        // FILTER TAHUN
        // ===========================
        if ($request->filled('tahun')) {

            $query->whereYear(
                'created_at',
                $request->tahun
            );
        }

        $data = $query->latest()->get();

        /*
    |--------------------------------------------------------------------------
    | Statistik
    |--------------------------------------------------------------------------
    */

        $statistik = PengaduanBencana::query();

        if ($request->filled('bulan')) {

            $statistik->whereMonth(
                'created_at',
                $request->bulan
            );
        }

        if ($request->filled('tahun')) {

            $statistik->whereYear(
                'created_at',
                $request->tahun
            );
        }

        $totalPengaduan = (clone $statistik)->count();

        $totalBelum = (clone $statistik)
            ->where('status_pengaduan', 'BELUM_DITANGANI')
            ->count();

        $totalDitangani = (clone $statistik)
            ->where('status_pengaduan', 'DITANGANI')
            ->count();

        $totalDitolak = (clone $statistik)
            ->where('status_pengaduan', 'TIDAK_DIREKOMENDASIKAN')
            ->count();

        return view(
            'pengaduan_bencana.kabid.index',
            compact(
                'data',
                'totalPengaduan',
                'totalBelum',
                'totalDitangani',
                'totalDitolak'
            )
        );
    }
    // =====================================
    // DETAIL PENGADUAN
    // =====================================

    public function kabidDetail($id)
    {
        $data = PengaduanBencana::with([
            'user',
            'kategori',
            'foto',
            'kebutuhan'
        ])->findOrFail($id);

        return view(
            'pengaduan_bencana.kabid.detail',
            compact('data')
        );
    }

    // =====================================
    // FORM VERIFIKASI
    // =====================================

    public function verifikasi($id)
    {
        $data = PengaduanBencana::with([
            'user',
            'kategori',
            'foto',
            'kebutuhan'
        ])->findOrFail($id);

        return view(
            'pengaduan_bencana.kabid.verifikasi',
            compact('data')
        );
    }

    // =====================================
    // SIMPAN VERIFIKASI
    // =====================================

    public function simpanVerifikasi(Request $request, $id)
    {
        $request->validate([
            'status_pengaduan' => 'required',
        ]);

        $pengaduan = PengaduanBencana::findOrFail($id);

        $pengaduan->update([
            'status_pengaduan' => $request->status_pengaduan,
            'keterangan_verifikasi' => $request->keterangan_verifikasi,
        ]);

        if ($pengaduan->kebutuhan) {

            $pengaduan->kebutuhan->update([
                'dapur_umum' => $request->dapur_umum ?? 'Tidak',
                'psikososial' => $request->psikososial ?? 'Tidak',
                'logistik_rentan' => $request->logistik_rentan ?? 'Tidak',
                'logistik_makanan' => $request->logistik_makanan ?? 'Tidak',
                'logistik_penampungan' => $request->logistik_penampungan ?? 'Tidak',
                'keterangan' => $request->keterangan_kebutuhan,
            ]);
        }

        return redirect()
            ->route('kabid.pengaduan.index')
            ->with('success', 'Verifikasi berhasil disimpan');
    }

    // =====================================
    // KETUA TIM - MONITORING
    // =====================================

    public function ketuaTimIndex(Request $request)
    {
        $query = PengaduanBencana::with([
            'user',
            'kategori',
            'foto',
            'kebutuhan'
        ]);

        // =====================
        // PENCARIAN
        // =====================
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('desa', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {

                        $u->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // =====================
        // FILTER STATUS
        // =====================
        if ($request->filled('status')) {

            $query->where(
                'status_pengaduan',
                $request->status
            );
        } else {

            $query->whereIn('status_pengaduan', [
                'DITANGANI',
                'SELESAI'
            ]);
        }

        // =====================
        // FILTER BULAN
        // =====================
        if ($request->filled('bulan')) {

            $query->whereMonth(
                'created_at',
                $request->bulan
            );
        }

        // =====================
        // FILTER TAHUN
        // =====================
        if ($request->filled('tahun')) {

            $query->whereYear(
                'created_at',
                $request->tahun
            );
        }

        // =====================
        // DATA TABEL
        // =====================
        $data = $query
            ->latest()
            ->get();

        // =====================
        // STATISTIK
        // =====================

        $statistik = PengaduanBencana::query();

        if ($request->filled('bulan')) {

            $statistik->whereMonth(
                'created_at',
                $request->bulan
            );
        }

        if ($request->filled('tahun')) {

            $statistik->whereYear(
                'created_at',
                $request->tahun
            );
        }

        $statistik->whereIn('status_pengaduan', [
            'DITANGANI',
            'SELESAI'
        ]);

        $totalPengaduan = (clone $statistik)->count();

        $totalSelesai = (clone $statistik)
            ->where('status_pengaduan', 'SELESAI')
            ->count();

        $totalBelum = (clone $statistik)
            ->where('status_pengaduan', 'DITANGANI')
            ->count();

        return view(
            'pengaduan_bencana.ketua_tim.index',
            compact(
                'data',
                'totalPengaduan',
                'totalSelesai',
                'totalBelum'
            )
        );
    }
    // =====================================
    // FORM SELESAI
    // =====================================

    public function formSelesai($id)
    {
        $data = PengaduanBencana::with([
            'user',
            'kategori',
            'foto',
            'kebutuhan'
        ])->findOrFail($id);

        return view(
            'pengaduan_bencana.ketua_tim.selesai',
            compact('data')
        );
    }

    // =====================================
    // SIMPAN PENYELESAIAN
    // =====================================

    public function simpanSelesai(Request $request, $id)
    {
        $request->validate([
            'tanggal_selesai' => 'required|date'
        ]);

        $pengaduan = PengaduanBencana::findOrFail($id);

        $pengaduan->update([
            'status_pengaduan' => 'SELESAI',
            'tanggal_selesai' => $request->tanggal_selesai
        ]);

        return redirect()
            ->route('ketua_tim.pengaduan.index')
            ->with(
                'success',
                'Pengaduan berhasil diselesaikan'
            );
    }
    // =====================================
    // USER (RELAWAN / KADUS / DESA)
    // =====================================

    public function userIndex(Request $request)
    {
        $query = PengaduanBencana::with([
            'user',
            'kategori',
            'foto',
            'kebutuhan'
        ])
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('desa', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {

            $query->where(
                'status_pengaduan',
                $request->status
            );
        }

        $data = $query->latest()->get();

        return view(
            'pengaduan_bencana.user.index',
            compact('data')
        );
    }

    // =====================================
    // FORM TAMBAH PENGADUAN USER
    // =====================================

    public function userCreate()
    {
        $kategori = KategoriBencana::all();
        $desa = Desa::orderBy('nama_desa')->get();

        return view(
            'pengaduan_bencana.user.create',
            compact('kategori','desa')
        );
    }

    // =====================================
    // SIMPAN PENGADUAN USER
    // =====================================

public function userStore(Request $request)
{
    $request->validate([
        'kategori_id' => 'required',
        'desa' => 'required',
        'deskripsi' => 'required',
        'foto.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120'
    ]);

    DB::beginTransaction();

    try {

        // Simpan pengaduan
        $pengaduan = PengaduanBencana::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'desa' => $request->desa,
            'deskripsi' => $request->deskripsi,
            'status_pengaduan' => 'BELUM_DITANGANI'
        ]);

        // Simpan kebutuhan default
        KebutuhanPengaduan::create([
            'pengaduan_bencana_id' => $pengaduan->id,
            'dapur_umum' => 'Tidak',
            'psikososial' => 'Tidak',
            'logistik_rentan' => 'Tidak',
            'logistik_makanan' => 'Tidak',
            'logistik_penampungan' => 'Tidak',
            'keterangan' => null
        ]);

        // Upload foto
        if ($request->hasFile('foto')) {

            foreach ($request->file('foto') as $file) {

                $nama = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                $file->move(
                    public_path('foto'),
                    $nama
                );

                FotoPengaduan::create([
                    'pengaduan_bencana_id' => $pengaduan->id,
                    'file_foto' => $nama,
                    'keterangan' => $request->keterangan
                ]);
            }
        }

        DB::commit();

        // ==========================
        // KIRIM WHATSAPP FONNTE
        // ==========================
        try {

            $pengaduan->load('user', 'kategori');

            $namaPelapor = $pengaduan->user->nama ?? $pengaduan->user->name ?? 'Pengguna';

            $kategori = $pengaduan->kategori->nama_kategori
                        ?? $pengaduan->kategori->nama
                        ?? '-';

            $pesan = "🚨 *BPBD Kabupaten Banyumas*\n";
            $pesan .= "Sistem Informasi Manajemen Bencana\n\n";

            $pesan .= "━━━━━━━━━━━━━━━━━━\n";
            $pesan .= "📢 *Pengaduan Baru*\n";
            $pesan .= "━━━━━━━━━━━━━━━━━━\n\n";

            $pesan .= "👤 Pelapor : {$namaPelapor}\n";
            $pesan .= "📂 Kategori : {$kategori}\n";
            $pesan .= "📍 Lokasi : {$pengaduan->desa}\n";
            $pesan .= "📝 Deskripsi : {$pengaduan->deskripsi}\n";
            $pesan .= "🕒 Waktu : " . now()->format('d-m-Y H:i') . " WIB\n\n";

            $pesan .= "Silakan login ke aplikasi untuk melakukan verifikasi.";

            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => env('FONNTE_TARGET'),
                'message' => $pesan,
            ]);

        } catch (\Exception $e) {

            \Log::error('Fonnte Error : ' . $e->getMessage());

        }

        return redirect()
            ->route('user.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dikirim.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    // =====================================
    // DETAIL PENGADUAN USER
    // =====================================

    public function showUser($id)
    {
        $data = PengaduanBencana::with([
            'user',
            'kategori',
            'foto',
            'kebutuhan'
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view(
            'pengaduan_bencana.user.show',
            compact('data')
        );
    }
}
