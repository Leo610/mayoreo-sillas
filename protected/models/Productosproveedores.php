<?php

/**
 * This is the model class for table "productosproveedores".
 *
 * The followings are the available columns in table 'productosproveedores':
 * @property integer $id_productos_proveedores
 * @property integer $id_producto
 * @property integer $id_proveedor
 * @property integer $productosproveedores_preciocompra
 * @property string $productosproveedores_ultimamodificacion
 * @property string $productosproveedores_tiempoentrega
 */
class Productosproveedores extends
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
		return 'productosproveedores';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_producto, id_proveedor', 'numerical', 'integerOnly'=>true),
			array('productosproveedores_ultimamodificacion', 'length', 'max'=>9999),
			array('productosproveedores_tiempoentrega', 'length', 'max'=>50),
			array('productosproveedores_preciocompra','numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_productos_proveedores, id_producto, id_proveedor, productosproveedores_preciocompra, productosproveedores_ultimamodificacion, productosproveedores_tiempoentrega', 'safe', 'on'=>'search'),
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
			'rl_proveedor'=>array(self::BELONGS_TO, 'Proveedores', 'id_proveedor')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_productos_proveedores' => 'Id Productos Proveedores',
			'id_producto' => 'Id Producto',
			'id_proveedor' => 'Id Proveedor',
			'productosproveedores_preciocompra' => 'Productosproveedores Preciocompra',
			'productosproveedores_ultimamodificacion' => 'Productosproveedores Ultimamodificacion',
			'productosproveedores_tiempoentrega' => 'Productosproveedores Tiempoentrega',
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

		$criteria->compare('id_productos_proveedores',$this->id_productos_proveedores);
		$criteria->compare('id_producto',$this->id_producto);
		$criteria->compare('id_proveedor',$this->id_proveedor);
		$criteria->compare('productosproveedores_preciocompra',$this->productosproveedores_preciocompra);
		$criteria->compare('productosproveedores_ultimamodificacion',$this->productosproveedores_ultimamodificacion,true);
		$criteria->compare('productosproveedores_tiempoentrega',$this->productosproveedores_tiempoentrega,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Productosproveedores the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
