<?php
    require_once ("conexion/db.class.php");
    require_once ("respuestas.class.php");
    require_once ("suplidores.class.php");
    require_once ("auth.class.php");

    class suplidores_cxp extends DB{
     
         private $id_asiento;
         private $id_usuario;
         private $id_establecimiento;
         private $fecha;
         private $id_suplidores;
         private $tipo; // Tipo de transaccion es si es un pago o una compra
         private $descripcion;
         private $compra;
         private $pagos;
         private $valor;
         private $notas;
         private $table = "suplidores_cxp";

        //  public function __construct() {
        //      $this->table = 
        //  }

        public function get ($json){
            $_auth = new auth();
            $_respuestas = new respuestas();
            
            $auth = $json["auth"];

            $verificar = $_auth->validar_token($auth);

                if ($verificar == 0) {
                    # Si el token no es valido, dirá lo siguiente:
                    echo json_encode($_respuestas->code_401("Token invalido"));
        
                } else {
                    # la funcion id_usuario devuelve el id del usuario de un token verificado
                    $this->id_usuario = parent::id_usuario($verificar);
            
                    $sql = "SELECT * 
                    FROM ".$this->table ."
                    WHERE
                    id_usuario=" . $this->id_usuario ."
                    order by id_asiento_cxp desc";
                    // echo $sql;
                        # parent::leer_bdd devuelve un objeto mysqli_result con toda la info.
                        $consultar = parent::leer_bdd($sql);

                        if ($consultar) {
                            
                            $transacciones = array();
                            
                            foreach ($consultar as $key => $value) {
                                $elementos["transacciones"] = [
                                    "asiento" => $value["id_asiento_cxp"],
                                    "id_suplidores" => $value["id_suplidores"],
                                    "id_usuario" => $value["id_usuario"],
                                    "id_establecimiento" => $value["id_establecimiento"],
                                    "fecha" => $value["fecha_cxp"],
                                    "concepto" => $value["concepto_cxp"],
                                    "descripcion" => $value["descripcion_cxp"],
                                    "compras" => $value["compras_cxp"],
                                    "pagos" => $value["pagos_cxp"],
                                    "notas" => $value["nota_cxp"]


                                ];
                                
                                array_push($transacciones,$elementos);
                            }

                                http_response_code(200);
                                header("content-type: application/json; charset=UTF-8");
                                // echo json_encode($transacciones);
                                print_r($transacciones);

                        } else {

                                http_response_code(500);
                                header("content-type: application/json; charset=UTF-8");
                                echo json_encode($_respuestas->code_500("No se han podido obtener datos. "));

                        }

                }
        }

        public function get_id($id,$json){
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
                     FROM ". $this->table ."
                     WHERE
                     id_usuario=" . $this->id_usuario ."
                     AND
                     id_suplidores='$id'
                     order by id_suplidores desc";
    
                         # parent::leer_bdd devuelve un objeto mysqli_result con toda la info.
                         $consultar = parent::leer_bdd($sql);
    
                         if ($consultar) {
    
                             $suplidores = array();
    
                             foreach ($consultar as $key => $value) {
                                $elementos["transacciones"] = [
                                    "asiento" => $value["id_asiento_cxp"],
                                    "id_suplidores" => $value["id_suplidores"],
                                    "id_usuario" => $value["id_usuario"],
                                    "id_establecimiento" => $value["id_establecimiento"],
                                    "fecha" => $value["fecha_cxp"],
                                    "concepto" => $value["concepto_cxp"],
                                    "descripcion" => $value["descripcion_cxp"],
                                    "compras" => $value["compras_cxp"],
                                    "pagos" => $value["pagos_cxp"],
                                    "notas" => $value["nota_cxp"]


                                ];
    
                                 array_push($suplidores,$elementos);
                             }
    
                                 http_response_code(200);
                                 header("content-type: application/json; charset=UTF-8");
                                //  echo json_encode($clientes);
                                 print_r($suplidores);
    
                         } else {
    
                                 http_response_code(500);
                                 header("content-type: application/json; charset=UTF-8");
                                 echo json_encode($_respuestas->code_500("No se han podido obtener datos. "));
    
                         }
    
                 }
    
         }

         public function get_asiento($id,$json){
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
                     FROM ". $this->table ."
                     WHERE
                     id_usuario=" . $this->id_usuario ."
                     AND
                     id_asiento_cxp='$id'
                     order by id_suplidores desc";
    
                         # parent::leer_bdd devuelve un objeto mysqli_result con toda la info.
                         $consultar = parent::leer_bdd($sql);
    
                         if ($consultar) {
    
                             $suplidores = array();
    
                             foreach ($consultar as $key => $value) {
                                $elementos["transacciones"] = [
                                    "asiento" => $value["id_asiento_cxp"],
                                    "id_suplidores" => $value["id_suplidores"],
                                    "id_usuario" => $value["id_usuario"],
                                    "id_establecimiento" => $value["id_establecimiento"],
                                    "fecha" => $value["fecha_cxp"],
                                    "concepto" => $value["concepto_cxp"],
                                    "descripcion" => $value["descripcion_cxp"],
                                    "compras" => $value["compras_cxp"],
                                    "pagos" => $value["pagos_cxp"],
                                    "notas" => $value["nota_cxp"]


                                ];
    
                                 array_push($suplidores,$elementos);
                             }
    
                                 http_response_code(200);
                                 header("content-type: application/json; charset=UTF-8");
                                //  echo json_encode($clientes);
                                 print_r($suplidores);
    
                         } else {
    
                                 http_response_code(500);
                                 header("content-type: application/json; charset=UTF-8");
                                 echo json_encode($_respuestas->code_500("No se han podido obtener datos. "));
    
                         }
    
                 }
    
         }
        public function post ($headers,$json){
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
                            if ($body["tipo"] == "") {
                                echo json_encode($_respuestas->code_400("Falta el tipo de transaccion"));


                            } else {
                                
                                    $this->id_suplidores  = $body["id_suplidores"];
                                    $this->tipo = $body["tipo"];
                                    $this->fecha = $body["fecha"];
                                    $this->descripcion = $body["descripcion_cxp"];
                                    $this->valor = $body["valor"];
                                    $this->notas = $body["notas"];

                                    $consultar = $this->nueva_transaccion($this->tipo,$this->valor);
                                    if ($consultar > 0) {
                                        echo json_encode($_respuestas->code_200("Transaccion registrada correctamente"));
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
                            // echo "Token validado, el id del usuario es: $id_usuario";

                                if (is_numeric($body["valor"])) {
                                    if (is_numeric($body["id_suplidores"])) {
                                        
                                        $fecha_valida = parent::validar_fecha($body["fecha"]);

                                        if ($fecha_valida == 1) {
                                            if (is_numeric($body["tipo"])) {
                                                if ($body["tipo"] < 3) {
                                                     
                                                    $this->id_asiento = $body["id_asiento"];                                    
                                                    $this->id_suplidores = $body["id_suplidores"];
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
                    
                    if (is_numeric($body["id_asiento"])) {

                        $this->id_asiento = $body["id_asiento"];

                        $sql = "DELETE  
                        FROM ".$this->table."
                        WHERE 
                        id_asiento_cxp='".$this->id_asiento."' 
                        AND
                        id_usuario ='".$this->id_usuario."'";

                        $consultar = parent::modificar_bdd($sql);


                        if ($consultar > 0) {
                                    
                            echo json_encode($_respuestas->code_200("Asiento eliminado correctamente"));

                        } else {
                                    
                            echo json_encode($_respuestas->code_500("no se pudo eliminar el asiento"));

                        }

                    }else{
                          
                        echo json_encode($_respuestas->code_400("El id_asiento debe ser un numero"));
                                            
                    }
                                         
                }
            }
        
        }

        private function nueva_transaccion($tipo,$valor){
            

                if ($tipo == 0) {
                    // ---> Compra
                    $sql = "INSERT INTO " . $this->table ." (
                        id_usuario,
                        id_suplidores,
                        fecha_cxp,
                        descripcion_cxp,
                        concepto_cxp,
                        compras_cxp,
                        nota_cxp
                    )
                    VALUES
                    (
                    '". $this->id_usuario ."',
                    '". $this->id_suplidores ."',
                    '". $this->fecha ."',
                    '". $this->descripcion ."',
                    'Compra',
                    '". $valor."',
                    '". $this->notas ."')";

                    $consultar = parent::modificar_bdd($sql);
            
                    return $consultar;
                } else if ($tipo == 1) {
                    // ---> Abono
                    $sql = "INSERT INTO ". $this->table ."(
                        id_usuario,
                        id_suplidores,
                        fecha_cxp,
                        descripcion_cxp,
                        concepto_cxp,
                        pagos_cxp,
                        nota_cxp
                    )
                    VALUES
                    (
                    '". $this->id_usuario ."',
                    '". $this->id_suplidores ."',
                    '". $this->fecha ."',
                    '". $this->descripcion ."',
                    'Abono',
                    '". $valor."',
                    '". $this->notas ."')";

                    $consultar = parent::modificar_bdd($sql);
            
                    return $consultar;

                } else if ($tipo > 1) {
                    echo "Tipo invalido";
                }
                


        }

       
        private function modificar_transaccion($tipo){
            # 1 = compra y 2 = pago
            if ($tipo == 1) {
                $sql = "UPDATE ". $this->table ." SET 
                            id_suplidores='". $this->id_suplidores ."',
                            fecha_cxp='". $this->fecha ."',
                            concepto_cxp='Compra',
                            descripcion_cxp='". $this->descripcion ."',
                            compras_cxp='". $this->valor ."',
                            nota_cxp='". $this->notas ."' 
                        WHERE 
                        id_asiento_cxp='".$this->id_asiento."'";
                        

               $consultar = parent::modificar_bdd($sql);
            //    echo $sql;
               
                return $consultar;

               

            } else if($tipo == 2){
            //    echo "Pago";
            $sql = "UPDATE ". $this->table ." SET 
                            id_suplidores='". $this->id_suplidores ."',
                            fecha_cxp='". $this->fecha ."',
                            concepto_cxp='Abono',
                            descripcion_cxp='". $this->descripcion ."',
                            pagos_cxp='". $this->valor ."',
                            nota_cxp='". $this->notas ."' 
                        WHERE 
                        id_asiento_cxp='".$this->id_asiento."'";

            // echo $sql;   

            $consultar = parent::modificar_bdd($sql);
            
            return $consultar;

            }
        }



    }







?>