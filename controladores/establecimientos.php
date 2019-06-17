<?php
class establecimientos
{

    const NOMBRE_TABLA = "establecimientos";
    const ID_ESTAB = "id_estab";
    const NOMBRE_ESTAB = "nombre_estab";
    const NUM_EXTERIOR_ESTAB = "num_exterior_estab";
    const CALLE_ESTAB = "calle_estab";
    const CRUZAMIENTO1_CALLE_ESTAB = "cruzamiento1_calle_estab";
    const CRUZAMIENTO2_CALLE_ESTAB = "cruzamiento2_calle_estab";
    const COLONIA_ESTAB = "colonia_estab";
    const CIUDAD_ESTAB = "ciudad_estab";
    const TELEFONO_ESTAB = "telefono_estab";
    const CORREO_ESTAB = "correo_estab";
    const HORARIOS = "horarios";
    const DESCRIPCION_ESTAB = "descripcion_estab";
    const ID_TIPO_COCINA = "id_tipo_cocina";
    const SERV_DOMICILIO = "serv_domicilio";
    const SERV_RESERV = "serv_reserv";
    const CALIFICACION = "calificacion";
    const ID_TIPO_REST = "id_tipo_rest";
    const UBICACION_GPS_ESTAB = "ubicacion_gps_estab";
    const FOTO_ESTAB = "foto_estab";
    const LOGO_ESTAB = "logo_estab";

    const CODIGO_EXITO = 1;
    const ESTADO_EXITO = 1;
    const ESTADO_ERROR = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_ERROR_PARAMETROS = 4;
    const ESTADO_NO_ENCONTRADO = 5;

    public static function get($peticion)
    {
        //consulta si el usuario tiene una clave de poder hacer cambios
        $idEmpleado = empleados::autorizar();
        //si la variable peticion esta vacia
        if (empty($peticion[0]))
            return self::obtenerEstablecimientos($idEmpleado);
        else
            return self::obtenerEstablecimientos($idEmpleado, $peticion[0]);
    }
    public static function post($peticion) //------------------post
    {
        $idEmpleado = empleados::autorizar();
        $body = file_get_contents('php://input');
        $establecimiento = json_decode($body);
        $id_establecimiento = establecimientos::crear($establecimiento);
        http_response_code(201);
        return [
            "estado" => self::CODIGO_EXITO,
            "mensaje" => "Establecimiento creado",
            "id" => $id_establecimiento
        ];
    }
    public static function put($peticion)  //------------------put
    {
        $idEmpleado = empleados::autorizar();
        if (!empty($peticion[0])) {
            $body = file_get_contents('php://input');
            $establecimiento = json_decode($body);
            if (self::actualizar($establecimiento, $peticion[0]) > 0) {
                http_response_code(200);
                return [
                    "estado" => self::CODIGO_EXITO,
                    "mensaje" => "Registro actualizado correctamente"
                ];
            } else {
                throw new ExcepcionApi(self::ESTADO_NO_ENCONTRADO,
                    "El establecimiento al que intentas acceder no existe", 404);
            }
        } else {
            throw new ExcepcionApi(self::ESTADO_ERROR_PARAMETROS, "Falta id", 422);
        }
    }
    public static function delete($peticion) //------------------delete
    {
        $idEmpleado = empleados::autorizar();
        if (!empty($peticion[0])) {
            if (self::eliminar($idEmpleado, $peticion[0]) > 0) {
                http_response_code(200);
                return [
                    "estado" => self::CODIGO_EXITO,
                    "mensaje" => "Registro eliminado correctamente"
                ];
            } else {
                throw new ExcepcionApi(self::ESTADO_NO_ENCONTRADO,
                    "El contacto al que intentas acceder no existe", 404);
            }
        } else {
            throw new ExcepcionApi(self::ESTADO_ERROR_PARAMETROS, "Falta id", 422);
        }
    }
    /**
     * Obtiene la colección de contactos o un solo contacto indicado por el identificador
     * @param int $idUsuario identificador del usuario
     * @param null $idContacto identificador del contacto (Opcional)
     * @return array registros de la tabla contacto
     * @throws Exception
     */
    private function obtenerEstablecimientos($idEmpleado, $idestablecimiento = NULL)
    {        
        try {                        
            if (!$idestablecimiento) {
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA ;                   
                // Preparar sentencia
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
            } else {                
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA .
                    " WHERE " . self::ID_ESTAB . "=" . "'" . $idestablecimiento . "'";
                // Preparar sentencia
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
                // Ligar idContacto e idUsuario                
                //$sentencia->bindParam(1, $idalimento, PDO::PARAM_INT);
                //$sentencia->bindParam(2, $idCliente, PDO::PARAM_INT);
            }
            // Ejecutar sentencia preparada
            if ($sentencia->execute()) {
                http_response_code(200);
                return
                    [
                        "estado" => self::ESTADO_EXITO,
                        "datos" => $sentencia->fetchAll(PDO::FETCH_ASSOC)
                    ];
            } else
                throw new ExcepcionApi(self::ESTADO_ERROR, "Se ha producido un error");
        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }
    /**
     * Añade un nuevo contacto asociado a un usuario
     * @param int $idUsuario identificador del usuario
     * @param mixed $contacto datos del contacto
     * @return string identificador del contacto
     * @throws ExcepcionApi
     */
    private function crear($establecimiento)
    {
        if ($establecimiento) {
            try {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "INSERT INTO " .self ::NOMBRE_TABLA  . "(" .
                //self ::ID_ESTAB . "," .
                self ::NOMBRE_ESTAB . "," .
                self ::NUM_EXTERIOR_ESTAB . "," .
                self ::CALLE_ESTAB . "," .
                self ::CRUZAMIENTO1_CALLE_ESTAB . "," .
                self ::CRUZAMIENTO2_CALLE_ESTAB . "," .
                self ::COLONIA_ESTAB . "," .
                self ::CIUDAD_ESTAB . "," .
                self ::TELEFONO_ESTAB . "," .
                self ::CORREO_ESTAB . "," .
                self ::HORARIOS . "," .
                self ::DESCRIPCION_ESTAB . "," .
                self ::ID_TIPO_COCINA . "," .
                self ::SERV_DOMICILIO . "," .
                self ::SERV_RESERV . "," .
                self ::CALIFICACION . "," .
                self ::ID_TIPO_REST . "," .
                self ::UBICACION_GPS_ESTAB . "," .
                self ::FOTO_ESTAB . "," .
                self ::LOGO_ESTAB . ")" .
                " VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                // Preparar la sentencia
                $sentencia = $pdo->prepare($comando);
               
  
                $sentencia->bindParam(1, $establecimiento->nombre_estab);
                $sentencia->bindParam(2, $establecimiento->num_exterior_estab);
                $sentencia->bindParam(3, $establecimiento->calle_estab);
                $sentencia->bindParam(4, $establecimiento->cruzamiento1_calle_estab);
                $sentencia->bindParam(5, $establecimiento->cruzamiento2_calle_estab);
                $sentencia->bindParam(6, $establecimiento->colonia_estab);
                $sentencia->bindParam(7, $establecimiento->ciudad_estab);
                $sentencia->bindParam(8, $establecimiento->telefono_estab);
                $sentencia->bindParam(9, $establecimiento->correo_estab);
                $sentencia->bindParam(10, $establecimiento->horarios); 
                $sentencia->bindParam(11, $establecimiento->descripcion_estab);
                $sentencia->bindParam(12, $establecimiento->id_tipo_cocina);
                $sentencia->bindParam(13, $establecimiento->serv_domicilio);
                $sentencia->bindParam(14, $establecimiento->serv_reserv);
                $sentencia->bindParam(15, $establecimiento->calificacion);
                $sentencia->bindParam(16, $establecimiento->id_tipo_rest);
                $sentencia->bindParam(17, $establecimiento->ubicacion_gps_estab);
                $sentencia->bindParam(18, $establecimiento->foto_estab);
                $sentencia->bindParam(19, $establecimiento->logo_estab);
                $sentencia->execute();
                // Retornar en el último id insertado
                return $pdo->lastInsertId();
            } catch (PDOException $e) {
                throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
            }
        } else {
            throw new ExcepcionApi(
                self::ESTADO_ERROR_PARAMETROS,
                utf8_encode("Error en existencia o sintaxis de parámetros"));
        }
    }
    /**
     * Actualiza el contacto especificado por idUsuario
     * @param int $idUsuario
     * @param object $contacto objeto con los valores nuevos del contacto
     * @param int $idContacto
     * @return PDOStatement
     * @throws Exception
     */
    private function actualizar($establecimiento, $idestablecimiento)
    {
        
        try {
            // Creando consulta UPDATE
            $consulta = "UPDATE " . self::NOMBRE_TABLA .
                " SET " .
                self ::NOMBRE_ESTAB  . "=?," .
                self ::NUM_EXTERIOR_ESTAB  . "=?," .
                self ::CALLE_ESTAB  . "=?," .
                self ::CRUZAMIENTO1_CALLE_ESTAB  . "=?," .
                self ::CRUZAMIENTO2_CALLE_ESTAB . "=?," .
                self ::COLONIA_ESTAB . "=?," .
                self ::CIUDAD_ESTAB . "=?," .
                self ::TELEFONO_ESTAB . "=?," .
                self ::CORREO_ESTAB . "=?," .
                self ::HORARIOS . "=?," .
                self ::DESCRIPCION_ESTAB . "=?," .
                self ::ID_TIPO_COCINA . "=?," .
                self ::SERV_DOMICILIO . "=?," .
                self ::SERV_RESERV . "=?," .
                self ::CALIFICACION . "=?," .
                self ::ID_TIPO_REST . "=?," .
                self ::UBICACION_GPS_ESTAB . "=?," .
                self ::FOTO_ESTAB . "=?," .
                self ::LOGO_ESTAB . "=?" .
                " WHERE " . self::ID_ESTAB . "=?";
            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($consulta);
            //$sentencia->bindParam(1, $idCliente);
   
                $sentencia->bindParam(1, $establecimiento->nombre_estab);
                $sentencia->bindParam(2, $establecimiento->num_exterior_estab);
                $sentencia->bindParam(3, $establecimiento->calle_estab);
                $sentencia->bindParam(4, $establecimiento->cruzamiento1_calle_estab);
                $sentencia->bindParam(5, $establecimiento->cruzamiento2_calle_estab);
                $sentencia->bindParam(6, $establecimiento->colonia_estab);
                $sentencia->bindParam(7, $establecimiento->ciudad_estab);
                $sentencia->bindParam(8, $establecimiento->telefono_estab);
                $sentencia->bindParam(9, $establecimiento->correo_estab);
                $sentencia->bindParam(10, $establecimiento->horarios); 
                $sentencia->bindParam(11, $establecimiento->descripcion_estab);
                $sentencia->bindParam(12, $establecimiento->id_tipo_cocina);
                $sentencia->bindParam(13, $establecimiento->serv_domicilio);
                $sentencia->bindParam(14, $establecimiento->serv_reserv);
                $sentencia->bindParam(15, $establecimiento->calificacion);
                $sentencia->bindParam(16, $establecimiento->id_tipo_rest);
                $sentencia->bindParam(17, $establecimiento->ubicacion_gps_estab);
                $sentencia->bindParam(18, $establecimiento->foto_estab);
                $sentencia->bindParam(19, $establecimiento->logo_estab);
                $sentencia->bindParam(20, $idestablecimiento);
            // Ejecutar la sentencia
            $sentencia->execute();
            return $sentencia->rowCount();
        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }
    /**
     * Elimina un contacto asociado a un usuario
     * @param int $idUsuario identificador del usuario
     * @param int $idContacto identificador del contacto
     * @return bool true si la eliminación se pudo realizar, en caso contrario false
     * @throws Exception excepcion por errores en la base de datos
     */
    private function eliminar( $idEmpleado ,$idEstab)
    {
        try {
            // Sentencia DELETE
            $comando = "DELETE FROM " . self::NOMBRE_TABLA .
                " WHERE " . self::ID_ESTAB . "=" . $idEstab;
            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);       
            $sentencia->execute();
            return $sentencia->rowCount();
        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }
}