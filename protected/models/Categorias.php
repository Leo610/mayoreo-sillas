<?php

/**
 * This is the model class for table "categorias".
 *
 * The followings are the available columns in table 'categorias':
 * @property integer $id
 * @property integer $id_usuario
 * @property integer $id_categoria_padre
 * @property string $nombre
 * @property string $seo
 * @property string $descripcion
 * @property string $imagen
 * @property string $descripcion_seo
 * @property integer $ver_en_web
 * @property integer $estatus
 * @property integer $eliminado
 * @property integer $orden
 * @property string $nombre_menu
 * @property string $fecha_alta
 * @property double $monto_monedero
 * @property string $archivo
 * @property string $video
 * @property string $banner
 * @property integer $id_cat_google
 * @property integer $tipo
 */
class Categorias extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'categorias';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_usuario, id_categoria_padre, ver_en_web, estatus, eliminado, orden, id_cat_google, tipo', 'numerical', 'integerOnly'=>true),
			array('monto_monedero', 'numerical'),
			array('nombre, imagen, descripcion_seo, nombre_menu, banner', 'length', 'max'=>255),
			array('seo, descripcion', 'length', 'max'=>999),
			array('archivo, video', 'length', 'max'=>500),
			array('fecha_alta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_usuario, id_categoria_padre, nombre, seo, descripcion, imagen, descripcion_seo, ver_en_web, estatus, eliminado, orden, nombre_menu, fecha_alta, monto_monedero, archivo, video, banner, id_cat_google, tipo', 'safe', 'on'=>'search'),
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
			'id_usuario' => 'Id Usuario',
			'id_categoria_padre' => 'Id Categoria Padre',
			'nombre' => 'Nombre',
			'seo' => 'Seo',
			'descripcion' => 'Descripcion',
			'imagen' => 'Imagen',
			'descripcion_seo' => 'Descripcion Seo',
			'ver_en_web' => 'Ver En Web',
			'estatus' => 'Estatus',
			'eliminado' => 'Eliminado',
			'orden' => 'Orden',
			'nombre_menu' => 'Nombre Menu',
			'fecha_alta' => 'Fecha Alta',
			'monto_monedero' => 'Monto Monedero',
			'archivo' => 'Archivo',
			'video' => 'Video',
			'banner' => 'Banner',
			'id_cat_google' => 'Id Cat Google',
			'tipo' => 'Tipo',
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
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('id_categoria_padre',$this->id_categoria_padre);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('seo',$this->seo,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('imagen',$this->imagen,true);
		$criteria->compare('descripcion_seo',$this->descripcion_seo,true);
		$criteria->compare('ver_en_web',$this->ver_en_web);
		$criteria->compare('estatus',$this->estatus);
		$criteria->compare('eliminado',$this->eliminado);
		$criteria->compare('orden',$this->orden);
		$criteria->compare('nombre_menu',$this->nombre_menu,true);
		$criteria->compare('fecha_alta',$this->fecha_alta,true);
		$criteria->compare('monto_monedero',$this->monto_monedero);
		$criteria->compare('archivo',$this->archivo,true);
		$criteria->compare('video',$this->video,true);
		$criteria->compare('banner',$this->banner,true);
		$criteria->compare('id_cat_google',$this->id_cat_google);
		$criteria->compare('tipo',$this->tipo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Categorias the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
