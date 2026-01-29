<?php

/**
 * This is the model class for table "perfiles_permisos".
 *
 * The followings are the available columns in table 'perfiles_permisos':
 * @property integer $id_perfil_permisos
 * @property integer $id_perfil
 * @property integer $id_actividad
 * @property integer $valor
 */
class PerfilesPermisos extends
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
		return 'perfiles_permisos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_perfil, id_actividad, valor', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_perfil_permisos, id_perfil, id_actividad, valor', 'safe', 'on'=>'search'),
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
			'id_perfil_permisos' => 'Id Perfil Permisos',
			'id_perfil' => 'Perfil',
			'id_actividad' => 'Activdad',
			'valor' => 'Valor',
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

		$criteria->compare('id_perfil_permisos',$this->id_perfil_permisos);
		$criteria->compare('id_perfil',$this->id_perfil);
		$criteria->compare('id_actividad',$this->id_actividad);
		$criteria->compare('valor',$this->valor);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PerfilesPermisos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
