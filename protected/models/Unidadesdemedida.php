<?php

/**
 * This is the model class for table "unidadesdemedida".
 *
 * The followings are the available columns in table 'unidadesdemedida':
 * @property integer $id_unidades_medida
 * @property string $unidades_medida_nombre
 * @property string $unidades_medida_abreviatura
 */
class Unidadesdemedida extends
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
		return 'unidadesdemedida';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('unidades_medida_nombre', 'required'),
			array('unidades_medida_nombre, unidades_medida_abreviatura', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_unidades_medida, unidades_medida_nombre, unidades_medida_abreviatura', 'safe', 'on'=>'search'),
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
			'id_unidades_medida' => 'Id Unidades Medida',
			'unidades_medida_nombre' => 'Unidades Medida Nombre',
			'unidades_medida_abreviatura' => 'Unidades Medida Abreviatura',
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

		$criteria->compare('id_unidades_medida',$this->id_unidades_medida);
		$criteria->compare('unidades_medida_nombre',$this->unidades_medida_nombre,true);
		$criteria->compare('unidades_medida_abreviatura',$this->unidades_medida_abreviatura,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Unidadesdemedida the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
