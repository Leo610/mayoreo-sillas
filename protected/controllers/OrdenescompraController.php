<?php

require_once(__DIR__ . '/../vendor/dompdf/autoload.inc.php');
// require_once(__DIR__ . '/../vendor/mpdf');
use Dompdf\Dompdf;

class OrdenescompraController extends Controller
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
	public function init()
	{
	}

	/**
	 * Pantalla para ver las ordenes de compra
	 */
	public function actionIndex()
	{
		$VerificarAcceso = $this->VerificarAcceso(24, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		// proceso para crear una orden de compra
		if (isset($_POST['OrdenesCompra'])) {
			// generamos la oc 
			$crearoc = new OrdenesCompra;
			$crearoc->id_sucursal = $_POST['OrdenesCompra']['id_sucursal'];
			$crearoc->id_proveedor = $_POST['OrdenesCompra']['id_proveedor'];
			$crearoc->id_usuario_crea = Yii::app()->user->id;
			$crearoc->fecha_alta = date('Y-m-d H:i:s');
			$crearoc->estatus = 1; // nacen como requisiciones
			$crearoc->comentarios = $_POST['OrdenesCompra']['comentarios'];
			$crearoc->eliminado = 0;
			$crearoc->total = 0;
			$crearoc->iva = 0;
			$crearoc->subtotal = 0;
			$crearoc->total_pendiente = 0;
			$crearoc->total_pagado = 0;
			$crearoc->tipo_oc = $_POST['OrdenesCompra']['tipo_oc'];
			$crearoc->id_usuario_solicita = $_POST['OrdenesCompra']['id_usuario_solicita'];
			if ($crearoc->save()) {
				// exito
				$logoc = array(
					'id_orden_compra' => $crearoc->id,
					'estatus_anterior' => '',
					'estatus_final' => $crearoc->estatus,
					'comentarios' => $crearoc->comentarios,
					'id_usuario' => $crearoc->id_usuario_crea,
					'fecha_alta' => $crearoc->fecha_alta,
					'total' => 0
				);
				$this->Insertarlogoc($logoc);
				// si seleccionaron incluir los productos con minimo, insertamos los productos de ese proveedor en el detalle de la oc
				if (isset($_POST['productosminimos']) && $_POST['productosminimos'] == 1) {
					// obtenemos los productos que surte ese proveedor
					$productos = ProductosPrecioCompra::model()->with(
						array(
							'idProducto' => array('condition' => 'idProducto.eliminado=0')
						)
					)->findAll(array('condition' => 'id_proveedor=:id_proveedor and t.eliminado = 0', 'params' => array(':id_proveedor' => $crearoc['id_proveedor'])));
					// recorremos el foreach verificando que cuenten con menos del minimo
					foreach ($productos as $rows) {
						$sucursalstock = SucursalesProductos::model()->find(
							array(
								'condition' => 'id_sucursal=:id_sucursal and id_producto = :id_producto and cantidad_stock <= reorden',
								'params' => array(':id_sucursal' => $crearoc['id_sucursal'], ':id_producto' => $rows['id_producto'])
							)
						);
						if (!empty($sucursalstock)) {
							// si existe algun producto, lo insertamos
							$agregarconcepto = new OrdenesCompraDetalles();
							$agregarconcepto->id_orden_compra = $crearoc['id'];
							$agregarconcepto->id_producto = $rows['id_producto'];
							// obtenemos la cantidad a surtir en base al maximo menos el stock
							$cantidadproducto = $sucursalstock['maximo'] - $sucursalstock['cantidad_stock'];
							// costos del producto
							$costosproducto = $this->Costocompra($rows['id_producto'], $crearoc['id_sucursal'], $crearoc['id_proveedor']);

							$agregarconcepto->unitario = $costosproducto['preciocompra'];
							$agregarconcepto->descuento = 0;
							$agregarconcepto->iva = $costosproducto['iva'];
							$agregarconcepto->cantidad = $cantidadproducto;
							$agregarconcepto->subtotal_unitario = $costosproducto['preciocompra'] * $cantidadproducto;
							$agregarconcepto->subtotal_iva = $costosproducto['iva'] * $cantidadproducto;
							$agregarconcepto->total = $costosproducto['unitarioconiva'] * $cantidadproducto;

							$agregarconcepto->cantidad_original = $cantidadproducto;
							$agregarconcepto->cantidad_recibida = 0;
							$agregarconcepto->cantidad_pendiente = $cantidadproducto;
							$agregarconcepto->eliminado = 0;
							$agregarconcepto->concepto = $rows['idProducto']['nombre'];
							if ($agregarconcepto->save()) {
								$this->Actualizartotaloc($crearoc['id']);
							}
						}
					}
				}
				Yii::app()->user->setflash('success', 'Orden de compra creada con exito #' . $crearoc['id']);
				$this->redirect(Yii::app()->createurl("ordenescompra/detalles", array('id' => $crearoc['id'])));
			} else {
				// error
				Yii::app()->user->setflash('danger', 'Ocurrio un error al generar .');
			}
			// redireccionamos la página de donde viene
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
		}
		// modelo para crear una orden de compra
		$tipodeoc = array('0' => 'NORMAL', '1' => 'URGENTE');
		$nuevaoc = new OrdenesCompra;
		$nuevaoc->id_sucursal = Yii::app()->user->getstate('id_sucursal');

		// lista de usuarios
		$usuarios = Usuarios::model()->findall(
			array(
				'condition' => 'eliminado = 0',
				'order' => 'Usuario_Nombre asc'
			)
		);

		// lista de sucursales
		$sucursales = Sucursales::model()->findall(
			array(
				'condition' => 'eliminado = 0 and estatus = 1 and bodega_principal = 1',
				'order' => 'nombre asc'
			)
		);

		// lista de proveedores
		$proveedores = Proveedores::model()->findall(
			array(
				'condition' => 'proveedor_estatus = 1',
				'order' => 'proveedor_nombre asc'
			)
		);
		$arrayproveedores = array();
		foreach ($proveedores as $rows) {
			$arrayproveedores[$rows['id_proveedor']] = $rows['proveedor_nombre'] . ' ' . $rows['proveedor_rfc'];
		}
		// variables para los filtros
		$fecha_desde = (isset($_GET['fecha_desde'])) ? $_GET['fecha_desde'] : $this->_data_last_three_month_day();
		// $fecha_desde = (isset($_GET['fecha_desde'])) ? $_GET['fecha_desde'] : $this->_data_first_month_day();
		$fecha_hasta = (isset($_GET['fecha_hasta'])) ? $_GET['fecha_hasta'] : $this->_data_last_month_day();
		$estatus = (isset($_GET['estatus'])) ? $_GET['estatus'] : '0';
		$sucursal = (isset($_GET['sucursal'])) ? $_GET['sucursal'] : '0';
		$tipos = (isset($_GET['tipos'])) ? $_GET['tipos'] : '9';

		// lista estatus
		$estatusoc = $this->EstatusOClista();
		$condition = 'eliminado = 0 and date(fecha_alta) between :fecha_desde and :fecha_hasta';
		$parametros = array(':fecha_desde' => $fecha_desde, ':fecha_hasta' => $fecha_hasta);

		if ($estatus != '0') {
			$estatus = $_GET['estatus'];
			$condition .= ' and estatus = :estatus';
			$parametros[':estatus'] = $estatus;
		}
		if ($sucursal != '0') {
			$sucursal = $_GET['sucursal'];
			$condition .= ' and id_sucursal = :sucursal';
			$parametros[':sucursal'] = $sucursal;
		}
		if ($tipos != '9') {
			$tipos = $_GET['tipos'];
			$condition .= ' and tipo_oc = :tipos';
			$parametros[':tipos'] = $tipos;
		}

		$ordenescompra = OrdenesCompra::model()->findAll(
			array(
				'condition' => $condition,
				'params' => $parametros
			)
		);
		$this->render(
			'index',
			array(
				'nuevaoc' => $nuevaoc,
				'tipodeoc' => $tipodeoc,
				'usuariosdropdown' => CHtml::listData($usuarios, 'ID_Usuario', 'Usuario_Nombre'),
				'sucursalesdropdown' => CHtml::listData($sucursales, 'id', 'nombre'),
				'proveedoresdropdown' => $arrayproveedores,
				'ordenescompra' => $ordenescompra,
				'fecha_desde' => $fecha_desde,
				'fecha_hasta' => $fecha_hasta,
				'estatusoc' => $estatusoc,
				'estatus' => $estatus,
				'sucursal' => $sucursal,
				'tipos' => $tipos,
			)
		);
	}

	/**
																																	 PANTALLA PARA VER EL DETALLE DE LA ORDEN DE COMPRA
							 **/
	public function actionDetalles()
	{
		if (!$this->VerificarAcceso(6, Yii::app()->user->id)) {
			// redireccionamos a inicio, no tiene acceso al modulo
			Yii::app()->user->setflash('danger', 'No cuenta con el privilegio para acceder al modulo.');
			$this->layout = 'noautorizado';
		}
		$id = $_GET['id'];
		if (empty($id)) {
			$this->redirect(Yii::app()->createurl('ordenescompra/index'));
		}
		$ordencompra = OrdenesCompra::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id)
			)
		);
		if (empty($ordencompra)) {
			$this->redirect(Yii::app()->createurl('ordenescompra/index'));
		}
		$this->render(
			'detalles',
			array(
				'ordencompra' => $ordencompra
			)
		);
	}
	/**
																																	 PROCESO QUE REGRESA LOS DATOS DE LA OC
																																	 CONCEPTOS, SUBTOTAL, IVA Y TOTAL
							 **/
	public function actionDatosoc()
	{
		$id = $_POST['id'];
		if (empty($id)) {
			exit;
		}
		$ordencompra = OrdenesCompra::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id)
			)
		);
		if (empty($ordencompra)) {
			exit;
		}
		// obtenemos los conceptos
		$conceptos = OrdenesCompraDetalles::model()->findAll(
			array(
				'condition' => 'id_orden_compra = :id and eliminado = 0',
				'params' => array(':id' => $id),
				'order' => 'id asc'
			)
		);
		// generamos una variable con los conceptos en formato de tabla
		$conceptostr = '';
		foreach ($conceptos as $rows) {
			$conceptostr .= '<tr id="partida' . $rows['id'] . '">';
			$conceptostr .= '<td>' . $rows['concepto'] . '</td>';
			$conceptostr .= '<td>' . $rows['idProducto']['producto_clave'] . '</td>';

			$conceptostr .= '<td> $ ' . number_format($rows['unitario'], 2) . '</td>';
			$conceptostr .= '<td> $ ' . number_format($rows['iva'], 2) . '</td>';
			$conceptostr .= '<td> $ ' . number_format($rows['unitario'] + $rows['iva'], 2) . '</td>';
			$conceptostr .= '<td style="text-align:center;">' . $rows['cantidad'] . '</td>';
			$conceptostr .= '<td> $ ' . number_format($rows['total'], 2) . '</td>';
			if ($ordencompra['estatus'] == 1 || $ordencompra['estatus'] == 2 || $ordencompra['estatus'] == 3) {
				$conceptostr .= '<td>
						<button type="button" class="btn btn-warning btn-xs" onclick="Datospartida(' . $rows['id'] . ')"><i class="fa fa-pencil" aria-hidden="true"></i></button>
						<button type="button" class="btn btn-danger btn-xs" onclick="Eliminarpartida(' . $rows['id'] . ')"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
					</td>';
			} else {
				$conceptostr .= '<td></td>';
			}
			$conceptostr .= '</tr>';
		}
		// renderizamos
		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'ordencompra' => $ordencompra,
				'message' => 'Datos encontrados con exito',
				'conceptos' => $conceptos,
				'subtotalformat' => '$ ' . number_format($ordencompra['subtotal'], 2),
				'ivaformat' => '$ ' . number_format($ordencompra['iva'], 2),
				'totalformat' => '$ ' . number_format($ordencompra['total'], 2),
				'conceptostr' => $conceptostr
			)
		);
	}
	/**
																																	 PROCESO QUE REGRESA LOS DATOS DEL PRODUCTO EN BASE AL PROVEEDOR, COSTO DE COMPRA
							 **/
	public function actionDatosproducto()
	{
		$id_sucursal = Yii::app()->user->getstate('id_sucursal');
		if (!isset($_POST['idproducto'])) {
			exit;
		}
		$idproducto = $_POST['idproducto'];
		//
		$datos = Productosprecios::model()->find(
			array(
				'condition' => 'id_producto = :id_producto ',
				'params' => array(':id_producto' => $idproducto),
			)
		);
		// renderizamos
		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'message' => 'Producto encontrado con exito',
				'datos' => $datos,
			)
		);
	}
	/**
																																	 METODO PARA INSERTAR UN PRODUCTO NUEVO EN LA ORDEN DE COMPRA
							 **/
	public function actionAgregarproductooc()
	{
		$id = $_POST['id'];
		if (empty($id)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Identificador no encontrado',
				)
			);
			exit;
		}
		$ordencompra = OrdenesCompra::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (1,2,3)',
				'params' => array(':id' => $id)
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
		$id_producto = $_POST['id_producto'];
		$unitarioproducto = $_POST['unitarioproducto'];
		$cantidadproducto = $_POST['cantidadproducto'];
		if (empty($id_producto) || empty($unitarioproducto) || empty($cantidadproducto)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se encontraron los datos del producto',
				)
			);
			exit;
		}

		// insertamos el producto en la orden de compra y obtenemos el IVA, en base a si genera iva y el campo de iva en la sucursal
		$agregarconcepto = new OrdenesCompraDetalles();
		$agregarconcepto->id_orden_compra = $id;
		$agregarconcepto->id_producto = $id_producto;
		// costos del producto
		$costosproducto = $this->Costocompra($id_producto, $ordencompra['id_sucursal'], $ordencompra['id_proveedor']);



		$agregarconcepto->unitario = $costosproducto['preciocompra'];
		$agregarconcepto->descuento = 0;
		$agregarconcepto->iva = $costosproducto['iva'];
		$agregarconcepto->cantidad = $cantidadproducto;
		$agregarconcepto->subtotal_unitario = $costosproducto['preciocompra'] * $cantidadproducto;
		$agregarconcepto->subtotal_iva = $costosproducto['iva'] * $cantidadproducto;
		$agregarconcepto->total = $costosproducto['unitarioconiva'] * $cantidadproducto;

		$agregarconcepto->cantidad_original = $cantidadproducto;
		$agregarconcepto->cantidad_recibida = 0;
		$agregarconcepto->cantidad_pendiente = $cantidadproducto;
		$agregarconcepto->eliminado = 0;
		$agregarconcepto->concepto = $agregarconcepto['idProducto']['producto_nombre'];
		if ($agregarconcepto->save()) {
			$this->Actualizartotaloc($id);
			// llamamos la funcion para actualizar el costo de la oc
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Producto agregado con exito',
				)
			);
			exit;
		}
	}
	/**
																																	 METODO PARA INSERTAR UN PRODUCTO NUEVO EN LA ORDEN DE COMPRA DE MANERA MANUAL
							 **/
	public function actionAgregarproductoocmanual()
	{
		$id = $_POST['id'];
		if (empty($id)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Identificador no encontrado',
				)
			);
			exit;
		}
		$ordencompra = OrdenesCompra::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (1,2,3)',
				'params' => array(':id' => $id)
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
		$concepto_os = $_POST['concepto_os'];
		$unitarioproducto = $_POST['unitarioproducto'];
		$cantidadproducto = $_POST['cantidadproducto'];
		if (empty($concepto_os) || empty($unitarioproducto) || empty($cantidadproducto)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se encontraron los datos del producto',
				)
			);
			exit;
		}
		// insertamos el producto en la orden de compra y obtenemos el IVA, en base a si genera iva y el campo de iva en la sucursal
		$agregarconcepto = new OrdenesCompraDetalles();
		$agregarconcepto->id_orden_compra = $id;
		$agregarconcepto->id_producto = 0;
		// costos del producto
		#$costosproducto = $this->Costocompra($datos['id_producto'],$ordencompra['id_sucursal'],$datos['id_proveedor']);
		$costosproducto = array(
			'preciocompra' => $unitarioproducto,
			'iva' => $unitarioproducto * ($ordencompra['idSucursal']['porcentaje_iva'] / 100),
			'unitarioconiva' => $unitarioproducto + ($unitarioproducto * ($ordencompra['idSucursal']['porcentaje_iva'] / 100)),
		);
		$agregarconcepto->unitario = $costosproducto['preciocompra'];
		$agregarconcepto->descuento = 0;
		$agregarconcepto->iva = $costosproducto['iva'];
		$agregarconcepto->cantidad = $cantidadproducto;
		$agregarconcepto->subtotal_unitario = $costosproducto['preciocompra'] * $cantidadproducto;
		$agregarconcepto->subtotal_iva = $costosproducto['iva'] * $cantidadproducto;
		$agregarconcepto->total = $costosproducto['unitarioconiva'] * $cantidadproducto;

		$agregarconcepto->cantidad_original = $cantidadproducto;
		$agregarconcepto->cantidad_recibida = 0;
		$agregarconcepto->cantidad_pendiente = $cantidadproducto;
		$agregarconcepto->eliminado = 0;
		$agregarconcepto->concepto = $concepto_os;
		if ($agregarconcepto->save()) {
			$this->Actualizartotaloc($id);
			// llamamos la funcion para actualizar el costo de la oc
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Producto agregado con exito',
				)
			);
			exit;
		}
	}
	/**
																																	 METODO PARA ACTUALIZAR UNA PARTIDA DE LA OC
							 **/
	public function actionActualizarproductooc()
	{
		$id = $_POST['id'];
		if (empty($id)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Identificador no encontrado',
				)
			);
			exit;
		}
		$ordencompra = OrdenesCompra::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (1,2,3)',
				'params' => array(':id' => $id)
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
		$id_partida = $_POST['id_partida'];
		$partida = OrdenesCompraDetalles::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id_partida)
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
		$id_producto = $_POST['id_producto'];
		$unitarioproducto = $_POST['unitarioproducto'];
		$cantidadproducto = $_POST['cantidadproducto'];
		if ((empty($id_producto) || empty($unitarioproducto) || empty($cantidadproducto) || empty($partida)) && $id_producto != 0) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se encontraron los datos del producto',
				)
			);
			exit;
		}
		//
		$datos = ProductosPrecioCompra::model()->find(
			array(
				'condition' => 'id_producto = :id_producto and id_proveedor = :id_proveedor and eliminado = 0',
				'params' => array(':id_producto' => $id_producto, ':id_proveedor' => $ordencompra['id_proveedor']),
			)
		);
		if (empty($datos) && $id_producto != 0) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'El producto no fue encontrado',
				)
			);
			exit;
		}

		// comprobamos si el precio fue editado, para actualizar e insertar en el log de costos de compra
		if ($datos['precio_compra'] != $unitarioproducto && $id_producto != 0) {
			// insertamos el cambio de precio y actualizamos el rpecio en el proveedor
			$precio_compra_antes = $datos->precio_compra;
			$datos->precio_compra = $unitarioproducto;
			$datos->id_usuario = Yii::app()->user->id;
			$datos->ultima_actualizacion = date('Y-m-d H:i:s');
			if ($datos->save()) {
				// insertamos en log
				$log = new ProductosPrecioCompraLog();
				$log->id_producto = $datos['id_producto'];
				$log->id_proveedor = $datos['id_proveedor'];
				$log->id_producto_precio_compra = $datos->id;
				$log->precio_compra_antes = $precio_compra_antes;
				$log->precio_compra_despues = $unitarioproducto;
				$log->fecha_alta = date('Y-m-d H:i:s');
				$log->id_usuario = Yii::app()->user->id;
				$log->comentarios = 'Actualización del precio del producto';
				$log->save();
			}
		}

		// insertamos el producto en la orden de compra y obtenemos el IVA, en base a si genera iva y el campo de iva en la sucursal
		$partida->id_orden_compra = $id;
		$partida->id_producto = $id_producto;
		// costos del producto
		if ($id_producto != 0) {
			$costosproducto = $this->Costocompra($datos['id_producto'], $ordencompra['id_sucursal'], $datos['id_proveedor']);
		} else {
			$costosproducto = array(
				'preciocompra' => $unitarioproducto,
				'iva' => $unitarioproducto * ($ordencompra['idSucursal']['porcentaje_iva'] / 100),
				'unitarioconiva' => $unitarioproducto + ($unitarioproducto * ($ordencompra['idSucursal']['porcentaje_iva'] / 100)),
			);
		}

		$partida->unitario = $costosproducto['preciocompra'];
		$partida->descuento = 0;
		$partida->iva = $costosproducto['iva'];
		$partida->cantidad = $cantidadproducto;
		$partida->subtotal_unitario = $costosproducto['preciocompra'] * $cantidadproducto;
		$partida->subtotal_iva = $costosproducto['iva'] * $cantidadproducto;
		$partida->total = $costosproducto['unitarioconiva'] * $cantidadproducto;

		$partida->cantidad_original = $cantidadproducto;
		$partida->cantidad_recibida = 0;
		$partida->cantidad_pendiente = $cantidadproducto;
		$partida->eliminado = 0;
		if ($partida->save()) {
			$this->Actualizartotaloc($id);
			// llamamos la funcion para actualizar el costo de la oc
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Partida actualizada con exito',
				)
			);
			exit;
		}
	}
	/**
																																	 METODO PARA ELIMINAR LA PARTIDA DE LA COTIZACION
							 **/
	public function actionEliminarpartida()
	{
		$id = $_POST['id'];
		$id_oc = $_POST['id_oc'];
		if (empty($id) || empty($id_oc)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Identificador no encontrado',
				)
			);
			exit;
		}
		$ordencompra = OrdenesCompra::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (1,2,3)',
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
		// eliminamos el concepto
		$concepto = OrdenesCompraDetalles::model()->find(
			array(
				'condition' => 'id=:id and id_orden_compra = :idoc and eliminado = 0',
				'params' => array(':id' => $id, ':idoc' => $id_oc)
			)
		);
		if (empty($concepto)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Concepto no encontrado',
				)
			);
			exit;
		}
		$concepto->eliminado = 1;
		if ($concepto->save()) {
			$this->Actualizartotaloc($id_oc);
			// llamamos la funcion para actualizar el costo de la oc
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Partida eliminado con exito',
				)
			);
			exit;
		}
	}
	/**
																																	 METODO PARA OBTENER LOS DATOS
							 **/
	public function actionDatospartida()
	{
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit();
		$id = $_POST['id'];
		$id_oc = $_POST['id_oc'];
		if (empty($id) || empty($id_oc)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Identificador no encontrado',
				)
			);
			exit;
		}
		$ordencompra = OrdenesCompra::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (1,2,3)',
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
		// datos del concepto
		$concepto = OrdenesCompraDetalles::model()->find(
			array(
				'condition' => 'id=:id and id_orden_compra = :idoc and eliminado = 0',
				'params' => array(':id' => $id, ':idoc' => $id_oc)
			)
		);
		if (empty($concepto)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Concepto no encontrado',
				)
			);
			exit;
		}
		// llamamos la funcion para actualizar el costo de la oc
		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'message' => 'Datos encontrados con exito',
				'concepto' => $concepto,
				'img' => $concepto['idProducto']['producto_imagen'],
				'nombre' => $concepto['idProducto']['producto_nombre'],
				'clave' => $concepto['idProducto']['producto_clave']
			)
		);
		exit;
	}

	/**		
																																	 FUNCION PARA ACTUALIZAR EL ESTATUS DE LA OC
							 **/
	public function actionActualizarestatus()
	{

		//   1= NUEVA REQUISICION
		//   2= ORDEN DE COMPRA ABIERTA
		//   3= POR AUTORIZAR
		//   4= ORDEN DE COMPRA AUTORIZADA
		//   5 = CERRADA Y LIBERADA A PAGOS
		//   9 = CANCELADA

		$id = $_POST['id_oc'];
		$estatusnuevo = $_POST['estatusnuevo'];
		if (empty($id) || empty($estatusnuevo)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Ocurrio un error con la OC'
				)
			);
			exit;
		}

		if (!$this->VerificarAcceso(7, Yii::app()->user->id) == 1 && $estatusnuevo == 1) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No cuenta con el privilegio para cambiar el estatus de la orden de compra'
				)
			);
			exit;
		}

		if (!$this->VerificarAcceso(8, Yii::app()->user->id) == 1 && $estatusnuevo == 2) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No cuenta con el privilegio para cambiar el estatus de la orden de compra'
				)
			);
			exit;
		}

		// echo "<pre>";
		// print_r($estatusnuevo);
		// echo "</pre>";
		// echo "<pre>";
		// print_r(!$this->VerificarAcceso(9, Yii::app()->user->id));
		// echo "</pre>";
		// exit();

		if (!$this->VerificarAcceso(9, Yii::app()->user->id) == 1 && $estatusnuevo == 5) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No cuenta con el privilegio para cambiar el estatus de la orden de compra'
				)
			);
			exit;
		}


		$ordencompra = OrdenesCompra::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id)
			)
		);
		if (empty($ordencompra)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Ocurrio un error con la OC'
				)
			);
			exit;
		}
		// actualizar estatus
		$ordencompra->estatus = $estatusnuevo;
		/*if($ordencompra->save())*/ {
			// si es el estatus es 4, actualizamos la tabla de productos para insertar la cantidad por recibir, solo sumamos
			if ($estatusnuevo == 4) {
				$ordencompra->save();
				$conceptos = OrdenesCompraDetalles::model()->findAll(
					array(
						'condition' => 'id_orden_compra = :idoc and eliminado = 0',
						'params' => array(':idoc' => $id)
					)
				);

				// si el id_prducto es 0 quiere decir que es concepto manual, si es asi agregamos a productos y y traemos despues ese id
				foreach ($conceptos as $rows) {
					if ($rows->id_producto == 0) {
						// si id producto es 0 entra aca
						$productos = new Productos;
						$productos->producto_nombre = $rows->concepto;
						$productos->producto_estatus = 0;
						$productos->eliminado = 0;
						$productos->producto_clave = 'AgregadoManual-' . $rows->concepto;
						if ($productos->save()) {
							$rows->id_producto = $productos->id_producto;
							$rows->save();
						} else {
							echo "<pre>";
							print_r($productos->getErrors());
							echo "</pre>";
							exit();

						}

					} else {
						continue;
					}
				}

				if (!empty($conceptos)) {
					// actualizamos la columna cantidad_por_recibir
					foreach ($conceptos as $rows) {

						$sucursalproducto = SucursalesProductos::model()->find('id_sucursal=' . $ordencompra->id_sucursal . ' and id_producto=' . $rows->id_producto);
						if (empty($sucursalproducto)) {
							// quiere decir que no existe, insertamos
							$sucursalproducto = new SucursalesProductos;
							$sucursalproducto->id_sucursal = $ordencompra->id_sucursal;
							$sucursalproducto->id_producto = $rows->id_producto;
							$sucursalproducto->cantidad_stock = 0;
							$sucursalproducto->cantidad_por_recibir = $rows->cantidad;
						} else {
							$sucursalproducto->cantidad_por_recibir = $sucursalproducto->cantidad_por_recibir + $rows->cantidad;
						}
						$sucursalproducto->save();
					}
				}
			}
			// si es el estatus es 5, actualizamos el inventario del producto, solo sumamos
			if ($estatusnuevo == 5) {
				try {
					$transaction = Yii::app()->db->beginTransaction();
					if (!$ordencompra->save()) {
						throw new Exception("Error al actualizar la orden de compra");
					}
					$conceptos = OrdenesCompraDetalles::model()->findAll(
						array(
							'condition' => 'id_orden_compra = :idoc and eliminado = 0',
							'params' => array(':idoc' => $id)
						)
					);
					/*print_r($conceptos);exit;*/
					if (!empty($conceptos)) {
						// actualizamos la columna cantidad_por_recibir
						foreach ($conceptos as $rows) {
							$cantidadarecibir = $rows['cantidad_pendiente'];
							// insertamos en movimientos y actualizamos el producto stock
							$id_sucursal = $ordencompra['id_sucursal'];
							$id_producto = $rows['id_producto'];
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
							$almacenprod->fecha_ultima_compra = date('Y-m-d H:i:s');
							if (!$almacenprod->save()) {
								throw new Exception("No se pudo guardar la actualización del stock");
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
								throw new Exception("Error al guardar el movimiento");
							}
							// actualizamos la partida
							$rows->cantidad_recibida = $rows->cantidad_recibida + $cantidadarecibir;
							$rows->cantidad_pendiente = $rows->cantidad_pendiente - $cantidadarecibir;
							if (!$rows->save()) {
								throw new Exception("No fue posible actualizar la partida de la orden de compra");
							}
							// actualizamos las partidas de sucursales productos campo de cantidad por recibir
							$almacenprod->cantidad_por_recibir = $almacenprod->cantidad_por_recibir - $cantidadarecibir;
							if (!$almacenprod->save()) {
								throw new Exception("No fue posible actualizar el stock orden de compra");
							}
							// verificamos si es con serie y lote para insertarlos
							$control_por_serie = $rows['idProducto']['control_por_serie'];
							if ($control_por_serie) {
								$serieylotesalida = array();
								parse_str($_POST['serieylote'], $serieylotesalida);
								if (count($serieylotesalida['serie']) != $cantidadarecibir) {
									throw new Exception("Las cantidades no concuerdan.");
								}

								#print_r($serieylotesalida);exit;
								//  (
								//  [serie] => Array
								//  (
								//  [0] => 456
								//  [1] => 457
								//  [2] => 458
								//  
								//  [lote] => Array
								//  (
								//  [0] => lote4
								//  [1] => lote4
								//  [2] => lote4
								//  )


								foreach ($serieylotesalida['serie'] as $key => $value) {
									$serie = $serieylotesalida['serie'][$key];
									$lote = $serieylotesalida['lote'][$key];

									// actualizamos el stock e insertamos el movimiento
									$verificarstockserie = SucursalesProductosSeries::model()->find(
										array(
											'condition' => 'id_producto=:id_producto and id_sucursal=:id_sucursal and serie=:serie and lote=:lote',
											'params' => array(':id_producto' => $id_producto, ':id_sucursal' => $id_sucursal, ':serie' => $serie, ':lote' => $lote),
										)
									);
									if (empty($verificarstockserie)) {
										// insertamos el producto
										$verificarstockserie = new SucursalesProductosSeries;
										$verificarstockserie->id_producto = $id_producto;
										$verificarstockserie->id_sucursal = $id_sucursal;
										$verificarstockserie->serie = $serie;
										$verificarstockserie->lote = $lote;
										$verificarstockserie->cantidad_stock = 0;
										if (!$verificarstockserie->save()) {
											throw new Exception("No fue posible guardar la serie.");
										}
									}
									$stockseriefinal = $verificarstockserie->cantidad_stock + 1;
									// movimiento
									$movserie = new SucursalesMovimientosSeries;
									$movserie->id_producto = $id_producto;
									$movserie->id_sucursal = $id_sucursal;
									$movserie->id_serie = $verificarstockserie['id'];
									$movserie->id_movimiento = $movimiento['id'];
									$movserie->cantidad_stock_antes = $verificarstockserie->cantidad_stock;
									$movserie->cantidad_movimiento = 1;
									$movserie->cantidad_stock_final = $stockseriefinal;
									$movserie->fecha_movimiento = date('Y-m-d H:i:s');
									$movserie->id_usuario = Yii::app()->user->id;
									$movserie->eliminado = 0;
									$movserie->comentarios = 'Movimiento de entrada de OC';
									if (!$movserie->save()) {
										throw new Exception("No fue posible guardar el movimiento de la serie.");
									}
									// actualizamos el stock
									$verificarstockserie->cantidad_stock = $stockseriefinal;
									if (!$verificarstockserie->save()) {
										throw new Exception("No fue posible actualizar el stock de la serie.");
									}
								}
							}
						}
					}

					$transaction->commit();
				} catch (Exception $e) {
					$transaction->rollBack();
					echo CJSON::encode(
						array(
							'requestresult' => 'fail',
							'message' => $e->getMessage(),
						)
					);
					exit;
				}
			} else {
				$ordencompra->save();
			}
			//
			$this->Actualizartotaloc($id);
			// llamamos la funcion para actualizar el costo de la oc
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Orden de compra actualizada con exito',
				)
			);
			exit;
		}
	}

	/**
																																	 FUNCION PARA RECIBIR UN OC
							 **/
	public function actionRecibir()
	{

		// 4= ORDEN DE COMPRA AUTORIZADA
		// 5 = CERRADA Y LIBERADA A PAGOS

		$ordencompra = array();
		$conceptos = array();
		$id_sucursal = isset($_GET['idsucursal']) ? $_GET['idsucursal'] : 0;
		// $id_sucursal = Yii::app()->user->getstate('id_sucursal');

		$ordenescompra = OrdenesCompra::model()->findAll(
			array(
				'condition' => 'eliminado = 0 and estatus in(4) and id_sucursal = :id_sucursal',
				'params' => array(':id_sucursal' => $id_sucursal)
			)
		);

		if (isset($_GET['buscar']) && isset($_GET['numero_oc'])) {
			// buscamos la oc
			$ordencompra = OrdenesCompra::model()->find(
				array(
					'condition' => 'eliminado = 0 and id = :id and estatus in(4,5) and id_sucursal = :id_sucursal',
					'params' => array(':id' => $_GET['numero_oc'], ':id_sucursal' => $id_sucursal)
				)
			);
			if (empty($ordencompra)) {
				Yii::app()->user->setFlash('danger', "Orden de compra no encontrada.");
				$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
				$this->redirect($urlfrom);
			}
			// obtenemos los conceptos
			$conceptos = OrdenesCompraDetalles::model()->findAll(
				array(
					'condition' => 'id_orden_compra = :idoc and eliminado = 0',
					'params' => array(':idoc' => $ordencompra['id'])
				)
			);
		}

		$this->render(
			'recibir',
			array(
				'ordencompra' => $ordencompra,
				'conceptos' => $conceptos,
				'ordenescompra' => $ordenescompra,

			)
		);
	}

	/**
																																	 FUNCION PARA RECIBIR UN OC
							 **/
	public function actionPagar()
	{
		$id_sucursal = Yii::app()->user->getstate('id_sucursal');
		/*
																																																																		  4= ORDEN DE COMPRA AUTORIZADA
																																																																		  5 = CERRADA Y LIBERADA A PAGOS
																																																																		  */
		$ordencompra = array();
		$pagos = array();
		$conceptos = array();
		$ordenescompra = OrdenesCompra::model()->findAll(
			array(
				'condition' => 'eliminado = 0 and estatus in(4,5) and total_pendiente > 0 and id_sucursal = :idsuc',
				'params' => array(':idsuc' => $id_sucursal)
			)
		);
		$metodosdepago = GrupoRecurrenteDetalles::model()->findAll(
			array(
				'condition' => 'id_grupo = 3 and eliminado = 0 and id not in (49,50)',
				'order' => 'valor asc'
			)
		);

		if (isset($_GET['buscar']) && isset($_GET['numero_oc'])) {
			// buscamos la oc
			$ordencompra = OrdenesCompra::model()->find(
				array(
					'condition' => 'eliminado = 0 and id = :id and estatus in(4,5) and id_sucursal = :idsuc',
					'params' => array(':id' => $_GET['numero_oc'], ':idsuc' => $id_sucursal)
				)
			);
			if (empty($ordencompra)) {
				Yii::app()->user->setFlash('danger', "Orden de compra no encontrada.");
				$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
				$this->redirect($urlfrom);
			}
			$pagos = Egresos::model()->findAll(
				array(
					'condition' => 'eliminado = 0 and tipo = 3 and id_identificador=:id_identificador',
					'params' => array(':id_identificador' => $_GET['numero_oc'])
				)
			);

			// obtenemos los conceptos
			$conceptos = OrdenesCompraDetalles::model()->findAll(
				array(
					'condition' => 'id_orden_compra = :idoc and eliminado = 0',
					'params' => array(':idoc' => $ordencompra['id'])
				)
			);
		}
		$modeloparaegreo = new Egresos();
		$this->render(
			'pagar',
			array(
				'ordencompra' => $ordencompra,
				'conceptos' => $conceptos,
				'ordenescompra' => $ordenescompra,
				'pagos' => $pagos,
				'modeloparaegreo' => $modeloparaegreo,
				'metodosdepago' => CHtml::listData($metodosdepago, 'id', 'nombre')

			)
		);
	}

	/**
																																	 FUNCION PARA ACTUALIZAR EL COSTO DE LA ORDEN DE COMPRA
																																	 SE DEBE MANDAR A LLAMAR DESPUES DE AGREGAR UN PRODUCTO, EDITAR O ELIMINAR
							 **/
	public function Actualizartotaloc($id)
	{
		if ($id == 0) {
			return 1;
		}
		$ordendecompra = OrdenesCompra::model()->find('id=:id and eliminado = 0', array(':id' => $id));
		// actualizamos la ordendecompra
		$ordendecompratotalsql = '	select sum(total) as total,sum(subtotal_unitario) as subtotal,sum(subtotal_iva) as iva from ordenes_compra_detalles where eliminado = 0 and id_orden_compra = ' . $id;
		$totaloc = Yii::app()->db->createCommand($ordendecompratotalsql)->queryrow();
		$ordendecompra->subtotal = $totaloc['subtotal'];
		$ordendecompra->iva = $totaloc['iva'];
		$ordendecompra->total = $totaloc['total'];
		$ordendecompra->total_pendiente = $totaloc['total'];
		$ordendecompra->total_pagado = 0;
		if ($ordendecompra->save()) {
			$logoc = array(
				'id_orden_compra' => $ordendecompra->id,
				'estatus_anterior' => '',
				'estatus_final' => $ordendecompra->estatus,
				'comentarios' => $ordendecompra->comentarios,
				'id_usuario' => $ordendecompra->id_usuario_crea,
				'fecha_alta' => $ordendecompra->fecha_alta,
				'total' => $ordendecompra->total
			);
			$this->Insertarlogoc($logoc);

			return 1;
		} else {
			return 0;
		}
	}

	/**
																																	 METODO PARA AGREGAR UN PAGO A LA ORDEN DE COMPRA
							 **/
	public function actionAgregarpago()
	{
		// cachamos las variables
		$idcompra = $_POST['Egresos']['id_identificador'];
		$id_sucursal = Yii::app()->user->getstate('id_sucursal');
		print_r($_POST);
		// verificamos que exista la orden de compra
		$ordendecompra = OrdenesCompra::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and id_sucursal = :id_sucursal',
				'params' => array(':id' => $idcompra, ':id_sucursal' => $id_sucursal)
			)
		);
		if (empty($ordendecompra)) {
			Yii::app()->user->setflash('danger', 'No se encontro la orden de compra.');
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
		}
		// verificamos que no este pagada
		if ($ordendecompra['total_pendiente'] < $_POST['Egresos']['monto']) {
			Yii::app()->user->setflash('danger', 'El pago no puede ser mayor a lo pendiente.');
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
		}
		// aqui ya podemos realizar el pago

		$pago = new Egresos();
		$pago->id_sucursal = $id_sucursal;
		$pago->id_usuario = Yii::app()->user->id;
		$pago->tipo = $_POST['Egresos']['tipo'];
		$pago->id_identificador = $_POST['Egresos']['id_identificador'];
		$pago->id_forma_pago = $_POST['Egresos']['id_forma_pago'];
		$pago->comentarios = $_POST['Egresos']['comentarios'];
		$pago->fecha_alta = date('Y-m-d H:i:s');
		$pago->eliminado = 0;
		$pago->monto = $_POST['Egresos']['monto'];

		if ($pago->save()) {
			// actualizamos el total pendiente y pagado de la orden de compra
			$ordendecompra->total_pendiente = $ordendecompra->total_pendiente - $pago->monto;
			$ordendecompra->total_pagado = $ordendecompra->total_pagado + $pago->monto;
			if ($ordendecompra->save()) {
				// mandamos un mensaje de exito
				Yii::app()->user->setflash('success', 'Pago agregado correctamente.');
			} else {
				Yii::app()->user->setflash('danger', 'No fue posible actualizar la orden de compra.');
			}
		} else {
			Yii::app()->user->setflash('danger', 'No fue posible agregar el pago.');
		}
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	/**
																																	 metodo para crear el pdf
							 **/
	public function actionPdf()
	{
		$id = $_GET['id'];
		if (empty($id)) {

			exit;
		}
		$ordendecompra = OrdenesCompra::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id)
			)
		);
		if (empty($ordendecompra)) {
			exit;
		}
		// datos del concepto
		$conceptos = OrdenesCompraDetalles::model()->findAll(
			array(
				'condition' => 'id_orden_compra = :id and eliminado = 0',
				'params' => array(':id' => $id)
			)
		);
		if (empty($conceptos)) {

			exit;
		}
		// Datos de configuracion 
		$DatosConfiguracion = Configuracion::model()->findBypk(1);
		// dompdf
		$dompdf = new Dompdf();
		// $fecha_formateada = date("d F Y", strtotime($DatosCotizacion['cotizacion_fecha_alta']));
		$html = $this->renderPartial('pdf', array(

			'ordendecompra' => $ordendecompra,
			'conceptos' => $conceptos,
			'DatosConfiguracion' => $DatosConfiguracion,
		), true);
		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait'); //->tamaño y orientacion de la hoja
		// Render the HTML as PDF
		$dompdf->render();
		// paginacion
		$canvas = $dompdf->get_canvas();
		$canvas->page_text(520, 750, "Hoja {PAGE_NUM} - {PAGE_COUNT}", null, 12, array(0, 0, 0));

		$dompdf->stream('Orden de Compra-' . $id . '-' . date('Y') . '.pdf', array('Attachment' => 0));


		// PDF
		# You can easily override default constructor's params
		// $mPDF1 = Yii::app()->ePdf->mpdf(
		// 	'',
		// 	// mode - default ''
		// 	'',
		// 	// format - A4, for example, default ''
		// 	0,
		// 	// font size - default 0
		// 	'segoe-ui',
		// 	// default font family
		// 	10,
		// 	// margin_left
		// 	10,
		// 	// margin right
		// 	40,
		// 	// margin top
		// 	16,
		// 	// margin bottom
		// 	9,
		// 	// margin header
		// 	9,
		// 	// margin footer
		// 	'L'
		// ); // L - landscape, P - portrait

		// $mPDF1->SetDisplayMode('fullpage');
		// $mPDF1->list_indent_first_level = 0;
		// $mPDF1->setAutoTopMargin = 'stretch';
		// $mPDF1->setAutoBottomMargin = 'stretch';
		// /*$mPDF1->SetHTMLHeader('<img src="' .Yii::app()->baseurl. '/images/header.png"/>');
		// 																																													$mPDF1->SetHTMLFooter('<img src="' .Yii::app()->baseurl. '/images/footer.png"/>');*/
		// //{PAGENO}

		// $html = $this->renderPartial(
		// 	'pdf',
		// 	array(
		// 		'ordendecompra' => $ordendecompra,
		// 		'conceptos' => $conceptos
		// 	),
		// 	true
		// );

		// $mPDF1->WriteHTML($html);

		// # Outputs ready PDF
		// $mPDF1->Output('Orden de Compra #  ' . $ordendecompra['id'] . '.pdf', 'I'); //Nombre del pdf y parámetro para ver pdf o descargarlo directamente.
		// exit;
	}

	/**
																																		 METODO PARA OBTENER LOS DATOS DEL IDENTIFICADOR
							 **/
	public function actionObtenerdatosidentificadoregreso()
	{
		//
		$tipo = $_POST['tipo'];
		$id_identificador = $_POST['id_identificador'];
		//
		switch ($tipo) {
			case 3: // 3 = PAGO A ORDEN DE COMPRA
				// Obtenemos la lista de ordenes de compra liberadas para pago - 5 = CERRADA Y LIBERADA A PAGOS con total_pendiente > 0 
				$ordenescompra = OrdenesCompra::model()->findbypk($id_identificador);
				if (empty($ordenescompra)) {
					// no existen oc
					echo CJSON::encode(
						array(
							'requestresult' => 'fail',
							'message' => 'No se encontraron los datos de la Orden de compra',
						)
					);
					exit;
				}
				//
				echo CJSON::encode(
					array(
						'requestresult' => 'ok',
						'message' => 'Ordenes de compra encontradas',
						'datos' => $ordenescompra
					)
				);
				exit;

				break;

			default:
				echo CJSON::encode(
					array(
						'requestresult' => 'fail',
						'message' => 'No se encontro el tipo de egreso',
					)
				);
				exit;
				break;
		}
	}
}
