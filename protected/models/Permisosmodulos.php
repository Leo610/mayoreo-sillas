<?php

/**
 * This is the model class for table "permisosmodulos".
 *
 * The followings are the available columns in table 'permisosmodulos':
 * @property integer $id_permisos_modulos
 * @property integer $id_modulos
 * @property integer $id_usuario
 * @property integer $permisos_modulos_permitido
 */
class Permisosmodulos extends
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
		return 'permisosmodulos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_modulos, id_usuario, permisos_modulos_permitido', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_permisos_modulos, id_modulos, id_usuario, permisos_modulos_permitido', 'safe', 'on'=>'search'),
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
			'id_permisos_modulos' => 'Id Permisos Modulos',
			'id_modulos' => 'Id Modulos',
			'id_usuario' => 'Id Usuario',
			'permisos_modulos_permitido' => 'Permisos Modulos Permitido',
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

		$criteria->compare('id_permisos_modulos',$this->id_permisos_modulos);
		$criteria->compare('id_modulos',$this->id_modulos);
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('permisos_modulos_permitido',$this->permisos_modulos_permitido);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Permisosmodulos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
