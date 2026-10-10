<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi Pengiriman — {{ $order->tracking_number ?? $order->order_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Inter:wght@400;500;600;700&family=Libre+Barcode+128&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1f2937;
            background-color: #f3f4f6;
            padding: 24px;
            font-size: 13px;
            line-height: 1.4;
        }
        .no-print-bar {
            max-width: 800px;
            margin: 0 auto 20px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }
        .btn-primary {
            background-color: #B85D66;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #8F3B44;
        }
        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        .btn-secondary:hover {
            background-color: #e5e7eb;
        }
        .airwaybill-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #111827;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header-section {
            padding: 16px 20px;
            border-bottom: 2px solid #111827;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fffaf9;
        }
        .brand-title {
            font-family: 'Cinzel', Georgia, serif;
            font-size: 20px;
            font-weight: 700;
            color: #B85D66;
            letter-spacing: 1px;
        }
        .brand-sub {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }
        .badge-standard {
            display: inline-block;
            background: #111827;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }
        .barcode-section {
            padding: 16px 20px;
            border-bottom: 2px dashed #9ca3af;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
        }
        .barcode-wrap {
            text-align: left;
        }
        .barcode-font {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 48px;
            line-height: 1;
            color: #111827;
            letter-spacing: 2px;
        }
        .tracking-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #111827;
            margin-top: 2px;
        }
        .order-ref {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }
        .qr-wrap {
            text-align: center;
            padding-left: 16px;
        }
        .qr-wrap img {
            width: 84px;
            height: 84px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 2px;
        }
        .qr-caption {
            font-size: 9px;
            color: #6b7280;
            margin-top: 3px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-bottom: 2px solid #111827;
        }
        .address-box {
            padding: 14px 20px;
        }
        .address-box:first-child {
            border-right: 2px solid #111827;
            background-color: #fafaf9;
        }
        .box-label {
            font-size: 10px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .person-name {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2px;
        }
        .person-phone {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 6px;
        }
        .address-text {
            font-size: 12px;
            color: #374151;
            line-height: 1.45;
        }
        .delivery-meta-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            padding: 12px 20px;
            border-bottom: 2px solid #111827;
            background-color: #f9fafb;
            gap: 12px;
        }
        .meta-item .meta-label {
            font-size: 10px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
        }
        .meta-item .meta-val {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
            margin-top: 2px;
        }
        .greeting-box {
            padding: 12px 20px;
            border-bottom: 2px solid #111827;
            background-color: #fffef2;
        }
        .greeting-text {
            font-style: italic;
            font-size: 12px;
            color: #78350f;
            margin-top: 4px;
            line-height: 1.4;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            border-bottom: 2px solid #111827;
        }
        .items-table th {
            background-color: #f3f4f6;
            padding: 8px 16px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            border-bottom: 1px solid #d1d5db;
        }
        .items-table td {
            padding: 8px 16px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        .footer-note {
            padding: 8px 20px;
            background-color: #111827;
            color: #ffffff;
            font-size: 10px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .no-print-bar {
                display: none;
            }
            .airwaybill-container {
                box-shadow: none;
                max-width: 100%;
                border-width: 1.5px;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div>
            <strong>Resi Pengiriman Resmi (Airway Bill)</strong>
            <span style="font-size: 11px; color: #6b7280; margin-left: 8px;">Order: {{ $order->order_number }}</span>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">
                ← Kembali ke Detail Pesanan
            </a>
            <a href="{{ route('checkout.tracking', $order->tracking_number ?? $order->order_number) }}" target="_blank" class="btn btn-secondary">
                🔍 Buka Lacak Live
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Cetak Resi Sekarang
            </button>
        </div>
    </div>

    <div class="airwaybill-container">
        <!-- Header -->
        <div class="header-section">
            <div>
                <div class="brand-title">DESY FLORIST SURABAYA</div>
                <div class="brand-sub">Premium Fresh Flower Florist & Artisan Bouquet Studio</div>
            </div>
            <div style="text-align: right;">
                <span class="badge-standard">RESI PENGIRIMAN RESMI</span>
                <div style="font-size: 10px; color: #6b7280; margin-top: 4px;">Kurir Khusus Bunga Segar</div>
            </div>
        </div>

        <!-- Barcode & QR Code Section -->
        <div class="barcode-section">
            <div class="barcode-wrap">
                <div style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Nomor Resi Pelacakan (Tracking No):</div>
                <div class="barcode-font">*{{ $order->tracking_number ?? $order->order_number }}*</div>
                <div class="tracking-code">{{ $order->tracking_number ?? $order->order_number }}</div>
                <div class="order-ref">No. Ref Pesanan: <strong>{{ $order->order_number }}</strong> • Dibuat: {{ $order->created_at->format('d/m/Y H:i') }} WIB</div>
            </div>
            
            <div class="qr-wrap">
                @php
                    $trackingUrl = route('checkout.tracking', $order->tracking_number ?? $order->order_number);
                    $qrCodeApi = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($trackingUrl);
                @endphp
                <img src="{{ $qrCodeApi }}" alt="QR Code Tracking">
                <div class="qr-caption">Scan untuk Lacak</div>
            </div>
        </div>

        <!-- Addresses (Sender vs Recipient) -->
        <div class="grid-2">
            <!-- Sender -->
            <div class="address-box">
                <div class="box-label">🏠 Dari (Pengirim):</div>
                <div class="person-name">Desy Florist Studio Surabaya</div>
                <div class="person-phone">{{ $storeSettings['store_phone'] ?? '0812-3456-7890' }}</div>
                <div class="address-text">
                    {{ $storeSettings['store_address'] ?? 'Jl. Kayoon, Embong Kaliasin, Genteng' }}, Surabaya, Jawa Timur
                </div>
            </div>

            <!-- Recipient -->
            <div class="address-box" style="background-color: #fff;">
                <div class="box-label" style="color: #B85D66;">📍 Kepada (Penerima):</div>
                <div class="person-name">{{ $order->recipient_name }}</div>
                <div class="person-phone">{{ $order->recipient_phone }}</div>
                <div class="address-text">
                    <strong>{{ $order->delivery_address }}</strong>
                    @if($order->delivery_district || $order->delivery_city)
                        <br>{{ $order->delivery_district }}, {{ $order->delivery_city }}
                    @endif
                    @if($order->delivery_notes)
                        <div style="margin-top: 4px; font-size: 11px; color: #6b7280;">
                            <em>Patokan: {{ $order->delivery_notes }}</em>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Delivery Metadata -->
        <div class="delivery-meta-grid">
            <div class="meta-item">
                <div class="meta-label">📅 Jadwal Pengiriman:</div>
                <div class="meta-val">{{ $order->delivery_date->format('d M Y') }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">⏰ Slot Waktu:</div>
                <div class="meta-val">{{ $order->delivery_slot }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">🛵 Kurir Bertugas:</div>
                <div class="meta-val">{{ $order->delivery?->courier?->name ?? 'Kurir Desy Florist' }}</div>
            </div>
        </div>

        <!-- Greeting Card Message (if any) -->
        @if($order->greeting_message)
            <div class="greeting-box">
                <div class="box-label" style="color: #92400e;">💌 Pesan Kartu Ucapan (Wajib Disertakan pada Bunga):</div>
                <div class="greeting-text">"{{ $order->greeting_message }}"</div>
            </div>
        @endif

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Rangkaian Bunga & Konfigurasi</th>
                    <th style="width: 80px; text-align: center;">Jumlah</th>
                    <th style="width: 140px; text-align: right;">Status Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $idx => $it)
                    @php $cfg = $it->parsed_config; @endphp
                    <tr>
                        <td style="text-align: center; font-weight: 600;">{{ $idx + 1 }}</td>
                        <td>
                            <strong>{{ $it->name }}</strong>
                            @if($cfg)
                                <div style="font-size: 10px; color: #6b7280; margin-top: 2px;">
                                    @if(!empty($cfg['flowers']))
                                        Bunga: 
                                        @foreach($cfg['flowers'] as $f)
                                            {{ $f['qty'] }}x {{ $f['name'] }}{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    @endif
                                    @if(!empty($cfg['wrapping'])) • Wrapping: {{ $cfg['wrapping'] }} @endif
                                    @if(!empty($cfg['ribbon'])) • Pita: {{ $cfg['ribbon'] }} @endif
                                </div>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: 700;">{{ $it->quantity }}x</td>
                        <td style="text-align: right; font-weight: 600;">
                            {{ $order->payment_status === 'paid' ? 'LUNAS (PAID)' : strtoupper($order->payment_status_label) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer-note">
            ⚠️ PERHATIAN: RANGKAIAN BUNGA SEGAR ASLI (FRAGILE) — JAGA KESEGARAN DAN JAUHKAN DARI TERIK MATAHARI LANGSUNG
        </div>
    </div>

</body>
</html>
