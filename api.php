<?php

header("Content-Type: application/json");

include 'koneksi.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$method = $_SERVER['REQUEST_METHOD'];

/*
|--------------------------------------------------------------------------
| GET ALL DATA
|--------------------------------------------------------------------------
| GET /api.php
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
| GET SINGLE DATA
|--------------------------------------------------------------------------
| GET /api.php?id=1
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
        "message" => "Detail user",
        "data" => $data
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| INSERT DATA
|--------------------------------------------------------------------------
| POST /api.php
*/

if($method == "POST"){

    $nama  = $_POST['nama'];
    $sandi = $_POST['sandi'];

    $insert = mysqli_query(
        $koneksi,
        "INSERT INTO user(nama, sandi)
         VALUES('$nama', '$sandi')"
    );

    if($insert){

        echo json_encode([
            "status" => true,
            "message" => "Data berhasil ditambahkan"
        ]);

    }else{

        echo json_encode([
            "status" => false,
            "message" => "Gagal tambah data"
        ]);
    }

    exit;
}

/*
|--------------------------------------------------------------------------
| UPDATE DATA
|--------------------------------------------------------------------------
| PUT /api.php?id=1
*/

if($method == "PUT"){

    parse_str(file_get_contents("php://input"), $_PUT);

    $id    = $_GET['id'];
    $nama  = $_PUT['nama'];
    $sandi = $_PUT['sandi'];

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
            "message" => "Gagal update data"
        ]);
    }

    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE DATA
|--------------------------------------------------------------------------
| DELETE /api.php?id=1
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
            "message" => "Gagal hapus data"
        ]);
    }

    exit;
}

echo json_encode([
    "status" => false,
    "message" => "Method tidak valid"
]);
