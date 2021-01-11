<?php
    require_once ("conexion/db.class.php");
    require_once ("respuestas.class.php");
    require_once ("auth.class.php");
    // Campos:
    // id_producto
    // id_establecimiento
    // id_usuario
    // nombre_producto
    // costo_und
    // precio_und

    class productos extends DB{

        private $id_producto;
        private $id_establecimiento;
        private $id_usuario;
        private $nombre_producto;
        private $costo_und;
        private $precio_und;
        private $table = "productos";

        public function get($headers){
            echo $_SERVER["REQUEST_METHOD"];
        }

        
        public function post($headers,$json){
            
            $_auth = new auth();
            $_respuestas = new respuestas();

            $token =  $headers["auth"];

            $body = json_decode($json,true);

            $verificar = $_auth->validar_token($token);

            if ($verificar == 0) {
                echo json_encode($_respuestas->code_401("Token invalido"));

                } else {
                    # comprobamos que estén todas las key de la peticion
                        // # Este metodo retorna el id del usuario recibiendo por parametro un token ya verificado
                        $this->id_usuario = parent::id_usuario($verificar);

                        if ($this->id_usuario == 0) {
                            echo json_encode($_respuestas->code_401("Token invalido o no se encuentra en la peticion"));

                        } else {
                       
                                
                                    $this->nombre_producto = $body["nombre_producto"];
                                    $this->costo_und = $body["costo_und"];
                                    $this->precio_und = $body["precio_und"];
                                    

                                    $consultar = $this->nuevo_producto();

                                    if ($consultar > 0) {
                                        http_response_code(201);
                                        echo json_encode($_respuestas->code_201("Producto agregado correctamente correctamente"));
                                    } else {
                                        http_response_code(500);

                                        echo json_encode($_respuestas->code_500("El servidor no ha podido procesar la solicitud"));

                                    }

                        }

                }
        }
        
        public function put($headers,$json){
            echo $_SERVER["REQUEST_METHOD"];
        }
        
        public function delete($headers,$json){
            echo $_SERVER["REQUEST_METHOD"];
        }

        private function nuevo_producto(){
            $sql = "INSERT INTO ".$this->table." (
                id_usuario,
                nombre_producto,
                costo_und,
                precio_und
            )
            VALUES
            (
            '". $this->id_usuario ."',
            '". $this->nombre_producto ."',
            '". $this->costo_und ."',
            '". $this->precio_und ."')";

            // echo $sql;
            $consultar = parent::modificar_bdd($sql);

            return $consultar;
        }

    }

?>