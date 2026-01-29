<?php

/**
 * This is the model class for table "proveedores".
 *
 * The followings are the available columns in table 'proveedores':
 * @property integer $id_proveedor
 * @property string $proveedor_razonsocial
 * @property string $proveedor_rfc
 * @property string $proveedor_calle
 * @property string $proveedor_colonia
 * @property string $proveedor_numerointerior
 * @property string $proveedor_numeroexterior
 * @property string $proveedor_codigopostal
 * @property string $proveedor_municipio
 * @property string $proveedor_entidad
 * @property string $proveedor_pais
 * @property string $proveedor_nombre
 * @property string $proveedor_email
 * @property string $proveedor_telefono
 * @property integer $proveedor_estatus
 * @property string $proveedor_categoria
 */
class Proveedores extends
 RActiveRecord{
	public function getDbConnection()
	{
	  return self::getAdvertDbConnection();
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'proveedores';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('proveedor_nombre,proveedor_email,proveedor_telefono','required'),
			array('proveedor_estatus', 'numerical', 'integerOnly'=>true),
			array('proveedor_razonsocial, proveedor_calle, proveedor_colonia', 'length', 'max'=>999),
			array('proveedor_rfc, proveedor_numerointerior, proveedor_numeroexterior, proveedor_codigopostal, proveedor_categoria', 'length', 'max'=>50),
			array('proveedor_municipio, proveedor_entidad, proveedor_pais', 'length', 'max'=>99),
			array('proveedor_nombre, proveedor_email, proveedor_telefono', 'length', 'max'=>9999),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_proveedor, proveedor_razonsocial, proveedor_rfc, proveedor_calle, proveedor_colonia, proveedor_numerointerior, proveedor_numeroexterior, proveedor_codigopostal, proveedor_municipio, proveedor_entidad, proveedor_pais, proveedor_nombre, proveedor_email, proveedor_telefono, proveedor_estatus, proveedor_categoria', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_proveedor' => 'ID',
			'proveedor_razonsocial' => 'Razón social',
			'proveedor_rfc' => 'RFC',
			'proveedor_calle' => 'Calle',
			'proveedor_colonia' => 'Colonia',
			'proveedor_numerointerior' => 'Número interior',
			'proveedor_numeroexterior' => 'Número exterior',
			'proveedor_codigopostal' => 'Código postal',
			'proveedor_municipio' => 'Municipio',
			'proveedor_entidad' => 'Entidad',
			'proveedor_pais' => 'País',
			'proveedor_nombre' => 'Nombre',
			'proveedor_email' => 'Email',
			'proveedor_telefono' => 'Teléfono',
			'proveedor_estatus' => 'Proveedor Estatus',
			'proveedor_categoria' => 'Proveedor Categoria',

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

		$criteria->compare('id_proveedor',$this->id_proveedor);
		$criteria->compare('proveedor_razonsocial',$this->proveedor_razonsocial,true);
		$criteria->compare('proveedor_rfc',$this->proveedor_rfc,true);
		$criteria->compare('proveedor_calle',$this->proveedor_calle,true);
		$criteria->compare('proveedor_colonia',$this->proveedor_colonia,true);
		$criteria->compare('proveedor_numerointerior',$this->proveedor_numerointerior,true);
		$criteria->compare('proveedor_numeroexterior',$this->proveedor_numeroexterior,true);
		$criteria->compare('proveedor_codigopostal',$this->proveedor_codigopostal,true);
		$criteria->compare('proveedor_municipio',$this->proveedor_municipio,true);
		$criteria->compare('proveedor_entidad',$this->proveedor_entidad,true);
		$criteria->compare('proveedor_pais',$this->proveedor_pais,true);
		$criteria->compare('proveedor_nombre',$this->proveedor_nombre,true);
		$criteria->compare('proveedor_email',$this->proveedor_email,true);
		$criteria->compare('proveedor_telefono',$this->proveedor_telefono,true);
		$criteria->compare('proveedor_estatus',$this->proveedor_estatus);
		$criteria->compare('proveedor_categoria',$this->proveedor_categoria,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Proveedores the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
