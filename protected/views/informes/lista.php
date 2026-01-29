<?php
/* @var $this ContabilidadegresosController */
$this->pageTitle='Lista de informes';

$this->breadcrumbs=array(
    'Informes',
);
?>
<div class="row">

    <div class="col-md-12">
    	<p class="lead">Reportes de la aplicación</p>
    	<div class="clearfix"></div>
    	<a href="<?=Yii::app()->createurl('informes/Oportunidades')?>" class="btn btn-success"> Reporte de Oportunidades</a>
    	<a href="<?=Yii::app()->createurl('informes/ventas')?>" class="btn btn-success"> Reporte de Ventas</a>
    	<a href="<?=Yii::app()->createurl('informes/ingresos')?>" class="btn btn-success"> Reporte de Ingresos</a>
    	<a href="<?=Yii::app()->createurl('informes/cuentasporcobrar')?>" class="btn btn-success"> Reporte de Cuentas por cobrar</a>

    </div>
</div>