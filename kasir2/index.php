<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kasir Sederhana</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f3f0ff;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #6a5acd;
            margin-bottom: 20px;
            font-size: 36px;
            text-transform: uppercase;
        }
        form {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
            transition: box-shadow 0.3s;
        }
        form:hover {
            box-shadow: 0 15px 36px rgba(0, 0, 0, 0.2);
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 500;
        }
        select, input[type="number"] {
            width: calc(100% - 20px);
            padding: 12px;
            border: 1px solid #dcdcdc;
            border-radius: 5px;
            transition: border-color 0.3s;
            margin-bottom: 15px;
        }
        select:focus, input[type="number"]:focus {
            border-color: #6a5acd;
            outline: none;
        }
        button {
            background-color: #6a5acd;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            width: 48%;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 10px;
            font-size: 16px;
        }
        button:hover {
            background-color: #483d8b;
            transform: translateY(-2px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 16px;
        }
        th {
            background-color: #6a5acd;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #e8e8ff;
        }
        tr:hover {
            background-color: #d3d3e6;
        }
        .total {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

<h2>Kasir Toko Sembako</h2>

<form action="index.php" method="POST">
    <label>Nama Barang:</label>
    <select name="nama_barang" id="nama_barang" onchange="updateHarga()">
        <option value="">Pilih Barang</option>
        <option value="Susu">Susu</option>
        <option value="Roti">Roti</option>
        <option value="Telur">Telur</option>
        <option value="Beras">Beras</option>
        <option value="Minyak Goreng">Minyak Goreng</option>
        <option value="Gula Pasir">Gula Pasir</option>
        <option value="Tepung Terigu">Tepung Terigu</option>
        <option value="Kopi">Kopi</option>
        <option value="Mie Instan">Mie Instan</option>
    </select>

    <label>Harga Barang:</label>
    <input type="number" name="harga" id="harga" readonly required>

    <label>Jumlah Barang:</label>
    <input type="number" name="jumlah" required>

    <div style="display: flex; justify-content: space-between;">
        <button type="submit" name="tambah">Tambah Barang</button>
        <button type="submit" name="reset">Reset</button>
    </div>
</form>

<?php
session_start();

// Inisialisasi session untuk menampung daftar belanjaan
if (!isset($_SESSION['belanja'])) {
    $_SESSION['belanja'] = [];
}

// Menentukan harga barang berdasarkan nama
$hargaBarang = [
    'Susu' => 15000,
    'Roti' => 10000,
    'Telur' => 20000,
    'Beras' => 10000,
    'Minyak Goreng' => 12000,
    'Gula Pasir' => 8000,
    'Tepung Terigu' => 6000,
    'Kopi' => 25000,
    'Mie Instan' => 3000,
];

// Tambah barang ke dalam daftar belanja
if (isset($_POST['tambah'])) {
    $namaBarang = $_POST['nama_barang'];
    $harga = $hargaBarang[$namaBarang];
    $jumlah = (int)$_POST['jumlah'];
    $subtotal = $harga * $jumlah;

    $_SESSION['belanja'][] = [
        'nama_barang' => $namaBarang,
        'harga' => $harga,
        'jumlah' => $jumlah,
        'subtotal' => $subtotal
    ];
}

// Reset daftar belanjaan
if (isset($_POST['reset'])) {
    $_SESSION['belanja'] = [];
}

// Hitung total dan tampilkan daftar belanjaan
if (!empty($_SESSION['belanja'])) {
    $totalHarga = 0;
    $thresholdDiskon = 100000; // Batas minimal untuk mendapatkan diskon
    $diskon = 0.1; // Diskon 10%

    echo "<table>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>";

    foreach ($_SESSION['belanja'] as $item) {
        echo "<tr>
                <td>{$item['nama_barang']}</td>
                <td>Rp " . number_format($item['harga'], 2, ',', '.') . "</td>
                <td>{$item['jumlah']}</td>
                <td>Rp " . number_format($item['subtotal'], 2, ',', '.') . "</td>
              </tr>";
        $totalHarga += $item['subtotal'];
    }

    // Terapkan diskon jika total belanja melebihi threshold
    if ($totalHarga > $thresholdDiskon) {
        $potonganDiskon = $totalHarga * $diskon;
        $totalHarga -= $potonganDiskon;
        echo "<tr>
                <td colspan='3' class='total'>Diskon 10%</td>
                <td class='total'>- Rp " . number_format($potonganDiskon, 2, ',', '.') . "</td>
              </tr>";
    }

    echo "<tr>
            <td colspan='3' class='total'>Total Bayar</td>
            <td class='total'>Rp " . number_format($totalHarga, 2, ',', '.') . "</td>
          </tr>
          </table>";
}
?>

<script>
    const hargaBarang = {
        'Susu': 15000,
        'Roti': 10000,
        'Telur': 20000,
        'Beras': 10000,
        'Minyak Goreng': 12000,
        'Gula Pasir': 8000,
        'Tepung Terigu': 6000,
        'Kopi': 25000,
        'Mie Instan': 3000
    };

    function updateHarga() {
        const namaBarang = document.getElementById('nama_barang').value;
        const hargaInput = document.getElementById('harga');

        if (namaBarang) {
            hargaInput.value = hargaBarang[namaBarang];
        } else {
            hargaInput.value = '';
        }
    }
</script>

<div class="footer">
    &copy; 2024 Aplikasi Kasir Sederhana. All Rights Reserved.
</div>

</body>
</html>