<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION['nip'])) {
        // Jika pengguna sudah login, tampilkan tombol "Logout"
        header("Location: dashboardDokter.php?page=loginDokter");
        exit;
    }

    if (isset($_POST['simpanData'])) {
        $id_dokter = $_POST['id_dokter'];
        $status = $_POST['status'];
        
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $stmt = $mysqli->prepare("UPDATE jadwal_periksa SET status=? WHERE id=?");
            $stmt->bind_param("si", $status, $id);

            if ($stmt->execute()) {
                echo "
                    <script> 
                        alert('Berhasil mengubah data.');
                        document.location='dashboardDokter.php?page=jadwalPeriksa';
                    </script>
                ";
            } else {
                // Handle error
            }

            $stmt->close();
        } /* else {
            $stmt = $mysqli->prepare("INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $id_dokter, $hari, $jam_mulai, $jam_selesai);

            if ($stmt->execute()) {
                echo "
                    <script> 
                        alert('Berhasil menambah data.');
                        document.location='dashboardDokter.php?page=jadwalPeriksa';
                    </script>
                ";
            } else {
                // Handle error
            }

            $stmt->close();
        } */
    }
?>

<main id="jadwalPeriksa-page">
    <div class="container" style="margin-top: 5.5rem;">
        <div class="row">
            <h2 class="ps-0">Edit Jadwal Periksa</h2>
            <!-- <div class="d-flex justify-content-end pe-0">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahDokter">
                    <i class="fa-regular fa-plus"></i> Tambah
                </button>
            </div> -->
            <div class="container">
                <form action="" method="POST" onsubmit="return(validate());">
                    <?php
                    $id_dokter = '';
                    $hari = '';
                    $jam_mulai = '';
                    $jam_selesai = '';
                    $status = '';
                    if (isset($_GET['id'])) {
                        $get = mysqli_query($mysqli, "SELECT * FROM jadwal_periksa 
                                WHERE id='" . $_GET['id'] . "'");
                        while ($row = mysqli_fetch_array($get)) {
                            $id_dokter = $row['id_dokter'];
                            $hari = $row['hari'];
                            $jam_mulai = $row['jam_mulai'];
                            $jam_selesai = $row['jam_selesai'];
                            $status = $row['status'];
                        }
                    ?>
                        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                    <?php
                    }
                    ?>
                    <div class="dropdown mb-3 w-25">
                        <label for="hari">Hari <span class="text-danger">*</span></label>
                        <input disabled type="text" name="hari" class="form-control" required value="<?php echo $hari ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="jam_mulai">Jam Mulai <span class="text-danger">*</span></label>
                        <input disabled type="time" name="jam_mulai" class="form-control" required value="<?php echo $jam_mulai ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="jam_selesai">Jam Selesai <span class="text-danger">*</span></label>
                        <input disabled type="time" name="jam_selesai" class="form-control" required value="<?php echo $jam_selesai ?>">
                    </div>
                    <div class="dropdown mb-3 w-25">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" aria-label="status">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button type="submit" name="simpanData" class="btn btn-primary">Simpan</button>
                    </div>
    
                </form>
            </div>
        </div>
    </div>
</main>