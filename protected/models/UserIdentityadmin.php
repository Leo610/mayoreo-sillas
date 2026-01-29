<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentityadmin extends CUserIdentity
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
        $arraypermitidos = array('admin@ventelia.com'=>'120716VeN?!z');
        // Verificamos que esten bien las credenciales
        if(array_key_exists ($email,$arraypermitidos)  && ($arraypermitidos[$email] == $password))
        {
            $this->_id = 99999;
            $this->errorCode = self::ERROR_NONE;
            Yii::app()->user->setState('name','Usuario Administrador Ventelia');
        }else{
            $this->errorCode = self::ERROR_USERNAME_INVALID ;
        }

        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->_id;
    }
}