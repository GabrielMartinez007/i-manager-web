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
    private $table_relacionada = "suplidores_cxp";

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

                $cuentas_por_pagar = array();

                $suplidores = $this->obtener_id_suplidores();
    
                foreach ($suplidores as $key => $value) {
                   $balance_suplidores = $this->obtener_suplidor($value["id"]);
                   array_push($cuentas_por_pagar,$balance_suplidores);
    
                }
    
                echo json_encode($cuentas_por_pagar);
                        
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
                if ($verificar == 0) {
                    echo json_encode($_respuestas->code_401("Token invalido"));
         
                } else {
                   
         
                 echo json_encode($this->obtener_suplidor($id));
         
         
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
    
    private function obtener_suplidor($id){
        
        $sql="SELECT ".$this->table.".id_suplidores, 
        ".$this->table.".nota_suplidores, 
        sum(compras_cxp), 
        sum(pagos_cxp), 
        sum(compras_cxp)-sum(pagos_cxp), 
        ".$this->table.".nombre_suplidores 
        FROM 
        ".$this->table."
        JOIN 
        ". $this->table_relacionada ."
        ON 
        ".$this->table.".id_suplidores = ".$this->table.".id_suplidores 
        WHERE 
        ".$this->table.".id_suplidores='$id'";

        $consultar = parent::leer_bdd($sql);
          if ($consultar) {

            while ($fila = $consultar->fetch_assoc()) {

                    $elementos = [
                                "id" => $fila["id_suplidores"],
                                "nombre" => $fila["nombre_suplidores"],
                                "notas" => $fila["nota_suplidores"],
                                "compras" => $fila["sum(compras_cxp)"],
                                "pagos" => $fila["sum(pagos_cxp)"],
                                "balance" => $fila["sum(compras_cxp)-sum(pagos_cxp)"]
                            ];
                  
                
                }
          
            return $elementos;
                
        } else {
            return '0';
        }

}

private function obtener_id_suplidores(){

    $sql = "SELECT
    id_suplidores
    FROM 
    ".$this->table."";

    $consultar = parent::leer_bdd($sql);

      if ($consultar) {

            $array_id = array();

            while ($fila = $consultar->fetch_assoc()) {
             $elementos = [
                 "id" => $fila["id_suplidores"]
            ];

                array_push($array_id,$elementos);
          }

            return $array_id;
            
    } else {
        return 0;
    }
}


}

?>