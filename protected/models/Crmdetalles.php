<?php

/**
 * This is the model class for table "crmdetalles".
 *
 * The followings are the available columns in table 'crmdetalles':
 * @property integer $id_crm_detalle
 * @property integer $id_oportunidad
 * @property integer $id_cliente
 * @property integer $id_crm_acciones
 * @property string $crm_detalles_comentarios
 * @property string $crm_detalles_fecha_alta
 * @property integer $crm_detalles_usuario_alta
 * @property integer $crm_detalles_estatus
 * @property string $crm_detalle_ultima_modificacion
 * @property string $crm_detalles_fecha
  * @property string $estatus
 * @property string $fecha_realizado
 * @property integer $id_usuario_realizado
 * @property string $comentario_realizado
 */
class Crmdetalles extends
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
		return 'crmdetalles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_cliente,id_crm_acciones,crm_detalles_fecha,id_oportunidad', 'required'),
			array('id_cliente, id_crm_acciones, crm_detalles_usuario_alta, crm_detalles_estatus, id_oportunidad, id_usuario_realizado', 'numerical', 'integerOnly'=>true),
			array('crm_detalles_comentarios', 'length', 'max'=>9999),
			array('estatus', 'length', 'max'=>12),
			array('comentario_realizado', 'length', 'max'=>999),
			array('crm_detalles_fecha_alta, crm_detalle_ultima_modificacion, crm_detalles_fecha, fecha_realizado', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_crm_detalle, id_cliente, id_crm_acciones, crm_detalles_comentarios, crm_detalles_fecha_alta, crm_detalles_usuario_alta, crm_detalles_estatus, crm_detalle_ultima_modificacion, crm_detalles_fecha, id_oportunidad, estatus, fecha_realizado, id_usuario_realizado, comentario_realizado', 'safe', 'on'=>'search'),
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
			'rl_cliente'=>array(self::BELONGS_TO, 'Clientes', 'id_cliente'),
			'rl_crmaccion'=>array(self::BELONGS_TO, 'Crmacciones', 'id_crm_acciones'),
			'rl_usuarios'=>array(self::BELONGS_TO, 'Usuarios', 'crm_detalles_usuario_alta'),
			'rl_oportunidad'=>array(self::BELONGS_TO, 'CrmOportunidades', 'id_oportunidad'),
			'rl_usuario_realizo'=>array(self::BELONGS_TO, 'Usuarios', 'id_usuario_realizado'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_crm_detalle' => 'ID',
			'id_oportunidad'=>'Oportunidad',
			'id_cliente' => 'Cliente',
			'id_crm_acciones' => 'Acción',
			'crm_detalles_comentarios' => 'Comentarios',
			'crm_detalles_fecha_alta' => 'Fecha Alta',
			'crm_detalles_usuario_alta' => 'Usuario Alta',
			'crm_detalles_estatus' => 'Estatus',
			'crm_detalle_ultima_modificacion' => 'Ultima Modificacion',
			'crm_detalles_fecha' => 'Fecha',
			'estatus' => 'Estatus',
			'fecha_realizado' => 'Fecha Realizado',
			'id_usuario_realizado' => 'Id Usuario Realizado',
			'comentario_realizado' => 'Comentario Realizado',
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

		$criteria->compare('id_crm_detalle',$this->id_crm_detalle);
		$criteria->compare('id_oportunidad',$this->id_oportunidad);
		$criteria->compare('id_cliente',$this->id_cliente);
		$criteria->compare('id_crm_acciones',$this->id_crm_acciones);
		$criteria->compare('crm_detalles_comentarios',$this->crm_detalles_comentarios,true);
		$criteria->compare('crm_detalles_fecha_alta',$this->crm_detalles_fecha_alta,true);
		$criteria->compare('crm_detalles_usuario_alta',$this->crm_detalles_usuario_alta);
		$criteria->compare('crm_detalles_estatus',$this->crm_detalles_estatus);
		$criteria->compare('crm_detalle_ultima_modificacion',$this->crm_detalle_ultima_modificacion,true);
		$criteria->compare('crm_detalles_fecha',$this->crm_detalles_fecha,true);
		$criteria->compare('estatus',$this->estatus,true);
		$criteria->compare('fecha_realizado',$this->fecha_realizado,true);
		$criteria->compare('id_usuario_realizado',$this->id_usuario_realizado);
		$criteria->compare('comentario_realizado',$this->comentario_realizado,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Crmdetalles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
