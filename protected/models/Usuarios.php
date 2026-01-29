<?php

/**
 * This is the model class for table "usuarios".
 *
 * The followings are the available columns in table 'usuarios':
 * @property integer $ID_Usuario
 * @property integer $id_empleado
 * @property string $Usuario_Nombre
 * @property string $Usuario_Email
 * @property string $Usuario_Password
 * @property integer $level
 * @property integer $id_almacen
 * @property integer $id_perfil
 * @property integer $equipo_venta
 * @property integer $ubicacion
 * @property integer $mercado
 * @property integer $zona
 * @property integer $id_usuario_padre
 * @property string $bodega
 * @property integer $tel
 */
class Usuarios extends RActiveRecord
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
		return 'usuarios';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Usuario_Nombre,Usuario_Email,Usuario_Password,id_perfil', 'required'),
			array('Usuario_Email', 'unique', 'message' => "El Usuario Email debe ser unico", 'on' => 'insert'),
			array('level, id_perfil, equipo_venta, ubicacion, mercado, zona, id_usuario_padre', 'numerical', 'integerOnly' => true),
			array('Usuario_Nombre, Usuario_Email, Usuario_Password,bodega,tel', 'length', 'max' => 9999),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ID_Usuario, Usuario_Nombre, Usuario_Email, Usuario_Password, level, id_perfil, equipo_venta, ubicacion, mercado, zona, id_usuario_padre', 'safe', 'on' => 'search'),
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
			'rl_usuario' => array(self::BELONGS_TO, 'Agente', 'id_usuario'),
			'rl_cliente' => array(self::BELONGS_TO, 'Clientes', 'id_cliente'),
			'rl_perfil' => array(self::BELONGS_TO, 'Perfiles', 'id_perfil'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID_Usuario' => ' Usuario',
			'Usuario_Nombre' => 'Usuario ',
			'Usuario_Email' => 'Email',
			'Usuario_Password' => 'Password',
			'level' => 'Level',
			'id_perfil' => 'Perfil',
			'equipo_venta' => 'Equipo Venta',
			'ubicacion' => 'Ubicacion',
			'mercado' => 'Mercado',
			'zona' => 'Zona',
			'id_usuario_padre' => 'Usuario Padre',
			'bodega' => 'Bodega',
			'tel' => 'Telefono/Celular'
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

		$criteria->compare('ID_Usuario', $this->ID_Usuario);
		$criteria->compare('Usuario_Nombre', $this->Usuario_Nombre, true);
		$criteria->compare('Usuario_Email', $this->Usuario_Email, true);
		$criteria->compare('Usuario_Password', $this->Usuario_Password, true);
		$criteria->compare('level', $this->level);
		$criteria->compare('id_perfil', $this->id_perfil);
		$criteria->compare('equipo_venta', $this->equipo_venta);
		$criteria->compare('ubicacion', $this->ubicacion);
		$criteria->compare('mercado', $this->mercado);
		$criteria->compare('zona', $this->zona);
		$criteria->compare('tel', $this->tel);
		$criteria->compare('id_usuario_padre', $this->id_usuario_padre);
		$criteria->compare('bodega', $this->bodega, true);

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
	 * @return Usuarios the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}