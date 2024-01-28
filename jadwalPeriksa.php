<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION['nip'])) {
        // Jika pengguna sudah login, tampilkan tombol "Logout"
        header("Location: dashboardDokter.php?page=loginUser");
        exit;
    }
?>

<main id="jadwalPeriksa-page">
    <div class="container" style="margin-top: 5.5rem;">
        <div class="row">
            <h2 class="ps-0">Jadwal Periksa</h2>
            <div class="d-flex justify-content-end mt-2">
                <a class="btn btn-sm btn-primary text-white" href="dashboardDokter.php?page=tambahJadwalPeriksa">
                    <i class="fa-regular fa-plus"></i> Tambah Jadwal Periksa
                </a>
            </div>

            <div class="table-responsive mt-3 px-0">
                <table class="table text-center">
                    <thead class="table-primary">
                        <tr>
                            <th valign="middle">No</th>
                            <th valign="middle">Nama Dokter</th>
                            <th valign="middle">Hari</th>
                            <th valign="middle">Jam Mulai</th>
                            <th valign="middle">Jam Selesai</th>
                            <th valign="middle">Status</th>
                            <th valign="middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($mysqli, "SELECT dokter.nama, jadwal_periksa.id, jadwal_periksa.hari, jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai, jadwal_periksa.status FROM dokter JOIN jadwal_periksa ON dokter.id = jadwal_periksa.id_dokter");
                            $no = 1;
                            while ($data = mysqli_fetch_array($result)) :
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $data['nama'] ?></td>
                                    <td><?php echo $data['hari'] ?></td>
                                    <td><?php echo $data['jam_mulai'] ?> WIB</td>
                                    <td><?php echo $data['jam_selesai'] ?> WIB</td>
                                    <td><?php echo $data['status'] ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-warning text-white" href="dashboardDokter.php?page=editJadwalPeriksa&id=<?php echo $data['id'] ?>">
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