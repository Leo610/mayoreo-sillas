<?php

/**
 * This is the model class for table "proyectosproductos".
 *
 * The followings are the available columns in table 'proyectosproductos':
 * @property integer $id_proyectos_productos
 * @property integer $id_proyecto
 * @property integer $id_producto
 * @property string $proyectos_productos_descripcion
 * @property double $proyectos_productos_cantidad
 * @property integer $id_almacen
 * @property double $proyectos_productos_factor
 * @property integer $abierto
 * @property double $proyectos_productos_cantidad_surtida
 * @property string $color
 * @property integer $tipo_producto
 * @property integer $estatus
 * @property integer $salio_stock
 * @property integer $cantidad_salida
 * @property integer $id_etapa
 * @property string $fecha_de_entrega
 * @property string $fecha_alta
 * @property double $precio_venta_producto
 */
class Proyectosproductos extends RActiveRecord
{
	public function getDbConnection()
	{
		return self::getAdvertDbConnection();
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'proyectosproductos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_proyecto, id_producto, id_almacen, abierto', 'numerical', 'integerOnly' => true),
			array('proyectos_productos_cantidad,precio_venta_producto, proyectos_productos_factor, id_etapa,proyectos_productos_cantidad_surtida,tipo_producto,salio_stock,cantidad_salida,estatus', 'numerical'),
			array('proyectos_productos_descripcion,color,color_tapiceria,especificaciones_extras', 'length', 'max' => 9999),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_proyectos_productos, id_proyecto, id_producto, proyectos_productos_descripcion, proyectos_productos_cantidad, id_almacen, proyectos_productos_factor, abierto, proyectos_productos_cantidad_surtida,fecha_de_entrega,fecha_alta', 'safe', 'on' => 'search'),
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
			'rl_producto' => array(self::BELONGS_TO, 'Productos', 'id_producto'),
			'rl_almacen' => array(self::BELONGS_TO, 'Almacenes', 'id_almacen'),
			'rl_proyecto' => array(self::BELONGS_TO, 'Proyectos', 'id_proyecto'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_proyectos_productos' => 'ID',
			'id_proyecto' => 'Proyecto',
			'id_producto' => ' Producto',
			'proyectos_productos_descripcion' => 'Descripcion',
			'proyectos_productos_cantidad' => 'Cantidad',
			'id_almacen' => 'Almacen',
			'tipo_prodcuto' => 'Tipo de Producto',
			'salio_stock' => 'salida de stock',
			'cantidad_salida' => 'Cantidad de salida',
			'proyectos_productos_factor' => 'Factor',
			'abierto' => 'Abierto',
			'proyectos_productos_cantidad_surtida' => ' Cantidad Surtida',
			'color' => 'Color',
			'estatus' => 'Estatus',
			'id_etapa' => 'Etapa',
			'fecha_de_entrega' => 'Fecha de Entrega',
			'fecha_alta' => 'Fecha de Alta',
			'color_tapiceria' => 'Color Tapicería',
			'especificaciones_extras' => 'Especificaciones Extras',
			'precio_venta_producto' => 'Precio en elq ue se vendio el producto'

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

		$criteria->compare('id_proyectos_productos', $this->id_proyectos_productos);
		$criteria->compare('id_proyecto', $this->id_proyecto);
		$criteria->compare('id_producto', $this->id_producto);
		$criteria->compare('proyectos_productos_descripcion', $this->proyectos_productos_descripcion, true);
		$criteria->compare('proyectos_productos_cantidad', $this->proyectos_productos_cantidad);
		$criteria->compare('id_almacen', $this->id_almacen);
		$criteria->compare('tipo_producto', $this->tipo_producto);
		$criteria->compare('salio_stock', $this->salio_stock);
		$criteria->compare('cantidad_salida', $this->cantidad_salida);
		$criteria->compare('proyectos_productos_factor', $this->proyectos_productos_factor);
		$criteria->compare('abierto', $this->abierto);
		$criteria->compare('proyectos_productos_cantidad_surtida', $this->proyectos_productos_cantidad_surtida);
		$criteria->compare('color', $this->color, true);
		$criteria->compare('estatus', $this->estatus);
		$criteria->compare('id_etapa', $this->id_etapa);
		$criteria->compare('fecha_de_entrega', $this->fecha_de_entrega, true);
		$criteria->compare('fecha_alta', $this->fecha_alta, true);
		$criteria->compare('color_tapiceria', $this->color_tapiceria, true);
		$criteria->compare('especificaciones_extras', $this->especificaciones_extras, true);
		$criteria->compare('precio_venta_producto', $this->precio_venta_producto);

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
	 * @return Proyectosproductos the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}