<?php


require_once(__DIR__ . '/../vendor/dompdf/autoload.inc.php');
// require_once(__DIR__ . '/../vendor/mpdf');
use Dompdf\Dompdf;

class TransferenciasController extends Controller
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
	 * Pantalla para ver las transferencias
	 */
	public function actionIndex()
	{
		$VerificarAcceso = $this->VerificarAcceso(24, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		/*if(!$this->VerificarAcceso(10,Yii::app()->user->id)){
													  // redireccionamos a inicio, no tiene acceso al modulo
													  Yii::app()->user->setflash('danger','No cuenta con el privilegio para acceder al modulo.');	
													  #$this->layout = 'noautorizado';
												  }*/
		// proceso para crear una transferencia
		if (isset($_POST['Transferencias'])) {
			// generamos la transferencia 
			$creartr = new Transferencias;
			$creartr->id_sucursal_destino = $_POST['Transferencias']['id_sucursal_destino'];
			$creartr->id_sucursal_origen = $_POST['Transferencias']['id_sucursal_origen'];
			$creartr->id_usuario_crea = Yii::app()->user->id;
			$creartr->fecha_solicitud = date('Y-m-d H:i:s');
			$creartr->estatus = 1;
			$creartr->eliminado = 0;
			$creartr->tipo = $_POST['Transferencias']['tipo'];
			;
			$creartr->id_usuario_solicita = $_POST['Transferencias']['id_usuario_solicita'];
			$creartr->comentarios = $_POST['Transferencias']['comentarios'];
			if ($creartr->save()) {
				// exito
				$logtr = array(
					'id_transferencia' => $creartr->id,
					'estatus_anterior' => '',
					'estatus_final' => $creartr->estatus,
					'comentarios' => $creartr->comentarios,
					'id_usuario' => $creartr->id_usuario_crea,
					'fecha_alta' => $creartr->fecha_solicitud,
				);
				$this->Insertarlogtr($logtr);
				Yii::app()->user->setflash('success', 'Transferencia creada con éxito #' . $creartr['id']);
				$this->redirect(Yii::app()->createurl("transferencias/detalles", array('id' => $creartr['id'])));
			} else {
				// error
				Yii::app()->user->setflash('danger', 'Ocurrio un error al generar.');
			}
			// redireccionamos la página de donde viene
			$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
			$this->redirect($urlfrom);
		}

		// modelo para crear una transferencia
		$tipodetr = array('0' => 'NORMAL', '1' => 'URGENTE');
		$nuevatr = new Transferencias;


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
				'condition' => 'eliminado = 0 and estatus = 1',
				'order' => 'nombre asc'
			)
		);

		// lista de transferencias
		$transferencias = Transferencias::model()->findAll(
			array(
				'condition' => 'eliminado = 0'
			)
		);

		// variables para los filtros
		// $fecha_desde = (isset($_GET['fecha_desde'])) ? $_GET['fecha_desde'] : $this->_data_first_month_day();
		$fecha_desde = (isset($_GET['fecha_desde'])) ? $_GET['fecha_desde'] : $this->_data_last_three_month_day();
		$fecha_hasta = (isset($_GET['fecha_hasta'])) ? $_GET['fecha_hasta'] : $this->_data_last_month_day();
		$estatus = (isset($_GET['estatus'])) ? $_GET['estatus'] : '0';
		$sucursal_origen = (isset($_GET['sucursal_origen'])) ? $_GET['sucursal_origen'] : '0';
		$sucursal_destino = (isset($_GET['sucursal_destino'])) ? $_GET['sucursal_destino'] : '0';
		$tipos = (isset($_GET['tipos'])) ? $_GET['tipos'] : '9';

		// lista estatus
		$estatustr = $this->EstatusTRlista();
		$condition = 'eliminado = 0 and date(fecha_solicitud) between :fecha_desde and :fecha_hasta';
		$parametros = array(':fecha_desde' => $fecha_desde, ':fecha_hasta' => $fecha_hasta);

		if ($estatus != '0') {
			$estatus = $_GET['estatus'];
			$condition .= ' and estatus = :estatus';
			$parametros[':estatus'] = $estatus;
		}
		if ($sucursal_origen != '0') {
			$sucursal_origen = $_GET['sucursal_origen'];
			$condition .= ' and id_sucursal_origen = :sucursal_origen';
			$parametros[':sucursal_origen'] = $sucursal_origen;
		}
		if ($sucursal_destino != '0') {
			$sucursal_destino = $_GET['sucursal_destino'];
			$condition .= ' and id_sucursal_destino = :sucursal_destino';
			$parametros[':sucursal_destino'] = $sucursal_destino;
		}
		if ($tipos != '9') {
			$tipos = $_GET['tipos'];
			$condition .= ' and tipo = :tipos';
			$parametros[':tipos'] = $tipos;
		}

		$transferencias = Transferencias::model()->findAll(
			array(
				'condition' => $condition,
				'params' => $parametros
			)
		);


		$this->render(
			'index',
			array(
				'nuevatr' => $nuevatr,
				'tipodetr' => $tipodetr,
				'usuariosdropdown' => CHtml::listData($usuarios, 'ID_Usuario', 'Usuario_Nombre'),
				'sucursalesdropdown' => CHtml::listData($sucursales, 'id', 'nombre'),
				'transferencias' => $transferencias,
				'fecha_desde' => $fecha_desde,
				'fecha_hasta' => $fecha_hasta,
				'estatustr' => $estatustr,
				'estatus' => $estatus,
				'sucursal_origen' => $sucursal_origen,
				'sucursal_destino' => $sucursal_destino,
				'tipos' => $tipos,


			)
		);
	}
	/**
	 * proceso para eliminar un registro, baja logica
	 */
	public function actionEliminar()
	{
		$actualizar = Transferencias::model()->findByPk($_GET['id']);

		if (!empty($actualizar)) {
			$actualizar->eliminado = 1;
			if ($actualizar->save()) {
				Yii::app()->user->setFlash('success', "Registro eliminado con exito");
			} else {
				Yii::app()->user->setFlash('danger', "No se pudo eliminar el registro.");
			}
		} else {
			Yii::app()->user->setFlash('danger', "No se encontro el registro.");
		}
		// redireccionamos la página de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	/**
						 PANTALLA PARA VER EL DETALLE DE LA TRANSFERENCIA
			  **/
	public function actionDetalles()
	{
		$id = $_GET['id'];
		if (empty($id)) {
			$this->redirect(Yii::app()->createurl('transferencias/index'));
		}
		$transferencia = Transferencias::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id)
			)
		);
		if (empty($transferencia)) {
			$this->redirect(Yii::app()->createurl('transferencias/index'));
		}
		$this->render(
			'detalles',
			array(
				'transferencia' => $transferencia
			)
		);
	}

	/**		
						 FUNCION PARA ACTUALIZAR EL ESTATUS DE LA TRX
			  **/
	public function actionActualizarestatus()
	{
		/*
													  1= transferencia abierta
													  2= transferencia preparada
													  3= transferencia en curso
													  4= transferencia recibida
													  9= transferencia cancelada
												  */

		$id = $_POST['id_tr'];
		$estatusnuevo = $_POST['estatusnuevo'];

		if (empty($id) || empty($estatusnuevo)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Ocurrio un error con la TR'
				)
			);
			exit;
		}
		// verificamos si tiene acceso
		/*if(!$this->VerificarAcceso(12,Yii::app()->user->id)==1 && $estatusnuevo==3)
												  {
													  echo CJSON::encode(array(
															'requestresult' => 'fail',
														  'message'=>'No cuenta con el privilegio para cambiar el estatus de la transferencia'
													  ));
													  exit;
												  }

												  if(!$this->VerificarAcceso(11,Yii::app()->user->id)==1 && $estatusnuevo==4)
												  {
													  echo CJSON::encode(array(
															'requestresult' => 'fail',
														  'message'=>'No cuenta con el privilegio para cambiar el estatus de la transferencia'
													  ));
													  exit;
												  }*/

		$transferencia = Transferencias::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id)
			)
		);
		if (empty($transferencia)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Ocurrio un error con la TR'
				)
			);
			exit;
		}
		// actualizar estatus
		$transferencia->estatus = $estatusnuevo;
		// si es el estatus es 3, actualizamos la tabla de productos para insertar la cantidad por recibir, solo sumamos
		if ($estatusnuevo == 3) {

			$model = new Transferencias();
			$db = $model->getDbConnection();
			$transaction = $db->beginTransaction();
			try {
				#$transaction = Yii::app()->db->beginTransaction();

				if (!$transferencia->save()) {
					throw new Exception("No fue posible actualizar el estatus de la transferencia");
				}

				$conceptos = TransferenciaDetalles::model()->findAll(
					array(
						'condition' => 'id_transferencia = :id and eliminado = 0',
						'params' => array(':id' => $id)
					)
				);

				/*
																										   echo '<pre>';
																											   print_r($_POST);
																										   echo '</pre>';
																										   exit;
																									   
																										   Array
																										   (
																											   [estatusnuevo] => 3
																											   [id_tr] => 1
																											   [serieylote] => serieylote%5B%5D=4&idpartida%5B%5D=1&serieylote%5B%5D=5&idpartida%5B%5D=1&serieylote%5B%5D=6&idpartida%5B%5D=1
																										   )
																									   */
				if (count($conceptos) > 0) {
					// actualizamos la columna cantidad_por_recibir
					foreach ($conceptos as $rows) {
						$cantidadarecibir = $rows['cantidad'];
						$sucursalorigenproducto = SucursalesProductos::model()->find('id_sucursal=' . $transferencia->id_sucursal_origen . ' and id_producto=' . $rows->id_producto);
						if (empty($sucursalorigenproducto)) {
							// quiere decir que no existe, insertamos
							$sucursalorigenproducto = new SucursalesProductos;
							$sucursalorigenproducto->id_sucursal = $transferencia->id_sucursal_origen;
							$sucursalorigenproducto->id_producto = $rows->id_producto;
							$sucursalorigenproducto->cantidad_stock = 0;
							$sucursalorigenproducto->cantidad_por_enviar = $rows->cantidad;
						} else {
							$sucursalorigenproducto->cantidad_por_enviar = $sucursalorigenproducto->cantidad_por_enviar - $rows->cantidad;
						}
						if (!$sucursalorigenproducto->save()) {
							throw new Exception("No se actualizo la sucursal de origen | ");
						}

						$sucursaldestinoproducto = SucursalesProductos::model()->find('id_sucursal=' . $transferencia->id_sucursal_destino . ' and id_producto=' . $rows->id_producto);
						if (empty($sucursaldestinoproducto)) {
							// quiere decir que no existe, insertamos
							$sucursaldestinoproducto = new SucursalesProductos;
							$sucursaldestinoproducto->id_sucursal = $transferencia->id_sucursal_destino;
							$sucursaldestinoproducto->id_producto = $rows->id_producto;
							$sucursaldestinoproducto->cantidad_stock = 0;
							$sucursaldestinoproducto->cantidad_por_recibir = $rows->cantidad;
						} else {
							$sucursaldestinoproducto->cantidad_por_recibir = $sucursaldestinoproducto->cantidad_por_recibir + $rows->cantidad;
						}
						if (!$sucursaldestinoproducto->save()) {
							throw new Exception("No se actualizo la sucursal de destino | ");
						}
						// actualizamos la tabla de conceptos
						$conceptos_actualizar = TransferenciaDetalles::model()->findByPk($rows['id']);
						$conceptos_actualizar->cantidad_original = $rows['cantidad'];
						$conceptos_actualizar->cantidad = $rows->cantidad_salida;
						$conceptos_actualizar->cantidad_pendiente = $rows->cantidad_salida;
						// actiualizamos los precios
						$conceptos_actualizar->subtotal_unitario = $rows->cantidad_salida * $rows->unitario;
						$conceptos_actualizar->subtotal_iva = $rows->cantidad_salida * $rows->iva;
						;
						$conceptos_actualizar->total = ($rows->cantidad_salida * $rows->unitario) + ($rows->cantidad_salida * $rows->iva);
						$conceptos_actualizar->save();
						if (!$conceptos_actualizar->save()) {
							throw new Exception("No se actualizo el concepto | ");
						}


						$cantidadporsalir = $rows['cantidad'];
						// verificamos que la cantidad por salir no sea mayor a la que ya salio


						if ($cantidadporsalir > $rows['cantidad_por_salir']) {
							throw new Exception("Cantidad mayor a la pendiente");
						}
						// insertamos en movimientos y actualizamos el producto stock
						$id_sucursal_origen = $transferencia['id_sucursal_origen'];
						$id_producto = $rows['id_producto'];
						// validamos que ese producto cuente con stock para salir
						$almacenprod = SucursalesProductos::model()->find(
							array(
								'condition' => 'id_sucursal=:id_sucursal_origen and id_producto=:id_producto and cantidad_stock > :cantidadporsalir',
								'params' => array(':id_sucursal_origen' => $id_sucursal_origen, ':id_producto' => $id_producto, ':cantidadporsalir' => $cantidadporsalir),
							)
						);
						if (empty($almacenprod)) {
							throw new Exception("El producto no cuenta con stock suficiente.");
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
						$movimiento->id_partida = $rows['id'];
						if (!$movimiento->save()) {
							throw new Exception("No se pudo guardar el movimiento | ");
						}
						// actualizamos la rows
						$rows->cantidad_salida = $rows->cantidad_salida + $cantidadporsalir;
						$rows->cantidad_por_salir = $rows->cantidad_por_salir - $cantidadporsalir;
						if (!$rows->save()) {
							throw new Exception("No se pudo guardar la actualización de la partida | ");
						}
						// actualizamos las partidas de sucursales productos campo de cantidad por recibir
						$almacenprod->cantidad_stock = $almacenprod->cantidad_stock - $cantidadporsalir;
						$almacenprod->cantidad_por_enviar = $almacenprod->cantidad_por_enviar + $cantidadporsalir;
						if (!$almacenprod->save()) {
							throw new Exception("No se pudo guardar la actualización de la partida en el stock | ");
						}
						// verificamos si es producto controlado.
						/*$control_por_serie = $rows['idProducto']['control_por_serie'];
																																						 if($control_por_serie==1){
																																							 $serieylotesalida = array();
																																							 parse_str($_POST['serieylote'], $serieylotesalida);

																																							 
																																							 //Array
																																							 //(
																																							 //    [serieylote] => Array
																																							 //        (
																																							 //            [0] => 4
																																							 //            [1] => 5
																																							 //            [2] => 6
																																							 //        )
																															 //
																																							 //    [idpartida] => Array
																																							 //        (
																																							 //            [0] => 1
																																							 //            [1] => 1
																																							 //            [2] => 1
																																							 //       )
																															 //
																																							 //)
																																							 //print_r($serieylotesalida);
																																							 //exit;
																																							 

																																							 if(count($serieylotesalida['serieylote'])!=$cantidadporsalir){
																																								 throw new Exception("Las cantidades no concuerdan."); 
																																							 }
																																							 foreach($serieylotesalida['serieylote'] as $key => $value){
																																								 $id_serie = $value;
																																								 $verificarstockserie = SucursalesProductosSeries::model()->find(array(
																																									 'condition'=>'id_producto=:id_producto and id_sucursal=:id_sucursal and id=:id and cantidad_stock >= 1',
																																									 'params'=>array(':id_producto'=>$id_producto,':id_sucursal'=>$id_sucursal_origen,':id'=>$id_serie),
																																								 ));
																																								 if(empty($verificarstockserie)){
																																									 throw new Exception("La serie no cuenta con stock."); 
																																								 }
																																								 // insertamos el movimiento
																																								 $movserie = new SucursalesMovimientosSeries;
																																								 $movserie->id_producto = $id_producto;
																																								 $movserie->id_sucursal = $id_sucursal_origen;
																																								 $movserie->id_serie = $id_serie;
																																								 $movserie->id_movimiento = $movimiento['id'];
																																								 $movserie->cantidad_stock_antes = 1;
																																								 $movserie->cantidad_movimiento = 1;
																																								 $movserie->cantidad_stock_final = 0;
																																								 $movserie->fecha_movimiento = date('Y-m-d H:i:s');
																																								 $movserie->id_usuario = Yii::app()->user->id;
																																								 $movserie->eliminado = 0;
																																								 $movserie->comentarios = 'Movimiento de salida de transferencia';
																																								 if(!$movserie->save()){
																																									 throw new Exception("No fue posible guardar el movimiento de la serie."); 
																																								 }
																																								 // actualizamos el stock
																																								 $verificarstockserie->cantidad_stock = 0;
																																								 if(!$verificarstockserie->save()){
																																									 throw new Exception("No fue posible actualizar el stock de la serie."); 
																																								 }
																																							 }
																																						 }*/
						// ajuste por dvb el 02 de marzo del 2020 para verificar que si existe en rsi no pueda sacar mas de la cantidad que existe
						// $verificaranalisisrsi = RsiTecnoplaza::model()->find(array(
						// 	'condition'=>'sku = :sku and eliminado = 0 and id_almacen = :idsucursalorigen',
						// 	'params'=>array(':sku'=>$sucursalorigenproducto['idProducto']['clave'],':idsucursalorigen'=>$transferencia['id_sucursal_origen']),
						// 	'order'=>'id desc'
						// ));
						// if(empty($verificaranalisisrsi)){
						// 	throw new Exception("El producto ".$sucursalorigenproducto['idProducto']['clave']." no se encuentra en el modulo RSI, primero es necesario darlo de alta"); 
						// }elseif($verificaranalisisrsi['unidades_en_almacen'] < $rows['cantidad']){
						// 	throw new Exception("El producto ".$sucursalorigenproducto['idProducto']['clave']." cuenta con ".$verificaranalisisrsi['unidades_en_almacen']." unidades en almacen, no es posible transferir mas de la cantidad, favor de ajustar la cantidad a maximo"); 
						// }
						// // restamos esa cantidad d e las unidades en almacen
						// $verificaranalisisrsi->unidades_en_almacen = $verificaranalisisrsi->unidades_en_almacen -$rows['cantidad'];
						// if(!$verificaranalisisrsi->save()){
						// 	throw new Exception("No fue posible actualizar el stock RSI."); 
						// }
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
		} elseif ($estatusnuevo == 4) {
			// recibimos la transferencia
			$conceptos = TransferenciaDetalles::model()->findAll(
				array(
					'condition' => 'id_transferencia = :id and eliminado = 0',
					'params' => array(':id' => $id)
				)
			);
			#if(count($conceptos)>0)
			{

				$model = new Transferencias();
				$db = $model->getDbConnection();
				$transaction = $db->beginTransaction();
				try {
					#$transaction = Yii::app()->db->beginTransaction();
					$transferencia->save();


					// agregado por dvb ya que no validaba que tengamos stock en origen y no hacia moviientos
					foreach ($conceptos as $rows) {
						$cantidadarecibir = $rows['cantidad'];
						$sucursalorigenproducto = SucursalesProductos::model()->find('id_sucursal=' . $transferencia->id_sucursal_origen . ' and id_producto=' . $rows->id_producto);
						if (empty($sucursalorigenproducto)) {
							// quiere decir que no existe, insertamos
							$sucursalorigenproducto = new SucursalesProductos;
							$sucursalorigenproducto->id_sucursal = $transferencia->id_sucursal_origen;
							$sucursalorigenproducto->id_producto = $rows->id_producto;
							$sucursalorigenproducto->cantidad_stock = 0;
							$sucursalorigenproducto->cantidad_por_enviar = $rows->cantidad;
						} else {
							$sucursalorigenproducto->cantidad_por_enviar = $sucursalorigenproducto->cantidad_por_enviar - $rows->cantidad;
						}
						if (!$sucursalorigenproducto->save()) {
							throw new Exception("No se actualizo la sucursal de origen | ");
						}

						$sucursaldestinoproducto = SucursalesProductos::model()->find('id_sucursal=' . $transferencia->id_sucursal_destino . ' and id_producto=' . $rows->id_producto);
						if (empty($sucursaldestinoproducto)) {
							// quiere decir que no existe, insertamos
							$sucursaldestinoproducto = new SucursalesProductos;
							$sucursaldestinoproducto->id_sucursal = $transferencia->id_sucursal_destino;
							$sucursaldestinoproducto->id_producto = $rows->id_producto;
							$sucursaldestinoproducto->cantidad_stock = 0;
							$sucursaldestinoproducto->cantidad_por_recibir = $rows->cantidad;
						} else {
							$sucursaldestinoproducto->cantidad_por_recibir = $sucursaldestinoproducto->cantidad_por_recibir + $rows->cantidad;
						}
						if (!$sucursaldestinoproducto->save()) {
							throw new Exception("No se actualizo la sucursal de destino | ");
						}
						// actualizamos la tabla de conceptos
						$conceptos_actualizar = TransferenciaDetalles::model()->findByPk($rows['id']);
						$conceptos_actualizar->cantidad_original = $rows['cantidad'];
						$conceptos_actualizar->cantidad = $rows->cantidad_salida;
						$conceptos_actualizar->cantidad_pendiente = $rows->cantidad_salida;
						// actiualizamos los precios
						$conceptos_actualizar->subtotal_unitario = $rows->cantidad_salida * $rows->unitario;
						$conceptos_actualizar->subtotal_iva = $rows->cantidad_salida * $rows->iva;
						;
						$conceptos_actualizar->total = ($rows->cantidad_salida * $rows->unitario) + ($rows->cantidad_salida * $rows->iva);
						$conceptos_actualizar->save();
						if (!$conceptos_actualizar->save()) {
							throw new Exception("No se actualizo el concepto | ");
						}


						$cantidadporsalir = $rows['cantidad'];
						// verificamos que la cantidad por salir no sea mayor a la que ya salio
						#throw new Exception($cantidadporsalir.'<br>'.$rows['cantidad_por_salir']);
						if ($cantidadporsalir > $rows['cantidad_por_salir']) {
							throw new Exception("Cantidad mayor a la pendiente");
						}
						// insertamos en movimientos y actualizamos el producto stock
						$id_sucursal_origen = $transferencia['id_sucursal_origen'];
						$id_producto = $rows['id_producto'];
						// validamos que ese producto cuente con stock para salir
						$almacenprod = SucursalesProductos::model()->find(
							array(
								'condition' => 'id_sucursal=:id_sucursal_origen and id_producto=:id_producto and cantidad_stock >= :cantidadporsalir',
								'params' => array(':id_sucursal_origen' => $id_sucursal_origen, ':id_producto' => $id_producto, ':cantidadporsalir' => $cantidadporsalir),
							)
						);
						if (empty($almacenprod)) {
							throw new Exception("El producto no cuenta con stock suficiente.");
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
						$movimiento->id_partida = $rows['id'];
						if (!$movimiento->save()) {
							throw new Exception("No se pudo guardar el movimiento | ");
						}
						// actualizamos la rows
						$rows->cantidad_salida = $rows->cantidad_salida + $cantidadporsalir;
						$rows->cantidad_por_salir = $rows->cantidad_por_salir - $cantidadporsalir;
						if (!$rows->save()) {
							throw new Exception("No se pudo guardar la actualización de la partida | ");
						}
						// actualizamos las partidas de sucursales productos campo de cantidad por recibir
						$almacenprod->cantidad_stock = $almacenprod->cantidad_stock - $cantidadporsalir;
						$almacenprod->cantidad_por_enviar = $almacenprod->cantidad_por_enviar + $cantidadporsalir;
						if (!$almacenprod->save()) {
							throw new Exception("No se pudo guardar la actualización de la partida en el stock | ");
						}
					}
					// termina

					// actualizamos la columna cantidad_por_recibir
					foreach ($conceptos as $rows) {
						$cantidadarecibir = $rows['cantidad'];
						$sucursalorigenproducto = SucursalesProductos::model()->find('id_sucursal=' . $transferencia->id_sucursal_origen . ' and id_producto=' . $rows->id_producto);

						$sucursaldestinoproducto = SucursalesProductos::model()->find('id_sucursal=' . $transferencia->id_sucursal_destino . ' and id_producto=' . $rows->id_producto);
						$cantidad_stock_antes = 0;
						$cantidad_stock_final = $cantidadarecibir;



						if (empty($sucursaldestinoproducto)) {
							// quiere decir que no existe, insertamos
							$sucursaldestinoproducto = new SucursalesProductos;
							$sucursaldestinoproducto->id_sucursal = $transferencia->id_sucursal_destino;
							$sucursaldestinoproducto->id_producto = $rows->id_producto;
							$sucursaldestinoproducto->cantidad_stock = 0;
						} else {
							$cantidad_stock_antes = $sucursaldestinoproducto->cantidad_stock;
							$cantidad_stock_final = $sucursaldestinoproducto->cantidad_stock + $cantidad_stock_final;
						}
						if (!$sucursaldestinoproducto->save()) {
							throw new Exception("No se pudo actualizar el stock del destino | ");
						}



						// actualizamos la tabla de conceptos
						$conceptos_actualizar = TransferenciaDetalles::model()->findByPk($rows['id']);
						$conceptos_actualizar->cantidad_original = $rows['cantidad'];
						$conceptos_actualizar->cantidad = $rows['cantidad'];
						$conceptos_actualizar->cantidad_pendiente = 0;
						// actiualizamos los precios
						$conceptos_actualizar->subtotal_unitario = $rows->cantidad_salida * $rows->unitario;
						$conceptos_actualizar->subtotal_iva = $rows->cantidad_salida * $rows->iva;
						;
						$conceptos_actualizar->total = ($rows->cantidad_salida * $rows->unitario) + ($rows->cantidad_salida * $rows->iva);
						$conceptos_actualizar->save();
						if (!$conceptos_actualizar->save()) {
							throw new Exception("No se pudo actualizar la partida | ");
						}




						$movimiento = new SucursalesMovimientos;
						$movimiento->id_sucursal = $transferencia->id_sucursal_destino;
						$movimiento->id_producto = $rows->id_producto;
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
						$movimiento->id_partida = $rows['id'];
						if (!$movimiento->save()) {
							throw new Exception("No se pudo guardar el movimiento | ");
						}


						// actualizamos la rows
						$rows->cantidad_recibida = $rows->cantidad_recibida + $cantidadarecibir;
						$rows->cantidad_pendiente = $rows->cantidad_pendiente - $cantidadarecibir;
						if (!$rows->save()) {
							throw new Exception("No se pudo guardar la actualización de la partida | ");
						}
						// actualizamos las partidas de sucursales productos campo de cantidad por recibir
						$sucursaldestinoproducto->cantidad_por_recibir = $sucursaldestinoproducto->cantidad_por_recibir - $cantidadarecibir;
						$sucursaldestinoproducto->cantidad_stock = $cantidad_stock_final;
						if (!$sucursaldestinoproducto->save()) {
							throw new Exception("No se pudo guardar la actualización de la rows en el stock | ");
						}

						#throw new Exception("Aqui vamos bien");

						#$control_por_serie = $rows['idProducto']['control_por_serie'];
						/*if($control_por_serie){
																																							 // obtenemos los movimientos de salida de cada partida
																																							 $movimientos = SucursalesMovimientos::model()->findAll(array(
																																								 'condition'=>'tipo = 2 and tipo_identificador = 21 and id_identificador = :id_transferencia and id_partida = :id_partida',
																																								 'params'=>array(':id_transferencia'=>$transferencia['id'],':id_partida'=>$rows['id'])
																																							 ));
																																							 foreach ($movimientos as $mov) {
																																								 foreach ($mov['idMovseries'] as $serie_mov)
																																								 {
																																									 $serie = $serie_mov['idSerie']['serie'];
																																									 $lote = $serie_mov['idSerie']['lote'];
																																									 $id_sucursal = $transferencia->id_sucursal_destino;
																																									 $id_producto = $rows->id_producto;
																																									 // actualizamos el stock e insertamos el movimiento
																																									 $verificarstockserie = SucursalesProductosSeries::model()->find(array(
																																										 'condition'=>'id_producto=:id_producto and id_sucursal=:id_sucursal and serie=:serie and lote=:lote',
																																										 'params'=>array(':id_producto'=>$id_producto,':id_sucursal'=>$id_sucursal,':serie'=>$serie,':lote'=>$lote),
																																									 ));
																																									 if(empty($verificarstockserie)){
																																										 // insertamos el producto
																																										 $verificarstockserie = new SucursalesProductosSeries;
																																										 $verificarstockserie->id_producto = $id_producto;
																																										 $verificarstockserie->id_sucursal = $id_sucursal;
																																										 $verificarstockserie->serie = $serie;
																																										 $verificarstockserie->lote = $lote;
																																										 $verificarstockserie->cantidad_stock = 0;
																																										 if(!$verificarstockserie->save()){
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
																																									 $movserie->comentarios = 'Movimiento en ajuste de inventario de entrada';
																																									 if(!$movserie->save()){
																																										 throw new Exception("No fue posible guardar el movimiento de la serie."); 
																																									 }
																																									 // actualizamos el stock
																																									 $verificarstockserie->cantidad_stock = $stockseriefinal;
																																									 if(!$verificarstockserie->save()){
																																										 throw new Exception("No fue posible actualizar el stock de la serie."); 
																																									 }
																																								 }
																																							 }
																																						 }*/

						// $verificaranalisisrsi = RsiTecnoplaza::model()->find(array(
						// 	'condition'=>'sku = :sku and eliminado = 0 and id_almacen = :idsucursalorigen',
						// 	'params'=>array(':sku'=>$sucursalorigenproducto['idProducto']['clave'],':idsucursalorigen'=>$transferencia['id_sucursal_origen']),
						// 	'order'=>'id desc'
						// ));
						// if(empty($verificaranalisisrsi)){
						// 	throw new Exception("El producto ".$sucursalorigenproducto['idProducto']['clave']." no se encuentra en el modulo RSI, primero es necesario darlo de alta"); 
						// }elseif($verificaranalisisrsi['unidades_en_almacen'] < $rows['cantidad']){
						// 	throw new Exception("El producto ".$sucursalorigenproducto['idProducto']['clave']." cuenta con ".$verificaranalisisrsi['unidades_en_almacen']." unidades en almacen, no es posible transferir mas de la cantidad, favor de ajustar la cantidad a maximo"); 
						// }
						// //
						// $preciosproductodatos = $this->Preciosventa($sucursaldestinoproducto['id_producto'],$sucursaldestinoproducto['id_sucursal'],array(),1);
						// // creamos un nuevo rsi en esa sucursal
						// $nuevoregistrorsi = new RsiTecnoplaza();
						// $nuevoregistrorsi->id_usuario = Yii::app()->user->id;
						// $nuevoregistrorsi->fecha_alta = date('Y-m-d H:i:s');
						// //
						// $nuevoregistrorsi->fecha_envio = date('Y-m-d H:i:s');
						// $nuevoregistrorsi->id_almacen = $sucursaldestinoproducto['id_sucursal'];
						// $nuevoregistrorsi->categoria = $sucursaldestinoproducto['idProducto']['idCategoria']['nombre'];
						// $nuevoregistrorsi->folio = $verificaranalisisrsi['folio'];
						// $nuevoregistrorsi->sku = $sucursaldestinoproducto['idProducto']['clave'];
						// $nuevoregistrorsi->descripcion = '';
						// $nuevoregistrorsi->costo = $preciosproductodatos['costo'];
						// $nuevoregistrorsi->unidades_enviadas = $cantidadarecibir;
						// $nuevoregistrorsi->rsi_padre = $verificaranalisisrsi['id'];

						// $nuevoregistrorsi->fecha_ultimo_analisis = $nuevoregistrorsi->fecha_envio;
						// $nuevoregistrorsi->unidades_en_almacen = $nuevoregistrorsi->unidades_enviadas;
						// $nuevoregistrorsi->inversion_total = $nuevoregistrorsi->unidades_enviadas * $nuevoregistrorsi->costo;
						// // verificamos si ingreso imagen
						// if(!$nuevoregistrorsi->save()){
						// 	throw new Exception("Error al registrar el RSI."); 
						// }
						// // ajustamos el movimiento con el id
						// $movimiento->id_rsi=$nuevoregistrorsi['id'];
						// if(!$movimiento->save()){
						// 	throw new Exception("Error al registrar el RSI movimiento."); 
						// }

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
			}
		} else {
			$transferencia->save();
		}
		//
		$this->Actualizartotaltr($id);
		// llamamos la funcion para actualizar el costo de la tr
		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'message' => 'Transferencia actualizada con exito',
			)
		);
		exit;
	}
	/**
						 PROCESO QUE REGRESA LOS DATOS DE LA TR
						 CONCEPTOS, SUBTOTAL, IVA Y TOTAL
			  **/
	public function actionDatostr()
	{
		$id = $_POST['id'];
		if (empty($id)) {
			exit;
		}
		$transferencia = Transferencias::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id)
			)
		);
		if (empty($transferencia)) {
			exit;
		}
		// obtenemos los conceptos
		$conceptos = TransferenciaDetalles::model()->findAll(
			array(
				'condition' => 'id_transferencia = :id and eliminado = 0',
				'params' => array(':id' => $id),
				'order' => 'id asc'
			)
		);
		// generamos una variable con los conceptos en formato de tabla
		$conceptostr = '';

		if (count($conceptos) > 0) {
			foreach ($conceptos as $rows) {
				///
				//
				$conceptostr .= '<tr id="partida' . $rows['id'] . '">';
				$conceptostr .= '<td>' . $rows['idProducto']['producto_nombre'] . '</td>';
				$conceptostr .= '<td>' . $rows['idProducto']['producto_clave'] . '</td>';
				$conceptostr .= '<td> $ ' . number_format($rows['unitario'], 2) . '</td>';
				$conceptostr .= '<td> $ ' . number_format($rows['iva'], 2) . '</td>';
				$conceptostr .= '<td> $ ' . number_format($rows['unitario'] + $rows['iva'], 2) . '</td>';
				$conceptostr .= '<td style="text-align:center;">' . $rows['cantidad'] . '</td>';
				$conceptostr .= '<td> $ ' . number_format($rows['total'], 2) . '</td>';
				if ($transferencia['estatus'] == 1 || $transferencia['estatus'] == 2) {
					$conceptostr .= '<td>
							<button type="button" class="btn btn-warning btn-xs" onclick="Datospartida(' . $rows['id'] . ')"><i class="fa fa-pencil" aria-hidden="true"></i></button>
							<button type="button" class="btn btn-danger btn-xs" onclick="Eliminarpartida(' . $rows['id'] . ')"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
						</td>';
				} else {
					$conceptostr .= '<td></td>';
				}

				$conceptostr .= '</tr>';
			}
		}
		// renderizamos
		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'transferencia' => $transferencia,
				'message' => 'Datos encontrados con exito',
				'conceptos' => $conceptos,
				'subtotalformat' => '$ ' . number_format($transferencia['subtotal'], 2),
				'ivaformat' => '$ ' . number_format($transferencia['iva'], 2),
				'totalformat' => '$ ' . number_format($transferencia['total'], 2),
				'conceptostr' => $conceptostr
			)
		);
	}

	/**
						 PROCESO QUE REGRESA LOS DATOS DEL PRODUCTO EN BASE AL PROVEEDOR, COSTO DE COMPRA
			  **/
	public function actionDatosproducto()
	{
		if (!isset($_POST['idproducto'])) {
			exit;
		}
		$idproducto = $_POST['idproducto'];
		if (!isset($_POST['id_sucursal'])) {
			exit;
		}
		$id_sucursal = $_POST['id_sucursal'];
		// verificamos si existe ese producto en la sucursal de origen
		$datos = SucursalesProductos::model()->find(
			array(
				'condition' => 'id_producto = :id_producto and id_sucursal = :id_sucursal',
				'params' => array(':id_producto' => $idproducto, ':id_sucursal' => $id_sucursal),
			)
		);
		// obtenemos el precio de venta en base a la sucursal de origen
		/*$precios = ProductosPrecios::model()->find(array(
													  'condition'=>'id_producto = :id_producto and id_sucursal = :id_sucursal and eliminado = 0',
													  'params'=>array(':id_producto'=>$idproducto,':id_sucursal'=>$id_sucursal),
												  ));*/
		$precios = ProductosPrecios::model()->find(
			array(
				'condition' => 'id_producto = :id_producto',
				'params' => array(':id_producto' => $idproducto),
			)
		);
		if (empty($precios)) {
			// regresamos el precio de compra

			$precios = array('precio' => 0);
		}
		// renderizamos
		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'message' => 'Producto encontrado con exito',
				'datos' => $datos,
				'precios' => $precios
			)
		);
	}
	/**
						 METODO PARA INSERTAR UN PRODUCTO NUEVO EN LA TRANSFERENCIA
			  **/
	public function actionAgregarproductotr()
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
		$transferencia = Transferencias::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (1)',
				'params' => array(':id' => $id)
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
		//
		$datos = ProductosPrecios::model()->find(
			array(
				'condition' => 'id_producto = :id_producto',
				'params' => array(':id_producto' => $id_producto),
			)
		);


		// insertamos el producto en la transferencia y obtenemos el IVA, en base a si genera iva y el campo de iva en la sucursal
		$agregarconcepto = new TransferenciaDetalles();
		$agregarconcepto->id_transferencia = $id;
		$agregarconcepto->id_producto = $id_producto;
		$precioventa = array();
		#if(empty($datos))
		#{
		$precioventa['precioventa'] = $unitarioproducto;
		$precioventa['unitarioconiva'] = $unitarioproducto;
		// obtenemos el iva
		$precioventa['iva'] = $unitarioproducto * (16 / 100);

		$precioventa['unitarioconiva'] = $unitarioproducto + $precioventa['iva'];
		#}
		#print_r($precioventa);exit;

		$agregarconcepto->unitario = $precioventa['precioventa'];
		$agregarconcepto->descuento = 0;
		$agregarconcepto->iva = $precioventa['iva'];
		$agregarconcepto->cantidad = $cantidadproducto;
		$agregarconcepto->subtotal_unitario = $precioventa['precioventa'] * $cantidadproducto;
		$agregarconcepto->subtotal_iva = $precioventa['iva'] * $cantidadproducto;
		$agregarconcepto->total = $precioventa['unitarioconiva'] * $cantidadproducto;

		$agregarconcepto->cantidad_original = $cantidadproducto;
		$agregarconcepto->cantidad_recibida = 0;
		$agregarconcepto->cantidad_pendiente = $cantidadproducto;
		$agregarconcepto->eliminado = 0;
		//
		$agregarconcepto->cantidad_salida = 0;
		$agregarconcepto->cantidad_por_salir = $cantidadproducto;
		if ($agregarconcepto->save()) {
			$this->Actualizartotaltr($id);
			// llamamos la funcion para actualizar el costo de la oc
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Producto agregado con exito',
				)
			);
			exit;
		} else {
			print_r($agregarconcepto->geterrors());
		}
	}
	/**
						 METODO PARA ACTUALIZAR UNA PARTIDA DE LA TR
			  **/
	public function actionActualizarproductotr()
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
		$transferencia = Transferencias::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (1,2,3)',
				'params' => array(':id' => $id)
			)
		);
		if (empty($transferencia)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Transferencias no encontrada',
				)
			);
			exit;
		}
		// partida
		$id_partida = $_POST['id_partida'];
		$partida = TransferenciaDetalles::model()->find(
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
		if (empty($id_producto) || empty($unitarioproducto) || empty($cantidadproducto) || empty($partida)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'No se encontraron los datos del producto',
				)
			);
			exit;
		}
		//
		$datos = ProductosPrecios::model()->find(
			array(
				'condition' => 'id_producto = :id_producto',
				'params' => array(':id_producto' => $id_producto),
			)
		);


		// insertamos el producto en la orden de compra y obtenemos el IVA, en base a si genera iva y el campo de iva en la sucursal
		$partida->id_producto = $id_producto;
		// costos del producto
		#$precioventa = $this->Preciosventa($datos['id_producto'],$transferencia['id_sucursal_origen']);



		$precioventa['precioventa'] = $unitarioproducto;
		$precioventa['unitarioconiva'] = $unitarioproducto;
		// obtenemos el iva
		$precioventa['iva'] = $unitarioproducto * (16 / 100);
		$precioventa['unitarioconiva'] = $unitarioproducto + $precioventa['iva'];

		$partida->unitario = $precioventa['precioventa'];
		$partida->descuento = 0;
		$partida->iva = $precioventa['iva'];
		$partida->cantidad = $cantidadproducto;
		$partida->subtotal_unitario = $precioventa['precioventa'] * $cantidadproducto;
		$partida->subtotal_iva = $precioventa['iva'] * $cantidadproducto;
		$partida->total = $precioventa['unitarioconiva'] * $cantidadproducto;

		$partida->cantidad_original = $cantidadproducto;
		$partida->cantidad_recibida = 0;
		$partida->cantidad_pendiente = $cantidadproducto;
		$partida->eliminado = 0;
		//
		$partida->cantidad_salida = 0;
		$partida->cantidad_por_salir = $cantidadproducto;
		if ($partida->save()) {
			$this->Actualizartotaltr($id);
			// llamamos la funcion para actualizar el costo de la oc
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Partida actualizada con exito',
				)
			);
			exit;
		} else {
			print_r($partida->geterrors());
		}
	}
	/**
						 METODO PARA ELIMINAR LA PARTIDA DE TRANSFERENCIA
			  **/
	public function actionEliminarpartida()
	{
		$id = $_POST['id'];
		$id_tr = $_POST['id_tr'];
		if (empty($id) || empty($id_tr)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Identificador no encontrado',
				)
			);
			exit;
		}
		$transferencia = Transferencias::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (1,2,3)',
				'params' => array(':id' => $id_tr)
			)
		);
		if (empty($transferencia)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Orden de Compra no encontrada',
				)
			);
			exit;
		}
		// eliminamos el concepto
		$concepto = TransferenciaDetalles::model()->find(
			array(
				'condition' => 'id=:id and id_transferencia = :id_tr and eliminado = 0',
				'params' => array(':id' => $id, ':id_tr' => $id_tr)
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
			$this->Actualizartotaltr($id_tr);
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
						 METODO PARA OBTENER LOS DATOS
			  **/
	public function actionDatospartida()
	{
		$id = $_POST['id'];
		$id_tr = $_POST['id_tr'];
		if (empty($id) || empty($id_tr)) {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Identificador no encontrado',
				)
			);
			exit;
		}
		$transferencia = Transferencias::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id and estatus in (1,2)',
				'params' => array(':id' => $id_tr)
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
		// datos del concepto
		$concepto = TransferenciaDetalles::model()->find(
			array(
				'condition' => 'id=:id and id_transferencia = :id_tr and eliminado = 0',
				'params' => array(':id' => $id, ':id_tr' => $id_tr)
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
							 metodo para crear el pdf
			  **/
	public function actionPdf()
	{
		$id = $_GET['id'];
		if (empty($id)) {
			exit;
		}
		$transferencia = Transferencias::model()->find(
			array(
				'condition' => 'eliminado = 0 and id = :id',
				'params' => array(':id' => $id)
			)
		);
		if (empty($transferencia)) {
			exit;
		}
		// datos del concepto
		$conceptos = TransferenciaDetalles::model()->findAll(
			array(
				'condition' => 'id_transferencia = :id and eliminado = 0',
				'params' => array(':id' => $id)
			)
		);
		if (empty($conceptos)) {
			exit;
		}


		$DatosConfiguracion = Configuracion::model()->findBypk(1);
		// PDF

		$dompdf = new Dompdf();


		$html = $this->renderPartial('pdf', array(
			'transferencia' => $transferencia,
			'conceptos' => $conceptos,
			'DatosConfiguracion' => $DatosConfiguracion,
		), true);

		$dompdf->loadHtml($html);
		$dompdf->setPaper('letter', 'portrait'); //->tamaño y orientacion de la hoja
		$dompdf->render();
		$dompdf->stream('Transferencia -' . $id . '-' . date('Y') . '.pdf', array('Attachment' => 0));
		# You can easily override default constructor's params
		// $mPDF1 = Yii::app()->ePdf->mpdf('',
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
		/*$mPDF1->SetHTMLHeader('<img src="' .Yii::app()->baseurl. '/images/header.png"/>');
												  $mPDF1->SetHTMLFooter('<img src="' .Yii::app()->baseurl. '/images/footer.png"/>');*/
		//{PAGENO}



		// $mPDF1->WriteHTML($html);

		// # Outputs ready PDF
		// $mPDF1->Output('Transferencia #  ' . $transferencia['id'] . '.pdf', 'I'); //Nombre del pdf y parámetro para ver pdf o descargarlo directamente.
		exit;
	}
	/**
						 FUNCION PARA DAR SALIDA DE PRODUCTOS DE TRANSFERENCIA
			  **/
	public function actionSalida()
	{
		/*
												  2 = transferencia cerrada
												  */
		$transferencia = array();
		$transferencias = Transferencias::model()->findAll(
			array(
				'condition' => 'eliminado = 0 and estatus in(2)'
			)
		);
		$conceptos = array();
		if (isset($_GET['buscar']) && isset($_GET['numero_tc'])) {
			// buscamos la oc
			$transferencia = Transferencias::model()->find(
				array(
					'condition' => 'eliminado = 0 and id = :id and estatus in(2,3)',
					'params' => array(':id' => $_GET['numero_tc'])
				)
			);
			if (empty($transferencia)) {
				Yii::app()->user->setFlash('danger', "Transferencia no encontrada.");
				$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
				$this->redirect($urlfrom);
			}
			// obtenemos los conceptos
			$conceptos = TransferenciaDetalles::model()->findAll(
				array(
					'condition' => 'id_transferencia = :id_tr and eliminado = 0',
					'params' => array(':id_tr' => $transferencia['id'])
				)
			);
		}
		$this->render(
			'salida',
			array(
				'transferencia' => $transferencia,
				'transferencias' => $transferencias,
				'conceptos' => $conceptos,
			)
		);
	}
	/**
						 FUNCION PARA RECIBIR UNA TRANSFERENCIA
			  **/
	public function actionRecibir()
	{
		/*
												  3 = transferencia en curso
												  */
		$transferencia = array();
		$transferencias = Transferencias::model()->findAll(
			array(
				'condition' => 'eliminado = 0 and estatus in(3)'
			)
		);

		$conceptos = array();
		if (isset($_GET['buscar']) && isset($_GET['numero_tc'])) {
			// buscamos la oc
			$transferencia = Transferencias::model()->find(
				array(
					'condition' => 'eliminado = 0 and id = :id and estatus in(3,4)',
					'params' => array(':id' => $_GET['numero_tc'])
				)
			);
			if (empty($transferencia)) {
				Yii::app()->user->setFlash('danger', "Transferencia no encontrada.");
				$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
				$this->redirect($urlfrom);
			}
			// obtenemos los conceptos
			$conceptos = TransferenciaDetalles::model()->findAll(
				array(
					'condition' => 'id_transferencia = :id_tr and eliminado = 0',
					'params' => array(':id_tr' => $transferencia['id'])
				)
			);
		}
		$this->render(
			'recibir',
			array(
				'transferencia' => $transferencia,
				'transferencias' => $transferencias,
				'conceptos' => $conceptos
			)
		);
	}

	/**
						 FUNCION PARA ACTUALIZAR EL COSTO DE LA TRANSFERENCIA
						 SE DEBE MANDAR A LLAMAR DESPUES DE AGREGAR UN PRODUCTO, EDITAR O ELIMINAR
			  **/
	public function Actualizartotaltr($id)
	{
		if ($id == 0) {
			return 1;
		}
		$transferencia = Transferencias::model()->find('id=:id and eliminado = 0', array(':id' => $id));
		// actualizamos la transferencia
		$transferenciatotalsql = '	select sum(total) as total,sum(subtotal_unitario) as subtotal,sum(subtotal_iva) as iva from transferencia_detalles where eliminado = 0 and id_transferencia = ' . $id;
		$totaltr = Yii::app()->db->createCommand($transferenciatotalsql)->queryrow();
		$transferencia->subtotal = $totaltr['subtotal'];
		$transferencia->iva = $totaltr['iva'];
		$transferencia->total = $totaltr['total'];
		if ($transferencia->save()) {
			$logoc = array(
				'id_transferencia' => $transferencia->id,
				'estatus_anterior' => '',
				'estatus_final' => $transferencia->estatus,
				'comentarios' => $transferencia->comentarios,
				'id_usuario' => $transferencia->id_usuario_crea,
				'fecha_alta' => date('Y-m-d H:i:s'),
				'total' => $transferencia->total
			);
			$this->Insertarlogtr($logoc);

			return 1;
		} else {
			return 0;
		}
	}
}
