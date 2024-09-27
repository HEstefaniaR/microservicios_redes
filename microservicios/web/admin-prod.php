<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Administrar Productos</title>
</head>
<body>
    <?php
        session_start();
        $us = $_SESSION["usuario"];
        if ($us == ""){
            header("Location: index.html");
            exit();
        }

        // Manejar creación de producto
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $inventario = $_POST['inventario'];

            $url = 'http://productos:3002/productos'; // URL del servicio para crear producto
            $data = json_encode([
                'nombre' => $nombre,
                'precio' => $precio,
                'inventario' => $inventario
            ]);

            $options = [
                'http' => [
                    'header'  => "Content-type: application/json\r\n",
                    'method'  => 'POST',
                    'content' => $data,
                ],
            ];
            $context  = stream_context_create($options);
            $response = file_get_contents($url, false, $context);

            if ($response === FALSE) {
                die('Error al crear el producto');
            }

            header('Location: admin-prod.php'); // Redirigir a la página de productos después de crear uno
            exit();
        }

        // Manejar actualización de producto
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $inventario = $_POST['inventario'];

            $url = "http://productos:3002/productos/$id"; // URL del servicio para actualizar producto
            $data = json_encode([
                'nombre' => $nombre,
                'precio' => $precio,
                'inventario' => $inventario
            ]);

            $options = [
                'http' => [
                    'header'  => "Content-type: application/json\r\n",
                    'method'  => 'PUT',
                    'content' => $data,
                ],
            ];
            $context  = stream_context_create($options);
            $response = file_get_contents($url, false, $context);

            if ($response === FALSE) {
                die('Error al actualizar el producto');
            }

            header('Location: admin-prod.php'); // Redirigir a la página de productos después de actualizar uno
            exit();
        }

        // Manejar eliminación de producto
        if (isset($_GET['delete_id'])) {
            $id = $_GET['delete_id'];

            $url = "http://productos:3002/productos/$id"; // URL del servicio para eliminar producto

            $options = [
                'http' => [
                    'method'  => 'DELETE',
                ],
            ];
            $context  = stream_context_create($options);
            $response = file_get_contents($url, false, $context);

            if ($response === FALSE) {
                die('Error al eliminar el producto');
            }

            header('Location: admin-prod.php'); // Redirigir a la página de productos después de eliminar uno
            exit();
        }
    ?>

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
                        <a class="nav-link active" href="admin-prod.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-ord.php">Ordenes</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <?php echo "<a class='nav-link' href='logout.php'>Logout $us</a>" ;?>
                </span>
            </div>
        </div>
    </nav>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Precio</th>
                <th scope="col">Inventario</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $servurl = "http://productos:3002/productos";
            $curl = curl_init($servurl);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            
            if ($response === false){
                curl_close($curl);
                die("Error en la conexion");
            }

            curl_close($curl);
            $resp = json_decode($response);
            $long = count($resp);
            for ($i = 0; $i < $long; $i++){
                $dec = $resp[$i];
                $id = $dec->id;
                $nombre = $dec->nombre;
                $precio = $dec->precio;
                $inventario = $dec->inventario;
        ?>
            <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo $nombre; ?></td>
                <td><?php echo $precio; ?></td>
                <td><?php echo $inventario; ?></td>
                <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $id; ?>" class="btn btn-warning btn-sm">Actualizar</a>
                    <a href="?delete_id=<?php echo $id; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>

            <!-- Modal de Actualización -->
            <div class="modal fade" id="updateModal<?php echo $id; ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateModalLabel">Actualizar Producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="precio" class="form-label">Precio</label>
                                    <input type="text" name="precio" class="form-control" id="precio" value="<?php echo $precio; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="inventario" class="form-label">Inventario</label>
                                    <input type="text" name="inventario" class="form-control" id="inventario" value="<?php echo $inventario; ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php
            }
        ?>
        </tbody>
    </table>

    <!-- Modal de Creación -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
        Crear Producto
    </button>
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Crear Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" id="nombre">
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="text" name="precio" class="form-control" id="precio">
                        </div>
                        <div class="mb-3">
                            <label for="inventario" class="form-label">Inventario</label>
                            <input type="text" name="inventario" class="form-control" id="inventario">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Crear Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
