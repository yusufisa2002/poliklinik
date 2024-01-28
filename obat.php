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

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM obat WHERE id = '" . $_GET['id'] . "'");

        if ($hapus) {
            echo "
                <script> 
                    alert('Berhasil menghapus data.');
                    document.location='index.php?page=obat';
                </script>
            ";
        } else {
            echo "
                <script> 
                    alert('Gagal menghapus data: " . mysqli_error($mysqli) . "');
                    document.location='index.php?page=obat';
                </script>
            ";
        }
    }
}
?>
<main id="obat-page">
    <div class="container" style="margin-top: 5.5rem;">
        <div class="row">
            <h2 class="ps-0">Daftar Obat</h2>
            <div class="d-flex justify-content-end mt-2">
                <a class="btn btn-sm btn-primary text-white" href="index.php?page=tambahObat">
                    <i class="fa-regular fa-plus"></i> Tambah Obat
                </a>
            </div>

            <div class="table-responsive mt-3 px-0">
                <table class="table text-center">
                    <thead class="table-primary">
                        <tr>
                            <th valign="middle">No</th>
                            <th valign="middle">Nama Obat</th>
                            <th valign="middle">Kemasan</th>
                            <th valign="middle">Harga</th>
                            <th valign="middle" style="width: 0.5%;" colspan="2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($mysqli, "SELECT * FROM obat");
                            $no = 1;
                            while ($data = mysqli_fetch_array($result)) :
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $data['nama_obat'] ?></td>
                                    <td><?php echo $data['kemasan'] ?></td>
                                    <td><?php echo $data['harga'] ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-warning text-white" href="index.php?page=editObat&id=<?php echo $data['id'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="index.php?page=obat&id=<?php echo $data['id'] ?>&aksi=hapus" class="btn btn-sm btn-danger text-white">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                        <?php endwhile; ?>
                    </tbody>
                </table>
                        

            </div>
        </div>
    </div>
</main>