<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $sale->receipt_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            color: #000;
            width: 58mm; /* Standard thermal width */
            background-color: #fff;
        }
        
        .receipt-container {
            width: 58mm;
            padding: 4mm;
            margin: 0 auto;
        }
        
        .text-center { text-align: center; }
        .text-start { text-align: left; }
        .text-end { text-align: right; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        
        .border-top { border-top: 1px dashed #000; }
        .border-bottom { border-bottom: 1px dashed #000; }
        
        .header { margin-bottom: 4mm; }
        .shop-name { font-size: 15px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
        .shop-address { font-size: 10px; line-height: 1.3; color: #222; }
        
        .info-table, .item-table, .total-table { width: 100%; font-size: 11px; }
        
        .info-table { margin-bottom: 3mm; }
        .info-table td { padding: 1px 0; }
        
        .item-table { margin-bottom: 2mm; border-collapse: collapse; }
        .item-table th, .item-table td { padding: 2px 0; vertical-align: top; }
        .item-name { font-size: 11.5px; padding-bottom: 1px; line-height: 1.2; }
        .item-details { font-size: 10.5px; color: #333; }
        
        .total-section { padding-top: 2mm; margin-top: 2mm; }
        .total-table td { padding: 2px 0; }
        .total-row { font-size: 13.5px; font-weight: 700; border-bottom: 1px dashed #000; padding-bottom: 2mm; }
        
        .footer { font-size: 10px; text-align: center; margin-top: 4mm; line-height: 1.4; color: #222; }
        
        @media print {
            body { width: 58mm; }
            @page { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header text-center">
            @if($hasPremiumStruk && $store->logo_path)
                <img src="{{ asset('storage/' . $store->logo_path) }}" alt="Logo" style="max-width: 100px; margin-bottom: 5px;">
            @endif
            <div class="shop-name font-bold">{{ $store->name ?: 'NAMA TOKO' }}</div>
            <div class="shop-address">
                <span style="white-space: pre-wrap;">{{ $store->address ?: 'Jl. Contoh Alamat No. 123' }}</span><br>
                Telp: {{ $store->phone ?: '081234567890' }}
            </div>
        </div>

        <!-- Info -->
        <div class="border-top border-bottom" style="padding: 2mm 0; margin-bottom: 3mm;">
            <table class="info-table">
                <tr>
                    <td class="text-start" style="width: 35%;">Struk</td>
                    <td class="text-end font-semibold">{{ $sale->receipt_number }}</td>
                </tr>
                <tr>
                    <td class="text-start">Tanggal</td>
                    <td class="text-end">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="text-start">Kasir</td>
                    <td class="text-end">{{ $sale->user->name ?? 'Kasir' }}</td>
                </tr>
            </table>
        </div>

        <!-- Items -->
        <table class="item-table">
            @foreach($sale->items as $item)
            <tr>
                <td colspan="2" class="text-start font-semibold item-name">
                    {{ $item->product ? $item->product->name : $item->custom_name }}
                </td>
            </tr>
            <tr class="item-details">
                <td class="text-start" style="padding-bottom: 3px;">
                    {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}
                </td>
                <td class="text-end font-semibold" style="color: #000; padding-bottom: 3px;">
                    {{ number_format($item->subtotal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </table>

        <!-- Totals -->
        <div class="border-top total-section">
            <table class="total-table">
                <tr class="total-row">
                    <td class="text-start" style="padding-bottom: 3px;">TOTAL</td>
                    <td class="text-end" style="padding-bottom: 3px;">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                </tr>
                <tr style="font-size: 11px;">
                    <td class="text-start" style="padding-top: 3px;">Tunai</td>
                    <td class="text-end" style="padding-top: 3px;">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
                </tr>
                <tr style="font-size: 11px;">
                    <td class="text-start">Kembali</td>
                    <td class="text-end">Rp {{ number_format($sale->return_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer border-top" style="padding-top: 3mm;">
            @if($hasPremiumStruk && $store->receipt_footer)
                <div style="white-space: pre-wrap; margin-bottom: 1mm;">{{ $store->receipt_footer }}</div>
            @else
                <div style="white-space: pre-wrap; margin-bottom: 1mm;">Terima kasih atas kunjungannya!</div>
                <div style="font-size: 8px; color: #555;">
                    * Powered by Usavetra *
                </div>
            @endif
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); // Beri waktu font Google (Inter) untuk load sebelum print
        }
    </script>
</body>
</html>
