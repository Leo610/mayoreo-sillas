<?php
$this->pageTitle='Sucursal '.$registro['nombre'];
$this->pageDescription = '';
$this->breadcrumbs=array(
    'Lista sucursales'=>yii::app()->createUrl('sucursales/index'),
	$this->pageTitle
	);

$op_menu_sucursal = $registro->id; // es el id de la sucursal a editar o a agregar cosas
$op_menu = 1;
#include 'menu.php';
$this->renderpartial('//inventario/menu',array('op_menu'=>13));
?>



<div class="panel">
	<div class="col-md-12">
		<div class="form">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'editarregistroform',
                'action'=>Yii::app()->createUrl('sucursales/editardatos'),
                'enableClientValidation'=>true,
                'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    ),
                    )); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $form->errorSummary($registro); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php if($registro['logotipo']!='')
                            {?>
                                <img src="<?=Yii::app()->baseurl?>/images/sucursales/<?=$registro['logotipo']?>" class="img-fluid">
                            <?php } ?>
                            <?php echo $form->labelEx($registro,'logotipo'); ?>
                            <?php echo $form->filefield($registro,'logotipo',array('class'=>'')); ?>
                            <?php echo $form->error($registro,'logotipo'); ?>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($registro,'nombre'); ?>
                                    <?php echo $form->textField($registro,'nombre',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($registro,'nombre'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($registro,'direccion'); ?>
                                    <?php echo $form->textField($registro,'direccion',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($registro,'direccion'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($registro,'codigo_postal'); ?>
                                    <?php echo $form->textField($registro,'codigo_postal',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($registro,'codigo_postal'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($registro,'colonia'); ?>
                                    <?php echo $form->textField($registro,'colonia',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($registro,'colonia'); ?>
                                </div>
                                <div class="col-md-3">
                                            <?php echo $form->labelEx($registro,'estado'); ?>
                                            <?php echo $form->dropdownlist($registro,'estado',$entidades,array('empty'=>'-- Seleccione estado  --','class'=>'form-control actualizarmunicipios','data-campo'=>'agregar')); ?>
                                            <?php echo $form->error($registro,'estado'); ?>
                                </div>
                                <div class="col-md-3">
                                        <?php echo $form->labelEx($registro,'ciudad'); ?>
                                        <?php echo $form->dropdownlist($registro,'ciudad',$municipios,array('empty'=>'-- Seleccione --','class'=>'form-control ciudades')); ?>
                                        <?php echo $form->error($registro,'ciudad'); ?>
                                </div>
                                <div class="col-md-3" >
                                    <?php echo $form->labelEx($registro,'tel1'); ?>
                                    <?php echo $form->textField($registro,'tel1',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($registro,'tel1'); ?>
                                    <!--<div class="row">
                                        <div class="col-md-6">
                                            <?php echo $form->labelEx($registro,'tel1'); ?>
                                            <?php echo $form->textField($registro,'tel1',array('class'=>'form-control')); ?>
                                            <?php echo $form->error($registro,'tel1'); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo $form->labelEx($registro,'tel2'); ?>
                                            <?php echo $form->textField($registro,'tel2',array('class'=>'form-control')); ?>
                                            <?php echo $form->error($registro,'tel2'); ?>
                                        </div>
                                    </div>-->
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($registro,'correo'); ?>
                                    <?php echo $form->textField($registro,'correo',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($registro,'correo'); ?>
                                </div>
                                 <!--<div class="col-md-3">
                                    <?php echo $form->labelEx($registro,'correo_compras'); ?>
                                    <?php echo $form->textField($registro,'correo_compras',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($registro,'correo_compras'); ?>
                                </div>
                                
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($registro,'minimo_caja'); ?>
                                    <?php echo $form->textField($registro,'minimo_caja',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($registro,'minimo_caja'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($registro,'aviso_monto_retiro'); ?>
                                    <?php echo $form->textField($registro,'aviso_monto_retiro',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($registro,'aviso_monto_retiro'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($registro,'porcentaje_iva'); ?>
                                    <?php echo $form->textField($registro,'porcentaje_iva',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($registro,'porcentaje_iva'); ?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo $form->labelEx($registro,'web'); ?>
                                    <?php echo $form->textField($registro,'web',array('class'=>'form-control')); ?>
                                    <?php echo $form->error($registro,'web'); ?>
                                </div>-->
                            </div>
                        </div>
                    </div>
                  
                    
                    <?php echo $form->hiddenField($registro,'id'); ?>
                    <?php echo $form->hiddenField($registro,'pais'); ?>
                    <?php echo $form->hiddenField($registro,'estatus'); ?>
                    <?php echo $form->hiddenField($registro,'eliminado',array('value'=>0)); ?>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <hr>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Actualizar datos de la sucursal</button>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div><!-- form -->
            </div>
        </div>
	</div>
</div>
<script type="text/javascript">
	$( document ).ready(function() {
		$("#editarregistroform #Sucursales_estado").val('').change();
            setTimeout(
                function() {
                	$("#editarregistroform #Sucursales_estado").val('<?=$registro['estado']?>').change();
                    $("#editarregistroform #Sucursales_ciudad").val('<?=$registro['ciudad']?>').change();
                    $("#editarregistroform #Sucursales_fiscal_entidad").val('<?=$registro['fiscal_entidad']?>').change();
            }, 1000);


        $(".actualizarmunicipios").change(function () {
			if (this.value == '') {
				return false;
			}
			var campo = $(this).data('campo');

			// enviamos la petición ajaxx
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("site/obtenermunicipios"); ?>",
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					id_entidad: this.value,
				},
				success: function (Response) {
					if (Response.requestresult == 'ok') {
						 $.notify(Response.message, "success");
						//toastr.success(Response.message, { timeOut: 500 })
							$('.ciudades').empty().append(Response.options);
                            $("#editarregistroform #Sucursales_ciudad").val('<?=$registro['ciudad']?>').change();
					} else {
						 $.notify(Response.message, "warning");
						//toastr.warning(Response.message, { timeOut: 500 })
					}
				},
				error: function (e) {
					//toastr.warning('Ocurrio un error inesperado', { timeOut: 500 })
					 $.notify('Ocurrio un error inesperado', "warning");
				}
			});
		});

        $( ".js-switch-small" ).change(function() {
            // aqui entra cuando el checkbox ya cambio, por ejemplo si esta vacio muestra checekeado y alreves
            /*alert($(this).is(":checked"));*/
            if($(this).is(":checked"))
            {
                var valor = 1;
            }else{
                var valor = 0;
            }
            var id = $(this).data('id');
            var campo = $(this).data('campo');
            
            // metodo ajax para actualizar la informacion
            var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("sucursales/actualizarcampoajax"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                valor:valor,
                id:id,
                campo:campo
            },
            success : function(Response, newValue) {
                if (Response.requestresult == 'ok') {
                    toastr.success(Response.message, {timeOut: 500})
                }else{
                    toastr.warning(Response.message, {timeOut: 500})
                }
            },
            error: function(e){
                   toastr.warning('Ocurrio un error inesperado', {timeOut: 500})
                }
            });
        });
    });
</script>