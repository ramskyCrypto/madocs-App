<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #222; }
        h1 { font-size: 15px; margin: 0; } .sub { color: #666; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 5px; text-align: left; }
        th { background: #e8e8f5; }
        .ttd { margin-top: 30px; float: right; text-align: center; width: 200px; }
    </style>
</head>
<body>
    <h1>MaDocs - {{ $judul }}</h1>
    <div class="sub">
        Dicetak: {{ now()->translatedFormat('d F Y H:i') }} &middot; Total {{ $total }} data
        @foreach($ringkas as $st => $n) &middot; {{ ucfirst($st) }}: {{ $n }} @endforeach
        @if(! empty($filter['dari']) || ! empty($filter['sampai']))<br>Periode: {{ $filter['dari'] ?? '...' }} s/d {{ $filter['sampai'] ?? '...' }}@endif
    </div>
    <table>
        <thead><tr><th>#</th>@foreach($heads as $h)<th>{{ $h }}</th>@endforeach</tr></thead>
        <tbody>
        @foreach($rows as $n => $row)
            <tr><td>{{ $n + 1 }}</td>@foreach($row as $cell)<td>{{ $cell }}</td>@endforeach</tr>
        @endforeach
        </tbody>
    </table>
    <div class="ttd">Petugas Tata Usaha<br><br><br><br>( {{ auth()->user()->name }} )</div>
</body>
</html>
