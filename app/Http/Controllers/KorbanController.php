<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Bencana;
use App\Models\Korban;
use App\Models\Posko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class KorbanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;

        $query = Korban::with(['bencana.kategori', 'posko', 'user']);

        $query->whereYear('tanggal_kejadian', $tahun);

        if ($request->filled('bencana_id')) {
            $query->where('bencana_id', $request->bencana_id);
        }

        if ($request->filled('posko_id')) {
            $query->where('posko_id', $request->posko_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        $korban = $query->latest()->paginate(10)->withQueryString();

        $bencana = Bencana::with('kategori')->get();
        $posko = Posko::all();

        $tahunList = Korban::selectRaw('YEAR(tanggal_kejadian) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view(
            'management_korban.korban.index',
            compact(
                'korban',
                'bencana',
                'posko',
                'tahun',
                'tahunList'
            )
        );
    }

    public function create()
    {
        $bencana = Bencana::with(['kategori', 'desa'])->get();
        $posko = Posko::with(['desa'])->get();

        return view('management_korban.korban.create', compact('bencana', 'posko'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'bencana_id' => ['required', 'exists:bencana,id'],
            'posko_id' => ['required', 'exists:posko,id'],
            'nama' => ['required', 'string', 'max:100'],
            'nik' => [
                'nullable',
                'string',
                'max:20',
                'unique:korban,nik'
            ],
            'jenis_kelamin' => ['required', 'in:Laki-Laki,Perempuan'],
            'umur' => ['required', 'integer', 'min:0', 'max:150'],
            'alamat' => ['required', 'string', 'max:255'],
            'lokasi_kejadian' => ['required', 'string', 'max:255'],
            'tanggal_kejadian' => ['required', 'date'],
        ]);

        $data['user_id'] = Auth::id();

        Korban::create($data);

        return redirect()->route($this->getRoutePrefix() . '.korban.index')
            ->with('success', 'Data korban berhasil ditambahkan.');
    }

    public function show(Korban $korban)
    {
        $korban->load(['bencana.kategori', 'bencana.desa', 'posko', 'user']);

        return view('management_korban.korban.show', compact('korban'));
    }

    public function edit(Korban $korban)
    {
        $bencana = Bencana::with(['kategori', 'desa'])->get();
        $posko = Posko::with(['desa'])->get();

        return view('management_korban.korban.edit', compact('korban', 'bencana', 'posko'));
    }

    public function update(Request $request, Korban $korban)
    {
        $data = $request->validate([
            'bencana_id' => ['required', 'exists:bencana,id'],
            'posko_id' => ['required', 'exists:posko,id'],
            'nama' => ['required', 'string', 'max:100'],
            'nik' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('korban', 'nik')->ignore($korban->id),
            ],
            'jenis_kelamin' => ['required', 'in:Laki-Laki,Perempuan'],
            'umur' => ['required', 'integer', 'min:0', 'max:150'],
            'alamat' => ['required', 'string', 'max:255'],
            'lokasi_kejadian' => ['required', 'string', 'max:255'],
            'tanggal_kejadian' => ['required', 'date'],
        ]);

        $data['user_id'] = Auth::id();

        $korban->update($data);

        return redirect()->route($this->getRoutePrefix() . '.korban.index')
            ->with('success', 'Data korban berhasil diperbarui.');
    }

    public function destroy(Korban $korban)
    {
        $korban->delete();

        return redirect()->route($this->getRoutePrefix() . '.korban.index')
            ->with('success', 'Data korban berhasil dihapus.');
    }

    private function getFilteredKorban(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;

        $query = Korban::with(['bencana.kategori', 'posko', 'user']);

        $query->whereYear('tanggal_kejadian', $tahun);

        if ($request->filled('bencana_id')) {
            $query->where('bencana_id', $request->bencana_id);
        }

        if ($request->filled('posko_id')) {
            $query->where('posko_id', $request->posko_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        return $query->latest()->get();
    }

    public function reviewPdf(Request $request)
    {
        $korban = $this->getFilteredKorban($request);

        $tahun = $request->tahun ?? now()->year;

        $bencana = null;
        if ($request->filled('bencana_id')) {
            $bencana = Bencana::with(['kategori', 'desa'])
                ->find($request->bencana_id);
        }

        $posko = null;
        if ($request->filled('posko_id')) {
            $posko = Posko::find($request->posko_id);
        }

        $pdf = Pdf::loadView(
            'management_korban.laporan.korban_pdf',
            compact(
                'korban',
                'bencana',
                'posko',
                'tahun'
            )
        )->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-korban.pdf');
    }

    private function getRoutePrefix()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return 'admin';
        }

        if ($user->hasRole('petugas')) {
            return 'petugas';
        }

        if ($user->hasRole('relawan')) {
            return 'relawan';
        }

        if ($user->hasRole('desa')) {
            return 'desa';
        }

        abort(403);
    }
}
