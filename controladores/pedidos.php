<?php

class pedidos
{

    const NOMBRE_TABLA = "pedidos";
    const ID_PEDIDO = "folio";
    const ID_CLIENTE = "id_cte";
    const ID_ESTABLECIMIENTO = "id_estab";
    const HORA_SOLICITUD = "hora_solicitud";
    const ESTADO_PEDIDO = "status_pedido";
    const FORMA_PAGO = "forma_pago";
    const TOTAL_PEDIDO = "total";

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
        $pedido = json_decode($body);
        $id_pedido = pedidos::crear($pedido);

        http_response_code(201);
        return [
            "estado" => self::CODIGO_EXITO,
            "mensaje" => "Pedido creado",
            "id" => $id_pedido
        ];

    }

    public static function put($peticion)  //------------------put
    {
        $idEmpleado = empleados::autorizar();
        if (!empty($peticion[0])) {
            $body = file_get_contents('php://input');
            $pedido = json_decode($body);

            if (self::actualizar($pedido, $peticion[0]) > 0) {
                http_response_code(200);
                return [
                    "estado" => self::CODIGO_EXITO,
                    "mensaje" => "Registro actualizado correctamente"
                ];
            } else {
                throw new ExcepcionApi(self::ESTADO_NO_ENCONTRADO,
                    "El folio al que intentas acceder no existe", 404);
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
    private function obtenerPedidos($idCliente, $idPedido = NULL)
    {
        try {
            if (!$idPedido) {
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA ;
                    //" WHERE " . self::ID_PEDIDO . "=?";

                // Preparar sentencia
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
                // Ligar idUsuario
                $sentencia->bindParam(1, $idCliente, PDO::PARAM_INT);

            } else {
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA .
                    " WHERE " . self::ID_PEDIDO . "=?";// AND " .
                    //self::ID_CLIENTE . "=?";

                // Preparar sentencia
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
                // Ligar idContacto e idUsuario
                $sentencia->bindParam(1, $idPedido, PDO::PARAM_INT);
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

    private function crear($pedido)
    {
        if ($pedido) {
            try {

                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

                // Sentencia INSERT
                $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .
                    self::ID_CLIENTE . "," .
                    self::ID_ESTABLECIMIENTO . "," .
                    self::HORA_SOLICITUD . "," .
                    self::ESTADO_PEDIDO . "," .
                    self::FORMA_PAGO . "," .
                    self:: TOTAL_PEDIDO .")" .
                    " VALUES(?,?,?,?,?,?)";

                // Preparar la sentencia
                $sentencia = $pdo->prepare($comando);

                $sentencia->bindParam(1, $idCliente);
                $sentencia->bindParam(2, $idEstablecimiento);
                $sentencia->bindParam(3, $hora);
                $sentencia->bindParam(4, $estado);
                $sentencia->bindParam(5, $forPago);
                $sentencia->bindParam(6, $totaPedido);

                $idCliente = $pedido->id_cte;
                $idEstablecimiento = $pedido->id_estab;
                $hora = $pedido->hora_solicitud;
                $estado = $pedido->status_pedido;
                $forPago = $pedido->forma_pago;
                $totaPedido = $pedido->total;

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
    private function actualizar($pedido, $idPedido)
    {
        try {
            // Creando consulta UPDATE
            $consulta = "UPDATE " . self::NOMBRE_TABLA .
                " SET " .
                self::ID_CLIENTE . "=?," .
                self::ID_ESTABLECIMIENTO . "=?," .
                self::HORA_SOLICITUD . "=?," .
                self::ESTADO_PEDIDO . "=?, " .
                self::FORMA_PAGO . "=?, " .
                self::TOTAL_PEDIDO . "=? " .
                " WHERE " . self::ID_PEDIDO . "=?";//" AND " . self::ID_CLIENTE . "=?";

            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($consulta);

            $sentencia->bindParam(1, $idCliente);
            $sentencia->bindParam(2, $idEstablecimiento);
            $sentencia->bindParam(3, $hora);
            $sentencia->bindParam(4, $estado);
            $sentencia->bindParam(5, $forPago);
            $sentencia->bindParam(6, $totaPedido);
            $sentencia->bindParam(7, $idPedido);
            //$sentencia->bindParam(7, $idCliente);


            $idCliente = $pedido->id_cte;
            $idEstablecimiento = $pedido->id_estab;
            $hora = $pedido->hora_solicitud;
            $estado = $pedido->status_pedido;
            $forPago = $pedido->forma_pago;
            $totaPedido = $pedido->total;
            //$idPedido = $pedido->id_pedido;
            
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
                " WHERE " . self::ID_PEDIDO . "=? ";

            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            $sentencia->bindParam(1, $idPedido);
          //  $sentencia->bindParam(2, $idCliente);

            $sentencia->execute();

            return $sentencia->rowCount();

        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }
}

