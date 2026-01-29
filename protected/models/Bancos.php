<?php

/**
 * This is the model class for table "bancos".
 *
 * The followings are the available columns in table 'bancos':
 * @property integer $id_banco
 * @property string $banco_nombre
 * @property string $banco_comentarios
 * @property string $banco_clave
 */
class Bancos  extends
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
		return 'bancos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('banco_nombre','required'),
			array('banco_nombre, banco_comentarios, banco_clave', 'length', 'max'=>9999),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_banco, banco_nombre, banco_comentarios, banco_clave', 'safe', 'on'=>'search'),
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
			'id_banco' => 'Id Banco',
			'banco_nombre' => 'Nombre',
			'banco_comentarios' => 'Comentarios',
			'banco_clave' => 'Clave',

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

		$criteria->compare('id_banco',$this->id_banco);
		$criteria->compare('banco_nombre',$this->banco_nombre,true);
		$criteria->compare('banco_comentarios',$this->banco_comentarios,true);
		$criteria->compare('banco_clave',$this->banco_clave,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Bancos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
