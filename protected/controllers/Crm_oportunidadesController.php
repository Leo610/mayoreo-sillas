<?php

class Crm_oportunidadesController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated users to access all actions
          'users'=>array('@'),
            ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * METODO PARA PERDIDA OPORTUNIDAD PERDIDA
	 */
	 public function actionCancelacion()
    {
    	
  	   // obtenemos todas las variables
       $id = $_POST['CrmOportunidades']['id'];
       $estatus = 'PERDIDO';
       $motivo_perdido = $_POST['CrmOportunidades']['motivo_perdido'];
       $comentarios_perdido = $_POST['CrmOportunidades']['comentarios_perdido'];
          
        // Verificamos que la oportunidad exista
  	   	$Oportunidad = CrmOportunidades::model()->find(
  	   		'id='.$id.' and id_usuario='.Yii::app()->user->id
  	   		);
  	   	if(!empty($Oportunidad))
  	   	{
  	   		// Hacemos todos los cambios.
  	   		if(isset($_POST['CrmOportunidades']))
			{
			   $Oportunidad->estatus =$estatus;
			   $Oportunidad->motivo_perdido =$motivo_perdido;
			   $Oportunidad->comentarios_perdido =$comentarios_perdido;
			   $Oportunidad->fecha_ultima_modificacion = date('Y-m-d H:i:s');
			   $Oportunidad->save();

			   // Una vez guardado, mostramos el mensaje
			   Yii::app()->user->setFlash('success', "Se actualizo con exito la oportunidad");
			}
  	   	}else{
  	   		// No encontramos la oportunidad.
  	   		Yii::app()->user->setFlash('danger', "Solo el vendedor puede registrar la oportunidad perdida");
  	   	}

  	   	// Redireccionamos de la pagina de donde viene
	    $urlfrom = Yii::app()->getRequest()->getUrlReferrer();
	    $this->redirect($urlfrom);
    }



	public function loadModel($id)
	{
		$models=CrmOportunidades::model()->findByPk($id);
		return $models;
	}



																																							
}