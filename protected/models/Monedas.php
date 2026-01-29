<?php

/**
 * This is the model class for table "monedas".
 *
 * The followings are the available columns in table 'monedas':
 * @property integer $id_moneda
 * @property string $moneda_nombre
 * @property string $moneda_abreviacion
 * @property double $costo_compra
 * @property double $costo_venta
 * @property integer $default
 */
class Monedas extends
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
		return 'monedas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('moneda_nombre,costo_venta,costo_compra', 'required'),
			array('default', 'numerical', 'integerOnly'=>true),
			array('costo_compra, costo_venta', 'numerical'),
			array('moneda_nombre, moneda_abreviacion', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_moneda, moneda_nombre, moneda_abreviacion, costo_compra, costo_venta, default', 'safe', 'on'=>'search'),
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
			'id_moneda' => 'Moneda',
			'moneda_nombre' => 'Moneda Nombre',
			'moneda_abreviacion' => 'Moneda Abreviacion',
			'costo_compra' => 'Costo Compra',
			'costo_venta' => 'Costo Venta',
		
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

		$criteria->compare('id_moneda',$this->id_moneda);
		$criteria->compare('moneda_nombre',$this->moneda_nombre,true);
		$criteria->compare('moneda_abreviacion',$this->moneda_abreviacion,true);
		$criteria->compare('costo_compra',$this->costo_compra);
		$criteria->compare('costo_venta',$this->costo_venta);
		$criteria->compare('default',$this->default);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Monedas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
