<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BAController extends Controller
{
       public function beritaAcara($id)
{
    $distribusi = Distribusi::findOrFail($id);

    return view(
        'management_distribusi.berita_acara',
        compact('distribusi')
    );
}
}
