<?php
// archivo para realizar la conexion a la base de datos 
class DB {

    private $host;
    private $user;
    private $db;
    private $password;
    private $conexion;
    
    public function __construct() {
        $datos = $this->leer_archivo_config();
   
        foreach ($datos as $key => $value) {
            $this->host = $value["host"];
            $this->user =  $value["user"]; 
            $this->db = $value["db"];
            $this->password = $value["password"]; 
        }

        $this->conexion = new mysqli($this->host,$this->user,$this->password,$this->db);

        if ($this->conexion->connect_errno) {
            return 0;
            die();
        }else {
            return 1;
        }
        
    }


    private function leer_archivo_config(){
        // $ruta = __DIR__ . "\config" ;
        $ruta = "../../imanager_config/config" ;

        $archivo = file_get_contents($ruta);
        // return json_encode($archivo);
        return json_decode($archivo, true);
        

    }


    # Verficiar cuantas filas devuelve el SELECT
    public function consultar_bdd($sql){
        $resultados = $this->conexion->query($sql);
       
        return $resultados->num_rows;
    }

    public function leer_bdd($sql){
        $resultados = $this->conexion->query($sql);
        return $resultados;
    }

    # Todas las operaciones que modificaran la bdd (INSERT,DELETE,PUT)
    public function modificar_bdd($sql){
        $consulta = $this->conexion->query($sql);
        $respuesta = $this->conexion->affected_rows;
        if ($respuesta > 0) {
            return 1;
        } else {
            return 0;
        }
        
        // return $this->conexion->affected_rows;
    }

    # Todas las operaciones que modificaran la bdd (INSERT,DELETE,PUT) - Investigar funcion insert_id()
    public function modificar_bdd_id($sql){
        $resultados = $this->conexion->query($sql);
        $filas = $this->conexion->affected_rows;
        if ($filas >= 1) {
            return $this->conexion->insert_id;
        } else {
            return 0;
        }
        
    }

    # encriptar la contraseña
    protected function encriptar_pass($password){
       return md5($password); 
    }

    public function noJson($json){
        $_respuestas = new respuestas();
        $datos = json_decode($json,true);
        return $datos;
    }

        # Tomar el id del usuario mediante el token
        /**
         *  El parametro es un token que ya esté verificado.
         * 
         */


    public function id_usuario($resultado_token_verificado){
        $_respuestas = new respuestas();

        # En caso contrario buscamos el id_usuario asignado a ese token
        /*
            Si por alguna razon, el parametro que le llega al id_usuario no es un array, entonces será cero.
        
        
        */
        if (is_array($resultado_token_verificado)) {
            $usuario = array();
            foreach ($resultado_token_verificado as $key => $value) {
                $usuario = [
                    "id" => $value['id_usuario']
                ];
            }
            # retornamos el id del usuario
            return $usuario["id"];
        } else {
            return 0;

        }
        
        
    }

    public function array_vacio($array){
    # Verifica todos los elementos de un array para verificar si uno de esos se encuentra vacio. 
        foreach ($array as $key => $value) {
            $valor = empty($value);

            if ($valor) {
                return $valor;
                die;
            }
        
            
        }

        return $valor;
    }

    # validar que una fecha sea valida
    public function validar_fecha($fecha){
        # validamos que la fecha cuente con la cantidad de caracteres necesarios
        if (strlen($fecha) == 10) {
            // convertimos una fecha en un array para recorrerlo
            str_split($fecha);

            // Validamos que en el elemento 4 y 7 el valor sea un guion, de lo contrario la fecha no es valida
            if ($fecha[4] != "-") {
                return 0;
            } else {
                if ($fecha[7] != "-") {
                   return 0;
                } else {
                   return 1;
                }
                
            }
        }else {
            return 0;
        }
    }
        
}




?>