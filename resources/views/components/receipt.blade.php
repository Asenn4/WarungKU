<div class="receipt" style="font-family: 'Courier New', monospace; font-size: 12px; line-height: 1.4;">
    <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px;">
        <h2 style="margin: 0; font-size: 18px; font-weight: bold;">WARUNG BAROKAH</h2>
        <p style="margin: 5px 0 0 0; font-size: 11px;">Jl. Raya Sejahtera No. 123</p>
        <p style="margin: 0; font-size: 11px;">Telp: 0812-3456-7890</p>
    </div>

    <div style="margin-bottom: 10px;">
        <table style="width: 100%; font-size: 11px;">
            <tr>
                <td>No. Transaksi</td>
                <td style="text-align: right;">#{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td style="text-align: right;">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td style="text-align: right;">{{ $transaction->cashier }}</td>
            </tr>
        </table>
    </div>

    <div style="border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 10px 0; margin-bottom: 10px;">
        <table style="width: 100%; font-size: 11px;">
            @foreach($transaction->items as $item)
                <tr>
                    <td colspan="3" style="padding: 2px 0;">{{ $item->product->name }}</td>
                </tr>
                <tr style="padding-bottom: 5px;">
                    <td style="width: 40px;">{{ $item->qty }} x</td>
                    <td style="text-align: right;">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td style="text-align: right; width: 80px;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div style="margin-bottom: 15px;">
        <table style="width: 100%; font-size: 12px;">
            <tr style="font-weight: bold; font-size: 14px;">
                <td>TOTAL</td>
                <td style="text-align: right;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; border-top: 1px dashed #000; padding-top: 10px; font-size: 11px;">
        <p style="margin: 0;">*** TERIMA KASIH ***</p>
        <p style="margin: 5px 0 0 0;">Barang yang sudah dibeli</p>
        <p style="margin: 0;">tidak dapat ditukar/dikembalikan</p>
    </div>
</div>