<?php 
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION['username'])) {
        header("Location: index.php?page=loginUser");
        exit;
    }

    if (isset($_POST['simpanData'])) {
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
    }
?>

<main id="tambahDokter-page">
    <div class="container" style="margin-top: 5.5rem;">
        <div class="row">
            <h2 class="ps-0">Tambah Dokter</h2>
            <div class="container">
                <form action="" method="POST" onsubmit="return(validate());">
                    <?php
                    $nama = '';
                    $nip = '';
                    $password = '';
                    $alamat = '';
                    $no_hp = '';
                    $id_poli = '';
                    if (isset($_GET['id'])) {
                        $get = mysqli_query($mysqli, "SELECT * FROM dokter 
                                WHERE id='" . $_GET['id'] . "'");
                        while ($row = mysqli_fetch_array($get)) {
                            $nama = $row['nama'];
                            $nip = $row['nip'];
                            $password = $row['password'];
                            $alamat = $row['alamat'];
                            $no_hp = $row['no_hp'];
                            $id_poli = $row['id_poli'];
                        }
                    ?>
                        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                    <?php
                    }
                    ?>
                    <div class="mb-3 w-25">
                        <label for="nama">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama" value="<?php echo $nama ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="nip">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" class="form-control" required placeholder="Masukkan NIP" value="<?php echo $nip ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required placeholder="Masukkan password" value="<?php echo $password ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="alamat">Alamat <span class="text-danger">*</span></label>
                        <input type="text" name="alamat" class="form-control" required placeholder="Masukkan alamat" value="<?php echo $alamat ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="no_hp">No. HP <span class="text-danger">*</span></label>
                        <input type="number" name="no_hp" class="form-control" required placeholder="08xxxxxxxxxx" value="<?php echo $no_hp ?>">
                    </div>
                    <div class="dropdown mb-3 w-25">
                        <label for="id_poli">Poli <span class="text-danger">*</span></label>
                        <select class="form-select" name="id_poli" aria-label="id_poli">
                            <option selected>Pilih Poli...</option>
                            <?php
                                $result = mysqli_query($mysqli, "SELECT * FROM poli");

                                while ($data = mysqli_fetch_assoc($result)) {
                                    $selected = ($data['id'] == $id_poli) ? 'selected' : '';
                                    echo "<option $selected value='" . $data['id'] . "'>" . $data['nama_poli'] . "</option>";
                                }
                            ?>
                            <!-- <option value="1">One</option> -->
                            
                        </select>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button type="submit" name="simpanData" class="btn btn-primary">Simpan</button>
                    </div>
    
                </form>
            </div>

            <!-- <div class="table-responsive mt-3 px-0">
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
                        <php
                            $result = mysqli_query($mysqli, "SELECT * FROM dokter");
                            $no = 1;
                            while ($data = mysqli_fetch_array($result)) :
                            ?>
                                <tr>
                                    <td><php echo $no++ ?></td>
                                    <td><php echo $data['nip'] ?></td>
                                    <td><php echo $data['nama'] ?></td>
                                    <td><php echo $data['alamat'] ?></td>
                                    <td><php echo $data['no_hp'] ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-warning text-white" href="index.php?page=dokter&id=<php echo $data['id'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="index.php?page=dokter&id=<php echo $data['id'] ?>&aksi=hapus" class="btn btn-sm btn-danger text-white">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                        <php endwhile; ?>
                    </tbody>
                </table>
            </div> -->
        </div>
    </div>
</main>