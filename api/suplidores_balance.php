<?php
    // Este archivo obtiene el balance de los suplidores / de un suplidor

    require_once ("clases/respuestas.class.php");
    require_once ("clases/suplidores_balance.class.php");
    
    $_balance = new suplidores_balance();
    $_respuestas = new respuestas();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $headers =  getallheaders();
       if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $_balance->get_id($headers,$id);
       } else {
           $_balance->get($headers);

       }
       
        
    }else {
        echo "este metodo está deshabilitado";
    }

    


?>