<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	private $_id;
	
    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        // from database... change to suit your authentication criteria
        // -- Nope, I wont include mine --

        $users = Usuarios::model()->find('LOWER(Usuario_Email)=? and eliminado = 0', array(strtolower($this->username)));
       

        if($users===null)
        {	
        	 $this->errorCode = self::ERROR_USERNAME_INVALID ;
        	}
        else if($users->Usuario_Password != sha1($this->password)){

        	 $this->errorCode = self::ERROR_USERNAME_INVALID ;
			}
        else{
            // eliminamos todas las notificaciones
            $proyectos = ProyectosNotificaciones::model()->findAll([
                'condition' => 'estatus = 0 and eliminado = 0 and id_usuario = :usuario',
                'params' => [':usuario' => Yii::app()->user->id]
            ]);
            // recorremos todos
            foreach($proyectos as $rows){
                $rows->estatus = 1; // la marcamos como vista
                $rows->save();
            }
            // successful login
            $this->_id = $users->ID_Usuario;
            $this->errorCode = self::ERROR_NONE;
            Yii::app()->user->setState('name', $users->Usuario_Nombre);
            Yii::app()->user->setState('bd',Yii::app()->session['basededatos']);
            

        }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->_id;
    }
}