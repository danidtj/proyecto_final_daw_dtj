<?php
session_start();

use ModelsAdmin\Producto;
use ModelsAdmin\Categoria;

require_once dirname(__DIR__, 2) . '/models/admin/Postre.php';
require_once dirname(__DIR__, 2) . '/models/admin/Categoria.php';


$categorias = Categoria::obtenerCategorias();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Restaurante XITO</title>
</head>

<body>
    <?php include_once __DIR__ . '/../partials/headerAdmin.php'; ?>
    <hr id="hr1">
    <hr id="hr4">
    <main>
        <section class="container_form">
            <h2 class="titulo_form">MODIFICAR STOCK PRODUCTOS</h2>
            <form action="/controllers/admin/ProductoController.php" method="post">
                <p>Nombre corto:</p>
                <input type="text" name="nombre_corto" id="nombre_corto" placeholder="Nombre corto del producto" required><br>
                <p>Nombre largo:</p>
                <input type="text" name="nombre_largo" id="nombre_largo" placeholder="Nombre largo del producto" required><br>
                <p>Descripción:</p>
                <input type="text" name="descripcion" id="descripcion" placeholder="Descripción del producto" required><br>
                <p>Precio del producto:</p>
                <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" placeholder="Precio del producto" required><br>
                <p>Unidades del producto:</p>
                <input type="number" name="uds_stock" id="uds_stock" placeholder="Unidades del producto" required><br>
                <p>Categoría del producto:</p>
                <?php
                // Genera el select con las categorías disponibles en la base de datos
                echo '<select name="nombre_categoria" id="nombre_categoria" required>';
                echo '<option value="" disabled selected>Seleccione una categoría</option>';
                $nombreNoRepetido = array();

                foreach ($categorias as $categoria) {
                    if (!in_array($categoria->getNombreCategoria(), $nombreNoRepetido)) {
                        $nombreNoRepetido[] = $categoria->getNombreCategoria();
                        echo '<option value="' . $categoria->getNombreCategoria() . '">' . $categoria->getNombreCategoria() . '</option>';
                    }
                }
                echo '</select>';

                //Genera el select con el nombre de la categoria de la base de datos
                echo '<select name="tipo_categoria" id="tipo_categoria" required>';
                echo '<option value="" disabled selected>Seleccione un tipo</option>';
                $tipoNoRepetido = array();
                foreach ($categorias as $categoria) {
                    if (!in_array($categoria->getTipoCategoria(), $tipoNoRepetido)) {
                        $tipoNoRepetido[] = $categoria->getTipoCategoria();
                        echo '<option value="' . htmlspecialchars($categoria->getTipoCategoria()) . '" data-nombre="' . htmlspecialchars($categoria->getNombreCategoria()) . '">' . htmlspecialchars($categoria->getTipoCategoria()) . '</option>';
                    }
                }
                echo '</select>';

                //Genera el select con la modalidad del producto de la base de datos
                echo '<select name="modalidad_producto" id="modalidad_producto" required>';
                echo '<option value="" disabled selected>Seleccione una modalidad</option>';
                $modalidNoRepetida = array();

                foreach ($categorias as $categoria) {
                    // Creamos una clave que considere nombre + tipo + modalidad
                    $clave = $categoria->getNombreCategoria() . '|' . $categoria->getTipoCategoria() . '|' . $categoria->getModalidadCategoria();

                    if (!in_array($clave, $modalidNoRepetida)) {
                        $modalidNoRepetida[] = $clave;
                        echo '<option value="' . htmlspecialchars($categoria->getModalidadCategoria()) . '" data-nombre="' . htmlspecialchars($categoria->getNombreCategoria()) . '" data-tipo="' . htmlspecialchars($categoria->getTipoCategoria()) . '">' . htmlspecialchars($categoria->getModalidadCategoria()) . '</option>';
                    }
                }
                echo '</select>';
                ?>
                <br><br>

                <input type="submit" class="btn_crearProducto" value="Crear producto" name="crearNuevoProducto"><br>
            </form>
            <?php
            if (isset($_SESSION['producto_creado'])) {
                if ($_SESSION['producto_creado'] === true) {
                    echo "<p class='exito'>Producto creado correctamente.</p>";
                } else {
                    echo "<p class='error'>Error al crear el producto. Inténtelo de nuevo.</p>";
                }
            }
            ?>

        </section>
    </main>
    <?php include_once __DIR__ . '/../partials/footer.php'; ?>
    <script src="/assets/js/validacionNuevoProducto.js"></script>
</body>

</html>