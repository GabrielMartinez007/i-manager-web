<?php
require_once ("clases/respuestas.class.php");
require_once ("clases/suplidores.class.php");
    $_respuestas = new respuestas();
    $_suplidores = new suplidores();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $headers =  getallheaders();

        if (isset($_GET["suplidor"])) {
            $id =  $_GET["suplidor"];
            
            $_suplidores->get_id($id,$headers);

        } else {
        

            $_suplidores->get($headers);
        }
        
    
    } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        $headers =  getallheaders();
        $json = file_get_contents("php://input");

        $_suplidores ->post($headers,$json);

    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    
        $headers =  getallheaders();
        $json = file_get_contents("php://input");

        // print_r($json);
        $_suplidores->put($headers,$json);
        
    } else if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {

        $headers =  getallheaders();
        $json = file_get_contents("php://input");

        // print_r($json);
        $_suplidores->delete($headers,$json);
    }








?>