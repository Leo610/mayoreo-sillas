<?php

/**
 * This is the model class for table "ordenes_compra_log".
 *
 * The followings are the available columns in table 'ordenes_compra_log':
 * @property integer $id
 * @property integer $id_orden_compra
 * @property integer $estatus_anterior
 * @property integer $estatus_final
 * @property string $comentarios
 * @property integer $id_usuario
 * @property string $fecha_alta
 * @property double $total
 */
class OrdenesCompraLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ordenes_compra_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_orden_compra, estatus_anterior, estatus_final, id_usuario', 'numerical', 'integerOnly'=>true),
			array('total', 'numerical'),
			array('comentarios', 'length', 'max'=>9999),
			array('fecha_alta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_orden_compra, estatus_anterior, estatus_final, comentarios, id_usuario, fecha_alta, total', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'id_orden_compra' => 'Id Orden Compra',
			'estatus_anterior' => 'Estatus Anterior',
			'estatus_final' => 'Estatus Final',
			'comentarios' => 'Comentarios',
			'id_usuario' => 'Id Usuario',
			'fecha_alta' => 'Fecha Alta',
			'total' => 'Total',
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
		$criteria->compare('id_orden_compra',$this->id_orden_compra);
		$criteria->compare('estatus_anterior',$this->estatus_anterior);
		$criteria->compare('estatus_final',$this->estatus_final);
		$criteria->compare('comentarios',$this->comentarios,true);
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('fecha_alta',$this->fecha_alta,true);
		$criteria->compare('total',$this->total);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrdenesCompraLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
