<?php
    // Este archivo interactua con los productos del inventario
    /*
        - Agregar producto, modificarlos, eliminarlos, y obtener los productos.
    
    */
    require_once ("clases/respuestas.class.php");
    require_once ("clases/productos.class.php");

    $_productos = new productos();
    $_respuestas = new respuestas();


    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $headers =  getallheaders();

        if (isset($_GET["id_producto"])) {
            $id =  $_GET["id_producto"];
            
            $_productos->get_id($headers,$id);

        } else {
            $_productos->get($headers);
        }
            
            
    } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        $headers =  getallheaders();
        $json = json_encode($_POST);

        $_productos->post($headers,$json);


    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {
        $headers =  getallheaders();
        $json = json_encode($_POST);

        $_productos->put($headers,$json);


    } else if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {
        $headers =  getallheaders();
        $json = json_encode($_POST);
        $_productos->delete($headers,$json);

    }



?>