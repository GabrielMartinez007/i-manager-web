<?php

require_once ("conexion/db.class.php");
require_once ("respuestas.class.php");
require_once ("auth.class.php");

class balance extends DB{
    private $id_cliente;
    
    public function get_id($headers,$id_cliente){
        $_auth = new auth();
        $_respuestas = new respuestas();


       $token =  $headers["auth"];


       $verificar = $_auth->validar_token($token);

       if ($verificar == 0) {
           echo json_encode($_respuestas->code_401("Token invalido"));

       } else {
              $sql = "SELECT
              id_cliente, 
              sum(ventas_cxc),
              sum(abonos_cxc),
              sum(ventas_cxc)-sum(abonos_cxc) 
              FROM 
              clientes_cxc 
              WHERE 
              id_cliente='$id_cliente'";

              $consultar = parent::leer_bdd($sql);
              
              if ($consultar) {
                  while ($fila = $consultar->fetch_assoc()) {
                     $elementos = [
                         "id" => $fila["id_cliente"],
                         "ventas" => $fila["sum(ventas_cxc)"],
                         "abonos" => $fila["sum(abonos_cxc)"],
                         "balance" => $fila["sum(ventas_cxc)-sum(abonos_cxc)"]
                    ];
                  }

                  if (array_sum($elementos) > 0) {
                     print_r($elementos);
                  } else {
                      echo "El cliente especificado no ha realizado ninguna transaccion";
                  }
                 
              } else {
                  echo '0';
              }
              
              
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

            $id_todos = $this->obtener_id_clientes($headers);
            $cuentas_por_cobrar = array();

            foreach ($id_todos as $key => $value) {
                
                $elementos = $this->obtener_balance($value['id']);
                array_push($cuentas_por_cobrar,$elementos);
            
            }
            print_r($cuentas_por_cobrar);

            //     $sql = "SELECT
            //     id_cliente, 
            //     sum(ventas_cxc),
            //     sum(abonos_cxc),
            //     sum(ventas_cxc)-sum(abonos_cxc) 
            //     FROM 
            //     clientes_cxc
            //     WHERE
            //     id_cliente='$id'";

            //     $consultar = parent::leer_bdd($sql);
            //     $balances = array();
            //     if ($consultar) {

            //         while ($fila = $consultar->fetch_assoc()) {
            //             $elementos = [
            //                 "id" => $fila["id_cliente"]
            //             ];

            //         }

            //         array_push($balances,$elementos);
                        
            //     } else {
            //         echo '0';
            //     }

            //     array_push($cuentas_por_cobrar,$balances);
            }

            
              
              

    }

    private function obtener_balance($id){
       
            $sql = "SELECT
            id_cliente, 
            sum(ventas_cxc),
            sum(abonos_cxc),
            sum(ventas_cxc)-sum(abonos_cxc) 
            FROM 
            clientes_cxc
            WHERE
            id_cliente='$id'";

            $consultar = parent::leer_bdd($sql);
              if ($consultar) {

                while ($fila = $consultar->fetch_assoc()) {
                    $elementos = [
                        "id" => $fila["id_cliente"],
                        "ventas" => $fila["sum(ventas_cxc)"],
                        "abonos" => $fila["sum(abonos_cxc)"],
                        "balance" => $fila["sum(ventas_cxc)-sum(abonos_cxc)"]
                    ];

                }

                return $elementos;
                    
            } else {
                return '0';
            }
        
    }

    private function obtener_id_clientes($headers){
       
        $_auth = new auth();
        $_respuestas = new respuestas();

       $token =  $headers["auth"];


       $verificar = $_auth->validar_token($token);

       if ($verificar == 0) {
           echo json_encode($_respuestas->code_401("Token invalido"));

       } else {
            $sql = "SELECT
            id_cliente
            FROM 
            clientes";

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
}
                      

?>