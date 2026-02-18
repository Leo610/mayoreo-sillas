<?php

class RhempleadosController extends Controller
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
			'postOnly + delete',
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
	 * Listado de empleados
	 */
	public function actionAdmin()
	{
		$this->layout = "main";

		$filtro_estatus = isset($_GET['estatus']) ? $_GET['estatus'] : 'ACTIVO';

		$criteria = array('order' => 'empleado_nombre ASC');
		if (!empty($filtro_estatus)) {
			$criteria['condition'] = 'empleado_estatus = :est';
			$criteria['params'] = array(':est' => $filtro_estatus);
		}

		$lista = Empleados::model()->findAll($criteria);
		$model = new Empleados;

		$this->render('admin', array(
			'model' => $model,
			'lista' => $lista,
			'filtro_estatus' => $filtro_estatus,
		));
	}

	/**
	 * Campos que se rastrean en el historial de modificaciones
	 */
	private static $_camposRastreados = array(
		'empleado_num_reloj', 'empleado_num_empleado', 'empleado_nombre',
		'empleado_fecha_ingreso', 'empleado_estatus', 'empleado_seguro_social',
		'empleado_rfc', 'empleado_puesto', 'empleado_tarjeta_efectivale',
		'empleado_habitante_casa', 'empleado_sueldo_semanal', 'empleado_sueldo_semanal_real',
		'empleado_sueldo_diario', 'empleado_integrado', 'empleado_costo_hora',
		'empleado_bono_asistencia', 'empleado_bono_puntualidad', 'empleado_bono_productividad',
		'empleado_bono_condicional', 'empleado_imss', 'empleado_infonavit', 'empleado_isr',
		'empleado_rebaja_bono', 'empleado_requiere_checador', 'empleado_dias_tomados', 'empleado_dias_pendientes',
		'empleado_dias_prima_vacacional', 'empleado_observaciones',
	);

	/**
	 * Crear o actualizar empleado
	 */
	public function actionCreateorupdate()
	{
		$id = $_POST['Empleados']['id_empleado'];
		$model = $this->loadModel($id);
		$esNuevo = empty($model);

		if ($esNuevo) {
			$model = new Empleados;
		}

		if (isset($_POST['Empleados'])) {
			// Guardar valores anteriores para detectar cambios
			$valoresAnteriores = array();
			if (!$esNuevo) {
				foreach (self::$_camposRastreados as $campo) {
					$valoresAnteriores[$campo] = $model->$campo;
				}
			}

			$model->attributes = $_POST['Empleados'];
			$model->id_usuario = Yii::app()->user->id;

			if ($model->save()) {
				if ($esNuevo) {
					$historial = new RhEmpleadosHistorial;
					$historial->id_empleado = $model->id_empleado;
					$historial->historial_tipo = 'ALTA';
					$historial->historial_fecha_movimiento = date('Y-m-d');
					$historial->historial_estatus_anterior = '';
					$historial->historial_estatus_nuevo = 'ACTIVO';
					$historial->historial_observaciones = 'Alta de empleado en sistema';
					$historial->id_usuario = Yii::app()->user->id;
					$historial->save();
				} else {
					// Detectar campos que cambiaron
					$labels = $model->attributeLabels();
					$cambios = array();
					foreach (self::$_camposRastreados as $campo) {
						$viejo = trim((string)$valoresAnteriores[$campo]);
						$nuevo = trim((string)$model->$campo);
						if ($viejo !== $nuevo) {
							$label = isset($labels[$campo]) ? $labels[$campo] : $campo;
							$viejoTxt = $viejo === '' ? '(vacio)' : $viejo;
							$nuevoTxt = $nuevo === '' ? '(vacio)' : $nuevo;
							$cambios[] = $label . ': ' . $viejoTxt . ' → ' . $nuevoTxt;
						}
					}

					if (!empty($cambios)) {
						$historial = new RhEmpleadosHistorial;
						$historial->id_empleado = $model->id_empleado;
						$historial->historial_tipo = 'MODIFICACION';
						$historial->historial_fecha_movimiento = date('Y-m-d');
						$historial->historial_estatus_anterior = '';
						$historial->historial_estatus_nuevo = '';
						$historial->historial_observaciones = implode("\n", $cambios);
						$historial->id_usuario = Yii::app()->user->id;
						$historial->save();
					}
				}
				Yii::app()->user->setFlash('success', 'Se guardo correctamente.');
			} else {
				Yii::app()->user->setFlash('warning', 'Ocurrio un error al guardar.');
			}
		}

		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		if ($urlfrom) {
			$this->redirect($urlfrom);
		}
		$this->redirect('admin');
	}

	/**
	 * Vista de detalles del empleado
	 */
	public function actionDetalles()
	{
		$this->layout = "main";
		$id = isset($_GET['id_empleado']) ? $_GET['id_empleado'] : '';
		$Datos = Empleados::model()->findByPk($id);

		if (empty($Datos)) {
			$this->redirect(array('rhempleados/admin'));
		}

		$model = new Empleados;
		$historial = RhEmpleadosHistorial::model()->with('rl_usuario')->findAll(array(
			'condition' => 't.id_empleado = :id',
			'params' => array(':id' => $id),
			'order' => 't.historial_fecha_registro DESC',
		));

		$vacaciones = RhVacaciones::model()->findAll(array(
			'condition' => 'id_empleado = :id AND vacacion_estatus = "APROBADA"',
			'params' => array(':id' => $id),
			'order' => 'vacacion_fecha_inicio DESC',
		));

		$this->render('detalles', array(
			'id' => $id,
			'model' => $model,
			'Datos' => $Datos,
			'historial' => $historial,
			'vacaciones' => $vacaciones,
		));
	}

	/**
	 * AJAX: obtener datos del empleado para el modal
	 */
	/**
	 * AJAX: Buscar empleado por RFC (CURP) para detectar reingreso
	 */
	public function actionBuscarrfc()
	{
		$rfc = isset($_POST['rfc']) ? trim($_POST['rfc']) : '';
		if (strlen($rfc) < 10) {
			echo CJSON::encode(array('encontrado' => false));
			Yii::app()->end();
		}

		$emp = Empleados::model()->find(
			'empleado_rfc = :rfc',
			array(':rfc' => $rfc)
		);

		if (!empty($emp)) {
			echo CJSON::encode(array(
				'encontrado' => true,
				'id_empleado' => $emp->id_empleado,
				'nombre' => $emp->empleado_nombre,
				'estatus' => $emp->empleado_estatus,
				'url_detalle' => Yii::app()->createUrl('rhempleados/detalles', array('id_empleado' => $emp->id_empleado)),
			));
		} else {
			echo CJSON::encode(array('encontrado' => false));
		}
		Yii::app()->end();
	}

	public function actionDatos()
	{
		$id = $_POST['id'];
		$Datos = Empleados::model()->findByPk($id);

		echo CJSON::encode(array(
			'requestresult' => 'ok',
			'Datos' => $Datos,
			'antiguedad' => $Datos->getAntiguedad(),
			'diasVacaciones' => $Datos->getDiasVacaciones(),
		));
	}

	/**
	 * AJAX: dar de baja empleado
	 */
	public function actionDardebaja()
	{
		if (!isset($_POST['id_empleado'])) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'ID requerido'));
			Yii::app()->end();
		}

		$id = $_POST['id_empleado'];
		$fecha_baja = $_POST['fecha_baja'];
		$observaciones = $_POST['observaciones'];

		$model = $this->loadModel($id);
		if (empty($model)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Empleado no encontrado'));
			Yii::app()->end();
		}

		$estatusAnterior = $model->empleado_estatus;
		$model->empleado_estatus = 'INACTIVO';
		$model->empleado_fecha_baja = $fecha_baja;
		$model->empleado_observaciones = $observaciones;

		if ($model->save()) {
			$historial = new RhEmpleadosHistorial;
			$historial->id_empleado = $id;
			$historial->historial_tipo = 'BAJA';
			$historial->historial_fecha_movimiento = $fecha_baja;
			$historial->historial_estatus_anterior = $estatusAnterior;
			$historial->historial_estatus_nuevo = 'INACTIVO';
			$historial->historial_observaciones = $observaciones;
			$historial->id_usuario = Yii::app()->user->id;
			$historial->save();

			// Calcular finiquito automaticamente
			$finiquitoMsg = '';
			$finiquitoId = null;
			if (!empty($model->empleado_fecha_ingreso)
				&& (float)$model->empleado_integrado > 0
				&& (float)$model->empleado_sueldo_semanal_real > 0) {

				$fechaBajaDt = new DateTime($fecha_baja);
				$fechaIngresoDt = new DateTime($model->empleado_fecha_ingreso);
				$anioIngreso = (int)$fechaIngresoDt->format('Y');
				$anioBaja = (int)$fechaBajaDt->format('Y');

				// VBA: Si ingreso mismo año → fecha_ingreso, si no → 1-ene del año de baja
				$fechaAguinaldo = ($anioIngreso == $anioBaja)
					? $model->empleado_fecha_ingreso
					: $anioBaja . '-01-01';

				$finiquito = new RhFiniquitos;
				$finiquito->id_empleado = $id;
				$finiquito->finiquito_fecha_renuncia = $fecha_baja;
				$finiquito->finiquito_fecha_aguinaldo = $fechaAguinaldo;
				$finiquito->finiquito_observaciones = $observaciones;
				$finiquito->id_usuario = Yii::app()->user->id;

				if ($finiquito->calcularFiniquito() && $finiquito->save()) {
					$finiquitoId = $finiquito->id_finiquito;
					$finiquitoMsg = ' | Finiquito: $' . number_format($finiquito->finiquito_total, 2);
				}
			}

			echo CJSON::encode(array(
				'requestresult' => 'ok',
				'message' => 'Empleado dado de baja correctamente' . $finiquitoMsg,
				'id_finiquito' => $finiquitoId,
			));
		} else {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al dar de baja'));
		}
		Yii::app()->end();
	}

	/**
	 * AJAX: reactivar empleado
	 */
	public function actionReactivar()
	{
		if (!isset($_POST['id_empleado'])) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'ID requerido'));
			Yii::app()->end();
		}

		$id = $_POST['id_empleado'];
		$model = $this->loadModel($id);

		if (empty($model)) {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Empleado no encontrado'));
			Yii::app()->end();
		}

		$estatusAnterior = $model->empleado_estatus;
		$model->empleado_estatus = 'ACTIVO';
		$model->empleado_fecha_baja = null;

		if ($model->save()) {
			$historial = new RhEmpleadosHistorial;
			$historial->id_empleado = $id;
			$historial->historial_tipo = 'REINGRESO';
			$historial->historial_fecha_movimiento = date('Y-m-d');
			$historial->historial_estatus_anterior = $estatusAnterior;
			$historial->historial_estatus_nuevo = 'ACTIVO';
			$historial->historial_observaciones = 'Reactivacion de empleado';
			$historial->id_usuario = Yii::app()->user->id;
			$historial->save();

			echo CJSON::encode(array('requestresult' => 'ok', 'message' => 'Empleado reactivado correctamente'));
		} else {
			echo CJSON::encode(array('requestresult' => 'error', 'message' => 'Error al reactivar'));
		}
		Yii::app()->end();
	}

	/**
	 * Eliminar empleado
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		Yii::app()->user->setFlash('success', 'Se elimino con exito.');
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function loadModel($id)
	{
		$model = Empleados::model()->findByPk($id);
		return $model;
	}
}
