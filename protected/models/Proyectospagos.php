<?php

/**
 * This is the model class for table "proyectospagos".
 *
 * The followings are the available columns in table 'proyectospagos':
 * @property integer $id_proyecto_pago
 * @property integer $id_proyecto
 * @property integer $id_formapago
 * @property integer $id_usuario
 * @property integer $id_banco
 * @property double $proyectopago_cantidad
 * @property string $proyectopago_fecha
 * @property string $proyectopago_fechaalta
 */
class Proyectospagos extends
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
		return 'proyectospagos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_proyecto, id_formapago, id_usuario, id_banco', 'numerical', 'integerOnly'=>true),
			array('proyectopago_cantidad', 'numerical'),
			array('proyectopago_fecha, proyectopago_fechaalta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_proyecto_pago, id_proyecto, id_formapago, id_usuario, id_banco, proyectopago_cantidad, proyectopago_fecha, proyectopago_fechaalta', 'safe', 'on'=>'search'),
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
			'id_proyecto_pago' => 'Id Proyecto Pago',
			'id_proyecto' => 'Id Proyecto',
			'id_formapago' => 'Id Formapago',
			'id_usuario' => 'Id Usuario',
			'id_banco' => 'Id Banco',
			'proyectopago_cantidad' => 'Proyectopago Cantidad',
			'proyectopago_fecha' => 'Proyectopago Fecha',
			'proyectopago_fechaalta' => 'Proyectopago Fechaalta',
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

		$criteria->compare('id_proyecto_pago',$this->id_proyecto_pago);
		$criteria->compare('id_proyecto',$this->id_proyecto);
		$criteria->compare('id_formapago',$this->id_formapago);
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('id_banco',$this->id_banco);
		$criteria->compare('proyectopago_cantidad',$this->proyectopago_cantidad);
		$criteria->compare('proyectopago_fecha',$this->proyectopago_fecha,true);
		$criteria->compare('proyectopago_fechaalta',$this->proyectopago_fechaalta,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Proyectospagos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
