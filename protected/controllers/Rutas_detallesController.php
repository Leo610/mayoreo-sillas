<?php

class Rutas_detallesController extends Controller
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
	public function actionCrearrutadetalle()
	{
		$model=new RutasDetalles;
		if(isset($_POST['RutasDetalles']) )
		{
			
			$model->attributes=$_POST['RutasDetalles'];
			$model->fecha_alta=date('Y-m-d H:i:s');
			$model->fecha_ultima_modif=date('Y-m-d H:i:s');
			$model->estatus = 'PROGRAMADO';
			if($model->save())
			{
				Yii::app()->user->setFlash('success','Se guardo correctamente.');
			}else{
				print_r($model->getErrors());
				
			}
		}
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	/**
	 * METODO PARA ACTUALIZAR
	 */
	public function actionActualizarrutadetalle()
	{
		
		// Obtenemos el id de la ruta y el id a editar
		 $idruta = $_POST['RutasDetalles']['id_ruta'];
		 $id = $_POST['RutasDetalles']['id'];
		 

		$model=RutasDetalles::model()->find(
			'id_ruta = :idruta and id = :id',array(':idruta'=>$idruta,':id'=>$id)
			);


		if(($_POST['RutasDetalles']) &&  !empty($model))

		{
			
			$model->attributes=$_POST['RutasDetalles'];
			$model->fecha_alta=date('Y-m-d H:i:s');
			$model->fecha_ultima_modif=date('Y-m-d H:i:s');
			if($model->save())
			{
				Yii::app()->user->setFlash('success','Se actualizo correctamente.');
			}else{
				print_r($model->getErrors());
				
			}
		}
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	public function actionActualizarestatus()
	{
		$id = $_POST['id'];
		$nuevovalor = $_POST['nuevovalor'];

		print_r($_POST);
		exit;
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
		$model=RutasDetalles::model()->findByPk($id);
		return $model;
	}
	
	public function actionDatos()
	{
		$id = $_POST['id'];
		$Datos=RutasDetalles::model()->findBypk($id);

		
		echo CJSON::encode(array(
      	'requestresult' => 'ok',
        'Datos' => $Datos,
      ));
	}

	
}