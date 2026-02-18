<?php

class RhDiasFestivos extends RActiveRecord
{
	public function getDbConnection()
	{
		return self::getAdvertDbConnection();
	}

	public function tableName()
	{
		return 'rh_dias_festivos';
	}

	public function rules()
	{
		return array(
			array('festivo_fecha, festivo_descripcion', 'required'),
			array('festivo_anio', 'numerical', 'integerOnly' => true),
			array('festivo_descripcion', 'length', 'max' => 255),
			array('id_dia_festivo, festivo_fecha, festivo_descripcion, festivo_anio', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array();
	}

	public function attributeLabels()
	{
		return array(
			'id_dia_festivo' => 'ID',
			'festivo_fecha' => 'Fecha',
			'festivo_descripcion' => 'Descripcion',
			'festivo_anio' => 'Anio',
		);
	}

	/**
	 * Verificar si una fecha es dia festivo
	 */
	public static function esFestivo($fecha)
	{
		$model = self::model()->find(
			'festivo_fecha = :fecha',
			array(':fecha' => $fecha)
		);
		return !empty($model);
	}

	/**
	 * Obtener todos los festivos de un anio
	 */
	public static function obtenerFestivosPorAnio($anio)
	{
		$rows = self::model()->findAll(
			'festivo_anio = :anio',
			array(':anio' => $anio)
		);
		$fechas = array();
		foreach ($rows as $r) {
			$fechas[] = $r->festivo_fecha;
		}
		return $fechas;
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
