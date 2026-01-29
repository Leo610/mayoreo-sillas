<?php

/**
 * This is the model class for table "proveedorespagos".
 *
 * The followings are the available columns in table 'proveedorespagos':
 * @property integer $id_proveedores_pagos
 * @property integer $id_orden_de_compra
 * @property double $proveedorespagos_cantidad
 * @property integer $id_formapago
 * @property integer $id_banco
 * @property integer $id_usuario
 * @property string $proveedorespagos_fechaalta
 */
class Proveedorespagos extends
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
		return 'proveedorespagos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_orden_de_compra, id_formapago, id_banco, id_usuario', 'numerical', 'integerOnly'=>true),
			array('proveedorespagos_cantidad', 'numerical'),
			array('proveedorespagos_fechaalta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_proveedores_pagos, id_orden_de_compra, proveedorespagos_cantidad, id_formapago, id_banco, id_usuario, proveedorespagos_fechaalta', 'safe', 'on'=>'search'),
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
			'id_proveedores_pagos' => 'Id Proveedores Pagos',
			'id_orden_de_compra' => 'Id Orden De Compra',
			'proveedorespagos_cantidad' => 'Proveedorespagos Cantidad',
			'id_formapago' => 'Id Formapago',
			'id_banco' => 'Id Banco',
			'id_usuario' => 'Id Usuario',
			'proveedorespagos_fechaalta' => 'Proveedorespagos Fechaalta',
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

		$criteria->compare('id_proveedores_pagos',$this->id_proveedores_pagos);
		$criteria->compare('id_orden_de_compra',$this->id_orden_de_compra);
		$criteria->compare('proveedorespagos_cantidad',$this->proveedorespagos_cantidad);
		$criteria->compare('id_formapago',$this->id_formapago);
		$criteria->compare('id_banco',$this->id_banco);
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('proveedorespagos_fechaalta',$this->proveedorespagos_fechaalta,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Proveedorespagos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
