<?php
/* @var $this EnviarmensajeController */
/* @var $model Enviarmensaje */


$this->pageTitle = 'Enviar mensaje';
$this->breadcrumbs = array(
    'Aministracion' => Yii::app()->createUrl('administracion/'),
    'Enviar mensaje',
);

?>
<script type="text/javascript">
    $(document).ready(function () {
        // Funcion para ordenar la lista de resultados
        $('#lista').DataTable();
        // Funcion para mostrar el modal
        $(".abrirmodal").click(function () {
            var modal = $(this).data('idmodal');
            $(modal).modal('show');
        });
    });

</script>
<?php
$opmenu = 5;
include 'menu/menu.php';
?>

<div class="row">
    <div class="col-md-12">
        <h1 class="m-md">Enviar mensaje | <a href="<?= Yii::app()->createurl('administracion/mensajesrecibidos') ?>"
                class="btn btn-default">Mensajes recibidos </a> | <a
                href="<?= Yii::app()->createurl('administracion/mensajesenviados') ?>" class="btn btn-default">Mensajes
                enviados </a></h1>
        <div class="form">

            <?php $form = $this->beginWidget(
                'CActiveForm',
                array(
                    'id' => 'Enviarmensaje-form',
                    'action' => Yii::app()->createUrl('Mensajes/Crear'),
                    'enableClientValidation' => true,
                    'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
                )
            ); ?>


            <?php echo CHtml::errorSummary(array($model)); ?>

            <div class="row">
                <div class="col-md-12">
                    <?php echo $form->labelEx($model, 'id_destinatario'); ?>
                    <?php echo $form->dropDownList($model, 'id_destinatario', $listausuarios, array('empty' => '-- Seleccione --', 'class' => 'form-control select2', 'multiple' => 'true', 'name' => 'id_destinatario[]')); ?>
                    <?php echo $form->error($model, 'id_destinatario'); ?>
                </div>
                <div class="col-md-12">
                    <?php echo $form->labelEx($model, 'asunto'); ?>
                    <?php echo $form->textField($model, 'asunto', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'asunto'); ?>
                </div>
                <div class="col-md-12">
                    <?php echo $form->labelEx($model, 'mensaje'); ?>
                    <?php echo $form->textarea($model, 'mensaje', array('class' => 'form-control ckeditor')); ?>
                    <?php echo $form->error($model, 'mensaje'); ?>
                </div>
            </div>
            <div class="row buttons">
                <div class="col-md-12 mt-md center">
                    <?php echo CHtml::submitButton('Enviar mensaje', array('class' => 'btn btn-success btn-enviar')); ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div><!-- form -->
    </div>
</div>


<script>
    // Example using jQuery for a button click event
    $('.btn-enviar').on('click', function (e) {
        e.preventDefault();
        let mensaje = CKEDITOR.instances.Mensajes_mensaje.getData();
        console.log(mensaje);
        $("#Mensajes_mensaje").val(mensaje)
        setTimeout(function () {
            $("#Enviarmensaje-form").submit();
        }, 0);

    });
</script>