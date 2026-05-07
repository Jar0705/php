<?php

header("Content-Type: application/json");

include 'koneksi.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$method = $_SERVER['REQUEST_METHOD'];

/*
|--------------------------------------------------------------------------
| AMBIL BODY JSON
|--------------------------------------------------------------------------
*/

$inputJSON = file_get_contents("php://input");
$input = json_decode($inputJSON, true);

/*
|--------------------------------------------------------------------------
| GET ALL DATA
|--------------------------------------------------------------------------
*/

if($method == "GET" && !isset($_GET['id'])){

    $query = mysqli_query($koneksi, "SELECT * FROM user");

    $data = [];

    while($row = mysqli_fetch_assoc($query)){
        $data[] = $row;
    }

    echo json_encode([
        "status" => true,
        "message" => "Berhasil mengambil data",
        "data" => $data
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| GET DETAIL
|--------------------------------------------------------------------------
*/

if($method == "GET" && isset($_GET['id'])){

    $id = $_GET['id'];

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM user WHERE id='$id'"
    );

    $data = mysqli_fetch_assoc($query);

    echo json_encode([
        "status" => true,
        "data" => $data
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| POST
|--------------------------------------------------------------------------
*/

if($method == "POST"){

    // Bisa dari form-data / x-www-form-urlencoded
    $nama  = $_POST['nama'] ?? $input['nama'] ?? '';
    $sandi = $_POST['sandi'] ?? $input['sandi'] ?? '';

    if(empty($nama) || empty($sandi)){

        echo json_encode([
            "status" => false,
            "message" => "Nama atau sandi kosong"
        ]);

        exit;
    }

    $insert = mysqli_query(
        $koneksi,
        "INSERT INTO user(nama, sandi)
         VALUES('$nama','$sandi')"
    );

    if($insert){

        echo json_encode([
            "status" => true,
            "message" => "Data berhasil ditambahkan"
        ]);

    }else{

        echo json_encode([
            "status" => false,
            "message" => mysqli_error($koneksi)
        ]);
    }

    exit;
}

/*
|--------------------------------------------------------------------------
| PUT
|--------------------------------------------------------------------------
*/

if($method == "PUT"){

    $id = $_GET['id'] ?? '';

    // SUPPORT JSON
    if($input){

        $nama  = $input['nama'] ?? '';
        $sandi = $input['sandi'] ?? '';

    }else{

        // SUPPORT x-www-form-urlencoded
        parse_str($inputJSON, $putData);

        $nama  = $putData['nama'] ?? '';
        $sandi = $putData['sandi'] ?? '';
    }

    if(empty($id)){

        echo json_encode([
            "status" => false,
            "message" => "ID wajib diisi"
        ]);

        exit;
    }

    $update = mysqli_query(
        $koneksi,
        "UPDATE user
         SET nama='$nama',
             sandi='$sandi'
         WHERE id='$id'"
    );

    if($update){

        echo json_encode([
            "status" => true,
            "message" => "Data berhasil diupdate"
        ]);

    }else{

        echo json_encode([
            "status" => false,
            "message" => mysqli_error($koneksi)
        ]);
    }

    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

if($method == "DELETE"){

    $id = $_GET['id'];

    $delete = mysqli_query(
        $koneksi,
        "DELETE FROM user WHERE id='$id'"
    );

    if($delete){

        echo json_encode([
            "status" => true,
            "message" => "Data berhasil dihapus"
        ]);

    }else{

        echo json_encode([
            "status" => false,
            "message" => mysqli_error($koneksi)
        ]);
    }

    exit;
}

/*
|--------------------------------------------------------------------------
| METHOD TIDAK VALID
|--------------------------------------------------------------------------
*/

echo json_encode([
    "status" => false,
    "message" => "Method tidak valid"
]);
