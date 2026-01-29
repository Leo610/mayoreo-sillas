<?php

/**
 * This is the model class for table "proyectosempleados".
 *
 * The followings are the available columns in table 'proyectosempleados':
 * @property integer $id_proyectos_empleados
 * @property integer $id_proyecto
 * @property integer $id_producto
 * @property integer $id_empleado
 * @property string $proyectos_empleados_fecha_alta
 * @property integer $proyectos_empleados_tipo_hora
 * @property double $proyectos_empleados_cantidad
 * @property double $proyectos_empleados_total
 * @property double $proyectos_empleados_totalpendiente
 * @property double $proyectos_empleados_totalpagado
 * @property string $proyectos_empleados_ultima_modif
 * @property double $proyectos_empleados_unitario
 * @property double $proyectos_empleados_factor
 * @property integer $abierto
 * @property integer $id_usuario
 * @property string $fecha_ultima_modif
 * @property integer $id_moneda
 * @property string $referencia
 */
class Proyectosempleados extends
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
		return 'proyectosempleados';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_proyecto, id_producto, id_empleado, proyectos_empleados_tipo_hora, abierto, id_usuario, id_moneda', 'numerical', 'integerOnly'=>true),
			array('proyectos_empleados_cantidad, proyectos_empleados_total, proyectos_empleados_totalpendiente, proyectos_empleados_totalpagado, proyectos_empleados_unitario, proyectos_empleados_factor', 'numerical'),
			array('referencia', 'length', 'max'=>400),
			array('proyectos_empleados_fecha_alta, proyectos_empleados_ultima_modif, fecha_ultima_modif', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_proyectos_empleados, id_proyecto, id_producto, id_empleado, proyectos_empleados_fecha_alta, proyectos_empleados_tipo_hora, proyectos_empleados_cantidad, proyectos_empleados_total, proyectos_empleados_totalpendiente, proyectos_empleados_totalpagado, proyectos_empleados_ultima_modif, proyectos_empleados_unitario, proyectos_empleados_factor, abierto, id_usuario, fecha_ultima_modif, id_moneda, referencia', 'safe', 'on'=>'search'),
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
			'rl_empleados'=>array(self::BELONGS_TO, 'Empleados', 'id_empleado'),
			'rl_moneda'=>array(self::BELONGS_TO, 'Monedas', 'id_moneda'),
			'rl_proyecto'=>array(self::BELONGS_TO, 'Proyectos', 'id_proyecto')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_proyectos_empleados' => 'Id Proyectos Empleados',
			'id_proyecto' => 'Id Proyecto',
			'id_producto' => 'Id Producto',
			'id_empleado' => 'Id Empleado',
			'proyectos_empleados_fecha_alta' => 'Proyectos Empleados Fecha Alta',
			'proyectos_empleados_tipo_hora' => 'Proyectos Empleados Tipo Hora',
			'proyectos_empleados_cantidad' => 'Proyectos Empleados Cantidad',
			'proyectos_empleados_total' => 'Proyectos Empleados Total',
			'proyectos_empleados_totalpendiente' => 'Proyectos Empleados Totalpendiente',
			'proyectos_empleados_totalpagado' => 'Proyectos Empleados Total Pagado',
			'proyectos_empleados_ultima_modif' => 'Proyectos Empleados Ultima Modif',
			'proyectos_empleados_unitario' => 'Proyectos Empleados Unitario',
			'proyectos_empleados_factor' => 'Proyectos Empleados Factor',
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

		$criteria->compare('id_proyectos_empleados',$this->id_proyectos_empleados);
		$criteria->compare('id_proyecto',$this->id_proyecto);
		$criteria->compare('id_producto',$this->id_producto);
		$criteria->compare('id_empleado',$this->id_empleado);
		$criteria->compare('proyectos_empleados_fecha_alta',$this->proyectos_empleados_fecha_alta,true);
		$criteria->compare('proyectos_empleados_tipo_hora',$this->proyectos_empleados_tipo_hora);
		$criteria->compare('proyectos_empleados_cantidad',$this->proyectos_empleados_cantidad);
		$criteria->compare('proyectos_empleados_total',$this->proyectos_empleados_total);
		$criteria->compare('proyectos_empleados_totalpendiente',$this->proyectos_empleados_totalpendiente);
		$criteria->compare('proyectos_empleados_totalpagado',$this->proyectos_empleados_totalpagado);
		$criteria->compare('proyectos_empleados_ultima_modif',$this->proyectos_empleados_ultima_modif,true);
		$criteria->compare('proyectos_empleados_unitario',$this->proyectos_empleados_unitario);
		$criteria->compare('proyectos_empleados_factor',$this->proyectos_empleados_factor);
		$criteria->compare('abierto',$this->abierto);
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('fecha_ultima_modif',$this->fecha_ultima_modif,true);
		$criteria->compare('id_moneda',$this->id_moneda);
		$criteria->compare('referencia',$this->referencia,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Proyectosempleados the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
