<?php

require_once(__DIR__ . '/../vendor/dompdf/autoload.inc.php');
// require_once(__DIR__ . '/../vendor/mpdf');
use Dompdf\Dompdf;
use Mpdf\Utils\Arrays;

class ContabilidadingresosController extends Controller
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

	public function actionIndex()
	{
		$VerificarAcceso = $this->VerificarAcceso(14, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		$permiso = $this->VerificarAcceso(30, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		// $id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';
		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_last_three_month_day();
		// $fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();

		$id_usuario = (isset($_GET['id_usuario'])) ? $_GET['id_usuario'] : '';
		$id_bodega = (isset($_GET['id_bodega'])) ? $_GET['id_bodega'] : 0;
		$nombreUsuario = (isset($_GET['nombreusuario'])) ? $_GET['nombreusuario'] : '';

		// Obtenemos todos los egresos por el rango de fechas
		// if (!empty($id_cliente)) {
		// 	$params = 'id_cliente = ' . $id_cliente . ' ORDER BY id_cotizacion DESC';
		// } else {
		$params = 'contabilidad_ingresos_fechaalta between "' . $fechainicio . ' 00:00:00"  and "' . $fechafin . ' 23:59:59" ';

		// modificar en server
		if (!empty($id_usuario)) {

			$params .= 'and id_usuario = ' . $id_usuario . '';
		} else {

			// $parametros .= ' and id_usuario in (' . $usuarioshijos . ') ';

		}


		// modificar server
		if (!empty($id_bodega)) {
			$params .= ' and  id_usuario IN (SELECT ID_Usuario FROM usuarios WHERE bodega = ' . $id_bodega . ')';
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
		// }
		$ListaIngresos = Contabilidadingresos::model()->findAll($params);

		// obtenemos las formas de pago lars 25/08/25
		$formaspago  = Formasdepago::model()->findAll();




		$this->render(
			'index',
			array(
				'ListaIngresos' => $ListaIngresos,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin,
				'permiso' => $permiso,
				'formapago' => $formaspago,
				'bodegas' => $bodegas,
				'nombre' => $nombreUsuario,
				'id_bodega' => $id_bodega
			)
		);
	}

	public function actionAgregar()
	{

		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit();
		// En base al identificador, obtenemos el proceso
		$Identificador = explode("-", $_POST['Contabilidadingresos']['contabilidad_ingresos_identificador']);
		$Pago = $_POST['Contabilidadingresos']['contabilidad_ingresos_cantidad'];
		switch (trim($Identificador['0'])) {
			case 'Pedido':
				$DatosProyecto = Proyectos::model()->findbypk($Identificador['1']);

				if ($DatosProyecto->proyecto_totalpendiente >= $Pago) {

					// Actualizamos los totales de la Orden pago proyecto
					$DatosProyecto->proyecto_totalpagado = $DatosProyecto->proyecto_totalpagado + $Pago;
					$DatosProyecto->proyecto_totalpendiente = $DatosProyecto->proyecto_totalpendiente - $Pago;
					$DatosProyecto->proyecto_ultima_modificacion = date('Y-m-d H:i:s');
					if ($DatosProyecto->save()) {
						$this->AgregarPago($_POST['Contabilidadingresos'], $DatosProyecto['proyecto_totalpendiente']);
						Yii::app()->user->setFlash('success', 'Se agrego con exito.');
					} else {
						Yii::app()->user->setFlash('warning', 'Ocurrio un error inesperado.');
					}
				} else {
					Yii::app()->user->setFlash('warning', 'No se agrego el pago.');
				}
				break;
		} // termina el switch

		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	public function AgregarPago($ArrayDatos, $pendeinte = 0)
	{
		// Insertamos el movimiento en contabilidadingresos
		$agregar = new Contabilidadingresos;
		$agregar->attributes = $ArrayDatos;
		$agregar->id_usuario = Yii::app()->user->id;
		$agregar->contabilidad_ingresos_fechaalta = date('Y-m-d H:i:s');
		$agregar->pendiente = $pendeinte;
		if ($agregar->save()) {
			Yii::app()->user->setFlash('success', 'Se guardo correctamente.');
		} else {
			Yii::app()->user->setFlash('warning', 'ERROR, ocurrio un error inesperado.');
		}
	}



	public function actionPendientespago()
	{
		$VerificarAcceso = $this->VerificarAcceso(21, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}

		$id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';
		$parametros = '';

		if (!empty($id_cliente)) {
			$parametros = 'proyecto_totalpendiente>0 and id_cliente = ' . $id_cliente;
		} else {
			$parametros = 'proyecto_totalpendiente>0';
		}
		if (isset($_GET['id_usuario'])) {
			$idus = $_GET['id_usuario'];
			$parametros .= ' and id_usuario=' . $idus;
		}
		$ListaProyectospp = Proyectos::model()->findAll($parametros);

		// Obtenemos los proyectos con total pendiente.

		// modelo de contabilidad ingresos
		$model = new Contabilidadingresos;

		$DatosProyecto = Proyectos::model()->findAll();


		// Lista de bancos
		$ListaBancos = CHtml::listData(Bancos::model()->findAll(), 'id_banco', 'banco_nombre');

		// Lista de formas de pago
		$ListaFormasPago = CHtml::listData(Formasdepago::model()->findAll(), 'id_formapago', 'formapago_nombre');

		// Lista de monedas
		$ListaMonedas = CHtml::listData(Monedas::model()->findAll(), 'id_moneda', 'moneda_nombre');

		$this->render(
			'pendientespago',
			array(
				'ListaProyectospp' => $ListaProyectospp,
				'ListaBancos' => $ListaBancos,
				'ListaFormasPago' => $ListaFormasPago,
				'DatosProyecto' => $DatosProyecto,
				'model' => $model,
				'ListaMonedas' => $ListaMonedas
			)
		);
	}

	public function actionUpdate()
	{
		$ingresos = Contabilidadingresos::model()->findAll();
		foreach ($ingresos as $i) {
			$texto = $i['contabilidad_ingresos_identificador'];
			// como no tenemos columna del id proyecto lo sacamos del identificador
			if (preg_match('/\d+/', $texto, $coincidencias)) {
				$id_proyecto = $coincidencias[0];
			}
			$i->contabilidad_ingresos_identificador = 'Pedido - ' . $id_proyecto;
			$i->save();
		}
	}

	public function actionPdf()
	{
		if (!empty($_GET)) {
			$id = isset($_GET['id']) ? $_GET['id'] : '';
			// obtenemos el registro de la tabla con el id
			$ingreso = Contabilidadingresos::model()->find('id_contabilidad_ingresos = ' . $id);
			$texto = $ingreso['contabilidad_ingresos_identificador'];
			// como no tenemos columna del id proyecto lo sacamos del identificador
			if (preg_match('/\d+/', $texto, $coincidencias)) {
				$id_proyecto = $coincidencias[0];
			}
			// obtenemos los datos del proyecto para mostrar 
			$proyecto = Proyectos::model()->find('id_proyecto = ' . $id_proyecto);
			// del proyecto sacamos el total pendiente-> lars 17/01/24
			/// para saber si es anticipo o pago buscaremos todos los ingresos en base al pryecto 
			// demaneresa desc y traeremops unp, si el id que traemos es igual al que nos mandan quiere decir que tramos el ultimo registro 
			// y si este proyecto tiene total pendiente es anticipo caso contrario finiquito si el id no es igual entonces fue un anticipo
			$ingresos = Contabilidadingresos::model()->find(
				array(
					'condition' => 'contabilidad_ingresos_identificador like :proy',
					'params' => array(':proy' => '%' . $id_proyecto . '%'),
					'order' => 'id_contabilidad_ingresos desc'
				)
			);

			$palabra = '';
			if ($ingresos['id_contabilidad_ingresos'] != $id) {
				$palabra = 'ANTICIPO';
			} elseif (($proyecto['proyecto_totalpendiente'] > 0 && $ingresos['id_contabilidad_ingresos'] == $id)) {
				$palabra = 'ANTICIPO';
			} else if ($ingresos['id_contabilidad_ingresos'] == $id && $proyecto['proyecto_totalpendiente'] == 0) {

				$palabra = 'FINIQUITO';
			}

			// traemos los prodcutos del proyecto al que se le esta pagando por medio del id proyecto
			$prodcutos = Proyectosproductos::model()->findAll('id_proyecto = ' . $id_proyecto);
			$productosa = [];


			// exit();
			// necesitamos saber si el id que recewbimos es el ultimo id que se registro de ser asi sera el pago final y llevara el nombre de finiquito ->lars 05/01/2024
			// $pago = Contabilidadingresos::model()->find(
			// 	array(
			// 		'condition' => 'contabilidad_ingresos_identificador like :idp',
			// 		'params' => array(':idp' => "%" . $id_proyecto . "$"),
			// 		'order' => 'id_contabilidad_ingresos desc',
			// 		'limit' => 1
			// 	)
			// );

			$sql = 'SELECT id_contabilidad_ingresos FROM contabilidadingresos where contabilidad_ingresos_identificador LIKE "%' . $id_proyecto . '%" ORDER BY id_contabilidad_ingresos DESC LIMIT 1';
			RActiveRecord::getAdvertDbConnection();
			$command = Yii::app()->dbadvert->createCommand($sql);
			// $command->bindParam(":id", $id, PDO::PARAM_STR);
			$pago = $command->queryScalar();
			$idsql = $pago;

			// if ($id == $idsql) {
			// 	// si entra quiere decir que tenemos el ultimo pago y sera finiquito
			// 	$palabra = 'FINIQUITO';
			// } else {
			// 	// si no son iguales quiere decir que es un anticipo
			// 	$palabra = 'ANTICIPO';
			// }


			// $cantidad_productos = [];


			foreach ($prodcutos as $producto) {
				$productosa[] = [

					'cantidad' => $producto['proyectos_productos_cantidad'],
					'nombre' => $producto['rl_producto']['producto_nombre'],
					'color' => $producto['color'],
					'colortapi' => $producto['color_tapiceria'],
					'fecha' => $producto['fecha_de_entrega'],
					'esex' => $producto['especificaciones_extras'],

				];
			}

			// echo "<pre>";
			// print_r($ingreso);
			// echo "</pre>";
			// exit();

			$DatosConfiguracion = Configuracion::model()->findBypk(1);

			$fecha_formateada = $this->Fechaespañol(date("d F Y", strtotime($ingreso['contabilidad_ingresos_fechaalta'])));

			// PDF
			// return $this->render('pdf', [
			// 	'ingreso' => $ingreso,
			// 	'proyecto' => $proyecto,
			// 	'DatosConfiguracion' => $DatosConfiguracion,
			// 	'fecha_formateada' => $fecha_formateada,
			// 	'productos' => $productos,
			// 	'palabra' => $palabra
			// ]);
			$dompdf = new Dompdf();
			$html = $this->renderPartial('pdf', array(
				'ingreso' => $ingreso,
				'proyecto' => $proyecto,
				'DatosConfiguracion' => $DatosConfiguracion,
				'fecha_formateada' => $fecha_formateada,
				'productos' => $productosa,
				'palabra' => $palabra

			), true);

			$dompdf->loadHtml($html);

			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('letter', 'portrait'); //->tamaño y orientacion de la hoja

			// Render the HTML as PDF
			$dompdf->render();

			// paginacion
			$canvas = $dompdf->get_canvas();
			$canvas->page_text(520, 750, "Hoja {PAGE_NUM} - {PAGE_COUNT}", null, 12, array(0, 0, 0));


			$dompdf->stream($id . ' - ' . $proyecto['rl_clientes']['cliente_nombre'] . '.pdf', array('Attachment' => 0));
		}
	}

	public function actionKpiingresos()
	{
		// phpinfo();
		// exit;
		$id = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : '';
		$ingresos = [];
		$usuario = [
			'Usuario_Nombre' => ''
		];

		// si el id_usuario no esta vacio buscamos los ingresos con ese id
		if (!empty($id)) {

			$usuario = Usuarios::model()->find('id_usuario = ' . $id);

			$sql = 'SELECT COUNT(contabilidad_ingresos_identificador) AS cantidad_proyectos, SUM(contabilidad_ingresos_cantidad) AS proyectos_vendidos,id_usuario as usuario 
			FROM contabilidadingresos WHERE id_usuario = ' . $id;
			RActiveRecord::getAdvertDbConnection();
			$command = Yii::app()->dbadvert->createCommand($sql);
			// $command->bindParam(":id", $id, PDO::PARAM_STR);
			$ingresos = $command->queryAll();

			// neccesitamos revisar si tiene proyectos cancelados para restarlos
			//traemos los ingresos del usuario para sacar el id proyecto
			$ingresosusuario = Contabilidadingresos::model()->findAll('id_usuario = ' . $id);
			// con un for each revisamos si el proyecto esta cancelado y asi restamos la cantidad del ingreso de ese proyecto
			foreach ($ingresosusuario as $iu) {
				$texto = $iu['contabilidad_ingresos_identificador'];
				// como no tenemos columna del id proyecto lo sacamos del identificador
				if (preg_match('/\d+/', $texto, $coincidencias)) {
					$id_proyecto = $coincidencias[0];
				}
				// obtenemos los datos del proyecto para revisar el estatus
				$proyecto = Proyectos::model()->find('id_proyecto = ' . $id_proyecto);

				if ($proyecto['proyecto_estatus'] == 7) {
					$ingresos[0]['proyectos_vendidos'] = $ingresos[0]['proyectos_vendidos'] - $iu['contabilidad_ingresos_cantidad'];
				} else {
					continue;
				}
			}
		}


		$this->render(
			'kpi',
			[
				'ingresos' => $ingresos,
				'usuario' => $usuario
			]
		);
	}


	// action para buscar por usuario y obtener sus ventas ->lars 13/11/23


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

	/**
	 * confiramr el ingreso
	 * dvb 23 11 2023
	 */
	public function actionConfirmar()
	{
		$VerificarAcceso = $this->VerificarAcceso(30, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No cuenta con permisos para esta accion',
				)
			);
			// Lo redireccionamos a la pagina acceso restringido
			// $this->redirect(Yii::app()->createURL('site/noautorizado'));
		} else {
			$id = $_POST['id'];
			$dato = $_POST['dato'];

			$ingreso = Contabilidadingresos::model()->find('id_contabilidad_ingresos = ' . $id);
			$ingreso->ingreso_confirmado = 1;
			$ingreso->no_banca = $dato;
			$ingreso->confirmado_fecha = date('Y-m-d H:i:s');
			$ingreso->confirmado_usuario = Yii::app()->user->id;
			if ($ingreso->save()) {
				echo CJSON::encode(
					array(
						'requestresult' => 'ok',
						'message' => 'Actualizado correctamente',
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
	}

	public function actionReporteIngresosCostos()
	{
		// Verificamos el acceso al modulo
		$VerificarAcceso = $this->VerificarAcceso(19, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		try {
			RActiveRecord::getAdvertDbConnection();
			$anioSelected = (isset($_POST['anioSelected'])) ? $_POST['anioSelected'] : date("Y");

			// obtenemos los anios en los que hay ingresos
			$sqlAños = 'SELECT DISTINCT YEAR(contabilidad_ingresos_fechaalta) as anio FROM contabilidadingresos WHERE ingreso_confirmado=1 order by contabilidad_ingresos_fechaalta desc ';
			$aniosIngresos = Yii::app()->dbadvert->createCommand($sqlAños)->queryAll();

			// obtenemos los ingresos generados en el anio dado, agrupados por mes
			$sqlSumaDeingresosPorMes = 'SELECT SUM(contabilidad_ingresos_cantidad) as total,Month(contabilidad_ingresos_fechaalta) as month
			 from contabilidadingresos where ingreso_confirmado=1 and Year(contabilidad_ingresos_fechaalta)=:anioIngresos group by Month(contabilidad_ingresos_fechaalta)';
			$sumaIngresos = Yii::app()->dbadvert->createCommand($sqlSumaDeingresosPorMes)
				->bindParam(":anioIngresos", $anioSelected, PDO::PARAM_STR)
				->queryAll();

			$datosSumaIngresos = array_fill(1, 12, 0);
			foreach ($sumaIngresos as $suma) {
				$datosSumaIngresos[$suma['month']] = $suma['total'];
			}
			$datosSumaIngresos = array_values($datosSumaIngresos);

			// obtenemos la smatoria de costos
			$datosSumaCostos = array_fill(1, 12, 0);
			// obtenemos los ingresos generados en el anio dado, agrupados por mes
			$sqlSumaDeCostosPorMes = 'SELECT SUM(costo) as total,Month(proyecto_fecha_alta) as month
			from proyectos where proyecto_estatus!=7 and Year(proyecto_fecha_alta)=:anioIngresos group by Month(proyecto_fecha_alta)';
			$sumaCostos = Yii::app()->dbadvert->createCommand($sqlSumaDeCostosPorMes)
				->bindParam(":anioIngresos", $anioSelected, PDO::PARAM_STR)
				->queryAll();

			foreach ($sumaCostos as $costo) {
				$datosSumaCostos[$costo['month']] = $costo['total'];
			}
			$datosSumaCostos = array_values($datosSumaCostos);

			if (isset($_POST['ajax'])) {
				echo CJSON::encode(
					array(
						'data' => array($datosSumaIngresos, $datosSumaCostos),
						'requestresult' => 'ok'
					)
				);
				exit;
			}

			return $this->render(
				'reporte_ingresos_costos',
				array(
					'aniosIngresos' => $aniosIngresos,
					'anioSelected' => $anioSelected,
					'data' => json_encode(array($datosSumaIngresos, $datosSumaCostos)),
				)
			);
		} catch (Exception $e) {
			Yii::app()->user->setFlash('danger', 'Error: ' . $e->getMessage());
			$this->redirect(Yii::app()->createUrl('contabilidadingresos/index'));
		}
	}


	public function actionReporteVentasUsuarios()
	{
		// Verificamos el acceso al modulo
		$VerificarAcceso = $this->VerificarAcceso(20, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		try {
			RActiveRecord::getAdvertDbConnection();
			$usuarios = Usuarios::model()->findAll('eliminado=0');

			$anioSeleccionado = (isset($_POST['anio_seleccionado'])) ? $_POST['anio_seleccionado'] : date("Y");
			$idUsuario = (isset($_POST['id_usuario'])) ? $_POST['id_usuario'] : $usuarios[0]['ID_Usuario'];

			// buscamos pedidos por anio y por usuario y juntamos para buscar los producto y su cantidad 
			$sqlProductosPedidos = 'SELECT prod.producto_nombre, pp.id_producto,SUM(pp.proyectos_productos_cantidad) as cantidad_productos ,Month(p.proyecto_fecha_alta) as mes
			 FROM proyectos p 
			 INNER join proyectosproductos pp ON p.id_proyecto=pp.id_proyecto 
			 INNER join productos prod ON prod.id_producto=pp.id_producto 
			 WHERE p.proyecto_estatus!=7 AND p.id_usuario=:idUsuario AND YEAR(p.proyecto_fecha_alta)=:anioSeleccionado 
			 GROUP BY pp.id_producto,Month(p.proyecto_fecha_alta)';
			$productosPedidos = Yii::app()->dbadvert->createCommand($sqlProductosPedidos)
				->bindParam(":idUsuario", $idUsuario, PDO::PARAM_STR)
				->bindParam(":anioSeleccionado", $anioSeleccionado, PDO::PARAM_STR)
				->queryAll();

			$productosStackedPorMes = array();
			// creamos el arreglos con la key de nombre de los productos que contendra un arreglo con los meses
			foreach ($productosPedidos as $productoPedido) {
				if (!array_key_exists($productoPedido['producto_nombre'], $productosStackedPorMes)) {
					$productosStackedPorMes[$productoPedido['producto_nombre']] = array_fill(1, 12, 0);
				}
				// asignamos la cantidad de productos en cada mes de cada producto
				$productosStackedPorMes[$productoPedido['producto_nombre']][$productoPedido['mes']] = $productoPedido['cantidad_productos'];
			}

			if (isset($_POST['ajax'])) {
				echo CJSON::encode(
					array(
						'data' => $productosStackedPorMes,
						'requestresult' => 'ok'
					)
				);
				exit;
			}

			// anios en los que hay pedidos
			$sqlAños = 'SELECT DISTINCT YEAR(proyecto_fecha_alta) as anio FROM proyectos WHERE proyecto_estatus!=7 order by proyecto_fecha_alta desc ';
			$aniosPedidos = Yii::app()->dbadvert->createCommand($sqlAños)->queryAll();

			return $this->render(
				'reporte_ventas_usuarios',
				array(
					'aniosPedidos' => $aniosPedidos,
					'usuarios' => $usuarios,
					'anioSeleccionado' => $anioSeleccionado,
					'idUsuario' => $idUsuario,
					'productosStackedPorMes' => json_encode($productosStackedPorMes),
				)
			);
		} catch (Exception $e) {
			Yii::app()->user->setFlash('danger', 'Error: ' . $e->getMessage());
			$this->redirect(Yii::app()->createUrl('contabilidadingresos/index'));
		}
	}

	public function actionActualizarformapago()
	{
		$idIngreso = $_POST['idIngreso'];
		$idFormaPago = $_POST['idFormaPago'];
		if (!$idIngreso || !$idFormaPago) {
			echo CJSON::encode(['requestresult' => 'fail', 'message' => 'Datos incompletos']);
			Yii::app()->end();
		}

		// Permisos (opcional: reusar el mismo check que para estatus)
		// if(!$this->VerificarAcceso( XXX , Yii::app()->user->id)){ ... }

		$ingreso = Contabilidadingresos::model()->find([
			'condition' => 'id_contabilidad_ingresos = :id',
			'params'    => [':id' =>  $idIngreso],
		]);

		if (!$ingreso) {
			echo CJSON::encode(['requestresult' => 'fail', 'message' => 'No hay ingresos para este pedido']);
			Yii::app()->end();
		}

		// AJUSTA el nombre del campo FK exacto en tu tabla:
		$ingreso->id_formapago = $idFormaPago;

		if ($ingreso->save(false)) {
			echo CJSON::encode(['requestresult' => 'ok', 'message' => 'Forma de pago actualizada']);
		} else {
			echo CJSON::encode(['requestresult' => 'fail', 'message' => 'No se pudo guardar']);
		}
		Yii::app()->end();
	}
}
