<?php

/**
 * This is the model class for table "proyectos_notificaciones".
 *
 * The followings are the available columns in table 'proyectos_notificaciones':
 * @property integer $id
 * @property integer $id_proyecto
 * @property string $titulo
 * @property string $mensaje
 * @property string $fecha_alta
 * @property integer $estatus
 * @property integer $id_usuario
 * @property integer $eliminado
 */
class ProyectosNotificaciones extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'proyectos_notificaciones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, id_proyecto, estatus, id_usuario, eliminado', 'numerical', 'integerOnly'=>true),
			array('titulo', 'length', 'max'=>150),
			array('mensaje', 'length', 'max'=>500),
			array('fecha_alta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_proyecto, titulo, mensaje, fecha_alta, estatus, id_usuario, eliminado', 'safe', 'on'=>'search'),
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
			'id_proyecto' => 'Id Proyecto',
			'titulo' => 'Titulo',
			'mensaje' => 'Mensaje',
			'fecha_alta' => 'Fecha Alta',
			'estatus' => 'Estatus',
			'id_usuario' => 'Id Usuario',
			'eliminado' => 'Eliminado',
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
		$criteria->compare('id_proyecto',$this->id_proyecto);
		$criteria->compare('titulo',$this->titulo,true);
		$criteria->compare('mensaje',$this->mensaje,true);
		$criteria->compare('fecha_alta',$this->fecha_alta,true);
		$criteria->compare('estatus',$this->estatus);
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('eliminado',$this->eliminado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProyectosNotificaciones the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * proceso para insertar en la tabla cuando se crea un proyecto
	 */
	public function notificacionnuevoproyecto($idproyecto){
		// obtenemos todos los usuarios
		$usuarios = Usuarios::model()->findAll(['condition' => 'eliminado = 0 ']);
		// insertamos
		foreach ($usuarios as $rows) {
			// code...
			$nuevanot = new ProyectosNotificaciones;
			$nuevanot->id_proyecto = $idproyecto;
			$nuevanot->titulo = 'Nuevo proyecto';
			$nuevanot->mensaje = 'Nuevo proyecto mensaje';
			$nuevanot->fecha_alta = date('Y-m-d H:i:s');
			$nuevanot->estatus = 0; // no a sonado
			$nuevanot->id_usuario = $rows['ID_Usuario'];
			$nuevanot->eliminado = 0;
			$nuevanot->save();			
		}
	}
}
