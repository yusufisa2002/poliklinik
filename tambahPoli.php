<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=loginUser");
    exit;
}

if (isset($_POST['simpanData'])) {
    $nama_poli = $_POST['nama_poli'];
    $keterangan = $_POST['keterangan'];
    
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "UPDATE poli SET nama_poli='$nama_poli', keterangan='$keterangan' WHERE id='$id'";
        $edit = mysqli_query($mysqli, $sql);

        echo "
            <script> 
                alert('Berhasil mengubah data.');
                document.location='index.php?page=poli';
            </script>
        ";
    } else {
        $sql = "INSERT INTO poli (nama_poli, keterangan) VALUES ('$nama_poli', '$keterangan')";
        $tambah = mysqli_query($mysqli, $sql);

        echo "
            <script> 
                alert('Berhasil menambah data.');
                document.location='index.php?page=poli';
            </script>
        ";
    }
}
?>

<main id="obat-page">
    <div class="container" style="margin-top: 5.5rem;">
    <div class="row">
            <h2 class="ps-0">Tambah Poli</h2>
            <div class="container">
                <form action="" method="POST" onsubmit="return(validate());">
                    <?php
                    $nama_poli = '';
                    $keterangan = '';
                    if (isset($_GET['id'])) {
                        $get = mysqli_query($mysqli, "SELECT * FROM poli 
                                WHERE id='" . $_GET['id'] . "'");
                        while ($row = mysqli_fetch_array($get)) {
                            $nama_poli = $row['nama_poli'];
                            $keterangan = $row['keterangan'];
                        }
                    ?>
                        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                    <?php
                    }
                    ?>
                    <div class="mb-3 w-25">
                        <label for="nama_poli">Nama Poli <span class="text-danger">*</span></label>
                        <input type="text" name="nama_poli" class="form-control" required placeholder="Masukkan nama poli" value="<?php echo $nama_poli ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control" required placeholder="Masukkan keterangan"><?php echo $keterangan ?></textarea>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button type="submit" name="simpanData" class="btn btn-primary">Simpan</button>
                    </div>
    
                </form>
            </div>
        </div>
    </div>
</main>