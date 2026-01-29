<?php

class ProductospreciosController extends Controller
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


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionActualizarprecio()
	{
		// print_r($_POST);
		// exit;
		$id_producto = $_POST['id_producto'];
		$listaprecio = $_POST['listaprecio'];
		$precio = $_POST['precio'];
		$campo = $_POST["campo"];

		if (!empty($id_producto) and !empty($listaprecio) and $precio !== '') {
			// Consulta para verificar si existe el producto en la lista de precios.

			$verificarproducto = Productosprecios::model()->find('id_producto=' . $id_producto . ' and id_lista_precio=' . $listaprecio);

			if (empty($verificarproducto)) {
				// En caso de no existir el producto en productosprecios, lo tenemos que agregar
				$agregar = new Productosprecios;
				$agregar->id_producto = $id_producto;
				$agregar->id_lista_precio = $listaprecio;
				$agregar->$campo = $precio;
				$agregar->save();
				// echo 'aca3';
				// exit;
				echo CJSON::encode(
					array(
						'requestresult' => 'ok'
					)
				);
			} else {
				// En caso de existir el producto en productosprecios, lo tenemos que actualizar
				$verificarproducto->$campo = $precio;
				$verificarproducto->save();
				echo CJSON::encode(
					array(
						'requestresult' => 'ok'
					)
				);
			} // end if(empty($verificarproducto))
		} // if(!empty($id_producto) and !empty($listaprecio) and !empty($precio))
		else {
			echo CJSON::encode(
				array(
					'requestresult' => 'error',
					'message' => 'Los campos estan vacios'
				)
			);
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$VerificarAcceso = $this->VerificarAcceso(27, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		// Obtenemos el id de la lista de precios
		$idlistaprecio = (isset($_GET['idlistaprecio'])) ? $_GET['idlistaprecio'] : 0;

		// Obtenemos todas las listas de precios
		$listaprecios = CHtml::listData(ListaPrecios::model()->findAll('listaprecio_estatus=1'), 'id_lista_precio', 'listaprecio_nombre');

		// Datos de la lista de precios seleccionada
		$Datos = Listaprecios::model()->findbypk($idlistaprecio);
		if (empty($Datos)) {
			$Datos = Listaprecios::model()->find();
			$idlistaprecio = $Datos['id_lista_precio'];
		}

		// Obtenemos todos los productos uniendo la lista de precios
		$lista = Productos::model()->findall("producto_estatus = 1");

		// echo "<pre>";

		// print_r($Datos);
		// echo "</pre>";
		// exit();


		$this->render(
			'admin',
			array(
				'lista' => $lista,
				'listaprecios' => $listaprecios,
				'datos' => $Datos
			)
		);
	}


	//
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Productosprecios the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Productosprecios::model()->findByPk($id);
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Productosprecios $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'Productosprecios-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
