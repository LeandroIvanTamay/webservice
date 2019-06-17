<?php

class clientes
{
    const NOMBRE_TABLA = "clientes";
    const ID_CTE = "id_cte";
    const NOMBRE_CLIENTE = "nombre_cliente";
    const PRIMER_APELLIDO_CLIENTE = "primer_apellido_cliente";
    const SEGUNDO_APELLIDO_CLIENTE = "segundo_apellido_cliente";
    const TELEFONO_CLIENTE = "telefono_cliente";
    const CORREO_CLIENTE = "correo_cliente";
    const PASSWORD_CLIENTE = "password_cliente";
    const NUM_INTERIOR_CLIENTE = "num_interior_cliente";
    const NUM_EXTERIOR_CLIENTE = "num_exterior_cliente";
    const CALLE_CLIENTE = "calle_cliente";
    const CRUZAMIENTO1_CALLE_CLIENTE = "cruzamiento1_calle_cliente";
    const CRUZAMIENTO2_CALLE_CLIENTE = "cruzamiento2_calle_cliente";
    const COLONIA_CLIENTE = "colonia_cliente";
    const CIUDAD_CLIENTE = "ciudad_cliente";
    const UBICACION_GPS_CLIENTE = "ubicacion_gps_cliente";

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
        $cliente = json_decode($body);
        $id_cliente = clientes::crear($cliente);

        http_response_code(201);
        return [
            "estado" => self::CODIGO_EXITO,
            "mensaje" => "cliente registrado",
            "id" => $id_cliente
        ];

    }

    public static function put($peticion)  //------------------put
    {
        $idEmpleado = empleados::autorizar();
        if (!empty($peticion[0])) {
            $body = file_get_contents('php://input');
            $cliente = json_decode($body);

            if (self::actualizar($cliente, $peticion[0]) > 0) {
                http_response_code(200);
                return [
                    "estado" => self::CODIGO_EXITO,
                    "mensaje" => "Registro actualizado correctamente"
                ];
            } else {
                throw new ExcepcionApi(self::ESTADO_NO_ENCONTRADO,
                    "El cliente al que intentas acceder no existe", 404);
            }
        } else {
            throw new ExcepcionApi(self::ESTADO_ERROR_PARAMETROS, "Falta id", 422);
        }
    }

    public static function delete($peticion) //------------------delete
    {
        $idEmpleado = empleados::autorizar();

        if (!empty($peticion[0])) {
            if (self::eliminar($peticion[0]) > 0) {
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
    private function obtenerPedidos($idCliente, $id_cliente = NULL)
    {
        try {
            if (!$idalimento) {
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA ;

                // Preparar sentencia
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
                // Ligar idUsuario
                $sentencia->bindParam(1, $id_cliente, PDO::PARAM_INT);

            } else {
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA .
                    " WHERE " . self::ID_CTE . "=?";
                // Preparar sentencia
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
                // Ligar idContacto e idUsuario
                $sentencia->bindParam(1, $id_cliente, PDO::PARAM_INT);

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

    private function crear($cliente)
    {
        if ($cliente) {
            try {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .
                    self::NOMBRE_CLIENTE . "," .
                    self::PRIMER_APELLIDO_CLIENTE . "," .
                    self::SEGUNDO_APELLIDO_CLIENTE . "," .
                    self::TELEFONO_CLIENTE . "," .
                    self::CORREO_CLIENTE . "," .
                    self::PASSWORD_CLIENTE . "," .
                    self::NUM_INTERIOR_CLIENTE . "," .
                    self::NUM_EXTERIOR_CLIENTE . "," .
                    self::CALLE_CLIENTE . "," .
                    self::CRUZAMIENTO1_CALLE_CLIENTE . "," .
                    self::CRUZAMIENTO2_CALLE_CLIENTE . "," .
                    self::COLONIA_CLIENTE . "," .
                    self::CIUDAD_CLIENTE . "," .
                    self::UBICACION_GPS_CLIENTE . ")" .
                    " VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                // Preparar la sentencia
                $sentencia = $pdo->prepare($comando);

                $sentencia->bindParam(1, $cliente->nombre_cliente);
                $sentencia->bindParam(2, $cliente->primer_apellido_cliente);
                $sentencia->bindParam(3, $cliente->segundo_apellido_cliente);
                $sentencia->bindParam(4, $cliente->telefono_cliente);
                $sentencia->bindParam(5, $cliente->correo_cliente);
                $sentencia->bindParam(6, $cliente->password_cliente);
                $sentencia->bindParam(7, $cliente->num_interior_cliente);
                $sentencia->bindParam(8, $cliente->num_exterior_cliente);
                $sentencia->bindParam(9, $cliente->calle_cliente);
                $sentencia->bindParam(10, $cliente->cruzamiento1_calle_cliente);
                $sentencia->bindParam(11, $cliente->cruzamiento2_calle_cliente);
                $sentencia->bindParam(12, $cliente->colonia_cliente);
                $sentencia->bindParam(13, $cliente->ciudad_cliente);
                $sentencia->bindParam(14, $cliente->ubicacion_gps_cliente);


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
    private function actualizar($cliente, $id_cliente)
    {
        try {
            // Creando consulta UPDATE
            $consulta = "UPDATE " . self::NOMBRE_TABLA .
                " SET " .
                self::NOMBRE_CLIENTE . "=?," .
                self::PRIMER_APELLIDO_CLIENTE . "=?," .
                self::SEGUNDO_APELLIDO_CLIENTE . "=?," .
                self::TELEFONO_CLIENTE . "=?," .
                self::CORREO_CLIENTE . "=?," .
                self::PASSWORD_CLIENTE . "=?," .
                self::NUM_INTERIOR_CLIENTE . "=?," .
                self::NUM_EXTERIOR_CLIENTE . "=?," .
                self::CALLE_CLIENTE . "=?," .
                self::CRUZAMIENTO1_CALLE_CLIENTE . "=?," .
                self::CRUZAMIENTO2_CALLE_CLIENTE . "=?," .
                self::COLONIA_CLIENTE . "=?," .
                self::CIUDAD_CLIENTE . "=?," .
                self::UBICACION_GPS_CLIENTE . "=?" .
                  " WHERE " . self::ID_CTE . "=?";


            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($consulta);

            //$sentencia->bindParam(1, $idCliente);
            $sentencia->bindParam(1, $cliente->nombre_cliente);
            $sentencia->bindParam(2, $cliente->primer_apellido_cliente);
            $sentencia->bindParam(3, $cliente->segundo_apellido_cliente);
            $sentencia->bindParam(4, $cliente->telefono_cliente);
            $sentencia->bindParam(5, $cliente->correo_cliente);
            $sentencia->bindParam(6, $cliente->password_cliente);
            $sentencia->bindParam(7, $cliente->num_interior_cliente);
            $sentencia->bindParam(8, $cliente->num_exterior_cliente);
            $sentencia->bindParam(9, $cliente->calle_cliente);
            $sentencia->bindParam(10, $cliente->cruzamiento1_calle_cliente);
            $sentencia->bindParam(11, $cliente->cruzamiento2_calle_cliente);
            $sentencia->bindParam(12, $cliente->colonia_cliente);
            $sentencia->bindParam(13, $cliente->ciudad_cliente);
            $sentencia->bindParam(14, $cliente->ubicacion_gps_cliente);
            $sentencia->bindParam(15, $id_cliente);

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
    private function eliminar($id_cliente)
    {
        try {
            // Sentencia DELETE
            $comando = "DELETE FROM " . self::NOMBRE_TABLA .
                " WHERE " . self::ID_CTE . "=? ";

            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            $sentencia->bindParam(1, $id_cliente);

            $sentencia->execute();

            return $sentencia->rowCount();

        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }
}
