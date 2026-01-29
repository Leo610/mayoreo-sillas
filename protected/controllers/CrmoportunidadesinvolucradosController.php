<?php

class CrmoportunidadesinvolucradosController extends Controller
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

		$model=new CrmOportunidadesInvolucrados;
		if(isset($_POST['CrmOportunidadesInvolucrados']) )
		{
			$model->attributes=$_POST['CrmOportunidadesInvolucrados'];
			$model->eliminado=0;
			$model->fecha_alta = date('Y-m-d H:i:s');
			$model->ultima_modificacion = date('Y-m-d H:i:s');
			if($model->save())
			{
				Yii::app()->user->setFlash('success','Se agrego correctamente el agente.');
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

	public function actionEliminar()
	{
		$id = $_GET['id'];
		$id_oportunidad = $_GET['id_oportunidad'];
		// Verificamos que existe
		$Eliminar = CrmOportunidadesInvolucrados::model()->find('id='.$id.' and id_oportunidad='.$id_oportunidad);
		if(!empty($Eliminar))
		{
			$Eliminar->eliminado=1;
			$Eliminar->ultima_modificacion=date('Y-m-d H:i:s');
			$Eliminar->save();
			Yii::app()->user->setFlash('success','Se elimino correctamente el agente.');
		}else{
			Yii::app()->user->setFlash('danger','Sin datos para eliminar.');
		}
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	public function loadModel($id)
	{
		$model=CrmOportunidadesInvolucrados::model()->findByPk($id);
		return $model;
	}

}