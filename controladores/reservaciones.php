<?php

class reservaciones
{
    const NOMBRE_TABLA = "reservaciones";
    const ID_RESERVACION = "id_reservacion";
    const ID_ESTAB = "id_estab";
    const NUM_MESA = "num_mesa";    
    const CANTIDAD_PERSONAS = "cantidad_personas";
    const HORA_RESERVACION = "hora_reservacion";
    const HORA_REGISTRO = "hora_registro";
    const ID_CTE = "id_cte";
    const STATUS_RESERVACION = "status_reservacion";
    

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
            return self::obtenerPedidos($idEmpleado);
        else
            return self::obtenerPedidos($idEmpleado, $peticion[0]);

    }

    public static function post($peticion) //------------------post
    {
        $idEmpleado = empleados::autorizar();

        $body = file_get_contents('php://input');
        $reservacion = json_decode($body);
        $id_reservacion = reservaciones::crear($reservacion);

        http_response_code(201);
        return [
            "estado" => self::CODIGO_EXITO,
            "mensaje" => "Reservacion Creado",
            "id" => $id_reservacion
        ];

    }

    public static function put($peticion)  //------------------put
    {
        $idEmpleado = empleados::autorizar();
        if (!empty($peticion[0])) {
            $body = file_get_contents('php://input');
            $reservacion = json_decode($body);

            if (self::actualizar($reservacion, $peticion[0]) > 0) {
                http_response_code(200);
                return [
                    "estado" => self::CODIGO_EXITO,
                    "mensaje" => "Registro actualizado correctamente"
                ];
            } else {
                throw new ExcepcionApi(self::ESTADO_NO_ENCONTRADO,
                    "El producto al que intentas acceder no existe", 404);
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
    private function obtenerPedidos($idEmpleado, $idreservacion = NULL)
    {        
        try {                        
            if (!$idreservacion) {
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA ;                   

                // Preparar sentencia
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            } else {                
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA .
                    " WHERE " . self::ID_RESERVACION . "=" . "'" . $idreservacion . "'";
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

    private function crear($reservacion)
    {
        if ($reservacion) {
            try {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .
            
                   // self::ID_RESERVACION . "," .
                    self::ID_ESTAB . "," .
                    self::NUM_MESA . "," .
                    self::CANTIDAD_PERSONAS . "," .
                    self::HORA_RESERVACION . "," .
                    self::HORA_REGISTRO . "," .
                    self::ID_CTE . "," .
                    self::STATUS_RESERVACION . ")" .
                    " VALUES(?,?,?,?,?,?,?)";

                // Preparar la sentencia
                $sentencia = $pdo->prepare($comando);

              //  $sentencia->bindParam(1, $reservacion->id_reservacion);
                $sentencia->bindParam(1, $reservacion->id_estab);
                $sentencia->bindParam(2, $reservacion->num_mesa);
                $sentencia->bindParam(3, $reservacion->cantidad_personas);
                $sentencia->bindParam(4, $reservacion->hora_reservacion);
                $sentencia->bindParam(5, $reservacion->hora_registro);
                $sentencia->bindParam(6, $reservacion->id_cte);
                $sentencia->bindParam(7, $reservacion->status_reservacion);

               

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
    private function actualizar($reservacion, $idreservacion)
    {
        try {
            // Creando consulta UPDATE
            $consulta = "UPDATE " . self::NOMBRE_TABLA .
                " SET " .
                self::ID_ESTAB . "=?," .
                self::NUM_MESA. "=?, " .
                self::CANTIDAD_PERSONAS. "=?, " .
                self::HORA_RESERVACION . "=?," .
                self::HORA_REGISTRO . "=?," .
                self::ID_CTE. "=?, " .
                self::STATUS_RESERVACION. "=? " .
                " WHERE " . self::ID_RESERVACION . "=?";


            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($consulta);

            //$sentencia->bindParam(1, $idCliente);
                $sentencia->bindParam(1, $reservacion->id_estab);
                $sentencia->bindParam(2, $reservacion->num_mesa);
                $sentencia->bindParam(3, $reservacion->cantidad_personas);
                $sentencia->bindParam(4, $reservacion->hora_reservacion);
                $sentencia->bindParam(5, $reservacion->hora_registro);
                $sentencia->bindParam(6, $reservacion->id_cte);
                $sentencia->bindParam(7, $reservacion->status_reservacion);
                $sentencia->bindParam(8, $idreservacion);




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
    private function eliminar($idCliente, $idPedido)
    {
        try {
            // Sentencia DELETE
            $comando = "DELETE FROM " . self::NOMBRE_TABLA .
                " WHERE " . self::ID_RESERVACION . "=? ";

            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            $sentencia->bindParam(1, $idPedido);          

            $sentencia->execute();

            return $sentencia->rowCount();

        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }
}
// end of fila
//elmer