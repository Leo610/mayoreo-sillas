<?php

class Clientes_productosController extends Controller
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
	public function actionCrear()
	{

		$model=new ClientesProductos;
		if(isset($_POST['ClientesProductos']) )
		{
			$model->attributes=$_POST['ClientesProductos'];
			
			if($model->save())
			{
				Yii::app()->user->setFlash('success','Se agrego correctamente el producto.');
			}else{
				print_r($model->getErrors());
				
			}
		}else
		{
			Yii::app()->user->setFlash('warning','Sin datos para guardar.');
		}
		
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	public function loadModel($id)
	{
		$model=ClientesProductos::model()->findByPk($id);
		return $model;
	}

}