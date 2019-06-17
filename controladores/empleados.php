<?php

include_once 'datos/ConexionBD.php';
//require_once '../utilidades/nusoap.php';
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
    const PASSWORD_EMP = "password_emp";
    const CORREO_EMP = "correo_emp";
    const CLAVE_API = "claveApi";

    const ESTADO_CREACION_EXITOSA = 1;
    const ESTADO_CREACION_FALLIDA = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_AUSENCIA_CLAVE_API = 4;
    const ESTADO_CLAVE_NO_AUTORIZADA = 5;
    const ESTADO_URL_INCORRECTA = 6;
    const ESTADO_FALLA_DESCONOCIDA = 7;
    const ESTADO_PARAMETROS_INCORRECTOS = 8;

    public static function post($peticion)
    {
        if ($peticion[0] == 'registro') {
            return self::registrar();
        } else if ($peticion[0] == 'login') {
            return self::loguear();
        } else {
            throw new ExcepcionApi(self::ESTADO_URL_INCORRECTA, "Url mal formada", 400);
        }
    }


    /**
     * Crea un nuevo empleado en la base de datos
     */
    private function registrar()
    {
        $cuerpo = file_get_contents('php://input');
        $empleado = json_decode($cuerpo);
        $resultado = self::crear($empleado);

        switch ($resultado) {
            case self::ESTADO_CREACION_EXITOSA:
                http_response_code(200);
                return
                    [
                        "estado" => self::ESTADO_CREACION_EXITOSA,
                        "mensaje" => utf8_encode("Registro exitoso!")
                    ];
                break;
            case self::ESTADO_CREACION_FALLIDA:
                throw new ExcepcionApi(self::ESTADO_CREACION_FALLIDA, "Ha ocurrido un error");
                break;
            default:
                throw new ExcepcionApi(self::ESTADO_FALLA_DESCONOCIDA, "Falla desconocida", 400);
        }
    }

    /**
     * Crea un nuevo empleado en la tabla "empleado"
     * @param mixed $datosEmpleado columnas del registro
     * @return int codigo para determinar si la inserción fue exitosa
     */
    private function crear($datosEmpleado)
    {
        $nombre_emp = $datosEmpleado->nombre_emp;
        $primer_apellido_emp = $datosEmpleado->primer_apellido_emp;
        $segundo_apellido_emp = $datosEmpleado->segundo_apellido_emp;
        $puesto = $datosEmpleado->puesto;
        $id_estab = $datosEmpleado->id_estab;
        $password_emp = $datosEmpleado->password_emp;
        $password_empEncriptada = self::encriptarContrasena($password_emp);

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

    /**
     * Protege la contrase�a con un algoritmo de encriptado
     * @param $password_empPlana
     * @return bool|null|string
     */
    private function encriptarContrasena($password_empPlana)
    {
        if ($password_empPlana) {
            return password_hash($password_empPlana, PASSWORD_DEFAULT);
        } else {
            return null;
        }
    }

    private function generarClaveApi()
    {
        return md5(microtime() . rand());
    }

    private function loguear() //----------------------loguear
    {
        $respuesta = array();

        $body = file_get_contents('php://input');
        $empleado = json_decode($body);
        $correo_emp = $empleado->correo_emp;
        $password_emp = $empleado->password_emp;

        if (self::autenticar($correo_emp, $password_emp)) {
            $empleadoBD = self::obtenerUsuarioPorCorreo($correo_emp);
            if ($empleadoBD != NULL) {
                http_response_code(200);
                $respuesta["id_empleado"] = $empleadoBD["id_empleado"];
                $respuesta["nombre_emp"] = $empleadoBD["nombre_emp"];
                $respuesta["primer_apellido_emp"] = $empleadoBD["primer_apellido_emp"];
                $respuesta["segundo_apellido_emp"] = $empleadoBD["segundo_apellido_emp"];
                $respuesta["puesto"] = $empleadoBD["puesto"];
                $respuesta["id_estab"] = $empleadoBD["id_estab"];
                $respuesta["password_emp"] = $empleadoBD["password_emp"];
                $respuesta["correo_emp"] = $empleadoBD["correo_emp"];
                $respuesta["claveApi"] = $empleadoBD["claveApi"];
                return ["estado" => 1, "empleado" => $respuesta];
            } else {
                throw new ExcepcionApi(self::ESTADO_FALLA_DESCONOCIDA,
                    "Ha ocurrido un error");
            }
        } else {
            throw new ExcepcionApi(self::ESTADO_PARAMETROS_INCORRECTOS,
                utf8_encode("Correo o contrasenia invalidos"));
        }
    }

    private function autenticar($correo_emp, $password_emp)
    {
        $comando = "SELECT password_emp FROM " . self::NOMBRE_TABLA . " WHERE " . self::CORREO_EMP . "=?";
        try {

            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
            $sentencia->bindParam(1, $correo_emp);
            $sentencia->execute();

            if ($sentencia) {
                $resultado = $sentencia->fetch();

                if (self::validarContrasena($password_emp, $resultado["password_emp"])){
                    return true;
                } else {
                    return false;  /// no valida
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }

    private function validarContrasena($password_empPlana, $password_empHash)
    {
        return password_verify($password_empPlana, $password_empHash);
    }


    private function obtenerUsuarioPorCorreo($correo_emp){
        $comando = "SELECT " .
            self::ID_EMPLEADO . ",".
            self::NOMBRE_EMP . "," .
            self::PRIMER_APELLIDO_EMP . "," .
            self::SEGUNDO_APELLIDO_EMP . "," .
            self::PUESTO . "," .
            self::ID_ESTAB . "," .
            self::PASSWORD_EMP . "," .
            self::CORREO_EMP . "," .
            self::CLAVE_API .
            " FROM " . self::NOMBRE_TABLA .
            " WHERE " . self::CORREO_EMP . "=?";

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

        $sentencia->bindParam(1, $correo_emp);

        if ($sentencia->execute()) {
            return $sentencia->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    /**
     * Otorga los permisos a un empleado para que acceda a los recursos
     * @return null o el id del empleado autorizado
     * @throws Exception
     */
    public static function autorizar()
    {
        $cabeceras = apache_request_headers();

        if (isset($cabeceras["authorization"])) {

            $claveApi = $cabeceras["authorization"];

            if (empleados::validarClaveApi($claveApi)) {
                return empleados::obtenerIdUsuario($claveApi);
            } else {
                throw new ExcepcionApi(
                    self::ESTADO_CLAVE_NO_AUTORIZADA, "Clave de API no autorizada", 401);
            }

        } else {
            throw new ExcepcionApi(
                self::ESTADO_AUSENCIA_CLAVE_API,
                utf8_encode("Se requiere Clave del API para autenticacion"));
        }
    }

    /**
     * Comprueba la existencia de la clave para la api
     * @param $claveApi
     * @return bool true si existe o false en caso contrario
     */
    private function validarClaveApi($claveApi)
    {
        $comando = "SELECT COUNT(" . self::ID_EMPLEADO . ")" .
            " FROM " . self::NOMBRE_TABLA .
            " WHERE " . self::CLAVE_API . "=?";

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

        $sentencia->bindParam(1, $claveApi);

        $sentencia->execute();

        return $sentencia->fetchColumn(0) > 0;
    }

    /**
     * Obtiene el valor de la columna "id_empleado" basado en la clave de api
     * @param $claveApi
     * @return null si este no fue encontrado
     */
    private function obtenerIdUsuario($claveApi)
    {
        $comando = "SELECT " . self::ID_EMPLEADO .
            " FROM " . self::NOMBRE_TABLA .
            " WHERE " . self::CLAVE_API . "=?";

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

        $sentencia->bindParam(1, $claveApi);

        if ($sentencia->execute()) {
            $resultado = $sentencia->fetch();
            return $resultado['id_empleado'];
        } else {
            return null;
        }
    }


}
