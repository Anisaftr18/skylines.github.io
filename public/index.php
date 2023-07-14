<?php
    // Array Bandara Asal dan Tujuan
    $BandaraTujuan = ['Ngurah Rai (DPS)', 'Hasanuddin (UPG)', 'Inanwatan (INX)', 'Sultan Iskandarmuda (BTJ)'];
    $BandaraAsal = ['Soekarno-Hatta (CGK)', 'Husein SastraNegara (BDO)', 'Abdul Rachman Saleh (MLG)', 'Juanda (SUB)'];
    // Fungsi hitung total pajak
    function hitungPajak($bandaraAsal, $bandaraTujuan) {
        // Daftar Array Pajak Bandara Asal
        $pajakBandaraAsal = [
            'Soekarno-Hatta (CGK)' => 50000,
            'Husein SastraNegara (BDO)' => 30000,
            'Abdul Rachman Saleh (MLG)' => 40000,
            'Juanda (SUB)' => 40000
        ];
        // Daftar Array Pajak Bandara Asal
        $pajakBandaraTujuan = [
            'Ngurah Rai (DPS)' => 80000,
            'Hasanuddin (UPG)' => 70000,
            'Inanwatan (INX)' => 90000,
            'Sultan Iskandarmuda (BTJ)' =>  70000
        ];

        $totalPajak = $pajakBandaraAsal[$bandaraAsal] + $pajakBandaraTujuan[$bandaraTujuan];
        return $totalPajak;
    };
    // form handle
    // check request method
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [];
        $data['maskapai'] = $_POST['maskapai'];
        $data['bandaraAsal'] = $_POST['bandaraAsal'];
        $data['bandaraTujuan'] = $_POST['bandaraTujuan'];
        $data['harga'] = $_POST['harga'];
        $data['totPajak'] = hitungPajak($data['bandaraAsal'], $data['bandaraTujuan']);
        $data['totHarga'] = $data['totPajak'] + $data['harga'];
        // read file data.json
        $existData = [];
        $file = '../resource/data/data.json';
        // check file data.json
        if (file_exists($file)) {
            $existData = json_decode(file_get_contents($file), true);
        }

        // add data baru ke array existData
        $existData[] = $data;

        // sorting data by alfabet dengan ascending
        usort($existData, function($a, $b) {
            return strcmp($a['maskapai'], $b['maskapai']);
        });
        // simpan data ke file data.json
        file_put_contents($file, json_encode($existData));
        // exit session input data
        header("Location: ".$_SERVER["PHP_SELF"]);
        exit();
    };

    // read data dari file data.json
    $ExistData = [];
    $File = '../resource/data/data.json';
    // check file apakah ada
    if (file_exists($File)) {
        $ExistData = json_decode(file_get_contents($File), true);
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skylines.</title>
    <link rel="shortcut icon" href="img/logo2.png">
    <link rel="stylesheet" href="../resource/library/bootstrap-5.3.0-alpha3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body id="bg-homepage">
    <!-- Navbar -->
    <nav id="navbar">
          <input id="nav-toggle" type="checkbox">
          <img src="img/logo.png" class="logo" alt="">
          <ul id="menu">
            <li><a href="#home">Home</a></li>
            <li><a href="#rute">Rute</a></li>
            <li><a href="#about">About Me</a></li>
          </ul>
          <label for="nav-toggle" class="icon-burger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
          </label>
        </nav>
    
    <!-- Form -->
    <div class="container py-4 homepage" id="home">
        <h1 class="sub-text py-2">Discover Limitless Horizons with Skylines.</h1>
        <p class="text py-2">Daftarkan Rute Penerbangan Anda!</p>
        <div class="card py">
        <form method="POST">
            <div class="row mb-3">
                <label for="maskapai" class="col-sm-2 col-form-label">Maskapai</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" name="maskapai" id="maskapai" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="bandaraAsal" class="col-sm-2 col-form-label">Bandara Asal</label>
                <div class="col-sm-10">
                    <select name="bandaraAsal" class="form-select" aria-label="Default select example">
                        <?php foreach ($BandaraAsal as $bandara) : ?> <!--Menampilkan data array ke select option-->
                        <option value="<?= $bandara ?>"><?= $bandara ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="bandaraTujuan" class="col-sm-2 col-form-label">Bandara Tujuan</label>
                <div class="col-sm-10">
                    <select name="bandaraTujuan" class="form-select" aria-label="Default select example">
                        <?php foreach ($BandaraTujuan as $bandara) : ?> <!--Menampilkan data array ke select option-->
                        <option value="<?= $bandara ?>"><?= $bandara ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="harga" class="col-sm-2 col-form-label">Harga Tiket</label>
                <div class="col-sm-10">
                <input type="number" name="harga" class="form-control" id="harga" required>
                </div>
            </div>
            <div class="text-center d-grid gap-2 col-3 mx-auto">
                <button type="submit" class="btn">Submit</button>
            </div>
        </form>
        </div>
    </div>
    <!-- table -->
    <div id="rute"></div>
    <div class="container py-5 content table-responsive">
    <h3 class="text-center text-dark py-3 fw-bold">Daftar Rute Penerbangan</h3>
        <table class="table table-bordered table-hover table-sm">
            <thead class="text-center table-dark">
                <tr>
                <th scope="col">Maskapai</th>
                <th scope="col">Asal Penerbangan</th>
                <th scope="col">Tujuan Penerbangan</th>
                <th scope="col">Harga Tiket</th>
                <th scope="col">Pajak</th>
                <th scope="col">Total Harga Tiket</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($ExistData as $data) : ?>
            <tr class="text-center"> <!--Menampilkan data json ke table-->
                <td><?= $data['maskapai'] ?></td>
                <td><?= $data['bandaraAsal'] ?></td>
                <td><?= $data['bandaraTujuan'] ?></td>
                <td>Rp. <?= number_format($data['harga']) ?></td>
                <td>Rp. <?= number_format($data['totPajak']) ?></td>
                <td>Rp. <?= number_format($data['totHarga']) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Footer -->
    <footer id="about">
          <p>© 2023 Skylines. All rights reserved<br>create by Anisa Fitrianida</p>
                    <ul class="sosmed">
                        <li>
                            <a href="https://www.instagram.com/anisaftr.18/" target="_blank"><i class="ri-instagram-line"></i></a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/anisaftr.18/" target="_blank"><i class="ri-twitter-line"></i></a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com/nissa.fitrianida/" target="_blank"><i class="ri-facebook-box-line"></i></i></a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/channel/UCR4XM5TL6of3eSZEBix1vBw" target="_blank"><i class="ri-youtube-line"></i></a>
                        </li>
                        <li>
                            <a href="mailto:2010631170003@student.unsika.ac.id"><i class="ri-mail-line"></i></i></a>
                        </li>
                    </ul>
      </footer>

    <script src="js/script.js"></script>
    <script src="../resource/library/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>