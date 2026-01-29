<?php
/* @var $this AdministracionController */

$this->pageTitle = 'Datos de Configuración';
$this->breadcrumbs = array(
    'Modulos' => Yii::app()->createUrl("administracion/modulos"),
    'Datos de Configuración',
);
?>
<script type="text/javascript">
    $(document).on('ready', function () {


    });
</script>
<div class="row">
    <div class="col-md-12">
        <h1 class="m-none p-none">
            <?= $this->pageTitle ?>
        </h1>

        <div class="form">
            <?php $form = $this->beginWidget(
                'CActiveForm',
                array(
                    'id' => 'Bancos-form',
                    'action' => Yii::app()->createUrl('configuracion/index'),
                    'enableClientValidation' => true,
                    'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
                    'htmlOptions' => array(
                        'enctype' => 'multipart/form-data',
                    ),
                )
            ); ?>

            <div class="row">
                <div class="col-md-12">
                    <?php echo $form->errorSummary($Datos); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php if ($Datos->logotipo != '') {

                        echo '
                                <img src="' . Yii::app()->baseurl . '/companias/' . $Datos->directorio . '/' . $Datos->logotipo . '" class="img-responsive center-block mt-sm">
                            ';
                        echo '<div class="center">' . CHtml::link(
                            'Eliminar Imagen',
                            array('configuracion/eliminarlogotipo', 'id' => $Datos['id']),
                            array(
                                'submit' => array('configuracion/eliminarlogotipo', 'id' => $Datos['id']),
                                'class' => 'delete btn btn-xs btn-danger mt-sm center',
                                'confirm' => 'Seguro que lo deseas eliminar?'
                            )
                        ) . '</div>';
                    }
                    ?>
                    <div class="clearfix"></div>
                    <?php echo $form->labelEx($Datos, 'nombre_compania'); ?>
                    <?php echo $form->textField($Datos, 'nombre_compania', array('class' => 'form-control')); ?>
                    <?php echo $form->error($Datos, 'nombre_compania'); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($Datos, 'correo'); ?>
                    <?php echo $form->textField($Datos, 'correo', array('class' => 'form-control')); ?>
                    <?php echo $form->error($Datos, 'correo'); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($Datos, 'telefonos'); ?>
                    <?php echo $form->textField($Datos, 'telefonos', array('class' => 'form-control')); ?>
                    <?php echo $form->error($Datos, 'telefonos'); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($Datos, 'web'); ?>
                    <?php echo $form->textField($Datos, 'web', array('class' => 'form-control')); ?>
                    <?php echo $form->error($Datos, 'web'); ?>
                </div>
                <div class="col-md-12">
                    <?php echo $form->labelEx($Datos, 'logotipo'); ?>
                    <?php echo $form->filefield($Datos, 'logotipo', array('class' => 'form-control')); ?>
                    <?php echo $form->error($Datos, 'logotipo'); ?>

                </div>
                <div class="col-md-12">
                    <?php echo $form->labelEx($Datos, 'direccion'); ?>
                    <?php echo $form->textarea($Datos, 'direccion', array('class' => 'form-control')); ?>
                    <?php echo $form->error($Datos, 'direccion'); ?>
                </div>
                <div class="col-md-12">
                    <?php echo $form->labelEx($Datos, 'descripcion'); ?>
                    <?php echo $form->textarea($Datos, 'descripcion', array('class' => 'form-control')); ?>
                    <?php echo $form->error($Datos, 'descripcion'); ?>
                </div>

            </div>
            <div class="row buttons">
                <div class="col-md-12 center mt-md center">
                    <?php echo CHtml::submitButton('Guardar cambios', array('class' => 'btn btn-success')); ?>
                </div>
            </div>
            <?php echo $form->hiddenField($Datos, 'id'); ?>
            <?php $this->endWidget(); ?>
        </div><!-- form -->
    </div>
</div>