<?php

class CrmaccionesController extends Controller
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

		$id = $_POST['Crmacciones']['id_crm_acciones'];
		$model=$this->loadModel($id);
		if(empty($model)){ // Si el model esta vacio, quiere decir que es un insert nuevo. en caso contrario update
			$model=new Crmacciones;

			if(isset($_POST['Crmacciones']) )
			{
				$model->attributes=$_POST['Crmacciones'];
				if($model->save())
				{
					Yii::app()->user->setFlash('success','Se guardo correctamente.');
				}
			}else
			{
				Yii::app()->user->setFlash('warning','Sin datos para guardar.');
			}
		} // end empty($model)
		else
		{
			if(isset($_POST['Crmacciones']))
			{
				$model->attributes=$_POST['Crmacciones'];
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


	public function actionDatos()
	{
		$id = $_POST['id'];
		$Datos=Crmacciones::model()->findBypk($id);

		$model=$this->loadModel($id);

		echo CJSON::encode(array(
      	'requestresult' => 'ok',
        'Datos' => $Datos,
        'model' => $model
      ));
	}

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$model->delete();
		Yii::app()->user->setFlash('success','Se elimino con exito.');
		$this->redirect(array('admin'));
	}


	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$lista= Crmacciones::model()->findAll();

		$model=new Crmacciones;

		$this->render('admin',array(
			'model'=>$model,
			'lista'=>$lista
		));
	}

	public function loadModel($id)
	{
		$model=Crmacciones::model()->findByPk($id);
		return $model;
	}


	public function actionLista()
	{
		$this->render('lista');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}