<?php

/**
 * This is the model class for table "sucursales".
 *
 * The followings are the available columns in table 'sucursales':
 * @property integer $id
 * @property integer $id_distribuidor
 * @property string $nombre
 * @property string $rfc
 * @property string $direccion
 * @property integer $ciudad
 * @property integer $estado
 * @property integer $pais
 * @property string $codigo_postal
 * @property string $tel1
 * @property string $tel2
 * @property string $correo
 * @property string $web
 * @property string $email_smtp
 * @property integer $puerto_smtp
 * @property string $servidor_smtp
 * @property string $password_smtp
 * @property string $seguridad_smtp
 * @property integer $activar_smtp
 * @property integer $eliminado
 * @property integer $estatus
 * @property string $fecha_alta
 * @property integer $id_usuario
 * @property string $logotipo
 * @property string $ticket_texto
 * @property double $aviso_monto_retiro
 * @property double $minimo_caja
 * @property double $porcentaje_iva
 * @property string $correo_compras
 * @property integer $ver_en_web
 * @property string $datos_bancarios
 * @property string $facturacion_regimen_fiscal
 * @property integer $bodega_principal
 * @property string $formas_pagos_pos
 * @property string $latitud
 * @property string $longitud
 * @property string $horario
 * @property double $plazo_cancelar_ticket
 * @property integer $id_banco
 * @property string $colonia
 * @property string $nombre_fiscal
 * @property string $fiscal_direccion
 * @property string $fiscal_municipio
 * @property string $fiscal_entidad
 * @property string $fiscal_cp
 * @property double $cbm_capacidad
 */
class Sucursales extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sucursales';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_distribuidor, ciudad, estado, pais, puerto_smtp, activar_smtp, eliminado, estatus, id_usuario, ver_en_web, bodega_principal, id_banco', 'numerical', 'integerOnly'=>true),
			array('aviso_monto_retiro, minimo_caja, porcentaje_iva, plazo_cancelar_ticket, cbm_capacidad', 'numerical'),
			array('nombre, rfc, direccion, codigo_postal, tel1, tel2, correo, web, email_smtp, servidor_smtp, password_smtp, seguridad_smtp, logotipo, ticket_texto, correo_compras, facturacion_regimen_fiscal, latitud, longitud, colonia, nombre_fiscal, fiscal_direccion, fiscal_municipio, fiscal_entidad, fiscal_cp', 'length', 'max'=>255),
			array('horario', 'length', 'max'=>999),
			array('fecha_alta, datos_bancarios, formas_pagos_pos', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_distribuidor, nombre, rfc, direccion, ciudad, estado, pais, codigo_postal, tel1, tel2, correo, web, email_smtp, puerto_smtp, servidor_smtp, password_smtp, seguridad_smtp, activar_smtp, eliminado, estatus, fecha_alta, id_usuario, logotipo, ticket_texto, aviso_monto_retiro, minimo_caja, porcentaje_iva, correo_compras, ver_en_web, datos_bancarios, facturacion_regimen_fiscal, bodega_principal, formas_pagos_pos, latitud, longitud, horario, plazo_cancelar_ticket, id_banco, colonia, nombre_fiscal, fiscal_direccion, fiscal_municipio, fiscal_entidad, fiscal_cp, cbm_capacidad', 'safe', 'on'=>'search'),
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
			'idEntidad' => array(self::BELONGS_TO, 'Entidades', 'estado'),
			'idMunicipio' => array(self::BELONGS_TO, 'Municipios', 'ciudad'),
			'idRegimen' => array(self::BELONGS_TO, 'FactCRegimenfiscal','facturacion_regimen_fiscal'),
			'idBanco' => array(self::BELONGS_TO, 'GrupoRecurrenteDetalles','id_banco'),
			//
			'idEntidadfiscal' => array(self::BELONGS_TO, 'Entidades', 'fiscal_entidad'),
			'idMunicipiofiscal' => array(self::BELONGS_TO, 'Municipios', 'fiscal_municipio'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_distribuidor' => 'Id Distribuidor',
			'nombre' => 'Nombre',
			'rfc' => 'Rfc',
			'direccion' => 'Direccion',
			'ciudad' => 'Ciudad',
			'estado' => 'Estado',
			'pais' => 'Pais',
			'codigo_postal' => 'Codigo Postal',
			'tel1' => 'Tel1',
			'tel2' => 'Tel2',
			'correo' => 'Correo',
			'web' => 'Web',
			'email_smtp' => 'Email Smtp',
			'puerto_smtp' => 'Puerto Smtp',
			'servidor_smtp' => 'Servidor Smtp',
			'password_smtp' => 'Password Smtp',
			'seguridad_smtp' => 'Seguridad Smtp',
			'activar_smtp' => 'Activar Smtp',
			'eliminado' => 'Eliminado',
			'estatus' => 'Estatus',
			'fecha_alta' => 'Fecha Alta',
			'id_usuario' => 'Id Usuario',
			'logotipo' => 'Logotipo',
			'ticket_texto' => 'Ticket Texto',
			'aviso_monto_retiro' => 'Aviso Monto Retiro',
			'minimo_caja' => 'Minimo Caja',
			'porcentaje_iva' => 'Porcentaje Iva',
			'correo_compras' => 'Correo Compras',
			'ver_en_web' => 'Ver En Web',
			'datos_bancarios' => 'Datos Bancarios',
			'facturacion_regimen_fiscal' => 'Facturacion Regimen Fiscal',
			'bodega_principal' => 'Bodega Principal',
			'formas_pagos_pos' => 'Formas Pagos Pos',
			'latitud' => 'Latitud',
			'longitud' => 'Longitud',
			'horario' => 'Horario',
			'plazo_cancelar_ticket' => 'Plazo Cancelar Ticket',
			'id_banco' => 'Id Banco',
			'colonia' => 'Colonia',
			'nombre_fiscal' => 'Nombre Fiscal',
			'fiscal_direccion' => 'Fiscal Direccion',
			'fiscal_municipio' => 'Fiscal Municipio',
			'fiscal_entidad' => 'Fiscal Entidad',
			'fiscal_cp' => 'Fiscal Cp',
			'cbm_capacidad' => 'Cbm Capacidad',
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
		$criteria->compare('id_distribuidor',$this->id_distribuidor);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('rfc',$this->rfc,true);
		$criteria->compare('direccion',$this->direccion,true);
		$criteria->compare('ciudad',$this->ciudad);
		$criteria->compare('estado',$this->estado);
		$criteria->compare('pais',$this->pais);
		$criteria->compare('codigo_postal',$this->codigo_postal,true);
		$criteria->compare('tel1',$this->tel1,true);
		$criteria->compare('tel2',$this->tel2,true);
		$criteria->compare('correo',$this->correo,true);
		$criteria->compare('web',$this->web,true);
		$criteria->compare('email_smtp',$this->email_smtp,true);
		$criteria->compare('puerto_smtp',$this->puerto_smtp);
		$criteria->compare('servidor_smtp',$this->servidor_smtp,true);
		$criteria->compare('password_smtp',$this->password_smtp,true);
		$criteria->compare('seguridad_smtp',$this->seguridad_smtp,true);
		$criteria->compare('activar_smtp',$this->activar_smtp);
		$criteria->compare('eliminado',$this->eliminado);
		$criteria->compare('estatus',$this->estatus);
		$criteria->compare('fecha_alta',$this->fecha_alta,true);
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('logotipo',$this->logotipo,true);
		$criteria->compare('ticket_texto',$this->ticket_texto,true);
		$criteria->compare('aviso_monto_retiro',$this->aviso_monto_retiro);
		$criteria->compare('minimo_caja',$this->minimo_caja);
		$criteria->compare('porcentaje_iva',$this->porcentaje_iva);
		$criteria->compare('correo_compras',$this->correo_compras,true);
		$criteria->compare('ver_en_web',$this->ver_en_web);
		$criteria->compare('datos_bancarios',$this->datos_bancarios,true);
		$criteria->compare('facturacion_regimen_fiscal',$this->facturacion_regimen_fiscal,true);
		$criteria->compare('bodega_principal',$this->bodega_principal);
		$criteria->compare('formas_pagos_pos',$this->formas_pagos_pos,true);
		$criteria->compare('latitud',$this->latitud,true);
		$criteria->compare('longitud',$this->longitud,true);
		$criteria->compare('horario',$this->horario,true);
		$criteria->compare('plazo_cancelar_ticket',$this->plazo_cancelar_ticket);
		$criteria->compare('id_banco',$this->id_banco);
		$criteria->compare('colonia',$this->colonia,true);
		$criteria->compare('nombre_fiscal',$this->nombre_fiscal,true);
		$criteria->compare('fiscal_direccion',$this->fiscal_direccion,true);
		$criteria->compare('fiscal_municipio',$this->fiscal_municipio,true);
		$criteria->compare('fiscal_entidad',$this->fiscal_entidad,true);
		$criteria->compare('fiscal_cp',$this->fiscal_cp,true);
		$criteria->compare('cbm_capacidad',$this->cbm_capacidad);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sucursales the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
