<?php

/**
 * This is the model class for table "contabilidadegresos".
 *
 * The followings are the available columns in table 'contabilidadegresos':
 * @property integer $id_contabilidad_egresos
 * @property integer $id_formapago
 * @property integer $id_banco
 * @property integer $id_usuario
 * @property string $contabilidad_egresos_identificador
 * @property double $contabilidad_egresos_cantidad
 * @property string $contabilidad_egresos_fechaalta
 * @property integer $id_moneda
 */
class Contabilidadegresos extends RActiveRecord{
	public function getDbConnection()
	{
	  return self::getAdvertDbConnection();
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contabilidadegresos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contabilidad_egresos_cantidad,id_moneda', 'required'),
			array('id_formapago,id_banco,contabilidad_egresos_cantidad,contabilidad_egresos_identificador', 'required'),
			array('id_formapago, id_banco, id_usuario, id_moneda', 'numerical', 'integerOnly'=>true),
			array('contabilidad_egresos_cantidad', 'numerical'),
			array('contabilidad_egresos_identificador', 'length', 'max'=>150),
			array('contabilidad_egresos_fechaalta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_contabilidad_egresos, id_formapago, id_banco, id_usuario, contabilidad_egresos_identificador, contabilidad_egresos_cantidad, contabilidad_egresos_fechaalta, id_moneda', 'safe', 'on'=>'search'),
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
			'rl_usuario'=>array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
			'rl_banco'=>array(self::BELONGS_TO, 'Bancos', 'id_banco'),
			'rl_formasdepago'=>array(self::BELONGS_TO, 'Formasdepago', 'id_formapago'),
			'rl_moneda'=>array(self::BELONGS_TO, 'Monedas', 'id_moneda'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_contabilidad_egresos' => 'ID',
			'id_formapago' => 'Forma de pago',
			'id_banco' => 'Banco',
			'id_usuario' => 'Usuario',
			'contabilidad_egresos_identificador' => 'Identificador',
			'contabilidad_egresos_cantidad' => 'Cantidad',
			'contabilidad_egresos_fechaalta' => 'Fecha alta',
			'id_moneda'=>'Moneda'
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

		$criteria->compare('id_contabilidad_egresos',$this->id_contabilidad_egresos);
		$criteria->compare('id_formapago',$this->id_formapago);
		$criteria->compare('id_banco',$this->id_banco);
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('contabilidad_egresos_identificador',$this->contabilidad_egresos_identificador,true);
		$criteria->compare('contabilidad_egresos_cantidad',$this->contabilidad_egresos_cantidad);
		$criteria->compare('contabilidad_egresos_fechaalta',$this->contabilidad_egresos_fechaalta,true);
		$criteria->compare('id_moneda',$this->id_moneda);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Contabilidadegresos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
