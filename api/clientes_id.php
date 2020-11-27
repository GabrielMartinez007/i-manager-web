<?php

    require_once ("clases/respuestas.class.php");
    require_once ("clases/clientes.class.php");

    $_clientes = new clientes();
    $_respuestas = new respuestas();

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $headers = getallheaders();
        // $json = file_get_contents("php://input");

        # Capturar el id del cliente a través de la url
        $id = $_GET['id'];


        $_clientes->get_id($headers,$id);

    } else {
        echo json_encode($_respuestas->code_400("Metodo deshabilitado"));

    }
    



?>