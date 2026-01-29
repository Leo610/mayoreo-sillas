<?php

/**
 * This is the model class for table "fact_c_regimenfiscal".
 *
 * The followings are the available columns in table 'fact_c_regimenfiscal':
 * @property string $c_RegimenFiscal
 * @property string $descripcion
 * @property string $fisica
 * @property string $moral
 * @property string $fecha_inicio_vigencia
 * @property string $fecha_fin_vigencia
 */
class FactCRegimenfiscal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fact_c_regimenfiscal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('c_RegimenFiscal', 'required'),
			array('c_RegimenFiscal, descripcion, fisica, moral, fecha_inicio_vigencia, fecha_fin_vigencia', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('c_RegimenFiscal, descripcion, fisica, moral, fecha_inicio_vigencia, fecha_fin_vigencia', 'safe', 'on'=>'search'),
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
			'c_RegimenFiscal' => 'C Regimen Fiscal',
			'descripcion' => 'Descripcion',
			'fisica' => 'Fisica',
			'moral' => 'Moral',
			'fecha_inicio_vigencia' => 'Fecha Inicio Vigencia',
			'fecha_fin_vigencia' => 'Fecha Fin Vigencia',
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

		$criteria->compare('c_RegimenFiscal',$this->c_RegimenFiscal,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('fisica',$this->fisica,true);
		$criteria->compare('moral',$this->moral,true);
		$criteria->compare('fecha_inicio_vigencia',$this->fecha_inicio_vigencia,true);
		$criteria->compare('fecha_fin_vigencia',$this->fecha_fin_vigencia,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FactCRegimenfiscal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
