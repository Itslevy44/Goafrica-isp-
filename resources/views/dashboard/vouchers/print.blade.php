<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Vouchers - goAfrica Connect</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 20px;
        }
        .print-controls {
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-print {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-print:hover {
            background-color: #1d4ed8;
        }
        .voucher-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        .voucher-card {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 15px;
            background-color: white;
            text-align: center;
            page-break-inside: avoid;
        }
        .voucher-network {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .voucher-code {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 2px;
            color: #0f172a;
            margin-bottom: 10px;
            background: #f1f5f9;
            padding: 5px;
            border-radius: 4px;
        }
        .voucher-value {
            font-size: 14px;
            font-weight: 600;
            color: #3b82f6;
        }
        .voucher-footer {
            margin-top: 10px;
            font-size: 10px;
            color: #94a3b8;
        }
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .print-controls {
                display: none;
            }
            .voucher-card {
                border-color: #000;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">🖨️ Print Vouchers</button>
        <p style="margin-top: 10px; color: #64748b;">Printing {{ $vouchers->count() }} vouchers</p>
    </div>

    <div class="voucher-grid">
        @forelse($vouchers as $voucher)
        <div class="voucher-card">
            <div class="voucher-network">{{ $network->name ?? 'WIFI VOUCHER' }}</div>
            <div class="voucher-code">{{ $voucher->code }}</div>
            <div class="voucher-value">
                @if($voucher->type == 'time')
                    {{ $voucher->value }} Minutes Access
                @else
                    KES {{ $voucher->value }} Value
                @endif
            </div>
            <div class="voucher-footer">
                Valid for {{ $voucher->max_uses }} device(s)
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #64748b;">
            No unused vouchers available to print. Generate some first.
        </div>
        @endforelse
    </div>

</body>
</html>
