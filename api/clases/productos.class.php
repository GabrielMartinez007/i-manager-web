<?php
    require_once ("conexion/db.class.php");
    require_once ("respuestas.class.php");
    require_once ("auth.class.php");
    // Campos:
    // id_producto
    // id_usuario
    // nombre_producto
    // costo_und
    // precio_und

    class productos extends DB{

        private $id_producto;
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
            
        $_auth = new auth();
        $_respuestas = new respuestas();

        $auth =  $headers["auth"];

        $body = json_decode($json,true);

        $verificar = $_auth->validar_token($auth);

        if ($verificar == 0) {
            echo json_encode($_respuestas->code_401("Token invalido"));

            } else {
                    // # Este metodo retorna el id del usuario recibiendo por parametro un token ya verificado
                    $this->id_usuario = parent::id_usuario($verificar);

                    if ($this->id_usuario == 0) {
                        echo json_encode($_respuestas->code_401("Token invalido o no se encuentra en la peticion"));

                    } else {

                        if ($body["id_producto"] == "") {
                            echo json_encode($_respuestas->code_400("Debe proporcionar un id_producto"));


                        } else {

                                    $this->id_producto = $body["id_producto"];
                                    $this->nombre_producto = $body["nombre_producto"];
                                    $this->costo_und = $body["costo_und"];
                                    $this->precio_und = $body["precio_und"];

                                    $consultar = $this->modificar_producto();

                                    if ($consultar > 0) {
                                        http_response_code(201);
                                        echo json_encode($_respuestas->code_201("Modificado correctamente"));

                                    } else {
                                        http_response_code(500);
                                        echo json_encode($_respuestas->code_500("El servidor no ha podido procesar la solicitud"));

                                    }
                        }
                    }
            }
        }
        
        public function delete($headers,$json){
            $_auth = new auth();
            $_respuestas = new respuestas();

            # guardamos el token
            $auth =  $headers["auth"];

            $body = json_decode($json,true);

            # Validamos que el token este READY TO GO
            $verificar = $_auth->validar_token($auth);

            if ($verificar == 0) {

                    echo json_encode($_respuestas->code_401("Token invalido"));

            } else {
                        
                // Este metodo retorna el id del usuario recibiendo por parametro un token ya verificado
                $this->id_usuario = parent::id_usuario($verificar);

                if ($this->id_usuario == 0) {
                    
                    echo json_encode($_respuestas->code_401("Token invalido o no se encuentra en la peticion"));

                } else {
                    
                    if (is_numeric($body["id_producto"])) {

                        $this->id_producto = $body["id_producto"];

                        $sql = "DELETE  
                        FROM ".$this->table."
                        WHERE 
                        id_producto='".$this->id_producto."' 
                        AND
                        id_usuario ='".$this->id_usuario."'";

                        $consultar = parent::modificar_bdd($sql);


                        if ($consultar > 0) {
                                    
                            echo json_encode($_respuestas->code_200("Producto eliminado correctamente"));

                        } else {
                                    
                            echo json_encode($_respuestas->code_500("no se pudo eliminar el Producto"));

                        }

                    }else{
                          
                        echo json_encode($_respuestas->code_400("El id_producto debe ser un numero"));
                                            
                    }
                                         
                }
            }
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

        private function modificar_producto(){
            $sql = "UPDATE ".$this->table." SET
            id_usuario='". $this->id_usuario ."',
            nombre_producto='". $this->nombre_producto ."',
            costo_und='". $this->costo_und ."',
            precio_und='". $this->precio_und ."'
            WHERE
            id_producto='".$this->id_producto."'";
    
    
            // echo $sql;
            $consultar = parent::modificar_bdd($sql);
    
            return $consultar;
    
        }
    }

?>