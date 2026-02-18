<?php

class RhEmpleadosHistorial extends RActiveRecord
{
	public function getDbConnection()
	{
		return self::getAdvertDbConnection();
	}

	public function tableName()
	{
		return 'rh_empleados_historial';
	}

	public function rules()
	{
		return array(
			array('id_empleado, historial_tipo, historial_fecha_movimiento', 'required'),
			array('id_empleado, id_usuario', 'numerical', 'integerOnly' => true),
			array('historial_tipo', 'in', 'range' => array('ALTA', 'BAJA', 'REINGRESO', 'MODIFICACION')),
			array('historial_observaciones, historial_estatus_anterior, historial_estatus_nuevo, historial_fecha_registro', 'safe'),
			array('id_historial, id_empleado, historial_tipo, historial_fecha_movimiento, historial_observaciones, id_usuario', 'safe', 'on' => 'search'),
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
			'id_historial' => 'ID',
			'id_empleado' => 'Empleado',
			'historial_tipo' => 'Tipo de Movimiento',
			'historial_fecha_movimiento' => 'Fecha del Movimiento',
			'historial_observaciones' => 'Observaciones',
			'historial_estatus_anterior' => 'Estatus Anterior',
			'historial_estatus_nuevo' => 'Estatus Nuevo',
			'historial_fecha_registro' => 'Fecha de Registro',
			'id_usuario' => 'Usuario',
		);
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
