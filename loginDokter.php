<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nip = $_POST['nip'];
        $password = $_POST['password'];
    
        $query = "SELECT * FROM dokter WHERE nip = '$nip'";
        $result = $mysqli->query($query);
    
        if (!$result) {
            die("Query error: " . $mysqli->error);
        }
    
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['nip'] = $nip;
                $_SESSION['nama'] = $row['nama'];
                header("Location: dashboardDokter.php");
            } else {
                $error = "password salah";
            }
        } else {
            $error = "User tidak ditemukan";
        }
    }
?>

<main id="logindokter-page">
    <div class="container" style="margin-top: 5.5rem;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center fw-bold" style="font-size: 1.5rem;">Silahkan Login</div>
                    <div class="card-body my-4">
                        <form method="POST" action="index.php?page=loginDokter" class="ms-4">
                            <?php
                            if (isset($error)) {
                                echo '
                                    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
                                        <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                        </symbol>
                                    </svg>
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <svg class="bi flex-shrink-0 me-2" style="width: 20; height: 20;" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                        <div>
                                            ' . $error . '
                                        </div>
                                    </div>
                                ';
                            }
                            ?>
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" name="nip" class="form-control" required placeholder="Masukkan NIP anda">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" required placeholder="Masukkan password anda">
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-outline-primary px-4 btn-block">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>