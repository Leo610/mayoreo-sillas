<?php

require_once(__DIR__ . '/../vendor/dompdf/autoload.inc.php');
use Dompdf\Dompdf;

class RhvacacionesController extends Controller
{
	public function init()
	{
		if (Yii::app()->user->isGuest) {
			$this->redirect(array('site/login'));
		}
	}

	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'users' => array('@'),
			),
			array('deny',
				'users' => array('*'),
			),
		);
	}

	/**
	 * Listado general de vacaciones
	 */
	public function actionIndex()
	{
		$this->layout = "main";

		$filtro_empleado = isset($_GET['empleado']) ? $_GET['empleado'] : '';
		$filtro_anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
		$filtro_estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';

		$criteria = array('order' => 'vacacion_fecha_inicio DESC');
		$conditions = array();
		$params = array();

		if (!empty($filtro_empleado)) {
			$conditions[] = 'id_empleado = :emp';
			$params[':emp'] = $filtro_empleado;
		}
		if (!empty($filtro_anio)) {
			$conditions[] = 'YEAR(vacacion_fecha_inicio) = :anio';
			$params[':anio'] = $filtro_anio;
		}
		if (!empty($filtro_estatus)) {
			$conditions[] = 'vacacion_estatus = :est';
			$params[':est'] = $filtro_estatus;
		}

		if (!empty($conditions)) {
			$criteria['condition'] = implode(' AND ', $conditions);
			$criteria['params'] = $params;
		}

		$lista = RhVacaciones::model()->findAll($criteria);
		$empleados = Empleados::model()->findAll(array('order' => 'empleado_nombre ASC', 'condition' => 'empleado_estatus="ACTIVO"'));

		$this->render('index', array(
			'lista' => $lista,
			'empleados' => $empleados,
			'filtro_empleado' => $filtro_empleado,
			'filtro_anio' => $filtro_anio,
			'filtro_estatus' => $filtro_estatus,
		));
	}

	/**
	 * Historial de empleados - log de movimientos (ALTA, BAJA, REINGRESO, etc.)
	 */
	public function actionHistorial()
	{
		$this->layout = "main";

		$filtro_empleado = isset($_GET['empleado']) ? $_GET['empleado'] : '';
		$filtro_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

		$criteria = new CDbCriteria;
		$criteria->with = array('rl_empleado');
		$criteria->order = 't.historial_fecha_movimiento DESC';

		if (!empty($filtro_empleado)) {
			$criteria->addCondition('t.id_empleado = :emp');
			$criteria->params[':emp'] = $filtro_empleado;
		}
		if (!empty($filtro_tipo)) {
			$criteria->addCondition('t.historial_tipo = :tipo');
			$criteria->params[':tipo'] = $filtro_tipo;
		}

		$lista = RhEmpleadosHistorial::model()->findAll($criteria);
		$empleados = Empleados::model()->findAll(array('order' => 'empleado_nombre ASC'));

		$this->render('historial', array(
			'lista' => $lista,
			'empleados' => $empleados,
			'filtro_empleado' => $filtro_empleado,
			'filtro_tipo' => $filtro_tipo,
		));
	}

	/**
	 * AJAX: Registrar nueva vacacion
	 */
	public function actionRegistrar()
	{
		if (!isset($_POST['id_empleado'])) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Datos incompletos'));
			Yii::app()->end();
		}

		$id_empleado = $_POST['id_empleado'];
		$fecha_inicio = $_POST['fecha_inicio'];
		$fecha_fin = $_POST['fecha_fin'];
		$observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : '';

		$empleado = Empleados::model()->findByPk($id_empleado);
		if (empty($empleado)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Empleado no encontrado'));
			Yii::app()->end();
		}

		if ($empleado->getDiasVacaciones() <= 0) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'El empleado aun no tiene derecho a vacaciones (menos de 1 anio de antiguedad)'));
			Yii::app()->end();
		}

		$diasHabiles = RhVacaciones::calcularDiasHabiles($fecha_inicio, $fecha_fin);

		if ($diasHabiles <= 0) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'El rango de fechas no contiene dias habiles'));
			Yii::app()->end();
		}

		$diasDisponibles = $empleado->getDiasDisponibles();
		if ($diasHabiles > $diasDisponibles) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'El empleado solo tiene ' . $diasDisponibles . ' dias disponibles y se solicitan ' . $diasHabiles));
			Yii::app()->end();
		}

		// Validar que el mismo empleado no tenga vacaciones (APROBADA o PENDIENTE) que se solapan
		$solapeMismo = RhVacaciones::model()->find(
			'id_empleado = :id AND vacacion_estatus IN ("APROBADA","PENDIENTE") AND vacacion_fecha_inicio <= :fin AND vacacion_fecha_fin >= :ini',
			array(':id' => $id_empleado, ':fin' => $fecha_fin, ':ini' => $fecha_inicio)
		);
		if (!empty($solapeMismo)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'El empleado ya tiene vacaciones registradas del ' . date('d/m/Y', strtotime($solapeMismo->vacacion_fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($solapeMismo->vacacion_fecha_fin)) . ' que se cruzan con las fechas solicitadas'));
			Yii::app()->end();
		}

		// Validar que ningun otro empleado tenga vacaciones (APROBADA o PENDIENTE) en las mismas fechas
		$solapeOtro = RhVacaciones::model()->find(
			'id_empleado != :id AND vacacion_estatus IN ("APROBADA","PENDIENTE") AND vacacion_fecha_inicio <= :fin AND vacacion_fecha_fin >= :ini',
			array(':id' => $id_empleado, ':fin' => $fecha_fin, ':ini' => $fecha_inicio)
		);
		if (!empty($solapeOtro)) {
			$otroEmpleado = $solapeOtro->rl_empleado;
			$nombreOtro = !empty($otroEmpleado) ? $otroEmpleado->empleado_nombre : 'Otro empleado';
			echo CJSON::encode(array('requestresult' => 'error', 'message' => $nombreOtro . ' ya tiene vacaciones del ' . date('d/m/Y', strtotime($solapeOtro->vacacion_fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($solapeOtro->vacacion_fecha_fin)) . '. No pueden estar dos personas de vacaciones al mismo tiempo.'));
			Yii::app()->end();
		}

		$model = new RhVacaciones;
		$model->id_empleado = $id_empleado;
		$model->vacacion_fecha_inicio = $fecha_inicio;
		$model->vacacion_fecha_fin = $fecha_fin;
		$model->vacacion_dias = $diasHabiles;
		$model->vacacion_estatus = 'PENDIENTE';
		$model->vacacion_observaciones = $observaciones;
		$model->id_usuario = Yii::app()->user->id;

		if ($model->save()) {
			echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Solicitud registrada (' . $diasHabiles . ' dias habiles). Pendiente de aprobacion.'));
		} else {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al guardar'));
		}
		Yii::app()->end();
	}

	/**
	 * AJAX: Aprobar vacacion pendiente
	 */
	public function actionAprobar()
	{
		if (!isset($_POST['id_vacacion'])) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'ID requerido'));
			Yii::app()->end();
		}

		$model = RhVacaciones::model()->findByPk($_POST['id_vacacion']);
		if (empty($model)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Registro no encontrado'));
			Yii::app()->end();
		}

		if ($model->vacacion_estatus != 'PENDIENTE') {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Solo se pueden aprobar solicitudes pendientes'));
			Yii::app()->end();
		}

		$model->vacacion_estatus = 'APROBADA';
		if ($model->save()) {
			echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Vacacion aprobada correctamente (' . $model->vacacion_dias . ' dias)'));
		} else {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al aprobar'));
		}
		Yii::app()->end();
	}

	/**
	 * AJAX: Rechazar vacacion pendiente
	 */
	public function actionRechazar()
	{
		if (!isset($_POST['id_vacacion'])) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'ID requerido'));
			Yii::app()->end();
		}

		$model = RhVacaciones::model()->findByPk($_POST['id_vacacion']);
		if (empty($model)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Registro no encontrado'));
			Yii::app()->end();
		}

		if ($model->vacacion_estatus != 'PENDIENTE') {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Solo se pueden rechazar solicitudes pendientes'));
			Yii::app()->end();
		}

		$model->vacacion_estatus = 'RECHAZADA';
		if ($model->save()) {
			echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Vacacion rechazada'));
		} else {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al rechazar'));
		}
		Yii::app()->end();
	}

	/**
	 * AJAX: Cancelar vacacion
	 */
	public function actionCancelar()
	{
		if (!isset($_POST['id_vacacion'])) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'ID requerido'));
			Yii::app()->end();
		}

		$model = RhVacaciones::model()->findByPk($_POST['id_vacacion']);
		if (empty($model)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Registro no encontrado'));
			Yii::app()->end();
		}

		$model->vacacion_estatus = 'CANCELADA';
		if ($model->save()) {
			echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Vacacion cancelada correctamente'));
		} else {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al cancelar'));
		}
		Yii::app()->end();
	}

	/**
	 * AJAX: Calcular dias habiles entre dos fechas
	 */
	public function actionCalculardias()
	{
		$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
		$fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';

		if (empty($fecha_inicio) || empty($fecha_fin)) {
			echo CJSON::encode(array('requestresult' => 'error', 'dias' => 0));
			Yii::app()->end();
		}

		$dias = RhVacaciones::calcularDiasHabiles($fecha_inicio, $fecha_fin);
		echo CJSON::encode(array('requestresult' => 'ok', 'dias' => $dias));
		Yii::app()->end();
	}

	/**
	 * Vista de dias festivos
	 */
	public function actionFestivos()
	{
		$this->layout = "main";

		$filtro_anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

		$criteria = array('order' => 'festivo_fecha ASC');
		if (!empty($filtro_anio)) {
			$criteria['condition'] = 'festivo_anio = :anio';
			$criteria['params'] = array(':anio' => $filtro_anio);
		}

		$lista = RhDiasFestivos::model()->findAll($criteria);

		$this->render('festivos', array(
			'lista' => $lista,
			'filtro_anio' => $filtro_anio,
		));
	}

	/**
	 * AJAX: Crear dia festivo
	 */
	public function actionCrearfestivo()
	{
		if (!isset($_POST['festivo_fecha'])) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Datos incompletos'));
			Yii::app()->end();
		}

		$model = new RhDiasFestivos;
		$model->festivo_fecha = $_POST['festivo_fecha'];
		$model->festivo_descripcion = $_POST['festivo_descripcion'];
		$model->festivo_anio = date('Y', strtotime($_POST['festivo_fecha']));

		if ($model->save()) {
			echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Dia festivo agregado'));
		} else {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al guardar'));
		}
		Yii::app()->end();
	}

	/**
	 * AJAX: Eliminar dia festivo
	 */
	public function actionEliminarfestivo()
	{
		if (!isset($_POST['id'])) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'ID requerido'));
			Yii::app()->end();
		}

		$model = RhDiasFestivos::model()->findByPk($_POST['id']);
		if (!empty($model)) {
			$model->delete();
			echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Dia festivo eliminado'));
		} else {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'No encontrado'));
		}
		Yii::app()->end();
	}

	/**
	 * Formato de fecha estilo Excel: DD-mmm-YY (ej: 26-ene-26)
	 */
	private function fechaFormatoPDF($fecha)
	{
		$meses = array(
			1 => 'ene', 2 => 'feb', 3 => 'mar', 4 => 'abr',
			5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'ago',
			9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dic'
		);
		$d = date('d', strtotime($fecha));
		$m = (int)date('m', strtotime($fecha));
		$y = date('y', strtotime($fecha));
		return $d . '-' . $meses[$m] . '-' . $y;
	}

	/**
	 * Generar formato PDF de vacaciones
	 */
	public function actionFormato($id)
	{
		$vacacion = RhVacaciones::model()->findByPk($id);
		if (empty($vacacion)) {
			throw new CHttpException(404, 'Registro no encontrado');
		}

		$empleado = $vacacion->rl_empleado;
		$registradoPor = $vacacion->rl_usuario;
		$nombreUsuario = !empty($registradoPor) ? $registradoPor->Usuario_Nombre : '';

		$fechaInicio = $this->fechaFormatoPDF($vacacion->vacacion_fecha_inicio);
		$fechaRegistro = $this->fechaFormatoPDF($vacacion->vacacion_fecha_registro);

		// Calcular fecha de regreso (siguiente dia despues de fecha_fin)
		$regreso = new DateTime($vacacion->vacacion_fecha_fin);
		$regreso->modify('+1 day');
		$fechaRegreso = $this->fechaFormatoPDF($regreso->format('Y-m-d'));

		$saldoActual = $empleado->getDiasVacaciones();
		$diasSolicitados = $vacacion->vacacion_dias;
		$saldoNuevo = $empleado->getDiasDisponibles();

		// Logos en base64 para que dompdf los cargue sin problemas
		$logoOvisaPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'companias' . DIRECTORY_SEPARATOR . 'IND_OVI_MED.png';
		$logoMayoreoPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'companias' . DIRECTORY_SEPARATOR . '1502-cropped-logo-fotter-ms.jpg';

		$logoOvisa = '';
		$logoMayoreo = '';
		if (file_exists($logoOvisaPath)) {
			$logoOvisa = 'data:image/png;base64,' . base64_encode(file_get_contents($logoOvisaPath));
		}
		if (file_exists($logoMayoreoPath)) {
			$logoMayoreo = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoMayoreoPath));
		}

		$html = '
		<style>
			body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 15px 25px; }
			.header-table { width: 100%; margin-bottom: 0; }
			.header-table td { vertical-align: middle; }
			.titulo-central { font-size: 16px; font-weight: bold; text-align: center; text-decoration: underline; }
			.reglas-box { border: 1px solid #000; padding: 6px 8px; font-size: 9px; line-height: 1.4; margin-top: 5px; }
			.reglas-box b { font-size: 9px; display: block; text-align: center; margin-bottom: 3px; }
			.datos-section { margin-top: 8px; }
			.datos-row { margin-bottom: 2px; font-size: 11px; }
			.datos-row span.lbl { font-weight: bold; }
			.mensaje-box { border-top: 2px solid #000; border-bottom: 1px solid #000; padding: 8px 0; margin: 12px 0 5px; text-align: center; font-weight: bold; font-size: 11px; }
			.tabla-mov { width: 100%; border-collapse: collapse; margin: 5px 0; }
			.tabla-mov th { border-bottom: 2px solid #000; padding: 4px 3px; font-size: 9px; text-align: center; font-weight: bold; }
			.tabla-mov td { padding: 4px 3px; font-size: 10px; text-align: center; }
			.fila-datos td { border-bottom: 1px solid #999; }
			.num-col { font-weight: bold; text-align: left; padding-left: 5px; }
			.firmas-table { width: 100%; margin-top: 80px; }
			.firmas-table td { vertical-align: top; padding: 0; }
			.firma-bloque { text-align: left; }
			.firma-bloque-der { text-align: right; }
			.firma-linea { border-top: 1px solid #000; display: inline-block; padding-top: 3px; font-size: 10px; font-weight: bold; }
			.firma-nombre { font-size: 10px; font-weight: bold; }
			.firma-centro { text-align: center; margin-top: 60px; }
		</style>

		<!-- HEADER: Logo Ovisa | Titulo | Logo Mayoreo -->
		<table class="header-table">
			<tr>
				<td style="width:15%;"><img src="' . $logoOvisa . '" style="width:80px;"></td>
				<td style="width:50%; text-align:center;">
					<div class="titulo-central">MOVIMIENTOS DE VACACIONES</div>
				</td>
				<td style="width:15%; text-align:right;"><img src="' . $logoMayoreo . '" style="width:100px;"></td>
			</tr>
		</table>

		<!-- REGLAS + DATOS en dos columnas -->
		<table style="width:100%; margin-top:5px;">
			<tr>
				<td style="width:55%; vertical-align:top;">
					<div class="datos-section">
						<div class="datos-row"><span class="lbl">Nombre:</span> &nbsp;&nbsp; <u>' . CHtml::encode($empleado->empleado_nombre) . '</u></div>
						<div class="datos-row"><span class="lbl">Para:</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u>Administracion de Personal de R. H.</u></div>
						<div class="datos-row"><span class="lbl">Fecha De Ingreso:</span> &nbsp;&nbsp; <u>' . date('d/m/Y', strtotime($empleado->empleado_fecha_ingreso)) . '</u></div>
						<div class="datos-row"><span class="lbl">Area:</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u>' . CHtml::encode($empleado->empleado_puesto) . '</u></div>
					</div>
				</td>
				<td style="width:45%; vertical-align:top;">
					<div class="reglas-box">
						<b>REGLAS EN LA PROGRAMACION DE VACACIONES</b>
						1.- El personal debe notificar con un minimo de anticipacion de 7 dias.<br>
						2.- Por ningun motivo se otorgara vacaciones si otro companero se encuentra de vacaciones (quiere decir que dos personas no pueden estar de vacaciones al mismo tiempo).<br>
						3.- No podran ser otorgadas las vacaciones cuando no se cuente con personal suficiente en determinada area para cubrir con las labores diarias.
					</div>
				</td>
			</tr>
		</table>

		<!-- MENSAJE -->
		<div class="mensaje-box">
			POR MEDIO DE LA PRESENTE, SOLICITAMOS REALIZAR LOS TRAMITES<br>
			QUE CORRESPONDAN PARA PROCESAR LOS SIGUIENTES MOVIMIENTOS
		</div>

		<!-- TABLA DE MOVIMIENTOS -->
		<table class="tabla-mov">
			<thead>
				<tr>
					<th style="width:5%;"></th>
					<th style="width:25%; text-align:left;">Nombre</th>
					<th style="width:8%;">dias<br>Solicit.</th>
					<th style="width:8%;">Saldo<br>Actual</th>
					<th style="width:8%;">Saldo<br>Nuevo</th>
					<th style="width:14%;">Fecha de<br>Solicitud</th>
					<th style="width:14%;">Fecha<br>Inicio</th>
					<th style="width:14%;">Fecha<br>Regreso</th>
				</tr>
			</thead>
			<tbody>
				<tr class="fila-datos">
					<td class="num-col">1-</td>
					<td style="text-align:left; font-weight:bold;">' . CHtml::encode($empleado->empleado_nombre) . '</td>
					<td>' . $diasSolicitados . '</td>
					<td>' . $saldoActual . '</td>
					<td>' . $saldoNuevo . '</td>
					<td>' . $fechaRegistro . '</td>
					<td>' . $fechaInicio . '</td>
					<td>' . $fechaRegreso . '</td>
				</tr>
			</tbody>
		</table>

		' . (!empty($vacacion->vacacion_observaciones) ? '<div style="margin-top:8px; font-size:10px;"><b>Observaciones:</b> ' . CHtml::encode($vacacion->vacacion_observaciones) . '</div>' : '') . '

		<!-- FIRMAS -->
		<table class="firmas-table">
			<tr>
				<td style="width:50%;">
					<div class="firma-bloque">
						<div class="firma-linea" style="width:220px;">FIRMA DEL SOLICITANTE</div><br>
						<div class="firma-nombre">' . CHtml::encode($empleado->empleado_nombre) . '</div>
					</div>
				</td>
				<td style="width:50%;">
					<div class="firma-bloque-der">
						<div class="firma-linea" style="width:220px;">JEFE DIRECTO</div><br>
						<div class="firma-nombre">ING. CARLOS CASTILLO.</div>
					</div>
				</td>
			</tr>
		</table>

		<div class="firma-centro">
			<div class="firma-linea" style="width:220px;">RECURSOS HUMANOS.</div><br>
			<div class="firma-nombre">C.P. JUAN CARLOS.</div>
		</div>
		';

		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('letter', 'portrait');
		$dompdf->render();
		$dompdf->stream('Vacaciones_' . str_pad($vacacion->id_vacacion, 5, '0', STR_PAD_LEFT) . '.pdf', array('Attachment' => false));
		Yii::app()->end();
	}
}
