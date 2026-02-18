<?php

class RhAsistencia extends RActiveRecord
{
	const HORA_LIMITE_RETARDO = '08:00';
	const MINUTOS_ALMUERZO = 60;

	public function getDbConnection()
	{
		return self::getAdvertDbConnection();
	}

	public function tableName()
	{
		return 'rh_asistencia';
	}

	public function rules()
	{
		return array(
			array('id_periodo, id_empleado, asistencia_fecha', 'required'),
			array('asistencia_entrada, asistencia_salida, asistencia_horas, asistencia_retardo, asistencia_minutos_retardo, asistencia_tipo, asistencia_justificacion', 'safe'),
		);
	}

	public function relations()
	{
		return array(
			'rl_empleado' => array(self::BELONGS_TO, 'Empleados', 'id_empleado'),
			'rl_periodo' => array(self::BELONGS_TO, 'RhNominaPeriodos', 'id_periodo'),
		);
	}

	/**
	 * Calcula horas trabajadas, retardo, etc. a partir de entrada/salida
	 */
	public function calcular()
	{
		if ($this->asistencia_tipo != 'NORMAL' || empty($this->asistencia_entrada) || empty($this->asistencia_salida)) {
			if ($this->asistencia_tipo == 'FALTA') {
				$this->asistencia_horas = 0;
				$this->asistencia_retardo = 0;
				$this->asistencia_minutos_retardo = 0;
			}
			return;
		}

		$entrada = strtotime($this->asistencia_entrada);
		$salida = strtotime($this->asistencia_salida);

		if ($salida <= $entrada) {
			$this->asistencia_horas = 0;
			return;
		}

		$diffMin = ($salida - $entrada) / 60;

		// Restar almuerzo si trabaja mas de 6 horas
		if ($diffMin > 360) {
			$diffMin -= self::MINUTOS_ALMUERZO;
		}

		$this->asistencia_horas = round($diffMin / 60, 2);

		// Verificar retardo
		$limiteRetardo = strtotime(self::HORA_LIMITE_RETARDO);
		if ($entrada > $limiteRetardo) {
			$this->asistencia_retardo = 1;
			$this->asistencia_minutos_retardo = round(($entrada - $limiteRetardo) / 60);
		} else {
			$this->asistencia_retardo = 0;
			$this->asistencia_minutos_retardo = 0;
		}
	}

	/**
	 * Retorna el nombre del dia de la semana en espanol
	 */
	public function getNombreDia()
	{
		$dias = array(1 => 'Lunes', 2 => 'Martes', 3 => 'Miercoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sabado', 7 => 'Domingo');
		$num = date('N', strtotime($this->asistencia_fecha));
		return isset($dias[$num]) ? $dias[$num] : '';
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
