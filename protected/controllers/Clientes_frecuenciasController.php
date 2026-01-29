<?php

class Clientes_frecuenciasController extends Controller
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
	public function actionCreateorUpdate()
	{
		if(empty($model)){ 
		// Si el model esta vacio, quiere decir que es un insert nuevo. en caso contrario update

			$model=new ClientesFrecuencias;
			if(isset($_POST['ClientesFrecuencias']) )
			{
				$model->attributes=$_POST['ClientesFrecuencias'];
				if($model->save())
				{
					Yii::app()->user->setFlash('success','Se guardo correctamente el pedido.');
				}else{
					print_r($model->getErrors());
					
				}
			}else
			{
				Yii::app()->user->setFlash('warning','Sin datos para guardar.');
			}
		} // end empty($model)
		else
		{
			if(isset($_POST['ClientesFrecuencias']))
			{
					$model->attributes=$_POST['ClientesFrecuencias'];
				if($model->save())
				{	
					Yii::app()->user->setFlash('success','Se guardo correctamente.');
				}else{
					Yii::app()->user->setFlash('warning','No se guardo correctamente .');
				}
				

			}
		}
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		Yii::app()->user->setFlash('success','Se elimino con exito.');
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{	$this->layout="main"; // Utilizamos el layout para administracion
		
		$lista= ClientesFrecuencias::model()->findAll();

		$Frecuencia = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=12');
		$ListaFrecuencia = CHtml::listData($Frecuencia,'id_catalogo_recurrente', 'nombre');

		$ListaClientes= CHtml::listData(Clientes::model()->findAll(), 'id_cliente','cliente_nombre');

		$model=new ClientesFrecuencias;

		$this->render('admin',array(
			'model'=>$model,
			'ListaClientes'=>$ListaClientes,
			'ListaFrecuencia'=>$ListaFrecuencia,
			'lista'=>$lista
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Bancos the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ClientesFrecuencias::model()->findByPk($id);
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Bancos $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ClientesFrecuencias-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	
	public function actionDatos()
	{
		$id = $_POST['id'];
		$Datos=ClientesFrecuencias::model()->findBypk($id);

		$model=$this->loadModel($id);

		echo CJSON::encode(array(
      	'requestresult' => 'ok',
         'Datos' => $Datos,
         'model' => $model
      ));
	}

}
