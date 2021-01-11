<?php

require_once ("conexion/db.class.php");
require_once ("respuestas.class.php");
require_once ("auth.class.php");

class suplidores extends DB{
    private $id_suplidor;
    private $id_usuario;
    private $id_establecimiento;
    private $nombre;
    private $nota;
    private $table = "suplidores";

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
                 FROM ".$this->table."
                 WHERE
                 id_usuario=" . $this->id_usuario ."
                 order by id_suplidores desc";

                     # parent::leer_bdd devuelve un objeto mysqli_result con toda la info.
                     $consultar = parent::leer_bdd($sql);

                     if ($consultar) {

                         $suplidores = array();

                         foreach ($consultar as $key => $value) {
                             $elementos["suplidores"] = [
                                 "id" => $value["id_suplidores"],
                                 "id_usuario" => $value["id_usuario"],
                                 "id_establecimiento" => $value["id_establecimiento"],
                                 "nombre" => $value["nombre_suplidores"],
                                 "notas" => $value["nota_suplidores"]


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
                 FROM ".$this->table."
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
                             $elementos["suplidores"] = [
                                 "id" => $value["id_suplidores"],
                                 "id_usuario" => $value["id_usuario"],
                                 "id_establecimiento" => $value["id_establecimiento"],
                                 "nombre" => $value["nombre_suplidores"],
                                 "notas" => $value["nota_suplidores"]


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
                                $this->nota = $body["notas"];
                                $consultar = $this->nuevo_suplidor();
                                if ($consultar > 0) {
                                    echo json_encode($_respuestas->code_200("Suplidor registrado correctamente"));
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

                        if ($body["nombre"] == "" || $body["id_suplidores"] == "") {
                            echo json_encode($_respuestas->code_400("Campos vacios"));


                        } else {

                                    $this->nombre = $body["nombre"];
                                    $this->id_suplidor = $body["id_suplidores"];
                                    $this->nota = $body["notas"];

                                    $consultar = $this->modificar_suplidor();

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

                                echo json_encode($_respuestas->code_400("Debe suministrar un id"));

                } else {
                    if (is_numeric($body["id_suplidores"])) {
                        $this->id_suplidor = $body["id_suplidores"];
                        $consultar = $this->eliminar_suplidor();
                        if ($consultar > 0) {
                            echo json_encode($_respuestas->code_200("suplidor eliminado"));
                        } else {
                            echo json_encode($_respuestas->code_500("El servidor no ha podido procesar la solicitud"));

                        }

                    }else{
                        echo json_encode($_respuestas->code_400("El id del suplidor debe ser un numero"));
                    }
                }
            }

        }
    }




    private function nuevo_suplidor(){
        $sql = "INSERT INTO ".$this->table." (
            id_usuario,
            nombre_suplidores,
            nota_suplidores
        )
        VALUES
        (
        '". $this->id_usuario ."',
        '". $this->nombre ."',
        '". $this->nota ."')";

        $consultar = parent::modificar_bdd($sql);

        return $consultar;

    }

    private function eliminar_suplidor(){
        $sql = "DELETE
                FROM ".$this->table."
                WHERE
                id_suplidores='".$this->id_suplidor."'";

           $consultar = parent::modificar_bdd($sql);

        return $consultar;
    }

    private function modificar_suplidor(){
        $sql = "UPDATE ".$this->table." SET
        nombre_suplidores='". $this->nombre ."',
        nota_suplidores='". $this->nota ."'
        WHERE
        id_suplidores='".$this->id_suplidor."'";


        // echo $sql;
        $consultar = parent::modificar_bdd($sql);

        return $consultar;

    }

}

?>