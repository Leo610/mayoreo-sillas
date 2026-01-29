<?php

/**
 * This is the model class for table "ordenes_compra_detalles".
 *
 * The followings are the available columns in table 'ordenes_compra_detalles':
 * @property integer $id
 * @property integer $id_orden_compra
 * @property integer $id_producto
 * @property double $unitario
 * @property double $descuento
 * @property double $iva
 * @property double $cantidad
 * @property double $total
 * @property double $cantidad_original
 * @property double $cantidad_recibida
 * @property double $cantidad_pendiente
 * @property integer $eliminado
 * @property double $subtotal_unitario
 * @property double $subtotal_iva
 * @property string $concepto
 */
class OrdenesCompraDetalles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ordenes_compra_detalles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_orden_compra, id_producto, eliminado', 'numerical', 'integerOnly' => true),
			array('unitario, descuento, iva, cantidad, total, cantidad_original, cantidad_recibida, cantidad_pendiente, subtotal_unitario, subtotal_iva', 'numerical'),
			array('concepto', 'length', 'max' => 300),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_orden_compra, id_producto, unitario, descuento, iva, cantidad, total, cantidad_original, cantidad_recibida, cantidad_pendiente, eliminado, subtotal_unitario, subtotal_iva, concepto', 'safe', 'on' => 'search'),
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
			'id_orden_compra' => 'Id Orden Compra',
			'id_producto' => 'Id Producto',
			'unitario' => 'Unitario',
			'descuento' => 'Descuento',
			'iva' => 'Iva',
			'cantidad' => 'Cantidad',
			'total' => 'Total',
			'cantidad_original' => 'Cantidad Original',
			'cantidad_recibida' => 'Cantidad Recibida',
			'cantidad_pendiente' => 'Cantidad Pendiente',
			'eliminado' => 'Eliminado',
			'subtotal_unitario' => 'Subtotal Unitario',
			'subtotal_iva' => 'Subtotal Iva',
			'concepto' => 'Concepto',
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

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('id_orden_compra', $this->id_orden_compra);
		$criteria->compare('id_producto', $this->id_producto);
		$criteria->compare('unitario', $this->unitario);
		$criteria->compare('descuento', $this->descuento);
		$criteria->compare('iva', $this->iva);
		$criteria->compare('cantidad', $this->cantidad);
		$criteria->compare('total', $this->total);
		$criteria->compare('cantidad_original', $this->cantidad_original);
		$criteria->compare('cantidad_recibida', $this->cantidad_recibida);
		$criteria->compare('cantidad_pendiente', $this->cantidad_pendiente);
		$criteria->compare('eliminado', $this->eliminado);
		$criteria->compare('subtotal_unitario', $this->subtotal_unitario);
		$criteria->compare('subtotal_iva', $this->subtotal_iva);
		$criteria->compare('concepto', $this->concepto, true);

		return new CActiveDataProvider(
			$this,
			array(
				'criteria' => $criteria,
			)
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrdenesCompraDetalles the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}