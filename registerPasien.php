<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $no_ktp = $_POST['no_ktp'];
    //$confirm_ktp = $_POST['confirm_ktp'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    if ($result->num_rows == 0) {
        //$hashed_ktp = password_hash($no_ktp, PASSWORD_DEFAULT);

        // Get the total number of pasien
        $result = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM pasien");
        $row = mysqli_fetch_assoc($result);
        $totalPasien = $row['total'];
        $set_jumlah_pasien = '';
            if ($totalPasien < 10){
                $set_jumlah_pasien = "00{$totalPasien}";
            }else{
                if ($totalPasien < 100){
                    $set_jumlah_pasien = "0{$totalPasien}";
                }else{
                    $set_jumlah_pasien = "{$totalPasien}";
                }
            }

        // Generate no_rm based on the current date and total number of pasien
        $no_rm = date('Ym') . '-' . (string)$set_jumlah_pasien;

        $insert_query = "INSERT INTO pasien (nama, no_ktp, alamat, no_hp, no_rm) VALUES ('$nama', '$no_ktp', '$alamat', '$no_hp', '$no_rm')";
        if (mysqli_query($mysqli, $insert_query)) {
            $_SESSION['no_rm'] = $no_rm; // Store no_rm in session
            echo "<script>
            alert('Pendaftaran Berhasil. Nomor RM Anda adalah: $no_rm'); 
            document.location='index.php?page=loginPasien';
            </script>";
        } else {
            $error = "Pendaftaran gagal";
        }
    } else {
        $error = "nama sudah digunakan";
    }
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center" style="font-weight: bold; font-size: 32px;">Register</div>
                <div class="card-body">
                    <form method="POST" action="index.php?page=registerPasien">
                        <?php
                        if (isset($error)) {
                            echo '<div class="alert alert-danger">' . $error . '
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>';
                        }
                        ?>
                        <div class="form-group">
                            <label for="nama"> Nama</label>
                            <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama anda">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required placeholder="Masukkan alamat anda">
                        </div>
                        <div class="form-group">
                            <label for="no_ktp">Nomor KTP</label>
                            <input type="no_ktp" name="no_ktp" class="form-control" required placeholder="Masukkan nomor KTP">
                        </div>
                        <div class="form-group">
                            <label for="no_hp">No.HP</label>
                            <input type="text" name="no_hp" class="form-control" required placeholder="Masukkan no_hp anda">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </form>
                    <div class="text-center">
                        <p class="mt-3">Sudah Terdaftar? Silahkan <a href="index.php?page=loginPasien">Mendaftar Poli</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>