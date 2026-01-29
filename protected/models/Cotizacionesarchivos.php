<?php

/**
 * This is the model class for table "cotizacionesarchivos".
 *
 * The followings are the available columns in table 'cotizacionesarchivos':
 * @property integer $id_cotizacion_archivo
 * @property integer $id_cotizacion
 * @property string $cotizacion_archivo_nombre
 * @property string $cotizacion_archivo
 * @property integer $agregar_a_cotizacion
 */
class Cotizacionesarchivos extends
 RActiveRecord{
	public function getDbConnection()
	{
	  return self::getAdvertDbConnection();
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cotizacionesarchivos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cotizacion_archivo_nombre', 'required'),
			array('id_cotizacion, agregar_a_cotizacion', 'numerical', 'integerOnly'=>true),
			array('cotizacion_archivo_nombre', 'length', 'max'=>350),
			array('cotizacion_archivo', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_cotizacion_archivo, id_cotizacion, cotizacion_archivo_nombre, cotizacion_archivo, agregar_a_cotizacion', 'safe', 'on'=>'search'),
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
			'id_cotizacion_archivo' => 'Id Cotizacion Archivo',
			'cotizacion_archivo_nombre' => 'Cotizacion Archivo Nombre',
			'id_cotizacion' => 'Id Cotizacion',
			'cotizacion_archivo' => 'Cotizacion Archivo',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id_cotizacion_archivo',$this->id_cotizacion_archivo);
		$criteria->compare('id_cotizacion',$this->id_cotizacion);
		$criteria->compare('cotizacion_archivo_nombre',$this->cotizacion_archivo_nombre,true);
		$criteria->compare('cotizacion_archivo',$this->cotizacion_archivo,true);
		$criteria->compare('agregar_a_cotizacion',$this->agregar_a_cotizacion);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cotizacionesarchivos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
