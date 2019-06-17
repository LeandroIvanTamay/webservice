<?php

class alimentos
{
    const NOMBRE_TABLA = "alimentos";
    const ID_ALIM = "id_alim";
    const ID_ESTAB = "id_estab";
    const NOMBRE_ALIM = "nombre_alim";
    const DESCRIPCION_ALIM = "descripcion_alim";
    const U_MEDIDA = "u_medida";
    const TIEMPO_PREP = "tiempo_prep";
    const PRECIO_UNIT = "precio_unit";
    const ID_TIPO_COCINA = "id_tipo_cocina";
    const TIEMPO_MENU = "tiempo_menu";
    const FOTO_ALIM = "foto_alim";
    const EXISTENCIA = "existencia";

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
        $alimento = json_decode($body);
        $id_alimento = alimentos::crear($alimento);

        http_response_code(201);
        return [
            "estado" => self::CODIGO_EXITO,
            "mensaje" => "Pedido creado",
            "id" => $id_alimento
        ];

    }

    public static function put($peticion)  //------------------put
    {
        $idEmpleado = empleados::autorizar();
        if (!empty($peticion[0])) {
            $body = file_get_contents('php://input');
            $alimento = json_decode($body);

            if (self::actualizar($alimento, $peticion[0]) > 0) {
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
    private function obtenerPedidos($idEmpleado, $idalimento = NULL)
    {        
        try {                        
            if (!$idalimento) {
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA ;                   

                // Preparar sentencia
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            } else {                
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA .
                    " WHERE " . self::ID_ALIM . "=" . "'" . $idalimento . "'";
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

    private function crear($alimento)
    {
        if ($alimento) {
            try {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .
                    self::ID_ALIM . "," .
                    self::ID_ESTAB . "," .
                    self::NOMBRE_ALIM . "," .
                    self::DESCRIPCION_ALIM . "," .
                    self::U_MEDIDA . "," .
                    self::TIEMPO_PREP . "," .
                    self::PRECIO_UNIT . "," .
                    self::ID_TIPO_COCINA . "," .
                    self::TIEMPO_MENU . "," .
                    self::FOTO_ALIM . "," .
                    self::EXISTENCIA .")" .
                    " VALUES(?,?,?,?,?,?,?,?,?,?,?)";

                // Preparar la sentencia
                $sentencia = $pdo->prepare($comando);

                $sentencia->bindParam(1, $alimento->id_alim);
                $sentencia->bindParam(2, $alimento->id_estab);
                $sentencia->bindParam(3, $alimento->nombre_alim);
                $sentencia->bindParam(4, $alimento->descripcion_alim);
                $sentencia->bindParam(5, $alimento->u_medida);
                $sentencia->bindParam(6, $alimento->tiempo_prep);
                $sentencia->bindParam(7, $alimento->precio_unit);
                $sentencia->bindParam(8, $alimento->id_tipo_cocina);
                $sentencia->bindParam(9, $alimento->tiempo_menu);
                $sentencia->bindParam(10, $alimento->foto_alim);
                $sentencia->bindParam(11, $alimento->existencia);                

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
    private function actualizar($alimento, $idalimento)
    {
        try {
            // Creando consulta UPDATE
            $consulta = "UPDATE " . self::NOMBRE_TABLA .
                " SET " .
                //self::ID_ALIM . "=?," .
                self::NOMBRE_ALIM . "=?," .
                self::DESCRIPCION_ALIM. "=?, " .
                self::U_MEDIDA. "=?, " .
                self::TIEMPO_PREP . "=?," .
                self::PRECIO_UNIT . "=?," .
                self::ID_TIPO_COCINA. "=?, " .
                self::TIEMPO_MENU. "=?, " .
                self::FOTO_ALIM . "=?, " .
                self:: EXISTENCIA ."=? " .
                " WHERE " . self::ID_ALIM . "=?";


            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($consulta);

            //$sentencia->bindParam(1, $idCliente);
            $sentencia->bindParam(1, $nombrealim);
            $sentencia->bindParam(2, $descripcionalim);
            $sentencia->bindParam(3, $umedida);
            $sentencia->bindParam(4, $tiempoprep);
            $sentencia->bindParam(5, $preciounit);
            $sentencia->bindParam(6, $idtipococina);
            $sentencia->bindParam(7, $tiempomenu);
            $sentencia->bindParam(8, $fotoalim);
            $sentencia->bindParam(9, $existencia);
            $sentencia->bindParam(10, $idalimento);

            //$idalim = $alimento->id_alim;
            $nombrealim = $alimento->nombre_alim;
            $descripcionalim = $alimento->descripcion_alim;
            $umedida = $alimento->u_medida;
            $tiempoprep = $alimento->tiempo_prep;
            $preciounit = $alimento->precio_unit;
            $idtipococina = $alimento->id_tipo_cocina;
            $tiempomenu = $alimento->tiempo_menu;
            $fotoalim = $alimento->foto_alim;
            $existencia = $alimento->existencia;

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
                " WHERE " . self::ID_ALIM . "=? ";

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
