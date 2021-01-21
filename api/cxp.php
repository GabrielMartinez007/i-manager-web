<?php

require_once ("clases/respuestas.class.php");
require_once ("clases/suplidores_cxp.class.php");

    $_respuestas = new respuestas();
    $_cxp = new suplidores_cxp();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $headers =  getallheaders();

        // Comprobar que se envian uno de los dos parametros
        if (isset($_GET["id"]) || isset($_GET["id_cxp"])){
    
            // si se envian validar cual de los dos es

            if (isset($_GET["id"])) {

                // si es el id del suplidor, entonces get solo los asientos del suplidor
                $id =  $_GET["id"];
                
                $_cxp->get_id($id,$headers);

            } else if (isset($_GET["id_cxp"])){

                // de lo contrario, si es un asiento en particular, solo traer ese asiento. 
                $id_cxp =  $_GET["id_cxp"];
                
                $_cxp->get_asiento($id_cxp,$headers);

            }
            
        } else {
            // si no se envian, traelos a todos
            $_cxp->get($headers);
    
            
        }
        
     

    } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        $headers =  getallheaders();
        $json = json_encode($_POST);

        $_cxp->post($headers,$json);

    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    
        $headers =  getallheaders();
        $json = json_encode($_POST);

        // print_r($json);
        $_cxp->put($headers,$json);
        
    } else if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {

        $headers =  getallheaders();
        $json = json_encode($_POST);

        // print_r($json);
        $_cxp->delete($headers,$json);
    }




?>