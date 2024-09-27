<?php
    $user = $_POST["usuario"];
    $pass = $_POST["password"];

    $servurl = "http://usuarios:3001/usuarios/$user/$pass";
    $curl = curl_init($servurl);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    if ($response === false) {
        header("Location:index.html");
        exit();
    }

    // Decodificar la respuesta JSON
    $resp = json_decode($response, true); // Agregar `true` para obtener un array asociativo

    if (is_array($resp) && !empty($resp)) {
        session_start();
        $_SESSION["usuario"] = $user;

        if ($user == "admin") {
            echo "admin";
            header("Location:admin.php");
        } else {
            echo "usuario";
            header("Location:usuario.php");
        }
        exit();
    } else {
        header("Location:index.html");
        exit();
    }
?>
