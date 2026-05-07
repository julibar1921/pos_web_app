<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Étiquette - {{ $product->name }}</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        @page {
            size: 50mm 30mm;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 2mm;
            width: 46mm;
            height: 26mm;
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
        }
        .name {
            font-size: 10px;
            font-weight: 800;
            color: #000;
            margin-bottom: 1mm;
            line-height: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .price {
            font-size: 12px;
            font-weight: 900;
            margin-bottom: 1mm;
        }
        svg {
            max-width: 100%;
            height: auto;
            max-height: 10mm;
        }
        .barcode-text {
            font-size: 8px;
            font-family: monospace;
            margin-top: 1px;
        }
        @media print {
            .no-print { display: none; }
        }
        .no-print {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #4f46e5;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">IMPRIMER</button>

    <div class="name">{{ $product->name }}</div>
    <div class="price">{{ number_format($product->selling_price, 2) }} {{ \App\Models\Setting::get('currency', 'DA') }}</div>
    
    <svg id="barcode"></svg>

    <script>
        JsBarcode("#barcode", "{{ $product->barcode ?? $product->id }}", {
            format: "CODE128",
            width: 1.5,
            height: 40,
            displayValue: true,
            fontSize: 10,
            margin: 0
        });
        
        // Auto print and close if needed
        // window.print();
    </script>
</body>
</html>
