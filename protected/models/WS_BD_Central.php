<?php

class WS_BD_Central
{

    private $ConexionBD;
    private $dsn = 'mysql:host=localhost;dbname=ventelia_central';

    /*private $username='ventelia_admin';
    private $password='AdMiNbD120516';*/
    private $username = 'root';
    private $password = '';

    public function __construct()
    {
        // Realizamos la conexion con la base de datos central
        $this->ConexionBD = new CDbConnection($this->dsn, $this->username, $this->password);
        // establish connection. You may try...catch possible exceptions
        $this->ConexionBD->active = true;
        /*$ConexionBD->active=false;  // close connection*/
    }

    /**
     * METODO PARA AGREGAR UN USUARIO DE INSTANCIA
     */
    // public function AgregarUsuario($nombre_base_datos, $email, $password, $eliminado)
    // {
    //     // En base al nombre de la base de datos, obtenemos el id.
    //     $bdquery = 'select id from instancias where base_de_datos = "' . $nombre_base_datos . '"';
    //     $bd = $this->ConexionBD->createCommand($bdquery)->queryRow();

    //     $Insertar = "INSERT INTO instancias_usuarios (
    //                    id_instancia,
    //                    email,
    //                    password,
    //                    eliminado
    //                     )
    //                 VALUES(
    //                     :id_instancia,
    //                     :email,
    //                     :password,
    //                     :eliminado
    //                     )";
    //     $InsertarParametros = array(
    //         ":id_instancia" => $bd['id'],
    //         ":email" => $email,
    //         ":password" => sha1($password),
    //         ":eliminado" => $eliminado,
    //     );
    //     // Insertamos        
    //     $this->ConexionBD->createCommand($Insertar)->execute($InsertarParametros);
    //     /*$ConexionBD->getLastInsertID();*/
    // }

    /**
     * METODO PARA OBTENER LA INSTANCIA E INICIAR SESION
     */
    // public function IniciarSesion($email, $password, $nombrebd)
    // {

    //     $query = '
    //         SELECT 
    //             id_instancia,
    //             base_de_datos
    //         FROM 
    //             instancias_usuarios
    //         INNER JOIN instancias
    //         WHERE
    //             base_de_datos = "' . $nombrebd . '"
    //             AND email ="' . $email . '"
    //             AND eliminado = 0
    //         ';
    //     #echo $query;exit;
    //     $Registro = $this->ConexionBD->createCommand($query)->queryRow();

    //     if (!empty($Registro)) {
    //         // Si existe el usuario, regresamos la instancia.
    //         return array('rspt' => 1, 'instancia' => $Registro['base_de_datos']);
    //     } else {
    //         // No existe el usuario.
    //         return array('rspt' => 0);
    //     }
    // }

    /**
     * METODO PARA VERIFICAR SI EXISTE LA INSTANCIA.
     */
    public function Verificarinstancia($nombrebd)
    {
        $query = '
            SELECT 
                *
            FROM 
                instancias
            WHERE
                base_de_datos = "' . $nombrebd . '"
            ';
        $Registro = $this->ConexionBD->createCommand($query)->queryRow();

        if (empty($Registro)) {
            return 0;
        } else {
            return 1;
        }
    }

    // public function Instancias()
    // {
    //     $query = '
    //         SELECT 
    //             id,nombre,base_de_datos
    //         FROM 
    //             instancias
    //         ';
    //     return $this->ConexionBD->createCommand($query)->queryAll();
    // }

    /**
     * METODO PARA ACTUALIZAR LA CONTRASEÑA DE UN USUARIO, SE EJCUTA CUANDO CAMBIAN LA PASSWORD
     */
    public function Actualizarpswusinst($bd, $emailnuevo, $emailanterior, $ps)
    {
        // En base al nombre de la base de datos, obtenemos el id.
        $bdquery = 'select id from instancias where base_de_datos = "' . $bd . '"';
        $bd = $this->ConexionBD->createCommand($bdquery)->queryRow();

        $query = "UPDATE instancias_usuarios
                    SET
                       email= :email,
                       password= :password
                    where
                        email = :emailanterior AND
                        id_instancia = :id_instancia
                    ";
        $queryparams = array(
            ":id_instancia" => $bd['id'],
            ":email" => $emailnuevo,
            ":password" => sha1($ps),
            ":emailanterior" => $emailanterior,
        );
        // Insertamos        
        $this->ConexionBD->createCommand($query)->execute($queryparams);
    }
    /**
     * METODO PARA ACTUALIZAR LA CONTRASEÑA DE UN USUARIO, SE EJCUTA CUANDO CAMBIAN LA PASSWORD
     */
    public function Eliminarusinst($bd, $email)
    {
        // En base al nombre de la base de datos, obtenemos el id.
        $bdquery = 'select id from instancias where base_de_datos = "' . $bd . '"';
        $bd = $this->ConexionBD->createCommand($bdquery)->queryRow();

        $query = "UPDATE instancias_usuarios
                    SET
                        eliminado = 1
                    where
                        email = :email AND
                        id_instancia = :id_instancia
                    ";
        $queryparams = array(
            ":id_instancia" => $bd['id'],
            ":email" => $email
        );
        // Insertamos        
        $this->ConexionBD->createCommand($query)->execute($queryparams);
    }
}
