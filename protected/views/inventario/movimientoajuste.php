<?php
$this->pageTitle = 'Generar movimiento de ajuste';
$this->pageDescription = '';
$this->breadcrumbs = array(
    $this->pageTitle
);
$op_menu = 3;
include 'menu.php';
?>
<div class="row">
    <div class="col-md-8">
        <h1><?= $this->pageTitle ?></h1>
    </div>
</div>
<div class="panel">
    <div class="row">
        <div class="col-md-7">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'agregarregistroform',
                'action' => Yii::app()->createUrl('inventario/movimientoajuste'),
                'enableClientValidation' => true,
                'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    'class' => ''
                )
            )
            ); ?>

            <div class="row">
                <div class="col-md-12">
                    <?php echo $form->errorSummary($agregarmov); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $form->labelEx($agregarmov, 'id_sucursal', array('class' => 'control-label ')); ?>
                    <?php echo $form->dropdownlist($agregarmov, 'id_sucursal', $sucursales, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
                    <?php echo $form->error($agregarmov, 'id_sucursal'); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $form->labelEx($agregarmov, 'id_producto', array('class' => '')); ?>
                    <?php echo $form->hiddenField($agregarmov, 'id_producto', array('class' => 'form-control')); ?>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                        'name' => 'buscador',
                        'id' => 'buscador',
                        'source' => $this->createUrl('administracion/buscadorproducto'),
                        // Opciones javascript adicionales para el plugin
                        'options' => array(
                            'minLength' => '2',
                            'autoFocus' => true,
                            'select' => 'js:function(event, ui) {
                                        var src = $("#imagenproducto").data("src")+ui.item.producto_imagen;
                                        $("#SucursalesMovimientos_id_producto").val(ui.item.id_producto);
                                        $("#nombreprod").val(ui.item.producto_nombre);
                                        $("#claveprod").val(ui.item.producto_clave);
                                            if(ui.item.producto_imagen!=""){
                                                $("#imagenproducto").attr("src",src);
                                                $("#imagenproducto").show();
                                            }
                                        }',
                            'focus' => 'js:function(event, ui) {
                                          return false;
                                      }',
                            'search' => 'js:function(event, ui) {
                                            // obtenemos sucursale
                                            var idsucursal =  $("#SucursalesMovimientos_id_sucursal").val();
                                           $("#SucursalesMovimientos_id_sucursal").val(idsucursal);
                                           $("#SucursalesMovimientos_id_producto").val("");
                                        }',
                        ),
                        'htmlOptions' => array(
                            'class' => 'form-control obtenerstock',
                            'placeholder' => 'Buscar producto'
                        )
                    )
                    );
                    ?>
                    <?php echo $form->error($agregarmov, 'id_producto'); ?>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label>Nombre</label>
                    <input type="text" name="nombreprod" id="nombreprod" readonly="true" class="form-control">
                </div>
                <div class="col-sm-4">
                    <label>Clave</label>
                    <input type="text" name="claveprod" id="claveprod" readonly="true" class="form-control">
                </div>
                <div class="col-sm-4">
                    <label>Stock | <button type="button" class="btn btn-default btn-xs obtenerstock"><i
                                class="fa fa-refresh" aria-hidden="true"></i></button></label>
                    <input type="text" name="stockprod" id="stockprod" readonly="true" class="form-control">
                </div>
            </div>

            <div class="row">
                <!--<div class="col-md-2">
                        <label>Control</label>
                        <input type="text" name="contrlprod" id="contrlprod" readonly="true" class="form-control">
                    </div>-->
                <div class="col-md-4">
                    <?php echo $form->labelEx($agregarmov, 'tipo', array('class' => 'control-label ')); ?>
                    <?php echo $form->dropdownlist($agregarmov, 'tipo', array('1' => 'MOVIMIENTO DE ENTRADA', '2' => 'MOVIMIENTO DE SALIDA'), array('empty' => '-- Seleccione --', 'class' => 'form-control tipomovimiento mostrarocultarfoliorsi')); ?>
                    <?php echo $form->error($agregarmov, 'tipo'); ?>
                </div>
                <div class="col-md-3">
                    <?php echo $form->labelEx($agregarmov, 'cantidad_mov', array('class' => 'control-label')); ?>
                    <?php echo $form->numberField($agregarmov, 'cantidad_mov', array('class' => 'form-control tipomovimiento ', 'min' => '1')); ?>
                    <?php echo $form->error($agregarmov, 'cantidad_mov'); ?>
                </div>
                <div class="col-md-3 divfoliorsi" style="display: none">
                    <?php echo $form->labelEx($agregarmov, 'folio_rsi', array('class' => 'control-label')); ?> *
                    <?php echo $form->textfield($agregarmov, 'folio_rsi', array('class' => 'form-control')); ?>
                    <?php echo $form->error($agregarmov, 'folio_rsi'); ?>
                </div>
            </div>
            <div class="row divserie">
            </div>
            <div class="row ">
                <div class="col-md-12 divseriesalida">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php echo $form->labelEx($agregarmov, 'comentarios', array('class' => 'control-label')); ?>
                    <?php echo $form->textarea($agregarmov, 'comentarios', array('class' => 'form-control', 'style' => 'overflow-x:hidden;')); ?>
                    <?php echo $form->error($agregarmov, 'comentarios'); ?>
                </div>
            </div>
            <?php echo $form->hiddenField($agregarmov, 'eliminado', array('value' => 0)); ?>
            <div class="row">
                <div class="col-md-12">
                    <hr>
                    <button type="submit" class="btn btn-success btn-block" onclick="return Confirmarmovimiento()"><i
                            class="fa fa-check-circle"></i> Guardar movimiento</button>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
        <div class="col-md-5">
            <img src="" class="img-fluid mx-auto d-block" style="display: none;max-height: 400px;" id="imagenproducto"
                data-src="<?= yii::app()->baseurl ?>/archivos/">
        </div>
    </div>
</div>
<script type="text/javascript">
    function Confirmarmovimiento() {
        var control_por_serie = $('#control_por_serie').val();
        var tipo_mov = $('#SucursalesMovimientos_tipo').val();
        // verificamos si es controlado
        if (control_por_serie == 1 && tipo_mov == 1) {
            // entrada
            var continuar = true;
            // es producto controlado verificamos que tengan serie y lote
            $('.verificarinput').each(function () {
                if ($(this).val() == '') {
                    continuar = false;
                }
            })

            if (continuar == false) {
                toastr.warning('Favor de ingresar la serie y el lote', { timeOut: 500 })
                return false;
            } else {

                return true;
            }
        } else if (control_por_serie == 1 && tipo_mov == 2) {
            // es salida
        } else {
            // no es producto controlado no verificamos nada
            return true;
        }
    }
    $(document).ready(function () {
        //

        // metodo para obtener el stock
        $(document.body).on('change click', ".obtenerstock", function (e) {
            var id_producto = $('#SucursalesMovimientos_id_producto').val();
            var id_sucursal = $('#SucursalesMovimientos_id_sucursal').val();
            console.log(id_producto);
            console.log(id_sucursal);
            if (id_producto == '' || id_sucursal == '') {
                $('#stockprod').val('');
                return false;
            }

            // enviamos la petición ajaxx
            var jqxhr = $.ajax({
                url: "<?php echo $this->createUrl("inventario/obtenerstock"); ?>",
                type: "POST",
                dataType: "json",
                timeout: (120 * 1000),
                data: {
                    id_producto: id_producto,
                    id_sucursal: id_sucursal,
                },
                success: function (Response, newValue) {
                    //console.log('Respuesta: '+Response.stock);
                    if (Response.requestresult == 'ok') {
                        //toastr.success(Response.message, {timeOut: 500})
                        $('#stockprod').val(Response.stock);
                    } else {
                        //toastr.warning(Response.message, {timeOut: 500})
                    }
                },
                error: function (e) {
                    toastr.warning('Ocurrio un error inesperado', { timeOut: 500 })
                }
            });
        });
        // metodo para cuando esta controlado por serie, mostrar los campos para que ingresen las series dependiendo el numero de cantidad
        $(".tipomovimiento").bind('keyup mouseup', function () {
            var tipo_mov = $('#SucursalesMovimientos_tipo').val();
            $('#SucursalesMovimientos_cantidad_mov').attr('readonly', false);
            var control_por_serie = $('#control_por_serie').val();
            $('.divseriesalida').hide();
            $(".divserie").empty();
            if (tipo_mov == 1 && control_por_serie == 1) {
                // movimiento de entrada

                var id_producto = $('#SucursalesMovimientos_id_producto').val();
                var id_sucursal = $('#SucursalesMovimientos_id_sucursal').val();

                var cantidad = $('#SucursalesMovimientos_cantidad_mov').val();
                /*alert(id_producto);
                alert(id_sucursal);*/
                if (id_producto == '' || id_sucursal == '' || control_por_serie != 1 || tipo_mov != 1 || cantidad <= 0) {
                    return false;
                }
                // si es un 1 mostramos 
                // creamos un campo por cada cantidad
                $('.divserie').append('<div class="col-md-12" style="display:none"><p class="mb-0 mt-1">Serie y lote del producto *</p></div>');
                for (var i = 0; i < cantidad; i++) {
                    $('.divserie').append('<div class="col-md-6" style="display:none"><input type="text" name="serie[]" class="form-control verificarinput" placeholder="Ingrese la serie"  /></div>');
                    $('.divserie').append('<div class="col-md-6" style="display:none"><input type="text" name="lote[]" class="form-control verificarinput" placeholder="Ingrese el lote"  /></div>');
                }
                $('.verificarinput').val("id_producto:" + id_producto);
            } else if (tipo_mov == 2 && control_por_serie == 1) {

                // mostramos las series
                $('.divseriesalida').show();
                // ponemos el campo de cantidad readnonly, ya que sera la sumatoria de las series seleccionadas
                //$('#SucursalesMovimientos_cantidad_mov').val('');
                //$('#SucursalesMovimientos_cantidad_mov').attr('readonly', 'true');
                // hacemos botones de cada serie para la salida del inventario
                $('.divseriesalida').empty();
                $('.divseriesalida').append('<p class="mb-0 mt-1">Ingrese la cantidad y serie del producto *</p>');
                var cantidad = $('#SucursalesMovimientos_cantidad_mov').val();
                for (var i = 0; i < cantidad; i++) {
                    $('.divseriesalida').append('<label><input type="text" name="serieylotesalida[]" class="serieylotesalida form-control" placeholder="Serie"></label>');
                }

            }
        });

        //
        $(document.body).on('change click', ".serieylotesalida", function (e) {
            // contamos todos los checkeados y esa es la cantidad
            /*var cantidad = 0;
            $('.serieylotesalida').each(function(){
                if($(this).is(':checked')){
                    cantidad = cantidad + 1;
                }
            })
            if(cantidad==0){
                cantidad='';
            }*/
            $('#SucursalesMovimientos_cantidad_mov').val(cantidad);
        });
        $(document.body).on('change', ".mostrarocultarfoliorsi", function (e) {

            var valor = $(this).val();
            if (valor == 1) {
                $('.divfoliorsi').show();
            } else {
                $('.divfoliorsi').hide();
            }
        });
    });
</script>