<?php
require_once ("conexion/db.class.php");
require_once ("respuestas.class.php");
    class auth extends DB{
        
        public function login($json){
           $_respuestas = new respuestas();
           $datos = json_decode($json,true);
            if (isset($datos["usuario"]) and isset($datos["pass"])) {
                
                $usuario = $datos["usuario"];
                $pass = $datos["pass"];
                $datos = $this->autenticar_usuario($usuario,$pass);

                if ($datos > 0) {
                    # Si las filas que trae la consulta SELECT son mayor a 0 entonces;
                    $token = $this->insertar_token($usuario_id);

                        # Si la variable token no está vacia, entonces crea un array y mete el token dentro. 
                        if ($token != "") {
                            $respuesta["response"] = [
                                $arrayToken = [
                                    "auth" => $token
                                ]
                            ];
                            
                        } 
                    # 
                    
                    echo json_encode($respuesta);
                }else {
                    echo json_encode($_respuestas->code_400("Nombre o password incorrecta. Intente de nuevo"));

                }
            }else{
                header("content-type: application/json; charset=UTF-8");
                
                echo json_encode($_respuestas->code_400("Campos vacios!"));
            }
        }

        # insertar el token en la tabla al momento de hacer login
        private function insertar_token($usuarioId){
            $value = true;
            $fecha = date("Y-m-d h:i");
            # Colocamos la variable, porque la funcion openssl no acepta algo que no sea una variable

            $token = bin2hex(openssl_random_pseudo_bytes(16,$value));
            $activo = 1;

            $sql = "INSERT INTO
             usuarios_token 
             (id_usuario, 
             token, 
             activo, 
             fecha) VALUES(
                 '$usuarioId',
                 '$token',
                 '$activo',
                 '$fecha')";
            $insertar = parent::modificar_bdd($sql);
            if ($insertar) {
                return $token;
            } else {
                return 0;
            }
            
            

        }

        # Metodo para traer los datos de la base de datos correspondiente a dicho usuario
        private function consultar_id_usuario($usuario){
            $sql = "SELECT 
            id_usuario 
            FROM 
            usuarios 
            WHERE 
            nombre_usuario = '$usuario'";

            $consultar = parent::leer_bdd($sql);

            foreach ($consultar as $key => $value) {
                $id = $value["id_usuario"];
            }
            
            return $id;
        }

        // autenticar que los datos suministrados en el formulario de login si pertenecen a un usuario activo
        private function autenticar_usuario($usuario, $password){

            $sql = "SELECT 
            nombre_usuario,
            password_usuario,
            activo 
            FROM 
            usuarios 
            WHERE 
            nombre_usuario ='$usuario'AND 
            password_usuario = '$password' AND
            activo = '1'";

            # Consultar_bdd devuelve el numero de filas de una consulta SELECT

            $consultar = parent::consultar_bdd($sql);

            return $consultar;

        }

        # Este metodo valida que un token proporcionado es valido y está activo
        public function validar_token($token){
            $_respuestas = new respuestas();

            $sql = "SELECT 
            id_usuario 
            FROM 
            usuarios_token
            WHERE 
            token = '$token' AND
            activo = '1'";

            # Consultar_bdd devuelve el numero de filas de una consulta SELECT
            $consultar = parent::consultar_bdd($sql);

            if ($consultar > 0) {
                # metodo leer_bdd trae los datos de la consulta SELECT
                $datosUsuario = parent::leer_bdd($sql);

                $informacion = array();
                
                foreach ($datosUsuario as $key => $value) {
                    $elementos = [
                        "id_usuario" => $value["id_usuario"]
                    ];
                    
                    array_push($informacion,$elementos);
                }

                // echo json_encode($informacion);
                return $informacion;
            } else {
                return 0;
                // echo json_encode($_respuestas->code_401("Token invalido"));
            }
            



        }


        public function desabilitar_token($json){
            $_respuestas = new respuestas();
            $datos = json_decode($json,true);

            $token = $datos["token"];
            $sql = "UPDATE 
            usuarios_token  
            SET 
            activo = '0'
            WHERE 
            token ='$token'";

            $consultar = parent::modificar_bdd($sql);

            if ($consultar == 1) {
                echo json_encode($_respuestas->code_200("Token disabled already"));
            } else {
                echo json_encode($_respuestas->code_400("No se ha podido realizar la peticion. Intente nuevamente"));
            }
            

            
        }
    }

?>