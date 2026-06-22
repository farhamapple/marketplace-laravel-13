<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #0A0A0A; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .subtitle { font-size: 12px; color: #6B6B6B; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #6366F1; color: #fff; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; }
        td { padding: 7px 10px; border-bottom: 1px solid #E8E8EC; }
        tr:nth-child(even) td { background: #FAFAFA; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 9px; font-weight: 600; }
        .badge-sale { background: #10B981; color: #fff; }
        .badge-purchase { background: #F59E0B; color: #fff; }
        .footer { margin-top: 20px; font-size: 10px; color: #6B6B6B; text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Transaksi</h1>
    <p class="subtitle">
        Periode:
        {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d M Y') : 'Awal' }}
        —
        {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d M Y') : 'Sekarang' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Customer</th>
                <th class="text-center">Tipe</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $tx)
                <tr>
                    <td>#{{ $tx->id }}</td>
                    <td>{{ $tx->product->name }}</td>
                    <td>{{ $tx->product->category->name ?? '-' }}</td>
                    <td>{{ $tx->user?->name ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $tx->type === 'sale' ? 'badge-sale' : 'badge-purchase' }}">
                            {{ $tx->type === 'sale' ? 'Penjualan' : 'Pembelian' }}
                        </span>
                    </td>
                    <td class="text-right">{{ $tx->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                    <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:30px;color:#6B6B6B;">
                        Tidak ada transaksi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d M Y H:i') }} — Marketplace Laravel 13
    </div>
</body>
</html>
