<?php
class empleados
{
    // Datos de la tabla "empleado"
    const NOMBRE_TABLA = "empleado";
    const ID_EMPLEADO = "id_empleado";
    const NOMBRE_EMP = "nombre_emp";
    const PRIMER_APELLIDO_EMP = "primer_apellido_emp";
    const SEGUNDO_APELLIDO_EMP = "segundo_apellido_emp";
    const PUESTO = "puesto";
    const ID_ESTAB = "id_estab";
    const CONTRASENA = "password_emp";
    const CORREO = "correo_emp";
    const CLAVE_API = "claveApi";

    const ESTADO_CREACION_EXITOSA = "Creación exitosa";
    const ESTADO_URL_INCORRECTA = "Ruta incorrecta";
    const ESTADO_CREACION_FALLIDA = "Creación fallida";
    const ESTADO_FALLA_DESCONOCIDA = "Falla desconocida";
    const ESTADO_ERROR_BD = "Error de Base de Datos";
    
    public static function post($peticion) {
        if ($peticion[0] == 'registro') {            
            return self::registrar();
        } else if ($peticion[0] == 'login') {
            return self::loguear();
        } else {
            throw new ExcepcionApi(self::ESTADO_URL_INCORRECTA, "Url mal formada", 400);
        }
    }    
   
    private function registrar() {
        $cuerpo = file_get_contents('php://input');
        $empleado = json_decode($cuerpo);        
        $resultado = self::crear($empleado);

        switch ($resultado) {
            case self::ESTADO_CREACION_EXITOSA:
                http_response_code(200);
                return
                    [
                        "estado" => self::ESTADO_CREACION_EXITOSA,
                        "mensaje" => utf8_encode("¡Registro con éxito!")
                    ];
                break;
            case self::ESTADO_CREACION_FALLIDA:
                throw new ExcepcionApi(self::ESTADO_CREACION_FALLIDA, "Ha ocurrido un error");
                break;
            default:
                throw new ExcepcionApi(self::ESTADO_FALLA_DESCONOCIDA, "Falla desconocida", 400);
        }
    }

    private function crear($datosEmpleado) {        
        $nombre_emp = $datosEmpleado->nombre_emp;
        $primer_apellido_emp = $datosEmpleado->primer_apellido_emp;
        $segundo_apellido_emp = $datosEmpleado->segundo_apellido_emp;
        $puesto = $datosEmpleado->puesto;
        $id_empleado = $datosEmpleado->id_empleado;
        $password_emp = $datosEmpleado->password_emp;
        $contrasenaEncriptada = self::encriptarContrasena($password_emp);
        $correo_emp = $datosEmpleado->correo_emp;
        $claveApi = self::generarClaveApi();

        try {

            $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

            // Sentencia INSERT
            $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .

                self::NOMBRE_EMP . "," .
                self::PRIMER_APELLIDO_EMP . "," .
                self::SEGUNDO_APELLIDO_EMP . "," .
                self::PUESTO . "," .
                self::PASSWORD_EMP . "," .
                self::ID_ESTAB . "," .
                self::CLAVE_API . "," .
                self::CORREO_EMP . ")" .
                " VALUES(?,?,?,?,?,?,?,?)";


            $sentencia = $pdo->prepare($comando);

            $sentencia->bindParam(1, $nombre_emp);
            $sentencia->bindParam(2, $primer_apellido_emp);
            $sentencia->bindParam(3, $segundo_apellido_emp);
            $sentencia->bindParam(4, $puesto);
            $sentencia->bindParam(5, $password_empEncriptada);
            $sentencia->bindParam(6, $id_estab);
            $sentencia->bindParam(7, $claveApi);
            $sentencia->bindParam(8, $correo_emp);

            $resultado = $sentencia->execute();

            if ($resultado) {
                return self::ESTADO_CREACION_EXITOSA;
            } else {
                return self::ESTADO_CREACION_FALLIDA;
            }
        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }

    private function encriptarContrasena($contrasenaPlana) {
        if ($contrasenaPlana)
            return password_hash($contrasenaPlana, PASSWORD_DEFAULT);
        else return null;
    }

    private function generarClaveApi() {
        return md5(microtime().rand());
    }

}

?>