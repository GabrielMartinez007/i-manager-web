<?php

require_once ("clases/respuestas.class.php");
require_once ("clases/suplidores_cxp.class.php");

    $_respuestas = new respuestas();
    $_cxp = new suplidores_cxp();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $headers =  getallheaders();

        $_cxp->get($headers);
        
    } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        $headers =  getallheaders();
        $json = file_get_contents("php://input");

        $_cxp->post($headers,$json);

    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    
        $headers =  getallheaders();
        $json = file_get_contents("php://input");

        // print_r($json);
        $_cxp->put($headers,$json);
        
    } else if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {

        $headers =  getallheaders();
        $json = file_get_contents("php://input");

        // print_r($json);
        $_cxp->delete($headers,$json);
    }




?>