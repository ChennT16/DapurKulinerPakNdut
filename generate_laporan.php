<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Laporan Menu - Dapur Kuliner Pak Ndut</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        h1 {
            color: #FF9800;
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 40px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .data-table thead {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
        }

        .data-table th {
            padding: 18px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table tbody tr {
            background: #fff8f0;
            border-bottom: 2px solid white;
            transition: all 0.3s;
        }

        .data-table tbody tr:nth-child(odd) {
            background: #ffe0b2;
        }

        .data-table tbody tr:hover {
            background: #ffcc80;
            transform: scale(1.01);
        }

        .data-table td {
            padding: 16px 18px;
            color: #333;
        }

        .button-container {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .btn {
            padding: 15px 40px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.05em;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 152, 0, 0.5);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 20px;
            }

            .button-container {
                display: none;
            }

            .data-table tbody tr:hover {
                transform: none;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 1.8em;
            }

            .data-table {
                font-size: 0.85em;
            }

            .data-table th,
            .data-table td {
                padding: 12px 10px;
            }

            .button-container {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Menu</h1>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody id="menuTableBody">
                <tr>
                    <td>Pentol</td>
                    <td>Pentol & Gorengan</td>
                    <td>Rp 5.000</td>
                </tr>
                <tr>
                    <td>Pentol Tahu</td>
                    <td>Pentol & Gorengan</td>
                    <td>Rp 5.000</td>
                </tr>
                <tr>
                    <td>Pentol Bakar</td>
                    <td>Pentol & Gorengan</td>
                    <td>Rp 1.000</td>
                </tr>
                <tr>
                    <td>Tahu Bakar</td>
                    <td>Pentol & Gorengan</td>
                    <td>Rp 1.000</td>
                </tr>
                <tr>
                    <td>Gorengan Pangsit</td>
                    <td>Pentol & Gorengan</td>
                    <td>Rp 1.000</td>
                </tr>
                <tr>
                    <td>Nasi Bento Ayam Katsu</td>
                    <td>Nasi Bento</td>
                    <td>Rp 10.000</td>
                </tr>
                <tr>
                    <td>Nasi Bento Ayam Crispy</td>
                    <td>Nasi Bento</td>
                    <td>Rp 10.000</td>
                </tr>
                <tr>
                    <td>Nasi Bento Ayam Geprek</td>
                    <td>Nasi Bento</td>
                    <td>Rp 10.000</td>
                </tr>
                <tr>
                    <td>Nasi Bento Beff & Sosis</td>
                    <td>Nasi Bento</td>
                    <td>Rp 10.000</td>
                </tr>
                <tr>
                    <td>Nasi Bento Rica-Rica Balungan</td>
                    <td>Nasi Bento</td>
                    <td>Rp 8.000</td>
                </tr>
                <tr>
                    <td>Nasi Bento Ati Ampela</td>
                    <td>Nasi Bento</td>
                    <td>Rp 8.000</td>
                </tr>
                <tr>
                    <td>Susu Kedelai</td>
                    <td>Minuman</td>
                    <td>Rp 6.000</td>
                </tr>
                <tr>
                    <td>Es Kuwut</td>
                    <td>Minuman</td>
                    <td>Rp 3.000</td>
                </tr>
                <tr>
                    <td>Es Rasa Rasa</td>
                    <td>Minuman</td>
                    <td>Rp 3.000</td>
                </tr>
                <tr>
                    <td>Es Teh</td>
                    <td>Minuman</td>
                    <td>Rp 3.000</td>
                </tr>
                <tr>
                    <td>Teh Lemon</td>
                    <td>Minuman</td>
                    <td>Rp 3.000</td>
                </tr>
                <tr>
                    <td>Air Mineral</td>
                    <td>Minuman</td>
                    <td>Rp 3.000</td>
                </tr>
            </tbody>
        </table>

        <div class="button-container">
            <button class="btn btn-primary" onclick="generatePDF()">
                📄 Cetak PDF
            </button>
            <button class="btn btn-secondary" onclick="goBack()">
                🔙 Kembali
            </button>
        </div>
    </div>

    <script>
        function generatePDF() {
            window.print();
        }

        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>