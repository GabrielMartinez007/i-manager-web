<?php

    require_once ("conexion/db.class.php");
    require_once ("respuestas.class.php");
    require_once ("auth.class.php");

    class clientes extends DB{
        /**
         *  Clientes
         *  1. VVer todos los clientes disponible
         *  2. Agregar cliente
         *  3. Actualizar informacion del cliente
         *  4. Eliminar cliente
         *
         *  Solo puedo ver las trnasacciones del id_establecimiento que esté on line
         */
        // private $_auth;
        private $id_cliente;
        private $id_usuario;
        private $id_establecimiento;
        private $nombre;
        private $ventas;
        private $abonos;
        private $balance;
        private $table = "clientes";
        private $table_relacionada = "clientes_cxc";
        private $nota;


        public function get($json){

            $_auth = new auth();
            $_respuestas = new respuestas();

            if (isset($json["auth"])) {
                
            $token = $json["auth"];

            $verificar = $_auth->validar_token($token);

                 if ($verificar == 0) {
                     # Si el token no es valido, dirá lo siguiente:
                     echo json_encode($_respuestas->code_401("Token invalido"));

                 } else {
                     # la funcion id_usuario devuelve el id del usuario de un token verificado
                    $this->id_usuario = parent::id_usuario($verificar);

                    $cuentas_por_cobrar = array();

                    $clientes = $this->obtener_id_clientes();
        
                    foreach ($clientes as $key => $value) {
                       $balance_clientes = $this->obtener_cliente($value["id"]);
                       array_push($cuentas_por_cobrar,$balance_clientes);
        
                    }
        
                    echo json_encode($cuentas_por_cobrar);
                            
                }

            }else {
               
                    header("content-type: application/json; charset=UTF-8");
                    echo json_encode($_respuestas->code_401("Token invalido o no se encuentra en la peticion. "));
            }
            
         }
         
         public function get_id($headers,$id){
             # Metodo para seleccionar un cliente en particular
            
             $_auth = new auth();
             $_respuestas = new respuestas();


            $token =  $headers["auth"];


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
                           
                                    $clientes = $this->obtener_cliente($id);
                                   
                                    
                                    if ($clientes > 0) {
                                        // echo json_encode($clientes);
                                        print_r($clientes);

                                    } else {
                                        header("content-type: application/json; charset=UTF-8");
                                        echo json_encode($_respuestas->code_200("Cliente no encontrado "));

                                    }
                            

                        }

                }

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
                            if ($body["nombre"] == "") {
                                echo json_encode($_respuestas->code_400("Debe colocar un nombre"));


                            } else {
                                    $this->nombre = $body["nombre"];
                                    $this->notas = $body["notas"];
                                    $consultar = $this->nuevo_cliente();
                                    if ($consultar > 0) {
                                        echo json_encode($_respuestas->code_200("Cliente registrado correctamente"));
                                    } else {
                                        echo json_encode($_respuestas->code_500("El servidor no ha podido procesar la solicitud"));

                                    }

                            }
                        }

                }

        }

        public function put($headers,$json){

            $_auth = new auth();
            $_respuestas = new respuestas();

            # guardamos el token
            $auth =  $headers["auth"];

            // $auth =  "1545215";
            $body = json_decode($json,true);


            # Validamos que el token este READY TO GO
            $verificar = $_auth->validar_token($auth);

            if ($verificar == 0) {
                echo json_encode($_respuestas->code_401("Token invalido"));

                } else {
                    # comprobamos que estén todas las key de la peticion
                        // # Este metodo retorna el id del usuario recibiendo por parametro un token ya verificado
                        $this->id_usuario = parent::id_usuario($verificar);

                        if ($this->id_usuario == 0) {
                            echo json_encode($_respuestas->code_401("Token invalido o no se encuentra en la peticion"));

                        } else {

                            if ($body["nombre"] == "" || $body["id_cliente"] == "") {
                                echo json_encode($_respuestas->code_400("Campos vacios"));


                            } else {

                                        $this->nombre = $body["nombre"];
                                        $this->id_cliente = $body["id_cliente"];
                                        $this->notas = $body["notas"];

                                        $consultar = $this->modificar_cliente();

                                        if ($consultar > 0) {
                                            echo json_encode($_respuestas->code_200("Modificado correctamente"));

                                        } else {
                                            echo json_encode($_respuestas->code_500("El servidor no ha podido procesar la solicitud"));

                                        }
                            }
                        }
                }

        }
        public function delete($headers,$json){
            $_auth = new auth();
            $_respuestas = new respuestas();

            $token =  $headers["auth"];

            $body = json_decode($json,true);

            $verificar = $_auth->validar_token($token);

            if ($verificar == 0) {
                echo json_encode($_respuestas->code_401("Token invalido"));

                } else {

                    // # Este metodo retorna el id del usuario recibiendo por parametro un token ya verificado
                    $this->id_usuario = parent::id_usuario($verificar);

                    if ($this->id_usuario == 0) {
                            echo json_encode($_respuestas->code_401("Token invalido o no se encuentra en la peticion"));

                        } else {
                            if (parent::array_vacio($body)) {

                                    echo json_encode($_respuestas->code_400("Debe suministrar un id_cliente"));

                    } else {
                        if (is_numeric($body["id_cliente"])) {
                            $this->id_cliente = $body["id_cliente"];
                            $consultar = $this->eliminar_cliente();
                            if ($consultar > 0) {
                                echo json_encode($_respuestas->code_200("cliente eliminado"));
                            } else {
                                echo json_encode($_respuestas->code_500("El servidor no ha podido procesar la solicitud"));

                            }

                        }else{
                            echo json_encode($_respuestas->code_400("El id del cliente debe ser un numero"));
                        }
                    }
                }

            }
        }
       

        private function eliminar_cliente(){
            $sql = "DELETE
                    FROM ".$this->table."
                    WHERE
                    id_cliente='".$this->id_cliente."'";

               $consultar = parent::modificar_bdd($sql);

            return $consultar;
        }

        private function modificar_cliente(){
            $sql = "UPDATE ".$this->table." SET
            nombre_cliente='". $this->nombre ."',
            nota_cliente='". $this->notas ."'
            WHERE
            id_cliente='".$this->id_cliente."'";


            // echo $sql;
            $consultar = parent::modificar_bdd($sql);

            return $consultar;

        }



        private function nuevo_cliente(){
            $sql = "INSERT INTO ".$this->table." (
                id_usuario,
                nombre_cliente,
                nota_cliente
            )
            VALUES
            (
            '". $this->id_usuario ."',
            '". $this->nombre ."',
            '". $this->notas ."')";

            $consultar = parent::modificar_bdd($sql);

            return $consultar;

        }

        private function obtener_cliente($id){
                $sql="SELECT ".$this->table.".id_cliente, 
            ".$this->table.".nota_cliente, 
            sum(ventas_cxc), 
            sum(abonos_cxc), 
            sum(ventas_cxc)-sum(abonos_cxc), 
            ". $this->table .".nombre_cliente 
            FROM 
            ". $this->table ." 
            JOIN 
            ". $this->table_relacionada ."
            ON 
            ". $this->table .".id_cliente = ". $this->table_relacionada .".id_cliente 
            WHERE 
            ". $this->table .".id_cliente='$id'";
            
            $consultar = parent::leer_bdd($sql);
              if ($consultar) {

                while ($fila = $consultar->fetch_assoc()) {

                        $elementos = [
                                    "id" => $fila["id_cliente"],
                                    "nombre_cliente" => $fila["nombre_cliente"],
                                    "notas" => $fila["nota_cliente"],
                                    "abonos" => $fila["sum(abonos_cxc)"],
                                    "ventas" => $fila["sum(ventas_cxc)"],
                                    "balance" => $fila["sum(ventas_cxc)-sum(abonos_cxc)"]
                                ];
                      
                    
                    }
              
                return $elementos;
                    
            } else {
                return '0';
            }

        }

        private function obtener_id_clientes(){

            $sql = "SELECT
            id_cliente
            FROM 
            ".$this->table."";

            $consultar = parent::leer_bdd($sql);

              if ($consultar) {

                    $array_id = array();

                    while ($fila = $consultar->fetch_assoc()) {
                     $elementos = [
                         "id" => $fila["id_cliente"]
                    ];

                        array_push($array_id,$elementos);
                  }

                    return $array_id;
                    
            } else {
                return 0;
            }
        }

        private function comprobar_key_post($body){
            // comprobar que tiene los elementos necesarios
            if (count($body) == 6) {
                return 1;
            } else {
               return 0;
            }


        }

    }




?>