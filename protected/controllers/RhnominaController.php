<?php

require_once(__DIR__ . '/../vendor/dompdf/autoload.inc.php');
use Dompdf\Dompdf;

class RhnominaController extends Controller
{
	public function init()
	{
		if (Yii::app()->user->isGuest) {
			$this->redirect(array('site/login'));
		}
	}

	public function filters()
	{
		return array('accessControl');
	}

	public function accessRules()
	{
		return array(
			array('allow', 'users' => array('@')),
			array('deny', 'users' => array('*')),
		);
	}

	// =========================================================================
	// PERIODOS
	// =========================================================================

	public function actionPeriodos()
	{
		$this->layout = "main";
		$lista = RhNominaPeriodos::model()->findAll(array('order' => 'periodo_fecha_inicio DESC'));
		$this->render('periodos', array('lista' => $lista));
	}

	public function actionCrearperiodo()
	{
		$fi = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
		$ff = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';

		if (empty($fi) || empty($ff)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Fechas requeridas'));
			Yii::app()->end();
		}

		if ($fi > $ff) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'La fecha inicio no puede ser mayor a la fecha fin'));
			Yii::app()->end();
		}

		// Validar que inicio sea viernes y fin sea jueves
		$diaInicio = date('N', strtotime($fi)); // 5 = viernes
		$diaFin = date('N', strtotime($ff));    // 4 = jueves
		if ($diaInicio != 5) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'La fecha de inicio debe ser un VIERNES'));
			Yii::app()->end();
		}
		if ($diaFin != 4) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'La fecha fin debe ser un JUEVES'));
			Yii::app()->end();
		}

		// Verificar que no exista un periodo que se solape
		$solape = RhNominaPeriodos::model()->find(
			'periodo_fecha_inicio <= :fin AND periodo_fecha_fin >= :ini',
			array(':fin' => $ff, ':ini' => $fi)
		);
		if (!empty($solape)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Ya existe un periodo que se cruza con estas fechas (' . $solape->getEtiqueta() . ')'));
			Yii::app()->end();
		}

		$periodo = new RhNominaPeriodos;
		$periodo->periodo_fecha_inicio = $fi;
		$periodo->periodo_fecha_fin = $ff;
		$periodo->periodo_estatus = 'ABIERTO';
		$periodo->id_usuario = Yii::app()->user->id;

		if (!$periodo->save()) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al guardar periodo'));
			Yii::app()->end();
		}

		// Generar registros de asistencia para todos los empleados activos
		$empleados = Empleados::model()->findAll("empleado_estatus = 'ACTIVO'");
		$actual = new DateTime($fi);
		$fin = new DateTime($ff);

		while ($actual <= $fin) {
			$diaSemana = (int)$actual->format('N');
			$fechaStr = $actual->format('Y-m-d');

			foreach ($empleados as $emp) {
				$asist = new RhAsistencia;
				$asist->id_periodo = $periodo->id_periodo;
				$asist->id_empleado = $emp->id_empleado;
				$asist->asistencia_fecha = $fechaStr;

				// Sabado y Domingo = descanso
				if ($diaSemana == 6 || $diaSemana == 7) {
					$asist->asistencia_tipo = 'DESCANSO';
				} elseif (RhDiasFestivos::esFestivo($fechaStr)) {
					$asist->asistencia_tipo = 'FESTIVO';
				} else {
					// Verificar si tiene vacaciones ese dia
					$enVacacion = RhVacaciones::model()->find(
						'id_empleado = :id AND vacacion_estatus = "APROBADA" AND vacacion_fecha_inicio <= :f AND vacacion_fecha_fin >= :f',
						array(':id' => $emp->id_empleado, ':f' => $fechaStr)
					);
					if (!empty($enVacacion)) {
						$asist->asistencia_tipo = 'VACACION';
					} elseif ($emp->empleado_requiere_checador == 'NO') {
						// Sin checador: asistencia completa automatica (8am-6pm, 8hrs netas)
						$asist->asistencia_tipo = 'NORMAL';
						$asist->asistencia_entrada = '08:00';
						$asist->asistencia_salida = '18:00';
						$asist->asistencia_horas = 8;
						$asist->asistencia_retardo = 0;
						$asist->asistencia_minutos_retardo = 0;
					} else {
						$asist->asistencia_tipo = 'FALTA'; // Default hasta que se capture asistencia
					}
				}

				$asist->save();
			}

			$actual->modify('+1 day');
		}

		// Crear registros de nomina para cada empleado
		foreach ($empleados as $emp) {
			$det = new RhNominaDetalles;
			$det->id_periodo = $periodo->id_periodo;
			$det->id_empleado = $emp->id_empleado;
			$det->save();
		}

		echo CJSON::encode(array(
			'requestresult' => 'ok',
			'message' => 'Periodo creado. Se generaron registros para ' . count($empleados) . ' empleados.',
			'id_periodo' => $periodo->id_periodo,
		));
		Yii::app()->end();
	}

	/**
	 * Generar PDF de recibos de aguinaldo (sin guardar en BD)
	 * Replica exacta del VBA Aguinaldo.bas:
	 * - D10 (Sueldo Nominal) = sueldo_diario × 15 (o proporcional)
	 * - D11 (Premio Asistencia) = (sueldo_real/7) × dias - D10
	 * - ISR, IMSS, INFONAVIT = 0
	 */
	public function actionAguinaldopdf($anio)
	{
		$anio = (int)$anio;
		if ($anio < 2020 || $anio > 2050) {
			throw new CHttpException(400, 'Año no valido');
		}

		$empleados = Empleados::model()->findAll(array(
			'condition' => "empleado_estatus = 'ACTIVO'",
			'order' => 'empleado_nombre ASC',
		));

		// Periodo temporal (no se guarda)
		$periodo = new RhNominaPeriodos;
		$periodo->periodo_fecha_inicio = $anio . '-01-01';
		$periodo->periodo_fecha_fin = $anio . '-12-31';

		$htmlCompleto = '';
		$primero = true;

		foreach ($empleados as $emp) {
			$sueldoDiario = (float)$emp->empleado_sueldo_semanal / 7;
			$sueldoDiarioReal = (float)$emp->empleado_sueldo_semanal_real / 7;

			// VBA: IF(fecha_ingreso < DATE(año,1,1), diario*15, (15/365)*(31dic-ingreso)*diario)
			if ($emp->empleado_fecha_ingreso <= $anio . '-01-01') {
				$diasAguinaldo = 15;
			} else {
				$dtIngreso = new DateTime($emp->empleado_fecha_ingreso);
				$dtFinAnio = new DateTime($anio . '-12-31');
				$diffDias = $dtFinAnio->diff($dtIngreso)->days;
				$diasAguinaldo = (15 / 365) * $diffDias;
			}

			// VBA D10: aguinaldo IMSS
			$aguinaldo = round($sueldoDiario * $diasAguinaldo, 2);
			// VBA D11: compensacion = (sueldo_real/7)*dias - D10
			$compensacion = round($sueldoDiarioReal * $diasAguinaldo - $aguinaldo, 2);
			if ($compensacion < 0) $compensacion = 0;

			$totalPercepciones = round($aguinaldo + $compensacion, 2);

			// Detalle temporal con datos del VBA (no se guarda)
			$det = new RhNominaDetalles;
			$det->total_horas = round($diasAguinaldo * 8, 2);
			$det->sueldo_nominal = $aguinaldo;
			$det->premio_asistencia = $compensacion;
			$det->premio_puntualidad = 0;
			$det->premio_productividad = 0;
			$det->bono_condicional = 0;
			$det->prima_vacacional = 0;
			$det->total_percepciones = $totalPercepciones;
			$det->isr = 0;
			$det->imss = 0;
			$det->infonavit = 0;
			$det->otra_deduccion = 0;
			$det->total_deducciones = 0;
			$det->vales = 0;
			$det->efectivo = $totalPercepciones;
			$det->total_neto = $totalPercepciones;

			if (!$primero) {
				$htmlCompleto .= '<div style="page-break-before: always;"></div>';
			}
			$htmlCompleto .= $this->generarHTMLRecibo($det, $emp, $periodo, true);
			$primero = false;
		}

		$dompdf = new Dompdf();
		$dompdf->loadHtml($htmlCompleto);
		$dompdf->setPaper('letter', 'portrait');
		$dompdf->render();
		$dompdf->stream('Aguinaldo_' . $anio . '.pdf', array('Attachment' => false));
		Yii::app()->end();
	}

	public function actionEliminarperiodo()
	{
		$id = isset($_POST['id_periodo']) ? $_POST['id_periodo'] : '';
		$periodo = RhNominaPeriodos::model()->findByPk($id);

		if (empty($periodo)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Periodo no encontrado'));
			Yii::app()->end();
		}

		if ($periodo->periodo_estatus == 'CERRADO') {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'No se puede eliminar un periodo cerrado'));
			Yii::app()->end();
		}

		// Eliminar asistencias y detalles
		RhAsistencia::model()->deleteAll('id_periodo = :id', array(':id' => $id));
		RhNominaDetalles::model()->deleteAll('id_periodo = :id', array(':id' => $id));
		$periodo->delete();

		echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Periodo eliminado'));
		Yii::app()->end();
	}

	public function actionCerrarperiodo()
	{
		$id = isset($_POST['id_periodo']) ? $_POST['id_periodo'] : '';
		$periodo = RhNominaPeriodos::model()->findByPk($id);

		if (empty($periodo)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Periodo no encontrado'));
			Yii::app()->end();
		}

		$periodo->periodo_estatus = 'CERRADO';
		$periodo->save();
		echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Periodo cerrado'));
		Yii::app()->end();
	}

	// =========================================================================
	// ASISTENCIA
	// =========================================================================

	public function actionAsistencia($id)
	{
		$this->layout = "main";
		$periodo = RhNominaPeriodos::model()->findByPk($id);
		if (empty($periodo)) {
			throw new CHttpException(404, 'Periodo no encontrado');
		}

		// Obtener dias del periodo
		$dias = array();
		$actual = new DateTime($periodo->periodo_fecha_inicio);
		$fin = new DateTime($periodo->periodo_fecha_fin);
		while ($actual <= $fin) {
			$dias[] = $actual->format('Y-m-d');
			$actual->modify('+1 day');
		}

		// Obtener empleados con asistencia en este periodo
		$empleados = Empleados::model()->findAll(array(
			'condition' => "empleado_estatus = 'ACTIVO' OR id_empleado IN (SELECT DISTINCT id_empleado FROM rh_asistencia WHERE id_periodo = :per)",
			'params' => array(':per' => $id),
			'order' => 'empleado_nombre ASC',
		));

		// Obtener asistencias indexadas
		$asistencias = array();
		$registros = RhAsistencia::model()->findAll(
			'id_periodo = :per',
			array(':per' => $id)
		);
		foreach ($registros as $r) {
			$asistencias[$r->id_empleado][$r->asistencia_fecha] = $r;
		}

		$this->render('asistencia', array(
			'periodo' => $periodo,
			'dias' => $dias,
			'empleados' => $empleados,
			'asistencias' => $asistencias,
		));
	}

	public function actionGuardarasistencia()
	{
		$id_asistencia = isset($_POST['id_asistencia']) ? $_POST['id_asistencia'] : '';
		$entrada = isset($_POST['entrada']) ? $_POST['entrada'] : '';
		$salida = isset($_POST['salida']) ? $_POST['salida'] : '';
		$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'NORMAL';
		$justificacion = isset($_POST['justificacion']) ? $_POST['justificacion'] : '';

		$asist = RhAsistencia::model()->findByPk($id_asistencia);
		if (empty($asist)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Registro no encontrado'));
			Yii::app()->end();
		}

		$asist->asistencia_tipo = $tipo;
		$asist->asistencia_justificacion = $justificacion;

		if ($tipo == 'NORMAL' && !empty($entrada) && !empty($salida)) {
			$asist->asistencia_entrada = $entrada;
			$asist->asistencia_salida = $salida;
			$asist->calcular();
		} elseif ($tipo == 'FALTA') {
			$asist->asistencia_entrada = null;
			$asist->asistencia_salida = null;
			$asist->asistencia_horas = 0;
			$asist->asistencia_retardo = 0;
			$asist->asistencia_minutos_retardo = 0;
		} elseif ($tipo == 'VACACION' || $tipo == 'FESTIVO' || $tipo == 'DESCANSO') {
			$asist->asistencia_entrada = null;
			$asist->asistencia_salida = null;
			$asist->asistencia_horas = 0;
			$asist->asistencia_retardo = 0;
			$asist->asistencia_minutos_retardo = 0;
		}

		if ($asist->save()) {
			echo CJSON::encode(array(
				'requestresult' => 'ok',
				'horas' => $asist->asistencia_horas,
				'retardo' => $asist->asistencia_retardo,
				'minutos_retardo' => $asist->asistencia_minutos_retardo,
			));
		} else {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al guardar'));
		}
		Yii::app()->end();
	}

	/**
	 * Guardar asistencia de un empleado completa (todos los dias del periodo)
	 */
	public function actionGuardarasistenciaempleado()
	{
		$datos = isset($_POST['asistencias']) ? $_POST['asistencias'] : array();

		if (empty($datos)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Sin datos'));
			Yii::app()->end();
		}

		$guardados = 0;
		foreach ($datos as $item) {
			$asist = RhAsistencia::model()->findByPk($item['id_asistencia']);
			if (empty($asist)) continue;

			$asist->asistencia_tipo = $item['tipo'];

			if ($item['tipo'] == 'NORMAL' && !empty($item['entrada']) && !empty($item['salida'])) {
				$asist->asistencia_entrada = $item['entrada'];
				$asist->asistencia_salida = $item['salida'];
				$asist->calcular();
			} elseif ($item['tipo'] == 'FALTA') {
				$asist->asistencia_entrada = null;
				$asist->asistencia_salida = null;
				$asist->asistencia_horas = 0;
				$asist->asistencia_retardo = 0;
				$asist->asistencia_minutos_retardo = 0;
			} else {
				$asist->asistencia_entrada = null;
				$asist->asistencia_salida = null;
				$asist->asistencia_horas = 0;
				$asist->asistencia_retardo = 0;
				$asist->asistencia_minutos_retardo = 0;
			}

			if ($asist->save()) $guardados++;
		}

		echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Se guardaron ' . $guardados . ' registros'));
		Yii::app()->end();
	}

	/**
	 * Importar asistencia desde datos pegados (CSV)
	 * Formato: NumEmpleado,Fecha,Entrada,Salida
	 */
	public function actionImportarasistencia()
	{
		$id_periodo = isset($_POST['id_periodo']) ? $_POST['id_periodo'] : '';
		$datos = isset($_POST['datos']) ? trim($_POST['datos']) : '';

		if (empty($id_periodo) || empty($datos)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Datos incompletos'));
			Yii::app()->end();
		}

		$periodo = RhNominaPeriodos::model()->findByPk($id_periodo);
		if (empty($periodo)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Periodo no encontrado'));
			Yii::app()->end();
		}

		$lineas = explode("\n", $datos);
		$importados = 0;
		$errores = 0;

		foreach ($lineas as $linea) {
			$linea = trim($linea);
			if (empty($linea)) continue;

			// Separar por coma, tab o punto y coma
			$partes = preg_split('/[,;\t]/', $linea);
			if (count($partes) < 4) {
				$errores++;
				continue;
			}

			$numEmpleado = trim($partes[0]);
			$fecha = trim($partes[1]);
			$entrada = trim($partes[2]);
			$salida = trim($partes[3]);

			// Buscar empleado por num_empleado o num_reloj
			$empleado = Empleados::model()->find(
				'empleado_num_empleado = :num OR empleado_num_reloj = :num',
				array(':num' => $numEmpleado)
			);
			if (empty($empleado)) {
				$errores++;
				continue;
			}

			// Saltar empleados que no requieren checador
			if ($empleado->empleado_requiere_checador == 'NO') continue;

			// Buscar registro de asistencia existente
			$asist = RhAsistencia::model()->find(
				'id_periodo = :per AND id_empleado = :emp AND asistencia_fecha = :f',
				array(':per' => $id_periodo, ':emp' => $empleado->id_empleado, ':f' => $fecha)
			);

			if (empty($asist)) {
				$errores++;
				continue;
			}

			$asist->asistencia_tipo = 'NORMAL';
			$asist->asistencia_entrada = $entrada;
			$asist->asistencia_salida = $salida;
			$asist->calcular();

			if ($asist->save()) {
				$importados++;
			} else {
				$errores++;
			}
		}

		echo CJSON::encode(array(
			'requestresult' => 'ok',
			'message' => 'Importados: ' . $importados . ' registros. Errores: ' . $errores,
		));
		Yii::app()->end();
	}

	/**
	 * Importar asistencia desde Excel del reloj checador (.xls)
	 * Lee la hoja "Reporte de Asistencia" y extrae entrada/salida por empleado
	 */
	public function actionImportarchecador()
	{
		$id_periodo = isset($_POST['id_periodo']) ? $_POST['id_periodo'] : '';

		if (empty($id_periodo)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Periodo no especificado'));
			Yii::app()->end();
		}

		$periodo = RhNominaPeriodos::model()->findByPk($id_periodo);
		if (empty($periodo)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Periodo no encontrado'));
			Yii::app()->end();
		}

		if (!isset($_FILES['archivo_checador']) || $_FILES['archivo_checador']['error'] != UPLOAD_ERR_OK) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'No se recibio el archivo'));
			Yii::app()->end();
		}

		$tmpFile = $_FILES['archivo_checador']['tmp_name'];

		require_once(Yii::getPathOfAlias('application.vendor') . '/SimpleXLS.php');
		$xls = \Shuchkin\SimpleXLS::parse($tmpFile);

		if (!$xls) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'No se pudo leer el archivo Excel: ' . \Shuchkin\SimpleXLS::parseError()));
			Yii::app()->end();
		}

		// Buscar hoja "Reporte de Asistencia"
		$sheetIndex = -1;
		$sheetNames = $xls->sheetNames();
		foreach ($sheetNames as $idx => $name) {
			if (stripos($name, 'Reporte de Asistencia') !== false) {
				$sheetIndex = $idx;
				break;
			}
		}

		if ($sheetIndex < 0) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'No se encontro la hoja "Reporte de Asistencia" en el Excel'));
			Yii::app()->end();
		}

		$rows = $xls->rows($sheetIndex);

		// Fila 2 (index 2): periodo "2026-01-01 ~ 2026-01-31"
		$periodoTexto = isset($rows[2][6]) ? trim($rows[2][6]) : '';
		$partesPeriodo = explode('~', $periodoTexto);
		if (count($partesPeriodo) < 2) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'No se pudo leer el periodo del Excel. Valor: ' . $periodoTexto));
			Yii::app()->end();
		}
		$fechaInicioExcel = trim($partesPeriodo[0]);

		// Rango de fechas del periodo de nomina
		$fechaInicioPeriodo = $periodo->periodo_fecha_inicio;
		$fechaFinPeriodo = $periodo->periodo_fecha_fin;

		$importados = 0;
		$errores = 0;
		$sinMatch = array();

		// Recorrer filas en pares: fila par = encabezado empleado, fila impar = datos
		for ($i = 4; $i < count($rows) - 1; $i += 2) {
			$filaHead = $rows[$i];
			$filaData = $rows[$i + 1];

			// Verificar que es fila de empleado
			if (!isset($filaHead[0]) || trim($filaHead[0]) !== 'ID:') continue;

			$numReloj = isset($filaHead[4]) ? trim($filaHead[4]) : '';
			$nombreExcel = isset($filaHead[10]) ? trim($filaHead[10]) : '';

			if (empty($numReloj)) continue;

			// Buscar empleado por num_reloj
			$empleado = Empleados::model()->find(
				'empleado_num_reloj = :nr',
				array(':nr' => $numReloj)
			);
			if (empty($empleado)) {
				$sinMatch[] = $numReloj . ' - ' . $nombreExcel;
				continue;
			}

			// Saltar empleados que no requieren checador (ya tienen asistencia completa)
			if ($empleado->empleado_requiere_checador == 'NO') continue;

			// Recorrer dias 1-31 (columnas 0-30 en fila de datos)
			for ($d = 0; $d < 31; $d++) {
				$celda = isset($filaData[$d]) ? trim(str_replace(array("\n", "\r"), '', $filaData[$d])) : '';
				if (empty($celda)) continue;

				// Calcular fecha real: fechaInicioExcel + d dias
				$fechaReal = date('Y-m-d', strtotime($fechaInicioExcel . ' + ' . $d . ' days'));

				// Solo importar si cae dentro del periodo de nomina
				if ($fechaReal < $fechaInicioPeriodo || $fechaReal > $fechaFinPeriodo) continue;

				// Extraer entrada y salida de la celda
				// Formato: horas pegadas en bloques de 5 chars (HH:MM)
				// "08:0917:00" = entrada 08:09, salida 17:00
				// "07:4407:4918:00" = 3 punches, primera=entrada, ultima=salida
				$tiempos = array();
				$len = strlen($celda);
				for ($t = 0; $t + 4 < $len; $t += 5) {
					$bloque = substr($celda, $t, 5);
					if (preg_match('/^\d{2}:\d{2}$/', $bloque)) {
						$tiempos[] = $bloque;
					} else {
						break;
					}
				}

				if (empty($tiempos)) continue;

				$entrada = $tiempos[0];
				$salida = count($tiempos) > 1 ? $tiempos[count($tiempos) - 1] : '';

				// Si solo hay 1 punch: si >= 12:00 es salida, si < 12:00 es entrada
				if (count($tiempos) == 1) {
					if (strtotime($tiempos[0]) >= strtotime('12:00')) {
						$entrada = null;
						$salida = $tiempos[0];
					} else {
						$entrada = $tiempos[0];
						$salida = null;
					}
				}

				// Buscar registro de asistencia existente para este empleado+fecha
				$asist = RhAsistencia::model()->find(
					'id_periodo = :per AND id_empleado = :emp AND asistencia_fecha = :f',
					array(':per' => $id_periodo, ':emp' => $empleado->id_empleado, ':f' => $fechaReal)
				);

				if (empty($asist)) continue;

				// Solo actualizar si no es DESCANSO o FESTIVO
				if (in_array($asist->asistencia_tipo, array('DESCANSO', 'FESTIVO', 'VACACION'))) continue;

				$asist->asistencia_tipo = 'NORMAL';
				$asist->asistencia_entrada = $entrada;
				$asist->asistencia_salida = $salida;
				$asist->calcular();

				if ($asist->save()) {
					$importados++;
				} else {
					$errores++;
				}
			}
		}

		// Buscar empleados con retardos pero sin faltas en este periodo (candidatos a bono condicional)
		$empleadosConRetardo = array();
		$sqlRetardos = "SELECT e.id_empleado, e.empleado_nombre, e.empleado_rebaja_bono,
			SUM(CASE WHEN a.asistencia_retardo = 1 THEN 1 ELSE 0 END) as total_retardos,
			SUM(CASE WHEN a.asistencia_retardo = 1 THEN a.asistencia_minutos_retardo ELSE 0 END) as total_min_retardo,
			SUM(CASE WHEN a.asistencia_tipo = 'FALTA' THEN 1 ELSE 0 END) as total_faltas
			FROM rh_asistencia a
			INNER JOIN rh_empleados e ON e.id_empleado = a.id_empleado
			WHERE a.id_periodo = :per
			GROUP BY e.id_empleado
			HAVING total_retardos > 0 AND total_faltas = 0";
		$db = RhAsistencia::model()->getDbConnection();
		$retRows = $db->createCommand($sqlRetardos)->bindValue(':per', $id_periodo)->queryAll();
		foreach ($retRows as $r) {
			// No preguntar si empleado_rebaja_bono = 'NO' (siempre conserva bono)
			if ($r['empleado_rebaja_bono'] == 'NO') continue;
			$empleadosConRetardo[] = array(
				'id_empleado' => $r['id_empleado'],
				'nombre' => $r['empleado_nombre'],
				'retardos' => (int)$r['total_retardos'],
				'minutos' => (int)$r['total_min_retardo'],
			);
		}

		$msg = 'Importados: ' . $importados . ' registros.';
		if ($errores > 0) $msg .= ' Errores: ' . $errores . '.';
		if (!empty($sinMatch)) $msg .= ' Empleados no encontrados: ' . implode(', ', $sinMatch);

		echo CJSON::encode(array(
			'requestresult' => 'ok',
			'message' => $msg,
			'empleados_retardo' => $empleadosConRetardo,
		));
		Yii::app()->end();
	}

	/**
	 * Guardar decisiones de bono condicional para empleados con retardos
	 */
	public function actionGuardarbonocondicional()
	{
		$id_periodo = isset($_POST['id_periodo']) ? $_POST['id_periodo'] : '';
		$decisiones = isset($_POST['decisiones']) ? $_POST['decisiones'] : array();

		if (empty($id_periodo)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Periodo no especificado'));
			Yii::app()->end();
		}

		$guardados = 0;
		foreach ($decisiones as $dec) {
			$idEmp = isset($dec['id_empleado']) ? $dec['id_empleado'] : '';
			$bono = isset($dec['bono']) ? $dec['bono'] : 'SI';

			if (empty($idEmp)) continue;

			// Guardar la decision en rh_nomina_detalles si ya existe
			$det = RhNominaDetalles::model()->find(
				'id_periodo = :per AND id_empleado = :emp',
				array(':per' => $id_periodo, ':emp' => $idEmp)
			);

			if (!empty($det)) {
				$det->bono_condicional_aprobado = ($bono == 'SI') ? 'SI' : 'NO';
				$det->calcularNomina();
				$det->save();
				$guardados++;
			}
		}

		echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Bono condicional actualizado para ' . $guardados . ' empleados.'));
		Yii::app()->end();
	}

	// =========================================================================
	// NOMINA
	// =========================================================================

	public function actionNomina($id)
	{
		$this->layout = "main";
		$periodo = RhNominaPeriodos::model()->findByPk($id);
		if (empty($periodo)) {
			throw new CHttpException(404, 'Periodo no encontrado');
		}

		$detalles = RhNominaDetalles::model()->findAll(array(
			'condition' => 'id_periodo = :per',
			'params' => array(':per' => $id),
			'with' => array('rl_empleado'),
			'order' => 'rl_empleado.empleado_nombre ASC',
		));

		// Detectar empleados con aniversario en este periodo (replica VBA Workbook_Open)
		$primaVacacional = array();
		foreach ($detalles as $det) {
			$emp = $det->rl_empleado;
			if (empty($emp) || empty($emp->empleado_fecha_ingreso)) continue;
			$mesDiaIngreso = substr($emp->empleado_fecha_ingreso, 5); // MM-DD
			$anioInicio = (int)date('Y', strtotime($periodo->periodo_fecha_inicio));
			$anioFin = (int)date('Y', strtotime($periodo->periodo_fecha_fin));
			for ($anio = $anioInicio; $anio <= $anioFin; $anio++) {
				$fechaAniversario = $anio . '-' . $mesDiaIngreso;
				if (!strtotime($fechaAniversario)) continue;
				if ($fechaAniversario >= $periodo->periodo_fecha_inicio && $fechaAniversario <= $periodo->periodo_fecha_fin) {
					$ingDt = new DateTime($emp->empleado_fecha_ingreso);
					$anivDt = new DateTime($fechaAniversario);
					$anios = $anivDt->diff($ingDt)->y;
					if ($anios >= 1) {
						$tablaVac = RhVacacionesTabla::model()->find('anios_antiguedad = :a', array(':a' => $anios));
						$diasVac = !empty($tablaVac) ? (int)$tablaVac->dias_vacaciones : 32;
						$primaVacacional[] = array(
							'nombre' => $emp->empleado_nombre,
							'anios' => $anios,
							'dias_vacaciones' => $diasVac,
							'prima' => (float)$det->prima_vacacional,
						);
					}
					break;
				}
			}
		}

		$this->render('nomina', array(
			'periodo' => $periodo,
			'detalles' => $detalles,
			'primaVacacional' => $primaVacacional,
		));
	}

	/**
	 * Calcular nomina completa para un periodo
	 */
	public function actionCalcular()
	{
		$id_periodo = isset($_POST['id_periodo']) ? $_POST['id_periodo'] : '';
		$periodo = RhNominaPeriodos::model()->findByPk($id_periodo);

		if (empty($periodo)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Periodo no encontrado'));
			Yii::app()->end();
		}

		$detalles = RhNominaDetalles::model()->findAll(
			'id_periodo = :per',
			array(':per' => $id_periodo)
		);

		$calculados = 0;
		foreach ($detalles as $det) {
			$det->calcularNomina();
			if ($det->save()) $calculados++;
		}

		echo CJSON::encode(array(
			'requestresult' => 'ok',
			'message' => 'Nomina calculada para ' . $calculados . ' empleados',
		));
		Yii::app()->end();
	}

	/**
	 * Aprobar/Denegar bono condicional
	 */
	public function actionAprobarbono()
	{
		$id_detalle = isset($_POST['id_detalle']) ? $_POST['id_detalle'] : '';
		$aprobado = isset($_POST['aprobado']) ? (int)$_POST['aprobado'] : 0;

		$det = RhNominaDetalles::model()->findByPk($id_detalle);
		if (empty($det)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Registro no encontrado'));
			Yii::app()->end();
		}

		$det->bono_condicional_aprobado = $aprobado ? 'SI' : 'NO';

		// Recalcular la nomina completa con la nueva decision de bono
		$det->calcularNomina();

		if ($det->save()) {
			echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Bono ' . ($aprobado ? 'aprobado' : 'denegado')));
		} else {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al guardar'));
		}
		Yii::app()->end();
	}

	/**
	 * Guardar edicion manual de un detalle de nomina
	 */
	public function actionGuardarnomina()
	{
		$id_detalle = isset($_POST['id_detalle']) ? $_POST['id_detalle'] : '';
		$det = RhNominaDetalles::model()->findByPk($id_detalle);

		if (empty($det)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Registro no encontrado'));
			Yii::app()->end();
		}

		// Campos editables
		$campos = array('isr', 'otra_deduccion', 'otra_deduccion_desc', 'vales', 'nomina_observaciones',
			'premio_asistencia', 'premio_puntualidad', 'premio_productividad', 'bono_condicional');

		foreach ($campos as $c) {
			if (isset($_POST[$c])) {
				$det->$c = $_POST[$c];
			}
		}

		// Recalcular totales con valores editados manualmente
		$det->total_percepciones = round($det->sueldo_nominal + $det->premio_asistencia + $det->premio_puntualidad + $det->premio_productividad + $det->bono_condicional + $det->prima_vacacional, 2);
		$det->total_deducciones = round($det->isr + $det->imss + $det->infonavit + $det->otra_deduccion, 2);
		$det->efectivo = round($det->total_percepciones - $det->total_deducciones, 2);
		$det->total_neto = round($det->efectivo - $det->vales, 2);

		if ($det->save()) {
			echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Guardado correctamente'));
		} else {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al guardar'));
		}
		Yii::app()->end();
	}

	// =========================================================================
	// PDF RESUMEN NOMINA (landscape)
	// =========================================================================

	/**
	 * Generar PDF resumen de nomina con todos los empleados (landscape)
	 */
	public function actionNominapdf($id)
	{
		$periodo = RhNominaPeriodos::model()->findByPk($id);
		if (empty($periodo)) {
			throw new CHttpException(404, 'Periodo no encontrado');
		}

		$detalles = RhNominaDetalles::model()->findAll(array(
			'condition' => 'id_periodo = :per',
			'params' => array(':per' => $id),
			'with' => array('rl_empleado'),
			'order' => 'rl_empleado.empleado_nombre ASC',
		));

		$html = $this->generarHTMLNominaPDF($periodo, $detalles);

		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('letter', 'landscape');
		$dompdf->render();
		$dompdf->stream('Nomina_' . $periodo->periodo_fecha_inicio . '_' . $periodo->periodo_fecha_fin . '.pdf', array('Attachment' => false));
		Yii::app()->end();
	}

	/**
	 * Genera el HTML para el PDF resumen de nomina (replica exacta del Excel)
	 */
	private function generarHTMLNominaPDF($periodo, $detalles)
	{
		$meses = array(
			1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
			5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
			9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
		);

		$diaIni = (int)date('d', strtotime($periodo->periodo_fecha_inicio));
		$diaFin = (int)date('d', strtotime($periodo->periodo_fecha_fin));
		$mesFin = (int)date('m', strtotime($periodo->periodo_fecha_fin));
		$anioFin = date('Y', strtotime($periodo->periodo_fecha_fin));
		$titulo = 'INDUSTRIAL OVISA NOMINA CORRESPONDIENTE DEL ' . $diaIni . '  AL ' . $diaFin . ' DE ' . $meses[$mesFin] . ' DE ' . $anioFin;

		// Totales
		$totDias = 0;
		$totSDI = 0;
		$totSueldo = 0;
		$totCompens = 0;
		$totPunt = 0;
		$totAsist = 0;
		$totPVacac = 0;
		$totTotales = 0;
		$totIMSS = 0;
		$totInfonavit = 0;
		$totISR = 0;
		$totPagar = 0;

		// Filas
		$filas = '';
		$num = 0;
		foreach ($detalles as $det) {
			$emp = $det->rl_empleado;
			if (empty($emp)) continue;
			$num++;

			// DIAS = total_horas / 8 (incluye proporcion descanso, 56hrs = 7 dias)
			$dias = (float)$det->total_horas > 0 ? round((float)$det->total_horas / 8, 0) : 0;
			$sdi = (float)$emp->empleado_integrado;
			$sueldo = (float)$det->sueldo_nominal;
			$compens = (float)$det->premio_productividad + (float)$det->bono_condicional;
			$punt = (float)$det->premio_puntualidad;
			$asist = (float)$det->premio_asistencia;
			$pvacac = (float)$det->prima_vacacional;
			$totales = (float)$det->total_percepciones;
			$imss = (float)$det->imss;
			$infonavit = (float)$det->infonavit;
			$isr = (float)$det->isr;
			$pagar = (float)$det->total_neto;

			$totDias += $dias;
			$totSDI += $sdi;
			$totSueldo += $sueldo;
			$totCompens += $compens;
			$totPunt += $punt;
			$totAsist += $asist;
			$totPVacac += $pvacac;
			$totTotales += $totales;
			$totIMSS += $imss;
			$totInfonavit += $infonavit;
			$totISR += $isr;
			$totPagar += $pagar;

			$filas .= '<tr>
				<td class="l">' . CHtml::encode($emp->empleado_nombre) . '</td>
				<td class="c">' . $dias . '</td>
				<td class="r">' . number_format($sdi, 2) . '</td>
				<td class="r">' . number_format($sueldo, 2) . '</td>
				<td class="r">' . ($compens > 0 ? number_format($compens, 2) : '-') . '</td>
				<td class="r">' . number_format($punt, 2) . '</td>
				<td class="r">' . number_format($asist, 2) . '</td>
				<td class="r">' . ($pvacac > 0 ? number_format($pvacac, 2) : '') . '</td>
				<td class="r b">' . number_format($totales, 2) . '</td>
				<td class="r">' . number_format($imss, 2) . '</td>
				<td class="r">' . ($infonavit > 0 ? number_format($infonavit, 2) : '') . '</td>
				<td class="r">' . ($isr > 0 ? number_format($isr, 2) : '') . '</td>
				<td class="r b">' . number_format($pagar, 2) . '</td>
			</tr>';
		}

		// Fila de totales
		$filas .= '<tr class="totales">
			<td class="r">TOTALES</td>
			<td class="c">' . $totDias . '</td>
			<td class="r">' . number_format($totSDI, 2) . '</td>
			<td class="r">' . number_format($totSueldo, 2) . '</td>
			<td class="r">' . number_format($totCompens, 2) . '</td>
			<td class="r">' . number_format($totPunt, 2) . '</td>
			<td class="r">' . number_format($totAsist, 2) . '</td>
			<td class="r">' . ($totPVacac > 0 ? number_format($totPVacac, 2) : '-') . '</td>
			<td class="r">' . number_format($totTotales, 2) . '</td>
			<td class="r">' . number_format($totIMSS, 2) . '</td>
			<td class="r">' . number_format($totInfonavit, 2) . '</td>
			<td class="r">' . number_format($totISR, 2) . '</td>
			<td class="r">' . number_format($totPagar, 2) . '</td>
		</tr>';

		$html = '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
	@page { margin: 8mm 10mm; }
	body { font-family: Arial, sans-serif; font-size: 7pt; margin: 0; padding: 0; }
	table { width: 100%; border-collapse: collapse; }
	th { background: #d9d9d9; color: #000; padding: 2px 3px; font-size: 6.5pt; text-align: center; border: 1px solid #000; font-weight: bold; }
	td { border: 1px solid #aaa; padding: 1px 3px; font-size: 7pt; }
	td.l { text-align: left; }
	td.r { text-align: right; }
	td.c { text-align: center; }
	td.b { font-weight: bold; }
	tr.totales td { font-weight: bold; border-top: 2px solid #000; padding: 2px 3px; }
</style>
</head>
<body>
	<div style="text-align:center; font-weight:bold; font-size:9pt; margin-bottom:5px;">' . $titulo . '</div>

	<table>
		<thead>
			<tr>
				<th style="text-align:left; width:19%;">NOMBRE DEL TRABAJADOR</th>
				<th style="width:3%;">DIAS</th>
				<th style="width:5%;">SDI</th>
				<th style="width:7%;">S/NOMINAL</th>
				<th style="width:6%;">COMPENS</th>
				<th style="width:5%;">PUNT</th>
				<th style="width:5%;">ASIST</th>
				<th style="width:5%;">P VACAC</th>
				<th style="width:7%;">TOTALES</th>
				<th style="width:5%;">IMSS</th>
				<th style="width:7%;">INFONAVIT</th>
				<th style="width:5%;">ISR</th>
				<th style="width:8%;">TOTAL A PAGAR</th>
			</tr>
		</thead>
		<tbody>
			' . $filas . '
		</tbody>
	</table>

	<div style="margin-top:5px; font-size:6pt; color:#666; text-align:center;">
		Generado el ' . date('d/m/Y H:i') . ' | ' . $num . ' empleados
	</div>
</body>
</html>';

		return $html;
	}

	// =========================================================================
	// RECIBO PDF
	// =========================================================================

	/**
	 * Obtener dias de vacaciones que corresponden a la prima de un detalle de nomina
	 */
	private function getDiasVacacionesPrima($det)
	{
		$emp = $det->rl_empleado;
		$periodo = $det->rl_periodo;
		if (empty($emp) || empty($periodo) || empty($emp->empleado_fecha_ingreso)) return 0;
		$mesDiaIngreso = substr($emp->empleado_fecha_ingreso, 5);
		$anioInicio = (int)date('Y', strtotime($periodo->periodo_fecha_inicio));
		$anioFin = (int)date('Y', strtotime($periodo->periodo_fecha_fin));
		for ($anio = $anioInicio; $anio <= $anioFin; $anio++) {
			$fechaAniversario = $anio . '-' . $mesDiaIngreso;
			if (!strtotime($fechaAniversario)) continue;
			if ($fechaAniversario >= $periodo->periodo_fecha_inicio && $fechaAniversario <= $periodo->periodo_fecha_fin) {
				$ingDt = new DateTime($emp->empleado_fecha_ingreso);
				$anivDt = new DateTime($fechaAniversario);
				$aniosAnt = $anivDt->diff($ingDt)->y;
				if ($aniosAnt >= 1) {
					return Empleados::getDiasVacPorAnio($aniosAnt, $emp->empleado_fecha_ingreso);
				}
			}
		}
		return 0;
	}

	/**
	 * Contar dias de vacacion tomados por el empleado en un periodo
	 */
	private function getDiasVacacionEnPeriodo($det)
	{
		$count = RhAsistencia::model()->count(
			'id_periodo = :pid AND id_empleado = :eid AND asistencia_tipo = :tipo',
			array(':pid' => $det->id_periodo, ':eid' => $det->id_empleado, ':tipo' => 'VACACION')
		);
		return (int)$count;
	}

	private function fechaReciboPDF($fecha)
	{
		$meses = array(
			1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
			5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
			9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
		);
		$d = date('d', strtotime($fecha));
		$m = (int)date('m', strtotime($fecha));
		$y = date('Y', strtotime($fecha));
		return $d . ' de ' . $meses[$m] . ' de ' . $y;
	}

	public function actionRecibo($id)
	{
		$det = RhNominaDetalles::model()->findByPk($id);
		if (empty($det)) {
			throw new CHttpException(404, 'Registro no encontrado');
		}

		$empleado = $det->rl_empleado;
		$periodo = $det->rl_periodo;

		$fechaInicio = $this->fechaReciboPDF($periodo->periodo_fecha_inicio);
		$fechaFin = $this->fechaReciboPDF($periodo->periodo_fecha_fin);

		$html = $this->generarHTMLRecibo($det, $empleado, $periodo);

		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('letter', 'portrait');
		$dompdf->render();
		$dompdf->stream('Recibo_' . $empleado->empleado_num_empleado . '_' . $periodo->periodo_fecha_inicio . '.pdf', array('Attachment' => false));
		Yii::app()->end();
	}

	/**
	 * Generar todos los recibos del periodo en un solo PDF
	 */
	public function actionRecibostodos($id)
	{
		$periodo = RhNominaPeriodos::model()->findByPk($id);
		if (empty($periodo)) {
			throw new CHttpException(404, 'Periodo no encontrado');
		}

		$detalles = RhNominaDetalles::model()->findAll(array(
			'condition' => 'id_periodo = :per',
			'params' => array(':per' => $id),
			'with' => array('rl_empleado'),
			'order' => 'rl_empleado.empleado_nombre ASC',
		));

		$htmlCompleto = '';
		$primero = true;
		foreach ($detalles as $det) {
			$empleado = $det->rl_empleado;
			if (empty($empleado)) continue;
			if (!$primero) {
				$htmlCompleto .= '<div style="page-break-before: always;"></div>';
			}
			$htmlCompleto .= $this->generarHTMLRecibo($det, $empleado, $periodo);
			$primero = false;
		}

		$dompdf = new Dompdf();
		$dompdf->loadHtml($htmlCompleto);
		$dompdf->setPaper('letter', 'portrait');
		$dompdf->render();
		$dompdf->stream('Recibos_Nomina_' . $periodo->periodo_fecha_inicio . '.pdf', array('Attachment' => false));
		Yii::app()->end();
	}

	private function generarCopiaRecibo($det, $empleado, $periodo, $tipoCopia, $esAguinaldo = false)
	{
		$fi = $this->fechaReciboPDF($periodo->periodo_fecha_inicio);
		$ff = $this->fechaReciboPDF($periodo->periodo_fecha_fin);
		$diasEfectivos = (float)$det->total_horas > 0 ? round((float)$det->total_horas / 8, 0) : 0;
		$compensacion = (float)$det->premio_productividad + (float)$det->bono_condicional;
		$primaVac = (float)$det->prima_vacacional;
		$vacDiasEnPeriodo = $this->getDiasVacacionEnPeriodo($det);

		$ts = 'width:100%; border-collapse:collapse; font-family:Arial,sans-serif; font-size:11px;';
		$dw = 'border: 1.5px solid #000; border-radius: 10px; overflow: hidden;';
		$vd = 'border-right:1px solid #000;'; // vertical divider percepciones|deducciones

		// === TABLA 1: Encabezado empresa ===
		$html = '
		<div style="'.$dw.'">
		<table style="'.$ts.'" cellpadding="0" cellspacing="0">
			<tr>
				<td style="text-align:center; font-weight:bold; font-size:16px; padding:6px 0;">INDUSTRIAL OVISA S.A. DE C.V.</td>
			</tr>
			<tr>
				<td style="text-align:center; font-size:13px; padding:3px 0;">R.F.C  IOV1709084G9</td>
			</tr>
			<tr>
				<td style="text-align:center; font-size:10px; padding:3px 0;">CALLE MIGUEL HIDALGO Y COSTILLA No. 905 COL. LOS ALTOS GENERAL ESCOBEDO, N.L. C.P. 66052</td>
			</tr>
			<tr>
				<td style="border-top:1px solid #000; text-align:center; font-size:11px; padding:4px 0; font-weight:bold;">Registro Patronal D43-23-31810-2</td>
			</tr>
		</table>
		</div>';

		// === TABLA 2: Datos del empleado ===
		$ir = 'border-right:1px solid #000;';
		$ib = 'border-bottom:1px solid #000;';
		$html .= '
		<div style="height:6px;"></div>
		<div style="'.$dw.'">
		<table style="'.$ts.'" cellpadding="0" cellspacing="0">
			<tr>
				<td style="'.$ir.' '.$ib.' padding:5px 6px; font-weight:bold; width:11%;">NOMBRE:</td>
				<td colspan="3" style="'.$ir.' '.$ib.' padding:5px 6px; width:35%;">'.CHtml::encode($empleado->empleado_nombre).'</td>
				<td style="'.$ir.' '.$ib.' padding:5px 6px; font-weight:bold; text-align:center; width:10%;">R.F.C.</td>
				<td colspan="2" style="'.$ir.' '.$ib.' padding:5px 6px; width:20%;">'.CHtml::encode($empleado->empleado_rfc ?? '').'</td>
				<td style="'.$ir.' '.$ib.' padding:5px 6px; font-weight:bold; text-align:center; width:8%;">N.S.S.</td>
				<td style="'.$ib.' padding:5px 6px; width:16%;">'.CHtml::encode($empleado->empleado_seguro_social ?? '').'</td>
			</tr>
			<tr>
				<td colspan="2" style="'.$ir.' padding:5px 6px; font-weight:bold;">N&deg; EMPLEADO:</td>
				<td style="'.$ir.' padding:5px 6px;">'.CHtml::encode($empleado->empleado_num_empleado ?? '').'</td>
				<td style="'.$ir.' padding:5px 6px; font-weight:bold;">Sueldo Nominal</td>
				<td style="'.$ir.' padding:5px 6px;">$ '.number_format((float)$empleado->empleado_sueldo_diario, 2).'</td>
				<td colspan="2" style="'.$ir.' padding:5px 6px; font-weight:bold; text-align:center;">Integrado</td>
				<td colspan="2" style="padding:5px 6px;">$ '.number_format((float)$empleado->empleado_integrado, 2).'</td>
			</tr>
		</table>
		</div>';

		// === TABLA 3: Percepciones / Deducciones / Totales ===
		$html .= '
		<div style="height:6px;"></div>
		<div style="'.$dw.'">
		<table style="'.$ts.'" cellpadding="0" cellspacing="0">
			<!-- Encabezado PERCEPCIONES / DEDUCCIONES -->
			<tr style="font-weight:bold;">
				<td colspan="3" style="border-bottom:1px solid #000; padding:5px 6px;">PERCEPCIONES</td>
				<td style="border-bottom:1px solid #000; '.$vd.' padding:5px 6px; text-align:right;">Importe $</td>
				<td colspan="3" style="border-bottom:1px solid #000; padding:5px 6px;">DEDUCCIONES</td>
				<td colspan="2" style="border-bottom:1px solid #000; padding:5px 6px; text-align:right;">Importe $</td>
			</tr>
			<!-- Sueldo Nominal o AGUINALDO / ISR -->
			<tr>
				<td colspan="3" style="padding:4px 6px; font-weight:bold;">'.($esAguinaldo ? 'AGUINALDO' : 'Sueldo Nominal').'</td>
				<td style="'.$vd.' padding:4px 6px; text-align:right;">$ '.number_format((float)$det->sueldo_nominal, 2).'</td>
				<td colspan="3" style="padding:4px 6px; font-weight:bold;">'.(!$esAguinaldo ? 'ISR' : '').'</td>
				<td colspan="2" style="padding:4px 6px; text-align:right;">'.(!$esAguinaldo ? '$ '.number_format((float)$det->isr, 2) : '').'</td>
			</tr>
			<!-- Premio Asistencia o GRATIFICACION / I.M.S.S. -->
			<tr>
				<td colspan="3" style="padding:4px 6px; font-weight:bold;">'.($esAguinaldo ? 'GRATIFICACION' : 'Premio Asistencia').'</td>
				<td style="'.$vd.' padding:4px 6px; text-align:right;">'.((float)$det->premio_asistencia > 0 ? '$ '.number_format((float)$det->premio_asistencia, 2) : '').'</td>
				<td colspan="3" style="padding:4px 6px; font-weight:bold;">'.(!$esAguinaldo ? 'I.M.S.S.' : '').'</td>
				<td colspan="2" style="padding:4px 6px; text-align:right;">'.(!$esAguinaldo ? '$ '.number_format((float)$det->imss, 2) : '').'</td>
			</tr>';

		// Filas extras: solo en nomina normal, NO en aguinaldo
		if (!$esAguinaldo) {
			$html .= '
			<tr>
				<td colspan="3" style="padding:4px 6px; font-weight:bold;">Premio Puntualidad</td>
				<td style="'.$vd.' padding:4px 6px; text-align:right;">$ '.number_format((float)$det->premio_puntualidad, 2).'</td>
				<td colspan="3" style="padding:4px 6px;">'.((float)$det->infonavit > 0 ? '<strong>INFONAVIT</strong>' : '').'</td>
				<td colspan="2" style="padding:4px 6px; text-align:right;">'.((float)$det->infonavit > 0 ? '$ '.number_format((float)$det->infonavit, 2) : '').'</td>
			</tr>
			<tr>
				<td colspan="3" style="padding:4px 6px; font-size:9px;">'.($vacDiasEnPeriodo > 0 ? '(' . $vacDiasEnPeriodo . ' DIAS DE VACACIONES)' : ($primaVac > 0 ? '(' . $this->getDiasVacacionesPrima($det) . ' DIAS DE VACACIONES)' : '')).'</td>
				<td style="'.$vd.' padding:4px 6px;"></td>
				<td colspan="5" style=""></td>
			</tr>
			<tr>
				<td colspan="3" style="padding:4px 6px; font-weight:bold;">'.($primaVac > 0 ? 'PRIMA VACACIONAL' : '').'</td>
				<td style="'.$vd.' padding:4px 6px; text-align:right;">'.($primaVac > 0 ? '$ '.number_format($primaVac, 2) : '').'</td>
				<td colspan="5" style=""></td>
			</tr>
			<tr>
				<td colspan="3" style="padding:4px 6px; font-weight:bold; font-size:12px;">COMPENSACION</td>
				<td style="'.$vd.' padding:4px 6px; text-align:right; font-weight:bold;">$ '.number_format($compensacion, 2).'</td>
				<td colspan="5" style=""></td>
			</tr>';
		}

		$html .= '
			<!-- Espacio -->
			<tr><td colspan="4" style="'.$vd.' height:8px;"></td><td colspan="5" style=""></td></tr>
			<!-- Total Percepciones / Total de deducciones -->
			<tr style="font-weight:bold;">
				<td colspan="2" style="padding:5px 6px;"></td>
				<td style="padding:5px 6px; text-align:right;">Total Percepciones</td>
				<td style="'.$vd.' padding:5px 6px; text-align:right; border-bottom:1px solid #000;">$ '.number_format((float)$det->total_percepciones, 2).'</td>
				<td colspan="3" style="padding:5px 6px; text-align:right;">Total de deducciones</td>
				<td colspan="2" style="padding:5px 6px; text-align:right; border-bottom:1px solid #000;">$ '.number_format((float)$det->total_deducciones, 2).'</td>
			</tr>
			<!-- vales -->
			<tr>
				<td colspan="4" style="'.$vd.'"></td>
				<td colspan="2" style="padding:3px 6px;"></td>
				<td style="padding:3px 6px; border:1px solid #000;">vales</td>
				<td colspan="2" style="padding:3px 6px; text-align:right; border:1px solid #000;">$ '.number_format((float)$det->vales, 2).'</td>
			</tr>
			<!-- Efectivo -->
			<tr>
				<td colspan="4" style="'.$vd.'"></td>
				<td colspan="2" style="padding:3px 6px;"></td>
				<td style="padding:3px 6px; border:1px solid #000;">Efectivo</td>
				<td colspan="2" style="padding:3px 6px; text-align:right; border:1px solid #000;">$ '.number_format((float)$det->efectivo, 2).'</td>
			</tr>
			<!-- SUELDO POR X DIAS / Total Neto -->
			<tr>
				<td style="padding:4px 6px; font-weight:bold;">'.($esAguinaldo ? 'AGUINALDO POR' : 'SUELDO POR').'</td>
				<td style="padding:4px 6px; text-align:center; font-weight:bold;">'.$diasEfectivos.'</td>
				<td colspan="2" style="'.$vd.' padding:4px 6px; font-size:10px;">'.($esAguinaldo ? 'DIAS' : 'DIAS DE TRABAJO EN EL').'</td>
				<td colspan="2" style="padding:4px 6px;"></td>
				<td style="padding:4px 6px; font-weight:bold; border:1px solid #000;">Total Neto</td>
				<td colspan="2" style="padding:4px 6px; text-align:right; font-weight:bold; border:1px solid #000;">$ '.number_format((float)$det->total_neto, 2).'</td>
			</tr>
			<!-- PERIODO DEL / RECIBI DE CONFORMIDAD -->
			<tr>
				<td colspan="2" style="padding:4px 6px; font-size:9px; font-weight:bold;">PERIODO DEL</td>
				<td style="padding:4px 6px; font-size:9px;">'.CHtml::encode($fi).'</td>
				<td style="'.$vd.' padding:4px 6px; font-size:9px; font-weight:bold;">AL</td>
				<td colspan="5" style="padding:4px 6px; font-size:9px; font-weight:bold;">'.CHtml::encode($ff).'&nbsp;&nbsp;&nbsp;RECIBI DE CONFORMIDAD SIN ADEUDOS EN HORAS EXTRAS</td>
			</tr>
			<!-- Texto legal -->
			<tr>
				<td colspan="9" style="border-top:1px solid #000; padding:5px 6px; font-size:8px;">RECIBI DE CONFORMIDAD LA LIQUIDACI&Oacute;N EN ESTE RECIBO POR SUELDO Y PRESTACIONES POR LOS TRABAJOS QUE HE DESEMPE&Ntilde;ADO. DECLARO QUE ME CONSIDERO LEGAL Y SATISFACTORIAMENTE PAGADO HASTA LA FECHA EN VIRTUD DE HAB&Eacute;RSEME CUMPLIDO EN TODAS LAS PARTES CON LA LEY FEDERAL DEL TRABAJO.</td>
			</tr>
			<!-- etiqueta copia -->
			<tr>
				<td colspan="9" style="text-align:right; padding:4px 6px; font-size:9px; color:#c00; font-weight:bold;">'.$tipoCopia.'</td>
			</tr>
		</table>
		</div>';

		return $html;
	}

	/**
	 * Recibo de NOMINA con borde redondeado, tabla unica, caben 2 copias por hoja
	 */
	private function generarCopiaReciboNomina($det, $empleado, $periodo, $tipoCopia)
	{
		$fi = $this->fechaReciboPDF($periodo->periodo_fecha_inicio);
		$ff = $this->fechaReciboPDF($periodo->periodo_fecha_fin);
		$diasEfectivos = (float)$det->total_horas > 0 ? round((float)$det->total_horas / 8, 0) : 0;
		$compensacion = (float)$det->premio_productividad + (float)$det->bono_condicional;
		$primaVac = (float)$det->prima_vacacional;
		$vacDiasEnPeriodo = $this->getDiasVacacionEnPeriodo($det);

		$ts = 'width:100%; border-collapse:collapse; font-family:Arial,sans-serif; font-size:10px;';
		$dw = 'border: 1.5px solid #000; border-radius: 10px; overflow: hidden;';
		$vd = 'border-right:1px solid #000;'; // divisor vertical percepciones|deducciones
		$ir = 'border-right:1px solid #000;'; // borde interno vertical
		$ib = 'border-bottom:1px solid #000;'; // borde interno horizontal
		$bt = 'border-top:1px solid #000;';

		$html = '<div style="'.$dw.'">';
		$html .= '<table style="'.$ts.'" cellpadding="0" cellspacing="0">';
		// Empresa
		$html .= '<tr><td colspan="9" style="text-align:center; font-weight:bold; font-size:14px; padding:5px 0;">INDUSTRIAL OVISA S.A. DE C.V.</td></tr>';
		$html .= '<tr><td colspan="9" style="text-align:center; font-size:11px; padding:2px 0;">R.F.C  IOV1709084G9</td></tr>';
		$html .= '<tr><td colspan="9" style="text-align:center; font-size:9px; padding:2px 0;">CALLE MIGUEL HIDALGO Y COSTILLA No. 905 COL. LOS ALTOS GENERAL ESCOBEDO, N.L. C.P. 66052</td></tr>';
		$html .= '<tr><td colspan="9" style="'.$bt.' text-align:center; font-size:10px; padding:4px 0; font-weight:bold;">Registro Patronal D43-23-31810-2</td></tr>';
		// Datos empleado
		$html .= '<tr>
			<td style="'.$ir.' '.$ib.' '.$bt.' padding:4px 5px; font-weight:bold; width:11%;">NOMBRE:</td>
			<td colspan="3" style="'.$ir.' '.$ib.' '.$bt.' padding:4px 5px; width:35%;">'.CHtml::encode($empleado->empleado_nombre).'</td>
			<td style="'.$ir.' '.$ib.' '.$bt.' padding:4px 5px; font-weight:bold; text-align:center; width:10%;">R.F.C.</td>
			<td colspan="2" style="'.$ir.' '.$ib.' '.$bt.' padding:4px 5px; width:20%;">'.CHtml::encode($empleado->empleado_rfc ?? '').'</td>
			<td style="'.$ir.' '.$ib.' '.$bt.' padding:4px 5px; font-weight:bold; text-align:center; width:8%;">N.S.S.</td>
			<td style="'.$ib.' '.$bt.' padding:4px 5px; width:16%;">'.CHtml::encode($empleado->empleado_seguro_social ?? '').'</td>
		</tr>';
		$html .= '<tr>
			<td colspan="2" style="'.$ir.' '.$ib.' padding:4px 5px; font-weight:bold;">N&deg; EMPLEADO:</td>
			<td style="'.$ir.' '.$ib.' padding:4px 5px;">'.CHtml::encode($empleado->empleado_num_empleado ?? '').'</td>
			<td style="'.$ir.' '.$ib.' padding:4px 5px; font-weight:bold;">Sueldo Nominal</td>
			<td style="'.$ir.' '.$ib.' padding:4px 5px;">$ '.number_format((float)$empleado->empleado_sueldo_diario, 2).'</td>
			<td colspan="2" style="'.$ir.' '.$ib.' padding:4px 5px; font-weight:bold; text-align:center;">Integrado</td>
			<td colspan="2" style="'.$ib.' padding:4px 5px;">$ '.number_format((float)$empleado->empleado_integrado, 2).'</td>
		</tr>';
		// Encabezado Percepciones / Deducciones
		$html .= '<tr style="font-weight:bold;">
			<td colspan="3" style="'.$ib.' padding:4px 5px;">PERCEPCIONES</td>
			<td style="'.$ib.' '.$vd.' padding:4px 5px; text-align:right;">Importe $</td>
			<td colspan="3" style="'.$ib.' padding:4px 5px;">DEDUCCIONES</td>
			<td colspan="2" style="'.$ib.' padding:4px 5px; text-align:right;">Importe $</td>
		</tr>';
		// Sueldo Nominal / ISR
		$html .= '<tr>
			<td colspan="3" style="padding:4px 5px; font-weight:bold;">Sueldo Nominal</td>
			<td style="'.$vd.' padding:4px 5px; text-align:right;">$ '.number_format((float)$det->sueldo_nominal, 2).'</td>
			<td colspan="3" style="padding:4px 5px; font-weight:bold;">ISR</td>
			<td colspan="2" style="padding:4px 5px; text-align:right;">$ '.number_format((float)$det->isr, 2).'</td>
		</tr>';
		// Premio Asistencia / IMSS
		$html .= '<tr>
			<td colspan="3" style="padding:4px 5px; font-weight:bold;">Premio Asistencia</td>
			<td style="'.$vd.' padding:4px 5px; text-align:right;">'.((float)$det->premio_asistencia > 0 ? '$ '.number_format((float)$det->premio_asistencia, 2) : '').'</td>
			<td colspan="3" style="padding:4px 5px; font-weight:bold;">I.M.S.S.</td>
			<td colspan="2" style="padding:4px 5px; text-align:right;">$ '.number_format((float)$det->imss, 2).'</td>
		</tr>';
		// Premio Puntualidad / INFONAVIT
		$html .= '<tr>
			<td colspan="3" style="padding:4px 5px; font-weight:bold;">Premio Puntualidad</td>
			<td style="'.$vd.' padding:4px 5px; text-align:right;">$ '.number_format((float)$det->premio_puntualidad, 2).'</td>
			<td colspan="3" style="padding:4px 5px;">'.((float)$det->infonavit > 0 ? '<strong>INFONAVIT</strong>' : '').'</td>
			<td colspan="2" style="padding:4px 5px; text-align:right;">'.((float)$det->infonavit > 0 ? '$ '.number_format((float)$det->infonavit, 2) : '').'</td>
		</tr>';
		// Vacaciones
		$html .= '<tr>
			<td colspan="3" style="padding:2px 5px; font-size:9px;">'.($vacDiasEnPeriodo > 0 ? '(' . $vacDiasEnPeriodo . ' DIAS DE VACACIONES)' : ($primaVac > 0 ? '(' . $this->getDiasVacacionesPrima($det) . ' DIAS DE VACACIONES)' : '')).'</td>
			<td style="'.$vd.' padding:2px 5px;"></td>
			<td colspan="5" style=""></td>
		</tr>';
		// PRIMA VACACIONAL
		$html .= '<tr>
			<td colspan="3" style="padding:4px 5px; font-weight:bold;">'.($primaVac > 0 ? 'PRIMA VACACIONAL' : '').'</td>
			<td style="'.$vd.' padding:4px 5px; text-align:right;">'.($primaVac > 0 ? '$ '.number_format($primaVac, 2) : '').'</td>
			<td colspan="5" style=""></td>
		</tr>';
		// COMPENSACION
		$html .= '<tr>
			<td colspan="3" style="padding:4px 5px; font-weight:bold; font-size:11px;">COMPENSACION</td>
			<td style="'.$vd.' padding:4px 5px; text-align:right; font-weight:bold;">$ '.number_format($compensacion, 2).'</td>
			<td colspan="5" style=""></td>
		</tr>';
		// Espacio
		$html .= '<tr><td colspan="4" style="'.$vd.' height:6px;"></td><td colspan="5" style=""></td></tr>';
		// Totales
		$html .= '<tr style="font-weight:bold;">
			<td colspan="2" style="padding:4px 5px;"></td>
			<td style="padding:4px 5px; text-align:right;">Total Percepciones</td>
			<td style="'.$vd.' padding:4px 5px; text-align:right; border-bottom:1px solid #000;">$ '.number_format((float)$det->total_percepciones, 2).'</td>
			<td colspan="3" style="padding:4px 5px; text-align:right;">Total de deducciones</td>
			<td colspan="2" style="padding:4px 5px; text-align:right; border-bottom:1px solid #000;">$ '.number_format((float)$det->total_deducciones, 2).'</td>
		</tr>';
		// vales
		$html .= '<tr>
			<td colspan="4" style="'.$vd.'"></td>
			<td colspan="2" style="padding:3px 5px;"></td>
			<td style="padding:3px 5px; border:1px solid #000;">vales</td>
			<td colspan="2" style="padding:3px 5px; text-align:right; border:1px solid #000;">$ '.number_format((float)$det->vales, 2).'</td>
		</tr>';
		// Efectivo
		$html .= '<tr>
			<td colspan="4" style="'.$vd.'"></td>
			<td colspan="2" style="padding:3px 5px;"></td>
			<td style="padding:3px 5px; border:1px solid #000;">Efectivo</td>
			<td colspan="2" style="padding:3px 5px; text-align:right; border:1px solid #000;">$ '.number_format((float)$det->efectivo, 2).'</td>
		</tr>';
		// SUELDO POR X DIAS / Total Neto
		$html .= '<tr>
			<td style="padding:4px 5px; font-weight:bold;">SUELDO POR</td>
			<td style="padding:4px 5px; text-align:center; font-weight:bold;">'.$diasEfectivos.'</td>
			<td colspan="2" style="'.$vd.' padding:4px 5px; font-size:9px;">DIAS DE TRABAJO EN EL</td>
			<td colspan="2" style="padding:4px 5px;"></td>
			<td style="padding:4px 5px; font-weight:bold; border:1px solid #000;">Total Neto</td>
			<td colspan="2" style="padding:4px 5px; text-align:right; font-weight:bold; border:1px solid #000;">$ '.number_format((float)$det->total_neto, 2).'</td>
		</tr>';
		// PERIODO DEL
		$html .= '<tr>
			<td colspan="2" style="padding:4px 5px; font-size:9px; font-weight:bold;">PERIODO DEL</td>
			<td style="padding:4px 5px; font-size:9px;">'.CHtml::encode($fi).'</td>
			<td style="'.$vd.' padding:4px 5px; font-size:9px; font-weight:bold;">AL</td>
			<td colspan="5" style="padding:4px 5px; font-size:9px; font-weight:bold;">'.CHtml::encode($ff).'&nbsp;&nbsp;&nbsp;RECIBI DE CONFORMIDAD SIN ADEUDOS EN HORAS EXTRAS</td>
		</tr>';
		// Texto legal
		$html .= '<tr>
			<td colspan="9" style="'.$bt.' padding:4px 5px; font-size:8px;">RECIBI DE CONFORMIDAD LA LIQUIDACI&Oacute;N EN ESTE RECIBO POR SUELDO Y PRESTACIONES POR LOS TRABAJOS QUE HE DESEMPE&Ntilde;ADO. DECLARO QUE ME CONSIDERO LEGAL Y SATISFACTORIAMENTE PAGADO HASTA LA FECHA EN VIRTUD DE HAB&Eacute;RSEME CUMPLIDO EN TODAS LAS PARTES CON LA LEY FEDERAL DEL TRABAJO.</td>
		</tr>';
		// Etiqueta copia
		$html .= '<tr>
			<td colspan="9" style="text-align:right; padding:4px 5px; font-size:9px; color:#c00; font-weight:bold;">'.$tipoCopia.'</td>
		</tr>';
		$html .= '</table>';
		$html .= '</div>';

		return $html;
	}

	private function generarHTMLRecibo($det, $empleado, $periodo, $esAguinaldo = false)
	{
		if ($esAguinaldo) {
			$html = '
			<style>
				body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 15px 20px; }
				table { border-collapse: collapse; }
			</style>';
			$html .= $this->generarCopiaRecibo($det, $empleado, $periodo, 'COPIA PATRON', true);
			$html .= '<div style="height:8px;"></div>';
			$html .= $this->generarCopiaRecibo($det, $empleado, $periodo, 'COPIA EMPLEADO', true);
		} else {
			$html = '
			<style>
				body { font-family: Arial, sans-serif; font-size: 10px; margin: 0; padding: 10px 18px; }
				table { border-collapse: collapse; }
			</style>';
			$html .= $this->generarCopiaReciboNomina($det, $empleado, $periodo, 'COPIA PATRON');
			$html .= '<div style="height:10px;"></div>';
			$html .= $this->generarCopiaReciboNomina($det, $empleado, $periodo, 'COPIA EMPLEADO');
		}

		return $html;
	}
}
