<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    // Jika pengguna sudah login, tampilkan tombol "Logout"
    header("Location: index.php?page=loginUser");
    exit;
}

if (isset($_POST['simpanData'])) {
    $nama_obat = $_POST['nama_obat'];
    $kemasan = $_POST['kemasan'];
    $harga = $_POST['harga'];
    
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "UPDATE obat SET nama_obat='$nama_obat', kemasan='$kemasan', harga='$harga' WHERE id='$id'";
        $edit = mysqli_query($mysqli, $sql);

        echo "
            <script> 
                alert('Berhasil mengubah data.');
                document.location='index.php?page=obat';
            </script>
        ";
    } else {
        $sql = "INSERT INTO obat (nama_obat, kemasan, harga) VALUES ('$nama_obat', '$kemasan', '$harga')";
        $tambah = mysqli_query($mysqli, $sql);

        echo "
            <script> 
                alert('Berhasil menambah data.');
                document.location='index.php?page=obat';
            </script>
        ";
    }
}
?>

<main id="obat-page">
    <div class="container" style="margin-top: 5.5rem;">
        <div class="row">
            <h2 class="ps-0">Tambah Obat</h2>
            <!-- <div class="d-flex justify-content-end pe-0">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahDokter">
                    <i class="fa-regular fa-plus"></i> Tambah
                </button>
            </div> -->
            <div class="container">
                <form action="" method="POST" onsubmit="return(validate());">
                    <?php
                    $nama_obat = '';
                    $kemasan = '';
                    $harga = '';
                    if (isset($_GET['id'])) {
                        $get = mysqli_query($mysqli, "SELECT * FROM obat 
                                WHERE id='" . $_GET['id'] . "'");
                        while ($row = mysqli_fetch_array($get)) {
                            $nama_obat = $row['nama_obat'];
                            $kemasan = $row['kemasan'];
                            $harga = $row['harga'];
                        }
                    ?>
                        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                    <?php
                    }
                    ?>
                    <div class="mb-3 w-25">
                        <label for="nama_obat">Nama Obat <span class="text-danger">*</span></label>
                        <input type="text" name="nama_obat" class="form-control" required placeholder="Masukkan obat" value="<?php echo $nama_obat ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="kemasan">Kemasan <span class="text-danger">*</span></label>
                        <input type="text" name="kemasan" class="form-control" required placeholder="Masukkan kemasan" value="<?php echo $kemasan ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="harga">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="harga" class="form-control" required placeholder="Rp. " value="<?php echo $harga ?>">
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button type="submit" name="simpanData" class="btn btn-primary">Simpan</button>
                    </div>   
                </form>
            </div>
        </div>
    </div>
</main>