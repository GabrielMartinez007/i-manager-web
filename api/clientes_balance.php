<?php
    
    require_once ("clases/respuestas.class.php");
    // require_once ("clases/clientes.class.php");
    require_once ("clases/clientes_balance.class.php");
    
    // $_clientes = new clientes();
    $_balance = new clientes_balance();
    $_respuestas = new respuestas();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $headers =  getallheaders();
        // $_clientes->incrementar_balance_cliente($id_cliente);
        // $_balance->get($headers,$id);
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