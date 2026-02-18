<?php

require_once(__DIR__ . '/../vendor/dompdf/autoload.inc.php');
use Dompdf\Dompdf;

class RhfiniquitosController extends Controller
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

	/**
	 * Lista de finiquitos + formulario para calcular nuevo
	 */
	public function actionIndex()
	{
		$this->layout = "main";

		$empleados = Empleados::model()->findAll(array('order' => 'empleado_nombre ASC'));
		$lista = RhFiniquitos::model()->findAll(array(
			'with' => array('rl_empleado'),
			'order' => 'finiquito_fecha_registro DESC',
		));

		$id_empleado_pre = isset($_GET['empleado']) ? (int)$_GET['empleado'] : '';

		$this->render('index', array(
			'empleados' => $empleados,
			'lista' => $lista,
			'id_empleado_pre' => $id_empleado_pre,
		));
	}

	/**
	 * AJAX: Calcular y guardar finiquito
	 * Solo requiere: id_empleado, fecha_renuncia, fecha_aguinaldo
	 * Sueldos salen automaticos del empleado (VBA: INTEGRADO*7 y SUELDO SEMANAL REAL)
	 */
	public function actionCalcular()
	{
		$id_empleado = isset($_POST['id_empleado']) ? (int)$_POST['id_empleado'] : 0;
		$fecha_renuncia = isset($_POST['fecha_renuncia']) ? $_POST['fecha_renuncia'] : '';
		$fecha_aguinaldo = isset($_POST['fecha_aguinaldo']) ? $_POST['fecha_aguinaldo'] : '';
		$observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : '';
		$dias_pendientes = isset($_POST['dias_pendientes']) && $_POST['dias_pendientes'] !== '' ? (float)$_POST['dias_pendientes'] : null;

		if (empty($id_empleado) || empty($fecha_renuncia) || empty($fecha_aguinaldo)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Complete todos los campos requeridos'));
			Yii::app()->end();
		}

		$empleado = Empleados::model()->findByPk($id_empleado);
		if (empty($empleado)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Empleado no encontrado'));
			Yii::app()->end();
		}

		// Validar que el empleado tenga datos de sueldo
		if (empty($empleado->empleado_integrado) || (float)$empleado->empleado_integrado <= 0) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'El empleado no tiene Salario Diario Integrado configurado'));
			Yii::app()->end();
		}
		if (empty($empleado->empleado_sueldo_semanal_real) || (float)$empleado->empleado_sueldo_semanal_real <= 0) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'El empleado no tiene Sueldo Semanal Real configurado'));
			Yii::app()->end();
		}

		$finiquito = new RhFiniquitos;
		$finiquito->id_empleado = $id_empleado;
		$finiquito->finiquito_fecha_renuncia = $fecha_renuncia;
		$finiquito->finiquito_fecha_aguinaldo = $fecha_aguinaldo;
		$finiquito->finiquito_observaciones = $observaciones;
		$finiquito->id_usuario = Yii::app()->user->id;

		if (!$finiquito->calcularFiniquito($dias_pendientes)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al calcular el finiquito'));
			Yii::app()->end();
		}

		if (!$finiquito->save()) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al guardar: ' . implode(', ', array_map(function($e){ return implode(', ', $e); }, $finiquito->getErrors()))));
			Yii::app()->end();
		}

		echo CJSON::encode(array(
			'requestresult' => 'ok',
			'message' => 'Finiquito calculado. IMSS: $' . number_format($finiquito->finiquito_total_imss, 2) . ' | Real: $' . number_format($finiquito->finiquito_total, 2) . ' | Compensacion: $' . number_format($finiquito->finiquito_compensacion, 2),
			'id_finiquito' => $finiquito->id_finiquito,
		));
		Yii::app()->end();
	}

	/**
	 * Detalle de un finiquito
	 */
	public function actionVer($id)
	{
		$this->layout = "main";

		$finiquito = RhFiniquitos::model()->with('rl_empleado')->findByPk($id);
		if (empty($finiquito)) {
			throw new CHttpException(404, 'Finiquito no encontrado');
		}

		$this->render('ver', array('finiquito' => $finiquito));
	}

	/**
	 * AJAX: Eliminar finiquito
	 */
	public function actionEliminar()
	{
		$id = isset($_POST['id_finiquito']) ? (int)$_POST['id_finiquito'] : 0;
		$finiquito = RhFiniquitos::model()->findByPk($id);

		if (empty($finiquito)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Finiquito no encontrado'));
			Yii::app()->end();
		}

		$finiquito->delete();
		echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Finiquito eliminado'));
		Yii::app()->end();
	}

	/**
	 * Generar PDF formato finiquito (carta de renuncia voluntaria)
	 */
	public function actionFormato($id)
	{
		$finiquito = RhFiniquitos::model()->with('rl_empleado')->findByPk($id);
		if (empty($finiquito)) {
			throw new CHttpException(404, 'Finiquito no encontrado');
		}

		$emp = $finiquito->rl_empleado;
		$html = $this->generarHTMLFormato($finiquito, $emp);

		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('letter', 'portrait');
		$dompdf->render();
		$dompdf->stream('Finiquito_' . ($emp->empleado_num_empleado ?: $emp->id_empleado) . '_' . $finiquito->finiquito_fecha_renuncia . '.pdf', array('Attachment' => false));
		Yii::app()->end();
	}

	/**
	 * Genera HTML para el PDF del formato de finiquito.
	 * Replica EXACTA de la hoja FORMATO FINIQUITO del Excel:
	 * Carta de renuncia voluntaria con tabla de montos.
	 *
	 * Mapeo de la tabla (igual que el Excel):
	 * 1 DIA LABORADO  = Aguinaldo IMSS
	 * AGUINALDO       = Vacaciones IMSS
	 * VACACIONES      = Prima Vacacional IMSS
	 * PRIMA VACAC     = Compensacion (Real - IMSS)
	 * FINIQUITO TOTAL = Total Real
	 */
	private function generarHTMLFormato($finiquito, $emp)
	{
		$nombre = mb_strtoupper(CHtml::encode($emp->empleado_nombre), 'UTF-8');

		$html = '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
	@page { margin: 25mm 25mm; }
	body { font-family: Arial, sans-serif; font-size: 12pt; margin: 0; padding: 0; color: #000; }
	.titulo { text-align: center; font-size: 16pt; font-weight: bold; margin-top: 50px; margin-bottom: 40px; }
	.texto { font-size: 11pt; line-height: 2; text-align: left; }
	.nombre-emp { font-weight: bold; text-decoration: underline; }
	.tabla-montos { border-collapse: collapse; margin: 40px auto 0 auto; }
	.tabla-montos td { border: 1px solid #000; padding: 5px 12px; font-size: 11pt; }
	.tabla-montos .concepto { text-align: left; width: 180px; }
	.tabla-montos .monto { text-align: right; width: 120px; }
	.tabla-montos .total-row td { font-weight: bold; }
	.atte { margin-top: 80px; font-size: 11pt; }
	.firma-centro { text-align: center; margin-top: 100px; }
	.firma-linea { border-top: 1px solid #000; width: 250px; margin: 0 auto; padding-top: 5px; font-size: 12pt; font-weight: bold; }
</style>
</head>
<body>

<div class="titulo">RENUNCIA VOLUNTARIA</div>

<div class="texto">
POR MEDIO DE ESTE CONDUCTO HAGO CONSTAR, EN PLENO USO DE MIS FACULTADES MENTALES, YO
<span class="nombre-emp">' . $nombre . '</span>, PRESENTO MI RENUNCIA VOLUNTARIA CON EL CAR&Aacute;CTER DE IRREVOCABLE
AL PUESTO QUE VENIA DESEMPE&Ntilde;ANDO, EN LA INTELIGENCIA DE QUE NO EJERZO ACCION LEGAL ALGUNA EN
SU CONTRA, CABE HACER MENCION QUE NO SE ADEUDAN MONTOS NI PRESTACIONES, ACLARANDO ADEMAS QUE
DURANTE EL TIEMPO LABORAL NO SUFRI ACCIDENTE LABORAL NI PADEZCO NINGUNA ENFERMEDAD DE
CAR&Aacute;CTER PROFESIONAL.
</div>

<table class="tabla-montos">
	<tr>
		<td class="concepto">1 DIA LABORADO</td>
		<td class="monto">$ ' . number_format((float)$finiquito->finiquito_aguinaldo_imss, 2) . '</td>
	</tr>
	<tr>
		<td class="concepto">AGUINALDO</td>
		<td class="monto">$ ' . number_format((float)$finiquito->finiquito_vacaciones_imss, 2) . '</td>
	</tr>
	<tr>
		<td class="concepto">VACACIONES</td>
		<td class="monto">$ ' . number_format((float)$finiquito->finiquito_prima_imss, 2) . '</td>
	</tr>
	<tr>
		<td class="concepto">PRIMA VACAC</td>
		<td class="monto">$ ' . number_format((float)$finiquito->finiquito_compensacion, 2) . '</td>
	</tr>
	<tr class="total-row">
		<td class="concepto">FINIQUITO TOTAL</td>
		<td class="monto">$ ' . number_format((float)$finiquito->finiquito_total, 2) . '</td>
	</tr>
</table>

<div class="atte">
	ATTE. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $nombre . '
</div>

<div class="firma-centro">
	<div class="firma-linea">FIRMA</div>
</div>

</body>
</html>';

		return $html;
	}
}
