<?php

/**
 * This is the model class for table "sucursales_productos".
 *
 * The followings are the available columns in table 'sucursales_productos':
 * @property integer $id
 * @property integer $id_sucursal
 * @property integer $id_producto
 * @property double $cantidad_stock
 * @property double $cantidad_separada
 * @property double $minimo
 * @property double $maximo
 * @property double $reorden
 * @property double $cantidad_por_recibir
 * @property double $cantidad_por_enviar
 * @property string $fecha_ultima_compra
 * @property string $fecha_ultima_venta
 * @property string $fecha_llegada
 */
class SucursalesProductos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sucursales_productos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_sucursal, id_producto', 'numerical', 'integerOnly'=>true),
			array('cantidad_stock, cantidad_separada, minimo, maximo, reorden, cantidad_por_recibir, cantidad_por_enviar', 'numerical'),
			array('fecha_llegada', 'length', 'max'=>50),
			array('fecha_ultima_compra, fecha_ultima_venta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_sucursal, id_producto, cantidad_stock, cantidad_separada, minimo, maximo, reorden, cantidad_por_recibir, cantidad_por_enviar, fecha_ultima_compra, fecha_ultima_venta, fecha_llegada', 'safe', 'on'=>'search'),
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
			'idSucursal' => array(self::BELONGS_TO, 'Sucursales', 'id_sucursal'),
			'idProducto' => array(self::BELONGS_TO, 'Productos', 'id_producto'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_sucursal' => 'Id Sucursal',
			'id_producto' => 'Id Producto',
			'cantidad_stock' => 'Cantidad Stock',
			'cantidad_separada' => 'Cantidad Separada',
			'minimo' => 'Minimo',
			'maximo' => 'Maximo',
			'reorden' => 'Reorden',
			'cantidad_por_recibir' => 'Cantidad Por Recibir',
			'cantidad_por_enviar' => 'Cantidad Por Enviar',
			'fecha_ultima_compra' => 'Fecha Ultima Compra',
			'fecha_ultima_venta' => 'Fecha Ultima Venta',
			'fecha_llegada' => 'Fecha Llegada',
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
		$criteria->compare('id_sucursal',$this->id_sucursal);
		$criteria->compare('id_producto',$this->id_producto);
		$criteria->compare('cantidad_stock',$this->cantidad_stock);
		$criteria->compare('cantidad_separada',$this->cantidad_separada);
		$criteria->compare('minimo',$this->minimo);
		$criteria->compare('maximo',$this->maximo);
		$criteria->compare('reorden',$this->reorden);
		$criteria->compare('cantidad_por_recibir',$this->cantidad_por_recibir);
		$criteria->compare('cantidad_por_enviar',$this->cantidad_por_enviar);
		$criteria->compare('fecha_ultima_compra',$this->fecha_ultima_compra,true);
		$criteria->compare('fecha_ultima_venta',$this->fecha_ultima_venta,true);
		$criteria->compare('fecha_llegada',$this->fecha_llegada,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SucursalesProductos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
