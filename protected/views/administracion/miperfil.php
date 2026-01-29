<?php
/* @var $this AdministracionController */

$this->pageTitle='Mi Perfil '.Yii::app()->user->name;
$this->breadcrumbs=array(
	$this->pageTitle,
);
?>
<div class="row">
    <div class="col-md-12">
        <h1><?=$this->pageTitle;?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tabs">
            <ul class="nav nav-tabs nav-justified">
                <li class="active">
                    <a href="#popular10" data-toggle="tab" class="text-center"><i class="fa fa-user" aria-hidden="true"></i> Datos Generales</a>
                </li>
                <li>
                    <a href="#recent10" data-toggle="tab" class="text-center"><i class="fa fa-lock" aria-hidden="true"></i> Accesos</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="popular10" class="tab-pane active">
                    <!-- -->    
                    <div class="form">
                        <?php $form=$this->beginWidget('CActiveForm', array(
                            'id'=>'usuarioseditar-form',
                            'action'=>Yii::app()->createUrl('Usuarios/Createorupdate'),
                            'enableClientValidation'=>true,
                            'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
                        )); ?>

                            <div class="row">
                                <div class="col-md-12">
                                <p class="note">Llene los campos a continuación para editar un usuario</p>
                                    <?php echo $form->errorSummary($model); ?>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo $form->labelEx($model,'Usuario_Nombre'); ?>
                                    <?php echo $form->textField($model,'Usuario_Nombre',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($model,'Usuario_Nombre'); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo $form->labelEx($model,'Usuario_Email'); ?>
                                    <?php echo $form->textField($model,'Usuario_Email',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($model,'Usuario_Email'); ?>
                                </div>
                            </div>
                            
                            <div class="row buttons">
                                <div class="col-md-12 center">
                                    <hr>
                                    <?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
                                </div>
                            </div>
                            <?php echo $form->hiddenField($model,'ID_Usuario'); ?>
                        <?php $this->endWidget(); ?>
                        </div><!-- form -->
                    <!-- -->
                </div>
                <div id="recent10" class="tab-pane">
                    <!-- -->
                    <div class="form">
                        <?php $form=$this->beginWidget('CActiveForm', array(
                            'id'=>'usuariospswdform',
                            'action'=>Yii::app()->createUrl('Usuarios/Actualizarpssword'),
                            'enableClientValidation'=>true,
                            'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
                        )); ?>

                            <div class="row">
                                <div class="col-md-12">
                                <p class="note">Cambiar contraseña de usuario</p>
                                    <?php echo $form->errorSummary($model); ?>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo $form->labelEx($model,'Usuario_Email'); ?>
                                    <?php echo $form->textField($model,'Usuario_Email',array('class'=>'form-control','readonly'=>'true')); ?>
                                    <?php echo $form->error($model,'Usuario_Email'); ?>
                                </div>
                                <div class="col-md-12">
                                    <?php echo $form->labelEx($model,'Usuario_Password'); ?>
                                    <?php echo $form->passwordfield($model,'Usuario_Password',array('class'=>'form-control','value'=>'')); ?>
                                    <?php echo $form->error($model,'Usuario_Password'); ?>
                                </div>
                                <div class="col-md-12">
                                    <?php echo $form->labelEx($model,'ID_Usuario'); ?>
                                    <?php echo $form->textField($model,'ID_Usuario',array('class'=>'form-control','readonly'=>'true')); ?>
                                    <?php echo $form->error($model,'ID_Usuario'); ?>
                                </div>
                            </div>
                            
                            <div class="row buttons">
                                <div class="col-md-12 center">
                                    <hr>
                                    <?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
                                </div>
                            </div>
                        <?php $this->endWidget(); ?>
                    </div><!-- form -->
                    <!-- -->
                </div>
            </div>
        </div>
    </div>
</div>