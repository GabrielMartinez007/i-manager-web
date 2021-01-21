<?php

require_once ("clases/respuestas.class.php");
require_once ("clases/clientes.class.php");
    $_respuestas = new respuestas();
    $_clientes = new clientes();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $headers =  getallheaders();
        
        if (isset($_GET["cliente"])) {
            $id =  $_GET["cliente"];
            
            $_clientes->get_id($headers,$id);

        } else {
        
            $_clientes->get($headers);
        }
        
    } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        $headers =  getallheaders();
        $json = json_encode($_POST);
        $_clientes ->post($headers,$json);


    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    
        $headers =  getallheaders();
        $json = json_encode($_POST);

        // print_r($json);
        $_clientes->put($headers,$json);

    } else if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {

        $headers =  getallheaders();
        $json = json_encode($_POST);

        // print_r($json);
        $_clientes->delete($headers,$json);

    }





?>