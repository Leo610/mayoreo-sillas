<?php

class ProductosController extends Controller
{
	private $filtrosReporte = [];
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
	public function actionCreateorUpdate()
	{

		try {
			$id = $_POST['Productos']['id_producto'];
			$model = $this->loadModel($id);
			if (empty($model)) { // Si el model esta vacio, quiere decir que es un insert nuevo. en caso contrario update
				$model = new Productos;

				if (isset($_POST['Productos'])) {
					if (isset($_POST['Productos']['utilidad']) && !empty($_POST['Productos']['utilidad'])) {
						if ($_POST['Productos']['utilidad'] > 100 || $_POST['Productos']['utilidad'] < 0) {
							throw new Exception('La utilidad del producto debe de ser un numero entre 0 y 100');
						}
					}
					$model->attributes = $_POST['Productos'];
					//variables para subir archivos a servidor.
					$uploadedFile = CUploadedFile::getInstance($model, 'producto_imagen');
					if (!empty($uploadedFile)) {
						$rnd = rand(0, 9999);
						$fileName = "{$rnd}-{$uploadedFile}"; // random number + file name
						$model->producto_imagen = $fileName;
					}
					$model->producto_estatus = 1;
					$model->eliminado = 0;
					if ($model->save()) {
						if ($uploadedFile != '') {
							$uploadedFile->saveAs(Yii::app()->basePath . '/../archivos/' . $fileName); // image will uplode to rootDirectory/banner/
						}
						Yii::app()->user->setFlash('success', 'Se guardo correctamente.');
					} else {
						throw new Exception('Ocurrio un error, favor de verificar los campos.');
					}
				} else {
					throw new Exception('Sin datos para guardar.');
				}
			} else {
				if (isset($_POST['Productos'])) {
					if (isset($_POST['Productos']['utilidad']) && !empty($_POST['Productos']['utilidad'])) {
						if ($_POST['Productos']['utilidad'] > 100 || $_POST['Productos']['utilidad'] < 0) {
							throw new Exception('La utilidad del producto debe de ser un numero entre 0 y 100');
						}
					}
					$model->attributes = $_POST['Productos'];
					//variables para subir archivos a servidor.
					$rnd = rand(0, 9999);
					$uploadedFile = CUploadedFile::getInstance($model, 'producto_imagen');

					if (!empty($uploadedFile)) {
						$fileName = "{$rnd}-{$uploadedFile}"; // random number + file name
						$model->producto_imagen = $fileName;
					} else {
						$model->producto_imagen = $_POST['imagenoriginal'];
					}

					if ($model->save()) {
						if ($uploadedFile != '') {
							$uploadedFile->saveAs(Yii::app()->basePath . '/../archivos/' . $fileName);
						}
						Yii::app()->user->setFlash('success', 'Se guardo correctamente.');
					} else {
						throw new Exception('No se guardo correctamente .');
					}
				}
			}
		} catch (Exception $e) {
			Yii::app()->user->setFlash('warning', 'Error: ' . $e->getMessage());
		} finally {
			// Redireccionamos de la pagina de donde viene
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
		}
	}


	public function actionDatos()
	{
		$id = $_POST['id'];
		$Datos = Productos::model()->findBypk($id);


		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'Datos' => $Datos,
			)
		);
	}


	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$model->producto_estatus = 0;
		$model->save();
		Yii::app()->user->setFlash('success', 'Se elimino con exito.');
		$this->redirect(array('admin'));
	}


	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$VerificarAcceso = $this->VerificarAcceso(22, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}

		$id = (isset($_GET['id'])) ? $_GET['id'] : '';
		// Obtebemos el termino ingresado

		$keyword = (isset($_GET['buscador'])) ? $_GET['buscador'] : '%';
		if (!empty($id)) {

			$lista = Productos::model()->findAll(
				array(
					// 'condition' => 'producto_estatus=1 and producto_nombre LIKE :inicial',
					'condition' => 'producto_estatus=1 and eliminado =0 and id_categoria= :id',
					'params' => array(':id' => $id)
				)
			);
		} else {
			$lista = Productos::model()->findAll(
				array(

					'condition' => 'producto_estatus=1 and eliminado =0',

				)
			);
		}


		$model = new Productos;

		// Obtenemos la lista de proveedores, verificando que los campos coincidan.
		$arraylistaproveedores = CHtml::listData(Proveedores::model()->findAll('proveedor_estatus=1'), 'id_proveedor', 'proveedor_nombre');

		$arraylistaunidadesmedidas = CHtml::listData(Unidadesdemedida::model()->findAll(), 'id_unidades_medida', 'unidades_medida_nombre');

		$ListaProveedor = CHtml::listData(Proveedores::model()->findAll(), 'id_proveedor', 'proveedor_nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Categoria = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=1');
		$listacategorias = CHtml::listData($Categoria, 'id_catalogo_recurrente', 'nombre');

		// Tipo de prodcuto
		$tipop = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=32');
		$listatipop = CHtml::listData($tipop, 'num', 'nombre');
		// Obtenemos las bodegas de fabricacion
		$bodegafabr = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=28');
		$listabodegas = CHtml::listData($bodegafabr, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Subcategoria = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=15');
		$listasubcategoria = CHtml::listData($Subcategoria, 'id_catalogo_recurrente', 'nombre');



		// Obtenemos los tipos de como se entero de nosotros
		$Grupo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=13');
		$listagrupo = CHtml::listData($Grupo, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Subgrupo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=14');
		$listasubgrupo = CHtml::listData($Subgrupo, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Linea = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=16');
		$listalinea = CHtml::listData($Linea, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Sublinea = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=17');
		$listasublinea = CHtml::listData($Sublinea, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Segmento = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=18');
		$listasegmento = CHtml::listData($Segmento, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Subsegmento = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=19');
		$listasubsegmento = CHtml::listData($Subsegmento, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Marca = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=20');
		$listamarca = CHtml::listData($Marca, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Submarca = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=21');
		$listasubmarca = CHtml::listData($Submarca, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Modelo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=22');
		$listamodelo = CHtml::listData($Modelo, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Submodelo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=23');
		$listasubmodelo = CHtml::listData($Submodelo, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Posicion = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=24');
		$listaposicion = CHtml::listData($Posicion, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos los tipos de como se entero de nosotros
		$Subposicion = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=25');
		$listasubposicion = CHtml::listData($Subposicion, 'id_catalogo_recurrente', 'nombre');
		$valorPreseleccionado = $id;
		// echo "<pre>";
		// print_r($Categoria);
		// echo "</pre>";
		// exit();

		// echo "<pre>";
		// print_r($lista);
		// echo "</pre>";
		// exit();

		$this->render(
			'admin',
			array(
				'model' => $model,
				'lista' => $lista,
				'arraylistaproveedores' => $arraylistaproveedores,
				'arraylistaunidadesmedidas' => $arraylistaunidadesmedidas,
				'Categoria' => $Categoria,
				'listacategorias' => $listacategorias,
				'listasubcategoria' => $listasubcategoria,
				'listatipop' => $listatipop,
				'listagrupo' => $listagrupo,
				'listasubgrupo' => $listasubgrupo,
				'listalinea' => $listalinea,
				'listasublinea' => $listasublinea,
				'listasegmento' => $listasegmento,
				'listasubsegmento' => $listasubsegmento,
				'listamarca' => $listamarca,
				'listasubmarca' => $listasubmarca,
				'listamodelo' => $listamodelo,
				'listasubmodelo' => $listasubmodelo,
				'listaposicion' => $listaposicion,
				'listasubposicion' => $listasubposicion,
				'ListaProveedor' => $ListaProveedor,
				'keyword' => $keyword,
				'letra' => $id,
				'listabodegas' => $listabodegas,
				'valorPreseleccionado' => $valorPreseleccionado,
			)
		);
	}

	public function loadModel($id)
	{
		$model = Productos::model()->findByPk($id);
		return $model;
	}


	/*
	 * METODO QUE REGRESA UNA LISTA EN AJAX DE LOS PRODUCTOS, PARA AUTOCOMPLETE
	 * REALIZADA POR DANIEL VILLARREAL 31 DE ENERO DEL 2016
	 */
	public function actionProductosajax()
	{

		$sql = 'SELECT id_producto as id, 
		CONCAT_WS("",NULL,producto_nombre," ",producto_clave) as value, CONCAT_WS("",NULL,producto_nombre," ",producto_clave)  as label,
		producto_descripcion as descripcion 
		FROM productos WHERE producto_estatus = 1 and  (producto_nombre LIKE :qterm or producto_clave LIKE :qterm)';
		RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$qterm = '%' . $_GET['term'] . '%';
		$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
		$result = $command->queryAll();
		echo CJSON::encode($result);
		exit;
	}

	//  action para carga masiva de productos

	// public function actionCargamasiva()
	// {
	// 	if (isset($_FILES['archivocsv'])) {
	// 		if (empty($_FILES['archivocsv']['name'])) {
	// 			Yii::app()->user->setflash('danger', 'Favor de cargar el archivo');
	// 			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
	// 			$this->redirect($urlfrom);
	// 		}
	// 		set_time_limit(0);
	// 		try {
	// 			// $transaction = Yii::app()->db->beginTransaction();
	// 			// verificamos el proceso
	// 			$filename = $_FILES['archivocsv']['tmp_name'];
	// 			$renglon = 0;
	// 			if (@$fh_in = fopen("{$filename}", "r")) {
	// 				$allowed = array('csv');
	// 				$filename = $_FILES['archivocsv']['name'];
	// 				$ext = pathinfo($filename, PATHINFO_EXTENSION);
	// 				if (!in_array($ext, $allowed)) {
	// 					throw new Exception("Formato incorrecto");
	// 				}
	// 				while (!feof($fh_in)) {
	// 					$line = fgetcsv($fh_in, 1024, ',');
	// 					if ($renglon == 0) {
	// 						// // verificamos las columnas
	// 						// if (
	// 						// 	$line[0] != 'CLAVE' ||
	// 						// 	$line[1] != 'PRODUCTO' ||
	// 						// 	$line[2] != 'LAB' ||
	// 						// 	$line[3] != 'FICHA TECNICA' ||
	// 						// 	$line[4] != 'PRECIO 1' ||
	// 						// 	$line[5] != 'PRECIO 2' ||
	// 						// 	$line[6] != 'PRECIO 3'

	// 						// ) {
	// 						// 	throw new Exception("Encabezado de las columnas incorrectas");
	// 						// }
	// 						$renglon = 1;
	// 						continue;
	// 					}


	// 					$line[0] = utf8_encode($line[0]);
	// 					$line[1] = utf8_encode($line[1]);
	// 					$line[2] = utf8_encode($line[2]);
	// 					$line[3] = utf8_encode($line[3]);


	// 					$renglon++;
	// 					// definimos las variables
	// 					$nombre = $line[1];
	// 					$lab = $line[2];
	// 					$desc = strval($line[3]);
	// 					$clave = $line[0];

	// 					if ($lab == "MS") {
	// 						$cambiado = 68;
	// 					} else if ($lab == "RI") {
	// 						$cambiado = 69;
	// 					}

	// 					"dsjfklajdla"1/2""

	// 					$nuevoproducto = new Productos();
	// 					$nuevoproducto->producto_nombre = $nombre;
	// 					$nuevoproducto->producto_estatus = 1;
	// 					$nuevoproducto->producto_clave = $clave;
	// 					$nuevoproducto->producto_descripcion = $desc;
	// 					$nuevoproducto->eliminado = 0;
	// 					$nuevoproducto->id_bodega_fabricacion = $cambiado;
	// 					if (!$nuevoproducto->save()) {
	// 						print_r($nuevoproducto->getErrors());
	// 						exit;
	// 						throw new Exception("No fue posible generar el producto del renglon " . $renglon);
	// 					}
	// 				}
	// 			}
	// 			// $transaction->commit();
	// 			Yii::app()->user->setFlash('success', 'Proceso ejecutado con exito');
	// 			$this->refresh();
	// 		} catch (Exception $e) {
	// 			// $transaction->rollBack();
	// 			Yii::app()->user->setFlash('danger', $e->getMessage());
	// 		}
	// 	}
	// 	return $this->render('cargamasiva');
	// }


	public function actionPizarraproductos()
	{
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$bodeganombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
		$pedido = '';

		if ($id == 'null') {
			$id = '';
		}
		// traemos los pedidos pa mostrarlos en la pizarra
		$pedidos = Proyectos::model()->findAll(
			array('condition' => 'proyecto_estatus !=8')
		);

		if (empty($id)) {
			$condition = 'id_proyecto is not null and id_etapa is not null';
			if (!empty($bodeganombre)) {
				$productosPedidos = Proyectosproductos::model()->findAll(array('condition' => $condition . ' and bodega=:bodega', 'params' => array(':bodega' => $bodeganombre), 'order' => 'fecha_de_entrega ASC'));
			} else {
				$productosPedidos = Proyectosproductos::model()->findAll(array('condition' => $condition, 'order' => 'fecha_de_entrega ASC'));
			}
		}
		// Mostrar por bodega, apraesto se verifica si buscaron por bodega
		if (!empty($bodeganombre) && !empty($id)) {

			$productosPedidos = Proyectosproductos::model()->findAll(
				array(
					'condition' => 'bodega = :bodega and id_proyecto = :id',
					'params' => array(':bodega' => $bodeganombre, ':id' => $id),
					'order' => 'fecha_de_entrega ASC'
				)
			);

			$pedido = Proyectos::model()->find(
				array(
					'condition' => 'id_proyecto = :id',
					'params' => array(':id' => $id)
				)
			);
		} else if (!empty($id)) {
			// echo "<pre>";
			// print_r($id);
			// echo "</pre>";
			// exit();
			// cambiar el estatus del pedido(proyecto)
			$pedido = Proyectos::model()->find(
				array(
					'condition' => 'id_proyecto = :id',
					'params' => array(':id' => $id)
				)
			);
			if ($pedido['proyecto_estatus'] == 0) {
				$pedido['proyecto_estatus'] = 2;
				$pedido->save();
			}
			// obtenemos los productos del pedido que nos pasan de todas las bodegas
			$productosPedidos = Proyectosproductos::model()->findAll(
				array(
					'condition' => ' id_proyecto = :id',
					'params' => array(':id' => $id),
					'order' => 'fecha_de_entrega ASC'
				)
			);
		}
		// Obtenemos los tipos de estapas y bodegas
		$bodegas = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 28');
		$Etapas = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente= 34');

		$this->render(
			'pizarraproductos',
			array(
				'productosPedidos' => $productosPedidos,
				'Etapas' => $Etapas,
				'bodegas' => $bodegas,
				'pedidos' => $pedidos,
				'pedido' => $pedido

			)
		);
	}

	/**
	 * *Proceso para actualizar la etapa del prodcuto de pedido
	 * *lars -> 09/10/23
	 */
	public function actionActualizaretapa()
	{

		$id = $_POST['id'];
		$Datos = Proyectosproductos::model()->findByPk($id);
		// verificamos que datos no este vacio
		if (!empty($Datos)) {
			$Datos->id_etapa = $_POST['valor'];

			if ($Datos->save()) {
				echo CJSON::encode(
					array(
						'requestresult' => 'ok',
						'message' => 'Etapa actualizada con exito'
					)
				);
			} else {
				echo CJSON::encode(
					array(
						'requestresult' => 'fail',
						'message' => 'Ocurrio un error inesperado',
						'error' => print_r($Datos->getErrors())
					)
				);
			}
		} else {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Ocurrio un error inesperado, no se encontro el prodcuto',
				)
			);
		}
	}

	/***
	 * *action para actualizar la fecha de entrega de los prtoductos de la pizarra -> lars 10/10/23
	 * 
	 */
	public function actionactualizarfecha()
	{
		if (!empty($_POST)) {
			$fecha_entrega = isset($_POST['fecha']) ? $_POST['fecha'] : '';
			$id = isset($_POST['id']) ? $_POST['id'] : '';

			// verificamos que no esten vacios los datos 
			if (!empty($id)) {
				// si no esta vacio traemos los datos de ese prodcuto
				$prodcuto = Proyectosproductos::model()->find(
					array('condition' => 'id_proyectos_productos = :id', 'params' => array(':id' => $id))
				);
				// guardamos la fecha en la fila que se trajo

				$prodcuto->fecha_de_entrega = $fecha_entrega . ' ' . date('H:i:s');

				if ($prodcuto->save()) {

					// verificamos si ya existe cambios anteriormente
					$guardarinfo = CatalogosRecurrentes::model()->find(
						array(
							'condition' => 'num =' . $id . ' and nombre = "fecha de entrega"',
							'order' => 'id_catalogo_recurrente desc'
						)
					);

					// si noesta vacio quiere decir que no hay data y guardamos
					if (!empty($guardarinfo && $guardarinfo->update == null)) {

						$guardarinfo->update = $fecha_entrega;

						$guardarinfo->save();
					} else {
						$guardarinfo = new CatalogosRecurrentes;
						$guardarinfo->id_grupo_recurrente = 37;
						$guardarinfo->num = $id;
						$guardarinfo->nombre = 'fecha de entrega';
						$guardarinfo->descripcion = $fecha_entrega;
						$guardarinfo->id_usuario = Yii::app()->user->id;
						$guardarinfo->eliminado = 0;
						$guardarinfo->fecha_alta = date("Y-m-d H:i:s");
						$guardarinfo->save();
					}
					echo CJSON::encode(
						array(
							'requestresult' => 'ok',
							'message' => 'Fecha actualizada con exito'
						)
					);
				} else {
					echo CJSON::encode(
						array(
							'requestresult' => 'fail',
							'message' => 'Ocurrio un error inesperado',
							'error' => print_r($prodcuto->getErrors())
						)
					);
				}
			} else {
				echo CJSON::encode(
					array(
						'requestresult' => 'fail',
						'message' => 'Ocurrio un error inesperado',
					)
				);
			}
		}
	}
	// action para traer todos los pedidos

	public function actionTraerpedidos()
	{
		if (isset($_POST) && !empty($_POST)) {

			$sql = 'SELECT id_proyecto as id, CONCAT_WS(" - ", id_proyecto, proyecto_nombre) as value, CONCAT_WS(" - ", id_proyecto, proyecto_nombre) as label, proyecto_nombre as nombre FROM proyectos WHERE proyecto_nombre LIKE :q or id_proyecto LIKE :q and proyecto_estatus != 8';

			RActiveRecord::getAdvertDbConnection();
			$command = Yii::app()->dbadvert->createCommand($sql);
			$q = '%' . $_POST['q'] . '%';
			$command->bindParam(":q", $q, PDO::PARAM_STR);
			$result = $command->queryAll();

			// Modificar el formato de cada elemento en $result
			foreach ($result as &$item) {
				$item['value'] = '#' . $item['value'];
				$item['label'] = '#' . $item['label'];
			}
		}
		echo CJSON::encode($result);
		exit;
	}

	// action para el reporte de los prdocutos de fabricacion -> lars 17/10/23
	public function actionReportefabricacion()
	{

		$VerificarAcceso = $this->VerificarAcceso(23, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}

		// GET id y get nombre estaran vcacios

		$bodeganombre = isset($_GET['bodeganombre']) ? implode(',', $_GET['bodeganombre']) : [];
		// echo "<pre>";
		// print_r($bodeganombre);
		// echo "</pre>";
		// exit();

		//  @jl 12/12/23 
		try {
			// obtenemos ka fecha actual para el bg de la oc
			$hoy = date('Y-m-d');
			// traemos la fecha 7 dias antes a la actual
			$fechaantes = date('Y-m-d', strtotime($hoy . ' -3 days'));
			$id = isset($_GET['id']) ? $_GET['id'] : '';

			$conditionProyectosProductos = 'id_etapa is not null ';
			$paramsProyectosProductos = array();
			$conditionProyecto = '';
			$paramsProyecto = array();
			if (empty($id)) {
				// echo ' if del id';
				// exit;
				// if (!empty($bodeganombre)) {
				// 	$conditionProyectosProductos .= ' and bodega in (:bodega)';
				// 	$paramsProyectosProductos[':bodega'] = $bodeganombre;
				// }
				if (!empty($bodeganombre) && is_array($bodeganombre)) {
					$bodeganombre = array_map('intval', $bodeganombre); // Convierte todos los elementos a enteros

					$conditionProyectosProductos .= ' and bodega in (' . $bodeganombre . ')';
					$paramsProyectosProductos[':bodega'] = $bodeganombre;
					// echo ' if de bodega nombre';
					// exit;
				} else if (!empty($bodeganombre)) {


					$conditionProyectosProductos .= ' and bodega in (' . $bodeganombre . ')';
					$paramsProyectosProductos[':bodega'] = $bodeganombre;
					// echo ' if de bodega nombre';
					// exit;
				}
			} else if (!empty($bodeganombre) && !empty($id)) {
				// echo 'else if del id y bodega';
				// exit;
				$conditionProyectosProductos .= ' and bodega in (' . implode(',', $bodeganombre) . ')';
				$paramsProyectosProductos[':bodega'] = $bodeganombre;

				$conditionProyecto .= ' id_proyecto = :id';
				$paramsProyecto[':id'] = $id;
			} else if (!empty($id)) {
				// echo 'else if del id';
				// exit;
				$conditionProyecto .= ' t.id_proyecto = :id';
				$paramsProyecto[':id'] = $id;
			}

			// revisamos si el proyecto tiene al menos un producto con esas caracteristicas para mostrarlo
			$verificarSiProyectoTieneProductos = 'EXISTS (SELECT 1 FROM proyectosproductos WHERE proyectosproductos.id_proyecto = t.id_proyecto AND ' . $conditionProyectosProductos . ')';
			if (!empty($conditionProyecto)) {
				$verificarSiProyectoTieneProductos .= ' AND ' . $conditionProyecto;
			}

			$proyectos = Proyectos::model()->with(
				array(
					'proyectos_productos' => array(
						'condition' => $conditionProyectosProductos,
						'params' => $paramsProyectosProductos,

					)
				)
			)->findAll(
				array(
					'condition' => $verificarSiProyectoTieneProductos,
					'params' => array_merge($paramsProyecto, $paramsProyectosProductos),
					// 'order' => 'fecha_de_entrega desc'
				)
			);





			$listabodegas = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 28 and eliminado = 0 ');

			if (!empty($bodeganombre)) {

				$listabodegas = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 28 and eliminado = 0 and id_catalogo_recurrente in (' . $bodeganombre . ')');
			}

			// agrupamos los productos de cada proyecto por nombre de la bodega


			$nombreBodegas = array();
			foreach ($proyectos as $proyecto) {
				$groupedProyectosProductos = array();
				// foreach ($listabodegas as $lis) {
				// 	$groupedProyectosProductos[$lis['id_catalogo_recurrente']] = array();
				// }
				foreach ($proyecto['proyectos_productos'] as $proyectoProducto) {
					$bodega = $proyectoProducto['bodega'];
					// if (array_key_exists($bodega, $groupedProyectosProductos)) {
					// 	array_push($groupedProyectosProductos[$bodega], $proyectoProducto);
					// }
					if (!isset($groupedProyectosProductos[$bodega])) {
						$groupedProyectosProductos[$bodega] = array();
					}
					if (!isset($nombreBodegas[$bodega])) {
						$nombreBodegas[$bodega] = 0;
					}

					$groupedProyectosProductos[$bodega][] = $proyectoProducto;
				}
				$proyecto['proyectos_productos'] = $groupedProyectosProductos;
			}



			foreach ($proyectos as $proyecto) {
				foreach ($proyecto['proyectos_productos'] as $key => $proyectoProducto) {
					$numeroProductosEnBodega = count($proyecto['proyectos_productos'][$key]);
					if ($nombreBodegas[$key] < $numeroProductosEnBodega) {
						$nombreBodegas[$key] = $numeroProductosEnBodega;
					}
				}
			}


			$bodegaschidas = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 28 and eliminado = 0 ');
			// Agregar un nuevo dato al arreglo
			$nuevoDato = array('id_catalogo_recurrente' => '', 'nombre' => 'Todas las Bodegas');
			$bodegaschidas[] = $nuevoDato;
			$this->render(
				'reporte_fabricacion',
				array(
					'bodeganombre' => $bodeganombre,
					'listabodegas' => $listabodegas,
					'nombreBodegas' => $nombreBodegas,
					'proyectos' => $proyectos,
					'hoy' => $hoy,
					'fechaant' => $fechaantes,
					'bodegaschidas' => $bodegaschidas
				)
			);
		} catch (Exception $e) {
			Yii::app()->user->setFlash('danger', 'Error: ' . $e->getMessage());
			$this->redirect(Yii::app()->createUrl('productos/admin'));
		}
	}

	// reporte_fabricacion
	// Action para buscar en los grupos recurrentes y ver si hay cambios


	// public function actionActbode()
	// {
	// 	$proyectosp = Proyectosproductos::model()->findAll(
	// 		array(
	// 			'condition' => 'bodega = RITUBULARES'
	// 		)
	// 	);


	// 	if (!empty($proyectosp)) {
	// 		foreach ($proyectosp as $proyecto) {
	// 			$cambio = Proyectosproductos::model()->find('id_proyectos_productos = ' . $proyecto->id_proyectos_productos);
	// 			$cambio->bodega = '677';
	// 			$cambio->save();
	// 		}
	// 	}
	// }

	public function actionBuscarcambios()
	{
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		//  si id no esta vacio quiere decir que que traermos ids y buscamos en recurrentes si hay mas de un registro
		// colocamos mayor a 5 por que siempre que se agrega un nuevo pedido en la tabla de cambios se agregan los datos para tener registro, por ende ya hay 5 registros
		// con el valor num, si hay ams de 5 quiere decir que hiceron un cambio
		// traemos el prducto de proyectosprodcutos con el id que nos pasan 
		$producto = Proyectosproductos::model()->find('id_proyectos_productos=' . $id);
		// sacamos la fecha de cuando se subio ese prodcuto 
		$ferchaprodcuto = '2023-11-13 17:06:26';
		$sql = 'SELECT * FROM catalogos_recurrentes WHERE num = :q AND eliminado = 0  AND fecha_alta BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 2 DAY) AND UNIX_TIMESTAMP(fecha_alta) != UNIX_TIMESTAMP(:fechaalta);';
		RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$q = $id;
		$fechaalta = $ferchaprodcuto;
		$command->bindParam(":q", $q, PDO::PARAM_STR);
		$command->bindParam(":fechaalta", $fechaalta, PDO::PARAM_STR);
		$cambios = $command->queryAll();

		// echo "<pre>";
		// print_r($ferchaprodcuto);
		// print_r($cambios);
		// echo "</pre>";
		// exit();
		if (!empty($cambios)) {
			// si no esta vacio quiere decir que hay cambios
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'variable' => true,
					'mensaje' => 'hay cambios',
					// 'fecha' => $cambios['fecha_alta']
				)
			);
		} else {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'variable' => false,
					'mensaje' => 'no hay cambios'
				)
			);
		}
		// echo CJSON::encode($cambios);
	}

	public function actionKpi()
	{
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit();
		// debe llegar id usuario
		$producto_nombre = (isset($_POST['producto_nombre'])) ? $_POST['producto_nombre'] : '';
		// $fechainicio = (isset($_POST['fechainicio'])) ? $_POST['fechainicio'] : $this->_data_first_month_day();
		$fechainicio = (isset($_POST['fechainicio'])) ? $_POST['fechainicio'] : $this->_data_last_three_month_day();
		$fechafin = (isset($_POST['fechafin'])) ? $_POST['fechafin'] : $this->_data_last_month_day();
		$idus = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : '';
		// $fechainicio = (isset($_POST['fechainicio'])) ? $_POST['fechainicio'] : $this->_data_first_month_day();
		// $fechafin = (isset($_POST['fechafin'])) ? $_POST['fechafin'] : $this->_data_last_month_day();
		$id_producto = (isset($_POST['id_producto'])) ? $_POST['id_producto'] : '';
		$cantidad = '';
		$venta_total = '';

		$parametros = 'id_producto = ' . $id_producto . ' and fecha_alta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" ';

		if ($idus != '') {
			// si entramos aqui quiere decir que busco por usuario 
			$parametros .= ' and id_proyecto in( select id_proyecto from proyectos where id_usuario = ' . $idus . ')';
		}


		// con el id_producto vamos a proyectos prodcutos 
		if ($id_producto != '') {
			$proyectos_productos = Proyectosproductos::model()->findAll($parametros);
			// haremos un for each para revisar que el proyecto no este cancelado 
			$ids_proyecto_validos = []; //-> introduciremos los proyectos que no esten eliminados
			foreach ($proyectos_productos as $pp) {
				$proyecto = Proyectos::model()->find(
					array(
						'condition' => 'id_proyecto = :proyecto and proyecto_estatus != 7',
						'params' => array(':proyecto' => $pp->id_proyecto)
					)
				);
				if (!empty($proyecto)) {
					$ids_proyecto_validos[] = $proyecto['id_proyecto'];
				} else {
					continue;
				}
			}
			$ids_proyecto_validos = implode(',', $ids_proyecto_validos);
			// traemos los proyectos productos con los ids de los proyectos no cancelados 
			$sql = 'SELECT SUM(proyectos_productos_cantidad) AS cantidad, SUM(precio_venta_producto) AS precio FROM proyectosproductos 
			where id_proyecto in(' . $ids_proyecto_validos . ') and id_producto =' . $id_producto;
			RActiveRecord::getAdvertDbConnection();
			$command = Yii::app()->dbadvert->createCommand($sql);
			$result = $command->queryAll();
			$cantidad = $result[0]['cantidad'];
			$venta_total = $result[0]['cantidad'] * $result[0]['precio'];
		}

		$this->render(
			'kpi_ventasp',
			[
				// 'fechainicio' => $fechainicio,
				// 'fechafin' => $fechafin,
				'id_producto' => $id_producto,
				'producto_nombre' => $producto_nombre,
				'cantidad' => $cantidad,
				'total' => $venta_total,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin,

			]
		);
	}

	// action para el reporte de ventas del producto 
	// lars 13/11/23

	public function actionReporteventas()
	{

		$VerificarAcceso = $this->VerificarAcceso(28, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}


		$mes = isset($_GET['mes']) ? $_GET['mes'] : '';
		$anio = isset($_GET['anio']) ? $_GET['anio'] : '';
		$todo = isset($_GET['todo']) ? $_GET['todo'] : '';
		$ventaxbodega = isset($_GET['ventaxbodega']) ? $_GET['ventaxbodega'] : 0;
		$ventaxusu = isset($_GET['ventaxusu']) ? $_GET['ventaxusu'] : 0;
		$categorias = isset($_GET['categorias']) ? $_GET['categorias'] : [];



		$ultimoDia = 0;

		$proyectosproductos = [];
		$fechas = [];


		if (!empty($todo)) {
		}

		// de acuerdo al numero de mes obtenemso la fecha de fin e inicio
		//esto solo nos sorve para colocar 30-31 o 28 columnas que son lso dias del mesi y rellenarlas filas 
		if (!empty($mes)) {

			$fechainicio = new DateTime(date("$anio-$mes-01"));
			// obtenemos el ultimo dia
			$ultimoDia = $fechainicio->format("t");
			$fechafin = new DateTime(date("$anio-$mes-$ultimoDia"));
			//formateamos las fechas como string
			// $fis = $fechainicio->format("Y-m-d");
			// $ffs = $fechafin->format("Y-m-d");

			$fechaInicio = DateTime::createFromFormat('Y-m-d', $fechainicio->format('Y-m-d'));
			$fechaFin = DateTime::createFromFormat('Y-m-d', $fechafin->format('Y-m-d'));
			// Añadir el primer día
			$fechas[] = $fechaInicio->format('Y-m-d');

			// Bucle para añadir los días intermedios
			while ($fechaInicio < $fechaFin) {
				$fechaInicio->add(new DateInterval('P1D'));
				$fechas[] = $fechaInicio->format('Y-m-d');
			}
			// Añadir el último día
			$fechas[] = $fechaFin->format('Y-m-d');
			$fechas = array_unique($fechas);
		}

		$condition = 'eliminado = :eliminado AND producto_estatus = :estatus';
		$params = [':eliminado' => 0, ':estatus' => 1,];

		if ($ventaxbodega != 0) {
			// obtenemos los usuarios que pertenezcan a esta venta por bodega
			$usuariosxbodega = Usuarios::model()->findAll('eliminado = 0 and equipo_venta = ' . $ventaxbodega);

			// extraemos los IDs de usuarios
			$idsUsuarios = array_map(function ($usuario) {
				return $usuario->ID_Usuario;
			}, $usuariosxbodega);

			if (!empty($idsUsuarios)) {
				// 1) Construye placeholders para usuarios :uid0, :uid1, ...
				$placeholdersUsuarios = [];
				$paramsUsuarios = []; // Array separado para params de usuarios
				foreach ($idsUsuarios as $k => $idUsuario) {
					$key = ':uid' . $k;
					$placeholdersUsuarios[] = $key;
					$paramsUsuarios[$key] = (int)$idUsuario;
				}

				// 2) Consulta productos con IN de usuarios
				$command = Yii::app()->db->createCommand()
					->selectDistinct('pp.id_producto')
					->from('proyectosproductos pp')
					->join('proyectos p', 'p.id_proyecto = pp.id_proyecto')
					->where('p.id_usuario IN (' . implode(',', $placeholdersUsuarios) . ')');

				// Vincula los parámetros de usuarios
				foreach ($paramsUsuarios as $key => $value) {
					$command->bindValue($key, $value);
				}

				$idsProductos = $command->queryColumn();

				if (!empty($idsProductos)) {
					// 3) Construye placeholders para productos :pid0, :pid1, ...
					$ph = [];
					foreach ($idsProductos as $k => $id) {
						$key = ':pid' . $k;
						$ph[] = $key;
						$params[$key] = (int)$id;
					}
					$condition .= ' AND id_producto IN (' . implode(',', $ph) . ')';
				}
			}
		}

		if ($ventaxusu != 0) {
			// 1) Trae los id_producto de los proyectos del usuario
			$idsProductos = Yii::app()->db->createCommand()
				->selectDistinct('pp.id_producto')
				->from('proyectosproductos pp')
				->join('proyectos p', 'p.id_proyecto = pp.id_proyecto')
				->where('p.id_usuario = :uid', [':uid' => (int)$ventaxusu])
				// ->andWhere('p.proyecto_estatus != 7') // opcional
				->queryColumn();

			if (!empty($idsProductos)) {
				// 2) Construye placeholders :pid0, :pid1, ...
				$ph = [];
				foreach ($idsProductos as $k => $id) {
					$key = ':pid' . $k;
					$ph[] = $key;
					$params[$key] = (int)$id;
				}
				$condition .= ' AND id_producto IN (' . implode(',', $ph) . ')';
			}
		}



		if (!empty($categorias)) {
			$placeholders = [];
			foreach ($categorias as $i => $catId) {
				$key = ':cat' . $i;
				$placeholders[] = $key;
				$params[$key] = (int)$catId;
			}

			// Asumiendo que la columna se llama id_categoria
			$condition .= ' AND id_categoria IN (' . implode(',', $placeholders) . ')';
		}

		// sacamos la lista de productos
		// $productos = Productos::model()->findAll('eliminado = 0 and producto_estatus = 1');
		$productos = Productos::model()->findAll($condition, $params);
		$sql = 'SELECT DISTINCT YEAR(proyecto_fecha_alta) as anio FROM proyectos WHERE proyecto_estatus!=7 order by proyecto_fecha_alta desc ';
		// RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$result = $command->queryAll();

		// Extraer solo los años de los resultados
		$anios = $result;

		// RActiveRecord::getDbConnection();

		// obtenemos las bodegas de fabricacion lars 19/10/2025
		// $bodegafabr = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=28');
		$bodegafabr = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=6');
		$listabodegas = CHtml::listData($bodegafabr, 'id_catalogo_recurrente', 'nombre');
		// obtenemos las bodegas de fabricacion lars 19/10/2025
		$usuarios = Usuarios::model()->findAll('eliminado  = 0 ');
		$vendedores = CHtml::listData($usuarios, 'ID_Usuario', 'Usuario_Nombre');
		// obtenemos las bodegas de fabricacion lars 19/10/2025
		// CATEGORÍAS: siempre desde la BD principal
		$categoriasarr = Yii::app()->db->createCommand()
			->select('id_catalogo_recurrente, nombre')
			->from('catalogos_recurrentes')              // usa '{{catalogos_recurrentes}}' si tienes tablePrefix
			->where('eliminado = 0 AND id_grupo_recurrente = 1')
			->queryAll();

		$valoresCategorias = CHtml::listData($categoriasarr, 'id_catalogo_recurrente', 'nombre');

		// echo "<pre>";
		// print_r($valoresCategorias);
		// echo "</pre>";
		// exit;


		// ★ NUEVO: persistir filtros para que los usen las funciones
		$this->filtrosReporte = [
			'anio'        => $anio,
			'mes'         => $mes,
			'ventaxbodega' => (int)$ventaxbodega, // no lo necesitamos en las funciones (ya filtras productos), pero lo guardo por consistencia
			'ventaxusu'   => (int)$ventaxusu,
			'todo'        => $todo,
		];

		$this->render(
			'reporte_ventas_productos',
			[

				'udia' => $ultimoDia,
				'proyectosproductos' => $proyectosproductos,
				'productos' => $productos,
				'mes' => $mes,
				'fechas' => $fechas,
				'anios' => $anios,
				'anio' => $anio,
				'ventaxbodega' => $ventaxbodega,
				'ventaxusu' => $ventaxusu,
				'todo' => $todo,
				'listabodegas' => $listabodegas,
				'vendedores' => $vendedores,
				'valoresCategorias' => $valoresCategorias
			]
		);
	}

	// ★ NUEVO: helper para agregar condición de vendedor
	private function _aplicarFiltroVendedor(&$where, array &$params)
	{
		if (!empty($this->filtrosReporte['ventaxusu'])) {
			$where .= ' AND p.id_usuario = :uid';
			$params[':uid'] = (int)$this->filtrosReporte['ventaxusu'];
		}
	}


	// AGREGAR este método en tu controlador
	private function _aplicarFiltroBodega(&$where, &$params)
	{
		if (isset($this->filtrosReporte['ventaxbodega']) && (int)$this->filtrosReporte['ventaxbodega'] > 0) {
			$ventaxbodega = (int)$this->filtrosReporte['ventaxbodega'];

			// Obtener usuarios de la bodega
			$usuariosxbodega = Usuarios::model()->findAll(
				'eliminado = 0 AND equipo_venta = :bodega',
				[':bodega' => $ventaxbodega]
			);

			if (!empty($usuariosxbodega)) {
				$idsUsuarios = array_map(function ($u) {
					return (int)$u->ID_Usuario;
				}, $usuariosxbodega);

				// Crear placeholders
				$placeholders = [];
				foreach ($idsUsuarios as $k => $idUsu) {
					$key = ':bodega_usr' . $k;
					$placeholders[] = $key;
					$params[$key] = $idUsu;
				}

				$where .= ' AND p.id_usuario IN (' . implode(',', $placeholders) . ')';
			}
		}
	}

	// funcion para obtener el precio de venta 

	public function funcionpv($id, $fecha)
	{

		// ★ CAMBIO: filtrar por fecha exacta del día y por vendedor
		$where  = 'pp.id_producto = :id
               AND DATE(p.proyecto_fecha_alta) = :fecha
               AND p.proyecto_estatus != 7';

		$params = [
			':id'    => $id,
			':fecha' => $fecha, // viene ya como 'Y-m-d' de tu arreglo $fechas
		];

		// ★ NUEVO: vendedor
		$this->_aplicarFiltroVendedor($where, $params);
		$this->_aplicarFiltroBodega($where, $params);  // ← AGREGAR

		$sql = "SELECT
                pp.precio_venta_producto AS pvp,
                SUM(pp.proyectos_productos_cantidad) AS cantidad
            FROM proyectosproductos pp
            INNER JOIN proyectos p ON pp.id_proyecto = p.id_proyecto
            WHERE {$where}
            GROUP BY pp.precio_venta_producto";

		// RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		foreach ($params as $k => $v) $command->bindValue($k, $v);
		$result = $command->queryAll();

		$unico = [];
		foreach ($result as $r) {
			if (isset($r['pvp'])) {
				$unico = [$r['pvp']];
			}
		}
		return $unico;
		// $sql = 'SELECT pp.precio_venta_producto AS pvp,sum(pp.proyectos_productos_cantidad) as cantidad FROM proyectosproductos AS pp 
		// INNER JOIN proyectos p ON pp.id_proyecto = p.id_proyecto WHERE pp.id_producto = :id AND p.proyecto_fecha_alta LIKE :fecha AND proyecto_estatus != 7 GROUP BY precio_venta_producto';
		// RActiveRecord::getAdvertDbConnection();
		// $command = Yii::app()->dbadvert->createCommand($sql);
		// $fecha = '%' . $fecha . '%';
		// $command->bindParam(":fecha", $fecha, PDO::PARAM_STR);
		// $command->bindParam(":id", $id, PDO::PARAM_STR);
		// $result = $command->queryAll();

		// $unico = [];
		// foreach ($result as $r) {

		// 	if (isset($r['pvp'])) {
		// 		$unico = [$r['pvp']];
		// 	}
		// }
		// // echo "<pre>";-;
		// return $unico;
		// ${exit();


	}

	// funcion para obtener las catntidades del prodcuto en el reporte de vetna del prodcuto -> lars 17/11/23
	public function funcion($id, $fecha)
	{
		// ★ CAMBIO: fecha exacta y vendedor
		$where  = 'pp.id_producto = :id
               AND DATE(p.proyecto_fecha_alta) = :fecha
               AND p.proyecto_estatus != 7';

		$params = [
			':id'    => $id,
			':fecha' => $fecha,
		];

		$this->_aplicarFiltroVendedor($where, $params);
		$this->_aplicarFiltroBodega($where, $params);  // ← AGREGAR

		$sql = "SELECT
                SUM(pp.precio_venta_producto) AS suma_monto,
                SUM(pp.proyectos_productos_cantidad) AS cantidad
            FROM proyectosproductos pp
            INNER JOIN proyectos p ON pp.id_proyecto = p.id_proyecto
            WHERE {$where}
            GROUP BY pp.id_producto";

		// RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		foreach ($params as $k => $v) $command->bindValue($k, $v);
		return $command->queryAll();
		// $sql = 'SELECT SUM(pp.precio_venta_producto) AS suma_monto, sum(pp.proyectos_productos_cantidad) as cantidad FROM proyectosproductos AS pp 
		// INNER JOIN proyectos p ON pp.id_proyecto = p.id_proyecto WHERE pp.id_producto = :id AND p.proyecto_fecha_alta LIKE :fecha AND proyecto_estatus != 7 GROUP BY id_producto';
		// RActiveRecord::getAdvertDbConnection();
		// $command = Yii::app()->dbadvert->createCommand($sql);
		// $fecha = '%' . $fecha . '%';
		// $command->bindParam(":fecha", $fecha, PDO::PARAM_STR);
		// $command->bindParam(":id", $id, PDO::PARAM_STR);
		// $result = $command->queryAll();

		// // echo "<pre>";
		// // print_r($result);
		// // echo "</pre>";
		// return $result;
		// // ${exit();


	}
	// action para buscar usaurio -> lars 05/01/24
	public function actionBuscarusuario()
	{
		$sql = 'SELECT ID_Usuario as id, CONCAT_WS("",NULL,Usuario_Nombre," ",Usuario_Email) as value, CONCAT_WS("",NULL,Usuario_Nombre," ",Usuario_Email)  as label FROM usuarios WHERE Usuario_Nombre LIKE :qterm or Usuario_Email LIKE :qterm';
		RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$qterm = '%' . $_GET['term'] . '%';
		$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
		$result = $command->queryAll();
		echo CJSON::encode($result);
		exit;
	}

	public function todo($id)
	{
		// $sql = 'SELECT sum(pp.proyectos_productos_cantidad) AS cantidad, sum(pp.precio_venta_producto) AS precio,
		// sum(pp.proyectos_productos_cantidad)*sum(pp.precio_venta_producto) AS total  
		// FROM proyectosproductos as pp INNER JOIN proyectos AS p ON pp.id_proyecto =  p.id_proyecto
		// WHERE p.proyecto_fecha_alta BETWEEN DATE ("2023-01-01") AND DATE("2023-12-31") AND id_producto = :id AND p.proyecto_estatus!=7 GROUP BY id_producto';
		// RActiveRecord::getAdvertDbConnection();
		// $command = Yii::app()->dbadvert->createCommand($sql);
		// $command->bindParam(":id", $id, PDO::PARAM_STR);
		// $result = $command->queryAll();

		// // echo "<pre>";
		// // print_r($result);
		// // echo "</pre>";
		// return $result;

		// ★ NUEVO: año dinámico (y todo el año)
		$anio = !empty($this->filtrosReporte['anio']) ? (int)$this->filtrosReporte['anio'] : (int)date('Y');
		$ini  = "{$anio}-01-01";
		$fin  = "{$anio}-12-31";

		$where = 'pp.id_producto = :id
              AND p.proyecto_fecha_alta BETWEEN DATE(:ini) AND DATE(:fin)
              AND p.proyecto_estatus != 7';

		$params = [
			':id'  => $id,
			':ini' => $ini,
			':fin' => $fin,
		];

		// ★ NUEVO: si hay vendedor seleccionado, filtrarlo
		$this->_aplicarFiltroVendedor($where, $params);
		$this->_aplicarFiltroBodega($where, $params);  // ← AGREGAR

		$sql = "SELECT
                SUM(pp.proyectos_productos_cantidad) AS cantidad,
                SUM(pp.precio_venta_producto)      AS precio,
                SUM(pp.proyectos_productos_cantidad)*SUM(pp.precio_venta_producto) AS total
            FROM proyectosproductos pp
            INNER JOIN proyectos p ON pp.id_proyecto = p.id_proyecto
            WHERE {$where}
            GROUP BY pp.id_producto";

		// RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		foreach ($params as $k => $v) $command->bindValue($k, $v);
		return $command->queryAll();
		// ${exit();

	}


	public function actionRegresarEstatusEmpaqueProyectoProducto()
	{
		try {
			$idPproducto = isset($_POST['idPproducto']) ? $_POST['idPproducto'] : "";
			if (empty($idPproducto)) {
				throw new Exception("No hay id de producto Producto");
			}

			$proyectoProducto = Proyectosproductos::model()->findByPk($idPproducto);
			if (empty($proyectoProducto)) {
				throw new Exception("No se encontro el proyecto producto ");
			}

			$txtPedido = "Pedido: " . $proyectoProducto['id_proyecto'];
			$txtNombre = trim($proyectoProducto['rl_producto']['producto_nombre']);

			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Producto encontrado con exito',
					'data' => array(
						'id_producto' => $proyectoProducto['id_proyectos_productos'],
						'empacado' => $proyectoProducto['empacado'],
						'txtNombre' => $txtNombre,
						'txtPedido' => $txtPedido,
					)
				)
			);
		} catch (Exception $e) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => $e->getMessage()
				)
			);
		}
	}

	public function actionActualizarEstatusEmpaque()
	{
		try {
			$idPproducto = isset($_POST['idPproducto']) ? $_POST['idPproducto'] : "";
			if (empty($idPproducto)) {
				throw new Exception("No hay id de producto Producto");
			}
			$proyectoProducto = Proyectosproductos::model()->findByPk($idPproducto);
			if (empty($proyectoProducto)) {
				throw new Exception("No se encontro el proyecto producto ");
			}

			if ($proyectoProducto->empacado == 0) {
				$proyectoProducto->empacado = 1;
			} else {
				$proyectoProducto->empacado = 0;
			}

			if (!$proyectoProducto->save()) {
				throw new Exception("No se pudo actualizar el estatus");
			}

			$cambios = CatalogosRecurrentes::model()->find(
				array(
					'condition' => '`update` is not null and  num = :num',
					'params' => array(':num' => $idPproducto)
				)
			);

			if (!empty($cambios)) {
				$mensajecolor = 'no vacio';
			} else {
				$mensajecolor = 'vacio';
			}

			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'cambio' => $mensajecolor,
					'message' => 'Producto actualizado con exito',
					'data' => array(
						'id_producto' => $proyectoProducto['id_proyectos_productos'],
						'empacado' => $proyectoProducto['empacado'],
					)
				)
			);
		} catch (Exception $e) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => $e->getMessage()
				)
			);
		}
	}
}
