<?php
session_start(); // Debe estar al principio del archivo

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"] == "") {
    header("Location: index.html");
    exit(); // Asegúrate de detener la ejecución después de la redirección
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">Almacen ABC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="admin.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-prod.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-ord.php">Ordenes</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <?php echo "<a class='nav-link' href='logout.php'>Logout " . htmlspecialchars($_SESSION["usuario"]) . "</a>" ;?>
                </span>
            </div>
        </div>
    </nav>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre Cliente</th>
                <th scope="col">Email Cliente</th>
                <th scope="col">Total Cuenta</th>
                <th scope="col">Fecha</th>
            </tr>
        </thead>
        <tbody>
        <?php
$servurl = "http://ordenes:3003/ordenes";
$curl = curl_init($servurl);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);

if ($response === false) {
    curl_close($curl);
    die("Error en la conexión");
}
curl_close($curl);
$resp = json_decode($response);

// Verifica si la respuesta es un arreglo
if (is_array($resp)) {
    if (empty($resp)) {
        // Si el arreglo está vacío, muestra un mensaje
        echo "<tr><td colspan='5'>No hay datos disponibles.</td></tr>";
    } else {
        foreach ($resp as $dec) {
            // Verifica si $dec es un objeto y tiene las propiedades necesarias
            if (is_object($dec) && isset($dec->id, $dec->nombreCliente, $dec->emailCliente, $dec->totalCuenta, $dec->fecha)) {
                $id = $dec->id;
                $nombreCliente = $dec->nombreCliente;
                $emailCliente = $dec->emailCliente;
                $totalCuenta = $dec->totalCuenta;
                $fecha = $dec->fecha;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($id); ?></td>
                    <td><?php echo htmlspecialchars($nombreCliente); ?></td>
                    <td><?php echo htmlspecialchars($emailCliente); ?></td>
                    <td><?php echo htmlspecialchars($totalCuenta); ?></td>
                    <td><?php echo htmlspecialchars($fecha); ?></td>
                </tr>
                <?php 
            } else {
                // Manejo de error: el objeto no tiene las propiedades esperadas
                echo "<tr><td colspan='5'>Error: datos inválidos en la respuesta.</td></tr>";
            }
        }
    }
} else {
    echo "<tr><td colspan='5'>Error: la respuesta no es un arreglo válido.</td></tr>";
}
?>

        </tbody>
    </table>
</body>
</html>

