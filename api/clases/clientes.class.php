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
        private $nota;


        public function get($json){
            $_auth = new auth();
            $_respuestas = new respuestas();
            $token = $json["auth"];

            $verificar = $_auth->validar_token($token);

                 if ($verificar == 0) {
                     # Si el token no es valido, dirá lo siguiente:
                     echo json_encode($_respuestas->code_401("Token invalido"));

                 } else {
                     # la funcion id_usuario devuelve el id del usuario de un token verificado
                    $this->id_usuario = parent::id_usuario($verificar);

                     $sql = "SELECT *
                     FROM clientes
                     WHERE
                     id_usuario=" . $this->id_usuario ."
                     order by id_cliente desc";

                         # parent::leer_bdd devuelve un objeto mysqli_result con toda la info.
                         $consultar = parent::leer_bdd($sql);

                         if ($consultar) {

                             $clientes = array();

                             foreach ($consultar as $key => $value) {
                                 $elementos["clientes"] = [
                                     "id" => $value["id_cliente"],
                                     "id_usuario" => $value["id_usuario"],
                                     "id_establecimiento" => $value["id_establecimiento"],
                                     "nombre" => $value["nombre_cliente"],
                                     "ventas" => $value["ventas_cliente"],
                                     "abonos" => $value["abonos_cliente"],
                                     "balance" => $value["balance_cliente"],
                                     "notas" => $value["nota_cliente"]


                                 ];

                                 array_push($clientes,$elementos);
                             }

                                 http_response_code(200);
                                 header("content-type: application/json; charset=UTF-8");
                                //  echo json_encode($clientes);
                                 print_r($clientes);

                         } else {

                                 http_response_code(500);
                                 header("content-type: application/json; charset=UTF-8");
                                 echo json_encode($_respuestas->code_500("No se han podido obtener datos. "));

                         }

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
                            
                            $sql = "SELECT *
                            FROM clientes
                            WHERE
                            id_usuario=" . $this->id_usuario ." 
                            AND
                            id_cliente=$id";

                            $consultar = parent::leer_bdd($sql);

                            if ($consultar) {

                                $clientes = array();
   
                                foreach ($consultar as $key => $value) {
                                    $elementos["clientes"] = [
                                        "id" => $value["id_cliente"],
                                        "id_usuario" => $value["id_usuario"],
                                        "id_establecimiento" => $value["id_establecimiento"],
                                        "nombre" => $value["nombre_cliente"],
                                        "ventas" => $value["ventas_cliente"],
                                        "abonos" => $value["abonos_cliente"],
                                        "balance" => $value["balance_cliente"],
                                        "notas" => $value["nota_cliente"]
   
                                    ];
   
                                    array_push($clientes,$elementos);
                                }
   
                                   
                                    if ($consultar->num_rows == 0) {
                                        http_response_code(200);
                                        header("content-type: application/json; charset=UTF-8");
                                        echo json_encode($_respuestas->code_200("Cliente no encontrado "));
                                    } else {
                                        print_r($clientes);

                                    }
  
                            } else {
   
                                    http_response_code(500);
                                    header("content-type: application/json; charset=UTF-8");
                                    echo json_encode($_respuestas->code_500("No se han podido obtener datos. "));
   
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
        public function incrementar_balance_cliente($id_cliente,$valor_venta){
            $_respuestas = new respuestas();
            # esta funcion incrementa el balance del cliente - ESTO ES PARA LAS VENTAS
            $sql = "SELECT  ventas_cliente,balance_cliente
            FROM clientes
            WHERE
            id_cliente=$id_cliente";
            // echo $sql;



           $consultar = parent::leer_bdd($sql);
           if ($consultar) {

                foreach ($consultar as $key => $value) {
                    $elementos = [
                        "ventas" => $value["ventas_cliente"],
                        "balance" => $value["balance_cliente"]

                    ];

                }


                
                // los nuevos valores que sumará a los existentes, para incrementar las ventas y el balance pendiente
            

                //  echo json_encode($clientes);

                   $ventas_incremento =  $elementos['ventas'] + $valor_venta;
                   $balance_incremento =  $elementos['balance'] + $valor_venta;

                   $update = "UPDATE clientes SET
                   ventas_cliente='$ventas_incremento',
                    balance_cliente='$balance_incremento'
                   WHERE
                   id_cliente='$id_cliente'";

                    $consultar_update = parent::modificar_bdd($update);

                    if ($consultar_update) {
                       return 1;
                    } else {
                        return 0;
                    }
                    

                   http_response_code(200);
                   header("content-type: application/json; charset=UTF-8");



           } else {
                echo json_encode($_respuestas->code_500("El servidor no ha podido procesar la solicitud"));


           }

        }
        public function disminuir_balance_cliente($id_cliente,$abono){

            # esta funcion incrementa el balance del cliente - ESTO ES PARA LOS ABONOS
            $sql = "SELECT  abonos_cliente,balance_cliente
            FROM clientes
            WHERE
            id_cliente=$id_cliente";
            // echo $sql;



           $consultar = parent::leer_bdd($sql);
           if ($consultar) {

                foreach ($consultar as $key => $value) {
                    $elementos = [
                        "abonos" => $value["abonos_cliente"],
                        "balance" => $value["balance_cliente"]

                    ];

                }


                
                // los nuevos valores que sumará a los existentes, para incrementar las ventas y el balance pendiente
            

                //  echo json_encode($clientes);

                   $abonos_incremento =  $elementos['abonos'] + $abono;
                   $balance_disminucion =  $elementos['balance'] - $abono;

                   $update = "UPDATE clientes SET
                   abonos_cliente='$abonos_incremento',
                    balance_cliente='$balance_disminucion'
                   WHERE
                   id_cliente='$id_cliente'";

                    $consultar_update = parent::modificar_bdd($update);

                    if ($consultar_update) {
                       return 1;
                    } else {
                        http_response_code(500);
                        header("content-type: application/json; charset=UTF-8");

                    }
                        
             } else {
                         http_response_code(500);
                        header("content-type: application/json; charset=UTF-8");
            }

        }


        private function eliminar_cliente(){
            $sql = "DELETE
                    FROM clientes
                    WHERE
                    id_cliente='".$this->id_cliente."'";

               $consultar = parent::modificar_bdd($sql);

            return $consultar;
        }

        private function modificar_cliente(){
            $sql = "UPDATE clientes SET
            nombre_cliente='". $this->nombre ."',
            nota_cliente='". $this->notas ."'
            WHERE
            id_cliente='".$this->id_cliente."'";


            // echo $sql;
            $consultar = parent::modificar_bdd($sql);

            return $consultar;

        }



        private function nuevo_cliente(){
            $sql = "INSERT INTO clientes (
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