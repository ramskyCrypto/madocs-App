<?php

namespace App\Http\Controllers;

use App\Support\Inbox;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $jenis = in_array($request->jenis, ['peminjaman', 'legalisir', 'ijazah'], true) ? $request->jenis : null;
        $semua = Inbox::items();

        return view('inbox.index', [
            'items' => $jenis ? $semua->where('jenis', $jenis)->values() : $semua,
            'jenis' => $jenis,
            'hitung' => $semua->countBy('jenis'),
            'total' => $semua->count(),
        ]);
    }
}
