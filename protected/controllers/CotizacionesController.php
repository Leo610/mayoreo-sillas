<?php

// include autoloader

require_once(__DIR__ . '/../vendor/dompdf/autoload.inc.php');
// require_once(__DIR__ . '/../vendor/mpdf');
use Dompdf\Dompdf;

// somewhere early in your project's loading, require the Composer autoloader
// see: http://getcomposer.org/doc/00-intro.md



// require_once('vendor/mpdf/src/Mpdf.php'); // Reemplaza 'ruta/al/archivo' con la ubicación real de mpdf.php en tu proyecto.
// require_once(__DIR__ . '/../vendor/autoload.php');
class CotizacionesController extends Controller
{
	// 0 = Pre cotizacion
	// 1 = Cotizacion 
	/*
	 *	VERIFICAMOS QUE PUEDA INGRESAR AL MODULO
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
	 *	METODO VER TODAS LAS COTIZACIONES
	 *	CREADO POR DANIEL VILLARREAL EL 1 DE FEBRERO DEL 2106
	 */
	public function actionLista()
	{
		$VerificarAcceso = $this->VerificarAcceso(16, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		// Variable para obtener el estatus

		// FECHA INICIO Y FECHA FIN
		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_last_three_month_day();
		// $fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();
		$id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';

		// Obtenemos todos los usuarios relacionados
		$usuarioshijos = $this->ObtenerHijosUsuario(Yii::app()->user->id, '');
		// TERMINA 

		// verificamos si viene desde cliente por el id_cliente
		if (!empty($id_cliente)) {
			$parametros = 'id_cliente = ' . $id_cliente . ' ORDER BY id_cotizacion DESC';

		} else {
			// Obtenemos la lista de cotizaciones de todos los clientes
			$parametros = ' cotizacion_fecha_alta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59"  ORDER BY id_cotizacion DESC';
			// $parametros = ' cotizacion_fecha_alta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" and id_usuario in (' . $usuarioshijos . ') ORDER BY id_cotizacion DESC';
		}

		// si el id cliente existe quiere decir que buscan las cotizaciones del cliente para el lunes jajaja 




		$listacotizaciones = Cotizaciones::model()->findAll($parametros);


		$this->render(
			'lista',
			array(
				'listacotizaciones' => $listacotizaciones,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin,
			)
		);



	}
	/*
	 *	METODO PARA VER LA COTIZACION EN LA PANTALLA
	 *	CREADO POR DANIEL VILLARREAL EL 1 DE FEBRERO DEL 2106
	 */
	public function actionVer($id)
	{
		// Obtenemos el encabezado de la cotizacion
		$DatosCotizacion = Cotizaciones::model()->findBypk($id);

		// Obtenemos el detalle de la cotizacion
		$DetalleCotizacion = Cotizacionesproductos::model()->findAll('id_cotizacion=' . $id);

		// Obtenemos los datos del cliente, seleccionado
		$DatosCliente = Clientes::model()->findBypk($DatosCotizacion->id_cliente);

		$this->render(
			'ver',
			array(
				'Datos' => $DatosCotizacion,
				'Detalle' => $DetalleCotizacion,
				'DatosCliente' => $DatosCliente

			)
		);
	}
	/*
	 *	METODO PARA VER LA COTIZACION EN PDF
	 *	CREADO POR DANIEL VILLARREAL EL 1 DE FEBRERO DEL 2106
	 */
	public function actionPdf($id)
	{
		// phpinfo();
		$descuentos = ($this->VerificarAcceso(8, Yii::app()->user->id)) == 1 ? false : true;

		// Datos de configuracion 
		$DatosConfiguracion = Configuracion::model()->findBypk(1);
		// Datos de la cotizacion
		// Obtenemos el encabezado de la cotizacion
		$DatosCotizacion = Cotizaciones::model()->findBypk($id);
		// Obtenemos el detalle de la cotizacion
		$DetalleCotizacion = Cotizacionesproductos::model()->findAll(
			"id_cotizacion=:id_cotizacion",
			array(':id_cotizacion' => $id)
		);
		// Obtenemos los datos del cliente, seleccionado
		$DatosCliente = Clientes::model()->findBypk($DatosCotizacion->id_cliente);
		/**************************************************************************/
		$archivoscot = Cotizacionesarchivos::model()->findAll('id_cotizacion=' . $DatosCotizacion->id_cotizacion . ' and agregar_a_cotizacion = 1');

		// echo "<pre>";
		// print_r($DatosConfiguracion);
		// echo "</pre>";
		// exit();
		// instantiate and use the dompdf class

		$dompdf = new Dompdf();
		$fecha = date("d F Y", strtotime($DatosCotizacion['cotizacion_fecha_alta']));
		$fecha_formateada = $this->Fechaespañol($fecha);





		$html = $this->renderPartial('pdf', array(

			'Datos' => $DatosCotizacion,
			'Detalle' => $DetalleCotizacion,
			'Detalleprod' => $DetalleCotizacion,
			'DatosCliente' => $DatosCliente,
			'DatosConfiguracion' => $DatosConfiguracion,
			'archivoscot' => $archivoscot,
			'fecha' => $fecha_formateada,
			'descuentos' => $descuentos
		), true);


		// echo "<pre>";
		// print_r($html);
		// echo "</pre>";
		// exit();

		// $this->renderPArtial('pdf', [
		// 	'Datos' => $DatosCotizacion,
		// 	'Detalle' => $DetalleCotizacion,
		// 	'Detalleprod' => $DetalleCotizacion,
		// 	'DatosCliente' => $DatosCliente,
		// 	'DatosConfiguracion' => $DatosConfiguracion,
		// 	'archivoscot' => $archivoscot,
		// 	'fecha' => $fecha_formateada,
		// 	'descuentos' => $descuentos
		// ]);

		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait'); //->tamaño y orientacion de la hoja

		// Render the HTML as PDF
		$dompdf->render();
		// paginacion
		$canvas = $dompdf->get_canvas();
		$canvas->page_text(520, 760, "Hoja {PAGE_NUM} - {PAGE_COUNT}", null, 12, array(0, 0, 0));

		$dompdf->stream($id . ' - ' . $DatosCliente['cliente_nombre'] . '.pdf', array('Attachment' => 0));
		// require_once __DIR__ . '/vendor/autoload.php';




	}

	// Metodo para crear una cotizacion
	public function actionCrear()
	{

		$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
		// Obtenemos los datos del cliente, seleccionado
		$DatosCliente = Clientes::model()->findBypk($id);

		// Mandamos el modelo de cotizacionesproductos
		$model = new Cotizacionesproductos;


		if (!empty($DatosCliente)) {
			// Obtenemos los productos en la cotizacion
			$productoscotizacion = Cotizacionesproductos::model()->findAll('id_cotizacion=0 and id_cliente=' . $DatosCliente->id_cliente . ' and id_usuario=' . Yii::app()->user->id);

			$this->render(
				'crear',
				array(
					'DatosCliente' => $DatosCliente,
					'model' => $model,
					'productoscotizacion' => $productoscotizacion
				)
			);
		} else {
			$this->render(
				'crear',
				array(
					'DatosCliente' => $DatosCliente,
					'model' => $model
				)
			);
		}
	}

	/*
	 *	METODO PARA GENERAR LA COTIZACION
	 *	CREADO POR DANIEL VILLARREAL EL 31 DE ENERO DEL 2016
	 */
	public function actionGenerarcotizacion()
	{
		$id_cliente = $_POST['id_cliente'];
		$nombrecotizacion = $_POST['nombrecotizacion'];
		$condicionesgenerales = $_POST['condicionesgenerales'];

		$id_usuario = Yii::app()->user->id;

		// Verificamos que tenga valor el cliente y el usuario y la moneda
		if (!empty($id_cliente) and !empty($id_usuario)) {
			// Obtenemos el total de la cotizacion
			RActiveRecord::getAdvertDbConnection();
			$cotizacion_total = Yii::app()->dbadvert->createCommand()
				->select('sum(cotizacion_producto_total) as cotizacion_total')
				->from('cotizacionesproductos')
				->where('id_cotizacion=0 and id_cliente=' . $id_cliente . ' and id_usuario=' . $id_usuario)
				->queryRow();

			// Realizamos el insert en cotizaciones para obtener el id de la cotizacion generada
			$Cotizacion = new Cotizaciones;
			$Cotizacion->id_cliente = $id_cliente;
			$Cotizacion->id_usuario = $id_usuario;
			$Cotizacion->cotizacion_comentario = $_POST['comentario'];
			$Cotizacion->cotizacion_nombre = $_POST['nombrecotizacion'];
			$Cotizacion->cotizacion_condiciones_generales = $_POST['condicionesgenerales'];
			$Cotizacion->cotizacion_total = ($cotizacion_total['cotizacion_total'] * 1.16);
			$Cotizacion->cotizacion_fecha_alta = date('Y-m-d H:i:s');
			$Cotizacion->cotizacion_ultima_modificacion = date('Y-m-d H:i:s');
			$Cotizacion->cotizacion_descuentos = 0;
			$Cotizacion->cotizacion_estatus = 1;
			$Cotizacion->save();
			$id_cotizacion = $Cotizacion->id_cotizacion;

			// Actualizamos los productos en la cotizacion
			Cotizacionesproductos::model()->updateAll(array('id_cotizacion' => $id_cotizacion), 'id_cotizacion = 0 AND id_cliente = ' . $id_cliente . ' AND id_usuario=' . $id_usuario);

			Yii::app()->user->setFlash('success', "Se genero con exito");
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
				)
			);
		}


	}


	/*
	 *	METODO PARA OBTENER LOS DATOS DE  LA COTIZACION
	 *	CREADO POR DANIEL VILLARREAL EL 31 DE ENERO DEL 2016
	 */
	public function actionDatosjs()
	{
		$DatosCotizacion = Cotizaciones::model()->findbypk($_POST['id']);

		$DatosCliente = Clientes::model()->findbypk($DatosCotizacion['id_cliente']);

		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'message' => 'Informacion obtenida con exito',
				'DatosCotizacion' => $DatosCotizacion,
				'DatosCliente' => $DatosCliente
			)
		);
	}
	public function actionVistaprevia($id)
	{
		// Obtenemos el encabezado de la cotizacion
		$DatosCliente = Clientes::model()->findBypk($id);

		// Obtenemos el detalle de la cotizacion
		$DetalleCliente = Cotizaciones::model()->findAll('id_cliente=1' . $id);

		// Obtenemos los productos en la cotizacion
		$productoscotizacion = Cotizacionesproductos::model()->findAll('id_cotizacion=0 and id_cliente=' . $DatosCliente->id_cliente . ' and id_usuario=' . Yii::app()->user->id);

		$this->render(
			'vistaprevia',
			array(
				'DatosCliente' => $DatosCliente,
				'productoscotizacion' => $productoscotizacion
			)
		);


	}

	// Metodo para actualizar una cotizacion
	public function actionActualizarcotizacion()
	{
		$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
		// Obtenemos los datos de la cotizacion
		$DatosCotizacion = Cotizaciones::model()->findBypk($id);

		if (empty($DatosCotizacion)) {
			// En caso no tener datos, redireccionamos a la lista de cotizaciones
			Yii::app()->user->setFlash('warning', "No fue posible encontrar la cotización.");
			$this->redirect(Yii::app()->createUrl('cotizaciones/lista'));
		}
		// Obtenemos los datos del cliente, seleccionado
		$DatosCliente = Clientes::model()->findBypk($DatosCotizacion->id_cliente);
		// Mandamos el modelo de cotizacionesproductos
		$model = new Cotizacionesproductos;
		// traemos los colores registrados ->lars 27/09/23
		// $arraylistaprecios = CHtml::listData(ListaPrecios::model()->findAll(), 'id_lista_precio', 'listaprecio_nombre');
		$colores = CHtml::listData(CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 27'), 'nombre', 'nombre');

		$colores2 = CHtml::listData(CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 35'), 'nombre', 'nombre');

		// Obtenemos los productos en la cotizacion
		$productoscotizacion = Cotizacionesproductos::model()->findAll('id_cotizacion=' . $DatosCotizacion->id_cotizacion);

		// Cargamos el modelo para agregar archivos
		$model_cot_arch = new Cotizacionesarchivos;

		// Obtenemos los archivos
		$archivoscot = Cotizacionesarchivos::model()->findAll('id_cotizacion=' . $DatosCotizacion->id_cotizacion);

		$this->render(
			'actualizar',
			array(
				'colores' => $colores,
				'colores2' => $colores2,
				'DatosCliente' => $DatosCliente,
				'model' => $model,
				'productoscotizacion' => $productoscotizacion,
				'DatosCotizacion' => $DatosCotizacion,
				'model_cot_arch' => $model_cot_arch,
				'archivoscot' => $archivoscot

			)
		);

	}

	public function actionActualizartotal()
	{
		$id_cotizacion = $_POST['id_cotizacion'];

		// Obtenemos el total de la cotizacion
		RActiveRecord::getAdvertDbConnection();
		$cotizacion_total = Yii::app()->dbadvert->createCommand()
			->select('sum(cotizacion_producto_total) as cotizacion_total')
			->from('cotizacionesproductos')
			->where('id_cotizacion=' . $id_cotizacion)
			->queryRow();



		// Actualizamos la cotizacion
		$Cotizacion = Cotizaciones::model()->findBypk($id_cotizacion);

		// $Cotizacion->cotizacion_total = ($cotizacion_total['cotizacion_total'] * 1.16) / $Cotizacion->tipo_cambio;
		if ($Cotizacion['sumar_iva'] == 1) {
			$Cotizacion->cotizacion_total = ($cotizacion_total['cotizacion_total'] * 1.16) / $Cotizacion->tipo_cambio;
		} else {
			$Cotizacion->cotizacion_total = ($cotizacion_total['cotizacion_total']) / $Cotizacion->tipo_cambio;
		}
		$Cotizacion->total_peso = ($cotizacion_total['cotizacion_total']);
		$Cotizacion->cotizacion_ultima_modificacion = date('Y-m-d H:i:s');
		$Cotizacion->cotizacion_descuentos = 0;
		$Cotizacion->cotizacion_estatus = 1;
		$Cotizacion->sumar_iva = $_POST['iva'];
		$Cotizacion->save();

		// En caseo de que la oportunidad sea diferente de 0 actualizamos el total
		if ($Cotizacion->id_oportunidad > 0) {
			$ActOP = CrmOportunidades::model()->findBypk($Cotizacion->id_oportunidad);
			$ActOP->valor_negocio = $Cotizacion->total_peso * 1.16;
			$ActOP->save();
		}

		// Redireccionamos de la pagina de donde viene
		/*$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
																																																																																																																																																																																																																																																																																																																																																																																																																																																								   $this->redirect($urlfrom);*/
		// LO REDIRECCIONAMOS A LA ULTIMA PARTE DEL PROCESO AGREGAR CONDICIONES COMERCIALES

		$this->redirect(Yii::app()->createUrl('/cotizaciones/condicionesgenerales/' . $id_cotizacion));
	}


	public function actionGuardar()
	{
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$id_cotizacion = (isset($_POST['Cotizaciones']['id_cotizacion'])) ? $_POST['Cotizaciones']['id_cotizacion'] : 0;
		// Mandamos el modelo de cotizaciones
		$Cotizacion = Cotizaciones::model()->findBypk($id_cotizacion);
		// Si hacen clic en guardar cambios
		if (isset($_POST['Cotizaciones'])) {
			$Cotizacion->attributes = $_POST['Cotizaciones'];
			$Cotizacion->cotizacion_ultima_modificacion = date('Y-m-d H:i:s');
			// Datos de moneda, 
			// echo "<pre>";
			// print_r($Cotizacion);
			// echo "</pre>";
			// exit();
			if ($Cotizacion->save()) {
				if (!isset($_POST['ajax'])) {
					Yii::app()->user->setFlash('success', "Se ha guardado con éxito");
					// Redireccionamos de la pagina de donde viene
					$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
					$this->redirect($urlfrom);
				} else {
					echo CJSON::encode(
						array(
							'requestresult' => 'ok',
							'message' => 'Guardaro con exito'
						)
					);
				}
			}
		}
	}

	public function actionCondicionesgenerales()
	{
		$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
		// Obtenemos los datos de la cotizacion
		$DatosCotizacion = Cotizaciones::model()->findBypk($id);

		// Obtenemos los datos del cliente, seleccionado
		$DatosCliente = Clientes::model()->findBypk($DatosCotizacion->id_cliente);

		// Mandamos el modelo de cotizacionesproductos
		$model = new Cotizacionesproductos;

		// Obtenemos los productos en la cotizacion
		$productoscotizacion = Cotizacionesproductos::model()->findAll('id_cotizacion=' . $DatosCotizacion->id_cotizacion);

		// Cargamos el modelo para agregar archivos
		$model_cot_arch = new Cotizacionesarchivos;

		// Obtenemos los archivos
		$archivoscot = Cotizacionesarchivos::model()->findAll('id_cotizacion=' . $DatosCotizacion->id_cotizacion);

		// Obtenemos las plantillas de la cotizacion
		$CotPlantillas = CHtml::listData(Cotizacionesplantterminos::model()->findAll(), 'id', 'nombre');

		// Si seleccione cotde plantilla obtenemos los campos
		$idcotplant = (isset($_GET['plantillacot'])) ? $_GET['plantillacot'] : 0;

		$DatosCotPlantilla = Cotizacionesplantterminos::model()->findBypk($idcotplant);

		// echo "<pre>";
		// print_r($DatosCotizacion);
		// echo "</pre>";
		// exit();

		if (empty($DatosCotPlantilla)) {
			$DatosCotPlantilla = Cotizacionesplantterminos::model()->find();
		}

		$this->render(
			'condicionesgenerales',
			array(
				'DatosCliente' => $DatosCliente,
				'model' => $model,
				'productoscotizacion' => $productoscotizacion,
				'DatosCotizacion' => $DatosCotizacion,
				'model_cot_arch' => $model_cot_arch,
				'archivoscot' => $archivoscot,
				'CotPlantillas' => $CotPlantillas,
				'idcotplant' => $idcotplant,
				'DatosCotPlantilla' => $DatosCotPlantilla
			)
		);


	}



	public function actionAutorizarenvio()
	{





		$id = (isset($_GET['id'])) ? $_GET['id'] : 0;



		// Obtenemos los datos de la cotizacion
		$DatosCotizacion = Cotizaciones::model()->findBypk($id);

		// Obtenemos los datos del cliente, seleccionado
		$DatosCliente = Clientes::model()->findBypk($DatosCotizacion->id_cliente);

		$this->render(
			'autorizarenvio',
			array(
				'DatosCliente' => $DatosCliente,
				'DatosCotizacion' => $DatosCotizacion

			)
		);

	}




	public function actionAutorizar()
	{

		$id = (isset($_GET['id'])) ? $_GET['id'] : 0;


		$model = Cotizaciones::model()->findBypk($id);
		$model->habilitar_envio = 1;
		$model->save();

		Yii::app()->user->setFlash('success', "Se ha autorizo con éxito");
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);


	}



	public function actionEnviarcotizacion()
	{
		if (isset($_POST['enviarcotizacion'])) {
			// validamos el correo
			//  $validator = new CEmailValidator;
			//  if(!$validator->validateValue($_POST['emailcliente']))
			//  {
			// 	 // terminamos el proceso.
			// 	 echo CJSON::encode(array(
			// 		   'requestresult' => 'fail',
			// 		 'message' => 'Correo electronico no valido',
			// 	 ));
			// 	 Yii::app()->end();
			//  }
			$descuentos = ($this->VerificarAcceso(8, Yii::app()->user->id)) == 1 ? false : true;
			// Realizamos el proceso para enviar la cotizacion
			$id_cotizacion = $_POST['id_cotizacion'];

			$DatosCotizacion = Cotizaciones::model()->findBypk($id_cotizacion);
			// Obtenemos los datos del cliente, seleccionado
			$DatosCliente = Clientes::model()->findBypk($DatosCotizacion->id_cliente);

			$DetalleCotizacion = Cotizacionesproductos::model()->findAll(
				"id_cotizacion=:id_cotizacion",
				array(':id_cotizacion' => $id_cotizacion)
			);
			$DatosConfiguracion = Configuracion::model()->findBypk(1);
			# mPDF
			// $mPDF1 = Yii::app()->ePdf->mpdf();
			# You can easily override default constructor's params
			// $mPDF1 = Yii::app()->ePdf->mpdf('c', 'A4', '', '', 0, 0, 0, 0, 0, 0, '');
			// $mPDF1->SetDisplayMode('fullpage');
			// $mPDF1->list_indent_first_level = 0;
			// Obtenemos los archivos
			$archivoscot = Cotizacionesarchivos::model()->findAll('id_cotizacion=' . $DatosCotizacion->id_cotizacion . ' and agregar_a_cotizacion = 1');


			$dompdf = new Dompdf();
			$fecha_formateada = date("d F Y", strtotime($DatosCotizacion['cotizacion_fecha_alta']));
			# renderPartial (only 'view' of current controller)
			// $mPDF1->WriteHTML($this->renderPartial('pdf', array(
			// 	'Datos' => $DatosCotizacion,
			// 	'Detalle' => $DetalleCotizacion,
			// 	'Detalleprod' => $DetalleCotizacion,
			// 	'DatosCliente' => $DatosCliente,
			// 	'DatosConfiguracion' => $DatosConfiguracion,
			// 	'archivoscot' => $archivoscot
			// ), true));
			$html = $this->renderPartial('pdf', array(

				'Datos' => $DatosCotizacion,
				'Detalle' => $DetalleCotizacion,
				'Detalleprod' => $DetalleCotizacion,
				'DatosCliente' => $DatosCliente,
				'DatosConfiguracion' => $DatosConfiguracion,
				'archivoscot' => $archivoscot,
				'fecha' => $fecha_formateada,
				'descuentos' => $descuentos
			), true);
			$dompdf->loadHtml($html);
			// $dompdf->loadHtml($html);
			$dompdf->setPaper('letter', 'portrait'); //->tamaño y orientacion de la hoja
			$dompdf->render();
			# Outputs ready PDF
			// $attachment = $mPDF1->Output('Cotizacion-' . $id_cotizacion . '-' . date('Y') . '.pdf', 'S'); //Nombre del pdf y parámetro para ver pdf o descargarlo directamente.
			// $dompdf->stream('Cotizacion-' . $id_cotizacion . '-' . date('Y') . '.pdf'); //Nombre del pdf y parámetro para ver pdf o descargarlo directamente.
			$attachment = $dompdf->output();
			// $attachment = Swift_Attachment::newInstance($mpdf->Output($pdf_path, "S"), $pdf_file_name, 'application/pdf');
			//  $message->attach($attachment); 
			// Verificamos que el cliente tenga correo
			if ($_POST['correo'] != '' && $_POST['comentarioscotizacion'] != '') {
				// Obtenemos los datos del usuario
				$DatosUsuario = Usuarios::model()->findBypk(Yii::app()->user->id);
				// termina 
				// Obtenemos el campo de descripcion
				$Comentarioscot = $_POST['comentarioscotizacion'];
				// Hacemos la firma de la cotizacion.
				$Comentarioscot .= '<br><br><h1><center>' . $DatosUsuario->Usuario_Nombre . '</center></h1>';
				$Comentarioscot .= '<center>' . $this->ObtenerLogotipo() . '</center>';
				$Comentarioscot .= '
						<div style="display:block;text-align:center">

						 	<h5>
						 		' . $DatosConfiguracion['nombre_compania'] . '
						 	</h5>';
				if ($DatosConfiguracion['direccion'] != "") {
					$Comentarioscot .= $DatosConfiguracion["direccion"] . '<br>';
				}
				if ($DatosConfiguracion['descripcion'] != "") {
					$Comentarioscot .= $DatosConfiguracion["descripcion"] . '<br>';
				}
				if ($DatosConfiguracion['correo'] != "") {
					$Comentarioscot .= $DatosConfiguracion["correo"] . '<br>';
				}
				if ($DatosConfiguracion['telefonos'] != "") {
					$Comentarioscot .= $DatosConfiguracion["telefonos"] . '<br>';
				}
				if ($DatosConfiguracion['web'] != "") {
					$Comentarioscot .= $DatosConfiguracion["web"] . '<br>';
				}
				$Comentarioscot .= '</div>';

				// Enviamos el correo
				Yii::import('ext.yii-mail.YiiMailMessage');
				$message = new YiiMailMessage;
				$message->contentType = "text/html";
				$message->subject = 'Envio cotizacion # ' . $DatosCotizacion->id_cotizacion . ' de ' . Yii::app()->name;
				$message->setBody($Comentarioscot, 'text/html');

				$_POST['correo'] = preg_replace('/\s+/', ' ', $_POST['correo']);
				$correos = explode(',', $_POST['correo']);
				foreach ($correos as $correo) {
					$message->addTo($correo);
				}

				// $message->from = $DatosUsuario->Usuario_Email;
				$message->from = Yii::app()->params['frommail'];
				// Guardamos el PDF en una variable
				$archivopdf = Swift_Attachment::newInstance($attachment, 'Cotizacion-' . $id_cotizacion . '-' . date('Y') . '.pdf', 'application/pdf');
				$message->attach($archivopdf);

				Yii::app()->mail->send($message);


				Yii::app()->user->setFlash('success', "Se envio con exito el correo.");
				// Redireccionamos de la pagina de donde viene
				$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
				$this->redirect($urlfrom);

			} else {

				Yii::app()->user->setFlash('danger', "El cliente no cuenta con correo o no a escrito ninguna descripción");
				// Redireccionamos de la pagina de donde viene
				$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
				$this->redirect($urlfrom);
			}



		}
		$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
		// Obtenemos los datos de la cotizacion
		$DatosCotizacion = Cotizaciones::model()->findBypk($id);
		// Obtenemos los datos del cliente, seleccionado
		$DatosCliente = Clientes::model()->findBypk($DatosCotizacion->id_cliente);



		$this->render(
			'enviarcorreo',
			array(
				'DatosCliente' => $DatosCliente,
				'DatosCotizacion' => $DatosCotizacion
			)
		);
	}
	/***
	 * *ACtion para actualizar los prodcutos de la cotizacion ->lars 02/11/2022
	 */

	public function actionActualizarproductoscot()
	{
		if (!empty($_POST)) {

			$id = isset($_POST['Cotizacionesproductos']['id_cotizacion_producto']) ? $_POST['Cotizacionesproductos']['id_cotizacion_producto'] : '';
			$cantidad = isset($_POST['Cotizacionesproductos']['cotizacion_producto_cantidad']) ? $_POST['Cotizacionesproductos']['cotizacion_producto_cantidad'] : '';
			$unitario = isset($_POST['Cotizacionesproductos']['cotizacion_producto_unitario']) ? $_POST['Cotizacionesproductos']['cotizacion_producto_unitario'] : '';
			$colortapi = isset($_POST['Cotizacionesproductos']['color_tapiceria']) ? $_POST['Cotizacionesproductos']['color_tapiceria'] : '';
			$color = isset($_POST['Cotizacionesproductos']['color']) ? $_POST['Cotizacionesproductos']['color'] : '';
			$desc = isset($_POST['Cotizacionesproductos']['cotizacion_producto_descripcion']) ? $_POST['Cotizacionesproductos']['cotizacion_producto_descripcion'] : '';
			$espext = isset($_POST['Cotizacionesproductos']['especificaciones_extras']) ? $_POST['Cotizacionesproductos']['especificaciones_extras'] : '';
			$tdesc = isset($_POST['Cotizacionesproductos']['tipo_descuetno']) ? $_POST['Cotizacionesproductos']['tipo_descuetno'] : '';
			$descuento = isset($_POST['Cotizacionesproductos']['descuento']) ? $_POST['Cotizacionesproductos']['descuento'] : '';



			// actualizamos la fila que traemos 



			$prodcuto = Cotizacionesproductos::model()->find('id_cotizacion_producto=:id', array(':id' => $id));

			// si la fila que traemos tiende descuetnso diferentes a los que nos pasan quiere decir que actualizaron los descuentos 
			// y guardamos primero esos
			if ($prodcuto['descuento'] != $descuento || $prodcuto['tipo_descuetno'] != $tdesc) {
				$prodcuto['descuento'] = $descuento;
				$prodcuto['tipo_descuetno'] = $tdesc;
				$prodcuto->save();
			}

			if (!empty($prodcuto)) {
				$total = $unitario * $cantidad;
				// hay que revisar si trae desceutno 
				if (!empty($prodcuto->tipo_descuetno) || $prodcuto->tipo_descuetno != null) {
					// si entra aqui rae desceutno y evaluamos
					if ($prodcuto->tipo_descuetno == 'porcentaje') {
						// sacamos el porcietno
						$porciento = $unitario * ($prodcuto->descuento / 100);
						// el resultado se lo quitamos al unitario
						$total = ($unitario - $porciento) * $cantidad;
					} else {
						// si no es descuento es monto
						$total = ($unitario - $prodcuto->descuento) * $cantidad;
					}
				}
				$prodcuto->cotizacion_producto_cantidad = $cantidad;
				$prodcuto->cotizacion_producto_unitario = $unitario;
				$prodcuto->color_tapiceria = $colortapi;
				$prodcuto->color = $color;
				$prodcuto->cotizacion_producto_descripcion = $desc;
				$prodcuto->especificaciones_extras = $espext;
				$prodcuto->cotizacion_producto_total = $total;

				if ($prodcuto->save()) {
					// echo "<pre>";
					// print_r('guardo');
					// echo "</pre>";
					// exit();
					Yii::app()->user->setFlash('success', "Datos actualizados con exito");
					$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
					$this->redirect($urlfrom);
				} else {
					// echo "<pre>";
					// print_r('no guardo');
					// echo "</pre>";
					// exit();
					Yii::app()->user->setFlash('danger', "Ocurrio un error inesperado");
					$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
					$this->redirect($urlfrom);

				}

			}
		}
	}

	// action para cancelar la cotizacion ->lars 21/11/23

	public function actionCancelar()
	{
		// echo "<pre>";
		// print_r($_GET);
		// echo "</pre>";
		// exit();
		// recibimos el id de la corizacion
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		// buscamos que la cotizacion no tenga pedido
		$cot = Cotizaciones::model()->find(
			array(
				'condition' => 'id_cotizacion = :id ',
				'params' => array(':id' => $id)
			)
		);
		// verificamos si tiene datos, si tiene eviamos que cancele
		if ($cot['cotizacion_estatus'] = 2 && $cot['pedido'] = 0) {
			Yii::app()->user->setFlash('warning', "La cotización no se puede cancelar");
			$this->redirect(Yii::app()->createUrl('cotizaciones/condicionesgenerales/' . $id));
		}
		// si se puede cancelar cambiamos el estatus 
		$cot->cotizacion_estatus = 2;
		if ($cot->save()) {
			if (isset($_GET['lista'])) {
				if ($_GET['lista'] == 1) {
					$this->redirect(Yii::app()->createUrl('cotizaciones/lista/'));
				}
			}
			Yii::app()->user->setFlash('warning', "La cotización ha sido cancelada");

			$this->redirect(Yii::app()->createUrl('cotizaciones/condicionesgenerales/' . $id));

		}

	}


	// metodo para agregar el iva lars->06/05/24
	public function actionModificariva()
	{
		$id = isset($_POST['idcot']) ? $_POST['idcot'] : '';
		$iva = isset($_POST['value']) ? $_POST['value'] : '';

		if (!empty($id)) {
			// actualizmos el iva
			$cot = Cotizaciones::model()->find(array('condition' => 'id_cotizacion = :id', 'params' => array(':id' => $id)));

			$cot->sumar_iva = $iva;

			if ($cot->save()) {
				// $this->redirect(Yii::app()->createUrl('cotizaciones/actualizarcotizacion/' . $id));
				echo CJSON::encode(
					array(
						'requestresult' => 'ok',
						'message' => 'Iva actualizado correctamente'
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
		} else {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Cotizacion no encontrada'
				)
			);
		}

	}
}