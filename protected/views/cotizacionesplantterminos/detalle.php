<?php
/* @var $this SucursalesController */
$this->pageTitle = 'Detalle de ' . $model->nombre;
$this->breadcrumbs = array(
  'Cotizaciones plantillas' => array('/cotizacionesplantterminos/admin'),
  'Detalle',
);

?>
<script type="text/javascript">
  $(document).ready(function () {
    // Funcion para ordenar la lista de resultados, lo ordena por la columna 4, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente	
    $('#listadetalles').DataTable({
      "order": [[3, "desc"]],
      "iDisplayLength": 8
    });

    // Metodo para utilizar el select2
    $.fn.modal.Constructor.prototype.enforceFocus = function () { };
    $(".select2").select2({
      theme: "bootstrap"
    });
  });
  function Editar(id) {
    $("#editar").modal('show');
  }

  var condiciones_generales = CKEDITOR.instances.Cotizacionesplantterminos_condiciones_pago ? CKEDITOR.instances.Cotizacionesplantterminos_condiciones_pago.getData() : '';

</script>

<div class="modal-body">
  <div class="form">

    <?php $form = $this->beginWidget(
      'CActiveForm',
      array(
        'id' => 'Cotizacionesplantterminos-form',
        'action' => Yii::app()->createUrl('Cotizacionesplantterminos/Createorupdate'),
        'enableClientValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
      )
    ); ?>

    <div class="row">
      <div class="col-md-12">
        <p class="note">Actualizar los campos</p>
        <?php echo $form->errorSummary($model); ?>
      </div>
    </div>
    <div class="row">
      <!-- <div class="col-md-12">
        <? //php echo $form->labelEx($model, 'nombre'); ?>
        <? //php echo $form->textfield($model, 'nombre', array('class' => 'form-control')); ?>
        <? //php echo $form->error($model, 'nombre'); ?>
      </div> -->
      <div class="col-md-12">
        <?php echo $form->labelEx($model, 'nombre_encargado'); ?>
        <?php echo $form->textfield($model, 'nombre_encargado', array('class' => 'form-control')); ?>
        <?php echo $form->error($model, 'nombre_encargado'); ?>
      </div>
      <div class="col-md-12">
        <label for="">Condiciones Generales</label>
        <!-- <? //php echo $form->labelEx($model, 'condiciones_pago'); ?> -->
        <?php echo $form->textArea($model, 'condiciones_pago', array('class' => 'form-control ckeditor', 'value' => $model->condiciones_pago)); ?>
        <?php echo $form->error($model, 'condiciones_pago'); ?>
      </div>
    </div>
    <!-- <div class="row">
      <div class="col-md-12">
        <? //php echo $form->labelEx($model, 'tiempo_fabricacion'); ?>
        <? //php echo $form->textArea($model, 'tiempo_fabricacion', array('class' => 'form-control ', 'value' => $model->tiempo_fabricacion)); ?>
        <? //php echo $form->error($model, 'tiempo_fabricacion'); ?>
      </div>
    </div> -->
    <!-- <div class="row">
      <div class="col-md-12">
        <? //php echo $form->labelEx($model, 'exclusiones'); ?>
        <? //php echo $form->textArea($model, 'exclusiones', array('class' => 'form-control ', 'value' => $model->exclusiones)); ?>
        <? //php echo $form->error($model, 'exclusiones'); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <? //php echo $form->labelEx($model, 'vigencia_propuesta'); ?>
        <? //php echo $form->textArea($model, 'vigencia_propuesta', array('class' => 'form-control ', 'value' => $model->vigencia_propuesta)); ?>
        <? //php echo $form->error($model, 'vigencia_propuesta'); ?>
      </div>
    </div> -->
    <!-- <div class="row">
      <div class="col-md-12">
        <? //php echo $form->labelEx($model, 'condiciones_generales'); ?>
        <? //php echo $form->textArea($model, 'condiciones_generales', array('class' => 'form-control ', 'value' => $model->condiciones_generales)); ?>
        <? //php echo $form->error($model, 'condiciones_generales'); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <? //php echo $form->labelEx($model, 'comentario'); ?>
        <? //php echo $form->textArea($model, 'comentario', array('class' => 'form-control ', 'value' => $model->comentario)); ?>
        <? //php echo $form->error($model, 'comentario'); ?>
      </div>
    </div> -->

    <div class="row buttons">
      <div class="col-md-12 center">
        <hr>
        <?php echo CHtml::submitButton('Guardar', array('class' => 'btn btn-success')); ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <?php echo $form->hiddenField($model, 'id'); ?>
    <?php $this->endWidget(); ?>
  </div><!-- form -->
</div>
</div>
</div>
</div>