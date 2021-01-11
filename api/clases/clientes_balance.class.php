<?php

require_once ("conexion/db.class.php");
require_once ("respuestas.class.php");
require_once ("auth.class.php");

class clientes_balance extends DB{

    private $id_cliente;
    private $id_usuario;
    private $table = "clientes_cxc";
    private $table_relacionada = "clientes";
    
    //---Metodos

    public function get_id($headers,$id_cliente){
        $_auth = new auth();
        $_respuestas = new respuestas();


       $token =  $headers["auth"];


       $verificar = $_auth->validar_token($token);

       if ($verificar == 0) {
           echo json_encode($_respuestas->code_401("Token invalido"));

       } else {

            print_r($this->obtener_balance($id_cliente));

              
       }

    }

    public function get($headers){
        $_auth = new auth();
        $_respuestas = new respuestas();


       $token =  $headers["auth"];


       $verificar = $_auth->validar_token($token);

       if ($verificar == 0) {
           echo json_encode($_respuestas->code_401("Token invalido"));

       } else {

            $cuentas_por_cobrar = array();

            $clientes = $this->obtener_id_clientes();

            foreach ($clientes as $key => $value) {
               $balance_cliente = $this->obtener_balance($value["id"]);
               array_push($cuentas_por_cobrar,$balance_cliente);

            }

                print_r($cuentas_por_cobrar);
            
            }

    }

    private function obtener_balance($id){
         
            //   $sql = "SELECT
            //   id_cliente, 
            //   sum(ventas_cxc),
            //   sum(abonos_cxc),
            //   sum(ventas_cxc)-sum(abonos_cxc) 
            //   FROM 
            //   clientes_cxc 
            //   WHERE 
            //   id_cliente='$id_cliente'";

        // ---> La explicacion de esta consulta SQL es la misma de suplidores.

        $sql="SELECT ".$this->table_relacionada.".id_cliente, 
        sum(ventas_cxc), 
        sum(abonos_cxc), 
        sum(ventas_cxc)-sum(abonos_cxc), 
        ". $this->table_relacionada .".nombre_cliente 
        FROM 
        ". $this->table_relacionada ." 
        JOIN 
        ". $this->table ."
        ON 
        ". $this->table_relacionada .".id_cliente = ". $this->table .".id_cliente 
        WHERE 
        ". $this->table_relacionada .".id_cliente='$id'";

            $consultar = parent::leer_bdd($sql);
              if ($consultar) {

                while ($fila = $consultar->fetch_assoc()) {

                        $elementos = [
                                    "id" => $fila["id_cliente"],
                                    "nombre_cliente" => $fila["nombre_cliente"],
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
            ".$this->table_relacionada."";

            $consultar = parent::leer_bdd($sql);

              if ($consultar) {

                    $id_clientes = array();

                    while ($fila = $consultar->fetch_assoc()) {
                     $elementos = [
                         "id" => $fila["id_cliente"]
                    ];

                        array_push($id_clientes,$elementos);
                  }

                    return $id_clientes;
                    
            } else {
                return 0;
            }
       
    }




}
                      

?>