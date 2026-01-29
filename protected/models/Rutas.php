<?php

/**
 * This is the model class for table "rutas".
 *
 * The followings are the available columns in table 'rutas':
 * @property integer $id
 * @property integer $id_vendedor
 * @property string $nombre
 * @property string $fecha_desde
 * @property string $fecha_hasta
 * @property string $estatus
 * @property string $comentarios
 * @property string $fecha_alta
 */
class Rutas extends
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
		return 'rutas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_vendedor,fecha_desde,fecha_hasta,nombre','required'),
			array('id_vendedor', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>200),
			array('estatus', 'length', 'max'=>10),
			array('comentarios', 'length', 'max'=>9999),
			array('fecha_desde, fecha_hasta, fecha_alta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_vendedor, nombre, fecha_desde, fecha_hasta, estatus, comentarios, fecha_alta', 'safe', 'on'=>'search'),
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
			'rl_vendedor'=>array(self::BELONGS_TO, 'Usuarios', 'id_vendedor'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_vendedor' => 'Id Vendedor',
			'nombre' => 'Nombre',
			'fecha_desde' => 'Fecha Desde',
			'fecha_hasta' => 'Fecha Hasta',
			'estatus' => 'Estatus',
			'comentarios' => 'Comentarios',
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
		$criteria->compare('id_vendedor',$this->id_vendedor);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('fecha_desde',$this->fecha_desde,true);
		$criteria->compare('fecha_hasta',$this->fecha_hasta,true);
		$criteria->compare('estatus',$this->estatus,true);
		$criteria->compare('comentarios',$this->comentarios,true);
		$criteria->compare('fecha_alta',$this->fecha_alta,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rutas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
