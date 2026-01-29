<?php

/**
 * This is the model class for table "grupo_recurrente_detalles".
 *
 * The followings are the available columns in table 'grupo_recurrente_detalles':
 * @property integer $id
 * @property integer $id_grupo
 * @property integer $id_grupo_relacion_detalle
 * @property string $nombre
 * @property string $comentarios
 * @property integer $valor
 * @property integer $eliminado
 * @property integer $restringir_eliminacion
 * @property integer $solicitar_ultimos_digitos
 * @property integer $solicitar_voucher
 * @property string $forma_pago_sat
 * @property double $comision_pos
 */
class GrupoRecurrenteDetalles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grupo_recurrente_detalles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_grupo, id_grupo_relacion_detalle, valor, eliminado, restringir_eliminacion, solicitar_ultimos_digitos, solicitar_voucher', 'numerical', 'integerOnly'=>true),
			array('comision_pos', 'numerical'),
			array('nombre, forma_pago_sat', 'length', 'max'=>255),
			array('comentarios', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_grupo, id_grupo_relacion_detalle, nombre, comentarios, valor, eliminado, restringir_eliminacion, solicitar_ultimos_digitos, solicitar_voucher, forma_pago_sat, comision_pos', 'safe', 'on'=>'search'),
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
			'id_grupo' => 'Id Grupo',
			'id_grupo_relacion_detalle' => 'Id Grupo Relacion Detalle',
			'nombre' => 'Nombre',
			'comentarios' => 'Comentarios',
			'valor' => 'Valor',
			'eliminado' => 'Eliminado',
			'restringir_eliminacion' => 'Restringir Eliminacion',
			'solicitar_ultimos_digitos' => 'Solicitar Ultimos Digitos',
			'solicitar_voucher' => 'Solicitar Voucher',
			'forma_pago_sat' => 'Forma Pago Sat',
			'comision_pos' => 'Comision Pos',
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
		$criteria->compare('id_grupo',$this->id_grupo);
		$criteria->compare('id_grupo_relacion_detalle',$this->id_grupo_relacion_detalle);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('comentarios',$this->comentarios,true);
		$criteria->compare('valor',$this->valor);
		$criteria->compare('eliminado',$this->eliminado);
		$criteria->compare('restringir_eliminacion',$this->restringir_eliminacion);
		$criteria->compare('solicitar_ultimos_digitos',$this->solicitar_ultimos_digitos);
		$criteria->compare('solicitar_voucher',$this->solicitar_voucher);
		$criteria->compare('forma_pago_sat',$this->forma_pago_sat,true);
		$criteria->compare('comision_pos',$this->comision_pos);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GrupoRecurrenteDetalles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
