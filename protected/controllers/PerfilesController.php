<?php

class PerfilesController extends Controller
{/**
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
	

	/*
	*
	*
	*/
	public function actionCreateorUpdate()
	{
		$id = $_POST['Perfiles']['id_perfil'];
		$model=$this->loadModel($id);
		if(empty($model)){ 
		// Si el model esta vacio, quiere decir que es un insert nuevo. en caso contrario update

			$model=new Perfiles;
			if(isset($_POST['Perfiles']) )
			{
				$model->attributes=$_POST['Perfiles'];
				if($model->save())
				{
					Yii::app()->user->setFlash('success','Se guardo correctamente.');
				}else{
					$errormessage = $this->ObtenerError($model->getErrors());
					Yii::app()->user->setFlash('danger',	$errormessage);	
					
				}
			}else
			{
				Yii::app()->user->setFlash('warning','Sin datos para guardar.');
			}
		} // end empty($model)
		else
		{
			if(isset($_POST['Perfiles']))
			{
				$model->attributes=$_POST['Perfiles'];
				if($model->save())
				{	
					Yii::app()->user->setFlash('success','Se guardo correctamente.');
				}else{
					$errormessage = $this->ObtenerError($model->getErrors());
					Yii::app()->user->setFlash('danger',	$errormessage);	
				}
				

			}
		}
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}
	
	/*
	*
	*
	*/
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		Yii::app()->user->setFlash('success','Se elimino con exito.');
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	

	/*
	*
	*
	*/
	public function actionIndex()
	{
		
		$lista= Perfiles::model()->findAll();

		$model=new Perfiles;

		$this->render('index',array(
			'model'=>$model,
			'lista'=>$lista
		));
	}

	/*
	*
	*
	*/
	public function loadModel($id)
	{
		$model=Perfiles::model()->findByPk($id);
		return $model;
	}

	/*
	*
	*
	*/
	public function actionDatos()
	{
		$id = $_POST['id'];
		$Datos=Perfiles::model()->findBypk($id);
		echo CJSON::encode(array(
      	'requestresult' => 'ok',
         'Datos' => $Datos
      ));
	}

	/**
	 * PANTALLA PARA ASIGNAR LOS PERMISOS
	 */
	public function actionDetalle()
	{
		
		// Obtenemos el perfil 
		$id_perfil = (isset($_GET['id_perfil']) && $_GET['id_perfil'] != '')?$_GET['id_perfil']:0;
		// Lista de los perfiles
		$ListaPerfiles = CHtml::listData(Perfiles::model()->findAll(), 'id_perfil','nombre');    

		// Lista de Actividades
		$ListaActividades = PerfilActividades::model()->findAll();

		$this->render('detalle',array(
			'ListaActividades'=>$ListaActividades,
			'ListaPerfiles'=>$ListaPerfiles,
			'id_perfil'=>$id_perfil
		));
	}
	/**
	 * METODO PARA INSERTAR O ACTUALIZAR UN PERMISO
	 */
	public function actionAgregaractividad()
	{
		$id_perfil = $_POST['id_perfil'];
		$id_actividad = $_POST['id_actividad'];
		$valor = $_POST['valor'];

		$Verificar = PerfilesPermisos::model()->find('id_perfil='.$id_perfil.' and id_actividad='.$id_actividad);

		if(!empty($Verificar)){ // Realizamos un update
			$Verificar->valor = $valor;
			$Verificar->save();
			$message = 'Actualizado con exito';
		}else{ // Realizamos un insert
			$agregar = new PerfilesPermisos;
			$agregar->id_perfil = $id_perfil;
			$agregar->id_actividad = $id_actividad;
			$agregar->valor = $valor;
			$agregar->save();
			$message = 'Agregado con exito';
		}

		echo CJSON::encode(array(
      	'requestresult' => 'ok',
         'message' => $message
      ));
	}
}
