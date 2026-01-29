<?php

/**
 * This is the model class for table "clientes".
 *
 * The followings are the available columns in table 'clientes':
 * @property integer $id_cliente
 * @property integer $id_usuario
 * @property integer $id_empresa
 * @property string $cliente_razonsocial
 * @property string $cliente_rfc
 * @property string $cliente_calle
 * @property string $cliente_colonia
 * @property string $cliente_numerointerior
 * @property string $cliente_numeroexterior
 * @property string $cliente_codigopostal
 * @property string $cliente_municipio
 * @property string $cliente_entidad
 * @property string $cliente_pais
 * @property string $cliente_nombre
 * @property string $cliente_email
 * @property string $cliente_telefono
 * @property integer $id_lista_precio
 * @property integer $cliente_tipo
 * @property string $cliente_logo
 * @property integer $cliente_tipo_clasificacion
 * @property integer $como_contacto
 * @property integer $cliente_como_trabajarlo
 * @property integer $pais
 */
class Clientes extends RActiveRecord
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
		return 'clientes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cliente_nombre,cliente_telefono,id_lista_precio', 'required'),
			array('id_usuario, id_empresa, id_lista_precio, cliente_tipo, cliente_tipo_clasificacion, cliente_como_trabajarlo,pais', 'numerical', 'integerOnly' => true),
			array('cliente_razonsocial, cliente_rfc, cliente_calle, cliente_colonia, cliente_numerointerior, cliente_numeroexterior, cliente_codigopostal, cliente_municipio, cliente_entidad, cliente_pais', 'length', 'max' => 50),
			array('cliente_nombre, como_contacto, cliente_email, cliente_telefono', 'length', 'max' => 9999),
			array('cliente_logo', 'length', 'max' => 500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_cliente, id_usuario, id_empresa, cliente_razonsocial, cliente_rfc, cliente_calle, cliente_colonia, cliente_numerointerior, cliente_numeroexterior, cliente_codigopostal, cliente_municipio, cliente_entidad, cliente_pais, cliente_nombre, cliente_email, cliente_telefono, id_lista_precio, cliente_tipo, cliente_logo, cliente_tipo_clasificacion, cliente_como_trabajarlo', 'safe', 'on' => 'search'),
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
			'rl_listaprecios' => array(self::BELONGS_TO, 'ListaPrecios', 'id_lista_precio'),
			'rl_usuarios' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
			'rl_cliente_tipo' => array(self::BELONGS_TO, 'CatalogosRecurrentes', 'cliente_tipo'),
			'rl_cliente_tipo_clasificacion' => array(self::BELONGS_TO, 'CatalogosRecurrentes', 'cliente_tipo_clasificacion'),
			'rl_cliente_como_trabajarlo' => array(self::BELONGS_TO, 'CatalogosRecurrentes', 'cliente_como_trabajarlo'),
			'rl_empresas' => array(self::BELONGS_TO, 'Empresas', 'id_empresa'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_cliente' => 'ID',
			'id_usuarios' => 'Usuarios',
			'id_empresa' => 'Empresa',
			'cliente_razonsocial' => 'Razón social',
			'cliente_rfc' => 'RFC',
			'cliente_calle' => 'Calle',
			'cliente_colonia' => 'Colonia',
			'cliente_numerointerior' => 'Número interior',
			'cliente_numeroexterior' => 'Número exterior',
			'cliente_codigopostal' => 'Código postal',
			'cliente_municipio' => 'Municipio',
			'cliente_entidad' => 'Entidad',
			'cliente_pais' => 'País',
			'cliente_nombre' => 'Nombre (cliente)',
			'cliente_email' => 'Email',
			'cliente_telefono' => 'Teléfono',
			'id_lista_precio' => 'Lista Precio',
			'cliente_tipo' => 'Giro de la empresa',
			'cliente_logo' => 'Cliente Logo',
			'cliente_tipo_clasificacion' => 'Cliente Tipo Clasificacion',
			'cliente_como_trabajarlo' => 'Cliente Como Trabajarlo',
			'como_contacto' => 'Como nos contacto',
			'pais' => 'Direccion de envio',

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

		$criteria->compare('id_cliente', $this->id_cliente);
		$criteria->compare('id_usuario', $this->id_usuario);
		$criteria->compare('id_empresa', $this->id_empresa);
		$criteria->compare('cliente_razonsocial', $this->cliente_razonsocial, true);
		$criteria->compare('cliente_rfc', $this->cliente_rfc, true);
		$criteria->compare('cliente_calle', $this->cliente_calle, true);
		$criteria->compare('cliente_colonia', $this->cliente_colonia, true);
		$criteria->compare('cliente_numerointerior', $this->cliente_numerointerior, true);
		$criteria->compare('cliente_numeroexterior', $this->cliente_numeroexterior, true);
		$criteria->compare('cliente_codigopostal', $this->cliente_codigopostal, true);
		$criteria->compare('cliente_municipio', $this->cliente_municipio, true);
		$criteria->compare('cliente_entidad', $this->cliente_entidad, true);
		$criteria->compare('cliente_pais', $this->cliente_pais, true);
		$criteria->compare('cliente_nombre', $this->cliente_nombre, true);
		$criteria->compare('cliente_email', $this->cliente_email, true);
		$criteria->compare('cliente_telefono', $this->cliente_telefono, true);
		$criteria->compare('id_lista_precio', $this->id_lista_precio);
		$criteria->compare('cliente_tipo', $this->cliente_tipo);
		$criteria->compare('cliente_logo', $this->cliente_logo, true);
		$criteria->compare('cliente_tipo_clasificacion', $this->cliente_tipo_clasificacion);
		$criteria->compare('cliente_como_trabajarlo', $this->cliente_como_trabajarlo);
		$criteria->compare('como_contacto', $this->como_contacto, true);

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
	 * @return Clientes the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
