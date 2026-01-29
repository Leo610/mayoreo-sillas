<?php

/**
 * This is the model class for table "productos".
 *
 * The followings are the available columns in table 'productos':
 * @property integer $id_producto
 * @property integer $id_unidades_medida
 * @property integer $id_categoria
 * @property string $producto_nombre
 * @property string $producto_clave
 * @property double $producto_costo_compra
 * @property double $producto_precio_venta_default
 * @property integer $producto_estatus
 * @property string $producto_tiempo_elaboracion
 * @property string $producto_descripcion
 * @property string $producto_imagen
 * @property integer $id_categoria
 * @property integer $id_subcategoria
 * @property integer $id_grupo
 * @property integer $id_subgrupo
 * @property integer $id_linea
 * @property integer $id_sublinea
 * @property integer $id_segmento
 * @property integer $id_subsegmento
 * @property integer $id_marca
 * @property integer $tipo
 * @property integer $id_submarca
 * @property integer $id_modelo
 * @property integer $id_submodelo
 * @property integer $id_posicion
 * @property integer $id_subposicion
 * @property integer $id_proveedor
 * @property string $descripcion_larga
 * @property integer $id_bodega_fabricacion
 */
class Productos extends RActiveRecord
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
		return 'productos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			# array('producto_clave', 'unique', 'message' => "La clave debe ser unica"),

			array('producto_nombre,producto_clave', 'required'),
			array('utilidad,id_unidades_medida, producto_estatus, id_categoria, id_subcategoria, id_grupo, id_subgrupo, id_linea, id_sublinea, id_segmento, id_subsegmento, id_marca, id_submarca, id_modelo, id_submodelo, id_posicion, id_subposicion, id_proveedor,id_bodega_fabricacion,tipo', 'numerical', 'integerOnly' => true),

			array('producto_nombre, producto_descripcion', 'length', 'max' => 9999),
			array('producto_clave, producto_imagen', 'length', 'max' => 50),
			array('descripcion_larga', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_producto, id_unidades_medida, id_categoria, producto_nombre, producto_clave, producto_estatus, producto_descripcion, producto_imagenid_categoria, id_subcategoria, id_grupo, id_subgrupo, id_linea, id_sublinea, id_segmento, id_subsegmento, id_marca, id_submarca, id_modelo, id_submodelo, id_posicion, id_subposicion, id_proveedor, descripcion_larga', 'safe', 'on' => 'search'),
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
			'rl_unidadesdemedida' => array(self::BELONGS_TO, 'Unidadesdemedida', 'id_unidades_medida'),
			'rl_categoria' => array(self::BELONGS_TO, 'CatalogosRecurrentes', 'id_categoria'),
			'rl_subcategoria' => array(self::BELONGS_TO, 'CatalogosRecurrentes', 'id_subcategoria'),
			'rl_subcategoria' => array(self::BELONGS_TO, 'CatalogosRecurrentes', 'id_subcategoria'),

			'rl_cotizacionesproducto' => array(self::BELONGS_TO, 'CotizacionesProductos', 'id_producto'),
			'rl_catalogosrecurrentes' => array(self::BELONGS_TO, 'CatalogosRecurrentes', 'id_bodega_fabricacion'),


		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(

			'id_producto' => 'ID',
			'id_unidades_medida' => 'Unidad de Medida',
			'id_categoria' => 'Categoria',
			'producto_nombre' => 'Nombre',
			'producto_clave' => 'Clave',
			'producto_estatus' => 'Estatus',
			'producto_descripcion' => ' Descripción',
			'producto_imagen' => 'Producto Imagen',
			'id_categoria' => 'Categoria',
			'id_subcategoria' => 'Subcategoria',
			'id_grupo' => 'Grupo',
			'id_subgrupo' => 'Subgrupo',
			'tipo' => 'Tipo',
			'id_linea' => 'Linea',
			'id_sublinea' => 'Sublinea',
			'id_segmento' => 'Segmento',
			'id_subsegmento' => 'Subsegmento',
			'id_marca' => 'Marca',
			'id_submarca' => 'Submarca',
			'id_modelo' => 'Modelo',
			'id_submodelo' => 'Submodelo',
			'id_posicion' => 'Posicion',
			'id_subposicion' => 'Subposicion',
			'id_proveedor' => 'Proveedor',
			'descripcion_larga' => 'Descripcion Larga',
			'id_bodega_fabricacion' => 'Bodega de Fabricación',
			'utilidad' => 'Utilidad'

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

		$criteria->compare('id_producto', $this->id_producto);
		$criteria->compare('id_unidades_medida', $this->id_unidades_medida);
		$criteria->compare('id_categoria', $this->id_categoria);
		$criteria->compare('producto_nombre', $this->producto_nombre, true);
		$criteria->compare('producto_clave', $this->producto_clave, true);
		$criteria->compare('producto_estatus', $this->producto_estatus);
		$criteria->compare('producto_descripcion', $this->producto_descripcion, true);
		$criteria->compare('producto_imagen', $this->producto_imagen, true);
		$criteria->compare('id_categoria', $this->id_categoria);
		$criteria->compare('id_subcategoria', $this->id_subcategoria);
		$criteria->compare('id_grupo', $this->id_grupo);
		$criteria->compare('tipo', $this->tipo);
		$criteria->compare('id_subgrupo', $this->id_subgrupo);
		$criteria->compare('id_linea', $this->id_linea);
		$criteria->compare('id_sublinea', $this->id_sublinea);
		$criteria->compare('id_segmento', $this->id_segmento);
		$criteria->compare('id_subsegmento', $this->id_subsegmento);
		$criteria->compare('id_marca', $this->id_marca);
		$criteria->compare('id_submarca', $this->id_submarca);
		$criteria->compare('id_modelo', $this->id_modelo);
		$criteria->compare('id_submodelo', $this->id_submodelo);
		$criteria->compare('id_posicion', $this->id_posicion);
		$criteria->compare('id_subposicion', $this->id_subposicion);
		$criteria->compare('id_proveedor', $this->id_proveedor);
		$criteria->compare('descripcion_larga', $this->descripcion_larga, true);
		$criteria->compare('id_bodega_fabricacion', $this->id_bodega_fabricacion);

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
	 * @return Productos the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
