<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 20px;
            width: 80mm; /* Standard POS width */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .header { margin-bottom: 20px; }
        .logo {
            max-width: 50mm;
            max-height: 20mm;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
        }
        @media print {
            body { padding: 0; margin: 0; }
            @page { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header text-center">
        @php $logo = \App\Models\Setting::get('logo'); @endphp
        @if($logo)
            <img src="{{ Storage::url($logo) }}" class="logo">
        @endif
        <div class="font-bold" style="font-size: 16px;">{{ \App\Models\Setting::get('company_name', 'Épicerie POS') }}</div>
        <div>{{ \App\Models\Setting::get('company_address', '') }}</div>
        <div>Tél: {{ \App\Models\Setting::get('company_phone', '') }}</div>
        @if(\App\Models\Setting::get('tax_number'))
            <div>NIF: {{ \App\Models\Setting::get('tax_number') }}</div>
        @endif
    </div>

    <div class="divider"></div>

    <div class="info">
        <div>Ticket #: {{ $order->id }}</div>
        <div>Date: {{ $order->created_at->format('d/m/Y H:i') }}</div>
        <div>Vendeur: {{ $order->user->name }}</div>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th class="text-left">Article</th>
                <th class="text-center">Qté</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->subtotal, 3) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    @php
        $taxRate = (float)\App\Models\Setting::get('tax_rate', 0);
        $total = $order->total_amount;
        $discount = $order->discount_amount;
        $subtotalHT = ($total + $discount) / (1 + ($taxRate / 100));
        $tvaAmount = ($total + $discount) - $subtotalHT;
    @endphp

    <table>
        @if($discount > 0)
        <tr>
            <td>Remise</td>
            <td class="text-right">-{{ number_format($discount, 3) }}</td>
        </tr>
        @endif
        @if($taxRate > 0)
        <tr>
            <td>Total HT</td>
            <td class="text-right">{{ number_format($subtotalHT, 3) }}</td>
        </tr>
        <tr>
            <td>TVA ({{ $taxRate }}%)</td>
            <td class="text-right">{{ number_format($tvaAmount, 3) }}</td>
        </tr>
        @endif
        <tr class="font-bold" style="font-size: 14px;">
            <td>TOTAL TTC</td>
            <td class="text-right">{{ number_format($total, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="text-center">
        Mode de paiement: {{ strtoupper($order->payment_method) }}
    </div>

    <div class="footer text-center">
        <div>{{ \App\Models\Setting::get('footer_text', 'Merci de votre visite !') }}</div>
        <div style="margin-top: 10px;">Logiciel POS v1.0</div>
    </div>

    <div class="no-print text-center" style="margin-top: 30px;">
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Fermer la fenêtre</button>
    </div>
</body>
</html>
