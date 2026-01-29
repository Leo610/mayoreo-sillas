<?php

/**
 * This is the model class for table "crmtiempos".
 *
 * The followings are the available columns in table 'crmtiempos':
 * @property integer $id
 * @property integer $id_oportunidad
 * @property integer $id_etapa
 * @property string $fecha_alta
 */
class Crmtiempos extends
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
		return 'crmtiempos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_oportunidad, id_etapa', 'numerical', 'integerOnly'=>true),
			array('fecha_alta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_oportunidad, id_etapa, fecha_alta', 'safe', 'on'=>'search'),
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
			'id_oportunidad' => 'Id Oportunidad',
			'id_etapa' => 'Id Etapa',
			'fecha_alta' => 'Fecha Alta',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('id_oportunidad',$this->id_oportunidad);
		$criteria->compare('id_etapa',$this->id_etapa);
		$criteria->compare('fecha_alta',$this->fecha_alta,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Crmtiempos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
