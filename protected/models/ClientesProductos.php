<?php

/**
 * This is the model class for table "clientes_productos".
 *
 * The followings are the available columns in table 'clientes_productos':
 * @property integer $id
 * @property integer $id_cliente
 * @property integer $id_producto
 * @property string $comentarios
 */
class ClientesProductos extends RActiveRecord{
	public function getDbConnection()
	{
	  return self::getAdvertDbConnection();
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'clientes_productos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_cliente,id_producto','required'),
			array('id_cliente, id_producto', 'numerical', 'integerOnly'=>true),
			array('comentarios', 'length', 'max'=>999),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_cliente, id_producto, comentarios', 'safe', 'on'=>'search'),
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
			'rl_producto'=>array(self::BELONGS_TO, 'Productos', 'id_producto'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_cliente' => 'Cliente',
			'id_producto' => 'Producto',
			'comentarios' => 'Comentarios',
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
		$criteria->compare('id_cliente',$this->id_cliente);
		$criteria->compare('id_producto',$this->id_producto);
		$criteria->compare('comentarios',$this->comentarios,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClientesProductos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
