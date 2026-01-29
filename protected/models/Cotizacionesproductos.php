<?php

/**
 * This is the model class for table "cotizacionesproductos".
 *
 * The followings are the available columns in table 'cotizacionesproductos':
 * @property integer $id_cotizacion_producto
 * @property integer $id_cotizacion
 * @property integer $id_producto
 * @property integer $
 * @property double $cotizacion_producto_cantidad
 * @property double $cotizacion_producto_unitario
 * @property double $cotizacion_producto_total
 * @property string $cotizacion_producto_nombre
 * @property string $cotizacion_producto_descripcion
 * @property integer $id_cliente
 * @property integer $id_usuario
 * @property string $color
 * @property string $tipo_descuetno
 * @property integer $descuento
 * @property string $color_tapiceria
 * @property string $especificaciones_extras
 */
class Cotizacionesproductos extends RActiveRecord
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
		return 'cotizacionesproductos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_producto, cotizacion_producto_cantidad, id_cliente, id_usuario', 'required'),
			array('id_cotizacion, id_producto, id_cliente, id_usuario', 'numerical', 'integerOnly' => true),
			array('descuento,cotizacion_producto_cantidad, cotizacion_producto_unitario, cotizacion_producto_total', 'numerical'),
			array('tipo_descuetno,color,cotizacion_producto_nombre, cotizacion_producto_descripcion,color_tapiceria,especificaciones_extras', 'length', 'max' => 9999),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_cotizacion_producto, id_cotizacion, id_producto, cotizacion_producto_cantidad, cotizacion_producto_unitario, cotizacion_producto_total, cotizacion_producto_nombre, cotizacion_producto_descripcion, id_cliente, id_usuario', 'safe', 'on' => 'search'),
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
			'rl_unidadesdemedida' => array(self::BELONGS_TO, 'Unidadesdemedida', 'id_unidades_medida')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_cotizacion_producto' => 'ID',
			'id_cotizacion' => 'Cotizacion',
			'id_producto' => 'ID',
			'cotizacion_producto_cantidad' => 'Cotizacion Producto Cantidad',
			'cotizacion_producto_unitario' => 'Cotizacion Producto Unitario',
			'cotizacion_producto_total' => 'Cotizacion Producto Total',
			'cotizacion_producto_nombre' => 'Cotizacion Producto Nombre',
			'cotizacion_producto_descripcion' => 'Cotizacion Producto Descripcion',
			'id_cliente' => 'Id Cliente',
			'id_usuario' => 'Id Usuario',
			'color' => 'Color de Estructura',
			'tipo_descuetno' => 'Tipo Descuento',
			'descuento' => 'Descuento',
			'color_tapiceria' => 'color Tapicería',
			'especificaciones_extras' => 'Especificaciones Extras'
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

		$criteria->compare('id_cotizacion_producto', $this->id_cotizacion_producto);
		$criteria->compare('id_cotizacion', $this->id_cotizacion);
		$criteria->compare('id_producto', $this->id_producto);
		$criteria->compare('cotizacion_producto_cantidad', $this->cotizacion_producto_cantidad);
		$criteria->compare('cotizacion_producto_unitario', $this->cotizacion_producto_unitario);
		$criteria->compare('cotizacion_producto_total', $this->cotizacion_producto_total);
		$criteria->compare('cotizacion_producto_nombre', $this->cotizacion_producto_nombre, true);
		$criteria->compare('cotizacion_producto_descripcion', $this->cotizacion_producto_descripcion, true);
		$criteria->compare('id_cliente', $this->id_cliente);
		$criteria->compare('id_usuario', $this->id_usuario);
		$criteria->compare('color', $this->color, true);
		$criteria->compare('tipo_descuetno', $this->tipo_descuetno, true);
		$criteria->compare('color_tapiceria', $this->color_tapiceria, true);
		$criteria->compare('especificaciones_extras', $this->especificaciones_extras, true);
		$criteria->compare('descuento', $this->descuento);

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
	 * @return Cotizacionesproductos the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}