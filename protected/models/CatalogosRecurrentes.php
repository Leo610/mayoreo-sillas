<?php

/**
 * This is the model class for table "catalogos_recurrentes".
 *
 * The followings are the available columns in table 'catalogos_recurrentes':
 * @property integer $id_catalogo_recurrente
 * @property integer $id_grupo_recurrente
 * @property string $nombre
 * @property string $descripcion
 * @property integer $num
 */
class CatalogosRecurrentes extends RActiveRecord
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
		return 'catalogos_recurrentes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre', 'required'),
			array('id_grupo_recurrente, num', 'numerical', 'integerOnly' => true),
			array('nombre', 'length', 'max' => 500),
			array('descripcion', 'length', 'max' => 900),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_catalogo_recurrente, id_grupo_recurrente, nombre, descripcion, num', 'safe', 'on' => 'search'),
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
			'rl_usuarios' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_catalogo_recurrente' => 'Catalogo Recurrente',
			'id_grupo_recurrente' => 'Grupo Recurrente',
			'nombre' => 'Nombre',
			'descripcion' => 'Descripcion',
			'num' => 'Num',
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

		$criteria->compare('id_catalogo_recurrente', $this->id_catalogo_recurrente);
		$criteria->compare('id_grupo_recurrente', $this->id_grupo_recurrente);
		$criteria->compare('nombre', $this->nombre, true);
		$criteria->compare('descripcion', $this->descripcion, true);
		$criteria->compare('num', $this->num);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		)
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CatalogosRecurrentes the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}