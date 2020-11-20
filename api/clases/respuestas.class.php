<?php

    class respuestas {
        public $respuesta = [
            "codigo" => 200,
            "resultado" => array()
        ];


        public function code_200($mensaje = "Ok"){
            $this->respuesta["codigo"] = '200';
            $this->respuesta["resultado"] = array(
                'id' => '200',
                'tipo' => 'Ok',
                'mensaje' =>utf8_encode($mensaje) 
            );

            return $this->respuesta;
        }
 
        public function code_400($mensaje = "Peticion incorrecta"){
            $this->respuesta["codigo"] = '400';
            $this->respuesta["resultado"] = array(
                'id' => '400',
                'tipo' => 'Bad Request',
                'mensaje' => utf8_encode($mensaje) 
            );

            return $this->respuesta;
        }

        public function code_401($mensaje = "Debe autenticarse"){
            $this->respuesta["codigo"] = '401'; 
            $this->respuesta["resultado"] = array(
                'id' => '401',
                'tipo' => 'Unauthorized',
                'mensaje' => utf8_encode($mensaje) 
            );

            return $this->respuesta;
        }

        public function code_404($mensaje = "Not Found"){
            $this->respuesta["codigo"] = '404';
            $this->respuesta["resultado"] = array(
                'id' => '404',
                'mensaje' =>utf8_encode($mensaje) 
            );

            return $this->respuesta;
        }
        public function code_405($mensaje = "Metodo desahibilitado"){
            $this->respuesta["codigo"] = '405';
            $this->respuesta["resultado"] = array(
                'id' => '405',
                'tipo' => 'Method not Allowed',
                'mensaje' => utf8_encode($mensaje) 
            );

            return $this->respuesta;
        }

        public function code_500($mensaje = "El servidor no puede procesar la solicitud"){
            $this->respuesta["codigo"] = '500';
            $this->respuesta["resultado"] = array(
                'id' => '500',
                'tipo' => 'Internal server error',
                'mensaje' =>utf8_encode($mensaje) 
            );

            return $this->respuesta;
        }
    }

?>