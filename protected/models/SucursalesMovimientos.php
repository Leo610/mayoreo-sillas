<?php

/**
 * This is the model class for table "sucursales_movimientos".
 *
 * The followings are the available columns in table 'sucursales_movimientos':
 * @property integer $id
 * @property integer $id_sucursal
 * @property integer $id_producto
 * @property integer $tipo
 * @property integer $tipo_identificador
 * @property integer $id_identificador
 * @property integer $id_partida
 * @property double $cantidad_stock_antes
 * @property double $cantidad_mov
 * @property double $cantidad_stock_final
 * @property string $fecha_movimiento
 * @property integer $id_usuario
 * @property integer $eliminado
 * @property string $comentarios
 * @property string $folio_rsi
 * @property integer $id_rsi
 */
class SucursalesMovimientos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sucursales_movimientos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_sucursal, id_producto, tipo, tipo_identificador, id_identificador, id_partida, id_usuario, eliminado, id_rsi', 'numerical', 'integerOnly'=>true),
			array('cantidad_stock_antes, cantidad_mov, cantidad_stock_final', 'numerical'),
			array('comentarios', 'length', 'max'=>999),
			array('folio_rsi', 'length', 'max'=>255),
			array('fecha_movimiento', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_sucursal, id_producto, tipo, tipo_identificador, id_identificador, id_partida, cantidad_stock_antes, cantidad_mov, cantidad_stock_final, fecha_movimiento, id_usuario, eliminado, comentarios, folio_rsi, id_rsi', 'safe', 'on'=>'search'),
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
			'idSucursal' => array(self::BELONGS_TO, 'Sucursales', 'id_sucursal'),
			'idUsuario' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
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
			'tipo' => 'Tipo',
			'tipo_identificador' => 'Tipo Identificador',
			'id_identificador' => 'Id Identificador',
			'id_partida' => 'Id Partida',
			'cantidad_stock_antes' => 'Cantidad Stock Antes',
			'cantidad_mov' => 'Cantidad Mov',
			'cantidad_stock_final' => 'Cantidad Stock Final',
			'fecha_movimiento' => 'Fecha Movimiento',
			'id_usuario' => 'Id Usuario',
			'eliminado' => 'Eliminado',
			'comentarios' => 'Comentarios',
			'folio_rsi' => 'Folio Rsi',
			'id_rsi' => 'Id Rsi',
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
		$criteria->compare('tipo',$this->tipo);
		$criteria->compare('tipo_identificador',$this->tipo_identificador);
		$criteria->compare('id_identificador',$this->id_identificador);
		$criteria->compare('id_partida',$this->id_partida);
		$criteria->compare('cantidad_stock_antes',$this->cantidad_stock_antes);
		$criteria->compare('cantidad_mov',$this->cantidad_mov);
		$criteria->compare('cantidad_stock_final',$this->cantidad_stock_final);
		$criteria->compare('fecha_movimiento',$this->fecha_movimiento,true);
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('eliminado',$this->eliminado);
		$criteria->compare('comentarios',$this->comentarios,true);
		$criteria->compare('folio_rsi',$this->folio_rsi,true);
		$criteria->compare('id_rsi',$this->id_rsi);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SucursalesMovimientos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
