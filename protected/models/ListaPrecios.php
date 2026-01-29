<?php

/**
 * This is the model class for table "listaprecios".
 *
 * The followings are the available columns in table 'listaprecios':
 * @property integer $id_lista_precio
 * @property string $listaprecio_nombre
 * @property string $listaprecio_fecha_alta
 * @property integer $listaprecio_estatus
 * @property integer $default
 * @property integer $id_moneda
 */
class ListaPrecios extends
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
		return 'listaprecios';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('listaprecio_estatus, default, id_moneda', 'numerical', 'integerOnly'=>true),
			array('listaprecio_nombre', 'length', 'max'=>9999),
			array('listaprecio_fecha_alta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_lista_precio, listaprecio_nombre, listaprecio_fecha_alta, listaprecio_estatus, default, id_moneda', 'safe', 'on'=>'search'),
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
			'rl_moneda'=>array(self::BELONGS_TO, 'Monedas', 'id_moneda'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_lista_precio' => 'Id Lista Precio',
			'listaprecio_nombre' => 'Listaprecio Nombre',
			'listaprecio_fecha_alta' => 'Listaprecio Fecha Alta',
			'listaprecio_estatus' => 'Listaprecio Estatus',
			'default' => 'Default',
			'id_moneda' => 'Id Moneda',
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

		$criteria->compare('id_lista_precio',$this->id_lista_precio);
		$criteria->compare('listaprecio_nombre',$this->listaprecio_nombre,true);
		$criteria->compare('listaprecio_fecha_alta',$this->listaprecio_fecha_alta,true);
		$criteria->compare('listaprecio_estatus',$this->listaprecio_estatus);
		$criteria->compare('default',$this->default);
		$criteria->compare('id_moneda',$this->id_moneda);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Listaprecios the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
