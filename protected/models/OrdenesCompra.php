<?php

/**
 * This is the model class for table "ordenes_compra".
 *
 * The followings are the available columns in table 'ordenes_compra':
 * @property integer $id
 * @property integer $id_sucursal
 * @property integer $id_proveedor
 * @property integer $id_usuario_crea
 * @property string $fecha_alta
 * @property integer $no_factura
 * @property integer $estatus
 * @property string $comentarios
 * @property integer $eliminado
 * @property double $total
 * @property double $iva
 * @property double $subtotal
 * @property double $total_pendiente
 * @property double $total_pagado
 * @property integer $tipo_oc
 * @property integer $id_usuario_solicita
 */
class OrdenesCompra extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ordenes_compra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_sucursal, id_proveedor, id_usuario_crea, no_factura, estatus, eliminado, tipo_oc, id_usuario_solicita', 'numerical', 'integerOnly' => true),
			array('total, iva, subtotal, total_pendiente, total_pagado', 'numerical'),
			array('comentarios', 'length', 'max' => 9999),
			array('fecha_alta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_sucursal, id_proveedor, id_usuario_crea, fecha_alta, no_factura, estatus, comentarios, eliminado, total, iva, subtotal, total_pendiente, total_pagado, tipo_oc, id_usuario_solicita', 'safe', 'on' => 'search'),
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
			'idUsuariocrea' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario_crea'),
			'idUsuariosolicita' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario_solicita'),
			'idProveedor' => array(self::BELONGS_TO, 'Proveedores', 'id_proveedor'),
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
			'id_proveedor' => 'Id Proveedor',
			'id_usuario_crea' => 'Id Usuario Crea',
			'fecha_alta' => 'Fecha Alta',
			'no_factura' => 'No Factura',
			'estatus' => 'Estatus',
			'comentarios' => 'Comentarios',
			'eliminado' => 'Eliminado',
			'total' => 'Total',
			'iva' => 'Iva',
			'subtotal' => 'Subtotal',
			'total_pendiente' => 'Total Pendiente',
			'total_pagado' => 'Total Pagado',
			'tipo_oc' => 'Tipo Oc',
			'id_usuario_solicita' => 'Id Usuario Solicita',
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
		$criteria->compare('id_sucursal', $this->id_sucursal);
		$criteria->compare('id_proveedor', $this->id_proveedor);
		$criteria->compare('id_usuario_crea', $this->id_usuario_crea);
		$criteria->compare('fecha_alta', $this->fecha_alta, true);
		$criteria->compare('no_factura', $this->no_factura);
		$criteria->compare('estatus', $this->estatus);
		$criteria->compare('comentarios', $this->comentarios, true);
		$criteria->compare('eliminado', $this->eliminado);
		$criteria->compare('total', $this->total);
		$criteria->compare('iva', $this->iva);
		$criteria->compare('subtotal', $this->subtotal);
		$criteria->compare('total_pendiente', $this->total_pendiente);
		$criteria->compare('total_pagado', $this->total_pagado);
		$criteria->compare('tipo_oc', $this->tipo_oc);
		$criteria->compare('id_usuario_solicita', $this->id_usuario_solicita);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		)
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrdenesCompra the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}