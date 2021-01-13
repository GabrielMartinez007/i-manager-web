<?php

 // Este archivo interactua con los productos del inventario
    /*
        - Agregar producto, modificarlos, eliminarlos, y obtener los productos.
    
    */
    require_once ("clases/respuestas.class.php");
    require_once ("clases/inventario.class.php");

    $_inventario = new inventario();
    $_respuestas = new respuestas();


    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $headers =  getallheaders();

        if (isset($_GET["id_producto"])) {
            $id =  $_GET["id_producto"];
            
            $_inventario->get_id($headers,$id);

        } else {
            $_inventario->get($headers);
        }
            
            
    } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        
        $headers =  getallheaders();
        $json = file_get_contents("php://input");

        $_inventario->post($headers,$json);


    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {
        $headers =  getallheaders();
        $json = file_get_contents("php://input");

        $_inventario->put($headers,$json);


    } else if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {
        $headers =  getallheaders();
        $json = file_get_contents("php://input");
        $_inventario->delete($headers,$json);

    }


?>