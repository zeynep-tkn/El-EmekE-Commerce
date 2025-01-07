<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Detayları</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .order-table th, .order-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .order-table th {
            background-color: #f8f8f8;
            font-size: 16px;
        }

        .order-table td {
            font-size: 14px;
        }

        .order-table tr:hover {
            background-color: #f1f1f1;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
        }

        .status.beklemede {
            background-color: #ffc107;
        }

        .status.kargoda {
            background-color: #17a2b8;
        }

        .status.teslim-edildi {
            background-color: #28a745;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Sipariş Detayları</h1>
    <table class="order-table">
        <thead>
            <tr>
                <th>Sipariş Tarihi</th>
                <th>Sipariş Tutarı</th>
                <th>Kargo Takip No</th>
                <th>Teslimat Adresi</th>
                <th>Fatura Adresi</th>
                <th>Sipariş Durumu</th>
            </tr>
        </thead>
        
        <tbody>
            <tr>
                <td>2025-01-01</td>
                <td>150.00 TL</td>
                <td>123456789</td>
                <td>İstanbul, Türkiye</td>
                <td>İstanbul, Türkiye</td>
                <td><span class="status beklemede">Beklemede</span></td>
            </tr>
            <tr>
                <td>2025-01-02</td>
                <td>200.00 TL</td>
                <td>987654321</td>
                <td>Ankara, Türkiye</td>
                <td>Ankara, Türkiye</td>
                <td><span class="status kargoda">Kargoda</span></td>
            </tr>
            <tr>
                <td>2025-01-03</td>
                <td>250.00 TL</td>
                <td>456789123</td>
                <td>İzmir, Türkiye</td>
                <td>İzmir, Türkiye</td>
                <td><span class="status teslim-edildi">Teslim Edildi</span></td>
            </tr>
            <tr>
                <td>2025-01-01</td>
                <td>150.00 TL</td>
                <td>123456789</td>
                <td>İstanbul, Türkiye</td>
                <td>İstanbul, Türkiye</td>
                <td><span class="status beklemede">Beklemede</span></td>
            </tr>
            <tr>
                <td>2025-01-02</td>
                <td>200.00 TL</td>
                <td>987654321</td>
                <td>Ankara, Türkiye</td>
                <td>Ankara, Türkiye</td>
                <td><span class="status kargoda">Kargoda</span></td>
            </tr>
            <tr>
                <td>2025-01-03</td>
                <td>250.00 TL</td>
                <td>456789123</td>
                <td>İzmir, Türkiye</td>
                <td>İzmir, Türkiye</td>
                <td><span class="status teslim-edildi">Teslim Edildi</span></td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>