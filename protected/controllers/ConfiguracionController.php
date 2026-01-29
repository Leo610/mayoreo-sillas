<?php

class ConfiguracionController extends Controller
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
	public function actionIndex()
	{
		$Datos = Configuracion::model()->findbypk(1);
		// Si envian el formulario actualizamos los campos
		if(isset($_POST['Configuracion']))
		{
			$Logoanterior = $Datos->logotipo;
			$Datos->attributes=$_POST['Configuracion'];
			$uploadedFile=CUploadedFile::getInstance($Datos,'logotipo');
			if($uploadedFile!=''){ 
			$rnd = rand(0,9999);	
	         $fileName = "{$rnd}-{$uploadedFile}";  // random number + file name
	         $fileName = self::HacerCadenaSEO($fileName);
	         $Datos->logotipo = $fileName;
			}else{
				$Datos->logotipo = $Logoanterior;
			}
			if($Datos->save())
			{
				Yii::app()->session['logotipo'] = $Datos->logotipo;
				if($uploadedFile!=''){ 
					$uploadedFile->saveAs(Yii::app()->basePath.'/../companias/'.$Datos->directorio.'/'.$fileName);  // image will uplode to rootDirectory/banner/
				}
				Yii::app()->user->setFlash('success', "Se guardaron con exito los cambios");
			}else{
				Yii::app()->user->setFlash('warning', "Ocurrio un error inesperado");
			}
		}

		// Renderizamos la vista
		$this->render('index',array(
			'Datos'=>$Datos
			));
	}
	/**
	 * METODO PARA ELIMINAR EL LOGOTIPO
	 */
	public function actionEliminarlogotipo()
	{
		$Datos = Configuracion::model()->findbypk(1);
		$Datos->logotipo = '';
		$Datos->save();
		Yii::app()->session['logotipo'] = '';
		Yii::app()->user->setFlash('success', "Se elimino con exito el logotipo");
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}
}

