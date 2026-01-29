<?php

class ProveedoresController extends Controller
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
			array(
				'allow', // allow authenticated users to access all actions
				'users' => array('@'),
			),
			array(
				'deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateorUpdate()
	{

		$id = $_POST['Proveedores']['id_proveedor'];
		$model = $this->loadModel($id);
		if (empty($model)) { // Si el model esta vacio, quiere decir que es un insert nuevo. en caso contrario update
			$model = new Proveedores;

			if (isset($_POST['Proveedores'])) {
				$model->attributes = $_POST['Proveedores'];
				$model->proveedor_estatus = 1;
				if ($model->save()) {
					Yii::app()->user->setFlash('success', 'Se guardo correctamente.');
				}
			} else {
				Yii::app()->user->setFlash('warning', 'Sin datos para guardar.');
			}
		} // end empty($model)
		else {
			if (isset($_POST['Proveedores'])) {
				$model->attributes = $_POST['Proveedores'];
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


	public function actionDatos()
	{
		$id = $_POST['id'];
		$Datos = Proveedores::model()->findBypk($id);

		$model = $this->loadModel($id);

		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'Datos' => $Datos,
				'model' => $model
			)
		);
	}

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$model->proveedor_estatus = 0;
		$model->save();
		Yii::app()->user->setFlash('success', 'Se elimino con exito.');
		$this->redirect(array('admin'));
	}


	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$VerificarAcceso = $this->VerificarAcceso(26, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		$lista = Proveedores::model()->findAll('proveedor_estatus=1');

		$model = new Proveedores;

		$this->render(
			'admin',
			array(
				'model' => $model,
				'lista' => $lista
			)
		);
	}

	public function loadModel($id)
	{
		$model = Proveedores::model()->findByPk($id);
		return $model;
	}

	public function actionVer($id)
	{
		// Obtenemos los datos del proveedor
		$Datos = Proveedores::model()->findbypk($id);

		// Obtenemos las ordendes de compra del proveedor
		$ListaOC = Ordenesdecompra::model()->findall('id_proveedor=' . $id);

		// Instanciamos el modelo del proveedor
		$model = new Proveedores;

		$this->render(
			'ver',
			array(
				'Datos' => $Datos,
				'ListaOC' => $ListaOC,
				'model' => $model
			)
		);
	}
}