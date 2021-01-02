<?php

    require_once ("clases/respuestas.class.php");
    require_once ("clases/clientes_cxc.class.php");

    $_cxc = new clientes_cxc();
    $_respuestas = new respuestas();

    if ($_SERVER['REQUEST_METHOD'] == "PUT") {
        $headers = getallheaders();

        # Capturar el id del cliente a través de la url
        $id = $_GET['id'];


        // $_cxc->get_id($headers,$id);
        $_cxc->cliente_venta_abono_balance_manual($headers,$id);

    } else {
        echo json_encode($_respuestas->code_400("Metodo deshabilitado"));

    }
    


?>