<?php
// NOTA
/*
    
    Este archivo obtiene el balance pendiente de un suplidor o de todos los suplidores. 

*/
require_once ("conexion/db.class.php");
require_once ("respuestas.class.php");
require_once ("auth.class.php");

class suplidores_balance extends DB{

    private $id_suplidores;
    private $table = "suplidores_cxp";
    private $table_relacionada = "suplidores";
    
    public function get($headers){
        $_auth = new auth();
        $_respuestas = new respuestas();

        $token =  $headers["auth"];

        $verificar = $_auth->validar_token($token);

       if ($verificar == 0) {
           echo json_encode($_respuestas->code_401("Token invalido"));

       } else {
           /*
           explicacion de la consulta: 

            Hago un SELECT a la tabla principal ($this->table) con los criterios especificados, pero ademas, le digo que de la tabla suplidores
            solo me traiga el nombre del suplidor
            con el inner join elazo la tabla suplidores a la tabla principal.
            con el ON creo una regla, la cual es que solo me traiga los registros donde el id_suplidores sea igual
            y por ultimo, la regla principal, que el id_suplidor goblal es, en este caso, el numero 4. 

            traerá la suma de las compras, los pagos, el nombre y el id, del suplidor numero 4.
                    
            SELECT suplidores_cxp.id_suplidores, 
            sum(compras_cxp), 
            sum(pagos_cxp), 
            sum(compras_cxp)-sum(pagos_cxp), 
            suplidores.nombre_suplidores 
            FROM suplidores_cxp 
            INNER JOIN suplidores ON suplidores_cxp.id_suplidores = suplidores.id_suplidores 
            WHERE suplidores_cxp.id_suplidores=4

           */

              $cuentas_por_pagar = array();

              $suplidores = $this->obtener_id_suplidores();
  
              foreach ($suplidores as $key => $value) {
                 $balance_suplidores = $this->obtener_balance($value["id"]);
                 array_push($cuentas_por_pagar,$balance_suplidores);
  
              }
  
                  print_r($cuentas_por_pagar);
              
              
              
              
       }

    }

    // obtiene el balance de un solo suplidor
    public function get_id($headers,$id){
        $_auth = new auth();
        $_respuestas = new respuestas();

        $token =  $headers["auth"];

        $verificar = $_auth->validar_token($token);

       if ($verificar == 0) {
           echo json_encode($_respuestas->code_401("Token invalido"));

       } else {
          

          print_r($this->obtener_balance($id));


       }

    }

    private function obtener_balance($id){
     
        // ---> La explicacion de esta consulta SQL es la misma de suplidores.

        $sql="SELECT ".$this->table_relacionada.".id_suplidores, 
        sum(compras_cxp), 
        sum(pagos_cxp), 
        sum(compras_cxp)-sum(pagos_cxp), 
        ". $this->table_relacionada .".nombre_suplidores 
        FROM 
        ". $this->table_relacionada ." 
        JOIN 
        ". $this->table ."
        ON 
        ". $this->table_relacionada .".id_suplidores = ". $this->table .".id_suplidores 
        WHERE 
        ". $this->table_relacionada .".id_suplidores='$id'";

        $consultar = parent::leer_bdd($sql);
          if ($consultar) {

            while ($fila = $consultar->fetch_assoc()) {

                    $elementos = [
                                "id" => $fila["id_suplidores"],
                                "nombre" => $fila["nombre_suplidores"],
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
            ".$this->table_relacionada."";

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