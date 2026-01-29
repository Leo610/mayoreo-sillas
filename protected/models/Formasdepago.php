<?php

/**
 * This is the model class for table "formasdepago".
 *
 * The followings are the available columns in table 'formasdepago':
 * @property integer $id_formapago
 * @property string $formapago_nombre
 * @property string $formapago_descripcion
 */
class Formasdepago extends
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
		return 'formasdepago';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('formapago_nombre, formapago_descripcion', 'required'),
			array('formapago_nombre, formapago_descripcion', 'length', 'max'=>9999),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_formapago, formapago_nombre, formapago_descripcion', 'safe', 'on'=>'search'),
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
			'id_formapago' => 'ID',
			'formapago_nombre' => 'Forma de Pago',
			'formapago_descripcion' => 'Descripción',
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

		$criteria->compare('id_formapago',$this->id_formapago);
		$criteria->compare('formapago_nombre',$this->formapago_nombre,true);
		$criteria->compare('formapago_descripcion',$this->formapago_descripcion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Formasdepago the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
