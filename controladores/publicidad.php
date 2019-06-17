<?php

class publicidad
{
    const NOMBRE_TABLA = "publicidad";
    const ID_PUB = "id_pub";
    const ID_ESTAB = "id_estab";
    const NOMBRE_PUB = "nombre_pub";
    const IMAGEN_PUB = "imagen_pub";
    const DESCRIPCION_PUB = "descripcion_pub";
    const PRODUCTOS_PUB = "productos_pub";
    
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
            return self::obtenerPublicidad($idEmpleado);
        else
            return self::obtenerPublicidad($idEmpleado, $peticion[0]);

    }

    public static function post($peticion) //------------------post
    {
        $idEmpleado = empleados::autorizar();

        $body = file_get_contents('php://input');
        $publicidad = json_decode($body);
        $id_pub = publicidad::crear($publicidad);

        http_response_code(201);
        return [
            "estado" => self::CODIGO_EXITO,
            "mensaje" => "Promoción registrada exitósamente",
            "id" => $id_pub
        ];

    }

    public static function put($peticion)  //------------------put
    {
        $idEmpleado = empleados::autorizar();
        if (!empty($peticion[0])) {
            $body = file_get_contents('php://input');
            $publicidad = json_decode($body);

            if (self::actualizar($publicidad, $peticion[0]) > 0) {
                http_response_code(200);
                return [
                    "estado" => self::CODIGO_EXITO,
                    "mensaje" => "Registro actualizado correctamente"
                ];
            } else {
                throw new ExcepcionApi(self::ESTADO_NO_ENCONTRADO,
                    "No existe ninguna publicidad con esta ID", 404);
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
    private function obtenerPublicidad($idEmpleado, $idpublicidad = NULL)
    {        
        try {                        
            if (!$idpublicidad) {
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA ;                                   
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
            } else{                
                    $comando = "SELECT * FROM " . self::NOMBRE_TABLA .
                    " WHERE " . self::ID_PUB . "=?";                    
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);                
                $sentencia->bindParam(1, $idpublicidad, PDO::PARAM_INT);
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

    private function crear($publicidad)
    {
        if ($publicidad) {
            try {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .                    
                    self::ID_ESTAB . "," .
                    self::NOMBRE_PUB . "," .
                    self::IMAGEN_PUB . "," .
                    self::DESCRIPCION_PUB . "," .
                    self::PRODUCTOS_PUB . ")" .                                    
                    " VALUES(?,?,?,?,?)";                
                $sentencia = $pdo->prepare($comando);
                $sentencia->bindParam(1, $publicidad->id_estab);
                $sentencia->bindParam(2, $publicidad->nombre_pub);
                $sentencia->bindParam(3, $publicidad->imagen_pub);
                $sentencia->bindParam(4, $publicidad->descripcion_pub);
                $sentencia->bindParam(5, $publicidad->productos_pub);
                $sentencia->execute();

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
    private function actualizar($publicidad, $idpublicidad)
    {
        try {
            // Creando consulta UPDATE
            $consulta = "UPDATE " . self::NOMBRE_TABLA .
                " SET " .                
                self::NOMBRE_PUB. "=?, " .
                self::IMAGEN_PUB. "=?, " .
                self::DESCRIPCION_PUB . "=?," .
                self::PRODUCTOS_PUB . "=?" .                
                " WHERE " . self::ID_PUB . "=?";
                
            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($consulta);

            $sentencia->bindParam(1, $publicidad->nombre_pub);
            $sentencia->bindParam(2, $publicidad->imagen_pub);
            $sentencia->bindParam(3, $publicidad->descripcion_pub);
            $sentencia->bindParam(4, $publicidad->productos_pub);
            $sentencia->bindParam(5, $idpublicidad);

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
    private function eliminar($idCliente, $idpublicidad)
    {
        try {
            // Sentencia DELETE
            $comando = "DELETE FROM " . self::NOMBRE_TABLA .
                " WHERE " . self::ID_PUB . "=? ";

            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            $sentencia->bindParam(1, $idpublicidad);
          //  $sentencia->bindParam(2, $idCliente);

            $sentencia->execute();

            return $sentencia->rowCount();

        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }
}
