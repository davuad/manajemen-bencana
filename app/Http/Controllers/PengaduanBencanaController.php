<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FotoPengaduan;
use App\Models\KategoriBencana;
use App\Models\KebutuhanPengaduan;
use App\Models\PengaduanBencana;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class PengaduanBencanaController extends Controller
{
    public function boot()
    {
        View::composer('*', function ($view) {

            $user = Auth::user();

            $view->with('user', $user);
        });
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
        $users = User::all();

        return view('pengaduan_bencana.create', compact('kategori', 'users'));
    }

    // simpan data
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'kategori_id' => 'required',
            'desa' => 'required',
            'deskripsi' => 'required',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:5120' // optional validasi foto
        ]);

        $pengaduan = PengaduanBencana::create([
            'user_id' => $request->user_id,
            'kategori_id' => $request->kategori_id,
            'desa' => $request->desa,
            'deskripsi' => $request->deskripsi,
            'status_pengaduan' => 'BELUM_DITANGANI'
        ]);

        KebutuhanPengaduan::create([
            'pengaduan_bencana_id' => $pengaduan->id,
            'dapur_umum' => $request->dapur_umum ?? 'Tidak',
            'psikososial' => $request->psikososial ?? 'Tidak',
            'logistik_rentan' => $request->logistik_rentan ?? 'Tidak',
            'logistik_makanan' => $request->logistik_makanan ?? 'Tidak',
            'logistik_penampungan' => $request->logistik_penampungan ?? 'Tidak',
            'keterangan' => $request->keterangan_kebutuhan
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {

                // bikin nama unik biar gak ketimpa
                $nama = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                // pindahkan ke folder public/foto
                $file->move(public_path('foto'), $nama);

                FotoPengaduan::create([
                    'pengaduan_bencana_id' => $pengaduan->id,
                    'file_foto' => $nama,
                    'keterangan' => $request->keterangan // dari input form
                ]);
            }
        }

        return redirect('/admin/pengaduan')->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $pengaduan = PengaduanBencana::findOrFail($id);
        $tanggal_selesai = ($request->status_pengaduan == 'SELESAI') ? $request->tanggal_selesai : null;

        // UPDATE PENGADUAN
        $pengaduan->update([
            'user_id' => $request->user_id,
            'kategori_id' => $request->kategori_id,
            'desa' => $request->desa,
            'deskripsi' => $request->deskripsi,
            'status_pengaduan' => $request->status_pengaduan,
            'tanggal_selesai'  => $tanggal_selesai,
        ]);

        // UPDATE FOTO
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto'), $nama);

            FotoPengaduan::updateOrCreate(
                ['pengaduan_bencana_id' => $id],
                [
                    'file_foto' => $nama,
                    'keterangan' => $request->keterangan_foto
                ]
            );
        }

        // UPDATE / CREATE KEBUTUHAN
        KebutuhanPengaduan::updateOrCreate(
            ['pengaduan_bencana_id' => $id],
            [
                'dapur_umum' => $request->dapur_umum,
                'psikososial' => $request->psikososial,
                'logistik_rentan' => $request->logistik_rentan,
                'logistik_makanan' => $request->logistik_makanan,
                'logistik_penampungan' => $request->logistik_penampungan,
                'keterangan' => $request->keterangan_kebutuhan
            ]
        );

        return redirect('/admin/pengaduan')->with('success', 'Data berhasil diupdate');
    }

    public function show($id)
    {
        $data = PengaduanBencana::with(['foto', 'kebutuhan'])->findOrFail($id);
        $kategori = KategoriBencana::all();
        $user = User::all();

        return view('pengaduan_bencana.edit', compact('data', 'kategori', 'user'));
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
        $foto = FotoPengaduan::with('pengaduan')->findOrFail($id);
        return view('pengaduan_bencana.detail_foto', compact('foto'));
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

        if ($request->filled('status')) {
            $query->where('status_pengaduan', $request->status);
        }

        $data = $query->latest()->get();

        return view(
            'pengaduan_bencana.kabid.index',
            compact('data')
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

        return view(
            'pengaduan_bencana.user.create',
            compact('kategori')
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
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $pengaduan = PengaduanBencana::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'desa' => $request->desa,
            'deskripsi' => $request->deskripsi,
            'status_pengaduan' => 'BELUM_DITANGANI'
        ]);

        KebutuhanPengaduan::create([
            'pengaduan_bencana_id' => $pengaduan->id,
            'dapur_umum' => 'Tidak',
            'psikososial' => 'Tidak',
            'logistik_rentan' => 'Tidak',
            'logistik_makanan' => 'Tidak',
            'logistik_penampungan' => 'Tidak',
            'keterangan' => null
        ]);

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

        return redirect()
            ->route('user.pengaduan.index')
            ->with(
                'success',
                'Pengaduan berhasil dikirim'
            );
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
