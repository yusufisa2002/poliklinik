<?php 
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION['username'])) {
        header("Location: index.php?page=loginUser");
        exit;
    }

    /*if (isset($_POST['simpanData'])) {
        $nama = $_POST['nama'];
        $nip = $_POST['nip'];
        $password = $_POST['password'];
        $alamat = $_POST['alamat'];
        $no_hp = $_POST['no_hp'];
        $id_poli = $_POST['id_poli'];
        
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("UPDATE dokter SET nama=?, nip=?, password=?, alamat=?, no_hp=?, id_poli=? WHERE id=?");
            $stmt->bind_param("ssssssi", $nama, $nip, $hashed_password, $alamat, $no_hp, $id_poli, $id);
            $stmt->execute();

            echo "
                <script> 
                    alert('Berhasil mengedit data.');
                    document.location='index.php?page=dokter';
                </script>
            ";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("INSERT INTO dokter (nama, nip, password, alamat, no_hp, id_poli) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $nama, $nip, $hashed_password, $alamat, $no_hp, $id_poli);
            $stmt->execute();

            echo "
                <script> 
                    alert('Berhasil menambah data.');
                    document.location='index.php?page=dokter';
                </script>
            ";
        }
    }*/

    if (isset($_GET['aksi'])) {
        if ($_GET['aksi'] == 'hapus') {
            $stmt = $mysqli->prepare("DELETE FROM dokter WHERE id = ?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "
                    <script> 
                        alert('Berhasil menghapus data.');
                        document.location='index.php?page=dokter';
                    </script>
                ";
            } else {
                echo "
                    <script> 
                        alert('Gagal menghapus data: " . mysqli_error($mysqli) . "');
                        document.location='index.php?page=dokter';
                    </script>
                ";
            }
        }
    }
?>

<main id="dokter-page">
    <div class="container" style="margin-top: 5.5rem;">
        <div class="row">
            <h2 class="ps-0">Daftar Dokter</h2>
            <div class="d-flex justify-content-end mt-2">
                <a class="btn btn-sm btn-primary text-white" href="index.php?page=tambahDokter">
                    <i class="fa-regular fa-plus"></i> Tambah Dokter
                </a>
            </div>

            <div class="table-responsive mt-3 px-0">
                <table class="table text-center">
                    <thead class="table-primary">
                        <tr>
                            <th valign="middle">No</th>
                            <th valign="middle">NIP</th>
                            <th valign="middle">Nama</th>
                            <th valign="middle">Alamat</th>
                            <th valign="middle">No Hp</th>
                            <th valign="middle" style="width: 0.5%;" colspan="2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($mysqli, "SELECT * FROM dokter");
                            $no = 1;
                            while ($data = mysqli_fetch_array($result)) :
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $data['nip'] ?></td>
                                    <td><?php echo $data['nama'] ?></td>
                                    <td><?php echo $data['alamat'] ?></td>
                                    <td><?php echo $data['no_hp'] ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-warning text-white" href="index.php?page=editDokter&id=<?php echo $data['id'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="index.php?page=dokter&id=<?php echo $data['id'] ?>&aksi=hapus" class="btn btn-sm btn-danger text-white">
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