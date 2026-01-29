<?php

/**
 * This is the model class for table "contabilidadingresos".
 *
 * The followings are the available columns in table 'contabilidadingresos':
 * @property integer $id_contabilidad_ingresos
 * @property integer $id_formapago
 * @property integer $id_banco
 * @property integer $id_usuario
 * @property string $contabilidad_ingresos_identificador
 * @property double $contabilidad_ingresos_cantidad
 * @property string $contabilidad_ingresos_fechaalta
 * @property string $factura
 * @property integer $id_moneda
 */
class Contabilidadingresos extends RActiveRecord
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
		return 'contabilidadingresos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contabilidad_ingresos_cantidad,id_moneda', 'required'),
			array('id_formapago, id_banco, id_usuario, id_moneda,ingreso_confirmado,confirmado_usuario', 'numerical', 'integerOnly' => true),
			array('contabilidad_ingresos_cantidad', 'numerical'),
			array('contabilidad_ingresos_identificador', 'length', 'max' => 150),
			array('factura', 'length', 'max' => 9999),
			array('contabilidad_ingresos_fechaalta,confirmado_fecha', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('factura,id_contabilidad_ingresos, id_formapago, id_banco, id_usuario, contabilidad_ingresos_identificador, contabilidad_ingresos_cantidad, contabilidad_ingresos_fechaalta, id_moneda', 'safe', 'on' => 'search'),
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
			'rl_formasdepago' => array(self::BELONGS_TO, 'Formasdepago', 'id_formapago'),
			'rl_usuario' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
			'rl_banco' => array(self::BELONGS_TO, 'Bancos', 'id_banco'),
			'rl_moneda' => array(self::BELONGS_TO, 'Monedas', 'id_moneda'),
			'rl_usuario_confirma' => array(self::BELONGS_TO, 'Usuarios', 'confirmado_usuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_contabilidad_ingresos' => 'ID',
			'id_formapago' => 'Forma de Pago',
			'id_banco' => 'Banco',
			'id_usuario' => 'Usuario',
			'contabilidad_ingresos_identificador' => 'Ingresos Identificador',
			'contabilidad_ingresos_cantidad' => 'Ingresos Cantidad',
			'contabilidad_ingresos_fechaalta' => 'Ingresos Fechaalta',
			'id_moneda' => 'Moneda',
			'factura' => 'Factura',
			'ingreso_confirmado' => 'Ingreso confirmado'
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

		$criteria->compare('id_contabilidad_ingresos', $this->id_contabilidad_ingresos);
		$criteria->compare('id_formapago', $this->id_formapago);
		$criteria->compare('id_banco', $this->id_banco);
		$criteria->compare('id_usuario', $this->id_usuario);
		$criteria->compare('contabilidad_ingresos_identificador', $this->contabilidad_ingresos_identificador, true);
		$criteria->compare('contabilidad_ingresos_cantidad', $this->contabilidad_ingresos_cantidad);
		$criteria->compare('contabilidad_ingresos_fechaalta', $this->contabilidad_ingresos_fechaalta, true);
		$criteria->compare('id_moneda', $this->id_moneda);
		$criteria->compare('factura', $this->factura, true);

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
	 * @return Contabilidadingresos the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	// funcion para cancelar los ingresos cuyo proyecto haya sido cancelado lars 13/02/2024
	public function revisaringresos($id)
	{
		$ingreso = Contabilidadingresos::model()->find('id_contabilidad_ingresos = ' . $id);
		$texto = $ingreso['contabilidad_ingresos_identificador'];
		// como no tenemos columna del id proyecto lo sacamos del identificador
		if (preg_match('/\d+/', $texto, $coincidencias)) {
			$id_proyecto = $coincidencias[0];
		}
		// una vez que obtenemos el id del proyecto revsisamos si esta cancelado 
		$proyecto = Proyectos::model()->find('id_proyecto = ' . $id_proyecto);

		if ($proyecto['proyecto_estatus'] == 7) {
			return 1;
		} else {
			return 0;
		}
	}
}
