<?php

require_once ("clases/respuestas.class.php");
require_once ("clases/clientes_cxc.class.php");
    $_clientes_cxc = new clientes_cxc();
    $_respuestas = new respuestas();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        # Este metodo está desabilitado
        $headers =  getallheaders();
        # En los headers se encuentra el token de auth
        $_clientes_cxc->obtener_todas_transacciones($headers);
        
    } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        # Este metodo está desabilitado
        $headers =  getallheaders();
        $json = json_encode($_POST);

        // print_r($json);
        $_clientes_cxc->post($headers,$json);


    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {
        $headers =  getallheaders();
        $json = json_encode($_POST);

        // print_r($json);
        $_clientes_cxc->put($headers,$json);

    } else if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {
        $headers =  getallheaders();
        $json = json_encode($_POST);

        // print_r($json);
        $_clientes_cxc->delete($headers,$json);

    }





?>