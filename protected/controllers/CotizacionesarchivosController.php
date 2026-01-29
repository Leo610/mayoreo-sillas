<?php

class CotizacionesarchivosController extends Controller
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
	public function actionAgregar()
	{
		$agregararchivo = new Cotizacionesarchivos;
		$uploadedFile=CUploadedFile::getInstance($agregararchivo,"cotizacion_archivo");
		$rnd = rand(0,9999);
		if($uploadedFile!='' and $_POST['Cotizacionesarchivos']['id_cotizacion'] !=''){ 
			$fileName = "{$rnd}-{$uploadedFile}";  // random number + file name
     		$agregararchivo->cotizacion_archivo = $fileName;
			$agregararchivo->id_cotizacion = $_POST['Cotizacionesarchivos']['id_cotizacion'];
			$agregararchivo->cotizacion_archivo_nombre = $_POST['Cotizacionesarchivos']['cotizacion_archivo_nombre'];
			$agregararchivo->agregar_a_cotizacion = $_POST['Cotizacionesarchivos']['agregar_a_cotizacion'];
			$uploadedFile->saveAs(Yii::app()->basePath.'/../archivos/'.$fileName);
			$agregararchivo->save();
			Yii::app()->user->setFlash('success', "Se agrego el archivo con éxito");
		}

		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}
	public function actionDelete($id)
	{

		$this->loadModel($id)->delete();
		Yii::app()->user->setFlash('success','Se elimino con exito.');
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('Agregar'));

	}
	public function loadModel($id)
	{
		$model=Cotizacionesarchivos::model()->findByPk($id);
		return $model;
	}

}