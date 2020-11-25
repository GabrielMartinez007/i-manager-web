<?php
    
    require_once ("clases/respuestas.class.php");
    require_once ("clases/clientes.class.php");
    
    $_clientes = new clientes();
    $_respuestas = new respuestas();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // # Este metodo está desabilitado
        // $headers =  getallheaders();
        // # En los headers se encuentra el token de auth
        // $_clientes_cxc->obtener_todas_transacciones($headers);
        $id_cliente = 1;
        
        $_clientes->incrementar_balance_cliente($id_cliente);
        
    }



?>