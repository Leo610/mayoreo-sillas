<?php

/**
 * This is the model class for table "grupos_recurrentes".
 *
 * The followings are the available columns in table 'grupos_recurrentes':
 * @property integer $id_grupo_recurrente
 * @property string $nombre
 * @property string $comentarios
 */
class GruposRecurrentes extends
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
		return 'grupos_recurrentes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre', 'length', 'max'=>500),
			array('comentarios', 'length', 'max'=>900),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_grupo_recurrente, nombre, comentarios', 'safe', 'on'=>'search'),
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
			'id_grupo_recurrente' => 'Id Grupo Recurrente',
			'nombre' => 'Nombre',
			'comentarios' => 'Comentarios',
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

		$criteria->compare('id_grupo_recurrente',$this->id_grupo_recurrente);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('comentarios',$this->comentarios,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GruposRecurrentes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
