<?php

/**
 * This is the model class for table "Proyectos".
 *
 * The followings are the available columns in table 'Proyectos':
 * @property integer $id_proyecto
 * @property integer $id_cliente
 * @property integer $id_cotizacion
 * @property integer $id_tipo_proyecto
 * @property string $numero_proyecto
 * @property double $proyecto_total
 * @property double $proyecto_totalpagado
 * @property double $proyecto_totalpendiente
 * @property integer $proyecto_estatus
 * @property string $proyecto_duracion
 * @property string $proyecto_fecha_alta
 * @property string $proyecto_ultima_modificacion
 * @property string $proyecto_comentarios
 * @property string $proyecto_facturado
 * @property string $proyecto_supervisor
 * @property integer $id_usuario
 * @property string $proyecto_descripcion_general
 * @property string $proyecto_descripcion_espesifica
 * @property string $proyecto_condiciones_generales
 * @property string $proyecto_nombre
 * @property string $localidad
 * @property integer $id_moneda
 * @property integer $id_almacen
 * @property double $tipo_cambio
 * @property double $total_peso
 */
class Proyectos extends RActiveRecord
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
		return 'proyectos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_cliente, id_cotizacion, id_tipo_proyecto, proyecto_estatus, id_usuario, id_moneda, id_almacen', 'numerical', 'integerOnly' => true),
			array('proyecto_total, proyecto_totalpagado, proyecto_totalpendiente, tipo_cambio, total_peso', 'numerical'),
			array('numero_proyecto', 'length', 'max' => 50),
			array('proyecto_duracion, proyecto_comentarios, proyecto_facturado, proyecto_supervisor,localidad', 'length', 'max' => 9999),
			array('proyecto_descripcion_general, proyecto_descripcion_espesifica, proyecto_condiciones_generales, proyecto_nombre', 'length', 'max' => 999),
			array('proyecto_fecha_alta, proyecto_ultima_modificacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_proyecto, id_cliente, id_cotizacion, id_tipo_proyecto, numero_proyecto, proyecto_total, proyecto_totalpagado, proyecto_totalpendiente, proyecto_estatus, proyecto_duracion, proyecto_fecha_alta, proyecto_ultima_modificacion, proyecto_comentarios, proyecto_facturado, proyecto_supervisor, id_usuario, proyecto_descripcion_general, proyecto_descripcion_espesifica, proyecto_condiciones_generales, proyecto_nombre, id_moneda, id_almacen, tipo_cambio, total_peso', 'safe', 'on' => 'search'),
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
			'rl_clientes' => array(self::BELONGS_TO, 'Clientes', 'id_cliente'),
			'rl_usuarios' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
			'rl_supervisor' => array(self::BELONGS_TO, 'Empleados', 'proyecto_supervisor'),
			'rl_moneda' => array(self::BELONGS_TO, 'Monedas', 'id_moneda'),
			'rl_almacen' => array(self::BELONGS_TO, 'Almacenes', 'id_almacen'),
			'rl_unidad_medida' => array(self::BELONGS_TO, 'Unidadesdemedida', 'id_unidades_medida'),
			'rl_cotizacionesproducto' => array(self::BELONGS_TO, 'CotizacionesProductos', 'id_cotizacion'),
			'rl_cotizaciones' => array(self::BELONGS_TO, 'Cotizaciones', 'id_cotizacion'),
			'proyectos_productos' => array(self::HAS_MANY, 'Proyectosproductos', 'id_proyecto'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(

			'id_proyecto' => ' Proyecto',
			'id_cliente' => ' Cliente',
			'id_cotizacion' => ' Cotizacion',
			'id_tipo_proyecto' => 'Tipo Proyecto',
			'numero_proyecto' => 'Numero Proyecto',
			'proyecto_total' => 'Proyecto Total',
			'proyecto_totalpendiente' => 'Proyecto Totalpendiente',
			'proyecto_estatus' => 'Proyecto Estatus',
			'proyecto_duracion' => 'Proyecto Duracion',
			'proyecto_fecha_alta' => 'Proyecto Fecha Alta',
			'proyecto_ultima_modificacion' => 'Proyecto Ultima Modificacion',
			'proyecto_comentarios' => 'Proyecto Comentarios',
			'proyecto_facturado' => 'Proyecto Facturado',
			'proyecto_totalpagado' => 'Proyecto Totalpagado',
			'proyecto_supervisor' => 'Proyecto Supervisor',
			'id_usuario' => 'Id Usuario',
			'proyecto_descripcion_general' => 'Proyecto Descripcion General',
			'proyecto_descripcion_espesifica' => 'Proyecto Descripcion Espesifica',
			'proyecto_condiciones_generales' => 'Proyecto Condiciones Generales',
			'proyecto_nombre' => 'Proyecto Nombre',
			'localidad' => 'Localidad'
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

		$criteria->compare('id_proyecto', $this->id_proyecto);
		$criteria->compare('id_cliente', $this->id_cliente);
		$criteria->compare('id_cotizacion', $this->id_cotizacion);
		$criteria->compare('id_tipo_proyecto', $this->id_tipo_proyecto);
		$criteria->compare('numero_proyecto', $this->numero_proyecto, true);
		$criteria->compare('localidad', $this->localidad, true);
		$criteria->compare('proyecto_total', $this->proyecto_total);
		$criteria->compare('proyecto_totalpagado', $this->proyecto_totalpagado);
		$criteria->compare('proyecto_totalpendiente', $this->proyecto_totalpendiente);
		$criteria->compare('proyecto_estatus', $this->proyecto_estatus);
		$criteria->compare('proyecto_duracion', $this->proyecto_duracion, true);
		$criteria->compare('proyecto_fecha_alta', $this->proyecto_fecha_alta, true);
		$criteria->compare('proyecto_ultima_modificacion', $this->proyecto_ultima_modificacion, true);
		$criteria->compare('proyecto_comentarios', $this->proyecto_comentarios, true);
		$criteria->compare('proyecto_facturado', $this->proyecto_facturado, true);
		$criteria->compare('proyecto_supervisor', $this->proyecto_supervisor, true);
		$criteria->compare('id_usuario', $this->id_usuario);
		$criteria->compare('proyecto_descripcion_general', $this->proyecto_descripcion_general, true);
		$criteria->compare('proyecto_descripcion_espesifica', $this->proyecto_descripcion_espesifica, true);
		$criteria->compare('proyecto_condiciones_generales', $this->proyecto_condiciones_generales, true);
		$criteria->compare('proyecto_nombre', $this->proyecto_nombre, true);
		$criteria->compare('id_moneda', $this->id_moneda);
		$criteria->compare('id_almacen', $this->id_almacen);
		$criteria->compare('tipo_cambio', $this->tipo_cambio);
		$criteria->compare('total_peso', $this->total_peso);

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
	 * @return Proyectos the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * funcion para confirmar que todos los ingresos de un proyecto estan confirmados
	 * dvb 23 11 2023
	 */
	public function ingresosConfirmados($idproyecto)
	{

		$ingresos = Contabilidadingresos::model()->findAll(
			array(
				'condition' => 'contabilidad_ingresos_identificador =  :id',
				'params' => array(':id' => 'Pedido - ' . $idproyecto . '')
			)
		);
		$ingresosconfirmados = 1;
		if (empty($ingresos)) {
			$ingresosconfirmados = 0;
		}
		foreach ($ingresos as $rows) {
			if ($rows['ingreso_confirmado'] == 0) {
				$ingresosconfirmados = 0;
				break;
			}
		}

		return $ingresosconfirmados;
	}

	// funcion para poner en rojo y restar si un proyecto se encuetnra cancelado lars 13/02/2023
	public function Revisar($id, $tipo)
	{
		$proyectos = Proyectos::model()->findByPk($id);

		if ($proyectos->proyecto_estatus == 7) {

			if ($tipo == 'total') {

				$total = -$proyectos->proyecto_total;
			} else
				if ($tipo == 'pagado') {
				$total = -$proyectos->proyecto_totalpagado;
			} else
					if ($tipo == 'pendiente') {
				$total = -$proyectos->proyecto_totalpendiente;
			}
			return $total;
		} else {
			if ($tipo == 'total') {
				$total = $proyectos->proyecto_total;
			} else
				if ($tipo == 'pagado') {
				$total = $proyectos->proyecto_totalpagado;
			} else
					if ($tipo == 'pendiente') {
				$total = $proyectos->proyecto_totalpendiente;
			}
			return $total;
		}
	}

	public function obtenerLog($id)
	{
		$log = PedidosLogs::model()->find(
			array(
				'condition' => 'eliminado = 0 and id_pedido =  :id',
				'params' => array(':id' => $id),
				'order' => 'fecha_alta DESC',
			)
		);
		if (empty($log)) {
			$logs = 'No cuenta con cambios registrados';
		} else {
			$logs = '<b>Fecha:</b> ' . $log['fecha_alta'] . '<br> <b>Usuario:</b> ' . $log['rl_usuario']['Usuario_Nombre'] . '<br> <b>Detalle:</b> ' . $log['descripcion'];
		}
		return $logs;
	}

	public function inserLogs($params)
	{

		$model = new PedidosLogs();


		$model->id_pedido = $params['id_pedido'];
		$model->id_usuario = Yii::app()->user->id;
		$model->movimiento = 'Actualizacion Estatus';
		$model->descripcion = $params['actualstatus'] . ' -> ' . $params['newstatus'];
		$model->fecha_alta = date('Y-m-d H:i:s');
		$model->eliminado = 0;

		if ($model->save()) {
			return true;
		} else {
			return false;
		}
	}
}
