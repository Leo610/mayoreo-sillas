<?php
class RActiveRecord extends CActiveRecord
{

    public static $dbadvert = null;



    public static function getAdvertDbConnection()
    {

        if (self::$dbadvert !== null)
            return self::$dbadvert;
        else {
            // Seleccionamos la base de datos que es la de sesion
            $db_name = Yii::app()->session['basededatos'];
            // echo $db_name;
            // exit;
            // if (empty(($db_name))) {

            $db_name = 'mayoreodesillas_ventelia_sistemaroscator';
            // }
            /* $VerificarDB = RActiveRecord::VerificarExistenciaBD($db_name);
             if($VerificarDB==0)
             {
              Yii::app()->user->setFlash('danger', "No se encontro la BD favor de contactar al administrador del sistema");
              // Redireccionamos de la pagina de donde viene
              $urlfrom = Yii::app()->getRequest()->getUrlReferrer();
              CController::redirect($urlfrom);
             }*/


            self::$dbadvert = Yii::createComponent(
                array(
                    'class' => 'CDbConnection',
                    // other config properties...
                    /*'connectionString'=>"mysql:host=localhost;dbname=ventelia_".$db_name, //dynamic database name here*/
                    'connectionString' => "mysql:host=localhost;dbname=" . $db_name,
                    //dynamic database name here
                    /*'username'=>'ventelia_admin',
                    'password'=> 'AdMiNbD120516',*/        //password here*/
                    'username' => 'mayoreodesillas_nubograma',
                    'password' => 'auphaXTkOO~P',
                    //password here*/
                    'charset' => 'utf8',
                    'emulatePrepare' => true,
                    'enableParamLogging' => false,
                    'enableProfiling' => false,
                )
            );

            Yii::app()->setComponent('dbadvert', self::$dbadvert);

            if (self::$dbadvert instanceof CDbConnection) {
                Yii::app()->db->setActive(false);
                Yii::app()->dbadvert->setActive(true);
                return self::$dbadvert;
            } else {

                throw new CDbException(Yii::t('yii', 'Active Record requires a "db" CDbConnection application component.'));
            }
        }
    }
}