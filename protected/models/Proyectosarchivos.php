<?php

/**
 * This is the model class for table "proyectosarchivos".
 *
 * The followings are the available columns in table 'proyectosarchivos':
 * @property integer $id_productos_archivos
 * @property integer $id_proyecto
 * @property string $proyectos_archivos_archivo
 * @property string $proyectos_archivos_nombre
 */
class Proyectosarchivos extends
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
		return 'proyectosarchivos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('proyectos_archivos_nombre,proyectos_archivos_archivo','required'),	
			array('id_proyecto', 'numerical', 'integerOnly'=>true),
			array('proyectos_archivos_archivo', 'length', 'max'=>200),
			array('proyectos_archivos_nombre', 'length', 'max'=>9999),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_productos_archivos, id_proyecto, proyectos_archivos_archivo, proyectos_archivos_nombre', 'safe', 'on'=>'search'),
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
			'id_productos_archivos' => 'Id Productos Archivos',
			'id_proyecto' => 'Id Proyecto',
			'proyectos_archivos_archivo' => 'Proyectos Archivos Archivo',
			'proyectos_archivos_nombre' => 'Proyectos Archivos Nombre',
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

		$criteria->compare('id_productos_archivos',$this->id_productos_archivos);
		$criteria->compare('id_proyecto',$this->id_proyecto);
		$criteria->compare('proyectos_archivos_archivo',$this->proyectos_archivos_archivo,true);
		$criteria->compare('proyectos_archivos_nombre',$this->proyectos_archivos_nombre,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Proyectosarchivos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
