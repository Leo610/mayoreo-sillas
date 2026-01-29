<?php

/**
 * This is the model class for table "tipo_proyectos".
 *
 * The followings are the available columns in table 'tipo_proyectos':
 * @property integer $id_tipo_proyecto
 * @property string $nombre
 * @property string $serie_cotizacion
 * @property string $sere_proyecto
 * @property string $fecha_alta
 */
class TipoProyectos extends
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
		return 'tipo_proyectos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('serie_cotizacion,sere_proyecto, nombre','required'),
			array('nombre', 'length', 'max'=>350),
			array('serie_cotizacion, sere_proyecto', 'length', 'max'=>50),
			array('fecha_alta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_tipo_proyecto, nombre, serie_cotizacion, sere_proyecto, fecha_alta', 'safe', 'on'=>'search'),
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
			'id_tipo_proyecto' => 'Id Tipo Proyecto',
			'nombre' => 'Nombre',
			'serie_cotizacion' => 'Serie Cotizacion',
			'sere_proyecto' => 'Sere Proyecto',
			'fecha_alta' => 'Fecha Alta',
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

		$criteria->compare('id_tipo_proyecto',$this->id_tipo_proyecto);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('serie_cotizacion',$this->serie_cotizacion,true);
		$criteria->compare('sere_proyecto',$this->sere_proyecto,true);
		$criteria->compare('fecha_alta',$this->fecha_alta,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TipoProyectos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
