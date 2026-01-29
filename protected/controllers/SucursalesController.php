<?php

class SucursalesController extends Controller
{

	/*
	 *
	 *
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

	/*
	 *
	 *
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
	 * pantalla para ver la lista de Sucursales
	 */
	public function actionIndex()
	{
		// $VerificarAcceso = $this->VerificarAcceso(31, Yii::app()->user->id);
		// if (!$VerificarAcceso) {
		// 	// Lo redireccionamos a la pagina acceso restringido
		// 	$this->redirect(Yii::app()->createURL('site/noautorizado'));
		// }
		/*if(!$this->VerificarAcceso(15)){
																																		  // redireccionamos a inicio, no tiene acceso al modulo
																																		  Yii::app()->user->setflash('danger','No cuenta con el privilegio para acceder al modulo.');	
																																		  $this->layout = 'noautorizado';
																																	  }*/
		$lista = Sucursales::model()->findAll('eliminado = 0');
		$model = new Sucursales();
		//lista de entidades
		$entidades = Entidades::model()->findAll(array('order' => 'Entidad_Nombre'));
		$listaregimenes = CHtml::listData(FactCRegimenfiscal::model()->findall(), 'c_RegimenFiscal', 'descripcion');

		$model->porcentaje_iva = 16;


		$municipios = Municipios::model()->findAll('ID_Entidad = 19');
		$this->render(
			'index',
			array(
				'model' => $model,
				'lista' => $lista,
				'entidades' => CHtml::listData($entidades, 'ID_Entidad', 'Entidad_Nombre'),
				'listaregimenes' => $listaregimenes
			)
		);
	}

	/**
	 * pantalla para ver el detalle de la sucursal
	 */
	public function actionDetalle()
	{
		/*if(!$this->VerificarAcceso(15)){
																																		  // redireccionamos a inicio, no tiene acceso al modulo
																																		  Yii::app()->user->setflash('danger','No cuenta con el privilegio para acceder al modulo.');	
																																		  $this->layout = 'noautorizado';
																																	  }*/
		// primero obtenemos los valores
		$id = $_GET['id'];
		// verificamos que exista en la tabla, si existe actualizamos, si no insertamos
		$registro = Sucursales::model()->findBypk($id);
		if (empty($registro)) {
			// redireccionamos a inicio, no se encontro la sucursal
			Yii::app()->user->setflash('danger', 'No se encontro la sucursal.');
			$this->redirect(yii::app()->createurl('sucursales/index'));
		}
		$entidades = Entidades::model()->findAll(array('order' => 'Entidad_Nombre'));
		$listaregimenes = CHtml::listData(FactCRegimenfiscal::model()->findall(), 'c_RegimenFiscal', 'descripcion');
		//// lista formas pagos
		$fp_pos = GrupoRecurrenteDetalles::model()->findAll(
			array(
				'condition' => 'eliminado = 0 and id_grupo = 3',
				'order' => 'nombre asc'
			)
		);

		$bancos = GrupoRecurrenteDetalles::model()->findAll(
			array(
				'condition' => 'eliminado = 0 and id_grupo = 8',
				'order' => 'nombre asc'
			)
		);


		$municipios = Municipios::model()->findAll(
			array(
				'condition' => 'ID_Entidad = ' . $registro['estado'],
				'order' => 'Municipio_Nombre asc'
			)
		);
		// generamos las variables y renderizamos la vista
		$this->render(
			'detalle',
			array(
				'registro' => $registro,
				'id' => $id,
				'entidades' => CHtml::listData($entidades, 'ID_Entidad', 'Entidad_Nombre'),
				'arraybancos' => CHtml::listData($bancos, 'id', 'nombre'),
				'municipios' => CHtml::listData($municipios, 'ID_Entidad', 'Municipio_Nombre'),
				'listaregimenes' => $listaregimenes,
				'fp_pos' => $fp_pos
			)
		);
	}

	/**
	 * proceso para agregar una sucursal
	 */
	public function actionAgregar()
	{
		// verificamos que cuente con sucursales para activar
		$Nuevoregistro = new Sucursales();
		$Nuevoregistro->attributes = $_POST['Sucursales'];
		$Nuevoregistro->fecha_alta = date('Y-m-d H:i:s');
		$Nuevoregistro->eliminado = 0;
		$Nuevoregistro->estatus = 1;
		$Nuevoregistro->id_usuario = Yii::app()->user->id;
		// en base al arreglo de las formas de pago, guardamos las disponibles
		/*$fppos = '';
																																	  foreach($_POST['formapago'] as $key => $value){
																																		  $fppos.=$value.',';
																																	  }
																																	  $fppos = rtrim($fppos, ',');
																																	  $Nuevoregistro->formas_pagos_pos = $fppos;*/
		// verificamos si ingreso imagen
		$uploadedFile = CUploadedFile::getInstance($Nuevoregistro, 'logotipo');
		if ($uploadedFile != '') {
			$rnd = rand(0, 9999) . date('is');
			$fileName = "{$rnd}-{$uploadedFile}"; // random number + file name
			$Nuevoregistro->logotipo = $fileName;
		}
		if ($Nuevoregistro->save()) {
			// insertamos una copia de todos los inventarios de otra sucursal
			$sql = '
			INSERT INTO sucursales_productos (id_sucursal,id_producto,cantidad_stock,minimo,maximo,reorden)
			select 
			' . $Nuevoregistro->id . ',
			p.id_producto,
			0,
			sp.minimo,
			sp.maximo,
			sp.reorden
			from sucursales_productos sp 
			inner join sucursales s on sp.id_sucursal = s.id
			inner join productos p on sp.id_producto = p.id_producto
			where s.eliminado = 0 and p.eliminado = 0 group by id_producto';
			Yii::app()->db->createCommand($sql)->execute();
			// guardamos la imagen
			if ($uploadedFile != '') {
				$uploadedFile->saveAs(Yii::app()->basePath . '/../images/sucursales/' . $fileName);

				/*
																																																																Yii::import('application.extensions.image.Image');
																																																																$image = new Image(Yii::app()->basePath . '/../images/sucursales/' . $fileName);
																																																																$image->quality(Yii::app()->params['calidadimagen']);
																																																																$image->master_dim = Image::HEIGHT;
																																																																$image->resize(Yii::app()->params['maxwidthimgnormal'], Yii::app()->params['maxheightimgnormal']);
																																																																$image->save(); // or $image->save('images/small.jpg');
																																																																// hacemos una pequeña, manteniendo el alto
																																																																$imagesmall = new Image(Yii::app()->basePath . '/../images/sucursales/' . $fileName);
																																																																$imagesmall->quality(Yii::app()->params['calidadimagen']);
																																																																$imagesmall->master_dim = Image::HEIGHT;
																																																																$imagesmall->resize(Yii::app()->params['maxwidthimgsmall'], Yii::app()->params['maxheightimgsmall']);
																																																																$imagesmall->save(Yii::app()->basePath . '/../images/sucursales/small_' . $fileName);*/
			}
			Yii::app()->user->setFlash('success', "Sucursal guardada con exito");
		} else {
			Yii::app()->user->setFlash('danger', "No se pudo guardar el registro.");
			print_r($Nuevoregistro->geterrors());
			exit;
		}
		// redireccionamos la página de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	/**
	 * proceso para actualizar o agrear el permiso de acceso al modulo
	 */
	public function actionActualizarcampoajax()
	{
		// primero obtenemos los valores
		$valor = $_POST['valor'];
		$id = $_POST['id'];
		$campo = $_POST['campo'];

		// verificamos que exista en la tabla, si existe actualizamos, si no insertamos
		$registro = Sucursales::model()->findBypk($id);
		if (empty($registro)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se encontro el registro'
				)
			);
			exit();
		} else {
			// si existe, actualizamos
			$registro->$campo = $valor;
		}
		// procemos a guardar los cambios
		if ($registro->save()) {
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Actualizado con exito'
				)
			);
			exit();
		} else {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Ocurrio un error inesperado',
					'error' => print_r($registro->geterrors())
				)
			);
			exit();
		}
	}

	/**
	 * proceso para editar los datos generales de la sucursal
	 */
	public function actionEditardatos()
	{
		$registro = Sucursales::model()->findBypk($_POST['Sucursales']['id']);
		// obtenemos la imagen original
		$imagenoriginal = $registro['logotipo'];
		$registro->attributes = $_POST['Sucursales'];

		// en base al arreglo de las formas de pago, guardamos las disponibles
		/*$fppos = '';
																																	  foreach($_POST['formapago'] as $key => $value){
																																		  $fppos.=$value.',';
																																	  }
																																	  $fppos = rtrim($fppos, ',');
																																	  $registro->formas_pagos_pos = $fppos;*/

		// verificamos si selecciono la imagen
		$uploadedFile = CUploadedFile::getInstance($registro, 'logotipo');
		if ($uploadedFile != '') {
			$rnd = rand(0, 9999) . date('is');
			$fileName = "{$rnd}-{$uploadedFile}"; // random number + file name
			$registro->logotipo = $fileName;
			$uploadedFile->saveAs(Yii::app()->basePath . '/../images/sucursales/' . $fileName); // image will uplode to rootDirectory/banner/
			#print_r(Yii::app()->basePath . '\..\images\sucursales\ ' . $fileName);exit;
			/*if ($uploadedFile != '') {
																																																	$uploadedFile->saveAs(Yii::app()->basePath . '\..\images\sucursales\ ' . $fileName);
																																																	Yii::import('application.extensions.image.Image');
																																																	$image = new Image(Yii::app()->basePath . '\..\images\sucursales\ ' . $fileName);
																																																	$image->quality(Yii::app()->params['calidadimagen']);
																																																	$image->master_dim = Image::HEIGHT;
																																																	$image->resize(Yii::app()->params['maxwidthimgnormal'], Yii::app()->params['	maxheightimgnormal']);
																																																	$image->save(); // or $image->save('images/small.jpg');
																																																	// hacemos una pequeña, manteniendo el alto
																																																	$imagesmall = new Image(Yii::app()->basePath . '\..\images\sucursales\ ' . $fileName);
																																																	$imagesmall->quality(Yii::app()->params['calidadimagen']);
																																																	$imagesmall->master_dim = Image::HEIGHT;
																																																	$imagesmall->resize(Yii::app()->params['maxwidthimgsmall'], Yii::app()->params['	maxheightimgsmall']);
																																																	$imagesmall->save(Yii::app()->basePath . '\..\images\sucursales\small_' . $fileName);
																																																}*/
		} else {
			$registro->logotipo = $imagenoriginal;
		}
		if ($registro->save()) {
			Yii::app()->user->setFlash('success', "Sucursal actualizada con exito");
		} else {
			Yii::app()->user->setFlash('danger', "No se pudo actualizar la sucursal.");
			print_r($registro->geterrors());
		}
		// redireccionamos la página de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}





	/*
	 * baja logica de la sucursal
	 */
	public function actionDelete($id)
	{
		$registro = Sucursales::model()->findBypk($id);
		$registro->eliminado = 1;
		if ($registro->save()) {
			Yii::app()->user->setFlash('success', "Registro eliminado con exito");
		} else {
			Yii::app()->user->setFlash('danger', "No se pudo eliminar el registro.");
			print_r($registro->geterrors());
		}
		// redireccionamos la página de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	/*
	 * obtenemos los datos de la sucursal
	 */
	public function actionDatos()
	{
		$id = $_POST['id'];
		$Datos = Sucursales::model()->findBypk($id);
		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'Datos' => $Datos,
				'message' => 'Datos encontrados con exito'
			)
		);
	}


	/**
	 * proceso para asignar distribuidor desde ajax
	 */
	public function actionAsignardistribuidor()
	{
		// primero obtenemos los valores
		$id_sucursal = $_POST['id_sucursal'];
		$id_distribuidor = $_POST['id_distribuidor'];
		$valor = $_POST['valor'];

		// verificamos que exista en la tabla, si existe actualizamos, si no insertamos
		$registro = Sucursales::model()->findBypk($id_sucursal);
		if (empty($registro)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se encontro al usuario'
				)
			);
			exit();
		} else {
			if ($valor == 1) {
				$registro->id_distribuidor = $id_distribuidor;
			} else {
				$registro->id_distribuidor = '';
			}
			// si existe, actualizamos
		}
		// procemos a guardar los cambios
		if ($registro->save()) {
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Distribuidor actualizado con exito'
				)
			);
			exit();
		} else {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Ocurrio un error inesperado',
					'error' => print_r($registro->geterrors())
				)
			);
			exit();
		}
	}
	/**
																   pantalla para la lista de certificados
																 **/
	public function actionCertificadosfacturacion()
	{
		/*if(!$this->VerificarAcceso(15)){
																																		  // redireccionamos a inicio, no tiene acceso al modulo
																																		  Yii::app()->user->setflash('danger','No cuenta con el privilegio para acceder al modulo.');	
																																		  $this->layout = 'noautorizado';
																																	  }*/
		// primero obtenemos los valores
		$id = $_GET['id'];
		// verificamos que exista en la tabla, si existe actualizamos, si no insertamos
		$registro = Sucursales::model()->findBypk($id);
		if (empty($registro)) {
			// redireccionamos a inicio, no se encontro la sucursal
			Yii::app()->user->setflash('danger', 'No se encontro la sucursal.');
			$this->redirect(yii::app()->createurl('sucursales/index'));
		}
		// verificamos si ya cuenta con 1 registro activo
		$certificadoactivo = FactCertificados::model()->find(
			array(
				'condition' => 'eliminado = 0 and id_sucursal =:sucursal',
				'params' => array(':sucursal' => $id)
			)
		);

		/*print_r($certificadoactivo);*/

		$carpetacert = Yii::app()->basePath . '/..//archivos/certificados/' . $id;
		if (empty($certificadoactivo)) {
			// creamos una carpeta para que los guarden ahi archivos/certificados/<id> sucursal y hacemos un insert para que ese editen
			if (!is_dir($carpetacert)) {
				mkdir($carpetacert, 0777, true);
				chmod($carpetacert, 0777);
			}
			// ahora insertamos en factCertificados, 
			$certificadoactivo = new FactCertificados;
			$certificadoactivo->id_sucursal = $id;
			$certificadoactivo->certificado_cer = '';
			$certificadoactivo->certificado_key = '';
			$certificadoactivo->certificado_password = '';
			$certificadoactivo->eliminado = 0;
			$certificadoactivo->id_usuario = Yii::app()->user->id;
			$certificadoactivo->fecha_alta = date('Y-m-d H:i:s');
			if (!$certificadoactivo->save()) {
				Yii::app()->user->setflash('danger', 'No fue posible crear el certificado de facturación, favor de contactar a sistemas.');
				$this->redirect(yii::app()->createurl('sucursales/detalle' . $id));
			}
		}

		$certificadoactivo->scenario = "actualizar";
		// generamos las variables y renderizamos la vista
		$this->render(
			'certificadosfacturacion',
			array(
				'registro' => $registro,
				'id' => $id,
				'certificadoactivo' => $certificadoactivo
			)
		);
	}

	/**
	 * Agregar Certificado
	 * 
	 * */
	public function actionAgregarcertificado()
	{

		// validamos que mandaron la info
		if (!isset($_POST['FactCertificados'])) {
			Yii::app()->user->setflash('danger', 'Datos no encontrados, favor de enviar nuevamente la información.');
			// redireccionamos la página de donde viene
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
		}

		$certificadoactivo = FactCertificados::model()->findBypk($_POST['FactCertificados']['id']);
		$certificadoactivo->attributes = $_POST['FactCertificados'];

		$cer = CUploadedFile::getInstance($certificadoactivo, 'certificado_cer');
		$key = CUploadedFile::getInstance($certificadoactivo, 'certificado_key');

		// verificamos extensiones del .cer y .key
		if ($cer->getExtensionName() != 'cer') {
			Yii::app()->user->setflash('danger', 'Certificado CER en formato incorrecto, favor de seleccionar un archivo correcto.');
			// redireccionamos la página de donde viene
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
		}
		if ($key->getExtensionName() != 'key') {
			Yii::app()->user->setflash('danger', 'Certificado KEY en formato incorrecto, favor de seleccionar un archivo correcto.');
			// redireccionamos la página de donde viene
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
		}
		// verificamos que exista la carpeta
		if (!file_exists(Yii::app()->basePath . '/../archivos/certificados/' . $certificadoactivo['id_sucursal'] . '/')) {
			mkdir(Yii::app()->basePath . '/../archivos/certificados/' . $certificadoactivo['id_sucursal'] . '/', 0777, true);
		}
		// guardamos los certificados
		$cer->saveAs(Yii::app()->basePath . '/../archivos/certificados/' . $certificadoactivo['id_sucursal'] . '/' . $cer);
		$key->saveAs(Yii::app()->basePath . '/../archivos/certificados/' . $certificadoactivo['id_sucursal'] . '/' . $key);

		// validamos con la contraseña
		$cerfile = Yii::app()->basePath . '/../archivos/certificados/' . $certificadoactivo['id_sucursal'] . '/' . $cer;
		$keyfile = Yii::app()->basePath . '/../archivos/certificados/' . $certificadoactivo['id_sucursal'] . '/' . $key;

		$openSSLpath = Yii::app()->basePath . '/../ssl/bin/openssl.exe';
		#echo $openSSLpath;exit;
		/* $openSSLpath='openssl'; */// para linux ubuntu

		/* Console::execOutput("openssl x509 -inform der -in \"".$cerfile."\" -outform pem -out \"".$cerfile.'.pem"'); */
		exec($openSSLpath . " x509 -inform der -in \"" . $cerfile . "\" -out \"" . $cerfile . '.pem"');

		$cmd = $openSSLpath . " x509 -inform pem -in \"" . $cerfile . ".pem\" -noout -serial";
		$noCertificado = Console::execOutput($cmd);

		$Certificado = ''; # Variable vacia para almacenar el número de certificado
		$noCertificado = str_replace(' ', '', $noCertificado); # Función para eliminar los espacios en la cadena
		$arr1 = str_split($noCertificado); # Función para convertir la cadena en un array
		# Ciclo para obtener el número de certificado
		for ($i = 7; $i < count($arr1); $i++) { # La variable $i comienza en la posición 7, para obtener solo el valor del certificado
			if ($i % 2 == 0) # Si la posición es par, el valor de la posición se almacena en la variable $Certificado
			{
				$Certificado = ($Certificado . ($arr1[$i])); # Concatena las posiciones pares del array para obtener el número de certificado
			}
		}

		$fechaDeInicio = Console::execOutput($openSSLpath . " x509 -inform pem -in \"" . $cerfile . ".pem\" -noout -startdate");
		$fechaDeInicio = substr($fechaDeInicio, strpos($fechaDeInicio, '=') + 1);
		//$fechaDeInicio = Openssl::dateToPHPDate($fechaDeInicio);

		$fechaDeFin = Console::execOutput($openSSLpath . " x509 -inform pem -in \"" . $cerfile . ".pem\" -noout -enddate");
		$fechaDeFin = substr($fechaDeFin, strpos($fechaDeFin, '=') + 1);
		//$fechaDeFin = Openssl::dateToPHPDate($fechaDeFin);
		//
		$cmd = $openSSLpath . " pkcs8 -inform DER -in \"" . $keyfile . "\" -passin pass:" . $certificadoactivo['certificado_password'] . " -out \"" .
			$keyfile . ".pem\"";

		try {
			Console::execOutput($cmd);
			if ((filesize($keyfile . ".pem") === false) || (filesize($keyfile . ".pem") == 0)) {
				throw new Exception('La Contrasena de Llave Privada no corresponde a la Llave Privada');
			}

			//Formateamos fecha
			$fechaDeInicio = date("Y-m-d H:i:s", strtotime($fechaDeInicio));
			$fechaDeFin = date("Y-m-d H:i:s", strtotime($fechaDeFin));

			// guardamos el registro

			$certificadoactivo->certificado_password = $certificadoactivo['certificado_password'];
			$certificadoactivo->id_usuario = Yii::app()->user->id;
			$certificadoactivo->fecha_alta = date('Y-m-d H:i:s');
			$certificadoactivo->certificado_fecha_inicio = $fechaDeInicio;
			$certificadoactivo->certificado_fecha_fin = $fechaDeFin;
			$certificadoactivo->no_certificado = $Certificado;
			$certificadoactivo->certificado_cer = $cer;
			$certificadoactivo->certificado_key = $key;
			if (!$certificadoactivo->save()) {
				throw new Exception('No fue posible guardar el ceritificado, intente nuevamente.');
			}
			Yii::app()->user->setflash('success', 'Certificados guardados con exitó.');
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
		} catch (Exception $exception) {
			Yii::app()->user->setflash('danger', $exception->getMessage());
			// redireccionamos la página de donde viene
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
			exit();
		}
	}

	/**
																   PANTALLA PARA VER UBICACION D
																 **/
	public function actionUbicacion()
	{
		/*if(!$this->VerificarAcceso(15)){
																																		  // redireccionamos a inicio, no tiene acceso al modulo
																																		  Yii::app()->user->setflash('danger','No cuenta con el privilegio para acceder al modulo.');	
																																		  $this->layout = 'noautorizado';
																																	  }*/
		// primero obtenemos los valores
		$id = $_GET['id'];
		// verificamos que exista en la tabla, si existe actualizamos, si no insertamos
		$registro = Sucursales::model()->findBypk($id);
		if (empty($registro)) {
			// redireccionamos a inicio, no se encontro la sucursal
			Yii::app()->user->setflash('danger', 'No se encontro la sucursal.');
			$this->redirect(yii::app()->createurl('sucursales/index'));
		}

		// obtenemos la ubicacion
		$this->render(
			'ubicacion',
			array(
				'registro' => $registro,
			)
		);
	}


	public function actionControlInventario()
	{


		$VerificarAcceso = $this->VerificarAcceso(25, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}

		$producto_nombre = (isset($_POST['producto_nombre'])) ? $_POST['producto_nombre'] : '';
		$id_producto = (isset($_POST['id_producto'])) ? $_POST['id_producto'] : '';
		$sucursalesval = (isset($_POST['sucursales'])) ? $_POST['sucursales'] : '';
		if ($producto_nombre == "todos") {
			$id_producto = '';
		}


		try {
			$sucursales = CHtml::listData(Sucursales::model()->findAll('eliminado = 0 and estatus = 1'), 'id', 'nombre');
			$condition = 'id_sucursal=:idSucursal';
			// $condition = 'id_sucursal=:idSucursal and cantidad_stock > 0 ';

			if (!empty($sucursalesval)) {
				$sucursales = CHtml::listData(Sucursales::model()->findAll('eliminado = 0 and estatus = 1 and id = ' . $sucursalesval), 'id', 'nombre');
			}
			if (!empty($id_producto)) {
				$condition .= ' and id_producto= ' . $id_producto;
			}

			$datosInventario = array();
			foreach ($sucursales as $idSucursal => $sucursalNombre) {
				$inventario = SucursalesProductos::model()->findAll(
					array(
						'condition' => $condition,
						'params' => array(':idSucursal' => $idSucursal),
						'order' => 'id desc'
					)
				);
				if (!array_key_exists($sucursalNombre, $datosInventario)) {
					$datosInventario[$sucursalNombre] = array();
				}
				$datosInventario[$sucursalNombre] = $inventario;
			}
			// traemos las bodegas
			$sucursales2 = Sucursales::model()->findAll('eliminado = 0 and estatus = 1');
			$nuevoDato = array('id' => '', 'nombre' => 'Todas las sucursales');
			$sucursales2[] = $nuevoDato;
			return $this->render(
				'controlInventario',
				array(
					'inventariosSucursales' => $datosInventario,
					'id_producto' => $id_producto,
					'producto_nombre' => $producto_nombre,
					'sucursales' => $sucursales2,
					'sucursalesval' => $sucursalesval

				)
			);
		} catch (Exception $e) {
			Yii::app()->user->setflash('danger', 'Error: ' . $e->getMessage());
			$this->redirect(yii::app()->createurl('sucursales/index'));
		}
	}


	public function actionActualizarCampo()
	{
		try {
			$id = isset($_POST['idSucursalProducto']) ? $_POST['idSucursalProducto'] : '';
			$campo = isset($_POST['campo']) ? $_POST['campo'] : '';
			$valor = isset($_POST['value']) ? doubleval($_POST['value']) : '';

			$productoStock = SucursalesProductos::model()->findByPk($id);

			if (empty($productoStock)) {
				throw new Exception('No se encontro el producto stock');
			}

			if ($campo == 'minimo' || $campo == 'maximo') {
				if (!is_numeric($valor)) {
					throw new Exception('minimo y maximo solo pueden ser valores numericos');
				}
			}

			$productoStock->$campo = $valor;
			$productoStock->save();

			$marcarlimite = $productoStock['cantidad_stock'] <= $productoStock['minimo'];


			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Actualizado con exito',
					'marcarLimite' => $marcarlimite
				)
			);
		} catch (Exception $e) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Error ' . $e->getMessage()
				)
			);
		}
	}
}