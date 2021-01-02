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
                    FROM suplidores_cxp
                    WHERE
                    id_usuario=" . $this->id_usuario ."
                    order by id_asiento_cxp desc";

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




        public function put ($headers,$json){
            echo $_SERVER["REQUEST_METHOD"];
        }

        public function delete ($headers,$json){
            echo $_SERVER["REQUEST_METHOD"];
        }

        private function nueva_transaccion($tipo,$valor){
            

                if ($tipo == 0) {
                    // ---> Compra
                    $sql = "INSERT INTO suplidores_cxp (
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
                    $sql = "INSERT INTO suplidores_cxp (
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





    }







?>