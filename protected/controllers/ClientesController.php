<?php

class ClientesController extends Controller
{
	/**
	 * @return array filters
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
	public function actionCreateorUpdate()
	{

		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit();


		$id = $_POST['Clientes']['id_cliente'];
		$model = $this->loadModel($id);
		if (empty($model)) {
			// Si el model esta vacio, quiere decir que es un insert nuevo. en caso contrario update

			$model = new Clientes;
			if (isset($_POST['Clientes'])) {
				$model->attributes = $_POST['Clientes'];
				$idEmpresa = $_POST['Clientes']['id_empresa']; // Obtener el ID de la empresa desde el formulario
				if (empty($idEmpresa)) {
					// Si el campo id_empresa está vacío, agregar una nueva empresa a la tabla de empresas
					$empresa = new Empresas;
					$empresa->empresa = $_POST['empresa'];
					if ($empresa->save()) {
						// Obtener el ID de la empresa recién agregada
						$idEmpresa = $empresa->id;
					}
				}
				$model->id_empresa = $idEmpresa;
				if ($_POST['Clientes']['pais'] == 2) {
					// asignamos laa direccion
					$model->cliente_entidad = $_POST['entusa'];
					$model->cliente_municipio = $_POST['munusa'];
					$model->cliente_colonia = $_POST['colusa'];

				}
				//variables para subir archivos a servidor.
				$rnd = rand(0, 9999);
				$uploadedFile = CUploadedFile::getInstance($model, 'cliente_logo');
				if (!empty($uploadedFile)) {
					$fileName = "{$rnd}-{$uploadedFile}"; // random number + file name
					$model->cliente_logo = $fileName;
				}
				$model->id_usuario = Yii::app()->user->id;
				// $model->cliente_codigopostal = $_POST['cliente_codigopostal'];
				$model->cliente_codigopostal = ($_POST['Clientes']['pais'] == 2) ? $_POST['Clientes']['cliente_codigopostal'] : $_POST['cliente_codigopostal'];
				if ($model->save()) {
					if ($uploadedFile != '') {
						$uploadedFile->saveAs(Yii::app()->basePath . '/../images/clientes/' . $fileName); // image will uplode to rootDirectory/banner/
					}
					Yii::app()->user->setFlash('success', 'Se guardo correctamente.');
				} else {
					//$model->getErrors()
					Yii::app()->user->setFlash('warning', 'Ocurrio un error, favor de verificar los campos.');
				}
			} else {
				Yii::app()->user->setFlash('warning', 'Sin datos para guardar.');
			}
		} // end empty($model)
		else {
			if (isset($_POST['Clientes'])) {
				$imagenor = $model->cliente_logo;
				$model->attributes = $_POST['Clientes'];
				$idEmpresa = $_POST['Clientes']['id_empresa']; // Obtener el ID de la empresa desde el formulario
				if (empty($idEmpresa)) {
					// Si el campo id_empresa está vacío, agregar una nueva empresa a la tabla de empresas
					$empresa = new Empresas;
					$empresa->empresa = $_POST['empresa'];
					if ($empresa->save()) {
						// Obtener el ID de la empresa recién agregada
						$idEmpresa = $empresa->id;
					}
				}
				$model->id_empresa = $idEmpresa;
				if ($_POST['Clientes']['pais'] == 2) {
					// asignamos laa direccion
					$model->cliente_entidad = $_POST['estusa'];
					$model->cliente_municipio = $_POST['munusa'];
					$model->cliente_colonia = $_POST['colusa'];

				}

				//variables para subir archivos a servidor.
				$rnd = rand(0, 9999);
				$uploadedFile = CUploadedFile::getInstance($model, 'cliente_logo');

				if (!empty($uploadedFile)) {
					$fileName = "{$rnd}-{$uploadedFile}"; // random number + file name
					$model->cliente_logo = $fileName;
				} else {
					$model->cliente_logo = $imagenor;
				}
				$model->id_usuario = Yii::app()->user->id;
				// $model->cliente_codigopostal = $_POST['cliente_codigopostal'];
				$model->cliente_codigopostal = ($_POST['Clientes']['pais'] == 2) ? $_POST['Clientes']['cliente_codigopostal'] : $_POST['cliente_codigopostal'];
				if ($model->save()) {
					if ($uploadedFile != '') {
						$uploadedFile->saveAs(Yii::app()->basePath . '/../images/clientes/' . $fileName);
					}
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


	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		Yii::app()->user->setFlash('success', 'Se elimino con exito.');
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}



	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$VerificarAcceso = $this->VerificarAcceso(17, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}

		$contacto = '';
		$giroempresa = '';
		if (isset($_GET['Clientes'])) {

			$contacto = isset($_GET['Clientes']['como_contacto']) ? $_GET['Clientes']['como_contacto'] : '';
			$giroempresa = isset($_GET['Clientes']['cliente_tipo']) ? $_GET['Clientes']['cliente_tipo'] : '';
		}


		$lista = Clientes::model()->findAll(
			array(
				'order' => 'id_cliente DESC'
			)
		);

		if ($giroempresa != '' && $contacto != '') {
			$lista = Clientes::model()->findAll(
				array(
					'condition' => 'cliente_tipo=:cont and como_contacto=:conta',
					'params' => array(':cont' => $giroempresa, ':conta' => $contacto),
					'order' => 'id_cliente DESC'

				)
			);
		} else
			if ($contacto != '') {
				$lista = Clientes::model()->findAll(
					array(
						'condition' => 'como_contacto=:cont',
						'params' => array(':cont' => $contacto),
						'order' => 'id_cliente DESC'

					)
				);
			} else
				if ($giroempresa != '') {
					$lista = Clientes::model()->findAll(
						array(
							'condition' => 'cliente_tipo=:cont',
							'params' => array(':cont' => $giroempresa),
							'order' => 'id_cliente DESC'

						)
					);
				}



		$model = new Clientes;

		$Clasificacion = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=9');
		$ListaClasificacion = CHtml::listData($Clasificacion, 'id_catalogo_recurrente', 'nombre');

		$Trabajarlo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=10');
		$ListaComoTrabajarlo = CHtml::listData($Trabajarlo, 'id_catalogo_recurrente', 'nombre');

		$ListaEmpresa = CHtml::listData(Empresas::model()->findAll(), 'id', 'empresa');

		// Obtenemos los tipos de estapas
		$Tipo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=3');
		$listatipo = CHtml::listData($Tipo, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos las listas de precios, para asignarlas al cliente.
		$arraylistaprecios = CHtml::listData(ListaPrecios::model()->findAll(), 'id_lista_precio', 'listaprecio_nombre');
		$arraytipoprecio = [
			1 => 'Precio lista',
			2 => '50 - 99',
			3 => '100 - 199',
			4 => '200 o más',
			// 5 => '300 o mas',
			6 => 'Distribuidores',
		];


		$this->render(
			'admin',
			array(
				'model' => $model,
				'lista' => $lista,
				'ListaClasificacion' => $ListaClasificacion,
				'ListaComoTrabajarlo' => $ListaComoTrabajarlo,
				'ListaEmpresa' => $ListaEmpresa,
				'listatipo' => $listatipo,
				'arraylistaprecios' => $arraylistaprecios,
				'precio' => $arraytipoprecio
			)
		);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Clientes the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Clientes::model()->findByPk($id);
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Clientes $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'Clientes-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	public function actionClientesdatos()
	{
		$id = $_POST['id'];
		$Datos = Clientes::model()->findBypk($id);

		if ($Datos['id_empresa'] == null) {
			$empresa = [
				'empresa' => ''
			];
		} else {

			$empresa = Empresas::model()->find('id=' . $Datos['id_empresa']);
		}


		$model = $this->loadModel($id);

		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'Datos' => $Datos,
				'model' => $model,
				'nombree' => $empresa['empresa']
			)
		);
	}

	/*
	 * METODO QUE REGRESA UNA LISTA EN AJAX DE LOS CLIENTES, PARA AUTOCOMPLETE
	 * REALIZADA POR DANIEL VILLARREAL 31 DE ENERO DEL 2016
	 */
	public function actionClientesajax()
	{

		$sql = '
    	SELECT 
    		id_cliente as id,
    		CONCAT_WS("",NULL,cliente_nombre," ",cliente_rfc) as value,
    		CONCAT_WS("",NULL,cliente_nombre," ",cliente_rfc) as label
    	FROM clientes
    	WHERE 
    		cliente_razonsocial LIKE :qterm or
    		cliente_rfc LIKE :qterm or
    		cliente_nombre LIKE :qterm';
		RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$qterm = $_GET['term'] . '%';
		$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
		$result = $command->queryAll();
		echo CJSON::encode($result);
		exit;

	}

	/*
	 * METODO QUE REGRESA UNA LISTA EN AJAX DE las empresas, PARA AUTOCOMPLETE
	 * REALIZADA POR LARS 26/10/23
	 */
	public function actionEmpresa()
	{

		$sql = '
    	SELECT id as id, empresa as label FROM empresas WHERE empresa LIKE :qterm';
		RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$qterm = $_GET['term'] . '%';
		$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
		$result = $command->queryAll();
		echo CJSON::encode($result);
		exit;

	}

	public function actionVer($id)
	{

		$model = Clientes::model()->findByPk($id);

		// Obtenemos la lista de acciones que tiene el cliente
		$listadetallescliente = Crmdetalles::model()->findall('id_cliente=' . $id);

		// Obtenemos el model de crmacciones
		$modelCrmdetalles = new Crmdetalles;

		//lista para obtener las acciones
		$arraylistacrmacciones = CHtml::listData(Crmacciones::model()->findAll(), 'id_crm_acciones', 'crm_acciones_nombre');

		// Variables para el form de edtar datos del cliente.');
		$ClienteEditar = new Clientes();
		// Obtenemos las listas de precios, para asignarlas al cliente.
		$arraylistaprecios = CHtml::listData(ListaPrecios::model()->findAll(), 'id_lista_precio', 'listaprecio_nombre');

		// Obtenemos la lista de cotizaciones del cliente
		$listacotizaciones = Cotizaciones::model()->findAll('id_cliente=' . $id);

		$this->render(
			'crmver',
			array(
				'datoscliente' => $model,
				'listadetallescliente' => $listadetallescliente,
				'modelCrmdetalles' => $modelCrmdetalles,
				'arraylistacrmacciones' => $arraylistacrmacciones,
				'model' => $ClienteEditar,
				'arraylistaprecios' => $arraylistaprecios,
				'listacotizaciones' => $listacotizaciones
			)
		);
	}


	public function actionModulos()
	{

		$this->render('modulos');
	}

	// <p>CDbCommand falló al ejecutar la sentencia SQL: SQLSTATE[42000]: 
	// Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version 
	// for the right syntax to use near 'LIMIT 1' at line 1. The SQL statement executed was:
	// 	 SELECT * FROM `empresas` `t` WHERE id= LIMIT 1 (C:\wamp64\www\nubograma\2023\sistemaroscator\framework\db\CDbCommand.php:543)</p>


	public function actionDetalles()
	{
		$id = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';
		$model = new Clientes;
		$datos = Clientes::model()->find('id_cliente = ' . $id);

		$Clasificacion = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=9');
		$ListaClasificacion = CHtml::listData($Clasificacion, 'id_catalogo_recurrente', 'nombre');

		$Trabajarlo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=10');
		$ListaComoTrabajarlo = CHtml::listData($Trabajarlo, 'id_catalogo_recurrente', 'nombre');

		$ListaEmpresa = CHtml::listData(Empresas::model()->findAll(), 'id', 'empresa');

		// Obtenemos los tipos de estapas
		$Tipo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=3');
		$listatipo = CHtml::listData($Tipo, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos las listas de precios, para asignarlas al cliente.
		// $arraylistaprecios = CHtml::listData(ListaPrecios::model()->findAll(), 'id_lista_precio', 'listaprecio_nombre');
		$arraylistaprecios = [
			1 => 'Precio lista',
			2 => '50 - 99',
			3 => '100 - 199',
			4 => '200 o más',
			// 5 => '300 o mas',
			6 => 'Distribuidores',
		];

		// vamos a proyecto para traer lo vendido, ingresado y pendiente


		$sql = 'SELECT SUM(proyecto_total) AS vendido, SUM(proyecto_totalpagado) AS acumulado, SUM(proyecto_totalpendiente) AS pendiente FROM proyectos WHERE id_cliente = :id AND proyecto_estatus !=7';
		RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$command->bindParam(":id", $id, PDO::PARAM_STR);
		$proyecto = $command->queryAll();

		$sql = 'SELECT SUM(proyecto_total) AS vendido, SUM(proyecto_totalpagado) AS acumulado, SUM(proyecto_totalpendiente) AS pendiente FROM proyectos WHERE id_cliente =:id AND proyecto_estatus != 7 AND YEAR(proyecto_fecha_alta) = YEAR(CURRENT_DATE)';
		RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$command->bindParam(":id", $id, PDO::PARAM_STR);
		$proyectofecha = $command->queryAll();





		$this->render(
			'detalles',
			[
				'id' => $id,
				'model' => $model,
				'ListaClasificacion' => $ListaClasificacion,
				'ListaComoTrabajarlo' => $ListaComoTrabajarlo,
				'ListaEmpresa' => $ListaEmpresa,
				'listatipo' => $listatipo,
				'arraylistaprecios' => $arraylistaprecios,
				'Datos' => $datos,
				'proy' => $proyecto,
				'proy2' => $proyectofecha,
			]
		);
	}
	public function actionCotizacionescli()
	{

		// Variable para obtener el estatus

		// FECHA INICIO Y FECHA FIN
		// $fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		// $fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();
		// $id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';

		// // Obtenemos todos los usuarios relacionados
		// $usuarioshijos = $this->ObtenerHijosUsuario(Yii::app()->user->id, '');
		// // TERMINA 

		// // verificamos si viene desde cliente por el id_cliente
		// if (!empty($id_cliente)) {
		// 	$parametros = 'id_cliente = ' . $id_cliente . '';

		// } else {
		// 	// Obtenemos la lista de cotizaciones de todos los clientes
		// 	$parametros = ' cotizacion_fecha_alta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" and id_usuario in (' . $usuarioshijos . ') ';
		// }
		// // si el id cliente existe quiere decir que buscan las cotizaciones del cliente para el lunes jajaja 
		// $listacotizaciones = Cotizaciones::model()->findAll($parametros);

		// $this->render(
		// 	'cotizacionescli',
		// 	[
		// 		'listacotizaciones' => $listacotizaciones,
		// 		'fechainicio' => $fechainicio,
		// 		'fechafin' => $fechafin,
		// 	]
		// );
	}
}
