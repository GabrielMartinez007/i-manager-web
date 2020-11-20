
<?php
require_once ("clases/respuestas.class.php");
require_once ("clases/auth.class.php");
    $_auth = new auth();
    $_respuestas = new respuestas();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        # Este metodo está desabilitado
       echo json_encode($_respuestas->code_405());

    } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        # Este metodo funciona para hacer el login
        $json = file_get_contents("php://input");
        $_auth->login($json);

    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {
        # Este metodo funciona para actualizar el token a deshabilitado
        $json = file_get_contents("php://input");
        // print_r($datosJson);
        $_auth->desabilitar_token($json);

    }
    

?>