<?php

/**
 * This is the model class for table "perfiles_historial_mov".
 *
 * The followings are the available columns in table 'perfiles_historial_mov':
 * @property integer $id
 * @property integer $id_usuario
 * @property integer $id_actividad
 * @property string $permiso
 * @property string $fecha_hora
 */
class PerfilesHistorialMov extends
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
		return 'perfiles_historial_mov';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_usuario, id_actividad', 'numerical', 'integerOnly'=>true),
			array('permiso, fecha_hora', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_usuario, id_actividad, permiso, fecha_hora', 'safe', 'on'=>'search'),
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
			'rl_sucursal'=>array(self::BELONGS_TO, 'Sucursales', 'id_sucursal'),
			'rl_usuario'=>array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
			'rl_perfil'=>array(self::BELONGS_TO, 'Perfiles', 'id_perfil'),
			'rl_actividad'=>array(self::BELONGS_TO, 'PerfilActividades', 'id_actividad'),

			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_usuario' => 'Id Usuario',
			'id_actividad' => 'Id Actividad',
			'permiso' => 'Permiso',
			'fecha_hora' => 'Fecha Hora',
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
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('id_actividad',$this->id_actividad);
		$criteria->compare('permiso',$this->permiso,true);
		$criteria->compare('fecha_hora',$this->fecha_hora,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PerfilesHistorialMov the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
