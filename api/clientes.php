<?php

require_once ("clases/respuestas.class.php");
require_once ("clases/clientes.class.php");
    $_respuestas = new respuestas();
    $_clientes = new clientes();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $headers =  getallheaders();
        // $headers =  [
        //     "auth"=>"asdasdasd"
        // ];

        $_clientes->get($headers);
        
    } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        $headers =  getallheaders();
        $json = file_get_contents("php://input");

        $_clientes ->post($headers,$json);


    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    
        $headers =  getallheaders();
        $json = file_get_contents("php://input");

        // print_r($json);
        $_clientes->put($headers,$json);

    } else if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {

        $headers =  getallheaders();
        $json = file_get_contents("php://input");

        // print_r($json);
        $_clientes->delete($headers,$json);

    }





?>