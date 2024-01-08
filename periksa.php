<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_POST['simpanData'])) {
        $id_daftar_poli = $_GET['id']; // Get the id from the URL
        $id_obat = $_POST['id_obat']; // Get the id_obat value from the form
        $base_biaya_periksa = 150000;
        $tgl_periksa = date('Y-m-d H:i:s'); // Get the current datetime
        $catatan = $_POST['catatan']; // Get the catatan value from the form

        // Query the obat table to get the harga for the selected id_obat
        $result = mysqli_query($mysqli, "SELECT harga FROM obat WHERE id = '$id_obat'");
        $data = mysqli_fetch_assoc($result);
        $harga_obat = $data['harga'];

        // Calculate the total biaya_periksa
        $biaya_periksa = $base_biaya_periksa + $harga_obat;
        
        $sql = "INSERT INTO periksa (id_daftar_poli, tgl_periksa, catatan, biaya_periksa) VALUES ('$id_daftar_poli', '$tgl_periksa', '$catatan', '$biaya_periksa')";
        $tambah = mysqli_query($mysqli, $sql);

        // Get the id_periksa of the record just inserted
        $id_periksa = mysqli_insert_id($mysqli);

        // Insert into detail_periksa table
        $sql = "INSERT INTO detail_periksa (id_periksa, id_obat) VALUES ('$id_periksa', '$id_obat')";
        $tambah = mysqli_query($mysqli, $sql);

        echo "
            <script> 
                alert('Berhasil menambah data.');
                window.location.href='dashboardDokter.php?page=periksa';
            </script>
        ";
        exit();
    }
?>

<main id="periksapasien-page">
    <div class="container" style="margin-top: 5.5rem;">
        <div class="row">
            <h2 class="ps-0">Data Pasien</h2>

            <div class="container">
            <form action="" method="POST">
                    <?php
                        $id_pasien = '';
                        $id_dokter = $_SESSION['id'];
                        $nama_dokter= $_SESSION['nama'];
                        $tgl_periksa = '';
                        $catatan = '';
                        $nama_pasien = '';
                        $no_antrian = '';
                        $keluhan = '';
                        if (isset($_GET['id'])) {
                            $get = mysqli_query($mysqli, "
                                SELECT daftar_poli.*, pasien.nama AS nama
                                FROM daftar_poli
                                JOIN pasien ON daftar_poli.id_pasien = pasien.id
                                WHERE daftar_poli.id='" . $_GET['id'] . "'
                            ");
                            while ($row = mysqli_fetch_array($get)) {
                                $id_pasien = $row['id_pasien'];
                                $nama_pasien = $row['nama'];
                                $no_antrian = $row['no_antrian'];
                                $keluhan = $row['keluhan'];
                            }
                        }
                    ?>
                    <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
                    <input type="hidden" name="id_pasien" value="<?php echo $id_pasien; ?>">
                    <input type="hidden" name="id_dokter" value="<?php echo $id_dokter; ?>">
                    <div class="mb-3 w-25">
                        <label for="no_antrian">No. Antrian <span class="text-danger">*</span></label>
                        <input disabled type="text" name="no_antrian" class="form-control" required value="<?php echo $no_antrian ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="id_pasien">Nama Pasien <span class="text-danger">*</span></label>
                        <input disabled type="text" name="id_pasien" class="form-control" required value="<?php echo $nama_pasien ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="id_dokter">Nama Dokter <span class="text-danger">*</span></label>
                        <input disabled type="text" name="id_dokter" class="form-control" required value="<?php echo $nama_dokter ?>">
                    </div>
                    <div class="mb-3 w-25">
                        <label for="catatan">Catatan <span class="text-danger">*</span></label>
                        <input type="text" name="catatan" class="form-control" required value="<?php echo $catatan ?>">
                    </div>
                    <div class="dropdown mb-3 w-25">
                        <label for="id_obat">Obat <span class="text-danger">*</span></label>
                        <select class="form-select" name="id_obat" aria-label="id_obat">
                            <option value="" selected>Pilih Obat...</option>
                            <?php
                                $result = mysqli_query($mysqli, "SELECT * FROM obat");
                                
                                while ($data = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $data['id'] . "'>" . $data['nama_obat'] . "</option>";
                                }
                            ?>
                            
                        </select>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button type="submit" name="simpanData" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive mt-3 px-0">
                <table class="table text-center">
                    <thead class="table-primary">
                        <tr>
                            <th valign="middle">No</th>
                            <th valign="middle">Nama Pasien</th>
                            <th valign="middle">No. Antrian</th>
                            <th valign="middle">Keluhan</th>
                            <th valign="middle" style="width: 0.5%;" colspan="2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $id_dokter = $_SESSION['id'];
                            $result = mysqli_query($mysqli, "
                                SELECT daftar_poli.*, pasien.nama AS nama, jadwal_periksa.hari, jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai
                                FROM daftar_poli
                                JOIN (
                                    SELECT id_pasien, MAX(tanggal) as max_tanggal
                                    FROM daftar_poli
                                    GROUP BY id_pasien
                                ) as latest_poli ON daftar_poli.id_pasien = latest_poli.id_pasien AND daftar_poli.tanggal = latest_poli.max_tanggal
                                JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id 
                                JOIN pasien ON daftar_poli.id_pasien = pasien.id
                                LEFT JOIN periksa ON daftar_poli.id = periksa.id_daftar_poli
                                WHERE jadwal_periksa.id_dokter = '$id_dokter' AND periksa.id_daftar_poli IS NULL
                            ");
                            $no = 1;
                            while ($data = mysqli_fetch_array($result)) :
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $data['nama'] ?></td>
                                    <td><?php echo $data['no_antrian'] ?></td>
                                    <td><?php echo $data['keluhan'] ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-warning text-white" href="dashboardDokter.php?page=periksa&id=<?php echo $data['id'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
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