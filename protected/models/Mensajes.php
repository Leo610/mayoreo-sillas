<?php

/**
 * This is the model class for table "mensajes".
 *
 * The followings are the available columns in table 'mensajes':
 * @property integer $id
 * @property integer $id_remitente
 * @property integer $id_destinatario
 * @property string $asunto
 * @property string $mensaje
 * @property string $estatus
 * @property string $fecha_alta
 * @property integer $id_mensaje_padre
 */
class Mensajes extends
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
		return 'mensajes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_remitente,id_destinatario,asunto,mensaje','required'),
			array('id_remitente, id_destinatario, id_mensaje_padre', 'numerical', 'integerOnly'=>true),
			array('asunto', 'length', 'max'=>500),
			array('estatus', 'length', 'max'=>8),
			array('mensaje, fecha_alta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_remitente, id_destinatario, asunto, mensaje, estatus, fecha_alta, id_mensaje_padre', 'safe', 'on'=>'search'),
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
			'rl_remitente'=>array(self::BELONGS_TO, 'Usuarios', 'id_remitente'),
			'rl_destinatario'=>array(self::BELONGS_TO, 'Usuarios', 'id_destinatario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_remitente' => 'Remitente',
			'id_destinatario' => 'Destinatario',
			'asunto' => 'Asunto',
			'mensaje' => 'Mensaje',
			'estatus' => 'Estatus',
			'fecha_alta' => 'Fecha Alta',
			'id_mensaje_padre' => 'Id Mensaje Padre',
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
		$criteria->compare('id_remitente',$this->id_remitente);
		$criteria->compare('id_destinatario',$this->id_destinatario);
		$criteria->compare('asunto',$this->asunto,true);
		$criteria->compare('mensaje',$this->mensaje,true);
		$criteria->compare('estatus',$this->estatus,true);
		$criteria->compare('fecha_alta',$this->fecha_alta,true);
		$criteria->compare('id_mensaje_padre',$this->id_mensaje_padre);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mensajes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
