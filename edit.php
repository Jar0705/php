<?php
include 'koneksi.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ambil ID
$id = $_GET['id'];

// Ambil data user berdasarkan ID
$data = mysqli_query($koneksi, "SELECT * FROM user WHERE id=$id");
$d = mysqli_fetch_array($data);

// Proses update
if(isset($_POST['update'])){

    $nama = $_POST['nama'];
    $sandi = $_POST['sandi'];

    mysqli_query($koneksi,
        "UPDATE user 
         SET nama='$nama', sandi='$sandi'
         WHERE id=$id");

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>

<h2>Edit Data User</h2>

<form method="POST">

    <input 
        type="text" 
        name="nama"
        value="<?php echo $d['nama']; ?>"
        required
    >

    <input 
        type="password"
        name="sandi"
        value="<?php echo $d['sandi']; ?>"
        required
    >

    <button type="submit" name="update">
        Update
    </button>

</form>

<br>

<a href="index.php">Kembali</a>

</body>
</html>
