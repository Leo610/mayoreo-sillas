<?php

/**
 * This is the model class for table "rutas_detalles".
 *
 * The followings are the available columns in table 'rutas_detalles':
 * @property integer $id
 * @property integer $id_ruta
 * @property integer $id_cliente
 * @property string $nombre
 * @property integer $orden
 * @property string $comentarios
 * @property string $fecha_visita
 * @property string $hora_visita
 * @property string $estatus
 * @property string $fecha_alta
 * @property string $fecha_ultima_modif
 * @property string $resultado
 */
class RutasDetalles extends
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
		return 'rutas_detalles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_ruta, id_cliente, orden', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>999),
			array('comentarios, resultado', 'length', 'max'=>9999),
			array('estatus', 'length', 'max'=>12),
			array('fecha_visita, hora_visita, fecha_alta, fecha_ultima_modif', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_ruta, id_cliente, nombre, orden, comentarios, fecha_visita, hora_visita, estatus, fecha_alta, fecha_ultima_modif, resultado', 'safe', 'on'=>'search'),
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
			'rl_clientes'=>array(self::BELONGS_TO, 'Clientes', 'id_cliente'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_ruta' => 'Ruta',
			'id_cliente' => 'Cliente',
			'nombre' => 'Nombre',
			'orden' => 'Orden',
			'comentarios' => 'Comentarios',
			'fecha_visita' => 'Fecha Visita',
			'hora_visita' => 'Hora Visita',
			'estatus' => 'Estatus',
			'fecha_alta' => 'Fecha Alta',
			'fecha_ultima_modif' => 'Fecha Ultima Modif',
			'resultado' => 'Resultado',
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
		$criteria->compare('id_ruta',$this->id_ruta);
		$criteria->compare('id_cliente',$this->id_cliente);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('orden',$this->orden);
		$criteria->compare('comentarios',$this->comentarios,true);
		$criteria->compare('fecha_visita',$this->fecha_visita,true);
		$criteria->compare('hora_visita',$this->hora_visita,true);
		$criteria->compare('estatus',$this->estatus,true);
		$criteria->compare('fecha_alta',$this->fecha_alta,true);
		$criteria->compare('fecha_ultima_modif',$this->fecha_ultima_modif,true);
		$criteria->compare('resultado',$this->resultado,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RutasDetalles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
