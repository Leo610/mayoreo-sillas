<?php
// estatus del proyecto 
// 2=>En Producción
// 6=>Empacado
// 8=>Entregado
// 7=>Cancelado

require_once(__DIR__ . '/../vendor/dompdf/autoload.inc.php');
// require_once(__DIR__ . '/../vendor/mpdf');
use Dompdf\Dompdf;

class ProyectosController extends Controller
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
	// Estatus de las ordenes de compra
	// 0 = Generados
	// 1 = Surtido
	// 2 = En Proceso
	// 3 = Finalizar
	public function ObtenerEstatus($id_estatus)
	{
		switch ($id_estatus) {
			case 0:
				return 'Generado';
				break;
			case 1:
				return 'Surtido';
				break;
			case 2:
				return 'En Proceso';
				break;
			case 3:
				return 'Facturado';
				break;
			case 4:
				return 'Pagado';
				break;
			case 5:
				return 'Finalizado';
				break;

			default:
				# code...
				break;
		}
	}


	/*
	 *	METODO PARA VER LA LISTA DE PROYECTOS DE TODOS LOS CLIENTES
	 *	CREADO POR DANIEL VILLARREAL EL 1 DE FEBRERO DEL 2016
	 */
	public function actionLista()
	{
		// Verificamos el acceso al modulo
		$VerificarAcceso = $this->VerificarAcceso(18, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		// termina

		$id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';
		// $fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_last_three_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();

		$nombreUsuario = (isset($_GET['nombreusuario'])) ? $_GET['nombreusuario'] : '';
		$id_usuario = (isset($_GET['id_usuario'])) ? $_GET['id_usuario'] : '';
		$id_bodega = (isset($_GET['id_bodega'])) ? $_GET['id_bodega'] : 0;
		// Obtenemos todos los usuarios relacionados
		$usuarioshijos = $this->ObtenerHijosUsuario(Yii::app()->user->id, '');
		// TERMINA 
		// Obtenemos la lista de cotizaciones de todos los clientes
		$parametros = 'proyecto_fecha_alta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" ';
		if (!empty($id_usuario)) {

			$parametros .= 'and id_usuario = ' . $id_usuario . '';
		} else {

			// $parametros .= ' and id_usuario in (' . $usuarioshijos . ') ';

		}

		$proyecto_estatus = (isset($_GET['proyecto_estatus']) and $_GET['proyecto_estatus'] != NULL) ? $_GET['proyecto_estatus'] : '-9';
		if ($proyecto_estatus != -9) {
			$parametros .= ' and proyecto_estatus=' . $proyecto_estatus . '';
		}

		if (!empty($id_cliente)) {
			$parametros = 'id_cliente = ' . $id_cliente . '';
		}

		if (!empty($id_bodega)) {
			$parametros .= ' and  id_usuario IN (SELECT ID_Usuario FROM usuarios WHERE bodega = ' . $id_bodega . ')';
		}

		// modificar server
		// obtenemos las bodegas
		$bodegas_ = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 28');

		$bodegas = [];

		foreach ($bodegas_ as $row) {
			$bodegas[] = [
				'id' => $row['id_catalogo_recurrente'],
				'name' => $row['nombre']
			];
		}


		// echo $parametros;
		// exit;
		// 
		// Obtenemos la lista de proyectos con los parametros ingresados
		$listaproyectos = Proyectos::model()->findAll(
			array(
				'condition' => $parametros,
				'order' => 'id_proyecto desc'
			)
		);



		$this->render(
			'lista',
			array(
				'formasPago' => $formaspago,
				'parametros' => $parametros,
				'listaproyectos' => $listaproyectos,
				'proyecto_estatus' => $proyecto_estatus,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin,
				'nombre' => $nombreUsuario,
				'bodegas' => $bodegas,
				'id_bodega' => $id_bodega
			)
		);
	}

	/*
	 *	METODO PARA OBTENER INFORMACION DE UN PROYECTO
	 *	CREADO POR DANIEL VILLARREAL EL 1 DE FEBRERO DEL 2016
	 */
	public function actionDatosjs()
	{
		$id = $_POST['id'];
		// Obtenemos los datos de lal proyecto
		$DatosProyecto = Proyectos::model()->findBypk($id);
		$Cliente = $DatosProyecto->rl_clientes->cliente_nombre;
		if (!empty($DatosProyecto)) {

			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Informacion encontrada',
					'DatosProyecto' => $DatosProyecto,
					'Cliente' => $Cliente
				)
			);
		} else {
			echo CJSON::encode(
				array(
					'requestresult' => 'error',
					'message' => 'No se encontro informacion',
				)
			);
		}
	}




	public function actionVer($id)
	{

		$VerificarAcceso = $this->VerificarAcceso(29, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		// phpinfo();
		// exit;
		// Obtenemos los datos del proyecto
		$DatosProyecto = Proyectos::model()->findByPk($id);
		// Datos cliente
		$DatosCliente = Clientes::model()->findByPk($DatosProyecto->id_cliente);
		// Obtenemos el detalle de los productos
		$Productosproyecto = Proyectosproductos::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto);

		// Obtenemos las bodegas para los productos
		$bodegas = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 28');

		// Obtenemos las ordenes de compra que se realizaron en base al proyecto

		// Modeo para validar datos del cliente
		$modelcliente = new Clientes;
		// Obtenemos las listas de precios, para asignarlas al cliente.
		$Listaprecios = CHtml::listData(ListaPrecios::model()->findAll(), 'id_lista_precio', 'listaprecio_nombre');


		$listaproductos = CHtml::listData(Productos::model()->findAll(), 'id_producto', 'producto_nombre');

		// Obtenemos los archivos en el proyecto
		$listaarchivos = Proyectosarchivos::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto);
		// Regresamos el modelo 
		$agregararchivo = new Proyectosarchivos;

		// Lista monedas
		$ListaMonedas = CHtml::listData(Monedas::model()->findAll(), 'id_moneda', 'moneda_nombre');


		//
		$Clasificacion = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=9');
		$ListaClasificacion = CHtml::listData($Clasificacion, 'id_catalogo_recurrente', 'nombre');

		$Trabajarlo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=10');
		$ListaComoTrabajarlo = CHtml::listData($Trabajarlo, 'id_catalogo_recurrente', 'nombre');

		$ListaEmpresa = CHtml::listData(Empresas::model()->findAll(), 'id', 'empresa');

		// Obtenemos los tipos de estapas
		$Tipo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=3');
		$listatipo = CHtml::listData($Tipo, 'id_catalogo_recurrente', 'nombre');
		//

		// modelo para agregar un comentario
		$Proyectoscomentarios = new Proyectoscomentarios();
		$Proyectoscomentarios->id_proyecto = $id;
		$Proyectoscomentarios->id_usuario = yii::app()->user->id;

		// lista de comentarios
		$listacomentarios = Proyectoscomentarios::model()->findall('id_proyecto=' . $id);

		// color estructra
		$color_estructura = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente= 27');




		$this->render(
			'ver',
			array(
				'id' => $id,
				'DatosProyecto' => $DatosProyecto,
				'datoscliente' => $DatosCliente,
				'Productosproyecto' => $Productosproyecto,
				'model' => $modelcliente,
				'arraylistaprecios' => $Listaprecios,
				'listaproductos' => $listaproductos,
				'agregararchivo' => $agregararchivo,
				'listaarchivos' => $listaarchivos,
				'ListaMonedas' => $ListaMonedas,
				'ListaClasificacion' => $ListaClasificacion,
				'ListaComoTrabajarlo' => $ListaComoTrabajarlo,
				'ListaEmpresa' => $ListaEmpresa,
				'listatipo' => $listatipo,
				'Proyectoscomentarios' => $Proyectoscomentarios,
				'listacomentarios' => $listacomentarios,
				'bodegas' => $bodegas,
				'color_estructura' => $color_estructura
			)
		);
	}

	/**
	 * *action para ver los detalles del pedido cuadno dan click en la pizarra
	 * * lars 11/10/2023
	 */

	public function actionDetalle()
	{
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$DatosProyecto = Proyectos::model()->findByPk($id);
		$Productosproyecto = Proyectosproductos::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto);
		$bodegas = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 28');
		// echo "<pre>";
		// print_r($_GET);
		// echo "</pre>";
		// exit();
		$this->render(
			'detallepedido',
			[
				'DatosProyecto' => $DatosProyecto,
				'Productosproyecto' => $Productosproyecto,
				'bodegas' => $bodegas
			]
		);
	}

	/*
	 *	METODO PARA CREAR UN PROYECTO, EN BASE A UNA COTIZACION
	 *	CREADO POR DANIEL VILLARREAL EL 14 DE FEBRERO DEL 2016
	 */
	public function actionCrear($id)
	{

		$descuentos = ($this->VerificarAcceso(8, Yii::app()->user->id)) == 1 ? false : true;
		// Lista de tipoproyectos.
		$tipoproyecto = TipoProyectos::model()->findall('id_tipo_proyecto');

		// datos de la cotizacion
		$DatosCot = Cotizaciones::model()->findBypk($id);

		// Datos del cliente
		$DatosCliente = Clientes::model()->findBypk($DatosCot->id_cliente);

		// Obtenemos los productos de la cotizacion.
		$ProductosCotizacion = Cotizacionesproductos::model()->findAll('id_cotizacion=' . $DatosCot->id_cotizacion);

		// obtenemos la forma de pago 
		// Lista de formas de pago
		$ListaFormasPago = Formasdepago::model()->findAll();


		$this->render(
			'crear',
			array(
				'DatosCliente' => $DatosCliente,
				'DatosCotizacion' => $DatosCot,
				'ProductosCot' => $ProductosCotizacion,
				'tipoproyecto' => $tipoproyecto,
				'descuentos' => $descuentos,
				'ListaFormasPago' => $ListaFormasPago,

			)
		);
	}

	/*
	 *	METODO PARA GENERAR UN PROYECTO, EN BASE A UNA COTIZACION
	 *	CREADO POR DANIEL VILLARREAL EL 14 DE FEBRERO DEL 2016
	 */
	public function actionGenerarproyecto()
	{

		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit();

		$id_cotizacion = $_POST['id_cotizacion'];
		#$id_almacen = $_POST['id_almacen'];
		$proyecto_comentarios = $_POST['proyecto_comentarios'];
		#$proyecto_supervisor =$_POST['proyecto_supervisor'];
		// $proyecto_nombre = $_POST['proyecto_nombre'];
		$localidad = $_POST['localidad'];
		$tipoproyecto = isset($_POST['id_tipo_proyecto']) ? $_POST['id_tipo_proyecto'] : '';
		// fecha de entrega, esta va pa los productos
		$fecha = $_POST['fecha'];
		$flete = isset($_POST['flete']) ? $_POST['flete'] : '';
		// datos de la cotizacion
		$DatosCot = Cotizaciones::model()->findBypk($id_cotizacion);
		// Datos del cliente
		$DatosCliente = Clientes::model()->findBypk($DatosCot->id_cliente);

		// Encabezado del proyecto
		$AgregarProyecto = new Proyectos;
		$AgregarProyecto->id_cliente = $DatosCot->id_cliente;
		// $AgregarProyecto->proyecto_nombre = $proyecto_nombre;
		$AgregarProyecto->proyecto_total = $DatosCot->cotizacion_total;
		$AgregarProyecto->proyecto_totalpagado = 0;
		$AgregarProyecto->proyecto_totalpendiente = $DatosCot->cotizacion_total;
		$AgregarProyecto->proyecto_estatus = 0; // El proyecto, nace como generado
		$AgregarProyecto->proyecto_fecha_alta = date('Y-m-d H:i:s');
		$AgregarProyecto->proyecto_ultima_modificacion = date('Y-m-d H:i:s');
		$AgregarProyecto->proyecto_comentarios = $proyecto_comentarios;
		$AgregarProyecto->id_usuario = Yii::app()->user->id;
		$AgregarProyecto->tipo_cambio = $DatosCot->tipo_cambio;
		$AgregarProyecto->total_peso = $DatosCot->total_peso;
		$AgregarProyecto->id_tipo_proyecto = $tipoproyecto;
		$AgregarProyecto->id_cotizacion = $DatosCot['id_cotizacion'];
		$AgregarProyecto->localidad = $localidad;
		$AgregarProyecto->proyecto_condiciones_generales = $flete;
		$AgregarProyecto->sumar_iva = $DatosCot->sumar_iva;



		// Una vez guardado el encabezado del proyecto, procedemos a guardar los productos del mismo
		if ($AgregarProyecto->save()) {
			$ultimoid = $AgregarProyecto['id_proyecto'];

			$DatosCot->pedido = 1;
			$DatosCot->save();

			// Sumamos + 1 en la tabla de tipo proyectos el tipo seleccionado, columna serie_cotizacion
			if (!empty($tipoproyecto)) {

				$tipoproyecto = TipoProyectos::model()->findBypk($AgregarProyecto->id_tipo_proyecto);
				$tipoproyecto->sere_proyecto = $tipoproyecto->sere_proyecto + 1;
				$tipoproyecto->save();
			}
			// Actualizamos el cliente
			$DatosCliente->cliente_tipo = 1;
			$DatosCliente->save();

			$numero_proyecto = date('Y') . '-' . $AgregarProyecto->id_tipo_proyecto . '-' . $AgregarProyecto->id_proyecto;
			$AgregarProyecto->numero_proyecto = $numero_proyecto;
			$AgregarProyecto->save();

			// Obtenemos los productos de la cotizacion.
			$ProductosCotizacion = Cotizacionesproductos::model()->findAll('id_cotizacion=' . $DatosCot->id_cotizacion);

			// Primero Guardamos los productos finales
			$costoProyectoProducto = 0;

			foreach ($ProductosCotizacion as $rows) {
				// primero sacamos el id del prodcuto para ir al prodcuto y sacar su tipo

				$bodegaFabricacion = CatalogosRecurrentes::model()->find(
					array(
						'condition' => 'eliminado=0 and id_grupo_recurrente=28 and id_catalogo_recurrente=:id_bodega',
						'params' => array(':id_bodega' => $rows['rl_producto']['id_bodega_fabricacion'])
					)
				);

				$traerstock = SucursalesProductos::model()->find(
					array(
						'condition' => 'id_producto=:id_producto',
						'params' => array(':id_producto' => $rows['id_producto']),
						'order' => 'cantidad_stock desc'
					)
				);

				$stock_anterior = $traerstock['cantidad_stock'];

				$Proyectoproductos = new Proyectosproductos;
				if ($rows['rl_producto']['tipo'] == 1) {

					// verificamos si la cantidad de estock es mayor 
					if ($traerstock['cantidad_stock'] > $rows['cotizacion_producto_cantidad']) {
						// reducimos cantidad
						$traerstock['cantidad_stock'] = $traerstock['cantidad_stock'] - $rows['cotizacion_producto_cantidad'];
						$traerstock->save();
						// ya que se resto la cantidad se guardara el movimiento en sucursales movimiento
						$movimiento = new SucursalesMovimientos;
						$movimiento->id_sucursal = $traerstock['id_sucursal'];
						$movimiento->id_producto = $traerstock['id_producto'];
						$movimiento->tipo = 2;
						$movimiento->tipo_identificador = 23;
						$movimiento->id_identificador = $ultimoid;
						$movimiento->cantidad_stock_antes = $stock_anterior;
						$movimiento->cantidad_mov = $rows['cotizacion_producto_cantidad'];
						$movimiento->cantidad_stock_final = $traerstock['cantidad_stock'];
						$movimiento->fecha_movimiento = date('Y-m-d H:i:s');
						$movimiento->id_usuario = Yii::app()->user->id;

						$movimiento->eliminado = 0;
						$movimiento->comentarios = 'Movimiento por pedido';
						// $movimiento->folio_rsi = $folio_rsi;
						if (!$movimiento->save()) {
							throw new Exception("No se  guardo la actualización del movimiento.");
						}

						$Proyectoproductos->salio_stock = 1;
						$Proyectoproductos->cantidad_salida = $rows['cotizacion_producto_cantidad'];
					} else {
						$Proyectoproductos->salio_stock = 0;
						$Proyectoproductos->cantidad_salida = 0;
					}
				}

				$producto = Productos::model()->findByPk($rows['id_producto']);

				$Proyectoproductos->id_proyecto = $AgregarProyecto->id_proyecto;
				$Proyectoproductos->id_producto = $rows->id_producto;
				$Proyectoproductos->proyectos_productos_cantidad = $rows->cotizacion_producto_cantidad;
				$Proyectoproductos->proyectos_productos_descripcion = $rows->cotizacion_producto_descripcion;
				$Proyectoproductos->color = $rows->color;
				$Proyectoproductos->proyectos_productos_cantidad_surtida = 0;
				$Proyectoproductos->tipo_producto = $rows['rl_producto']['tipo'];
				$Proyectoproductos->bodega = $bodegaFabricacion['id_catalogo_recurrente'];
				$Proyectoproductos->estatus = 1;
				$Proyectoproductos->id_etapa = 218;
				$Proyectoproductos->color_tapiceria = $rows['color_tapiceria'];
				$Proyectoproductos->especificaciones_extras = $rows['especificaciones_extras'];
				$Proyectoproductos->fecha_alta = date('Y-m-d H:i:s');
				$Proyectoproductos->fecha_de_entrega = (!empty($fecha)) ? $fecha : null;
				$Proyectoproductos->precio_venta_producto = $rows->cotizacion_producto_total / $rows->cotizacion_producto_cantidad;
				$Proyectoproductos->costo = ($rows->cotizacion_producto_total / $rows->cotizacion_producto_cantidad) * ($producto['utilidad'] / 100);

				$desc = $Proyectoproductos->proyectos_productos_descripcion;
				$color = $Proyectoproductos->color;
				$bode = $Proyectoproductos->bodega;
				$colortapi = $Proyectoproductos->color_tapiceria;
				$espext = $Proyectoproductos->especificaciones_extras;
				$estatus = $Proyectoproductos->estatus;
				$fechaentrega = $Proyectoproductos->fecha_de_entrega;

				if ($Proyectoproductos->save()) {
					$costoProyectoProducto += $Proyectoproductos->costo;

					if (!empty($fechaentrega)) {
						$guardarenrecurrentes = new CatalogosRecurrentes;
						$guardarenrecurrentes->num = $Proyectoproductos->id_proyectos_productos;
						$guardarenrecurrentes->nombre = 'fecha de entrega';
						$guardarenrecurrentes->descripcion = $fechaentrega;
						$guardarenrecurrentes->eliminado = 0;
						$guardarenrecurrentes->id_grupo_recurrente = 37;
						$guardarenrecurrentes->id_usuario = Yii::app()->user->id;
						$guardarenrecurrentes->fecha_alta = date('Y-m-d H:i:s');
						$guardarenrecurrentes->save();
					}

					if (!empty($desc)) {
						$guardarenrecurrentes = new CatalogosRecurrentes;
						$guardarenrecurrentes->num = $Proyectoproductos->id_proyectos_productos;
						$guardarenrecurrentes->nombre = 'descripcion';
						$guardarenrecurrentes->descripcion = $desc;
						$guardarenrecurrentes->eliminado = 0;
						$guardarenrecurrentes->id_grupo_recurrente = 29;
						$guardarenrecurrentes->id_usuario = Yii::app()->user->id;
						$guardarenrecurrentes->fecha_alta = date('Y-m-d H:i:s');
						$guardarenrecurrentes->save();
					}
					if (!empty($colortapi)) {
						$guardarenrecurrentes = new CatalogosRecurrentes;
						$guardarenrecurrentes->num = $Proyectoproductos->id_proyectos_productos;
						$guardarenrecurrentes->nombre = 'Color tapiceria';
						$guardarenrecurrentes->descripcion = $colortapi;
						$guardarenrecurrentes->eliminado = 0;
						$guardarenrecurrentes->id_grupo_recurrente = 35;
						$guardarenrecurrentes->id_usuario = Yii::app()->user->id;
						$guardarenrecurrentes->fecha_alta = date('Y-m-d H:i:s');
						$guardarenrecurrentes->save();
					}
					if (!empty($espext)) {
						$guardarenrecurrentes = new CatalogosRecurrentes;
						$guardarenrecurrentes->num = $Proyectoproductos->id_proyectos_productos;
						$guardarenrecurrentes->nombre = 'Especificaciones extras';
						$guardarenrecurrentes->descripcion = $espext;
						$guardarenrecurrentes->eliminado = 0;
						$guardarenrecurrentes->id_grupo_recurrente = 36;
						$guardarenrecurrentes->id_usuario = Yii::app()->user->id;
						$guardarenrecurrentes->fecha_alta = date('Y-m-d H:i:s');
						$guardarenrecurrentes->save();
					}

					if (!empty($bode)) {
						$guardarenrecurrentes = new CatalogosRecurrentes;
						$guardarenrecurrentes->num = $Proyectoproductos->id_proyectos_productos;
						$guardarenrecurrentes->nombre = 'bodega';
						$guardarenrecurrentes->descripcion = $bode;
						$guardarenrecurrentes->eliminado = 0;
						$guardarenrecurrentes->id_grupo_recurrente = 31;
						$guardarenrecurrentes->id_usuario = Yii::app()->user->id;
						$guardarenrecurrentes->fecha_alta = date('Y-m-d H:i:s');
						$guardarenrecurrentes->save();
					}
					if (!empty($color)) {
						$guardarenrecurrentes = new CatalogosRecurrentes;
						$guardarenrecurrentes->num = $Proyectoproductos->id_proyectos_productos;
						$guardarenrecurrentes->nombre = 'color';
						$guardarenrecurrentes->descripcion = $color;
						$guardarenrecurrentes->eliminado = 0;
						$guardarenrecurrentes->id_grupo_recurrente = 30;
						$guardarenrecurrentes->id_usuario = Yii::app()->user->id;
						$guardarenrecurrentes->fecha_alta = date('Y-m-d H:i:s');
						$guardarenrecurrentes->save();
					}
				}
			}
			// Obtenemos los archivos en la cotizacion para asignarlos al proyecto
			$ArchivosCot = Cotizacionesarchivos::model()->findall('id_cotizacion=' . $id_cotizacion);
			foreach ($ArchivosCot as $rowsa) {
				$addarchivo = new Proyectosarchivos;
				$addarchivo->id_proyecto = $AgregarProyecto->id_proyecto;
				$addarchivo->proyectos_archivos_archivo = $rowsa->cotizacion_archivo;
				$addarchivo->proyectos_archivos_nombre = $rowsa->cotizacion_archivo_nombre;
				$addarchivo->save();
			}

			$AgregarProyecto->costo = $costoProyectoProducto;
			$AgregarProyecto->save();
			// Termina
			// si forma de pago y monto n estan vacios queire decir que agreagron un ongreso
			$formap = $_POST['fomapago'];
			$monto = $_POST['monto'];

			//si no esta vacio ingresaron monto
			if (!empty($formap) && !empty($monto)) {
				// si no esta vacio ingresamos en ingresos 
				$ingreso = new Contabilidadingresos;

				$ingreso->id_formapago = $formap;
				$ingreso->id_usuario = Yii::app()->user->id;
				$ingreso->contabilidad_ingresos_identificador = 'Pedido - ' . $ultimoid;
				$ingreso->contabilidad_ingresos_cantidad = $monto;
				$ingreso->contabilidad_ingresos_fechaalta = date('Y-m-d H:i:s');
				$ingreso->id_moneda = 1;
				$ingreso->pendiente = $AgregarProyecto['proyecto_totalpendiente'] - $monto;

				if ($ingreso->save()) {
					// agregamos al proyecto lo que se pago
					$proyecto = Proyectos::model()->findByPk($ultimoid);

					$proyecto->proyecto_totalpagado = $monto;
					$proyecto->proyecto_totalpendiente = $proyecto->proyecto_total - $monto;
					$proyecto->save();
				}
			}


			// En caso de que la cotizacion sea de una oportunidad, actualizamos el estatus de la oportunindad a ganada.
			if ($DatosCot->id_oportunidad > 0) {
				$actOportunidad = CrmOportunidades::model()->findBypk($DatosCot->id_oportunidad);
				$actOportunidad->estatus = 'GANADO';
				$actOportunidad->fecha_ultima_modificacion = date('Y-m-d H:i:s');
				$actOportunidad->save();
			}

			// Obtenemos en base al cliente el tipo de frecuencia, la cantidad de dias y el nombre del dia.
			$DatosFrecuencia = ClientesFrecuencias::model()->find('id_cliente=' . $AgregarProyecto->id_cliente);
			if (!empty($DatosFrecuencia)) {
				// Obtenemos la primera etapa
				$Etapa = Crmetapas::model()->find(array('condition' => '', 'order' => 'orden asc'));
				// Quiere decir que existe el registro en la tabla.
				$FechaPedido = date('Y-m-d H:i:s');
				// Obtenemos el numerode dias en base a la frencuencia
				$Dias = $DatosFrecuencia->rl_frecuencia->num;
				$Nombre = $DatosFrecuencia->nombre_dia;
				$FechaOportunidadNueva = $this->ObtenerSigDia($Nombre, $this->Sumardiasfecha($FechaPedido, $Dias));
				// Insertamos una nueva oportunidad y una nueva accion
				$OportunidadRecurrente = new CrmOportunidades;
				$OportunidadRecurrente->id_cliente = $AgregarProyecto->id_cliente;
				$OportunidadRecurrente->valor_negocio = $AgregarProyecto->proyecto_total;
				$OportunidadRecurrente->id_etapa = $Etapa->id;
				$OportunidadRecurrente->id_usuario = Yii::app()->user->id;
				$OportunidadRecurrente->nombre = 'Recurrente ' . $AgregarProyecto->proyecto_nombre;
				$OportunidadRecurrente->estatus = 'SEGUIMIENTO';
				$OportunidadRecurrente->fecha_alta = date('Y-m-d H:i:s');
				$OportunidadRecurrente->fecha_ultima_modificacion = date('Y-m-d H:i:s');
				$OportunidadRecurrente->tipo_oportunidad = 1;
				if ($OportunidadRecurrente->save() && $Dias > 0) {
					// Obtenemos la primera etapa
					$Accion = Crmacciones::model()->find(array('condition' => 'crm_acciones_nombre LIKE "%llamada%"'));
					// Guardamos una accion a la oportunidad creada
					$AccionRecurrente = new Crmdetalles;
					$AccionRecurrente->id_oportunidad = $OportunidadRecurrente->id;
					$AccionRecurrente->id_cliente = $AgregarProyecto->id_cliente;
					$AccionRecurrente->id_crm_acciones = $Accion['id_crm_acciones'];
					$AccionRecurrente->crm_detalles_comentarios = 'Accion agregada automaticamente.';
					$AccionRecurrente->crm_detalles_fecha_alta = date('Y-m-d H:i:s');
					$AccionRecurrente->crm_detalles_usuario_alta = Yii::app()->user->id;
					$AccionRecurrente->crm_detalles_estatus = 1;
					$AccionRecurrente->crm_detalle_ultima_modificacion = date('Y-m-d H:i:s');
					$AccionRecurrente->crm_detalles_fecha = $FechaOportunidadNueva . ' ' . date('H:i:s');
					$AccionRecurrente->estatus = 'NO REALIZADO';
					$AccionRecurrente->save();
				}
			}

			// dvb 17 01 2024 insertamos en notificaciones
			$nuevanot = new ProyectosNotificaciones;
			$nuevanot->notificacionnuevoproyecto($AgregarProyecto->id_proyecto);
			//

			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Pedido generado, correctamente ' . $AgregarProyecto->id_proyecto,
					'id_proyecto' => $AgregarProyecto->id_proyecto
				)
			);
		} else {


			echo CJSON::encode(
				array(
					'requestresult' => 'error',
					'message' => 'Ocurrio un error inesperado al generar el pedido.',
				)
			);
		}
	}

	public function actionSurtir($id)
	{
		// Obtenemos los datos del proyecto
		$DatosProyecto = Proyectos::model()->findByPk($id);
		// Datos cliente
		$DatosCliente = Clientes::model()->findByPk($DatosProyecto->id_cliente);
		// Obtenemos los productos para surtir en el proyecto
		$Productosproyecto = Proyectosproductos::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto . ' and abierto = 1');
		// Lista de todos los productos
		$listaproductos = CHtml::listData(Productos::model()->findAll(), 'id_producto', 'producto_nombre');


		$this->render(
			'surtir',
			array(
				'listaproductos' => $listaproductos,
				'Productosproyecto' => $Productosproyecto,
				'DatosCliente' => $DatosCliente,
				'DatosProyecto' => $DatosProyecto
			)
		);
	}

	public function actionCantidadstockJS()
	{
		$id_producto = $_POST['id_producto'];
		$id_ubicacion = $_POST['id_ubicacion'];

		// Obtenemosl a cantidad de productos que tenemos en almacen
		$Datosproductostock = Almacenesproductos::model()->find('id_producto=' . $id_producto . ' and id_almacenes_ubicaciones=' . $id_ubicacion);

		if (empty($Datosproductostock)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'error',
					'message' => 'No se encontro el producto en la ubiación.',
					'stock' => 0
				)
			);
		} else {
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Producto encontrado',
					'stock' => $Datosproductostock['almacenesproductos_stock']
				)
			);
		}
	}
	/*
	 *	METODO PARA CAMBIAR ESTATUS PROYECTO
	 *	CREADO POR DANIEL VILLARREAL EL 09 DE MARZO DEL 2016
	 */
	public function actionActualizarestatus()
	{

		$id_orden_de_compra = $_POST['id_orden_de_compra'];
		$proyecto_estatus = $_POST['id_estatus'];
		$Actualizar = Proyectos::model()->findBypk($id_orden_de_compra);
		$actualStatus = $this->statusString($Actualizar->proyecto_estatus);
		$newStatus = $this->statusString($proyecto_estatus);

		$Actualizar->proyecto_estatus = $proyecto_estatus;
		$Actualizar->proyecto_ultima_modificacion = date('Y-m-d H:i:s');
		if ($Actualizar->save()) {
			// guardamos en log 
			$params = ['id_pedido' => $id_orden_de_compra, 'actualstatus' => $actualStatus, 'newstatus' => $newStatus];
			$r = $Actualizar->inserLogs($params);
			if (!$r) {
				echo CJSON::encode(
					array(
						'requestresult' => 'fail',
						'message' => 'Error al guardar en log'
					)
				);
			}
		}


		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'message' => 'Se actualizo con exito'
			)
		);
	}
	/*
	 *	METODO PARA VER EL PROYECTO EN PDF
	 *	CREADO POR DANIEL VILLARREAL EL 10 DE MARZO DEL 2016
	 */
	public function actionPdf($id)
	{
		$VerificarAcceso = $this->VerificarAcceso(29, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		// Obtenemos los datos del proyecto
		$DatosProyecto = Proyectos::model()->findByPk($id);
		$DatosCliente = Clientes::model()->findByPk($DatosProyecto->id_cliente);
		$Productosproyecto = Proyectosproductos::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto);
		// $Empleadosproyecto = Proyectosempleados::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto);
		// $OrdenesdeCompra = Ordenesdecompra::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto);
		$DatosConfiguracion = Configuracion::model()->findBypk(1);
		$listaarchivos = Proyectosarchivos::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto);
		// traemos la ulñtima observacion para agregarla a la remicion
		$observacion = Proyectoscomentarios::model()->find(
			array(
				'condition' => 'id_proyecto = :id',
				'params' => array(':id' => $id),
				'order' => 'id desc'
			)
		);
		/**************************************************************************/

		$dompdf = new Dompdf();
		// $fecha = date("d F Y", strtotime($DatosProyecto['proyecto_fecha_alta']));
		$fecha = date("d F Y");
		$fecha_formateada = $this->Fechaespañol($fecha);



		$html = $this->renderPartial('pdf', array(
			'Datos' => $DatosProyecto,
			'Productosproyecto' => $Productosproyecto,
			'DatosCliente' => $DatosCliente,
			'listaarchivos' => $listaarchivos,
			'DatosConfiguracion' => $DatosConfiguracion,
			'fecha' => $fecha_formateada,
			'obs' => $observacion['descripcion']


		), true);
		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait'); //->tamaño y orientacion de la hoja

		// Render the HTML as PDF
		$dompdf->render();
		// paginacion
		$canvas = $dompdf->get_canvas();
		$canvas->page_text(520, 760, "Hoja {PAGE_NUM} - {PAGE_COUNT}", null, 12, array(0, 0, 0));

		$dompdf->stream($id . ' - ' . $DatosCliente['cliente_nombre'] . '.pdf', array('Attachment' => 0));


		# mPDF
		// $mPDF1 = Yii::app()->ePdf->mpdf();

		// # You can easily override default constructor's params
		// $mPDF1 = Yii::app()->ePdf->mpdf('c', 'A4', '', '', 0, 0, 0, 0, 0, 0, '');

		// $mPDF1->SetDisplayMode('fullpage');
		// $mPDF1->list_indent_first_level = 0;

		// # renderPartial (only 'view' of current controller)
		// $mPDF1->WriteHTML($this->renderPartial('pdf', array(
		// 	'Datos' => $DatosProyecto,
		// 	'Productosproyecto' => $Productosproyecto,
		// 	// 'Empleadosproyecto' => $Empleadosproyecto,
		// 	'DatosCliente' => $DatosCliente,
		// 	// 'OrdenesdeCompra' => $OrdenesdeCompra,
		// 	'listaarchivos' => $listaarchivos,
		// 	// 'archivoscot' => $archivoscot
		// ), true));

		// # Outputs ready PDF
		// $mPDF1->Output('Proyecto-' . $id . '-' . date('Y') . '.pdf', 'I'); //Nombre del pdf y parámetro para ver pdf o descargarlo directamente.
		// exit;
	}

	/*
	 *	METODO PARA VER LA HOJA DE SALIDA EN PDF
	 *	CREADO POR DANIEL VILLARREAL EL 18 DE MARZO DEL 2016
	 */
	public function actionHojasalida($id)
	{
		// Obtenemos los datos del proyecto
		$DatosProyecto = Proyectos::model()->findByPk($id);
		$DatosCliente = Clientes::model()->findByPk($DatosProyecto->id_cliente);
		$Productosproyecto = Proyectosproductos::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto);
		/**************************************************************************/

		# mPDF
		$mPDF1 = Yii::app()->ePdf->mpdf();

		# You can easily override default constructor's params
		$mPDF1 = Yii::app()->ePdf->mpdf('c', 'A4', '', '', 0, 0, 0, 0, 0, 0, '');

		$mPDF1->SetDisplayMode('fullpage');
		$mPDF1->list_indent_first_level = 0;

		# renderPartial (only 'view' of current controller)
		$mPDF1->WriteHTML($this->renderPartial('hojasalida', array(
			'Datos' => $DatosProyecto,
			'Productosproyecto' => $Productosproyecto,
			'Empleadosproyecto' => $Empleadosproyecto,
			'DatosCliente' => $DatosCliente
		), true));

		# Outputs ready PDF
		$mPDF1->Output('Hoja Salida Proyecto-' . $id . '-' . date('Y') . '.pdf', 'I'); //Nombre del pdf y parámetro para ver pdf o descargarlo directamente.
		exit;
	}


	/*
	 *	METODO PARA AGREGAR UN COMENTARIO AL PROYECTO
	 *	CREADO POR DANIEL VILLARREAL EL 19 DE MAYO DEL 2017
	 */
	public function actionAgregarcomentario()
	{
		$usuario = Usuarios::model()->findByPk($_POST['Proyectoscomentarios']['id_usuario']);
		$model = new Proyectoscomentarios;
		$model->attributes = $_POST['Proyectoscomentarios'];
		$model->fecha_alta = date('Y-m-d H:i:s');
		$model->nombre = $usuario['Usuario_Nombre'];

		if ($model->save()) {

			Yii::app()->user->setFlash('success', 'Se guardo correctamente.');
		} else {

			/*print_r($model->getErrors());exit;*/
			Yii::app()->user->setFlash('warning', 'Ocurrio un error, favor de verificar los campos.');
		}

		// Redireccionamos de la pagina de donde viene

		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}
	/**
	 * Metodo para actualizar color y descipcion desde los detalles del pedido (Ver) 
	 * creado por 
	 * LARs 29/09/23
	 */

	public function actionActualizarcolorydesc()
	{
		// si value no esta vacio significa que hay un cambio

		if (!empty($_POST) && $_POST != null) {

			$id = isset($_POST['id']) ? $_POST['id'] : '';
			$color = isset($_POST['color']) ? $_POST['color'] : '';
			$colortapi = isset($_POST['colortapi']) ? $_POST['colortapi'] : '';
			$bode = isset($_POST['bodega']) ? $_POST['bodega'] : '';
			$desc = isset($_POST['desc']) ? $_POST['desc'] : '';
			$esext = isset($_POST['esext']) ? $_POST['esext'] : '';



			// traemos la tabla de donde haremos los cambios y la fila en base al id
			$Productosproyecto = Proyectosproductos::model()->find('id_proyectos_productos=' . $id);
			// if para validar si el dato que entro es diferente al de la tabla actualizamos y agregamos a recurrentes

			if ($color != $Productosproyecto->color) {
				$Productosproyecto->color = $color;
				if ($Productosproyecto->save()) {
					// verificamos si ya existe cambios anteriormente
					$guardarinfo = CatalogosRecurrentes::model()->find(
						array(
							'condition' => 'num =' . $id . ' and nombre = "color"',
							'order' => 'id_catalogo_recurrente desc'
						)
					);
					// si noesta vacio quiere decir que no hay data y guardamos
					if (!empty($guardarinfo && $guardarinfo->update == null)) {

						$guardarinfo->update = $color;

						$guardarinfo->save();
					} else {
						$guardarinfo = new CatalogosRecurrentes;
						$guardarinfo->id_grupo_recurrente = 30;
						$guardarinfo->num = $id;
						$guardarinfo->nombre = 'color';
						$guardarinfo->descripcion = $color;
						$guardarinfo->id_usuario = Yii::app()->user->id;
						$guardarinfo->eliminado = 0;
						$guardarinfo->fecha_alta = date("Y-m-d H:i:s");
						$guardarinfo->save();
					}
				}
			}
			if ($colortapi != $Productosproyecto->color_tapiceria) {

				$Productosproyecto->color_tapiceria = $colortapi;
				if ($Productosproyecto->save()) {
					// verificamos si ya existe cambios anteriormente
					$guardarinfo = CatalogosRecurrentes::model()->find(
						array(
							'condition' => 'num =' . $id . ' and nombre = "Color tapiceria"',
							'order' => 'id_catalogo_recurrente desc'
						)
					);
					// si noesta vacio quiere decir que no hay data y guardamos
					if (!empty($guardarinfo && $guardarinfo->update == null)) {

						$guardarinfo->update = $colortapi;

						$guardarinfo->save();
					} else {
						$guardarinfo = new CatalogosRecurrentes;
						$guardarinfo->id_grupo_recurrente = 35;
						$guardarinfo->num = $id;
						$guardarinfo->nombre = 'Color tapiceria';
						$guardarinfo->descripcion = $colortapi;
						$guardarinfo->id_usuario = Yii::app()->user->id;
						$guardarinfo->eliminado = 0;
						$guardarinfo->fecha_alta = date("Y-m-d H:i:s");
						$guardarinfo->save();
					}
				}
			}
			if ($bode != $Productosproyecto->bodega) {
				$Productosproyecto->bodega = $bode;
				if ($Productosproyecto->save()) {
					// verificamos si ya existe cambios anteriormente
					$guardarinfo = CatalogosRecurrentes::model()->find(
						array(
							'condition' => 'num =' . $id . ' and nombre = "bodega"',
							'order' => 'id_catalogo_recurrente desc'
						)
					);
					// si noesta vacio quiere decir que no hay data y guardamos
					if (!empty($guardarinfo) && $guardarinfo->update == null) {

						$guardarinfo->update = $bode;

						$guardarinfo->save();
					} else {
						$guardarinfo = new CatalogosRecurrentes;
						$guardarinfo->id_grupo_recurrente = 31;
						$guardarinfo->num = $id;
						$guardarinfo->nombre = 'bodega';
						$guardarinfo->descripcion = $bode;
						$guardarinfo->id_usuario = Yii::app()->user->id;
						$guardarinfo->eliminado = 0;
						$guardarinfo->fecha_alta = date("Y-m-d H:i:s");
						$guardarinfo->save();
					}
				}
			}
			if ($desc != $Productosproyecto->proyectos_productos_descripcion) {
				$Productosproyecto->proyectos_productos_descripcion = $desc;
				if ($Productosproyecto->save()) {
					// verificamos si ya existe cambios anteriormente
					$guardarinfo = CatalogosRecurrentes::model()->find(
						array(
							'condition' => 'num =' . $id . ' and nombre = "descripcion"',
							'order' => 'id_catalogo_recurrente desc'
						)
					);
					// si noesta vacio quiere decir que no hay data y guardamos
					if (!empty($guardarinfo) && $guardarinfo->update == null) {

						$guardarinfo->update = $desc;

						$guardarinfo->save();
					} else {
						$guardarinfo = new CatalogosRecurrentes;
						$guardarinfo->id_grupo_recurrente = 29;
						$guardarinfo->num = $id;
						$guardarinfo->nombre = 'descripcion';
						$guardarinfo->descripcion = $desc;
						$guardarinfo->id_usuario = Yii::app()->user->id;
						$guardarinfo->eliminado = 0;
						$guardarinfo->fecha_alta = date("Y-m-d H:i:s");
						$guardarinfo->save();
					}
				}
			}
			if ($esext != $Productosproyecto->especificaciones_extras) {

				$Productosproyecto->especificaciones_extras = $esext;
				if ($Productosproyecto->save()) {
					// verificamos si ya existe cambios anteriormente
					$guardarinfo = CatalogosRecurrentes::model()->find(
						array(
							'condition' => 'num =' . $id . ' and nombre = "Especificaciones extras"',
							'order' => 'id_catalogo_recurrente desc'
						)
					);
					// si noesta vacio quiere decir que no hay data y guardamos
					if (!empty($guardarinfo && $guardarinfo->update == null)) {

						$guardarinfo->update = $esext;

						$guardarinfo->save();
					} else {
						$guardarinfo = new CatalogosRecurrentes;
						$guardarinfo->id_grupo_recurrente = 36;
						$guardarinfo->num = $id;
						$guardarinfo->nombre = 'Especificaciones extras';
						$guardarinfo->descripcion = $esext;
						$guardarinfo->id_usuario = Yii::app()->user->id;
						$guardarinfo->eliminado = 0;
						$guardarinfo->fecha_alta = date("Y-m-d H:i:s");
						$guardarinfo->save();
					}
				}
			}

			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
		}
	}

	/**
	 * *Action para traer los cambios del producto 
	 * *lars->02/10/23
	 */

	public function actionCambiosprodcutover()
	{
		$response = array(); // Inicializa un arreglo para la respuesta

		//recibimos el id del producto en la fila
		$id = $_POST['id'];
		// verificamos que no este vacio y sea diferente de 0
		if (!empty($id) && $id != 0) {

			// traemos los cambios para guardarlos en variables y mostrarlos en el modal 
			$traercambios = CatalogosRecurrentes::model()->findAll('num=' . $id);
			// echo "<pre>";
			// print_r($traercambios);
			// echo "</pre>";
			// exit();
			// Llenamos la respuesta con los datos
			$response['success'] = true;
			$response['cambios'] = $traercambios;
			array_map(function ($e) {
				return $e['id_usuario'] = $e['rl_usuarios']['Usuario_Nombre'];
			}, $traercambios);

			// echo "<pre>";
			// print_r($traercambios);
			// echo "</pre>";
			// exit();

		} else {
			$response['success'] = false;
		}

		// Devolvemos la respuesta como JSON
		echo CJSON::encode($response);
		Yii::app()->end();
	}
	/**
	 * * Metodo para actualizar el estatus del prodcuto
	 * * LARS -> 06/10/23
	 */
	public function actionActualizarestatusp()
	{
		// si valor no esta vacio y en base al id buscamos en la 
		// tabla proyectos prodcutos y guardamos el estatus del producto
		if (!empty($_POST['value'])) {
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			$value = isset($_POST['value']) ? $_POST['value'] : '';

			$prodcutosPedido = Proyectosproductos::model()->find('id_proyectos_productos=' . $id);

			if (!empty($prodcutosPedido)) {

				$prodcutosPedido->estatus = $value;


				if ($prodcutosPedido->save()) {

					switch ($value) {
						case 218:
							$value = 'En espera';
							$prodcutosPedido->id_etapa = 218;
							$prodcutosPedido->save();
							break;
						case 219:
							$value = 'En fabricación';
							$prodcutosPedido->id_etapa = 219;
							$prodcutosPedido->save();
							break;
						case 220:
							$value = 'Terminado';
							$prodcutosPedido->id_etapa = 220;
							$prodcutosPedido->save();
							break;
						case 248:
							$value = 'Entregado';
							$prodcutosPedido->id_etapa = 248;
							$prodcutosPedido->save();
							break;
						default:
							break;
					}
					// verificamos si ya existe cambios anteriormente
					$guardarinfo = CatalogosRecurrentes::model()->find(
						array(
							'condition' => 'num =' . $id . ' and nombre = "Estatus"',
							'order' => 'id_catalogo_recurrente desc'
						)
					);

					// si noesta vacio quiere decir que no hay data y guardamos
					if (!empty($guardarinfo) && $guardarinfo->update == null) {

						$guardarinfo->update = $value;

						$guardarinfo->save();
					} else {
						$guardarinfo = new CatalogosRecurrentes;
						$guardarinfo->id_grupo_recurrente = 33;
						$guardarinfo->num = $id;
						$guardarinfo->nombre = 'Estatus';
						$guardarinfo->descripcion = $value;
						$guardarinfo->id_usuario = Yii::app()->user->id;
						$guardarinfo->eliminado = 0;
						$guardarinfo->fecha_alta = date("Y-m-d H:i:s");
						$guardarinfo->save();
					}
					echo CJSON::encode(
						array(
							'requestresult' => 'ok',
							'message' => 'La descripción se actualizo con exito'
						)
					);
				} else {
					echo CJSON::encode(
						array(
							'requestresult' => 'fail',
							'message' => 'Error al actualizar descripción'
						)
					);
				}
			}
		}
	}

	public function actionDatosaeditar()
	{
		$id = isset($_POST['id']) ? $_POST['id'] : '';

		$datos = Proyectosproductos::model()->find('id_proyectos_productos=' . $id);

		if (!empty($datos)) {
			$color = $datos['color'];
			$colortapi = $datos['color_tapiceria'];
			$descripcion = $datos['proyectos_productos_descripcion'];
			$especificaciones = $datos['especificaciones_extras'];
			$bode = $datos['bodega'];

			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Datos obtenidos con exito',
					'color' => $color,
					'colortapi' => $colortapi,
					'descipcion' => $descripcion,
					'espext' => $especificaciones,
					'bode' => $bode,
				)
			);
		} else {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Ocurrio un error',

				)
			);
		}
	}

	// al chile que sea lo que Dios quiera alv
	public function actionActprecioventa()
	{
		$productosCotizacion = Cotizacionesproductos::model()->findAll();
		foreach ($productosCotizacion as $pc) {
			$proyecto_productos = Proyectosproductos::model()->findAll();
			foreach ($proyecto_productos as $p) {
				if ($pc->id_producto == $p->id_producto) {
					$p->precio_venta_producto = $pc->cotizacion_producto_total / $pc->cotizacion_producto_cantidad;
					$p->save();
				}
			}
		}
	}


	// pdf del  pedido -> lars 21/11/23

	public function actionPdfpedido($id)
	{

		$VerificarAcceso = $this->VerificarAcceso(29, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}


		// Obtenemos los datos del proyecto
		$DatosProyecto = Proyectos::model()->findByPk($id);
		$DatosCliente = Clientes::model()->findByPk($DatosProyecto->id_cliente);
		$Productosproyecto = Proyectosproductos::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto);
		// $Empleadosproyecto = Proyectosempleados::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto);
		// $OrdenesdeCompra = Ordenesdecompra::model()->findall('id_proyecto=' . $DatosProyecto->id_proyecto);
		$DatosConfiguracion = Configuracion::model()->findBypk(1);

		// echo "<pre>";
		// print_r($Productosproyecto[0]['fecha_de_entrega']);
		// echo "</pre>";
		// exit();

		/**************************************************************************/
		$dompdf = new Dompdf();
		$options = $dompdf->getOptions();
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isRemoteEnabled', true);

		if (!empty($DatosProyecto)) {
			$fecha = date("d F Y", strtotime($DatosProyecto['proyecto_fecha_alta']));
			$fecha_formateada = $this->Fechaespañol($fecha);
		} else {
			$fecha_formateada = 'Sin Fecha';
		}

		if (!empty($Productosproyecto)) {
			$fecha_promesa = date("d F Y", strtotime($Productosproyecto[0]['fecha_de_entrega']));
			$fecha_formateada_promesa = $this->Fechaespañol($fecha_promesa);
		} else {
			$fecha_formateada_promesa = 'Sin fecha';
		}

		$html = $this->renderPartial('pdfpedido', array(
			'Datos' => $DatosProyecto,
			'Productosproyecto' => $Productosproyecto,
			'DatosCliente' => $DatosCliente,
			'fecha_promesa' => $fecha_formateada_promesa,
			'DatosConfiguracion' => $DatosConfiguracion,
			'fecha' => $fecha_formateada,
		), true);

		// return $this->render(
		// 	'pdfpedido',
		// 	array(
		// 		'Datos' => $DatosProyecto,
		// 		'Productosproyecto' => $Productosproyecto,
		// 		'DatosCliente' => $DatosCliente,
		// 		'fecha_promesa' => $fecha_formateada_promesa,
		// 		'DatosConfiguracion' => $DatosConfiguracion,
		// 		'fecha' => $fecha_formateada,


		// 	)
		// );
		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait'); //->tamaño y orientacion de la hoja
		// Render the HTML as PDF
		$dompdf->render();
		// paginacion
		$canvas = $dompdf->get_canvas();
		$canvas->page_text(520, 760, "Hoja {PAGE_NUM} - {PAGE_COUNT}", null, 12, array(0, 0, 0));
		$dompdf->stream($id . ' - ' . $DatosCliente['cliente_nombre'] . '.pdf', array('Attachment' => 0));
	}

	// funcion para traer la fecha de entrega

	public function Fechentrega($idp)
	{
		$fe = '';
		$pp = Proyectosproductos::model()->findAll('id_proyecto = ' . $idp);
		foreach ($pp as $p) {
			if (!empty($p['fecha_de_entrega'])) {
				$fe = $p['fecha_de_entrega'];
				break;
			} else {
				continue;
			}
		}
		$fe = $this->Fechacortada(date("d F Y", strtotime($fe)));
		return $fe;
	}

	// metodo para obtener el string del status lars 13/07/2025
	function statusString($status)
	{
		$stausString  = '';
		switch ($status) {
			case 0:
				$stausString = 'Generado';
				break;
			case 2:
				$stausString = 'En Producción';
				break;
			case 6:
				$stausString = 'Empacado';
				break;
			case 8:
				$stausString = 'Entregado';
				break;
			case 7:
				$stausString = 'Cancelado';
				break;
		}
		return $stausString;
	}

	// metodo para obtener la forma de pago -> lars 22/11/23

	public function Formap($idp)
	{
		$datos = [
			'fp' => '',
			'fecha' => '',
		];
		// obtenemos el dato de contabilidad ingreosos por medio del id de proyecto
		$ingreso = Contabilidadingresos::model()->findAll(
			array(
				'condition' => 'contabilidad_ingresos_identificador =  :id',
				'params' => array(':id' => 'Pedido - ' . $idp . '')
			)
		);
		if (!empty($ingreso)) {
			$formap = $ingreso[0]['rl_formasdepago']['formapago_nombre'];
			$fecha = $this->Fechacortada(date("d F Y", strtotime($ingreso[0]['contabilidad_ingresos_fechaalta'])));

			// dvb agregado para regresar las fechas de los demas pagos sin contar la primera
			$fechatxtfiniquito = '';
			$montotxtfiniquito = '';
			$fptxtfiniquito = '';
			foreach ($ingreso as $key => $rows) {
				if ($key == 0) {
					continue;
				}
				$fechatxtfiniquito .= $this->Fechacortada(date("d F Y", strtotime($rows['contabilidad_ingresos_fechaalta']))) . ',';
				$montotxtfiniquito .= '$' . number_format($rows['contabilidad_ingresos_cantidad'], 2) . ',';
				$fptxtfiniquito .= $rows['rl_formasdepago']['formapago_nombre'] . ',';
			}
			// eliminamos la ultima coma
			$fechatxtfiniquito = rtrim($fechatxtfiniquito, ',');
			$montotxtfiniquito = rtrim($montotxtfiniquito, ',');
			$fptxtfiniquito = rtrim($fptxtfiniquito, ',');
			//
			$datos = [
				'fp' => $formap,
				'fecha' => $fecha,
				'fechatxtfiniquito' => $fechatxtfiniquito,
				'montotxtfiniquito' => $montotxtfiniquito,
				'fptxtfiniquito' => $fptxtfiniquito,
			];
		}
		return $datos;
	}


	/* @jl 27/02/24
											  antiguo metodo para generar proyectos, se cambio por que agrupaba los productos en el proyecto,
											  y se cambio para que no lo haga*/
	// public function actionGenerarproyecto()
	// {

	// 	// echo "<pre>";
	// 	// print_r($_POST);
	// 	// echo "</pre>";
	// 	// exit();

	// 	$id_cotizacion = $_POST['id_cotizacion'];
	// 	#$id_almacen = $_POST['id_almacen'];
	// 	$proyecto_comentarios = $_POST['proyecto_comentarios'];
	// 	#$proyecto_supervisor =$_POST['proyecto_supervisor'];
	// 	// $proyecto_nombre = $_POST['proyecto_nombre'];
	// 	$localidad = $_POST['localidad'];
	// 	$tipoproyecto = isset($_POST['id_tipo_proyecto']) ? $_POST['id_tipo_proyecto'] : '';
	// 	// fecha de entrega, esta va pa los productos
	// 	$fecha = $_POST['fecha'];
	// 	$flete = isset($_POST['flete']) ? $_POST['flete'] : '';
	// 	// datos de la cotizacion
	// 	$DatosCot = Cotizaciones::model()->findBypk($id_cotizacion);
	// 	// Datos del cliente
	// 	$DatosCliente = Clientes::model()->findBypk($DatosCot->id_cliente);

	// 	// Encabezado del proyecto
	// 	$AgregarProyecto = new Proyectos;
	// 	$AgregarProyecto->id_cliente = $DatosCot->id_cliente;
	// 	// $AgregarProyecto->proyecto_nombre = $proyecto_nombre;
	// 	$AgregarProyecto->proyecto_total = $DatosCot->cotizacion_total;
	// 	$AgregarProyecto->proyecto_totalpagado = 0;
	// 	$AgregarProyecto->proyecto_totalpendiente = $DatosCot->cotizacion_total;
	// 	$AgregarProyecto->proyecto_estatus = 0; // El proyecto, nace como generado
	// 	$AgregarProyecto->proyecto_fecha_alta = date('Y-m-d H:i:s');
	// 	$AgregarProyecto->proyecto_ultima_modificacion = date('Y-m-d H:i:s');
	// 	$AgregarProyecto->proyecto_comentarios = $proyecto_comentarios;
	// 	$AgregarProyecto->id_usuario = Yii::app()->user->id;
	// 	$AgregarProyecto->tipo_cambio = $DatosCot->tipo_cambio;
	// 	$AgregarProyecto->total_peso = $DatosCot->total_peso;
	// 	$AgregarProyecto->id_tipo_proyecto = $tipoproyecto;
	// 	$AgregarProyecto->id_cotizacion = $DatosCot['id_cotizacion'];
	// 	$AgregarProyecto->localidad = $localidad;
	// 	$AgregarProyecto->proyecto_condiciones_generales = $flete;



	// 	// Una vez guardado el encabezado del proyecto, procedemos a guardar los productos del mismo
	// 	if ($AgregarProyecto->save()) {
	// 		$ultimoid = $AgregarProyecto['id_proyecto'];

	// 		$DatosCot->pedido = 1;
	// 		$DatosCot->save();

	// 		// Sumamos + 1 en la tabla de tipo proyectos el tipo seleccionado, columna serie_cotizacion
	// 		if (!empty($tipoproyecto)) {

	// 			$tipoproyecto = TipoProyectos::model()->findBypk($AgregarProyecto->id_tipo_proyecto);
	// 			$tipoproyecto->sere_proyecto = $tipoproyecto->sere_proyecto + 1;
	// 			$tipoproyecto->save();
	// 		}
	// 		// Actualizamos el cliente
	// 		$DatosCliente->cliente_tipo = 1;
	// 		$DatosCliente->save();

	// 		$numero_proyecto = date('Y') . '-' . $AgregarProyecto->id_tipo_proyecto . '-' . $AgregarProyecto->id_proyecto;
	// 		$AgregarProyecto->numero_proyecto = $numero_proyecto;
	// 		$AgregarProyecto->save();

	// 		// Obtenemos los productos de la cotizacion.
	// 		$ProductosCotizacion = Cotizacionesproductos::model()->findAll('id_cotizacion=' . $DatosCot->id_cotizacion);

	// 		// Primero Guardamos los productos finales
	// 		foreach ($ProductosCotizacion as $rows) {
	// 			// primero sacamos el id del prodcuto para ir al prodcuto y sacar su tipo

	// 			$bodegaFabricacion = CatalogosRecurrentes::model()->find(
	// 				array(
	// 					'condition' => 'eliminado=0 and id_grupo_recurrente=28 and id_catalogo_recurrente=:id_bodega',
	// 					'params' => array(':id_bodega' => $rows['rl_producto']['id_bodega_fabricacion'])
	// 				)
	// 			);


	// 			$Proyectoproductos = new Proyectosproductos;
	// 			$Proyectoproductos->id_proyecto = $AgregarProyecto->id_proyecto;
	// 			$Proyectoproductos->id_producto = $rows->id_producto;
	// 			$Proyectoproductos->proyectos_productos_cantidad = $rows->cotizacion_producto_cantidad;
	// 			$Proyectoproductos->proyectos_productos_descripcion = $rows->cotizacion_producto_descripcion;
	// 			$Proyectoproductos->color = $rows->color;
	// 			$Proyectoproductos->proyectos_productos_cantidad_surtida = 0;
	// 			$Proyectoproductos->tipo_producto = $rows['rl_producto']['tipo'];
	// 			$Proyectoproductos->bodega = $bodegaFabricacion['id_catalogo_recurrente'];
	// 			$Proyectoproductos->estatus = 1;
	// 			$Proyectoproductos->id_etapa = 218;
	// 			$Proyectoproductos->color_tapiceria = $rows['color_tapiceria'];
	// 			$Proyectoproductos->especificaciones_extras = $rows['especificaciones_extras'];
	// 			$Proyectoproductos->fecha_alta = date('Y-m-d H:i:s');
	// 			$Proyectoproductos->fecha_de_entrega = (!empty($fecha)) ? $fecha : null;
	// 			$Proyectoproductos->precio_venta_producto = $rows->cotizacion_producto_total / $rows->cotizacion_producto_cantidad;
	// 			$Proyectoproductos->save();
	// 		}
	// 		// Obtenemos los archivos en la cotizacion para asignarlos al proyecto
	// 		$ArchivosCot = Cotizacionesarchivos::model()->findall('id_cotizacion=' . $id_cotizacion);
	// 		foreach ($ArchivosCot as $rowsa) {
	// 			$addarchivo = new Proyectosarchivos;
	// 			$addarchivo->id_proyecto = $AgregarProyecto->id_proyecto;
	// 			$addarchivo->proyectos_archivos_archivo = $rowsa->cotizacion_archivo;
	// 			$addarchivo->proyectos_archivos_nombre = $rowsa->cotizacion_archivo_nombre;
	// 			$addarchivo->save();
	// 		}
	// 		// Metodo para juntar los productos iguales y sumar las cantidades	
	// 		RActiveRecord::getAdvertDbConnection();
	// 		$Productosenelproyectolimpios = Yii::app()->dbadvert->createCommand(
	// 			'SELECT id_proyecto,id_producto,id_etapa,color,proyectos_productos_descripcion,abierto,estatus,proyectos_productos_cantidad_surtida,tipo_producto,bodega,color_tapiceria,especificaciones_extras, sum(proyectos_productos_cantidad) as cantidad_real, precio_venta_producto,fecha_alta,fecha_de_entrega
	// 			FROM proyectosproductos where id_proyecto = ' . $AgregarProyecto->id_proyecto . ' group by id_producto, color order by id_producto'
	// 			// FROM proyectosproductos where id_proyecto = ' . $AgregarProyecto->id_proyecto . ' group by id_producto order by id_producto'
	// 		)->queryAll();
	// 		// Eliminamos los productos desordenados
	// 		Proyectosproductos::model()->deleteAll('id_proyecto =' . $AgregarProyecto->id_proyecto);
	// 		// Insertamos los productos
	// 		$costoProyectoProducto = 0;
	// 		foreach ($Productosenelproyectolimpios as $rows) {
	// 			$Proyectoproductos = new Proyectosproductos;
	// 			// si el prodcuto es tipo stock (1) vamos al stock para restarle la cantidad y actualizar el stock 
	// 			$traerstock = SucursalesProductos::model()->find(
	// 				array(
	// 					'condition' => 'id_producto=:id_producto',
	// 					'params' => array(':id_producto' => $rows['id_producto']),
	// 					'order' => 'cantidad_stock desc'
	// 				)
	// 			);

	// 			$producto = Productos::model()->findByPk($rows['id_producto']);
	// 			// stock antes de save
	// 			$stock_anterior = $traerstock['cantidad_stock'];
	// 			if ($rows['tipo_producto'] == 1) {

	// 				// verificamos si la cantidad de estock es mayor 
	// 				if ($traerstock['cantidad_stock'] > $rows['cantidad_real']) {
	// 					// reducimos cantidad
	// 					$traerstock['cantidad_stock'] = $traerstock['cantidad_stock'] - $rows['cantidad_real'];
	// 					$traerstock->save();
	// 					// ya que se resto la cantidad se guardara el movimiento en sucursales movimiento
	// 					$movimiento = new SucursalesMovimientos;
	// 					$movimiento->id_sucursal = $traerstock['id_sucursal'];
	// 					$movimiento->id_producto = $traerstock['id_producto'];
	// 					$movimiento->tipo = 2;
	// 					$movimiento->tipo_identificador = 23;
	// 					$movimiento->id_identificador = $ultimoid;
	// 					$movimiento->cantidad_stock_antes = $stock_anterior;
	// 					$movimiento->cantidad_mov = $rows['cantidad_real'];
	// 					$movimiento->cantidad_stock_final = $traerstock['cantidad_stock'];
	// 					$movimiento->fecha_movimiento = date('Y-m-d H:i:s');
	// 					$movimiento->id_usuario = Yii::app()->user->id;

	// 					$movimiento->eliminado = 0;
	// 					$movimiento->comentarios = 'Movimiento por pedido';
	// 					// $movimiento->folio_rsi = $folio_rsi;
	// 					if (!$movimiento->save()) {
	// 						throw new Exception("No se  guardo la actualización del movimiento.");
	// 					}



	// 					$Proyectoproductos->salio_stock = $rows['cantidad_real'];
	// 					$Proyectoproductos->cantidad_salida = 1;
	// 				} else {
	// 					$Proyectoproductos->salio_stock = 0;
	// 					$Proyectoproductos->salio_stock = $rows['cantidad_real'];
	// 				}
	// 			}
	// 			$Proyectoproductos->id_proyecto = $rows['id_proyecto'];
	// 			$Proyectoproductos->id_producto = $rows['id_producto'];
	// 			$Proyectoproductos->proyectos_productos_cantidad = $rows['cantidad_real'];
	// 			$Proyectoproductos->proyectos_productos_descripcion = $rows['proyectos_productos_descripcion'];
	// 			$Proyectoproductos->color = $rows['color'];
	// 			$Proyectoproductos->color_tapiceria = $rows['color_tapiceria'];
	// 			$Proyectoproductos->especificaciones_extras = $rows['especificaciones_extras'];
	// 			$Proyectoproductos->bodega = $rows['bodega'];
	// 			$Proyectoproductos->estatus = $rows['estatus'];
	// 			$Proyectoproductos->fecha_de_entrega = $rows['fecha_de_entrega'];
	// 			$Proyectoproductos->id_etapa = $rows['id_etapa'];
	// 			$Proyectoproductos->fecha_alta = $rows['fecha_alta'];
	// 			$Proyectoproductos->precio_venta_producto = $rows['precio_venta_producto'];

	// 			#$Proyectoproductos->id_almacen = $rows['id_almacen'];
	// 			$Proyectoproductos->proyectos_productos_cantidad_surtida = $rows['proyectos_productos_cantidad_surtida'];
	// 			$Proyectoproductos->tipo_producto = $rows['tipo_producto'];

	// 			// agregamos el costo del producto en base la utilidad
	// 			$Proyectoproductos->costo = ($rows['precio_venta_producto'] * $rows['cantidad_real']) * ($producto['utilidad'] / 100);

	// 			$desc = $Proyectoproductos->proyectos_productos_descripcion;
	// 			$color = $Proyectoproductos->color;
	// 			$bode = $Proyectoproductos->bodega;
	// 			$colortapi = $Proyectoproductos->color_tapiceria;
	// 			$espext = $Proyectoproductos->especificaciones_extras;
	// 			$estatus = $Proyectoproductos->estatus;

	// 			if ($Proyectoproductos->save()) {
	// 				$costoProyectoProducto += $Proyectoproductos->costo;

	// 				if (!empty($desc)) {
	// 					$guardarenrecurrentes = new CatalogosRecurrentes;
	// 					$guardarenrecurrentes->num = $Proyectoproductos->id_proyectos_productos;
	// 					$guardarenrecurrentes->nombre = 'descripcion';
	// 					$guardarenrecurrentes->descripcion = $desc;
	// 					$guardarenrecurrentes->eliminado = 0;
	// 					$guardarenrecurrentes->id_grupo_recurrente = 29;
	// 					$guardarenrecurrentes->id_usuario = Yii::app()->user->id;
	// 					$guardarenrecurrentes->fecha_alta = date('Y-m-d H:i:s');
	// 					$guardarenrecurrentes->save();
	// 				}
	// 				if (!empty($colortapi)) {
	// 					$guardarenrecurrentes = new CatalogosRecurrentes;
	// 					$guardarenrecurrentes->num = $Proyectoproductos->id_proyectos_productos;
	// 					$guardarenrecurrentes->nombre = 'Color tapiceria';
	// 					$guardarenrecurrentes->descripcion = $colortapi;
	// 					$guardarenrecurrentes->eliminado = 0;
	// 					$guardarenrecurrentes->id_grupo_recurrente = 35;
	// 					$guardarenrecurrentes->id_usuario = Yii::app()->user->id;
	// 					$guardarenrecurrentes->fecha_alta = date('Y-m-d H:i:s');
	// 					$guardarenrecurrentes->save();
	// 				}
	// 				if (!empty($espext)) {
	// 					$guardarenrecurrentes = new CatalogosRecurrentes;
	// 					$guardarenrecurrentes->num = $Proyectoproductos->id_proyectos_productos;
	// 					$guardarenrecurrentes->nombre = 'Especificaciones extras';
	// 					$guardarenrecurrentes->descripcion = $espext;
	// 					$guardarenrecurrentes->eliminado = 0;
	// 					$guardarenrecurrentes->id_grupo_recurrente = 36;
	// 					$guardarenrecurrentes->id_usuario = Yii::app()->user->id;
	// 					$guardarenrecurrentes->fecha_alta = date('Y-m-d H:i:s');
	// 					$guardarenrecurrentes->save();
	// 				}

	// 				if (!empty($bode)) {
	// 					$guardarenrecurrentes = new CatalogosRecurrentes;
	// 					$guardarenrecurrentes->num = $Proyectoproductos->id_proyectos_productos;
	// 					$guardarenrecurrentes->nombre = 'bodega';
	// 					$guardarenrecurrentes->descripcion = $bode;
	// 					$guardarenrecurrentes->eliminado = 0;
	// 					$guardarenrecurrentes->id_grupo_recurrente = 31;
	// 					$guardarenrecurrentes->id_usuario = Yii::app()->user->id;
	// 					$guardarenrecurrentes->fecha_alta = date('Y-m-d H:i:s');
	// 					$guardarenrecurrentes->save();
	// 				}
	// 				if (!empty($color)) {
	// 					$guardarenrecurrentes = new CatalogosRecurrentes;
	// 					$guardarenrecurrentes->num = $Proyectoproductos->id_proyectos_productos;
	// 					$guardarenrecurrentes->nombre = 'color';
	// 					$guardarenrecurrentes->descripcion = $color;
	// 					$guardarenrecurrentes->eliminado = 0;
	// 					$guardarenrecurrentes->id_grupo_recurrente = 30;
	// 					$guardarenrecurrentes->id_usuario = Yii::app()->user->id;
	// 					$guardarenrecurrentes->fecha_alta = date('Y-m-d H:i:s');
	// 					$guardarenrecurrentes->save();
	// 				}
	// 			}
	// 		}

	// 		$AgregarProyecto->costo = $costoProyectoProducto;
	// 		$AgregarProyecto->save();
	// 		// Termina
	// 		// si forma de pago y monto n estan vacios queire decir que agreagron un ongreso
	// 		$formap = $_POST['fomapago'];
	// 		$monto = $_POST['monto'];
	// 		//si no esta vacio ingresaron monto
	// 		if (!empty($formap) && !empty($monto)) {
	// 			// si no esta vacio ingresamos en ingresos 
	// 			$ingreso = new Contabilidadingresos;

	// 			$ingreso->id_formapago = $formap;
	// 			$ingreso->id_usuario = Yii::app()->user->id;
	// 			$ingreso->contabilidad_ingresos_identificador = 'Pedido - ' . $ultimoid;
	// 			$ingreso->contabilidad_ingresos_cantidad = $monto;
	// 			$ingreso->contabilidad_ingresos_fechaalta = date('Y-m-d H:i:s');
	// 			$ingreso->id_moneda = 1;
	// 			$ingreso->pendiente = $AgregarProyecto['proyecto_totalpendiente'] - $monto;
	// 			if ($ingreso->save()) {
	// 				// agregamos al proyecto lo que se pago
	// 				$proyecto = Proyectos::model()->findByPk($ultimoid);

	// 				$proyecto->proyecto_totalpagado = $monto;
	// 				$proyecto->proyecto_totalpendiente = $proyecto->proyecto_total - $monto;
	// 				$proyecto->save();
	// 			}
	// 		}


	// 		// En caso de que la cotizacion sea de una oportunidad, actualizamos el estatus de la oportunindad a ganada.
	// 		if ($DatosCot->id_oportunidad > 0) {
	// 			$actOportunidad = CrmOportunidades::model()->findBypk($DatosCot->id_oportunidad);
	// 			$actOportunidad->estatus = 'GANADO';
	// 			$actOportunidad->fecha_ultima_modificacion = date('Y-m-d H:i:s');
	// 			$actOportunidad->save();
	// 		}

	// 		// Obtenemos en base al cliente el tipo de frecuencia, la cantidad de dias y el nombre del dia.
	// 		$DatosFrecuencia = ClientesFrecuencias::model()->find('id_cliente=' . $AgregarProyecto->id_cliente);
	// 		if (!empty($DatosFrecuencia)) {
	// 			// Obtenemos la primera etapa
	// 			$Etapa = Crmetapas::model()->find(array('condition' => '', 'order' => 'orden asc'));
	// 			// Quiere decir que existe el registro en la tabla.
	// 			$FechaPedido = date('Y-m-d H:i:s');
	// 			// Obtenemos el numerode dias en base a la frencuencia
	// 			$Dias = $DatosFrecuencia->rl_frecuencia->num;
	// 			$Nombre = $DatosFrecuencia->nombre_dia;
	// 			$FechaOportunidadNueva = $this->ObtenerSigDia($Nombre, $this->Sumardiasfecha($FechaPedido, $Dias));
	// 			// Insertamos una nueva oportunidad y una nueva accion
	// 			$OportunidadRecurrente = new CrmOportunidades;
	// 			$OportunidadRecurrente->id_cliente = $AgregarProyecto->id_cliente;
	// 			$OportunidadRecurrente->valor_negocio = $AgregarProyecto->proyecto_total;
	// 			$OportunidadRecurrente->id_etapa = $Etapa->id;
	// 			$OportunidadRecurrente->id_usuario = Yii::app()->user->id;
	// 			$OportunidadRecurrente->nombre = 'Recurrente ' . $AgregarProyecto->proyecto_nombre;
	// 			$OportunidadRecurrente->estatus = 'SEGUIMIENTO';
	// 			$OportunidadRecurrente->fecha_alta = date('Y-m-d H:i:s');
	// 			$OportunidadRecurrente->fecha_ultima_modificacion = date('Y-m-d H:i:s');
	// 			$OportunidadRecurrente->tipo_oportunidad = 1;
	// 			if ($OportunidadRecurrente->save() && $Dias > 0) {
	// 				// Obtenemos la primera etapa
	// 				$Accion = Crmacciones::model()->find(array('condition' => 'crm_acciones_nombre LIKE "%llamada%"'));
	// 				// Guardamos una accion a la oportunidad creada
	// 				$AccionRecurrente = new Crmdetalles;
	// 				$AccionRecurrente->id_oportunidad = $OportunidadRecurrente->id;
	// 				$AccionRecurrente->id_cliente = $AgregarProyecto->id_cliente;
	// 				$AccionRecurrente->id_crm_acciones = $Accion['id_crm_acciones'];
	// 				$AccionRecurrente->crm_detalles_comentarios = 'Accion agregada automaticamente.';
	// 				$AccionRecurrente->crm_detalles_fecha_alta = date('Y-m-d H:i:s');
	// 				$AccionRecurrente->crm_detalles_usuario_alta = Yii::app()->user->id;
	// 				$AccionRecurrente->crm_detalles_estatus = 1;
	// 				$AccionRecurrente->crm_detalle_ultima_modificacion = date('Y-m-d H:i:s');
	// 				$AccionRecurrente->crm_detalles_fecha = $FechaOportunidadNueva . ' ' . date('H:i:s');
	// 				$AccionRecurrente->estatus = 'NO REALIZADO';
	// 				$AccionRecurrente->save();
	// 			}
	// 		}

	// 		// dvb 17 01 2024 insertamos en notificaciones
	// 		$nuevanot = new ProyectosNotificaciones;
	// 		$nuevanot->notificacionnuevoproyecto($AgregarProyecto->id_proyecto);
	// 		//

	// 		echo CJSON::encode(
	// 			array(
	// 				'requestresult' => 'ok',
	// 				'message' => 'Pedido generado, correctamente ' . $AgregarProyecto->id_proyecto,
	// 				'id_proyecto' => $AgregarProyecto->id_proyecto
	// 			)
	// 		);
	// 	} else {


	// 		echo CJSON::encode(
	// 			array(
	// 				'requestresult' => 'error',
	// 				'message' => 'Ocurrio un error inesperado al generar el pedido.',
	// 			)
	// 		);
	// 	}
	// }
}
