<?php

/**
 * This is the model class for table "entidades".
 *
 * The followings are the available columns in table 'entidades':
 * @property integer $ID_Entidad
 * @property string $Entidad_Clave
 * @property string $Entidad_Nombre
 * @property string $Entidad_SEO
 */
class Entidades extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'entidades';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Entidad_Clave', 'length', 'max'=>2),
			array('Entidad_Nombre', 'length', 'max'=>45),
			array('Entidad_SEO', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ID_Entidad, Entidad_Clave, Entidad_Nombre, Entidad_SEO', 'safe', 'on'=>'search'),
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
			'ID_Entidad' => 'Id Entidad',
			'Entidad_Clave' => 'Entidad Clave',
			'Entidad_Nombre' => 'Entidad Nombre',
			'Entidad_SEO' => 'Entidad Seo',
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

		$criteria->compare('ID_Entidad',$this->ID_Entidad);
		$criteria->compare('Entidad_Clave',$this->Entidad_Clave,true);
		$criteria->compare('Entidad_Nombre',$this->Entidad_Nombre,true);
		$criteria->compare('Entidad_SEO',$this->Entidad_SEO,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Entidades the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
