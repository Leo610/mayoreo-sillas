<?php

class CotizacionesproductosController extends Controller
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
	/*
	 *	METODO PARA AGREGAR PRODUCTO A LA COTIZACION
	 *	CREADO EL 31 DE ENERO DEL 2016 POR DANIEL VILLARREAL
	 */
	public function actionAgregaproducto()
	{

		$agregarproducto = new Cotizacionesproductos;
		$agregarproducto->attributes = $_POST['Cotizacionesproductos'];

		$agregarproducto->save();
		Yii::app()->user->setFlash('success', 'Se agrego con exito el producto.');
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	/*
	 *	METODO QUE REGRESA EL PRECIO DEL PRODUCTO EN BASE AL ID DEL PRODUCTO Y A LA LISTA DE PRECIOS DEL CLIENTE
	 *	CREADO POR DANIEL VILLARREAL EL 31 DE ENERO DEL 2016
	 */
	public function actionObtenerprecio()
	{
		$id_producto = $_POST['id_producto'];
		$id_listaprecio = $_POST['id_listaprecio'];
		$tipo_precio = $this->ObtenerTipoPrecio($_POST['tipo_precio']);
		$tipo_precio = $tipo_precio['campo'];

		$datos = Productosprecios::model()->find('id_producto=' . $id_producto . ' and id_lista_precio=' . $id_listaprecio);
		if (!empty($datos[$tipo_precio])) // Si no esta vacio el producto si tiene precio
		{
			$precio = $datos[$tipo_precio];
			$message = 'Datos encontrados';
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'precio' => $precio,
					'datos' => $datos,
					'message' => $message
				)
			);
		} else { // En caso de que este vacio, regresamos el precio default
			$message = 'No se encontro el precio del producto, favor de contactar a un administrador.';
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Datos encontrados',
					'datos' => $datos,
					'precio' => 0
				)
			);
		}
	}

	/*
	 *	METODO PARA SUMAR O RESTAR UN PRODUCTO EN LA COTIZACION
	 *	CREADO POR DANIEL VILLARREAL EL 31 DE ENERO DEL 2016
	 */
	public function actionActualizarproductos()
	{
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit();
		$id = $_POST['id'];
		$Actualizar = Cotizacionesproductos::model()->findBypk($id);
		// Suma =1 o Resta = 0
		$TipoActualizacion = $_POST['tipoactualizacion'];

		if ($TipoActualizacion) // suma
		{
			// Actualizamos la cantidad del producto
			$Actualizar->cotizacion_producto_cantidad = $Actualizar->cotizacion_producto_cantidad + 1;
			// Obtenemos el total sin factor, que es la cantidad por el costo unitario
			$totalsinfactor = $Actualizar->cotizacion_producto_cantidad * $Actualizar->cotizacion_producto_unitario;
			// Verificamos si tiene descuento y sacamos su valor
			if (!empty($Actualizar->tipo_descuetno)) {
				$desc = $Actualizar->descuento;
				if ($Actualizar->tipo_descuetno == 'porcentaje') {
					$valporcentaje = $totalsinfactor * ($desc / 100);
					$totalsinfactor = $totalsinfactor - $valporcentaje;
				} else {
					$valcant = $Actualizar->cotizacion_producto_cantidad * $desc;
					$totalsinfactor = $totalsinfactor - $valcant;
				}
			}

			// Actualizamos el total con el factor incluido
			$Actualizar->cotizacion_producto_total = $totalsinfactor;
		} else { // resta
			// Actualizamos la cantidad del producto
			$Actualizar->cotizacion_producto_cantidad = $Actualizar->cotizacion_producto_cantidad - 1;

			// Obtenemos el total sin factor, que es la cantidad por el costo unitario
			$totalsinfactor = $Actualizar->cotizacion_producto_cantidad * $Actualizar->cotizacion_producto_unitario;

			// Verificamos si tiene descuento y sacamos su valor
			if (!empty($Actualizar->tipo_descuetno)) {
				$desc = $Actualizar->descuento;
				if ($Actualizar->tipo_descuetno == 'porcentaje') {
					$valporcentaje = $totalsinfactor * ($desc / 100);
					$totalsinfactor = $totalsinfactor - $valporcentaje;
				} else {
					$valcant = $Actualizar->cotizacion_producto_cantidad * $desc;
					$totalsinfactor = $totalsinfactor - $valcant;
				}
			}

			// Actualizamos el total con el factor incluido
			$Actualizar->cotizacion_producto_total = $totalsinfactor;
		}
		$Actualizar->save();
		Yii::app()->user->setFlash('success', "Se actualizo con exito!");
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	/*
	 *	METODO PARA ELIMINAR PRODUCTO A LA OC
	 *	CREADO EL 31 DE ENERO DEL 2016 POR DANIEL VILLARREAL
	 */
	public function actionEliminarproducto($id)
	{
		$delete = Cotizacionesproductos::model()->findBypk($id);
		$delete->delete();
		Yii::app()->user->setFlash('success', 'Se elimino con exito el producto.');
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}


	// action para obtener los datos del prodcuto de la cotizacion lars->2/11/23
	public function actionObtenerdatosprodcot()
	{

		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit();
		$id = isset($_POST['id']) ? $_POST['id'] : '';

		if (!empty($id)) {
			// si el id no esta vacio mandamos a traer ese producto de la tabla

			$producto = Cotizacionesproductos::model()->find('id_cotizacion_producto=' . $id);
			// sacamos los datos para mandarlo al ajax
			if (!empty($producto)) {


				$desc = $producto['cotizacion_producto_descripcion'];
				$color = $producto['color'];
				$colortapi = $producto['color_tapiceria'];
				$espext = $producto['especificaciones_extras'];
				$cant = $producto['cotizacion_producto_cantidad'];
				$unitario = $producto['cotizacion_producto_unitario'];
				$prodid = $producto['id_cotizacion_producto'];
				$descuento = $producto['descuento'];
				$tdescuento = $producto['tipo_descuetno'];
				echo CJSON::encode(
					array(
						'requestresult' => 'ok',
						'desc' => $desc,
						'color' => $color,
						'colortapi' => $colortapi,
						'espext' => $espext,
						'cant' => $cant,
						'unitario' => $unitario,
						'message' => 'Datos obtenidos con exito',
						'id' => $prodid,
						'desc2' => $descuento,
						'tdesc' => $tdescuento
					)
				);
			} else {
				echo CJSON::encode(
					array(
						'requestresult' => 'fail',
						'message' => 'Ocurrio un error inesperado'
					)
				);
			}
		}
	}
}
