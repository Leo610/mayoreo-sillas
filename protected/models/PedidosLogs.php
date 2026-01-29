<?php

/**
 * This is the model class for table "pedidos_logs".
 *
 * The followings are the available columns in table 'pedidos_logs':
 * @property integer $id_pedidolog
 * @property integer $id_pedido
 * @property integer $id_usuario
 * @property string $movimiento
 * @property string $descripcion
 * @property string $fecha_alta
 * @property integer $eliminado
 *
 * The followings are the available model relations:
 * @property Pedidos $rl_pedido
 * @property Usuarios $rl_usuario
 */
class PedidosLogs extends RActiveRecord
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
        return 'pedidos_logs';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('id_pedido, id_usuario, fecha_alta', 'required'),
            array('id_pedido, id_usuario, eliminado', 'numerical', 'integerOnly' => true),
            array('movimiento, descripcion', 'length', 'max' => 255),
            array('movimiento, descripcion', 'safe'),

            // For search
            array('id_pedidolog, id_pedido, id_usuario, movimiento, descripcion, fecha_alta, eliminado', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'rl_pedido' => array(self::BELONGS_TO, 'Pedidos', 'id_pedido'),
            'rl_usuario' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_pedidolog' => 'ID',
            'id_pedido' => 'Pedido',
            'id_usuario' => 'Usuario',
            'movimiento' => 'Movimiento',
            'descripcion' => 'Descripción',
            'fecha_alta' => 'Fecha de alta',
            'eliminado' => 'Eliminado',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id_pedidolog', $this->id_pedidolog);
        $criteria->compare('id_pedido', $this->id_pedido);
        $criteria->compare('id_usuario', $this->id_usuario);
        $criteria->compare('movimiento', $this->movimiento, true);
        $criteria->compare('descripcion', $this->descripcion, true);
        $criteria->compare('fecha_alta', $this->fecha_alta, true);
        $criteria->compare('eliminado', $this->eliminado);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @param string $className
     * @return PedidosLogs the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
