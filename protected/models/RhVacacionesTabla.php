<?php

class RhVacacionesTabla extends RActiveRecord
{
	public function getDbConnection()
	{
		return self::getAdvertDbConnection();
	}

	public function tableName()
	{
		return 'rh_vacaciones_tabla';
	}

	public function rules()
	{
		return array(
			array('anios_antiguedad, dias_vacaciones', 'required'),
			array('anios_antiguedad, dias_vacaciones', 'numerical', 'integerOnly' => true),
		);
	}

	public function relations()
	{
		return array();
	}

	public function attributeLabels()
	{
		return array(
			'id_vacaciones_tabla' => 'ID',
			'anios_antiguedad' => 'Años de Antigüedad',
			'dias_vacaciones' => 'Días de Vacaciones',
		);
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
