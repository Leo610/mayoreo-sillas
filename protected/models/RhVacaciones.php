<?php

class RhVacaciones extends RActiveRecord
{
	public function getDbConnection()
	{
		return self::getAdvertDbConnection();
	}

	public function tableName()
	{
		return 'rh_vacaciones';
	}

	public function rules()
	{
		return array(
			array('id_empleado, vacacion_fecha_inicio, vacacion_fecha_fin, vacacion_dias', 'required'),
			array('id_empleado, vacacion_dias, id_usuario', 'numerical', 'integerOnly' => true),
			array('vacacion_estatus', 'in', 'range' => array('PENDIENTE', 'APROBADA', 'RECHAZADA', 'CANCELADA')),
			array('vacacion_observaciones, vacacion_fecha_registro', 'safe'),
			array('id_vacacion, id_empleado, vacacion_fecha_inicio, vacacion_fecha_fin, vacacion_dias, vacacion_estatus, vacacion_observaciones, id_usuario', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'rl_empleado' => array(self::BELONGS_TO, 'Empleados', 'id_empleado'),
			'rl_usuario' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id_vacacion' => 'ID',
			'id_empleado' => 'Empleado',
			'vacacion_fecha_inicio' => 'Fecha Inicio',
			'vacacion_fecha_fin' => 'Fecha Fin',
			'vacacion_dias' => 'Dias',
			'vacacion_estatus' => 'Estatus',
			'vacacion_observaciones' => 'Observaciones',
			'vacacion_fecha_registro' => 'Fecha Registro',
			'id_usuario' => 'Registrado por',
		);
	}

	protected function beforeSave()
	{
		if ($this->isNewRecord) {
			$this->vacacion_fecha_registro = date('Y-m-d H:i:s');
		}
		return parent::beforeSave();
	}

	/**
	 * Calcular dias habiles entre dos fechas (excluye sabados, domingos y festivos)
	 */
	public static function calcularDiasHabiles($fechaInicio, $fechaFin)
	{
		$inicio = new DateTime($fechaInicio);
		$fin = new DateTime($fechaFin);
		$dias = 0;

		$actual = clone $inicio;
		while ($actual <= $fin) {
			$diaSemana = $actual->format('N'); // 1=lunes ... 7=domingo
			if ($diaSemana < 6) { // no es sabado ni domingo
				if (!RhDiasFestivos::esFestivo($actual->format('Y-m-d'))) {
					$dias++;
				}
			}
			$actual->modify('+1 day');
		}

		return $dias;
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
