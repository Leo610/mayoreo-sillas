<?php

/**
 * This is the model class for table "cotizacionesplantterminos".
 *
 * The followings are the available columns in table 'cotizacionesplantterminos':
 * @property integer $id
 * @property string $nombre
 * @property string $condiciones_pago
 * @property string $tiempo_fabricacion
 * @property string $exclusiones
 * @property string $vigencia_propuesta
 * @property string $comentario
 * @property string $nombre_encargado
 * @property string $condiciones_generales
 */
class Cotizacionesplantterminos extends RActiveRecord
{
	public function getDbConnection()
	{
		return self::getAdvertDbConnection();
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cotizacionesplantterminos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre', 'length', 'max' => 200),
			array('condiciones_pago, tiempo_fabricacion, exclusiones, vigencia_propuesta, comentario, nombre_encargado, condiciones_generales', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nombre, condiciones_pago, tiempo_fabricacion, exclusiones, vigencia_propuesta, comentario, nombre_encargado, condiciones_generales', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nombre' => 'Nombre',
			'condiciones_pago' => 'Flete cotizado',
			'tiempo_fabricacion' => 'Tiempo de Entrega',
			'exclusiones' => 'Exclusiones',
			'vigencia_propuesta' => 'Vigencia Propuesta',
			'comentario' => 'Comentario',
			'nombre_encargado' => 'Nombre Encargado',
			'condiciones_generales' => 'Condiciones Generales',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('nombre', $this->nombre, true);
		$criteria->compare('condiciones_pago', $this->condiciones_pago, true);
		$criteria->compare('tiempo_fabricacion', $this->tiempo_fabricacion, true);
		$criteria->compare('exclusiones', $this->exclusiones, true);
		$criteria->compare('vigencia_propuesta', $this->vigencia_propuesta, true);
		$criteria->compare('comentario', $this->comentario, true);
		$criteria->compare('nombre_encargado', $this->nombre_encargado, true);
		$criteria->compare('condiciones_generales', $this->condiciones_generales, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		)
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cotizacionesplantterminos the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
