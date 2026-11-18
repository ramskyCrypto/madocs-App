<?php

namespace App\Http\Controllers;

use App\Models\Legalisir;
use App\Models\PengambilanIjazah;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    private const LABEL = [
        'peminjaman' => 'Peminjaman Buku Rapor Fisik',
        'legalisir' => 'Pengajuan Legalisir',
        'ijazah' => 'Pengambilan Ijazah Asli',
    ];

    private function build(Request $r): array
    {
        $jenis = in_array($r->input('jenis'), array_keys(self::LABEL)) ? $r->input('jenis') : 'peminjaman';
        $model = match ($jenis) {
            'legalisir' => Legalisir::class,
            'ijazah' => PengambilanIjazah::class,
            default => Peminjaman::class,
        };

        $q = $model::with('user')->latest()
            ->when($r->status, fn ($q, $s) => $q->where('status', $s))
            ->when($r->dari, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
            ->when($r->sampai, fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
        if ($jenis === 'peminjaman') {
            $q->with('document');
        }
        $items = $q->get();

        [$heads, $rows] = match ($jenis) {
            'legalisir' => [
                ['Kode', 'NIS', 'Nama', 'Dokumen', 'Rangkap', 'Keperluan', 'Tgl Ajuan', 'Status'],
                $items->map(fn ($x) => [$x->kode, $x->user->nis, $x->user->name, ucfirst($x->jenis), $x->jumlah_rangkap, $x->keperluan, $x->created_at->format('d/m/Y'), ucfirst($x->status)]),
            ],
            'ijazah' => [
                ['Kode', 'NIS', 'Nama', 'Jadwal', 'Pengambil', 'Tgl Diserahkan', 'Status'],
                $items->map(fn ($x) => [$x->kode, $x->user->nis, $x->user->name, $x->jadwal_tanggal->format('d/m/Y').' '.$x->jadwal_jam,
                    $x->diwakilkan ? $x->nama_pengambil.' (wakil)' : 'Siswa sendiri', optional($x->tgl_diserahkan)->format('d/m/Y H:i') ?? '-', ucfirst($x->status)]),
            ],
            default => [
                ['Kode', 'NIS', 'Nama', 'Dokumen', 'Tgl Pinjam', 'Rencana Kembali', 'Dikembalikan', 'Status'],
                $items->map(fn ($x) => [$x->kode, $x->user->nis, $x->user->name, $x->document->judul, $x->tgl_pinjam->format('d/m/Y'),
                    $x->tgl_kembali_rencana->format('d/m/Y'), optional($x->tgl_dikembalikan)->format('d/m/Y') ?? '-', ucfirst($x->status)]),
            ],
        };

        $ringkas = $items->groupBy('status')->map->count();

        return [
            'jenis' => $jenis,
            'judul' => 'Laporan '.self::LABEL[$jenis],
            'heads' => $heads,
            'rows' => $rows->values(),
            'ringkas' => $ringkas,
            'total' => $items->count(),
            'filter' => $r->only('jenis', 'status', 'dari', 'sampai'),
        ];
    }

    public function index(Request $request)
    {
        return view('laporan.index', $this->build($request) + ['labels' => self::LABEL]);
    }

    public function pdf(Request $request)
    {
        $data = $this->build($request);

        return Pdf::loadView('laporan.pdf', $data)->setPaper('a4', 'landscape')
            ->download('laporan-'.$data['jenis'].'-'.now()->format('Ymd').'.pdf');
    }

    public function csv(Request $request)
    {
        $data = $this->build($request);
        $filename = 'laporan-'.$data['jenis'].'-'.now()->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar terbaca benar di Excel
            fputcsv($out, $data['heads'], ';');
            foreach ($data['rows'] as $row) {
                fputcsv($out, $row, ';');
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
