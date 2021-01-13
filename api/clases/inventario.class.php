<?php
 require_once ("conexion/db.class.php");
 require_once ("respuestas.class.php");
 require_once ("auth.class.php");
 // Campos:
 // id_movimiento
 // id_producto
 // fecha_mov
 // compra_mov
 // venta_mov
 // transformacion_mov
 // merma_mov
 // donacion_mov

 class inventario extends DB{

    private $id_movimiento;
    private $id_producto;
    private $fecha_mov;
    private $tipo_mov;
    private $cantidad_mov;
    private $table = "inventario";
    private $table_relacionada = "productos";
    
   
    public function get($headers){
        $_auth = new auth();
        $_respuestas = new respuestas();

        $token =  $headers["auth"];

        $verificar = $_auth->validar_token($token);

       if ($verificar == 0) {
           echo json_encode($_respuestas->code_401("Token invalido"));

       } else {
     
               $inventario = array();

              $productos = $this->obtener_id_productos();
  
              foreach ($productos as $key => $value) {
                 $stock_producto = $this->obtener_stock($value["id"]);
                 array_push($inventario,$stock_producto);
  
              }
  
                  print_r($inventario);
              
              
              
              
       }

    }

    // obtiene el stock de un producto
    public function get_id($headers,$id){
        $_auth = new auth();
        $_respuestas = new respuestas();

        $token =  $headers["auth"];

        $verificar = $_auth->validar_token($token);

       if ($verificar == 0) {
           echo json_encode($_respuestas->code_401("Token invalido"));

       } else {
          

          print_r($this->obtener_stock($id));


       }

    }

    // enviaremos el tipo de movimiento en una variable que se llame tipo:
    /*
        0 = compra
        1 = venta
        2 = transformacion
        3 = merma
        4 = donacion
    
    */
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
                        if ($body["tipo"] == "") {
                            echo json_encode($_respuestas->code_400("Falta el tipo de transaccion"));


                        } else {
                          
                                $this->id_producto  = $body["id_producto"];
                                $this->fecha_mov = $body["fecha_mov"];
                                $tipo = $body["tipo"];
                                $this->cantidad_mov = $body["cantidad_mov"];

                                 $consultar = $this->nuevo_movimiento($tipo);
                                if ($consultar > 0) {
                                    http_response_code(201);
                                    echo json_encode($_respuestas->code_201("Transaccion registrada correctamente"));
                                } else {
                                    http_response_code(500);
                                    echo json_encode($_respuestas->code_500("El servidor no ha podido procesar la solicitud"));

                                }

                        }
                    }

            }
    }

    public function put($headers,$json){
        
    }

    public function delete($headers,$json){
        
    }


    private function nuevo_movimiento($tipo){
        
        if ($tipo == 0) {
        // compra

            $sql = "INSERT INTO " . $this->table ." (
                id_usuario,
                id_producto,
                fecha_mov,
                compra_mov
            )
            VALUES
            (
            '". $this->id_usuario ."',
            '". $this->id_producto ."',
            '". $this->fecha_mov ."',
            '". $this->cantidad_mov ."')";

            $consultar = parent::modificar_bdd($sql);
    
            return $consultar;

        } else if ($tipo == 1) {
        // venta


            $sql = "INSERT INTO " . $this->table ." (
                id_usuario,
                id_producto,
                fecha_mov,
                venta_mov
            )
            VALUES
            (
            '". $this->id_usuario ."',
            '". $this->id_producto ."',
            '". $this->fecha_mov ."',
            '". $this->cantidad_mov ."')";

            $consultar = parent::modificar_bdd($sql);
    
            return $consultar;

        }else if ($tipo == 2) {
        // transformacion

            $sql = "INSERT INTO " . $this->table ." (
                id_usuario,
                id_producto,
                fecha_mov,
                transformacion_mov
            )
            VALUES
            (
            '". $this->id_usuario ."',
            '". $this->id_producto ."',
            '". $this->fecha_mov ."',
            '". $this->cantidad_mov ."')";

            $consultar = parent::modificar_bdd($sql);
    
            return $consultar;

        }else if ($tipo == 3) {
        // merma

            $sql = "INSERT INTO " . $this->table ." (
                id_usuario,
                id_producto,
                fecha_mov,
                merma_mov
            )
            VALUES
            (
            '". $this->id_usuario ."',
            '". $this->id_producto ."',
            '". $this->fecha_mov ."',
            '". $this->cantidad_mov ."')";

            $consultar = parent::modificar_bdd($sql);
    
            return $consultar;

        }else if ($tipo == 4) {
        // donacion

            $sql = "INSERT INTO " . $this->table ." (
                id_usuario,
                id_producto,
                fecha_mov,
                donacion_mov
            )
            VALUES
            (
            '". $this->id_usuario ."',
            '". $this->id_producto ."',
            '". $this->fecha_mov ."',
            '". $this->cantidad_mov ."')";

            $consultar = parent::modificar_bdd($sql);
    
            return $consultar;

        } else if ($tipo > 4) {
        // Tipo invalido

            return 0;
        }
    }
    private function obtener_stock($id){
     
        // ---> La explicacion de esta consulta SQL es la misma de suplidores.
 // id_producto
 // fecha_mov
 // compra_mov
 // venta_mov
 // transformacion_mov
 // merma_mov
 // donacion_mov
        $sql="SELECT ".$this->table_relacionada.".id_producto, 
        sum(compra_mov), 
        sum(venta_mov), 
        sum(transformacion_mov), 
        sum(merma_mov), 
        sum(donacion_mov), 
        sum(compra_mov)-sum(venta_mov)-sum(transformacion_mov)-sum(merma_mov)-sum(donacion_mov), 
        ". $this->table_relacionada .".nombre_producto 
        FROM 
        ". $this->table_relacionada ." 
        JOIN 
        ". $this->table ."
        ON 
        ". $this->table_relacionada .".id_producto = ". $this->table .".id_producto 
        WHERE 
        ". $this->table_relacionada .".id_producto='$id'";

        $consultar = parent::leer_bdd($sql);
          if ($consultar) {

            while ($fila = $consultar->fetch_assoc()) {

                    $elementos["items"] = [
                                "id" => $fila["id_producto"],
                                "nombre" => $fila["nombre_producto"],
                                "compra_mov" => $fila["sum(compra_mov)"],
                                "venta_mov" => $fila["sum(venta_mov)"],
                                "transformacion_mov" => $fila["sum(transformacion_mov)"],
                                "merma_mov" => $fila["sum(merma_mov)"],
                                "donacion_mov" => $fila["sum(donacion_mov)"],
                                "Stock" => $fila["sum(compra_mov)-sum(venta_mov)-sum(transformacion_mov)-sum(merma_mov)-sum(donacion_mov)"]
                            ];
                  
                
                }
          
            return $elementos;
            // return $sql;
                
        } else {
            return '0';
        }
    
}

private function obtener_id_productos(){
       
    $sql = "SELECT
    id_producto
    FROM 
    ".$this->table_relacionada."";

    $consultar = parent::leer_bdd($sql);

      if ($consultar) {

            $array_id = array();

            while ($fila = $consultar->fetch_assoc()) {
             $elementos = [
                 "id" => $fila["id_producto"]
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