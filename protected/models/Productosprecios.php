<?php

/**
 * This is the model class for table "productosprecios".
 *
 * The followings are the available columns in table 'productosprecios':
 * @property integer $id_productosprecios
 * @property integer $id_producto
 * @property integer $id_lista_precio
 * @property double $precio
 * @property double $precio_ppp
 * @property double $precio_oferta
 * @property double $precio_cambio
 * @property double $precio_extra
 * @property double $costo
 */
class Productosprecios extends RActiveRecord
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
		return 'productosprecios';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_producto, id_lista_precio', 'numerical', 'integerOnly' => true),
			array('precio, precio_ppp, precio_oferta, precio_cambio, precio_extra,costo', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_productosprecios, id_producto, id_lista_precio,precio, precio_ppp, precio_oferta, precio_cambio, precio_extra,costo', 'safe', 'on' => 'search'),
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
			'id_productosprecios' => 'Id Productosprecios',
			'id_producto' => 'Id Producto',
			'id_lista_precio' => 'Id Lista Precio',
			'precio' => 'Precio',
			'precio_ppp' => 'Precio Ppp',
			'precio_oferta' => 'Precio Oferta',
			'precio_cambio' => 'Precio Cambio',
			'precio_extra' => 'Precio Extra',
			'costo' => 'Costo',
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

		$criteria->compare('id_productosprecios', $this->id_productosprecios);
		$criteria->compare('id_producto', $this->id_producto);
		$criteria->compare('id_lista_precio', $this->id_lista_precio);
		$criteria->compare('precio', $this->precio);
		$criteria->compare('precio_ppp', $this->precio_ppp);
		$criteria->compare('precio_oferta', $this->precio_oferta);
		$criteria->compare('precio_cambio', $this->precio_cambio);
		$criteria->compare('precio_extra', $this->precio_extra);
		$criteria->compare('costo', $this->costo);

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
	 * @return Productosprecios the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}