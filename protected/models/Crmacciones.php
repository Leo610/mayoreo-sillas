<?php

/**
 * This is the model class for table "crmacciones".
 *
 * The followings are the available columns in table 'crmacciones':
 * @property integer $id_crm_acciones
 * @property string $crm_acciones_nombre
 * @property string $crm_acciones_icono
 * @property string $crm_acciones_color
 */
class Crmacciones extends
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
		return 'crmacciones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('crm_acciones_nombre','required'),
			array('crm_acciones_nombre', 'length', 'max'=>9999),
			array('crm_acciones_icono, crm_acciones_color', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_crm_acciones, crm_acciones_nombre, crm_acciones_icono, crm_acciones_color', 'safe', 'on'=>'search'),
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
			'id_crm_acciones' => 'ID',
			'crm_acciones_nombre' => 'Nombre',
			'crm_acciones_icono' => 'Icono',
			'crm_acciones_color' => 'Color',
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

		$criteria->compare('id_crm_acciones',$this->id_crm_acciones);
		$criteria->compare('crm_acciones_nombre',$this->crm_acciones_nombre,true);
		$criteria->compare('crm_acciones_icono',$this->crm_acciones_icono,true);
		$criteria->compare('crm_acciones_color',$this->crm_acciones_color,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Crmacciones the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
