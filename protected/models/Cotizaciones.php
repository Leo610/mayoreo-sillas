<?php

/**
 * This is the model class for table "cotizaciones".
 *
 * The followings are the available columns in table 'cotizaciones':
 * @property integer $id_cotizacion
 * @property integer $id_cliente
 * @property integer $id_usuario
 * @property integer $id_moneda
 * @property integer $id_tipo_proyecto
 * @property double $cotizacion_total
 * @property string $cotizacion_fecha_alta
 * @property integer $cotizacion_estatus
 * @property string $cotizacion_ultima_modificacion
 * @property double $cotizacion_descuentos
 * @property string $cotizacion_comentario
 * @property string $cotizacion_nombre
 * @property double $tipo_cambio
 * @property double $total_peso
 * @property string $numero_cotizacion
 * @property integer $id_oportunidad
 * @property string $condiciones_de_pago
 * @property string $tiempo_fabricacion
 * @property string $exclusiones
 * @property string $vigencia_propuesta
 * @property integer $habilitar_envio
 * @property integer $pedido
 * @property string $nombre_encargado
 */
class Cotizaciones extends RActiveRecord
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
		return 'cotizaciones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(' id_cliente,id_lista_precio', 'required'),
			array('id_cliente,pedido, id_usuario, id_lista_precio, id_tipo_proyecto, cotizacion_estatus, id_oportunidad, habilitar_envio', 'numerical', 'integerOnly' => true),
			array('cotizacion_total, cotizacion_descuentos, tipo_cambio,tipo_precio', 'numerical'),
			array('cotizacion_comentario, cotizacion_nombre, nombre_encargado,cotizacion_condiciones_generales', 'length', 'max' => 999),
			array('numero_cotizacion', 'length', 'max' => 50),
			array('condiciones_de_pago, tiempo_fabricacion, exclusiones, vigencia_propuesta', 'length', 'max' => 9999),
			array('cotizacion_fecha_alta, cotizacion_ultima_modificacion,tipo_precio', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_cotizacion, id_cliente, id_usuario, id_lista_precio, id_tipo_proyecto, cotizacion_total, cotizacion_fecha_alta, cotizacion_estatus, cotizacion_ultima_modificacion, cotizacion_descuentos, cotizacion_comentario, cotizacion_nombre, tipo_cambio, total_peso, numero_cotizacion, id_oportunidad, condiciones_de_pago, tiempo_fabricacion, exclusiones, vigencia_propuesta, habilitar_envio, nombre_encargado', 'safe', 'on' => 'search'),
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
			'rl_clientes' => array(self::BELONGS_TO, 'Clientes', 'id_cliente'),
			'rl_usuarios' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
			'rl_lista_precio' => array(self::BELONGS_TO, 'ListaPrecios', 'id_lista_precio'),
			'rl_tipo_precio' => array(self::BELONGS_TO, 'ListaPrecios', 'tipo_precio')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_cotizacion' => 'ID',
			'id_cliente' => ' Cliente',
			'id_usuario' => 'Usuario',
			'id_lista_precio' => 'Id Lista Precio',
			'id_tipo_proyecto' => 'Tipo de Cotización',
			'cotizacion_total' => ' Total',
			'cotizacion_fecha_alta' => ' Fecha Alta',
			'cotizacion_estatus' => ' Estatus',
			'cotizacion_ultima_modificacion' => ' Ultima Modificacion',
			'cotizacion_descuentos' => ' Descuentos',
			'cotizacion_comentario' => ' Comentario',
			'cotizacion_nombre' => ' Nombre',
			'tipo_cambio' => 'Tipo Cambio',
			'total_peso' => 'Total Peso',
			'numero_cotizacion' => 'Numero Cotizacion',
			'id_oportunidad' => ' Oportunidad',
			'condiciones_de_pago' => 'Flete cotizado',
			'tiempo_fabricacion' => 'Tiempo de Entrega',
			'exclusiones' => 'Exclusiones',
			'vigencia_propuesta' => 'Vigencia Propuesta',
			'habilitar_envio' => 'Habilitar Envio',
			'nombre_encargado' => 'Nombre Encargado',
			'tipo_precio' => 'Tipo de Precio',
			'pedido' => 'Pedido',
			'cotizacion_condiciones_generales' => 'Cotizacion condiciones generales'
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

		$criteria->compare('id_cotizacion', $this->id_cotizacion);
		$criteria->compare('id_cliente', $this->id_cliente);
		$criteria->compare('id_usuario', $this->id_usuario);
		$criteria->compare('id_lista_precio', $this->id_lista_precio);
		$criteria->compare('id_tipo_proyecto', $this->id_tipo_proyecto);
		$criteria->compare('cotizacion_total', $this->cotizacion_total);
		$criteria->compare('cotizacion_fecha_alta', $this->cotizacion_fecha_alta, true);
		$criteria->compare('cotizacion_estatus', $this->cotizacion_estatus);
		$criteria->compare('cotizacion_ultima_modificacion', $this->cotizacion_ultima_modificacion, true);
		$criteria->compare('cotizacion_descuentos', $this->cotizacion_descuentos);
		$criteria->compare('cotizacion_comentario', $this->cotizacion_comentario, true);
		$criteria->compare('cotizacion_nombre', $this->cotizacion_nombre, true);
		$criteria->compare('tipo_cambio', $this->tipo_cambio);
		$criteria->compare('total_peso', $this->total_peso);
		$criteria->compare('numero_cotizacion', $this->numero_cotizacion, true);
		$criteria->compare('id_oportunidad', $this->id_oportunidad);
		$criteria->compare('condiciones_de_pago', $this->condiciones_de_pago, true);
		$criteria->compare('tiempo_fabricacion', $this->tiempo_fabricacion, true);
		$criteria->compare('exclusiones', $this->exclusiones, true);
		$criteria->compare('vigencia_propuesta', $this->vigencia_propuesta, true);
		$criteria->compare('habilitar_envio', $this->habilitar_envio);
		$criteria->compare('nombre_encargado', $this->nombre_encargado, true);
		$criteria->compare('tipo_precio', $this->tipo_precio, true);
		$criteria->compare('cotizacion_condiciones_generales', $this->cotizacion_condiciones_generales, true);

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
	 * @return Cotizaciones the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}