<?php

/**
 * This is the model class for table "transferencia_detalles".
 *
 * The followings are the available columns in table 'transferencia_detalles':
 * @property integer $id
 * @property integer $id_transferencia
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
 * @property double $cantidad_salida
 * @property double $cantidad_por_salir
 */
class TransferenciaDetalles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transferencia_detalles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_transferencia, id_producto, eliminado', 'numerical', 'integerOnly'=>true),
			array('unitario, descuento, iva, cantidad, total, cantidad_original, cantidad_recibida, cantidad_pendiente, subtotal_unitario, subtotal_iva, cantidad_salida, cantidad_por_salir', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_transferencia, id_producto, unitario, descuento, iva, cantidad, total, cantidad_original, cantidad_recibida, cantidad_pendiente, eliminado, subtotal_unitario, subtotal_iva, cantidad_salida, cantidad_por_salir', 'safe', 'on'=>'search'),
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
			'idTransferencia' => array(self::BELONGS_TO, 'Transferencias', 'id_transferencia'),
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_transferencia' => 'Transferencia',
			'id_producto' => 'Producto',
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
			'cantidad_salida' => 'Cantidad Salida',
			'cantidad_por_salir' => 'Cantidad Por Salir',
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
		$criteria->compare('id_transferencia',$this->id_transferencia);
		$criteria->compare('id_producto',$this->id_producto);
		$criteria->compare('unitario',$this->unitario);
		$criteria->compare('descuento',$this->descuento);
		$criteria->compare('iva',$this->iva);
		$criteria->compare('cantidad',$this->cantidad);
		$criteria->compare('total',$this->total);
		$criteria->compare('cantidad_original',$this->cantidad_original);
		$criteria->compare('cantidad_recibida',$this->cantidad_recibida);
		$criteria->compare('cantidad_pendiente',$this->cantidad_pendiente);
		$criteria->compare('eliminado',$this->eliminado);
		$criteria->compare('subtotal_unitario',$this->subtotal_unitario);
		$criteria->compare('subtotal_iva',$this->subtotal_iva);
		$criteria->compare('cantidad_salida',$this->cantidad_salida);
		$criteria->compare('cantidad_por_salir',$this->cantidad_por_salir);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TransferenciaDetalles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
