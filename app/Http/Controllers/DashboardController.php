<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use App\Models\DapurUmum;
use App\Models\Distribusi;
use App\Models\Gudang;
use App\Models\KebutuhanHarian;
use App\Models\PengaduanBencana;
use App\Models\Posko;
use App\Models\StokGudang;
use App\Models\WargaTerdampak;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }
        if ($user->hasRole('relawan')) {
            return $this->relawanDashboard();
        }
        if ($user->hasRole('kadus')) {
            return $this->kadusDashboard();
        }
        if ($user->hasRole('kabid')) {
            return $this->kabidDashboard();
        }
        if ($user->hasRole('desa')) {
            return $this->desaDashboard();
        }
        if ($user->hasRole('ketua_tim')) {
            return $this->ketuaTimDashboard();
        }
        if ($user->hasRole('pegawai')) {
            return $this->pegawaiDashboard();
        }
        if ($user->hasRole('petugas')) {
            return $this->petugasDashboard();
        }

        return view('dashboard');
    }

    protected function adminDashboard()
    {
        return view('dashboard', [
            'total_pengaduan' => PengaduanBencana::count(),
            'pengaduan_pending' => PengaduanBencana::where('status_pengaduan', 'BELUM_DITANGANI')->count(),
            'total_posko' => Posko::count(),
            'total_gudang' => Gudang::count(),
            'total_bencana' => Bencana::count(),
            'total_warga_terdampak' => WargaTerdampak::count(),
        ]);
    }

    protected function relawanDashboard()
    {
        $userId = Auth::id();

        return view('dashboard', [
            'pengaduan_saya' => PengaduanBencana::where('user_id', $userId)->count(),
            'pengaduan_pending' => PengaduanBencana::where('user_id', $userId)
                ->where('status_pengaduan', 'BELUM_DITANGANI')->count(),
            'pengaduan_proses' => PengaduanBencana::where('user_id', $userId)
                ->where('status_pengaduan', 'DITANGANI')->count(),
        ]);
    }

    protected function kadusDashboard()
    {
        $userId = Auth::id();

        return view('dashboard', [
            'total_warga' => WargaTerdampak::count(),
            'warga_pending' => WargaTerdampak::where('status_penyaluran', 'Belum diproses')->count(),
            'pengaduan_desa' => PengaduanBencana::where('user_id', $userId)->count(),
        ]);
    }

    protected function kabidDashboard()
    {
        return view('dashboard', [
            'total_bencana' => Bencana::count(),
            'bencana_aktif' => Bencana::where('status_bencana', 'berlangsung')->count(),
            'total_posko' => Posko::count(),
            'total_distribusi' => Distribusi::count(),
        ]);
    }

    protected function desaDashboard()
    {
        $userId = Auth::id();

        return view('dashboard', [
            'warga_terdampak' => WargaTerdampak::count(),
            'pengaduan_desa' => PengaduanBencana::where('user_id', $userId)->count(),
        ]);
    }

    protected function ketuaTimDashboard()
    {
        return view('dashboard', [
            'total_posko' => Posko::count(),
            'total_dapur_umum' => DapurUmum::count(),
            'distribusi_pending' => Distribusi::where('status', 'pending')->count(),
        ]);
    }

    protected function pegawaiDashboard()
    {
        return view('dashboard', [
            'total_stok' => StokGudang::sum('jumlah_stok'),
            'distribusi_pending' => Distribusi::where('status', 'pending')->count(),
            'gudang_count' => Gudang::count(),
            'barang_masuk' => DB::table('barang_masuk')->whereDate('created_at', today())->count(),
            'barang_keluar' => BarangKeluar::whereDate('created_at', today())->count(),
        ]);
    }

    protected function petugasDashboard()
    {
        return view('dashboard', [
            'posko_count' => Posko::count(),
            'dapur_umum_count' => DapurUmum::count(),
            'warga_terdampak' => WargaTerdampak::count(),
            'kebutuhan_harian' => KebutuhanHarian::whereDate('created_at', today())->count(),
        ]);
    }
}
