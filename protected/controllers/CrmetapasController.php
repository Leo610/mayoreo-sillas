<?php

class CrmetapasController extends Controller
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
		$id = $_POST['Crmetapas']['id'];
		$model=$this->loadModel($id);
		if(empty($model)){ 
		// Si el model esta vacio, quiere decir que es un insert nuevo. en caso contrario update

			$model=new Crmetapas;
			if(isset($_POST['Crmetapas']) )
			{
				$model->attributes=$_POST['Crmetapas'];
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
			if(isset($_POST['Crmetapas']))
			{
					$model->attributes=$_POST['Crmetapas'];
				if($model->save())
				{	
					Yii::app()->user->setFlash('success','Se guardo correctamente.');
				}else{
					Yii::app()->user->setFlash('warning','No se guardo correctamente .');
				}
				

			}
		}
		$this->redirect('admin');
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
		
		$lista= Crmetapas::model()->findAll();

		$model=new Crmetapas;

		$this->render('admin',array(
			'model'=>$model,
			'lista'=>$lista
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Crmetapas the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Crmetapas::model()->findByPk($id);
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Crmetapas $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='Crmetapas-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	
	public function actionCrmetapasdatos()
	{
		$id = $_POST['id'];
		$Datos=Crmetapas::model()->findBypk($id);

		$model=$this->loadModel($id);

		echo CJSON::encode(array(
      	'requestresult' => 'ok',
         'Datos' => $Datos,
         'model' => $model
      ));
	}

}
