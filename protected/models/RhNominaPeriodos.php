<?php

class RhNominaPeriodos extends RActiveRecord
{
	public function getDbConnection()
	{
		return self::getAdvertDbConnection();
	}

	public function tableName()
	{
		return 'rh_nomina_periodos';
	}

	public function rules()
	{
		return array(
			array('periodo_fecha_inicio, periodo_fecha_fin', 'required'),
			array('periodo_estatus, periodo_fecha_registro, id_usuario', 'safe'),
		);
	}

	public function relations()
	{
		return array(
			'rl_detalles' => array(self::HAS_MANY, 'RhNominaDetalles', 'id_periodo'),
			'rl_asistencias' => array(self::HAS_MANY, 'RhAsistencia', 'id_periodo'),
			'rl_usuario' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
		);
	}

	public function beforeSave()
	{
		if ($this->isNewRecord) {
			$this->periodo_fecha_registro = date('Y-m-d H:i:s');
		}
		return parent::beforeSave();
	}

	public function getEtiqueta()
	{
		return date('d/m/Y', strtotime($this->periodo_fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($this->periodo_fecha_fin));
	}

	public function getTotalEmpleados()
	{
		return Yii::app()->db->createCommand()
			->select('COUNT(DISTINCT id_empleado)')
			->from('rh_nomina_detalles')
			->where('id_periodo = :id', array(':id' => $this->id_periodo))
			->queryScalar();
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
