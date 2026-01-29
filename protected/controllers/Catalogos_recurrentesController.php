<?php

class Catalogos_recurrentesController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			// perform access control for CRUD operations
			'postOnly + delete',
			// we only allow deletion via POST request
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
			array(
				'allow',
				// allow authenticated users to access all actions
				'users' => array('@'),
			),
			array(
				'deny',
				// deny all users
				'users' => array('*'),
			),
		);
	}
	/*
	 *	METODO PARA LA ADMINISTRACION DE LOS CATALOGOS RECURRENTES
	 *
	 */
	public function actionIndex($id)
	{
		// Obtenemos los datos del grupo recurrente
		$Datos = GruposRecurrentes::model()->findbypk($id);

		// Obtenemos la lista de catalogos recurrentes
		$Lista = CatalogosRecurrentes::model()->findall('id_grupo_recurrente=' . $id);



		// Renderizamos la vista
		$this->render(
			'index',
			array(
				'Datos' => $Datos,
				'Lista' => $Lista
			)
		);
	}
	public function actionCreateorupdate()
	{
		$id = $_POST['CatalogosRecurrentes']['id_catalogo_recurrente'];
		$model = $this->loadModel($id);
		if (empty($model)) {
			// Si el model esta vacio, quiere decir que es un insert nuevo. en caso contrario update

			$model = new CatalogosRecurrentes;
			if (isset($_POST['CatalogosRecurrentes'])) {

				$model->attributes = $_POST['CatalogosRecurrentes'];
				$model->eliminado = 0;
				$model->fecha_alta = date('Y-m-d H:i:s');

				if ($model->save()) {
					Yii::app()->user->setFlash('success', 'Se guardo correctamente.');
				} else {
					print_r($model->getErrors());

				}
			} else {
				Yii::app()->user->setFlash('warning', 'Sin datos para guardar.');
			}
		} // end empty($model)
		else {
			if (isset($_POST['CatalogosRecurrentes'])) {
				$model->attributes = $_POST['CatalogosRecurrentes'];
				if ($model->save()) {
					Yii::app()->user->setFlash('success', 'Se guardo correctamente.');
				} else {
					Yii::app()->user->setFlash('warning', 'No se guardo correctamente .');
				}


			}
		}
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	/*
	 *	METODO PARA OBTENER LA INFORMACION MEDIANTE ID
	 *	RESPUESTA JSON
	 */

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		Yii::app()->user->setFlash('success', 'Se elimino con exito.');
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}



	/**
	 * Manages all models.
	 */
	public function actionDatos()
	{
		$id = $_POST['id'];
		$Datos = CatalogosRecurrentes::model()->findBypk($id);

		if (!empty($Datos)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'Datos' => $Datos,
					'message' => 'Informacion encontrada'
				)
			);
		} else {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se encontro información'
				)
			);
		}

	}


	public function loadModel($id)
	{
		$model = CatalogosRecurrentes::model()->findByPk($id);
		return $model;
	}

	/**
	 * AUTOCOMPLETE DE CATALOGOS RECURRENTES
	 */
	public function actionAutocompletejs($id)
	{

		$sql = '
		 	SELECT 
		 		id_catalogo_recurrente as id,
		 		nombre as value,
		 		nombre as label
		 	FROM catalogos_recurrentes
		 	WHERE 
		 		id_grupo_recurrente=' . $id . ' 
		 		and nombre LIKE :qterm';
		RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$qterm = $_GET['term'] . '%';
		$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
		$result = $command->queryAll();
		echo CJSON::encode($result);
		exit;
	}


}