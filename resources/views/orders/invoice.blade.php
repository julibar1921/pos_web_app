<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $order->id }} - {{ \App\Models\Setting::get('company_name') }}</title>
    <style>
        @page { size: A4; margin: 20mm; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 0; background: white; }
        .invoice-box { max-width: 800px; margin: auto; }
        
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 2px solid #f0f0f0; padding-bottom: 20px; }
        .logo { max-height: 80px; }
        .company-info h1 { margin: 0; font-size: 24px; color: #4f46e5; }
        .company-info p { margin: 5px 0; font-size: 12px; color: #666; }

        .details { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .details h2 { font-size: 14px; text-transform: uppercase; color: #999; margin-bottom: 10px; }
        .details p { margin: 0; font-weight: bold; font-size: 14px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        table th { background: #f9fafb; padding: 12px; text-align: left; font-size: 12px; text-transform: uppercase; border-bottom: 2px solid #f0f0f0; }
        table td { padding: 12px; border-bottom: 1px solid #f9fafb; font-size: 13px; }
        
        .totals { margin-left: auto; width: 300px; }
        .total-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .total-row.grand-total { border-bottom: none; font-weight: bold; font-size: 18px; color: #4f46e5; background: #f9fafb; padding: 15px; border-radius: 10px; margin-top: 10px; }
        
        .footer { margin-top: 60px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #f0f0f0; padding-top: 20px; }
        
        @media print {
            .no-print { display: none; }
            body { background: white; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice-box">
        <div class="header">
            <div class="company-info">
                <h1>{{ \App\Models\Setting::get('company_name', 'Gestion POS') }}</h1>
                <p>{{ \App\Models\Setting::get('company_address', 'Adresse non configurée') }}</p>
                <p>Tél: {{ \App\Models\Setting::get('company_phone', 'N/A') }}</p>
                <p>Email: {{ \App\Models\Setting::get('company_email', 'N/A') }}</p>
            </div>
            @if(\App\Models\Setting::get('logo'))
                <img src="{{ Storage::url(\App\Models\Setting::get('logo')) }}" class="logo">
            @endif
        </div>

        <div class="details">
            <div>
                <h2>Facturé à</h2>
                <p>{{ $order->customer ? $order->customer->name : 'Client de passage' }}</p>
                @if($order->customer)
                    <div style="font-size: 12px; color: #666; font-weight: normal; margin-top: 5px;">
                        {{ $order->customer->address }}<br>
                        {{ $order->customer->phone }}
                    </div>
                @endif
            </div>
            <div style="text-align: right;">
                <h2>Numéro de Facture</h2>
                <p>FAC-{{ date('Y') }}-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <h2 style="margin-top: 15px;">Date d'émission</h2>
                <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: center;">Qté</th>
                    <th style="text-align: right;">Prix Unitaire</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div style="font-weight: bold;">{{ $item->product->name }}</div>
                        <div style="font-size: 11px; color: #999;">{{ $item->product->barcode }}</div>
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->price, 3) }}</td>
                    <td style="text-align: right;">{{ number_format($item->subtotal, 3) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row">
                <span>Sous-total HT</span>
                <span>{{ number_format($order->total_amount + $order->discount_amount, 3) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row" style="color: #ef4444;">
                <span>Remise</span>
                <span>-{{ number_format($order->discount_amount, 3) }}</span>
            </div>
            @endif
            <div class="total-row grand-total">
                <span>TOTAL TTC</span>
                <span>{{ number_format($order->total_amount, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}</span>
            </div>
        </div>

        <div style="margin-top: 40px; font-size: 12px;">
            <p><strong>Mode de paiement :</strong> {{ strtoupper($order->payment_method) }}</p>
            <p><strong>Arrêté la présente facture à la somme de :</strong> <br>
            <span style="font-style: italic; color: #666;">Calcul du montant en toutes lettres...</span></p>
        </div>

        <div class="footer">
            <p>Merci pour votre confiance !</p>
            <p>{{ \App\Models\Setting::get('company_name') }} - Registre du Commerce: {{ \App\Models\Setting::get('company_rc', 'N/A') }} - Matricule Fiscale: {{ \App\Models\Setting::get('company_mf', 'N/A') }}</p>
        </div>
    </div>

    <div class="no-print" style="position: fixed; bottom: 20px; right: 20px;">
        <button onclick="window.print()" style="padding: 15px 30px; background: #4f46e5; color: white; border: none; rounded: 10px; font-weight: bold; cursor: pointer; border-radius: 12px; shadow: 0 10px 15px rgba(0,0,0,0.1);">
            <i class="fas fa-print"></i> Imprimer la Facture
        </button>
    </div>
</body>
</html>
