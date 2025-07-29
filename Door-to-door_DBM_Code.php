<?php
// index.php (Halaman Login)
session_start();

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
}

require_once 'config/database.php'; // Sertakan file koneksi database

$username = $password = "";
$username_err = $password_err = "";

// Proses form saat data disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validasi username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Mohon masukkan username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validasi password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Mohon masukkan password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Cek kredensial
    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = $username;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $username, $hashed_password, $role);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password benar, mulai sesi baru
                            session_start();

                            // Simpan data di sesi
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = $role; // Simpan role juga

                            // Arahkan ke halaman dashboard
                            header("Location: dashboard.php");
                        } else {
                            $password_err = "Password yang Anda masukkan salah.";
                        }
                    }
                } else {
                    $username_err = "Tidak ada akun dengan username tersebut.";
                }
            } else {
                echo "Ada yang salah. Mohon coba lagi nanti.";
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DBM Cargo</title>
    <link rel="stylesheet" href="css/style.css"> </head>
<body>
    <div class="login-container">
        <h2>Login DBM Cargo</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                <span class="error"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Login" class="btn">
            </div>
        </form>
    </div>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-group .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            display: block;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</body>
</html>

<?php
// includes/header.php
session_start();

// Cek apakah pengguna sudah login, jika tidak, arahkan ke halaman login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DBM Cargo - Dashboard</title>
    <link rel="stylesheet" href="css/style.css"> </head>
<body>
    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>DBM Cargo</h3>
            </div>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="pengiriman/input.php">Input Pengiriman</a></li>
                <li><a href="pengiriman/data.php">Data Pengiriman</a></li>
                <li><a href="barang/data.php">Data Barang</a></li>
                <li><a href="pelanggan/data.php">Data Pelanggan</a></li>
                <li><a href="kurir/data.php">Data Kurir</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>
        <div class="main-content">
            <header class="navbar">
                <p>Selamat Datang, <?php echo htmlspecialchars($_SESSION["username"]); ?> (<?php echo htmlspecialchars($_SESSION["role"]); ?>)</p>
            </header>
            <div class="content-area">
                ```

### **6. File Footer (`includes/footer.php`)**

Ini akan berisi bagian bawah halaman.

```php
<?php
// includes/footer.php
?>
            </div></div></div><style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
        }

        .wrapper {
            display: flex;
            width: 100%;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background-color: #2c3e50; /* Dark blue/grey */
            color: white;
            min-height: 100vh;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 1.8em;
            color: #ecf0f1;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav li {
            margin-bottom: 5px;
        }

        .sidebar-nav a {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: #34495e; /* Slightly lighter dark blue/grey */
            border-left: 5px solid #007bff; /* Highlight current page */
            padding-left: 15px;
        }

        /* Main Content Styling */
        .main-content {
            flex-grow: 1;
            padding: 0;
        }

        .navbar {
            background-color: #ffffff;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: flex-end; /* Align welcome message to right */
            align-items: center;
        }

        .navbar p {
            margin: 0;
            font-size: 1.1em;
            color: #333;
        }

        .content-area {
            padding: 20px;
            background-color: #ffffff;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        /* General Form Styling */
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: calc(100% - 22px); /* Account for padding and border */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box; /* Include padding and border in element's total width and height */
        }

        .form-group textarea {
            resize: vertical; /* Allow vertical resizing */
            min-height: 80px;
        }

        .form-group .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            display: block;
        }

        .btn-primary, .btn-danger, .btn-info {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none; /* For links styled as buttons */
            display: inline-block;
            margin-right: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        /* Table Styling */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .data-table th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
        }

        .data-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .data-table tr:hover {
            background-color: #ddd;
        }

        /* Utility classes */
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
    <script src="js/script.js"></script> </body>
</html>

<?php
// dashboard.php
require_once 'includes/header.php'; // Sertakan header yang sudah ada
?>

<div class="content-area">
    <h2>Dashboard</h2>
    <p>Selamat datang di Sistem Informasi Pengiriman Barang DBM Cargo.</p>
    <p>Gunakan menu di samping untuk mengelola data pengiriman, barang, pelanggan, dan kurir.</p>

    <div class="dashboard-stats">
        <div class="stat-box">
            <h3>Total Pengiriman</h3>
            <p>1250</p> </div>
        <div class="stat-box">
            <h3>Pengiriman Tertunda</h3>
            <p>50</p> </div>
        <div class="stat-box">
            <h3>Pelanggan Aktif</h3>
            <p>300</p> </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; // Sertakan footer ?>

<style>
    .dashboard-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 30px;
    }
    .stat-box {
        background-color: #e9ecef;
        border-radius: 8px;
        padding: 20px;
        flex: 1;
        min-width: 250px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .stat-box h3 {
        margin-top: 0;
        color: #333;
        font-size: 1.5em;
    }
    .stat-box p {
        font-size: 2.5em;
        font-weight: bold;
        color: #007bff;
        margin: 10px 0 0;
    }
</style>

<?php
// logout.php
session_start();

// Hapus semua variabel sesi
$_SESSION = array();

// Hancurkan sesi
session_destroy();

// Arahkan ke halaman login
header("Location: index.php");
exit;
?>

<?php
// pengiriman/input.php
require_once '../includes/header.php';
require_once '../config/database.php';

$no_resi_err = $tanggal_input_err = $nama_pengirim_err = $alamat_pengirim_err = $telepon_pengirim_err =
$nama_penerima_err = $alamat_penerima_err = $telepon_penerima_err = $jenis_layanan_err =
$tanggal_berangkat_err = $tanggal_sampai_err = $estimasi_harga_err = "";

$no_resi = $tanggal_input = $nama_pengirim = $alamat_pengirim = $telepon_pengirim =
$nama_penerima = $alamat_penerima = $telepon_penerima = $jenis_layanan =
$tanggal_berangkat = $tanggal_sampai = $estimasi_harga = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    if (empty(trim($_POST["no_resi"]))) {
        $no_resi_err = "Mohon masukkan Nomor Resi.";
    } else {
        // Cek apakah no_resi sudah ada
        $sql = "SELECT id_pengiriman FROM pengiriman WHERE no_resi = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_no_resi);
            $param_no_resi = trim($_POST["no_resi"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $no_resi_err = "Nomor Resi ini sudah terdaftar.";
                } else {
                    $no_resi = trim($_POST["no_resi"]);
                }
            } else {
                echo "Oops! Ada yang salah. Silakan coba lagi nanti.";
            }
            $stmt->close();
        }
    }

    $tanggal_input = trim($_POST["tanggal_input"]);
    $nama_pengirim = trim($_POST["nama_pengirim"]);
    $alamat_pengirim = trim($_POST["alamat_pengirim"]);
    $telepon_pengirim = trim($_POST["telepon_pengirim"]);
    $nama_penerima = trim($_POST["nama_penerima"]);
    $alamat_penerima = trim($_POST["alamat_penerima"]);
    $telepon_penerima = trim($_POST["telepon_penerima"]);
    $jenis_layanan = trim($_POST["jenis_layanan"]);
    $tanggal_berangkat = trim($_POST["tanggal_berangkat"]);
    $tanggal_sampai = trim($_POST["tanggal_sampai"]);
    $estimasi_harga = trim($_POST["estimasi_harga"]);

    // Contoh validasi sederhana (bisa diperluas)
    if (empty($tanggal_input)) $tanggal_input_err = "Tanggal Input wajib diisi.";
    if (empty($nama_pengirim)) $nama_pengirim_err = "Nama Pengirim wajib diisi.";
    if (empty($alamat_pengirim)) $alamat_pengirim_err = "Alamat Pengirim wajib diisi.";
    if (empty($telepon_pengirim)) $telepon_pengirim_err = "Telepon Pengirim wajib diisi.";
    if (empty($nama_penerima)) $nama_penerima_err = "Nama Penerima wajib diisi.";
    if (empty($alamat_penerima)) $alamat_penerima_err = "Alamat Penerima wajib diisi.";
    if (empty($telepon_penerima)) $telepon_penerima_err = "Telepon Penerima wajib diisi.";
    if (empty($jenis_layanan)) $jenis_layanan_err = "Jenis Layanan wajib diisi.";
    if (empty($tanggal_berangkat)) $tanggal_berangkat_err = "Tanggal Berangkat wajib diisi.";
    if (empty($estimasi_harga) || !is_numeric($estimasi_harga)) $estimasi_harga_err = "Estimasi Harga wajib diisi dan harus angka.";


    // Jika tidak ada error, masukkan data ke database
    if (empty($no_resi_err) && empty($tanggal_input_err) && empty($nama_pengirim_err) &&
        empty($alamat_pengirim_err) && empty($telepon_pengirim_err) && empty($nama_penerima_err) &&
        empty($alamat_penerima_err) && empty($telepon_penerima_err) && empty($jenis_layanan_err) &&
        empty($tanggal_berangkat_err) && empty($estimasi_harga_err)) {

        $sql = "INSERT INTO pengiriman (no_resi, tanggal_input, nama_pengirim, alamat_pengirim, telepon_pengirim,
                                      nama_penerima, alamat_penerima, telepon_penerima, jenis_layanan,
                                      tanggal_berangkat, tanggal_sampai, estimasi_harga)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssssssssd", $no_resi, $tanggal_input, $nama_pengirim, $alamat_pengirim, $telepon_pengirim,
                                          $nama_penerima, $alamat_penerima, $telepon_penerima, $jenis_layanan,
                                          $tanggal_berangkat, $tanggal_sampai, $estimasi_harga);

            if ($stmt->execute()) {
                echo "<script>alert('Data pengiriman berhasil ditambahkan!'); window.location.href='data.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<div class="content-area">
    <div class="form-container">
        <h2>Input Pengiriman</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="no_resi">No Resi:</label>
                <input type="text" id="no_resi" name="no_resi" value="<?php echo $no_resi; ?>">
                <span class="error"><?php echo $no_resi_err; ?></span>
            </div>
            <div class="form-group">
                <label for="tanggal_input">Tanggal Input:</label>
                <input type="date" id="tanggal_input" name="tanggal_input" value="<?php echo date('Y-m-d'); ?>" readonly>
                <span class="error"><?php echo $tanggal_input_err; ?></span>
            </div>

            <h3>Data Pengirim</h3>
            <div class="form-group">
                <label for="nama_pengirim">Nama Pengirim:</label>
                <input type="text" id="nama_pengirim" name="nama_pengirim" value="<?php echo $nama_pengirim; ?>">
                <span class="error"><?php echo $nama_pengirim_err; ?></span>
            </div>
            <div class="form-group">
                <label for="alamat_pengirim">Alamat Pengirim:</label>
                <textarea id="alamat_pengirim" name="alamat_pengirim"><?php echo $alamat_pengirim; ?></textarea>
                <span class="error"><?php echo $alamat_pengirim_err; ?></span>
            </div>
            <div class="form-group">
                <label for="telepon_pengirim">Telepon Pengirim:</label>
                <input type="text" id="telepon_pengirim" name="telepon_pengirim" value="<?php echo $telepon_pengirim; ?>">
                <span class="error"><?php echo $telepon_pengirim_err; ?></span>
            </div>

            <h3>Data Penerima</h3>
            <div class="form-group">
                <label for="nama_penerima">Nama Penerima:</label>
                <input type="text" id="nama_penerima" name="nama_penerima" value="<?php echo $nama_penerima; ?>">
                <span class="error"><?php echo $nama_penerima_err; ?></span>
            </div>
            <div class="form-group">
                <label for="alamat_penerima">Alamat Penerima:</label>
                <textarea id="alamat_penerima" name="alamat_penerima"><?php echo $alamat_penerima; ?></textarea>
                <span class="error"><?php echo $alamat_penerima_err; ?></span>
            </div>
            <div class="form-group">
                <label for="telepon_penerima">Telepon Penerima:</label>
                <input type="text" id="telepon_penerima" name="telepon_penerima" value="<?php echo $telepon_penerima; ?>">
                <span class="error"><?php echo $telepon_penerima_err; ?></span>
            </div>

            <div class="form-group">
                <label for="jenis_layanan">Jenis Layanan:</label>
                <select id="jenis_layanan" name="jenis_layanan">
                    <option value="">Pilih Jenis Layanan</option>
                    <option value="Reguler" <?php echo ($jenis_layanan == 'Reguler') ? 'selected' : ''; ?>>Reguler</option>
                    <option value="Express" <?php echo ($jenis_layanan == 'Express') ? 'selected' : ''; ?>>Express</option>
                    <option value="Same Day" <?php echo ($jenis_layanan == 'Same Day') ? 'selected' : ''; ?>>Same Day</option>
                </select>
                <span class="error"><?php echo $jenis_layanan_err; ?></span>
            </div>
            <div class="form-group">
                <label for="tanggal_berangkat">Tanggal Berangkat:</label>
                <input type="date" id="tanggal_berangkat" name="tanggal_berangkat" value="<?php echo $tanggal_berangkat; ?>">
                <span class="error"><?php echo $tanggal_berangkat_err; ?></span>
            </div>
            <div class="form-group">
                <label for="tanggal_sampai">Tanggal Sampai (Estimasi):</label>
                <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="<?php echo $tanggal_sampai; ?>">
                <span class="error"><?php echo $tanggal_sampai_err; ?></span>
            </div>
            <div class="form-group">
                <label for="estimasi_harga">Estimasi Harga (Rp):</label>
                <input type="number" id="estimasi_harga" name="estimasi_harga" step="0.01" value="<?php echo $estimasi_harga; ?>">
                <span class="error"><?php echo $estimasi_harga_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Simpan" class="btn-primary">
                <input type="reset" value="Reset" class="btn-info">
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<?php
// pengiriman/data.php
require_once '../includes/header.php';
require_once '../config/database.php';

// Ambil data pengiriman dari database
$sql = "SELECT * FROM pengiriman ORDER BY tanggal_input DESC";
$result = $conn->query($sql);

?>

<div class="content-area">
    <h2>Data Pengiriman</h2>
    <p><a href="input.php" class="btn-primary">Tambah Pengiriman Baru</a></p>

    <?php if ($result->num_rows > 0) : ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No Resi</th>
                    <th>Tgl Input</th>
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Layanan</th>
                    <th>Tgl Berangkat</th>
                    <th>Estimasi Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['no_resi']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal_input']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_pengirim']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_penerima']); ?></td>
                        <td><?php echo htmlspecialchars($row['jenis_layanan']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal_berangkat']); ?></td>
                        <td>Rp <?php echo number_format($row['estimasi_harga'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($row['status_pengiriman']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id_pengiriman']; ?>" class="btn-info">Edit</a>
                            <a href="proses.php?action=delete&id=<?php echo $row['id_pengiriman']; ?>" class="btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Tidak ada data pengiriman.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

<?php require_once '../includes/footer.php'; ?>

<?php
// pengiriman/edit.php
require_once '../includes/header.php';
require_once '../config/database.php';

$id_pengiriman = $_GET['id'] ?? null;

if (!isset($id_pengiriman)) {
    header("Location: data.php");
    exit;
}

$no_resi_err = $tanggal_input_err = $nama_pengirim_err = $alamat_pengirim_err = $telepon_pengirim_err =
$nama_penerima_err = $alamat_penerima_err = $telepon_penerima_err = $jenis_layanan_err =
$tanggal_berangkat_err = $tanggal_sampai_err = $estimasi_harga_err = $status_pengiriman_err = "";

$no_resi = $tanggal_input = $nama_pengirim = $alamat_pengirim = $telepon_pengirim =
$nama_penerima = $alamat_penerima = $telepon_penerima = $jenis_layanan =
$tanggal_berangkat = $tanggal_sampai = $estimasi_harga = $status_pengiriman = "";

// Ambil data yang akan diedit
$sql_select = "SELECT * FROM pengiriman WHERE id_pengiriman = ?";
if ($stmt_select = $conn->prepare($sql_select)) {
    $stmt_select->bind_param("i", $id_pengiriman);
    if ($stmt_select->execute()) {
        $result_select = $stmt_select->get_result();
        if ($result_select->num_rows == 1) {
            $row = $result_select->fetch_assoc();
            $no_resi = $row['no_resi'];
            $tanggal_input = $row['tanggal_input'];
            $nama_pengirim = $row['nama_pengirim'];
            $alamat_pengirim = $row['alamat_pengirim'];
            $telepon_pengirim = $row['telepon_pengirim'];
            $nama_penerima = $row['nama_penerima'];
            $alamat_penerima = $row['alamat_penerima'];
            $telepon_penerima = $row['telepon_penerima'];
            $jenis_layanan = $row['jenis_layanan'];
            $tanggal_berangkat = $row['tanggal_berangkat'];
            $tanggal_sampai = $row['tanggal_sampai'];
            $estimasi_harga = $row['estimasi_harga'];
            $status_pengiriman = $row['status_pengiriman'];
        } else {
            echo "<script>alert('Data tidak ditemukan!'); window.location.href='data.php';</script>";
            exit;
        }
    } else {
        echo "Oops! Ada yang salah. Silakan coba lagi nanti.";
    }
    $stmt_select->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input (mirip dengan input.php, tapi no_resi tidak perlu dicek unik kecuali jika diubah)
    $no_resi = trim($_POST["no_resi"]); // Tidak perlu cek unik jika tidak diubah

    $tanggal_input = trim($_POST["tanggal_input"]);
    $nama_pengirim = trim($_POST["nama_pengirim"]);
    $alamat_pengirim = trim($_POST["alamat_pengirim"]);
    $telepon_pengirim = trim($_POST["telepon_pengirim"]);
    $nama_penerima = trim($_POST["nama_penerima"]);
    $alamat_penerima = trim($_POST["alamat_penerima"]);
    $telepon_penerima = trim($_POST["telepon_penerima"]);
    $jenis_layanan = trim($_POST["jenis_layanan"]);
    $tanggal_berangkat = trim($_POST["tanggal_berangkat"]);
    $tanggal_sampai = trim($_POST["tanggal_sampai"]);
    $estimasi_harga = trim($_POST["estimasi_harga"]);
    $status_pengiriman = trim($_POST["status_pengiriman"]);


    // Contoh validasi sederhana (bisa diperluas)
    if (empty($no_resi)) $no_resi_err = "No Resi wajib diisi.";
    if (empty($tanggal_input)) $tanggal_input_err = "Tanggal Input wajib diisi.";
    if (empty($nama_pengirim)) $nama_pengirim_err = "Nama Pengirim wajib diisi.";
    if (empty($alamat_pengirim)) $alamat_pengirim_err = "Alamat Pengirim wajib diisi.";
    if (empty($telepon_pengirim)) $telepon_pengirim_err = "Telepon Pengirim wajib diisi.";
    if (empty($nama_penerima)) $nama_penerima_err = "Nama Penerima wajib diisi.";
    if (empty($alamat_penerima)) $alamat_penerima_err = "Alamat Penerima wajib diisi.";
    if (empty($telepon_penerima)) $telepon_penerima_err = "Telepon Penerima wajib diisi.";
    if (empty($jenis_layanan)) $jenis_layanan_err = "Jenis Layanan wajib diisi.";
    if (empty($tanggal_berangkat)) $tanggal_berangkat_err = "Tanggal Berangkat wajib diisi.";
    if (empty($estimasi_harga) || !is_numeric($estimasi_harga)) $estimasi_harga_err = "Estimasi Harga wajib diisi dan harus angka.";
    if (empty($status_pengiriman)) $status_pengiriman_err = "Status Pengiriman wajib diisi.";


    // Jika tidak ada error, update data di database
    if (empty($no_resi_err) && empty($tanggal_input_err) && empty($nama_pengirim_err) &&
        empty($alamat_pengirim_err) && empty($telepon_pengirim_err) && empty($nama_penerima_err) &&
        empty($alamat_penerima_err) && empty($telepon_penerima_err) && empty($jenis_layanan_err) &&
        empty($tanggal_berangkat_err) && empty($estimasi_harga_err) && empty($status_pengiriman_err)) {

        $sql_update = "UPDATE pengiriman SET no_resi=?, tanggal_input=?, nama_pengirim=?, alamat_pengirim=?, telepon_pengirim=?,
                                             nama_penerima=?, alamat_penerima=?, telepon_penerima=?, jenis_layanan=?,
                                             tanggal_berangkat=?, tanggal_sampai=?, estimasi_harga=?, status_pengiriman=?
                       WHERE id_pengiriman = ?";

        if ($stmt_update = $conn->prepare($sql_update)) {
            $stmt_update->bind_param("ssssssssssdsi", $no_resi, $tanggal_input, $nama_pengirim, $alamat_pengirim, $telepon_pengirim,
                                                  $nama_penerima, $alamat_penerima, $telepon_penerima, $jenis_layanan,
                                                  $tanggal_berangkat, $tanggal_sampai, $estimasi_harga, $status_pengiriman, $id_pengiriman);

            if ($stmt_update->execute()) {
                echo "<script>alert('Data pengiriman berhasil diperbarui!'); window.location.href='data.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt_update->error . "');</script>";
            }
            $stmt_update->close();
        }
    }
}
?>

<div class="content-area">
    <div class="form-container">
        <h2>Edit Pengiriman</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id_pengiriman; ?>" method="post">
            <div class="form-group">
                <label for="no_resi">No Resi:</label>
                <input type="text" id="no_resi" name="no_resi" value="<?php echo htmlspecialchars($no_resi); ?>">
                <span class="error"><?php echo $no_resi_err; ?></span>
            </div>
            <div class="form-group">
                <label for="tanggal_input">Tanggal Input:</label>
                <input type="date" id="tanggal_input" name="tanggal_input" value="<?php echo htmlspecialchars($tanggal_input); ?>">
                <span class="error"><?php echo $tanggal_input_err; ?></span>
            </div>

            <h3>Data Pengirim</h3>
            <div class="form-group">
                <label for="nama_pengirim">Nama Pengirim:</label>
                <input type="text" id="nama_pengirim" name="nama_pengirim" value="<?php echo htmlspecialchars($nama_pengirim); ?>">
                <span class="error"><?php echo $nama_pengirim_err; ?></span>
            </div>
            <div class="form-group">
                <label for="alamat_pengirim">Alamat Pengirim:</label>
                <textarea id="alamat_pengirim" name="alamat_pengirim"><?php echo htmlspecialchars($alamat_pengirim); ?></textarea>
                <span class="error"><?php echo $alamat_pengirim_err; ?></span>
            </div>
            <div class="form-group">
                <label for="telepon_pengirim">Telepon Pengirim:</label>
                <input type="text" id="telepon_pengirim" name="telepon_pengirim" value="<?php echo htmlspecialchars($telepon_pengirim); ?>">
                <span class="error"><?php echo $telepon_pengirim_err; ?></span>
            </div>

            <h3>Data Penerima</h3>
            <div class="form-group">
                <label for="nama_penerima">Nama Penerima:</label>
                <input type="text" id="nama_penerima" name="nama_penerima" value="<?php echo htmlspecialchars($nama_penerima); ?>">
                <span class="error"><?php echo $nama_penerima_err; ?></span>
            </div>
            <div class="form-group">
                <label for="alamat_penerima">Alamat Penerima:</label>
                <textarea id="alamat_penerima" name="alamat_penerima"><?php echo htmlspecialchars($alamat_penerima); ?></textarea>
                <span class="error"><?php echo $alamat_penerima_err; ?></span>
            </div>
            <div class="form-group">
                <label for="telepon_penerima">Telepon Penerima:</label>
                <input type="text" id="telepon_penerima" name="telepon_penerima" value="<?php echo htmlspecialchars($telepon_penerima); ?>">
                <span class="error"><?php echo $telepon_penerima_err; ?></span>
            </div>

            <div class="form-group">
                <label for="jenis_layanan">Jenis Layanan:</label>
                <select id="jenis_layanan" name="jenis_layanan">
                    <option value="">Pilih Jenis Layanan</option>
                    <option value="Reguler" <?php echo ($jenis_layanan == 'Reguler') ? 'selected' : ''; ?>>Reguler</option>
                    <option value="Express" <?php echo ($jenis_layanan == 'Express') ? 'selected' : ''; ?>>Express</option>
                    <option value="Same Day" <?php echo ($jenis_layanan == 'Same Day') ? 'selected' : ''; ?>>Same Day</option>
                </select>
                <span class="error"><?php echo $jenis_layanan_err; ?></span>
            </div>
            <div class="form-group">
                <label for="tanggal_berangkat">Tanggal Berangkat:</label>
                <input type="date" id="tanggal_berangkat" name="tanggal_berangkat" value="<?php echo htmlspecialchars($tanggal_berangkat); ?>">
                <span class="error"><?php echo $tanggal_berangkat_err; ?></span>
            </div>
            <div class="form-group">
                <label for="tanggal_sampai">Tanggal Sampai (Estimasi):</label>
                <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="<?php echo htmlspecialchars($tanggal_sampai); ?>">
                <span class="error"><?php echo $tanggal_sampai_err; ?></span>
            </div>
            <div class="form-group">
                <label for="estimasi_harga">Estimasi Harga (Rp):</label>
                <input type="number" id="estimasi_harga" name="estimasi_harga" step="0.01" value="<?php echo htmlspecialchars($estimasi_harga); ?>">
                <span class="error"><?php echo $estimasi_harga_err; ?></span>
            </div>
            <div class="form-group">
                <label for="status_pengiriman">Status Pengiriman:</label>
                <select id="status_pengiriman" name="status_pengiriman">
                    <option value="Pending" <?php echo ($status_pengiriman == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Dalam Perjalanan" <?php echo ($status_pengiriman == 'Dalam Perjalanan') ? 'selected' : ''; ?>>Dalam Perjalanan</option>
                    <option value="Delivered" <?php echo ($status_pengiriman == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                    <option value="Dibatalkan" <?php echo ($status_pengiriman == 'Dibatalkan') ? 'selected' : ''; ?>>Dibatalkan</option>
                </select>
                <span class="error"><?php echo $status_pengiriman_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Update" class="btn-primary">
                <a href="data.php" class="btn-info">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php $conn->close(); ?>
<?php require_once '../includes/footer.php'; ?>

<?php
// pengiriman/proses.php
require_once '../config/database.php';
session_start();

// Cek apakah pengguna sudah login dan punya hak akses (misal: admin)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}
// Opsional: Batasi hanya untuk admin
// if ($_SESSION['role'] !== 'admin') {
//     header("Location: ../dashboard.php"); // Atau halaman error
//     exit;
// }


if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_pengiriman = $_GET['id'];

    $sql = "DELETE FROM pengiriman WHERE id_pengiriman = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_pengiriman);
        if ($stmt->execute()) {
            echo "<script>alert('Data pengiriman berhasil dihapus!'); window.location.href='data.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='data.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href='data.php';</script>";
    }
    $conn->close();
    exit;
} else {
    // Jika akses tidak valid
    header("Location: data.php");
    exit;
}
?>

<?php
// barang/input.php
require_once '../includes/header.php';
require_once '../config/database.php';

$no_resi_err = $nama_barang_err = $berat_barang_err = $jenis_barang_err = "";

$no_resi = $nama_barang = $berat_barang = $jenis_barang = $dimensi_barang = $isi_barang = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    if (empty(trim($_POST["no_resi"]))) {
        $no_resi_err = "Mohon masukkan Nomor Resi.";
    } else {
        $no_resi = trim($_POST["no_resi"]);
        // Opsional: Validasi no_resi harus sudah ada di tabel pengiriman
        $sql_check_resi = "SELECT no_resi FROM pengiriman WHERE no_resi = ?";
        if ($stmt_check = $conn->prepare($sql_check_resi)) {
            $stmt_check->bind_param("s", $no_resi);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows == 0) {
                $no_resi_err = "Nomor Resi tidak ditemukan di data pengiriman.";
            }
            $stmt_check->close();
        }
    }

    $nama_barang = trim($_POST["nama_barang"]);
    $berat_barang = trim($_POST["berat_barang"]);
    $jenis_barang = trim($_POST["jenis_barang"]);
    $dimensi_barang = trim($_POST["dimensi_barang"]);
    $isi_barang = trim($_POST["isi_barang"]);

    // Contoh validasi sederhana
    if (empty($nama_barang)) $nama_barang_err = "Nama Barang wajib diisi.";
    if (empty($berat_barang) || !is_numeric($berat_barang)) $berat_barang_err = "Berat Barang wajib diisi dan harus angka.";
    if (empty($jenis_barang)) $jenis_barang_err = "Jenis Barang wajib diisi.";


    // Jika tidak ada error, masukkan data ke database
    if (empty($no_resi_err) && empty($nama_barang_err) && empty($berat_barang_err) && empty($jenis_barang_err)) {

        $sql = "INSERT INTO barang (no_resi, nama_barang, berat_barang, jenis_barang, dimensi_barang, isi_barang)
                VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssdiss", $no_resi, $nama_barang, $berat_barang, $jenis_barang, $dimensi_barang, $isi_barang);

            if ($stmt->execute()) {
                echo "<script>alert('Data barang berhasil ditambahkan!'); window.location.href='data.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<div class="content-area">
    <div class="form-container">
        <h2>Input Data Barang</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="no_resi">No Resi:</label>
                <input type="text" id="no_resi" name="no_resi" value="<?php echo $no_resi; ?>">
                <span class="error"><?php echo $no_resi_err; ?></span>
            </div>
            <div class="form-group">
                <label for="nama_barang">Nama Barang:</label>
                <input type="text" id="nama_barang" name="nama_barang" value="<?php echo $nama_barang; ?>">
                <span class="error"><?php echo $nama_barang_err; ?></span>
            </div>
            <div class="form-group">
                <label for="berat_barang">Berat Barang (kg):</label>
                <input type="number" id="berat_barang" name="berat_barang" step="0.01" value="<?php echo $berat_barang; ?>">
                <span class="error"><?php echo $berat_barang_err; ?></span>
            </div>
            <div class="form-group">
                <label for="jenis_barang">Jenis Barang:</label>
                <input type="text" id="jenis_barang" name="jenis_barang" value="<?php echo $jenis_barang; ?>">
                <span class="error"><?php echo $jenis_barang_err; ?></span>
            </div>
            <div class="form-group">
                <label for="dimensi_barang">Dimensi Barang (P x L x T cm):</label>
                <input type="text" id="dimensi_barang" name="dimensi_barang" value="<?php echo $dimensi_barang; ?>">
            </div>
            <div class="form-group">
                <label for="isi_barang">Isi Barang:</label>
                <textarea id="isi_barang" name="isi_barang"><?php echo $isi_barang; ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="Simpan" class="btn-primary">
                <input type="reset" value="Reset" class="btn-info">
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<?php
// barang/data.php
require_once '../includes/header.php';
require_once '../config/database.php';

// Ambil data barang dari database
// Menggabungkan dengan tabel pengiriman untuk menampilkan nama pengirim/penerima jika diperlukan
$sql = "SELECT b.*, p.nama_pengirim, p.nama_penerima FROM barang b
        JOIN pengiriman p ON b.no_resi = p.no_resi
        ORDER BY b.id_barang DESC";
$result = $conn->query($sql);

?>

<div class="content-area">
    <h2>Data Barang</h2>
    <p><a href="input.php" class="btn-primary">Tambah Data Barang Baru</a></p>

    <?php if ($result->num_rows > 0) : ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No Resi</th>
                    <th>Nama Barang</th>
                    <th>Berat (kg)</th>
                    <th>Jenis Barang</th>
                    <th>Dimensi</th>
                    <th>Isi Barang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['no_resi']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                        <td><?php echo htmlspecialchars($row['berat_barang']); ?></td>
                        <td><?php echo htmlspecialchars($row['jenis_barang']); ?></td>
                        <td><?php echo htmlspecialchars($row['dimensi_barang']); ?></td>
                        <td><?php echo htmlspecialchars($row['isi_barang']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id_barang']; ?>" class="btn-info">Edit</a>
                            <a href="proses.php?action=delete&id=<?php echo $row['id_barang']; ?>" class="btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Tidak ada data barang.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

<?php require_once '../includes/footer.php'; ?>

<?php
// barang/edit.php
require_once '../includes/header.php';
require_once '../config/database.php';

$id_barang = $_GET['id'] ?? null;

if (!isset($id_barang)) {
    header("Location: data.php");
    exit;
}

$no_resi_err = $nama_barang_err = $berat_barang_err = $jenis_barang_err = "";

$no_resi = $nama_barang = $berat_barang = $jenis_barang = $dimensi_barang = $isi_barang = "";

// Ambil data yang akan diedit
$sql_select = "SELECT * FROM barang WHERE id_barang = ?";
if ($stmt_select = $conn->prepare($sql_select)) {
    $stmt_select->bind_param("i", $id_barang);
    if ($stmt_select->execute()) {
        $result_select = $stmt_select->get_result();
        if ($result_select->num_rows == 1) {
            $row = $result_select->fetch_assoc();
            $no_resi = $row['no_resi'];
            $nama_barang = $row['nama_barang'];
            $berat_barang = $row['berat_barang'];
            $jenis_barang = $row['jenis_barang'];
            $dimensi_barang = $row['dimensi_barang'];
            $isi_barang = $row['isi_barang'];
        } else {
            echo "<script>alert('Data tidak ditemukan!'); window.location.href='data.php';</script>";
            exit;
        }
    } else {
        echo "Oops! Ada yang salah. Silakan coba lagi nanti.";
    }
    $stmt_select->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    $no_resi = trim($_POST["no_resi"]);
    $nama_barang = trim($_POST["nama_barang"]);
    $berat_barang = trim($_POST["berat_barang"]);
    $jenis_barang = trim($_POST["jenis_barang"]);
    $dimensi_barang = trim($_POST["dimensi_barang"]);
    $isi_barang = trim($_POST["isi_barang"]);

    // Contoh validasi sederhana
    if (empty($no_resi)) $no_resi_err = "No Resi wajib diisi.";
    if (empty($nama_barang)) $nama_barang_err = "Nama Barang wajib diisi.";
    if (empty($berat_barang) || !is_numeric($berat_barang)) $berat_barang_err = "Berat Barang wajib diisi dan harus angka.";
    if (empty($jenis_barang)) $jenis_barang_err = "Jenis Barang wajib diisi.";

    // Opsional: Validasi no_resi harus sudah ada di tabel pengiriman (jika diubah)
    if (!empty($no_resi) && $no_resi != $row['no_resi']) { // Check if no_resi changed
        $sql_check_resi = "SELECT no_resi FROM pengiriman WHERE no_resi = ?";
        if ($stmt_check = $conn->prepare($sql_check_resi)) {
            $stmt_check->bind_param("s", $no_resi);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows == 0) {
                $no_resi_err = "Nomor Resi tidak ditemukan di data pengiriman.";
            }
            $stmt_check->close();
        }
    }


    // Jika tidak ada error, update data di database
    if (empty($no_resi_err) && empty($nama_barang_err) && empty($berat_barang_err) && empty($jenis_barang_err)) {

        $sql_update = "UPDATE barang SET no_resi=?, nama_barang=?, berat_barang=?, jenis_barang=?, dimensi_barang=?, isi_barang=?
                       WHERE id_barang = ?";

        if ($stmt_update = $conn->prepare($sql_update)) {
            $stmt_update->bind_param("ssdissi", $no_resi, $nama_barang, $berat_barang, $jenis_barang, $dimensi_barang, $isi_barang, $id_barang);

            if ($stmt_update->execute()) {
                echo "<script>alert('Data barang berhasil diperbarui!'); window.location.href='data.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt_update->error . "');</script>";
            }
            $stmt_update->close();
        }
    }
}
?>

<div class="content-area">
    <div class="form-container">
        <h2>Edit Data Barang</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id_barang; ?>" method="post">
            <div class="form-group">
                <label for="no_resi">No Resi:</label>
                <input type="text" id="no_resi" name="no_resi" value="<?php echo htmlspecialchars($no_resi); ?>">
                <span class="error"><?php echo $no_resi_err; ?></span>
            </div>
            <div class="form-group">
                <label for="nama_barang">Nama Barang:</label>
                <input type="text" id="nama_barang" name="nama_barang" value="<?php echo htmlspecialchars($nama_barang); ?>">
                <span class="error"><?php echo $nama_barang_err; ?></span>
            </div>
            <div class="form-group">
                <label for="berat_barang">Berat Barang (kg):</label>
                <input type="number" id="berat_barang" name="berat_barang" step="0.01" value="<?php echo htmlspecialchars($berat_barang); ?>">
                <span class="error"><?php echo $berat_barang_err; ?></span>
            </div>
            <div class="form-group">
                <label for="jenis_barang">Jenis Barang:</label>
                <input type="text" id="jenis_barang" name="jenis_barang" value="<?php echo htmlspecialchars($jenis_barang); ?>">
                <span class="error"><?php echo $jenis_barang_err; ?></span>
            </div>
            <div class="form-group">
                <label for="dimensi_barang">Dimensi Barang (P x L x T cm):</label>
                <input type="text" id="dimensi_barang" name="dimensi_barang" value="<?php echo htmlspecialchars($dimensi_barang); ?>">
            </div>
            <div class="form-group">
                <label for="isi_barang">Isi Barang:</label>
                <textarea id="isi_barang" name="isi_barang"><?php echo htmlspecialchars($isi_barang); ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="Update" class="btn-primary">
                <a href="data.php" class="btn-info">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php $conn->close(); ?>
<?php require_once '../includes/footer.php'; ?>

<?php
// barang/proses.php
require_once '../config/database.php';
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_barang = $_GET['id'];

    $sql = "DELETE FROM barang WHERE id_barang = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_barang);
        if ($stmt->execute()) {
            echo "<script>alert('Data barang berhasil dihapus!'); window.location.href='data.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='data.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href='data.php';</script>";
    }
    $conn->close();
    exit;
} else {
    // Jika akses tidak valid
    header("Location: data.php");
    exit;
}
?>

<?php
// pelanggan/input.php
require_once '../includes/header.php';
require_once '../config/database.php';

$id_pelanggan_err = $nama_pelanggan_err = $alamat_pelanggan_err = $telepon_pelanggan_err = $email_pelanggan_err = "";

$id_pelanggan = $nama_pelanggan = $alamat_pelanggan = $telepon_pelanggan = $email_pelanggan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    if (empty(trim($_POST["id_pelanggan"]))) {
        $id_pelanggan_err = "Mohon masukkan ID Pelanggan.";
    } else {
        // Cek apakah ID Pelanggan sudah ada
        $sql = "SELECT id_pelanggan FROM pelanggan WHERE id_pelanggan = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_id_pelanggan);
            $param_id_pelanggan = trim($_POST["id_pelanggan"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $id_pelanggan_err = "ID Pelanggan ini sudah terdaftar.";
                } else {
                    $id_pelanggan = trim($_POST["id_pelanggan"]);
                }
            } else {
                echo "Oops! Ada yang salah. Silakan coba lagi nanti.";
            }
            $stmt->close();
        }
    }

    $nama_pelanggan = trim($_POST["nama_pelanggan"]);
    $alamat_pelanggan = trim($_POST["alamat_pelanggan"]);
    $telepon_pelanggan = trim($_POST["telepon_pelanggan"]);
    $email_pelanggan = trim($_POST["email_pelanggan"]);

    // Contoh validasi sederhana
    if (empty($nama_pelanggan)) $nama_pelanggan_err = "Nama Pelanggan wajib diisi.";
    if (empty($alamat_pelanggan)) $alamat_pelanggan_err = "Alamat Pelanggan wajib diisi.";
    if (empty($telepon_pelanggan)) $telepon_pelanggan_err = "Telepon Pelanggan wajib diisi.";
    if (!empty($email_pelanggan) && !filter_var($email_pelanggan, FILTER_VALIDATE_EMAIL)) $email_pelanggan_err = "Format Email tidak valid.";


    // Jika tidak ada error, masukkan data ke database
    if (empty($id_pelanggan_err) && empty($nama_pelanggan_err) && empty($alamat_pelanggan_err) && empty($telepon_pelanggan_err) && empty($email_pelanggan_err)) {

        $sql = "INSERT INTO pelanggan (id_pelanggan, nama_pelanggan, alamat_pelanggan, telepon_pelanggan, email_pelanggan)
                VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssss", $id_pelanggan, $nama_pelanggan, $alamat_pelanggan, $telepon_pelanggan, $email_pelanggan);

            if ($stmt->execute()) {
                echo "<script>alert('Data pelanggan berhasil ditambahkan!'); window.location.href='data.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<div class="content-area">
    <div class="form-container">
        <h2>Input Data Pelanggan</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="id_pelanggan">ID Pelanggan:</label>
                <input type="text" id="id_pelanggan" name="id_pelanggan" value="<?php echo $id_pelanggan; ?>">
                <span class="error"><?php echo $id_pelanggan_err; ?></span>
            </div>
            <div class="form-group">
                <label for="nama_pelanggan">Nama Pelanggan:</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="<?php echo $nama_pelanggan; ?>">
                <span class="error"><?php echo $nama_pelanggan_err; ?></span>
            </div>
            <div class="form-group">
                <label for="alamat_pelanggan">Alamat Pelanggan:</label>
                <textarea id="alamat_pelanggan" name="alamat_pelanggan"><?php echo $alamat_pelanggan; ?></textarea>
                <span class="error"><?php echo $alamat_pelanggan_err; ?></span>
            </div>
            <div class="form-group">
                <label for="telepon_pelanggan">Telepon Pelanggan:</label>
                <input type="text" id="telepon_pelanggan" name="telepon_pelanggan" value="<?php echo $telepon_pelanggan; ?>">
                <span class="error"><?php echo $telepon_pelanggan_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email_pelanggan">Email Pelanggan:</label>
                <input type="text" id="email_pelanggan" name="email_pelanggan" value="<?php echo $email_pelanggan; ?>">
                <span class="error"><?php echo $email_pelanggan_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Simpan" class="btn-primary">
                <input type="reset" value="Reset" class="btn-info">
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<?php
// pelanggan/data.php
require_once '../includes/header.php';
require_once '../config/database.php';

// Ambil data pelanggan dari database
$sql = "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC";
$result = $conn->query($sql);

?>

<div class="content-area">
    <h2>Data Pelanggan</h2>
    <p><a href="input.php" class="btn-primary">Tambah Data Pelanggan Baru</a></p>

    <?php if ($result->num_rows > 0) : ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['id_pelanggan']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_pelanggan']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat_pelanggan']); ?></td>
                        <td><?php echo htmlspecialchars($row['telepon_pelanggan']); ?></td>
                        <td><?php echo htmlspecialchars($row['email_pelanggan']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo urlencode($row['id_pelanggan']); ?>" class="btn-info">Edit</a>
                            <a href="proses.php?action=delete&id=<?php echo urlencode($row['id_pelanggan']); ?>" class="btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Tidak ada data pelanggan.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

<?php require_once '../includes/footer.php'; ?>

<?php
// pelanggan/edit.php
require_once '../includes/header.php';
require_once '../config/database.php';

$id_pelanggan_get = $_GET['id'] ?? null;

if (!isset($id_pelanggan_get)) {
    header("Location: data.php");
    exit;
}

$id_pelanggan_err = $nama_pelanggan_err = $alamat_pelanggan_err = $telepon_pelanggan_err = $email_pelanggan_err = "";

$id_pelanggan = $nama_pelanggan = $alamat_pelanggan = $telepon_pelanggan = $email_pelanggan = "";

// Ambil data yang akan diedit
$sql_select = "SELECT * FROM pelanggan WHERE id_pelanggan = ?";
if ($stmt_select = $conn->prepare($sql_select)) {
    $stmt_select->bind_param("s", $id_pelanggan_get);
    if ($stmt_select->execute()) {
        $result_select = $stmt_select->get_result();
        if ($result_select->num_rows == 1) {
            $row = $result_select->fetch_assoc();
            $id_pelanggan = $row['id_pelanggan'];
            $nama_pelanggan = $row['nama_pelanggan'];
            $alamat_pelanggan = $row['alamat_pelanggan'];
            $telepon_pelanggan = $row['telepon_pelanggan'];
            $email_pelanggan = $row['email_pelanggan'];
        } else {
            echo "<script>alert('Data tidak ditemukan!'); window.location.href='data.php';</script>";
            exit;
        }
    } else {
        echo "Oops! Ada yang salah. Silakan coba lagi nanti.";
    }
    $stmt_select->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    $new_id_pelanggan = trim($_POST["id_pelanggan"]);
    $nama_pelanggan = trim($_POST["nama_pelanggan"]);
    $alamat_pelanggan = trim($_POST["alamat_pelanggan"]);
    $telepon_pelanggan = trim($_POST["telepon_pelanggan"]);
    $email_pelanggan = trim($_POST["email_pelanggan"]);

    // Cek ID Pelanggan jika diubah
    if (empty($new_id_pelanggan)) {
        $id_pelanggan_err = "Mohon masukkan ID Pelanggan.";
    } elseif ($new_id_pelanggan !== $id_pelanggan_get) { // Jika ID diubah
        $sql = "SELECT id_pelanggan FROM pelanggan WHERE id_pelanggan = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $new_id_pelanggan);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $id_pelanggan_err = "ID Pelanggan ini sudah terdaftar.";
                }
            }
            $stmt->close();
        }
    }

    // Contoh validasi sederhana
    if (empty($nama_pelanggan)) $nama_pelanggan_err = "Nama Pelanggan wajib diisi.";
    if (empty($alamat_pelanggan)) $alamat_pelanggan_err = "Alamat Pelanggan wajib diisi.";
    if (empty($telepon_pelanggan)) $telepon_pelanggan_err = "Telepon Pelanggan wajib diisi.";
    if (!empty($email_pelanggan) && !filter_var($email_pelanggan, FILTER_VALIDATE_EMAIL)) $email_pelanggan_err = "Format Email tidak valid.";


    // Jika tidak ada error, update data di database
    if (empty($id_pelanggan_err) && empty($nama_pelanggan_err) && empty($alamat_pelanggan_err) && empty($telepon_pelanggan_err) && empty($email_pelanggan_err)) {

        $sql_update = "UPDATE pelanggan SET id_pelanggan=?, nama_pelanggan=?, alamat_pelanggan=?, telepon_pelanggan=?, email_pelanggan=?
                       WHERE id_pelanggan = ?";

        if ($stmt_update = $conn->prepare($sql_update)) {
            $stmt_update->bind_param("ssssss", $new_id_pelanggan, $nama_pelanggan, $alamat_pelanggan, $telepon_pelanggan, $email_pelanggan, $id_pelanggan_get);

            if ($stmt_update->execute()) {
                echo "<script>alert('Data pelanggan berhasil diperbarui!'); window.location.href='data.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt_update->error . "');</script>";
            }
            $stmt_update->close();
        }
    }
}
?>

<div class="content-area">
    <div class="form-container">
        <h2>Edit Data Pelanggan</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . urlencode($id_pelanggan_get); ?>" method="post">
            <div class="form-group">
                <label for="id_pelanggan">ID Pelanggan:</label>
                <input type="text" id="id_pelanggan" name="id_pelanggan" value="<?php echo htmlspecialchars($id_pelanggan); ?>">
                <span class="error"><?php echo $id_pelanggan_err; ?></span>
            </div>
            <div class="form-group">
                <label for="nama_pelanggan">Nama Pelanggan:</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="<?php echo htmlspecialchars($nama_pelanggan); ?>">
                <span class="error"><?php echo $nama_pelanggan_err; ?></span>
            </div>
            <div class="form-group">
                <label for="alamat_pelanggan">Alamat Pelanggan:</label>
                <textarea id="alamat_pelanggan" name="alamat_pelanggan"><?php echo htmlspecialchars($alamat_pelanggan); ?></textarea>
                <span class="error"><?php echo $alamat_pelanggan_err; ?></span>
            </div>
            <div class="form-group">
                <label for="telepon_pelanggan">Telepon Pelanggan:</label>
                <input type="text" id="telepon_pelanggan" name="telepon_pelanggan" value="<?php echo htmlspecialchars($telepon_pelanggan); ?>">
                <span class="error"><?php echo $telepon_pelanggan_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email_pelanggan">Email Pelanggan:</label>
                <input type="text" id="email_pelanggan" name="email_pelanggan" value="<?php echo htmlspecialchars($email_pelanggan); ?>">
                <span class="error"><?php echo $email_pelanggan_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Update" class="btn-primary">
                <a href="data.php" class="btn-info">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php $conn->close(); ?>
<?php require_once '../includes/footer.php'; ?>

<?php
// pelanggan/proses.php
require_once '../config/database.php';
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_pelanggan = $_GET['id'];

    $sql = "DELETE FROM pelanggan WHERE id_pelanggan = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $id_pelanggan);
        if ($stmt->execute()) {
            echo "<script>alert('Data pelanggan berhasil dihapus!'); window.location.href='data.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='data.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href='data.php';</script>";
    }
    $conn->close();
    exit;
} else {
    // Jika akses tidak valid
    header("Location: data.php");
    exit;
}
?>

<?php
// kurir/input.php
require_once '../includes/header.php';
require_once '../config/database.php';

$id_kurir_err = $nama_kurir_err = $alamat_kurir_err = $telepon_kurir_err = $email_kurir_err = $tanggal_lahir_err = $jenis_kelamin_err = "";

$id_kurir = $nama_kurir = $alamat_kurir = $telepon_kurir = $email_kurir = $tanggal_lahir = $jenis_kelamin = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    if (empty(trim($_POST["id_kurir"]))) {
        $id_kurir_err = "Mohon masukkan ID Kurir.";
    } else {
        // Cek apakah ID Kurir sudah ada
        $sql = "SELECT id_kurir FROM kurir WHERE id_kurir = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_id_kurir);
            $param_id_kurir = trim($_POST["id_kurir"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $id_kurir_err = "ID Kurir ini sudah terdaftar.";
                } else {
                    $id_kurir = trim($_POST["id_kurir"]);
                }
            } else {
                echo "Oops! Ada yang salah. Silakan coba lagi nanti.";
            }
            $stmt->close();
        }
    }

    $nama_kurir = trim($_POST["nama_kurir"]);
    $alamat_kurir = trim($_POST["alamat_kurir"]);
    $telepon_kurir = trim($_POST["telepon_kurir"]);
    $email_kurir = trim($_POST["email_kurir"]);
    $tanggal_lahir = trim($_POST["tanggal_lahir"]);
    $jenis_kelamin = trim($_POST["jenis_kelamin"]);

    // Contoh validasi sederhana
    if (empty($nama_kurir)) $nama_kurir_err = "Nama Kurir wajib diisi.";
    if (empty($alamat_kurir)) $alamat_kurir_err = "Alamat Kurir wajib diisi.";
    if (empty($telepon_kurir)) $telepon_kurir_err = "Telepon Kurir wajib diisi.";
    if (!empty($email_kurir) && !filter_var($email_kurir, FILTER_VALIDATE_EMAIL)) $email_kurir_err = "Format Email tidak valid.";
    if (empty($tanggal_lahir)) $tanggal_lahir_err = "Tanggal Lahir wajib diisi.";
    if (empty($jenis_kelamin)) $jenis_kelamin_err = "Jenis Kelamin wajib dipilih.";


    // Jika tidak ada error, masukkan data ke database
    if (empty($id_kurir_err) && empty($nama_kurir_err) && empty($alamat_kurir_err) && empty($telepon_kurir_err) &&
        empty($email_kurir_err) && empty($tanggal_lahir_err) && empty($jenis_kelamin_err)) {

        $sql = "INSERT INTO kurir (id_kurir, nama_kurir, alamat_kurir, telepon_kurir, email_kurir, tanggal_lahir, jenis_kelamin)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssss", $id_kurir, $nama_kurir, $alamat_kurir, $telepon_kurir, $email_kurir, $tanggal_lahir, $jenis_kelamin);

            if ($stmt->execute()) {
                echo "<script>alert('Data kurir berhasil ditambahkan!'); window.location.href='data.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<div class="content-area">
    <div class="form-container">
        <h2>Input Data Kurir</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="id_kurir">ID Kurir:</label>
                <input type="text" id="id_kurir" name="id_kurir" value="<?php echo $id_kurir; ?>">
                <span class="error"><?php echo $id_kurir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="nama_kurir">Nama Kurir:</label>
                <input type="text" id="nama_kurir" name="nama_kurir" value="<?php echo $nama_kurir; ?>">
                <span class="error"><?php echo $nama_kurir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="alamat_kurir">Alamat Kurir:</label>
                <textarea id="alamat_kurir" name="alamat_kurir"><?php echo $alamat_kurir; ?></textarea>
                <span class="error"><?php echo $alamat_kurir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="telepon_kurir">Telepon Kurir:</label>
                <input type="text" id="telepon_kurir" name="telepon_kurir" value="<?php echo $telepon_kurir; ?>">
                <span class="error"><?php echo $telepon_kurir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email_kurir">Email Kurir:</label>
                <input type="text" id="email_kurir" name="email_kurir" value="<?php echo $email_kurir; ?>">
                <span class="error"><?php echo $email_kurir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir:</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo $tanggal_lahir; ?>">
                <span class="error"><?php echo $tanggal_lahir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin:</label>
                <select id="jenis_kelamin" name="jenis_kelamin">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" <?php echo ($jenis_kelamin == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo ($jenis_kelamin == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
                <span class="error"><?php echo $jenis_kelamin_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Simpan" class="btn-primary">
                <input type="reset" value="Reset" class="btn-info">
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<?php
// kurir/data.php
require_once '../includes/header.php';
require_once '../config/database.php';

// Ambil data kurir dari database
$sql = "SELECT * FROM kurir ORDER BY nama_kurir ASC";
$result = $conn->query($sql);

?>

<div class="content-area">
    <h2>Data Kurir</h2>
    <p><a href="input.php" class="btn-primary">Tambah Data Kurir Baru</a></p>

    <?php if ($result->num_rows > 0) : ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Kurir</th>
                    <th>Nama Kurir</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Tgl Lahir</th>
                    <th>JK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['id_kurir']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_kurir']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat_kurir']); ?></td>
                        <td><?php echo htmlspecialchars($row['telepon_kurir']); ?></td>
                        <td><?php echo htmlspecialchars($row['email_kurir']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal_lahir']); ?></td>
                        <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo urlencode($row['id_kurir']); ?>" class="btn-info">Edit</a>
                            <a href="proses.php?action=delete&id=<?php echo urlencode($row['id_kurir']); ?>" class="btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Tidak ada data kurir.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

<?php require_once '../includes/footer.php'; ?>

<?php
// kurir/edit.php
require_once '../includes/header.php';
require_once '../config/database.php';

$id_kurir_get = $_GET['id'] ?? null;

if (!isset($id_kurir_get)) {
    header("Location: data.php");
    exit;
}

$id_kurir_err = $nama_kurir_err = $alamat_kurir_err = $telepon_kurir_err = $email_kurir_err = $tanggal_lahir_err = $jenis_kelamin_err = "";

$id_kurir = $nama_kurir = $alamat_kurir = $telepon_kurir = $email_kurir = $tanggal_lahir = $jenis_kelamin = "";

// Ambil data yang akan diedit
$sql_select = "SELECT * FROM kurir WHERE id_kurir = ?";
if ($stmt_select = $conn->prepare($sql_select)) {
    $stmt_select->bind_param("s", $id_kurir_get);
    if ($stmt_select->execute()) {
        $result_select = $stmt_select->get_result();
        if ($result_select->num_rows == 1) {
            $row = $result_select->fetch_assoc();
            $id_kurir = $row['id_kurir'];
            $nama_kurir = $row['nama_kurir'];
            $alamat_kurir = $row['alamat_kurir'];
            $telepon_kurir = $row['telepon_kurir'];
            $email_kurir = $row['email_kurir'];
            $tanggal_lahir = $row['tanggal_lahir'];
            $jenis_kelamin = $row['jenis_kelamin'];
        } else {
            echo "<script>alert('Data tidak ditemukan!'); window.location.href='data.php';</script>";
            exit;
        }
    } else {
        echo "Oops! Ada yang salah. Silakan coba lagi nanti.";
    }
    $stmt_select->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    $new_id_kurir = trim($_POST["id_kurir"]);
    $nama_kurir = trim($_POST["nama_kurir"]);
    $alamat_kurir = trim($_POST["alamat_kurir"]);
    $telepon_kurir = trim($_POST["telepon_kurir"]);
    $email_kurir = trim($_POST["email_kurir"]);
    $tanggal_lahir = trim($_POST["tanggal_lahir"]);
    $jenis_kelamin = trim($_POST["jenis_kelamin"]);

    // Cek ID Kurir jika diubah
    if (empty($new_id_kurir)) {
        $id_kurir_err = "Mohon masukkan ID Kurir.";
    } elseif ($new_id_kurir !== $id_kurir_get) { // Jika ID diubah
        $sql = "SELECT id_kurir FROM kurir WHERE id_kurir = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $new_id_kurir);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $id_kurir_err = "ID Kurir ini sudah terdaftar.";
                }
            }
            $stmt->close();
        }
    }

    // Contoh validasi sederhana
    if (empty($nama_kurir)) $nama_kurir_err = "Nama Kurir wajib diisi.";
    if (empty($alamat_kurir)) $alamat_kurir_err = "Alamat Kurir wajib diisi.";
    if (empty($telepon_kurir)) $telepon_kurir_err = "Telepon Kurir wajib diisi.";
    if (!empty($email_kurir) && !filter_var($email_kurir, FILTER_VALIDATE_EMAIL)) $email_kurir_err = "Format Email tidak valid.";
    if (empty($tanggal_lahir)) $tanggal_lahir_err = "Tanggal Lahir wajib diisi.";
    if (empty($jenis_kelamin)) $jenis_kelamin_err = "Jenis Kelamin wajib dipilih.";


    // Jika tidak ada error, update data di database
    if (empty($id_kurir_err) && empty($nama_kurir_err) && empty($alamat_kurir_err) && empty($telepon_kurir_err) &&
        empty($email_kurir_err) && empty($tanggal_lahir_err) && empty($jenis_kelamin_err)) {

        $sql_update = "UPDATE kurir SET id_kurir=?, nama_kurir=?, alamat_kurir=?, telepon_kurir=?, email_kurir=?, tanggal_lahir=?, jenis_kelamin=?
                       WHERE id_kurir = ?";

        if ($stmt_update = $conn->prepare($sql_update)) {
            $stmt_update->bind_param("ssssssss", $new_id_kurir, $nama_kurir, $alamat_kurir, $telepon_kurir, $email_kurir, $tanggal_lahir, $jenis_kelamin, $id_kurir_get);

            if ($stmt_update->execute()) {
                echo "<script>alert('Data kurir berhasil diperbarui!'); window.location.href='data.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt_update->error . "');</script>";
            }
            $stmt_update->close();
        }
    }
}
?>

<div class="content-area">
    <div class="form-container">
        <h2>Edit Data Kurir</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . urlencode($id_kurir_get); ?>" method="post">
            <div class="form-group">
                <label for="id_kurir">ID Kurir:</label>
                <input type="text" id="id_kurir" name="id_kurir" value="<?php echo htmlspecialchars($id_kurir); ?>">
                <span class="error"><?php echo $id_kurir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="nama_kurir">Nama Kurir:</label>
                <input type="text" id="nama_kurir" name="nama_kurir" value="<?php echo htmlspecialchars($nama_kurir); ?>">
                <span class="error"><?php echo $nama_kurir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="alamat_kurir">Alamat Kurir:</label>
                <textarea id="alamat_kurir" name="alamat_kurir"><?php echo htmlspecialchars($alamat_kurir); ?></textarea>
                <span class="error"><?php echo $alamat_kurir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="telepon_kurir">Telepon Kurir:</label>
                <input type="text" id="telepon_kurir" name="telepon_kurir" value="<?php echo htmlspecialchars($telepon_kurir); ?>">
                <span class="error"><?php echo $telepon_kurir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email_kurir">Email Kurir:</label>
                <input type="text" id="email_kurir" name="email_kurir" value="<?php echo htmlspecialchars($email_kurir); ?>">
                <span class="error"><?php echo $email_kurir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir:</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($tanggal_lahir); ?>">
                <span class="error"><?php echo $tanggal_lahir_err; ?></span>
            </div>
            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin:</label>
                <select id="jenis_kelamin" name="jenis_kelamin">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" <?php echo ($jenis_kelamin == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo ($jenis_kelamin == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
                <span class="error"><?php echo $jenis_kelamin_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Update" class="btn-primary">
                <a href="data.php" class="btn-info">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php $conn->close(); ?>
<?php require_once '../includes/footer.php'; ?>

<?php
// kurir/proses.php
require_once '../config/database.php';
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_kurir = $_GET['id'];

    $sql = "DELETE FROM kurir WHERE id_kurir = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $id_kurir);
        if ($stmt->execute()) {
            echo "<script>alert('Data kurir berhasil dihapus!'); window.location.href='data.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='data.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href='data.php';</script>";
    }
    $conn->close();
    exit;
} else {
    // Jika akses tidak valid
    header("Location: data.php");
    exit;
}
?>

<?php
// laporan/pengiriman.php
require_once '../includes/header.php';
require_once '../config/database.php';

// Ambil data pengiriman dari database
// Gabungkan dengan tabel pelanggan (untuk nama pengirim/penerima) dan kurir (untuk nama kurir)
$sql = "SELECT p.*,
               pl_pengirim.nama_pelanggan AS nama_pengirim_full,
               pl_penerima.nama_pelanggan AS nama_penerima_full,
               k.nama_kurir
        FROM pengiriman p
        LEFT JOIN pelanggan pl_pengirim ON p.id_pengirim = pl_pengirim.id_pelanggan
        LEFT JOIN pelanggan pl_penerima ON p.id_penerima = pl_penerima.id_pelanggan
        LEFT JOIN kurir k ON p.id_kurir = k.id_kurir
        ORDER BY p.tanggal_pengiriman DESC, p.no_resi DESC";
$result = $conn->query($sql);

?>

<div class="content-area">
    <h2>Laporan Data Pengiriman</h2>
    <div class="print-section">
        <p><button onclick="window.print()" class="btn-primary">Cetak Laporan</button></p>
    </div>

    <?php if ($result->num_rows > 0) : ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No Resi</th>
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Tanggal Pengiriman</th>
                    <th>Status</th>
                    <th>Biaya (Rp)</th>
                    <th>Keterangan</th>
                    <th>Kurir</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['no_resi']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_pengirim_full']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_penerima_full']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal_pengiriman']); ?></td>
                        <td><?php echo htmlspecialchars($row['status_pengiriman']); ?></td>
                        <td><?php echo number_format($row['biaya_pengiriman'], 0, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_kurir'] ?: 'Belum Ditugaskan'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Tidak ada data pengiriman.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

<?php require_once '../includes/footer.php'; ?>

<style>
    @media print {
        /* Sembunyikan navigasi dan tombol cetak */
        .sidebar, .main-header, .print-section {
            display: none;
        }
        /* Sesuaikan layout konten untuk cetak */
        .content-area {
            margin-left: 0;
            padding: 20px;
            width: 100%;
        }
        /* Pastikan tabel terlihat baik saat dicetak */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        /* Header dan Footer pada laporan cetak (opsional) */
        @page {
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    }
</style>

<?php
// laporan/barang.php
require_once '../includes/header.php';
require_once '../config/database.php';

// Ambil data barang dari database
$sql = "SELECT b.* FROM barang b ORDER BY b.no_resi DESC, b.nama_barang ASC";
$result = $conn->query($sql);

?>

<div class="content-area">
    <h2>Laporan Data Barang</h2>
    <div class="print-section">
        <p><button onclick="window.print()" class="btn-primary">Cetak Laporan</button></p>
    </div>

    <?php if ($result->num_rows > 0) : ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No Resi</th>
                    <th>Nama Barang</th>
                    <th>Berat (kg)</th>
                    <th>Jenis Barang</th>
                    <th>Dimensi</th>
                    <th>Isi Barang</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['no_resi']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                        <td><?php echo htmlspecialchars($row['berat_barang']); ?></td>
                        <td><?php echo htmlspecialchars($row['jenis_barang']); ?></td>
                        <td><?php echo htmlspecialchars($row['dimensi_barang']); ?></td>
                        <td><?php echo htmlspecialchars($row['isi_barang']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Tidak ada data barang.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

<?php require_once '../includes/footer.php'; ?>

<style>
    @media print {
        .sidebar, .main-header, .print-section {
            display: none;
        }
        .content-area {
            margin-left: 0;
            padding: 20px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        @page {
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    }
</style>

<?php
// laporan/pelanggan.php
require_once '../includes/header.php';
require_once '../config/database.php';

// Ambil data pelanggan dari database
$sql = "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC";
$result = $conn->query($sql);

?>

<div class="content-area">
    <h2>Laporan Data Pelanggan</h2>
    <div class="print-section">
        <p><button onclick="window.print()" class="btn-primary">Cetak Laporan</button></p>
    </div>

    <?php if ($result->num_rows > 0) : ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['id_pelanggan']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_pelanggan']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat_pelanggan']); ?></td>
                        <td><?php echo htmlspecialchars($row['telepon_pelanggan']); ?></td>
                        <td><?php echo htmlspecialchars($row['email_pelanggan']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Tidak ada data pelanggan.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

<?php require_once '../includes/footer.php'; ?>

<style>
    @media print {
        .sidebar, .main-header, .print-section {
            display: none;
        }
        .content-area {
            margin-left: 0;
            padding: 20px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        @page {
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    }
</style>

<?php
// laporan/kurir.php
require_once '../includes/header.php';
require_once '../config/database.php';

// Ambil data kurir dari database
$sql = "SELECT * FROM kurir ORDER BY nama_kurir ASC";
$result = $conn->query($sql);

?>

<div class="content-area">
    <h2>Laporan Data Kurir</h2>
    <div class="print-section">
        <p><button onclick="window.print()" class="btn-primary">Cetak Laporan</button></p>
    </div>

    <?php if ($result->num_rows > 0) : ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Kurir</th>
                    <th>Nama Kurir</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Tgl Lahir</th>
                    <th>JK</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['id_kurir']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_kurir']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat_kurir']); ?></td>
                        <td><?php echo htmlspecialchars($row['telepon_kurir']); ?></td>
                        <td><?php echo htmlspecialchars($row['email_kurir']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal_lahir']); ?></td>
                        <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Tidak ada data kurir.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

<?php require_once '../includes/footer.php'; ?>

<style>
    @media print {
        .sidebar, .main-header, .print-section {
            display: none;
        }
        .content-area {
            margin-left: 0;
            padding: 20px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        @page {
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    }
</style>

<div class="sidebar">
    <div class="logo">DBM Cargo</div>
    <ul class="nav-links">
        <li><a href="<?php echo BASE_URL; ?>dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo BASE_URL; ?>pengiriman/data.php"><i class="fas fa-truck"></i> Data Pengiriman</a></li>
        <li><a href="<?php echo BASE_URL; ?>barang/data.php"><i class="fas fa-box"></i> Data Barang</a></li>
        <li><a href="<?php echo BASE_URL; ?>pelanggan/data.php"><i class="fas fa-users"></i> Data Pelanggan</a></li>
        <li><a href="<?php echo BASE_URL; ?>kurir/data.php"><i class="fas fa-user-tie"></i> Data Kurir</a></li>
        <li class="dropdown">
            <a href="#" class="dropbtn"><i class="fas fa-chart-line"></i> Laporan <i class="fas fa-caret-down"></i></a>
            <div class="dropdown-content">
                <a href="<?php echo BASE_URL; ?>laporan/pengiriman.php">Laporan Pengiriman</a>
                <a href="<?php echo BASE_URL; ?>laporan/barang.php">Laporan Barang</a>
                <a href="<?php echo BASE_URL; ?>laporan/pelanggan.php">Laporan Pelanggan</a>
                <a href="<?php echo BASE_URL; ?>laporan/kurir.php">Laporan Kurir</a>
            </div>
        </li>
        <li><a href="<?php echo BASE_URL; ?>logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

