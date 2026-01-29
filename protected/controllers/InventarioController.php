<?php

class InventarioController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */

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
				// allow authenticated user to perform 'create' and 'update' actions
				'users' => array('@'),
			),
			array(
				'deny',
				// deny all users
				'users' => array('*'),
			),
		);
	}
	/**  **/
	public function init() {}

	public function actionIndex()
	{
		$VerificarAcceso = $this->VerificarAcceso(25, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}

		if (isset($_GET['actualizarproductos'])) {
			$sql = '
            INSERT INTO sucursales_productos (id_sucursal,id_producto)
            SELECT s.id as id_sucursal, p.id as id_producto 
            FROM productos p, sucursales s
            WHERE p.tipo IN (1) 
              AND p.id NOT IN (
                  SELECT id_producto FROM sucursales_productos WHERE id_sucursal = s.id
              )  
              AND p.eliminado = 0 
              AND s.eliminado = 0';
			if (Yii::app()->db->createCommand($sql)->execute()) {
				Yii::app()->user->setFlash('success', 'Productos actualizados con éxito.');
			} else {
				Yii::app()->user->setFlash('danger', 'Sin productos por agregar.');
			}
			$this->redirect(Yii::app()->getRequest()->getUrlReferrer());
		}

		$sucursales = Sucursales::model()->findAll(array('condition' => 'eliminado = 0 and estatus = 1'));

		// 🔄 AGREGADO: Obtener ID del producto (si lo seleccionó)
		$idProducto = isset($_GET['id_producto']) && $_GET['id_producto'] != '' ? (int)$_GET['id_producto'] : 0;

		$categoria = '';
		if (isset($_GET['subcategoria']) && $_GET['subcategoria'] != '' && $_GET['subcategoria'] != '0') {
			$categoria = $_GET['subcategoria'];
		} elseif (isset($_GET['categoria']) && $_GET['categoria'] != '' && $_GET['categoria'] != '0') {
			$categoria = $_GET['categoria'];
		} else {
			$categoria = (isset($_GET['familia'])) ? $_GET['familia'] : 0;
		}

		$cadena = $this->Cadenacategorias($categoria);

		$Categoria = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente =1');
		$categorias = CHtml::listData($Categoria, 'id_catalogo_recurrente', 'nombre');

		$Subcategoria = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=15');
		$subcategorias = CHtml::listData($Subcategoria, 'id_catalogo_recurrente', 'nombre');

		$sucursal = (isset($_GET['sucursal']) && $_GET['sucursal'] != '') ? $_GET['sucursal'] : Yii::app()->user->getState('id_sucursal');

		// 🔧 MODIFICADO: preparar condición extra por producto
		$condicionProducto = '';
		if ($idProducto > 0) {
			$condicionProducto = ' AND idProducto.id_producto = ' . $idProducto;
		}

		$nombreProducto = '';
		if ($idProducto > 0) {
			$producto = Productos::model()->findByPk($idProducto);
			if ($producto !== null) {
				$nombreProducto = $producto->producto_nombre;
			}
		}

		if ($categoria != 0) {
			$categorinas_in = $this->ObtenerHijosCat($categoria);

			$lista = SucursalesProductos::model()->with(array(
				'idProducto' => array(
					'condition' => 'idProducto.eliminado = 0 AND idProducto.id_categoria IN (' . $categorinas_in . ')' . $condicionProducto
				),
				'idSucursal' => array(
					'condition' => 'idSucursal.eliminado = 0 AND idSucursal.id = :id_suc',
					'params' => array(':id_suc' => $sucursal)
				)
			))->findAll(array());
		} else {
			$lista = SucursalesProductos::model()->with(array(
				'idProducto' => array(
					'condition' => 'idProducto.eliminado = 0' . $condicionProducto
				),
				'idSucursal' => array(
					'condition' => 'idSucursal.eliminado = 0 AND idSucursal.id = :id_suc',
					'params' => array(':id_suc' => $sucursal)
				)
			))->findAll(array());
		}

		$listacategorias = $this->Listacategorias(0);
		if (!empty($listacategorias)) {
			if (!count($listacategorias) > 0) {
				$listacategorias = array();
			}
		}

		$this->render('index', array(
			'lista' => $lista,
			'sucursales' => $sucursales,
			'listacategorias' => $listacategorias,
			'categoria' => $categoria,
			'sucursal' => $sucursal,
			'categorias' => $categorias,
			'subcategorias' => $subcategorias,
			'cadena' => $cadena,
			'id_producto' => $idProducto, // << AÑADE ESTO
			'nombre_producto' => $nombreProducto, // <-- AÑADE ESTO
		));
	}

	/**
	 * Pantalla para ver el inventario por sucursal
	 */
	// public function actionIndex()
	// {


	// 	$VerificarAcceso = $this->VerificarAcceso(25, Yii::app()->user->id);
	// 	if (!$VerificarAcceso) {
	// 		// Lo redireccionamos a la pagina acceso restringido
	// 		$this->redirect(Yii::app()->createURL('site/noautorizado'));
	// 	}

	// 	// actualizar productos - metodo para refrescar la lista de productos que no existen
	// 	if (isset($_GET['actualizarproductos'])) {
	// 		$sql = '
	// 			INSERT INTO sucursales_productos (id_sucursal,id_producto)
	// 			select s.id as id_sucursal, p.id as id_producto from productos p,sucursales s
	// 			 where p.tipo in(1) and p.id not in (select id_producto from sucursales_productos where id_sucursal=s.id)  and p.eliminado = 0 and s.eliminado = 0';
	// 		if (Yii::app()->db->createCommand($sql)->execute()) {
	// 			Yii::app()->user->setflash('success', 'Productos actualizados con exito.');
	// 		} else {
	// 			Yii::app()->user->setflash('danger', 'Sin productos por agregar.');
	// 		}
	// 		//
	// 		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
	// 		$this->redirect($urlfrom);
	// 	}
	// 	$sucursales = Sucursales::model()->findAll(array('condition' => 'eliminado = 0 and estatus = 1'));
	// 	$categoria = '';

	// 	if (isset($_GET['id_producto']) && $_GET['id_producto'] != '') {
	// 		$idProducto = (int)$_GET['id_producto'];
	// 		$condicionExtra = " AND idProducto.id = $idProducto";
	// 	} else {
	// 		$condicionExtra = "";
	// 	}

	// 	if (isset($_GET['subcategoria']) && $_GET['subcategoria'] != '' && $_GET['subcategoria'] != '0') {
	// 		// agregamos la categoria al producto
	// 		$categoria = $_GET['subcategoria'];
	// 	} elseif (isset($_GET['categoria']) && $_GET['categoria'] != '' && $_GET['categoria'] != '0') {
	// 		$categoria = $_GET['categoria'];
	// 	} else {
	// 		$categoria = (isset($_GET['familia'])) ? $_GET['familia'] : 0;
	// 	}
	// 	/*echo $categoria;*/

	// 	$cadena = $this->Cadenacategorias($categoria);



	// 	// Obtenemos los tipos de como se entero de nosotros
	// 	$Categoria = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=1');
	// 	$categorias = CHtml::listData($Categoria, 'id_catalogo_recurrente', 'nombre');


	// 	$Subcategoria = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=15');
	// 	$subcategorias = CHtml::listData($Subcategoria, 'id_catalogo_recurrente', 'nombre');

	// 	$sucursal = (isset($_GET['sucursal']) && $_GET['sucursal'] != '') ? $_GET['sucursal'] : Yii::app()->user->getstate('id_sucursal');



	// 	if ($categoria != 0) {
	// 		$categorinas_in = $this->ObtenerHijosCat($categoria);

	// 		$lista = SucursalesProductos::model()->with(
	// 			array(
	// 				// 'idProducto' => array('condition' => 'idProducto.eliminado=0 and idProducto.id_categoria in(' . $categorinas_in . ') and idProducto.tipo in (1,4)'),
	// 				'idProducto' => array('condition' => 'idProducto.eliminado=0 and idProducto.id_categoria in(' . $categorinas_in . ')'),
	// 				'idSucursal' => array('condition' => 'idSucursal.eliminado=0 and idSucursal.id=:id_suc', 'params' => array(':id_suc' => $sucursal))
	// 			)
	// 		)->findAll(array());
	// 	} else {
	// 		$lista = SucursalesProductos::model()->with(
	// 			array(
	// 				'idProducto' => array('condition' => 'idProducto.eliminado=0'),
	// 				'idSucursal' => array('condition' => 'idSucursal.eliminado=0 and idSucursal.id=:id_suc', 'params' => array(':id_suc' => $sucursal))
	// 			)
	// 		)->findAll(array());
	// 	}
	// 	// renderizamos
	// 	$listacategorias = $this->Listacategorias(0);
	// 	if (!empty($listacategorias)) {

	// 		if (!count($listacategorias) > 0) {
	// 			$listacategorias = array();
	// 		}
	// 	}
	// 	// echo 'aca2';
	// 	// exit;
	// 	$this->render(
	// 		'index',
	// 		array(
	// 			'lista' => $lista,
	// 			'sucursales' => $sucursales,
	// 			'listacategorias' => $listacategorias,
	// 			'categoria' => $categoria,
	// 			'sucursal' => $sucursal,
	// 			#'familias' => $familias,
	// 			'categorias' => $categorias,
	// 			'subcategorias' => $subcategorias,
	// 			'cadena' => $cadena,

	// 		)
	// 	);
	// }

	public function actionAutocomplete()
	{
		if (isset($_GET['term'])) {
			$term = $_GET['term'];
			$productos = Productos::model()->findAll(array(
				'condition' => 'eliminado = 0 AND producto_nombre LIKE :term',
				'params' => array(':term' => "%$term%"),
				'limit' => 10
			));

			$result = array();
			foreach ($productos as $producto) {
				$result[] = array(
					'label' => $producto->producto_nombre, // lo que se muestra en la lista
					'value' => $producto->producto_nombre, // lo que queda en el input
					'id' => $producto->id_producto,        // valor real que mandamos en hidden
				);
			}

			echo CJSON::encode($result);
			Yii::app()->end();
		}
	}



	/**
	 * Pantalla para ver los precios de venta
	 */
	public function actionPreciosdeventa()
	{
		/*if(!$this->VerificarAcceso(21)){
																												  // redireccionamos a inicio, no tiene acceso al modulo
																												  Yii::app()->user->setflash('danger','No cuenta con el privilegio para acceder al modulo.');
																												  $this->layout = 'noautorizado';
																											  }*/
		$sucursales = Sucursales::model()->findAll(array('condition' => 'eliminado = 0'));
		$listacategorias = $this->Listacategorias(0);
		if (!count($listacategorias) > 0) {
			$listacategorias = array();
		}
		$tipo = (isset($_GET['tipo']) && $_GET['tipo'] != '') ? $_GET['tipo'] : 0;

		if (isset($_GET['subcategoria']) && $_GET['subcategoria'] != '' && $_GET['subcategoria'] != '0') {
			// agregamos la categoria al producto
			$categoria = $_GET['subcategoria'];
		} elseif (isset($_GET['categoria']) && $_GET['categoria'] != '' && $_GET['categoria'] != '0') {
			$categoria = $_GET['categoria'];
		} else {
			$categoria = (isset($_GET['familia'])) ? $_GET['familia'] : 0;
		}
		/*echo $categoria;*/

		$cadena = $this->Cadenacategorias($categoria);

		/*print_r($cadena);*/

		$familias = Categorias::model()->findAll(
			array(
				'condition' => 'eliminado = 0 and  id_categoria_padre = 0'
			)
		);

		$categorias = Categorias::model()->findAll(
			array(
				'condition' => 'eliminado = 0 and  id_categoria_padre = :id_familia',
				'params' => array(':id_familia' => $cadena['Familia_id'])
			)
		);
		$subcategorias = Categorias::model()->findAll(
			array(
				'condition' => 'eliminado = 0 and  id_categoria_padre = :id_familia',
				'params' => array(':id_familia' => $cadena['Categoria_id'])
			)
		);


		$sucursal = (isset($_GET['sucursal']) && $_GET['sucursal'] != '') ? $_GET['sucursal'] : Yii::app()->user->getstate('id_sucursal');

		$id_lista_precios = (isset($_GET['id_listaprecios']) && $_GET['id_listaprecios'] != '') ? $_GET['id_listaprecios'] : 0;

		$listasprecios = ListaPrecios::model()->findAll(
			array(
				'condition' => 'eliminado = 0',
			)
		);


		$categorinas_in = $this->ObtenerHijosCat($categoria);

		/*print_r($categorinas_in);*/



		if ($id_lista_precios != 0) {
			$lista = ProductosPrecios::model()->with(
				array(
					'idProducto' => array('condition' => 'idProducto.eliminado=0 and idProducto.id_categoria in(' . $categorinas_in . ') and idProducto.estatus = 1'),
				)
			)->findAll(
				array(
					'condition' => 't.eliminado = 0 and t.id_lista_precios=:id_lista',
					'params' => array(':id_lista' => $id_lista_precios),
				)
			);
		} else {
			$lista = ProductosPrecios::model()->with(
				array(
					'idProducto' => array('condition' => 'idProducto.eliminado=0 and idProducto.id_categoria in(' . $categorinas_in . ') and idProducto.estatus = 1'),
				)
			)->findAll(
				array(
					'condition' => 't.eliminado = 0',
				)
			);
		}

		// renderizamos
		$this->render(
			'preciosdeventa',
			array(
				'lista' => $lista,
				'sucursales' => $sucursales,
				'listacategorias' => $listacategorias,
				'categoria' => $categoria,
				'sucursal' => $sucursal,
				'tipo' => $tipo,
				'cadena' => $cadena,
				'familias' => $familias,
				'categorias' => $categorias,
				'subcategorias' => $subcategorias,
				'id_lista_precios' => $id_lista_precios,
				'listasprecios' => $listasprecios
			)
		);
	}


	/**
													   PANTALLA PARA UN MOVIMIENTO DE AJUSTE
	 **/
	public function actionMovimientoajuste()
	{
		$VerificarAcceso = $this->VerificarAcceso(24, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		/*if(!$this->VerificarAcceso(13)){
																												  // redireccionamos a inicio, no tiene acceso al modulo
																												  Yii::app()->user->setflash('danger','No cuenta con el privilegio para acceder al modulo.');
																												  $this->layout = 'noautorizado';
																											  }*/
		if (isset($_POST['SucursalesMovimientos'])) {

			/*echo '<pre>';
																																				   print_r($_POST);
																																				   echo '</pre>';
																																				   exit;*/
			#$control_por_serie = $_POST['control_por_serie'];

			// obtenemos el tipo, almacen, ubicacion y producto
			$tipo = $_POST['SucursalesMovimientos']['tipo'];
			$id_sucursal = $_POST['SucursalesMovimientos']['id_sucursal'];
			$id_producto = $_POST['SucursalesMovimientos']['id_producto'];
			$cantidad_stock = $_POST['SucursalesMovimientos']['cantidad_mov'];
			$eliminado = $_POST['SucursalesMovimientos']['eliminado'];
			$comentarios = $_POST['SucursalesMovimientos']['comentarios'];
			$id_usuario = Yii::app()->user->id;
			$tipo_identificador = 0;
			if (isset($_POST['SucursalesMovimientos']['tipo_identificador'])) {
				$tipo_identificador = $_POST['SucursalesMovimientos']['tipo_identificador'];
			}
			$id_identificador = 0;
			if (isset($_POST['SucursalesMovimientos']['id_identificador'])) {
				$id_identificador = $_POST['SucursalesMovimientos']['id_identificador'];
			}
			$folio_rsi = 0;
			if (isset($_POST['SucursalesMovimientos']['folio_rsi'])) {
				$folio_rsi = $_POST['SucursalesMovimientos']['folio_rsi'];
			}


			/*echo $folio_rsi;
																																									 exit;*/
			$model = new SucursalesProductos();
			$db = $model->getDbConnection();
			#print_r($db->beginTransaction());exit;
			#$transaction = Yii::app()->db->beginTransaction();
			$transaction = $db->beginTransaction();
			try {

				if ($tipo == '1') { //Entrada

					// verificamos si existe en ese almacen ubicacion
					$almacenprod = SucursalesProductos::model()->find('id_sucursal=' . $id_sucursal . ' and id_producto=' . $id_producto);
					$cantidad_stock_antes = 0;
					$cantidad_stock_final = $cantidad_stock;
					if (empty($almacenprod)) {
						// quiere decir que no existe, insertamos
						$almacenprod = new SucursalesProductos;
						$almacenprod->id_sucursal = $id_sucursal;
						$almacenprod->id_producto = $id_producto;
						$almacenprod->cantidad_stock = $cantidad_stock;
					} else {
						$cantidad_stock_antes = $almacenprod->cantidad_stock;
						$cantidad_stock_final = $almacenprod->cantidad_stock + $cantidad_stock;
						// actualizamos la cantidad
						$almacenprod->cantidad_stock = $cantidad_stock_final;
					}
					// salvamos
					if (!$almacenprod->save()) {
						throw new Exception("No se  guardo la actualización.");
					}

					// registramos el movimiento
					$movimiento = new SucursalesMovimientos;
					$movimiento->id_sucursal = $id_sucursal;
					$movimiento->id_producto = $id_producto;
					$movimiento->tipo = $tipo;
					$movimiento->tipo_identificador = 3;
					$movimiento->id_identificador = $id_identificador;
					$movimiento->cantidad_stock_antes = $cantidad_stock_antes;
					$movimiento->cantidad_mov = $cantidad_stock;
					$movimiento->cantidad_stock_final = $cantidad_stock_final;
					$movimiento->fecha_movimiento = date('Y-m-d H:i:s');
					$movimiento->id_usuario = $id_usuario;
					$movimiento->eliminado = $eliminado;
					$movimiento->comentarios = $comentarios;
					$movimiento->folio_rsi = $folio_rsi;
					if (!$movimiento->save()) {
						throw new Exception("No se  guardo la actualización del movimiento.");
					}
				} elseif ($tipo == '2') {
					#echo 'ak';exit;
					// verificamos si existe en ese almacen ubicacion
					$almacenprod = SucursalesProductos::model()->find('id_sucursal=' . $id_sucursal . ' and id_producto=' . $id_producto);
					$cantidad_stock_antes = 0;
					$cantidad_stock_final = $cantidad_stock;
					if (empty($almacenprod)) {
						// quiere decir que no existe, insertamos
						$almacenprod = new SucursalesProductos;
						$almacenprod->id_sucursal = $id_sucursal;
						$almacenprod->id_producto = $id_producto;
						$almacenprod->cantidad_stock = $cantidad_stock;
					} else {
						$cantidad_stock_antes = $almacenprod->cantidad_stock;
						$cantidad_stock_final = $almacenprod->cantidad_stock - $cantidad_stock;
						// actualizamos la cantidad
						$almacenprod->cantidad_stock = $cantidad_stock_final;
					}

					if ($almacenprod->cantidad_stock < 0) {
						throw new Exception("Stock insuficiente.");
					}
					// salvamos
					if (!$almacenprod->save()) {
						throw new Exception("No se  guardo la actualización.");
					}
					// registramos el movimiento
					$movimiento = new SucursalesMovimientos;
					$movimiento->id_sucursal = $id_sucursal;
					$movimiento->id_producto = $id_producto;
					$movimiento->tipo = $tipo;
					$movimiento->tipo_identificador = 22;
					$movimiento->id_identificador = $id_identificador;
					$movimiento->cantidad_stock_antes = $cantidad_stock_antes;
					$movimiento->cantidad_mov = $cantidad_stock;
					$movimiento->cantidad_stock_final = $cantidad_stock_final;
					$movimiento->fecha_movimiento = date('Y-m-d H:i:s');
					$movimiento->id_usuario = $id_usuario;
					$movimiento->eliminado = $eliminado;
					$movimiento->comentarios = $comentarios;
					$movimiento->folio_rsi = 'salida';

					if (!$movimiento->save()) {
						throw new Exception("No se  guardo la actualización del movimiento.");
					}

					// regresamos exito
					Yii::app()->user->setFlash('success', 'Movimiento guardado con exito, stock del producto <b>' . $movimiento['idProducto']['producto_nombre'] . ': ' . $cantidad_stock_final . '</b>');
				}

				$transaction->commit();
				// regresamos exito
				Yii::app()->user->setFlash('success', 'Movimiento guardado con exito, stock del producto <b>' . $movimiento['idProducto']['producto_nombre'] . ': ' . $cantidad_stock_final . '</b>');
			} catch (Exception $e) {
				$transaction->rollBack();
				Yii::app()->user->setFlash('danger', $e->getMessage());
			}
			// Redireccionamos de la pagina de donde viene
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
			exit;
		}
		$agregarmov = new SucursalesMovimientos();
		$agregarmov->id_sucursal = Yii::app()->user->getstate('id_sucursal');
		$sucursales = CHtml::listData(Sucursales::model()->findAll('eliminado = 0 and estatus = 1'), 'id', 'nombre');
		$this->render(
			'movimientoajuste',
			array(
				'agregarmov' => $agregarmov,
				'sucursales' => $sucursales
			)
		);
	}
	/**
	 * Pantalla para ver los movimientos
	 */
	public function actionMovimientos()
	{
		$VerificarAcceso = $this->VerificarAcceso(24, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		/*if(!$this->VerificarAcceso(23)){
																												  // redireccionamos a inicio, no tiene acceso al modulo
																												  Yii::app()->user->setflash('danger','No cuenta con el privilegio para acceder al modulo.');
																												  $this->layout = 'noautorizado';
																											  }*/
		ini_set('memory_limit', '2048M');
		$sucursales = Sucursales::model()->findAll(array('condition' => 'eliminado = 0 and estatus = 1'));
		$listacategorias = $this->Listacategorias(0);
		if (!empty($listacategorias)) {

			if (!count($listacategorias) > 0) {
				$listacategorias = array();
			}
		}
		if (isset($_GET['subcategoria']) && $_GET['subcategoria'] != '' && $_GET['subcategoria'] != '0') {
			// agregamos la categoria al producto
			$categoria = $_GET['subcategoria'];
		} elseif (isset($_GET['categoria']) && $_GET['categoria'] != '' && $_GET['categoria'] != '0') {
			$categoria = $_GET['categoria'];
		} else {
			$categoria = (isset($_GET['familia'])) ? $_GET['familia'] : 0;
		}
		$fecha_desde = (isset($_GET['fecha_desde'])) ? $_GET['fecha_desde'] : $this->_data_first_month_day();
		$fecha_hasta = (isset($_GET['fecha_hasta'])) ? $_GET['fecha_hasta'] : $this->_data_last_month_day();

		#$fecha_desde = (isset($_GET['fecha_desde']))?$_GET['fecha_desde']:date('Y-m-d');
		#fecha_hasta = (isset($_GET['fecha_hasta']))?$_GET['fecha_hasta']:date('Y-m-d');


		/*echo $categoria;*/

		$cadena = $this->Cadenacategorias($categoria);

		$familias = Categorias::model()->findAll(
			array(
				'condition' => 'eliminado = 0 and id_categoria_padre = 0'
			)
		);

		/*$categorias = Categorias::model()->findAll(array(
																												  'condition'=>'eliminado = 0 and id_categoria_padre = :id_familia',
																												  'params'=>array(':id_familia'=>$cadena['Familia_id'])
																											  ));
																											  $subcategorias = Categorias::model()->findAll(array(
																												  'condition'=>'eliminado = 0 and id_categoria_padre = :id_familia',
																												  'params'=>array(':id_familia'=>$cadena['Categoria_id'])
																											  ));*/

		// Obtenemos las categorias 
		$categorias = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=1');
		$listacategorias = CHtml::listData($categorias, 'id_catalogo_recurrente', 'nombre');

		// Obtenemos las sub categorias
		$subcategorias = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=15');

		$listasubcategorias = CHtml::listData($subcategorias, 'id_catalogo_recurrente', 'nombre');

		$array_cat = '';
		foreach ($listacategorias as $key => $value) {

			$array_cat .= $key . ',';
		}

		$array_cat = rtrim($array_cat, ',');


		$array_subcat = '';
		foreach ($listasubcategorias as $key2 => $value2) {
			$array_subcat .= $key2 . ',';
		}

		$array_subcat = rtrim($array_subcat, ',');


		//print_r($listasubcategorias);exit;
		$sucursal = (isset($_GET['sucursal']) && $_GET['sucursal'] != '') ? $_GET['sucursal'] : Yii::app()->user->getstate('id_sucursal');
		#$categorinas_in = $this->ObtenerHijosCat($categorias);
		$tipomov = (isset($_GET['tipomov']) && $_GET['tipomov'] != '') ? $_GET['tipomov'] : 2;

		$condition = 'date(fecha_movimiento) between :fecha_desde and :fecha_hasta';
		$params = array(':fecha_desde' => $fecha_desde . ' 00:00:00', ':fecha_hasta' => $fecha_hasta . ' 23:59:59');

		if ($tipomov != 0) {
			$condition .= ' and t.tipo = :tipo';
			$params[':tipo'] = $tipomov;
		}
		$lista = SucursalesMovimientos::model()->with(
			'idProducto',
			'idSucursal'
		)->findAll(
			array(
				'condition' => $condition,
				'params' => $params
			)
		);

		/*if($tipomov==0){
																												  $lista = SucursalesMovimientos::model()->with(array(
																													  'idProducto'=>array('condition'=>'idProducto.eliminado=0 and idProducto.id_categoria in('.$array_cat.')'),
																													  'idSucursal'=>array('condition'=>'idSucursal.eliminado=0 and idSucursal.id=:id_suc','params'=>array(':id_suc'=>$sucursal))
																												  ))->findAll(array(
																													  'condition'=>'date(fecha_movimiento) between :fecha_desde and :fecha_hasta',
																													  'params'=>array(':fecha_desde'=>$fecha_desde.' 00:00:00',':fecha_hasta'=>$fecha_hasta.' 23:59:59')
																												  ));
																												  
																											  }else{

																												  $lista = SucursalesMovimientos::model()->with(array(
																													  'idProducto'=>array('condition'=>'idProducto.eliminado=0 and idProducto.id_categoria in('.$array_cat.')'),
																													  'idSucursal'=>array('condition'=>'idSucursal.eliminado=0 and idSucursal.id=:id_suc','params'=>array(':id_suc'=>$sucursal))
																												  ))->findAll(array(
																													  'condition'=>'t.tipo=:tipo and date(fecha_movimiento) between :fecha_desde and :fecha_hasta',
																													  'params'=>array(':tipo'=>$tipomov,':fecha_desde'=>$fecha_desde.' 00:00:00',':fecha_hasta'=>$fecha_hasta.' 23:59:59')
																												  ));

																												  $lista = SucursalesMovimientos::model()->with('idProducto','idSucursal'
																												  )->findAll(array(
																													  'condition'=>'t.tipo=:tipo and date(fecha_movimiento) between :fecha_desde and :fecha_hasta',
																													  'params'=>array(':tipo'=>$tipomov,':fecha_desde'=>$fecha_desde.' 00:00:00',':fecha_hasta'=>$fecha_hasta.' 23:59:59')
																												  ));
																												  
																											  }*/



		// renderizamos
		$this->render(
			'movimientos',
			array(
				'lista' => $lista,
				'sucursales' => $sucursales,
				'listacategorias' => $listacategorias,
				'categoria' => $categoria,
				'sucursal' => $sucursal,
				'tipomov' => $tipomov,
				'cadena' => $cadena,
				'familias' => $familias,
				'categorias' => $categorias,
				'subcategorias' => $subcategorias,
				'fecha_desde' => $fecha_desde,
				'fecha_hasta' => $fecha_hasta,
				'listacategorias' => $listacategorias,
				'listasubcategorias' => $listasubcategorias
			)
		);
	}

	/**
	 * metodo js para obtener sl tock
	 */
	public function actionObtenerstock()
	{
		#print_r($_POST);exit;
		// obtenemos el id del almacen
		$id_producto = $_POST['id_producto'];
		$id_sucursal = $_POST['id_sucursal'];

		$productostock = SucursalesProductos::model()->find(
			array(
				'condition' => 'id_sucursal = :id_sucursal and id_producto =:id_producto  and cantidad_stock > 0',
				'params' => array(':id_sucursal' => $id_sucursal, ':id_producto' => $id_producto),
			)
		);
		// regresamos las series tambien
		$productostockseries = SucursalesProductosSeries::model()->findall(
			array(
				'condition' => 'id_sucursal = :id_sucursal and id_producto =:id_producto  and cantidad_stock > 0',
				'params' => array(':id_sucursal' => $id_sucursal, ':id_producto' => $id_producto),
			)
		);

		if (!empty($productostock)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'stock' => $productostock['cantidad_stock'],
					'series' => $productostockseries,
					'message' => 'El stock del producto es' . $productostock['cantidad_stock']
				)
			);
		} else {
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'El producto no existe en la sucursal, sin stock.',
					'series' => $productostockseries,
					'stock' => 0,
				)
			);
		}
	}
	/**
													   PROCESO PARA RECIBIR UNA PARTIDA DE LA ORDEN DE COMPRA
	 **/
	public function actionRecibirpartidaoc()
	{
		$cantidadarecibir = $_POST['cantidadarecibir'];
		$id = $_POST['id'];
		$id_oc = $_POST['id_oc'];
		$ordencompra = OrdenesCompra::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (4,5)',
				'params' => array(':id' => $id_oc)
			)
		);
		if (empty($ordencompra)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Orden de Compra no encontrada',
				)
			);
			exit;
		}
		// partida
		$partida = OrdenesCompraDetalles::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id)
			)
		);
		if (empty($partida)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Partida no encontrada',
				)
			);
			exit;
		}
		// verificamos que la cantidad no sea mayor a la cantidad de la oc
		if ($cantidadarecibir > $partida['cantidad_pendiente']) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Cantidad mayor a la pendiente',
				)
			);
			exit;
		}
		// insertamos en movimientos y actualizamos el producto stock
		$id_sucursal = $ordencompra['id_sucursal'];
		$id_producto = $partida['id_producto'];
		// verificamos si existe en ese almacen ubicacion
		$almacenprod = SucursalesProductos::model()->find('id_sucursal=' . $id_sucursal . ' and id_producto=' . $id_producto);
		$cantidad_stock_antes = 0;
		$cantidad_stock_final = $cantidadarecibir;
		if (empty($almacenprod)) {
			// quiere decir que no existe, insertamos
			$almacenprod = new SucursalesProductos;
			$almacenprod->id_sucursal = $id_sucursal;
			$almacenprod->id_producto = $id_producto;
			$almacenprod->cantidad_stock = $cantidad_stock_final;
		} else {
			$cantidad_stock_antes = $almacenprod->cantidad_stock;
			$cantidad_stock_final = $almacenprod->cantidad_stock + $cantidad_stock_final;
			// actualizamos la cantidad
			$almacenprod->cantidad_stock = $cantidad_stock_final;
		}
		// salvamos
		if (!$almacenprod->save()) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se pudo guardar la actualizacion del stock',
				)
			);
			exit;
		}
		// registramos el movimiento
		$movimiento = new SucursalesMovimientos;
		$movimiento->id_sucursal = $id_sucursal;
		$movimiento->id_producto = $id_producto;
		$movimiento->tipo = 1; // entrada
		$movimiento->tipo_identificador = 1;
		$movimiento->id_identificador = $ordencompra['id'];
		$movimiento->cantidad_stock_antes = $cantidad_stock_antes;
		$movimiento->cantidad_mov = $cantidadarecibir;
		$movimiento->cantidad_stock_final = $cantidad_stock_final;
		$movimiento->fecha_movimiento = date('Y-m-d H:i:s');
		$movimiento->id_usuario = Yii::app()->user->id;
		$movimiento->eliminado = 0;
		$movimiento->comentarios = 'Movimiento de entrada de OC';
		if (!$movimiento->save()) {
			print_r($movimiento->geterrors());
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se pudo guardar el movimiento',
				)
			);
			exit;
		}
		// actualizamos la partida
		$partida->cantidad_recibida = $partida->cantidad_recibida + $cantidadarecibir;
		$partida->cantidad_pendiente = $partida->cantidad_pendiente - $cantidadarecibir;
		if (!$partida->save()) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se pudo guardar la actualización de la partida',
				)
			);
			exit;
		}
		// actualizamos las partidas de sucursales productos campo de cantidad por recibir
		$almacenprod->cantidad_por_recibir = $almacenprod->cantidad_por_recibir - $cantidadarecibir;
		if (!$almacenprod->save()) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se pudo guardar la actualización de la partida en el stock',
				)
			);
			exit;
		}
		// regresamos la respuesta
		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'message' => 'Movimiento registrado con exito',
				'cantidadpendiente' => number_format($partida->cantidad_pendiente, 2),
				'cantidadrecibida' => number_format($partida->cantidad_recibida, 2)
			)
		);
	}
	/**
													   PROCESO PARA ENVIAR UNA PARTIDA DE TRANSFERENCIA
	 **/
	public function actionSalidapartidatc()
	{
		$cantidadporsalir = $_POST['cantidadporsalir'];
		$id = $_POST['id'];
		$id_tx = $_POST['id_tx'];
		$transferencia = Transferencias::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (2)',
				'params' => array(':id' => $id_tx)
			)
		);
		if (empty($transferencia)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Transferencia no encontrada',
				)
			);
			exit;
		}
		// partida
		$partida = TransferenciaDetalles::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id)
			)
		);
		if (empty($partida)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Partida no encontrada',
				)
			);
			exit;
		}
		// verificamos que la cantidad por salir no sea mayor a la que ya salio
		if ($cantidadporsalir > $partida['cantidad_por_salir']) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Cantidad mayor a la pendiente',
				)
			);
			exit;
		}
		// insertamos en movimientos y actualizamos el producto stock
		$id_sucursal_origen = $transferencia['id_sucursal_origen'];
		$id_producto = $partida['id_producto'];
		// validamos que ese producto cuente con stock para salir
		$almacenprod = SucursalesProductos::model()->find(
			array(
				'condition' => 'id_sucursal=:id_sucursal_origen and id_producto=:id_producto and cantidad_stock > :cantidadporsalir',
				'params' => array(':id_sucursal_origen' => $id_sucursal_origen, ':id_producto' => $id_producto, ':cantidadporsalir' => $cantidadporsalir),
			)
		);
		if (empty($almacenprod)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'El producto no cuenta con stock suficiente.',
				)
			);
			exit;
		}
		$cantidad_stock_antes = $almacenprod['cantidad_stock'];
		$cantidad_stock_final = $almacenprod['cantidad_stock'] - $cantidadporsalir;
		// registramos el movimiento
		$movimiento = new SucursalesMovimientos;
		$movimiento->id_sucursal = $id_sucursal_origen;
		$movimiento->id_producto = $id_producto;
		$movimiento->tipo = 2; // salida
		$movimiento->tipo_identificador = 21;
		$movimiento->id_identificador = $transferencia['id'];
		$movimiento->cantidad_stock_antes = $cantidad_stock_antes;
		$movimiento->cantidad_mov = $cantidadporsalir;
		$movimiento->cantidad_stock_final = $cantidad_stock_final;
		$movimiento->fecha_movimiento = date('Y-m-d H:i:s');
		$movimiento->id_usuario = Yii::app()->user->id;
		$movimiento->eliminado = 0;
		$movimiento->comentarios = 'Movimiento de salida de TRX';
		if (!$movimiento->save()) {
			print_r($movimiento->geterrors());
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se pudo guardar el movimiento',
				)
			);
			exit;
		}
		// actualizamos la partida
		$partida->cantidad_salida = $partida->cantidad_salida + $cantidadporsalir;
		$partida->cantidad_por_salir = $partida->cantidad_por_salir - $cantidadporsalir;
		if (!$partida->save()) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se pudo guardar la actualización de la partida',
				)
			);
			exit;
		}
		// actualizamos las partidas de sucursales productos campo de cantidad por recibir
		$almacenprod->cantidad_stock = $almacenprod->cantidad_stock - $cantidadporsalir;
		$almacenprod->cantidad_por_enviar = $almacenprod->cantidad_por_enviar + $cantidadporsalir;
		if (!$almacenprod->save()) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se pudo guardar la actualización de la partida en el stock',
				)
			);
			exit;
		}
		// regresamos la respuesta
		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'message' => 'Movimiento registrado con exito',
				'cantidad_salida' => number_format($partida->cantidad_salida, 2),
				'cantidad_por_salir' => number_format($partida->cantidad_por_salir, 2),
				'cantidad_stock_actual' => number_format($almacenprod->cantidad_stock, 2),
			)
		);
	}
	public function actionRecibirpartidatr()
	{
		$cantidadarecibir = $_POST['cantidadarecibir'];
		$id = $_POST['id'];
		$id_tx = $_POST['id_tr'];
		$transferencia = Transferencias::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (3)',
				'params' => array(':id' => $id_tx)
			)
		);
		if (empty($transferencia)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Transferencia no encontrada',
				)
			);
			exit;
		}
		// partida
		$partida = TransferenciaDetalles::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id)
			)
		);
		if (empty($partida)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Partida no encontrada',
				)
			);
			exit;
		}
		// verificamos que la cantidad por entrar no sea mayor a la cantidad de la transferencia
		if ($cantidadarecibir > $partida['cantidad_pendiente']) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Cantidad mayor a la pendiente',
				)
			);
			exit;
		}
		// insertamos en movimientos y actualizamos el producto stock
		$id_sucursal = $transferencia['id_sucursal_destino'];
		$id_producto = $partida['id_producto'];
		// verificamos si existe en ese almacen ubicacion
		$almacenprod = SucursalesProductos::model()->find('id_sucursal=' . $id_sucursal . ' and id_producto=' . $id_producto);
		$cantidad_stock_antes = 0;
		$cantidad_stock_final = $cantidadarecibir;
		if (empty($almacenprod)) {
			// quiere decir que no existe, insertamos
			$almacenprod = new SucursalesProductos;
			$almacenprod->id_sucursal = $id_sucursal;
			$almacenprod->id_producto = $id_producto;
			$almacenprod->cantidad_stock = $cantidad_stock_final;
		} else {
			$cantidad_stock_antes = $almacenprod->cantidad_stock;
			$cantidad_stock_final = $almacenprod->cantidad_stock + $cantidad_stock_final;
			// actualizamos la cantidad
			$almacenprod->cantidad_stock = $cantidad_stock_final;
		}
		// salvamos
		if (!$almacenprod->save()) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se pudo guardar la actualizacion del stock',
				)
			);
			exit;
		}
		$movimiento = new SucursalesMovimientos;
		$movimiento->id_sucursal = $id_sucursal;
		$movimiento->id_producto = $id_producto;
		$movimiento->tipo = 1; // entrada
		$movimiento->tipo_identificador = 2;
		$movimiento->id_identificador = $transferencia['id'];
		$movimiento->cantidad_stock_antes = $cantidad_stock_antes;
		$movimiento->cantidad_mov = $cantidadarecibir;
		$movimiento->cantidad_stock_final = $cantidad_stock_final;
		$movimiento->fecha_movimiento = date('Y-m-d H:i:s');
		$movimiento->id_usuario = Yii::app()->user->id;
		$movimiento->eliminado = 0;
		$movimiento->comentarios = 'Movimiento de entrada de TRX';
		if (!$movimiento->save()) {
			print_r($movimiento->geterrors());
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se pudo guardar el movimiento',
				)
			);
			exit;
		}
		// actualizamos la partida
		$partida->cantidad_recibida = $partida->cantidad_recibida + $cantidadarecibir;
		$partida->cantidad_pendiente = $partida->cantidad_pendiente - $cantidadarecibir;
		if (!$partida->save()) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se pudo guardar la actualización de la partida',
				)
			);
			exit;
		}
		// actualizamos las partidas de sucursales productos campo de cantidad por recibir
		$almacenprod->cantidad_por_recibir = $almacenprod->cantidad_por_recibir - $cantidadarecibir;
		if (!$almacenprod->save()) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se pudo guardar la actualización de la partida en el stock',
				)
			);
			exit;
		}
		// regresamos la respuesta
		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'message' => 'Movimiento registrado con exito',
				'cantidadpendiente' => number_format($partida->cantidad_pendiente, 2),
				'cantidadrecibida' => number_format($partida->cantidad_recibida, 2)
			)
		);
	}

	/**
													   METODO QUE REGRESA UNA TABLA CON LA DISPONIBILIDAD DEL PRODUCTO
	 **/
	public function actionDisponibilidadajax()
	{
		$id_producto = $_POST['id_producto'];
		// obtenemos las sucursales en donde existe
		$sucursales = SucursalesProductos::model()->with('idSucursal')->findAll(
			array(
				'condition' => 't.id_producto = :id and idSucursal.eliminado = 0 and idSucursal.estatus = 1',
				'params' => array(':id' => $id_producto)
			)
		);
		//
		/*
																												  <th>Sucursal</th>
																												  <th>Producto</th>
																												  <th>Stock</th>
																												  <th>Por recibir</th>
																												  <th>Por enviar</th>
																												  <th>Ultima compra</th>
																												  <th>Ultima venta</th>
																												  <th>Minimo</th>
																												  <th>Maximo</th>
																												  <th>Reorden</th>
																												  <th></th>
																											  */
		$html = '';
		foreach ($sucursales as $rows) {
			$html .= '<tr>';
			$html .= '<td>' . $rows['idSucursal']['nombre'] . '</td>';
			$html .= '<td>' . $rows['idProducto']['clave'] . '</td>';
			$html .= '<td>' . $rows['cantidad_stock'] . '</td>';
			if (!isset($_POST['modulo'])) {
				$html .= '<td>' . $rows['cantidad_por_recibir'] . '</td>';
				$html .= '<td>' . $rows['cantidad_por_enviar'] . '</td>';
				$html .= '<td>' . $rows['fecha_ultima_compra'] . '</td>';
				$html .= '<td>' . $rows['fecha_ultima_venta'] . '</td>';
				$html .= '<td>' . $rows['minimo'] . '</td>';
				$html .= '<td>' . $rows['maximo'] . '</td>';
				$html .= '<td>' . $rows['reorden'] . '</td>';
				$html .= '<td></td>';
			}
			$html .= '</tr>';
		}
		// regresamos la respuesta
		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'message' => 'Disponibilidad encontrada',
				'html' => $html
			)
		);
	}

	/*
													   PROCESO PARA ELIMINAR UN PRECIO PRODUCTO
													   ***/
	public function actionEliminarprecioproducto()
	{
		/*if(!$this->VerificarAcceso(21)){
																												  // redireccionamos a inicio, no tiene acceso al modulo
																												  Yii::app()->user->setflash('danger','No cuenta con el privilegio para acceder al modulo.');
																												  $this->layout = 'noautorizado';
																											  }*/

		//
		$id_sucursal = Yii::app()->user->getstate('id_sucursal');;
		$id_producto = $_GET['id_producto'];
		$id = $_GET['id'];
		// obtenemos los datos
		$producto = ProductosPrecios::model()->find(
			array(
				'condition' => 'eliminado = 0 and id_sucursal = :id_sucursal and id = :id and id_producto =:id_producto',
				'params' => array(':id_sucursal' => $id_sucursal, ':id' => $id, 'id_producto' => $id_producto)
			)
		);

		if (empty($producto)) {
			Yii::app()->user->setflash('danger', 'Producto no encontrado.');
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
		}
		// eliminamos
		$producto->eliminado = 1;
		if ($producto->save()) {
			Yii::app()->user->setflash('success', 'Eliminado con exito.');
		} else {
			Yii::app()->user->setflash('danger', 'Error al guardar la eliminación');
		}
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	/**
													   PROCESO QUE REGRESA UN HTML DE LA TABLA CON LOS MOVIMIENTOS DE SERIE DE UN MOVIMIENTO
	 **/
	public function actionMovimientosserie()
	{
		$id = $_POST['id'];
		$movimientos = SucursalesMovimientosSeries::model()->findAll('id_movimiento = ' . $id);

		if (empty($movimientos)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Sin movimientos de serie y lote encontrados',
					'html' => $html
				)
			);
		} else {
			// generamos un html del tbody
			$html = '';
			foreach ($movimientos as $rows) {
				$html .= '<tr>';
				$html .= '<td>' . $rows['idSucursal']['nombre'] . '</td>';
				$html .= '<td>' . $rows['idProducto']['nombre'] . '<br>' . $rows['idProducto']['clave'] . '</td>';
				$html .= '<td>' . $rows['idSerie']['serie'] . ' - ' . $rows['idSerie']['lote'] . '</td>';
				$html .= '<td>' . $rows['cantidad_stock_antes'] . '</td>';
				$html .= '<td>' . $rows['cantidad_movimiento'] . '</td>';
				$html .= '<td>' . $rows['cantidad_stock_final'] . '</td>';
				$html .= '<td>' . $rows['fecha_movimiento'] . '</td>';
				$html .= '</tr>';
			}
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Movimientos de serie y lote encontrados',
					'html' => $html
				)
			);
		}
	}


	/**
													   Proceso que regresa el stock de la serie y lote de un producto sucursal
	 ***/
	public function actionStockserie()
	{
		$id_producto = $_POST['id_producto'];
		$id_sucursal = $_POST['id_sucursal'];
		$series = SucursalesProductosSeries::model()->findAll(
			array(
				'condition' => 'id_producto = :id_producto and id_sucursal =:id_sucursal and cantidad_stock > 0',
				'params' => array(':id_producto' => $id_producto, ':id_sucursal' => $id_sucursal)
			)
		);

		if (empty($series)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Sin movimientos de serie y lote encontrados',
					'html' => $html
				)
			);
		} else {
			// generamos un html del tbody
			$html = '';
			foreach ($series as $rows) {
				$html .= '<tr>';
				$html .= '<td>' . $rows['idSucursal']['nombre'] . '</td>';
				$html .= '<td>' . $rows['idProducto']['nombre'] . '<br>' . $rows['idProducto']['clave'] . '</td>';
				$html .= '<td>' . $rows['serie'] . '</td>';
				$html .= '<td>' . $rows['lote'] . '</td>';
				$html .= '<td>' . $rows['cantidad_stock'] . '</td>';
				$html .= '</tr>';
			}
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Stock de serie y lote encontrados',
					'html' => $html
				)
			);
		}
	}

	/**
	 * proceso para ajustar la cantidad separada
	 * dvb 18 11 2021
	 */
	public function actionAjustarcantidadseparada()
	{


		$sql = 'update sucursales_productos set cantidad_separada = 0';
		Yii::app()->db->createCommand($sql)->execute();
		//
		$sqlajustarcantidad = '
		SELECT td.id_producto, t.id_sucursal, sum(cantidad) as cantidad_separada 
		from ticket_detalles td

		inner join ticket t on td.id_ticket = t.id 
		WHERE t.estatus in (1,2) and td.eliminado = 0 and t.eliminado = 0 and date(t.fecha_alta) > "2021-10-05"

		group by t.id_sucursal,td.id_producto';

		$resultado = Yii::app()->db->createCommand($sqlajustarcantidad)->queryall();

		foreach ($resultado as $rows) {

			$act = SucursalesProductos::model()->find([
				'condition' => 'id_producto = :idproducto and id_sucursal =:id_sucursal',
				'params' => [':idproducto' => $rows['id_producto'], ':id_sucursal' => $rows['id_sucursal']]
			]);

			if (!empty($act)) {
				$act->cantidad_separada = $rows['cantidad_separada'];
				$act->save();
			}
		}
	}
}
