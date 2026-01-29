<?php

class RutasController extends Controller
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCrearruta()
	{
		$model=new Rutas;
		if(isset($_POST['Rutas']) )
		{
			$model->attributes=$_POST['Rutas'];
			$model->id_vendedor = Yii::app()->user->id;
			$model->fecha_alta=date('Y-m-d H:i:s');
			if($model->save())
			{
				Yii::app()->user->setFlash('success','Se guardo correctamente.');
			}else{
				print_r($model->getErrors());
				
			}
		}
		$this->redirect(Yii::app()->createUrl('administracion/rutas'));
	}

	public function actionActualizarestatus()
	{
		$id = $_POST['id'];
		$nuevovalor = $_POST['nuevovalor'];

		$model=$this->loadModel($id);
		$model->estatus =$nuevovalor;
		if(!empty($id))
			{
				$model->save();
				echo CJSON::encode(array(
		      	'requestresult' => 'ok',
		      	'message'=>'Estatus actualizado',
		      ));

			}
		else {

			echo CJSON::encode(array(
      	'requestresult' => 'error',
         'message'=>'Error, favor de verificar',
      ));

		}

	}
	/*
	*
	*
	*/

	public function loadModel($id)
	{
		$model=Rutas::model()->findByPk($id);
		return $model;
	}

	public function actionDatos()
	{
		$id = $_POST['id'];
		$Datos=Rutas::model()->findBypk($id);

		$model=$this->loadModel($id);

		echo CJSON::encode(array(
      	'requestresult' => 'ok',
        'Datos' => $Datos,
        'model' => $model
      ));
	}
	

	

	
}
