<?php
    require_once ("conexion/db.class.php");
    require_once ("respuestas.class.php");
    require_once ("auth.class.php");

    class clientes_cxc extends DB{
        /**
         *  Libro clientes
         *  1. Ver todas las transacciones del usuario que esté en sesion
         *  2. Insertar transaccion
         *  3. Actualizar transaccion
         *  4. Eliminar transaccion
         * 
         *  Solo puedo ver las trnasacciones del id_establecimiento que esté on line
         */

         private $id_asiento;
         private $id_usuario;
         private $id_establecimiento;
         private $fecha;
         private $id_cliente;
         private $concepto;
         private $descripcion;
         private $ventas;
         private $abonos;
         private $valor;
         private $notas;


         # FUNCION NO. 1
         public function obtener_todas_transacciones($json){
           $_auth = new auth();
           $_respuestas = new respuestas();
            
            # Recibimos el token de autenticacion que vino en el header
            $auth = $json["auth"];
            // $auth = "c2f0e750b8a9b7838372bc9212f7af8e";

            # Si el token es autentico nos trará el id
            $verificar = $_auth->validar_token($auth);

                if ($verificar == 0) {
                    # Si el token no es valido, dirá lo siguiente:
                    echo json_encode($_respuestas->code_401("Token invalido"));
        
                } else {
                    # la funcion id_usuario devuelve el id del usuario de un token verificado
                    $this->id_usuario = parent::id_usuario($verificar);
            
                    $sql = "SELECT * 
                    FROM clientes_cxc
                    WHERE
                    id_usuario=" . $this->id_usuario ."
                    order by id_asiento_cxc desc";

                        # parent::leer_bdd devuelve un objeto mysqli_result con toda la info.
                        $consultar = parent::leer_bdd($sql);

                        if ($consultar) {
                            
                            $transacciones = array();
                            
                            foreach ($consultar as $key => $value) {
                                $elementos["transacciones"] = [
                                    "asiento" => $value["id_asiento_cxc"],
                                    "id_cliente" => $value["id_cliente"],
                                    "id_usuario" => $value["id_usuario"],
                                    "id_establecimiento" => $value["id_establecimiento"],
                                    "fecha" => $value["fecha_cxc"],
                                    "concepto" => $value["concepto_cxc"],
                                    "descripcion" => $value["descripcion"],
                                    "ventas" => $value["ventas_cxc"],
                                    "abonos" => $value["abonos_cxc"],
                                    "notas" => $value["nota_clientes"]


                                ];
                                
                                array_push($transacciones,$elementos);
                            }

                                http_response_code(200);
                                header("content-type: application/json; charset=UTF-8");
                                echo json_encode($transacciones);
                                // print_r($transacciones);

                        } else {

                                http_response_code(500);
                                header("content-type: application/json; charset=UTF-8");
                                echo json_encode($_respuestas->code_500("No se han podido obtener datos. "));

                        }

                }
             
        }

        public function post($headers,$json){

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
                    if ($this->comprobar_key_post($body)) {
                        // # Este metodo retorna el id del usuario recibiendo por parametro un token ya verificado
                        $this->id_usuario = parent::id_usuario($verificar);

                        if ($this->id_usuario == 0) {
                            echo json_encode($_respuestas->code_401("Token invalido o no se encuentra en la peticion"));

                        } else {
                            // echo "Token validado, el id del usuario es: $id_usuario";

                            if (parent::array_vacio($body)) {
                                echo json_encode($_respuestas->code_400("Campos vacios"));


                            } else {
                                if (is_numeric($body["valor"])) {
                                    if (is_numeric($body["id_cliente"])) {
                                        
                                        $fecha_valida = parent::validar_fecha($body["fecha"]);

                                        if ($fecha_valida == 1) {
                                            if (is_numeric($body["tipo"])) {
                                                if ($body["tipo"] < 3) {
                                                     
                                                    $this->id_cliente = $body["id_cliente"];
                                                    $this->fecha = $body["fecha"];
                                                    $this->valor = $body["valor"]; 
                                                    $this->descripcion = $body["descripcion"];
                                                    
                                                    $this->notas = $body["notas"];
                                                    $tipo = $body["tipo"];
                                                    $consultar = $this->transaccion($tipo);
                            
                                                    if ($consultar > 0) {
                                                        echo json_encode($_respuestas->code_200("Registrado correctamente"));
                                                        
                                                    } else {
                                                        echo json_encode($_respuestas->code_500("El servidor no ha podido procesar la solicitud"));
                            
                                                    }
                                                } else {
                                                        echo json_encode($_respuestas->code_400("Tipo solo puede ser 1 o 2"));

                                                }
                                            } else {
                                                echo json_encode($_respuestas->code_400("Tipo de transaccion solo puede ser un numero"));

                                            }
                                        } else {
                                            echo json_encode($_respuestas->code_400("Fecha invalida"));

                                        }
                                    } else {
                                        echo json_encode($_respuestas->code_400("El id del cliente debe ser un numero"));

                                    }
                                } else {
                                    echo json_encode($_respuestas->code_400("El campo valor debe ser un numero"));

                                }
                            }
                        }
                    } else {
                        echo json_encode($_respuestas->code_400("Faltan elementos en la peticion"));
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
                    if ($this->comprobar_key_put($body)) {
                        // # Este metodo retorna el id del usuario recibiendo por parametro un token ya verificado
                        $this->id_usuario = parent::id_usuario($verificar);

                        if ($this->id_usuario == 0) {
                            echo json_encode($_respuestas->code_401("Token invalido o no se encuentra en la peticion"));

                        } else {
                            // echo "Token validado, el id del usuario es: $id_usuario";

                            if (parent::array_vacio($body)) {
                                echo json_encode($_respuestas->code_400("Campos vacios"));


                            } else {
                                if (is_numeric($body["valor"])) {
                                    if (is_numeric($body["id_cliente"])) {
                                        
                                        $fecha_valida = parent::validar_fecha($body["fecha"]);

                                        if ($fecha_valida == 1) {
                                            if (is_numeric($body["tipo"])) {
                                                if ($body["tipo"] < 3) {
                                                     
                                                    $this->id_asiento = $body["id_asiento"];                                    
                                                    $this->id_cliente = $body["id_cliente"];
                                                    $this->fecha = $body["fecha"];
                                                    $this->valor = $body["valor"]; 
                                                    $this->descripcion = $body["descripcion"];
                                                    $this->notas = $body["notas"];
                                                    $tipo = $body["tipo"];

                                                    $consultar = $this->modificar_transaccion($tipo);
                            
                                                    if ($consultar > 0) {
                                                        echo json_encode($_respuestas->code_200("Registrado correctamente"));
                                                        
                                                    } else {
                                                        echo json_encode($_respuestas->code_500("El servidor no ha podido procesar la solicitud"));
                            
                                                    }
                                                } else {
                                                        echo json_encode($_respuestas->code_400("Tipo solo puede ser 1 o 2"));

                                                }
                                            } else {
                                                echo json_encode($_respuestas->code_400("Tipo de transaccion solo puede ser un numero"));

                                            }
                                        } else {
                                            echo json_encode($_respuestas->code_400("Fecha invalida"));

                                        }
                                    } else {
                                        echo json_encode($_respuestas->code_400("El id del cliente debe ser un numero"));

                                    }
                                } else {
                                    echo json_encode($_respuestas->code_400("El campo valor debe ser un numero"));

                                }
                            }
                        }
                    } else {
                        echo json_encode($_respuestas->code_400("Faltan elementos en la peticion"));
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
                        
                    // # Este metodo retorna el id del usuario recibiendo por parametro un token ya verificado
                    $this->id_usuario = parent::id_usuario($verificar);

                    if ($this->id_usuario == 0) {
                            echo json_encode($_respuestas->code_401("Token invalido o no se encuentra en la peticion"));

                        } else {
                            if (parent::array_vacio($body)) {

                                    echo json_encode($_respuestas->code_400("Campos vacios"));
                               
                    } else {
                        if (is_numeric($body["id_asiento"])) {
                            $this->id_asiento = $body["id_asiento"];                                    
                            $consultar = $this->eliminar_transaccion();
                            if ($consultar > 0) {
                                echo json_encode($_respuestas->code_200("Asiento eliminado"));
                            } else {
                                echo json_encode($_respuestas->code_500("El servidor no ha podido procesar la solicitud"));
                            
                            }
            
                        }else{
                            echo json_encode($_respuestas->code_400("El id_asiento debe ser un numero"));
                        }
                    }
                } 
        
            }
        }                                    
        private function eliminar_transaccion(){
            $sql = "DELETE 
                    FROM clientes_cxc
                    WHERE 
                    id_asiento_cxc='".$this->id_asiento."'";

               $consultar = parent::modificar_bdd($sql);
                
            return $consultar;
        }

        private function modificar_transaccion($tipo){
            # 1 = Venta y 2 = Abono
            if ($tipo == 1) {
                // echo "venta";
                $sql = "UPDATE clientes_cxc SET 
                            id_cliente='". $this->id_cliente ."',
                            fecha_cxc='". $this->fecha ."',
                            concepto_cxc='Venta',
                            descripcion='". $this->descripcion ."',
                            ventas_cxc='". $this->valor ."',
                            nota_clientes='". $this->notas ."' 
                        WHERE 
                        id_asiento_cxc='".$this->id_asiento."'";
                        

                // echo $sql;        
               $consultar = parent::modificar_bdd($sql);
                
                return $consultar;

            } else if($tipo == 2){
            //    echo "Abono";
            $sql = "UPDATE clientes_cxc SET 
                            id_cliente='". $this->id_cliente ."',
                            fecha_cxc='". $this->fecha ."',
                            concepto_cxc='Abono',
                            descripcion='". $this->descripcion ."',
                            abono_cxc='". $this->valor ."',
                            nota_clientes='". $this->notas ."' 
                        WHERE 
                        id_asiento_cxc='".$this->id_asiento."'";

            // echo $sql;   

            $consultar = parent::modificar_bdd($sql);
            
            return $consultar;

            }
        }


        # hay dos tipos de consultas dependiendo si es un abono o una venta
        private function transaccion($tipo){

                # 1 = Venta y 2 = Abono
                if ($tipo == 1) {
                    // echo "venta";
                    $sql = "INSERT INTO clientes_cxc (
                                id_usuario,
                                id_cliente,
                                fecha_cxc,
                                concepto_cxc,
                                descripcion,
                                ventas_cxc,
                                nota_clientes
                            )
                            VALUES
                            (
                            '". $this->id_usuario ."',
                            '". $this->id_cliente ."',
                            '". $this->fecha ."',
                            'Venta',
                            '". $this->descripcion ."',
                            '". $this->valor ."',
                            '". $this->notas ."')";

                    $consultar = parent::modificar_bdd($sql);
                    
                    return $consultar;
                
                } else if($tipo == 2){
                //    echo "Abono";
                   $sql = "INSERT INTO clientes_cxc (
                               id_usuario,
                               id_cliente,
                               fecha_cxc,
                               concepto_cxc,
                               descripcion,
                               abonos_cxc,
                               nota_clientes
                           )
                           VALUES
                           (
                           '". $this->id_usuario ."',
                           '". $this->id_cliente ."',
                           '". $this->fecha ."',
                           'Abono',
                           '". $this->descripcion ."',
                           '". $this->valor ."',
                           '". $this->notas ."')";

                   $consultar = parent::modificar_bdd($sql);
                   
                   return $consultar;
               
                }
            
            }
        // comprobar que el cuerpo de la peticion contenga todas los elementos necesarios
        private function comprobar_key_post($body){
            // comprobar que tiene los elementos necesarios
            if (count($body) == 6) {
                return 1;
            } else {
               return 0;
            }
            
             
        } 
        // comprobar que el cuerpo de la peticion contenga todas los elementos necesarios
        private function comprobar_key_put($body){
            // comprobar que tiene los elementos necesarios
            if (count($body) == 7) {
                return 1;
            } else {
               return 0;
            }
            
             
        }     
        # Metodo para hacer el INSERT al a base de datos
        private function insertar_transaccion(){
            $sql = "INSERT INTO clientes_cxc (
                        id_usuario,
                        id_cliente,
                        fecha_cxc,
                        concepto_cxc,
                        descripcion,
                        ventas_cxc,
                        abonos_cxc,
                        nota_clientes
                    )
                    VALUES
                    (
                      '". $this->id_usuario ."',
                      '". $this->id_cliente ."',
                      '". $this->fecha ."',
                      '". $this->concepto ."',
                      '". $this->descripcion ."',
                      '". $this->ventas ."',
                      '". $this->abonos ."',
                      '". $this->notas ."')";

            $consultar = parent::modificar_bdd($sql);
            
            return $consultar;
            
            
        }
      
        


    }

?>