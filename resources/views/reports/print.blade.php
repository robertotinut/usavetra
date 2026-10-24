<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body {
            background-color: #fff;
            color: #000;
        }
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="print-container">
        <div class="text-center mb-4">
            <h2>Laporan Insight Bisnis</h2>
            <p>Periode: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
        </div>
        <hr>

        <h4 class="mb-3">Ringkasan Keuangan</h4>
        <table class="table table-bordered mb-5">
            <tbody>
                <tr>
                    <th style="width: 40%">Total Omzet Penjualan</th>
                    <td>Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total Laba Kotor</th>
                    <td>Rp {{ number_format($labaKotor, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total Pengeluaran (Pembelian)</th>
                    <td>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total Transaksi</th>
                    <td>{{ number_format($totalTransaksi, 0, ',', '.') }} Transaksi</td>
                </tr>
            </tbody>
        </table>

        <h4 class="mb-3">Top 5 Produk Terlaris</h4>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Produk</th>
                    <th>Kuantitas Terjual</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $idx => $prod)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>{{ $prod->name }}</td>
                    <td>{{ $prod->total_qty }}</td>
                    <td>Rp {{ number_format($prod->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada data penjualan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="text-center mt-5 no-print">
            <button onclick="window.print()" class="btn btn-primary">Cetak Ulang</button>
            <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</body>
</html>
