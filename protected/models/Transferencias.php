<?php

/**
 * This is the model class for table "transferencias".
 *
 * The followings are the available columns in table 'transferencias':
 * @property integer $id
 * @property integer $id_sucursal_destino
 * @property integer $id_sucursal_origen
 * @property integer $id_usuario_crea
 * @property string $fecha_solicitud
 * @property integer $estatus
 * @property integer $eliminado
 * @property integer $tipo
 * @property integer $id_usuario_solicita
 * @property string $comentarios
 * @property double $subtotal
 * @property double $iva
 * @property double $total
 */
class Transferencias extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transferencias';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_sucursal_destino, id_sucursal_origen, id_usuario_solicita, tipo', 'required'),
			array('id_sucursal_destino, id_sucursal_origen, id_usuario_crea, estatus, eliminado, tipo, id_usuario_solicita', 'numerical', 'integerOnly'=>true),
			array('subtotal, iva, total', 'numerical'),
			array('comentarios', 'length', 'max'=>999),
			array('fecha_solicitud', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_sucursal_destino, id_sucursal_origen, id_usuario_crea, fecha_solicitud, estatus, eliminado, tipo, id_usuario_solicita, comentarios, subtotal, iva, total', 'safe', 'on'=>'search'),
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
			'idSucursalorigen' => array(self::BELONGS_TO, 'Sucursales', 'id_sucursal_origen'),
			'idSucursaldestino' => array(self::BELONGS_TO, 'Sucursales', 'id_sucursal_destino'),
			'idUsuariocrea' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario_crea'),
			'idUsuariosolicita' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario_solicita'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_sucursal_destino' => 'Sucursal Destino',
			'id_sucursal_origen' => 'Sucursal Origen',
			'id_usuario_crea' => 'Usuario Crea',
			'fecha_solicitud' => 'Fecha Solicitud',
			'estatus' => 'Estatus',
			'eliminado' => 'Eliminado',
			'tipo' => 'Tipo',
			'id_usuario_solicita' => 'Usuario Solicita',
			'comentarios' => 'Comentarios',
			'subtotal' => 'Subtotal',
			'iva' => 'Iva',
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
		$criteria->compare('id_sucursal_destino',$this->id_sucursal_destino);
		$criteria->compare('id_sucursal_origen',$this->id_sucursal_origen);
		$criteria->compare('id_usuario_crea',$this->id_usuario_crea);
		$criteria->compare('fecha_solicitud',$this->fecha_solicitud,true);
		$criteria->compare('estatus',$this->estatus);
		$criteria->compare('eliminado',$this->eliminado);
		$criteria->compare('tipo',$this->tipo);
		$criteria->compare('id_usuario_solicita',$this->id_usuario_solicita);
		$criteria->compare('comentarios',$this->comentarios,true);
		$criteria->compare('subtotal',$this->subtotal);
		$criteria->compare('iva',$this->iva);
		$criteria->compare('total',$this->total);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Transferencias the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
