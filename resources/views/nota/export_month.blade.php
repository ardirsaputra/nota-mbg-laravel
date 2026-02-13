<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Nota - {{ $month }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #667eea;
            color: white;
        }
    </style>
</head>

<body>
    <h1>Export Nota - {{ $month }}</h1>
    <table>
        <thead>
            <tr>
                <th>No Nota</th>
                <th>Tanggal</th>
                <th>Toko</th>
                <th>Total</th>
                <th>Items</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notas as $nota)
                <tr>
                    <td>{{ $nota->no }}</td>
                    <td>{{ $nota->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $nota->nama_toko ?? '-' }}</td>
                    <td>Rp {{ number_format($nota->total, 0, ',', '.') }}</td>
                    <td>{{ $nota->items->count() }} items</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
