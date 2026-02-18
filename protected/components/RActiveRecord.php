<?php
class RActiveRecord extends CActiveRecord
{
    public static function getAdvertDbConnection()
    {
        return Yii::app()->db;
    }
}