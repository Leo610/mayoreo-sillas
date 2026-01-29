<?php

/**
 * This is the model class for table "productospaquetes".
 *
 * The followings are the available columns in table 'productospaquetes':
 * @property integer $id_pp
 * @property integer $id_producto
 * @property integer $id_productoincluido
 * @property double $pp_cantidad
 * @property integer $pp_estatus
 */
class Productospaquetes extends
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
		return 'productospaquetes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_producto,id_productoincluido,pp_cantidad','required'),
			array('id_producto, id_productoincluido, pp_estatus', 'numerical', 'integerOnly'=>true),
			array('pp_cantidad', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_pp, id_producto, id_productoincluido, pp_cantidad, pp_estatus', 'safe', 'on'=>'search'),
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
			'rl_productoincluido'=>array(self::BELONGS_TO, 'Productos', 'id_productoincluido'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_pp' => 'ID',
			'id_producto' => 'Paquete',
			'id_productoincluido' => 'Producto',
			'pp_cantidad' => 'Cantidad',
			'pp_estatus' => 'Estatus',
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

		$criteria->compare('id_pp',$this->id_pp);
		$criteria->compare('id_producto',$this->id_producto);
		$criteria->compare('id_productoincluido',$this->id_productoincluido);
		$criteria->compare('pp_cantidad',$this->pp_cantidad);
		$criteria->compare('pp_estatus',$this->pp_estatus);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Productospaquetes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
