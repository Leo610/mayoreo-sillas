<?php
$this->pageTitle='Certificados '.$registro['nombre'];
$this->pageDescription = '';
$this->breadcrumbs=array(
    'Lista sucursales'=>yii::app()->createUrl('sucursales/index'),
    $registro['nombre']=>yii::app()->createUrl('sucursales/detalle/'.$registro['id']),
	$this->pageTitle
	);

$op_menu_sucursal = $registro->id; // es el id de la sucursal a editar o a agregar cosas
$op_menu = 2;
include 'menu.php';
$this->renderpartial('//inventario/menu',array('op_menu'=>13));
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="panel">
                <h1>Certificado para facturación</h1>
                <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'agregarregistroform',
                'action'=>Yii::app()->createUrl('sucursales/agregarcertificado'),
                'enableClientValidation'=>true,
                'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    ),
                    )); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <p class="note">Llene los campos a continuación para dar de alta el certificado</p>
                            <?php echo $form->errorSummary($certificadoactivo); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $form->labelEx($certificadoactivo,'certificado_cer'); ?>
                            <?php echo $form->filefield($certificadoactivo,'certificado_cer',array('class'=>'form-control')); ?>
                            <?php echo $form->error($certificadoactivo,'certificado_cer'); ?>
                            <?= ($certificadoactivo['certificado_cer']=='')?'':'<span class="text-success">'.$certificadoactivo['certificado_cer'].'</span>'?>
                        </div>
                        <div class="col-md-12">
                            <?php echo $form->labelEx($certificadoactivo,'certificado_key'); ?>
                            <?php echo $form->filefield($certificadoactivo,'certificado_key',array('class'=>'form-control')); ?>
                            <?php echo $form->error($certificadoactivo,'certificado_key'); ?>
                            <?= ($certificadoactivo['certificado_key']=='')?'':'<span class="text-success">'.$certificadoactivo['certificado_key'].'</span>'?>
                        </div>
                        <div class="col-md-12">
                            <?php echo $form->labelEx($certificadoactivo,'certificado_password'); ?>
                            <?php echo $form->textField($certificadoactivo,'certificado_password',array('class'=>'form-control','value'=>$certificadoactivo['certificado_password'])); ?>
                            <?php echo $form->error($certificadoactivo,'certificado_password'); ?>
                        </div>
                       
                    </div>
                    <?php echo $form->hiddenField($certificadoactivo,'id',array('value'=>$certificadoactivo['id'])); ?>
                    <?php echo $form->hiddenField($certificadoactivo,'id_sucursal',array('value'=>$id)); ?>
                    <?php echo $form->hiddenField($certificadoactivo,'eliminado',array('value'=>0)); ?>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <hr>
                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check-circle"></i> Guardar</button>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div><!-- form -->
            </div>
        </div>
    </div>   
</div>