<?php

/**
 * This is the model class for table "crm_oportunidades".
 *
 * The followings are the available columns in table 'crm_oportunidades':
 * @property integer $id
 * @property integer $id_cliente
 * @property integer $id_etapa
 * @property integer $id_usuario
 * @property string $nombre
 * @property string $estatus
 * @property double $valor_negocio
 * @property string $fecha_alta
 * @property string $fecha_tentativa_cierre
 * @property string $fecha_ultima_modificacion
 * @property integer $motivo_perdido
 * @property string $comentarios_perdido
 * @property integer $tipo_oportunidad
 */
class CrmOportunidades extends
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
		return 'crm_oportunidades';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_cliente','required'),
			array('id_cliente, id_usuario, id_etapa, motivo_perdido, tipo_oportunidad', 'numerical', 'integerOnly'=>true),
			array('valor_negocio', 'numerical'),
			array('nombre', 'length', 'max'=>255),
			array('estatus', 'length', 'max'=>11),
			array('comentarios_perdido', 'length', 'max'=>500),
			array('fecha_alta, fecha_tentativa_cierre, fecha_ultima_modificacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_cliente, id_etapa, nombre, estatus, valor_negocio, fecha_alta, fecha_tentativa_cierre, fecha_ultima_modificacion, motivo_perdido, comentarios_perdido, tipo_oportunidad', 'safe', 'on'=>'search'),
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
			'rl_clientes'=>array(self::BELONGS_TO, 'Clientes', 'id_cliente'),
			'rl_usuario'=>array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
			'rl_catalogo'=>array(self::BELONGS_TO, 'Crmetapas', 'id_etapa'),
			'rl_motivo'=>array(self::BELONGS_TO, 'CatalogosRecurrentes', 'motivo_perdido'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_cliente' => 'Cliente',
			'id_etapa'=> 'Etapa',
			'id_usuario' => 'Usuario',
			'nombre' => 'Nombre Oportunidad',
			'estatus' => 'Estatus',
			'valor_negocio' => 'Valor Negocio',
			'fecha_alta' => 'Fecha Alta',
			'fecha_tentativa_cierre' => 'Fecha Tentativa Cierre',
			'fecha_ultima_modificacion' => 'Fecha Ultima Modificacion',
			'motivo_perdido' => 'Motivo Perdido',
			'comentarios_perdido' => 'Comentarios Perdido',
			'tipo_oportunidad' => 'Tipo Oportunidad',
			
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
		$criteria->compare('id_cliente',$this->id_cliente);
		$criteria->compare('id_etapa',$this->id_etapa);
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('estatus',$this->estatus,true);
		$criteria->compare('valor_negocio',$this->valor_negocio);
		$criteria->compare('fecha_alta',$this->fecha_alta,true);
		$criteria->compare('fecha_tentativa_cierre',$this->fecha_tentativa_cierre,true);
		$criteria->compare('fecha_ultima_modificacion',$this->fecha_ultima_modificacion,true);
		$criteria->compare('motivo_perdido',$this->motivo_perdido);
		$criteria->compare('comentarios_perdido',$this->comentarios_perdido,true);
		$criteria->compare('tipo_oportunidad',$this->tipo_oportunidad);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CrmOportunidades the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
