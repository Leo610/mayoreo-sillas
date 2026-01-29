<!-- Modal -->
<div id="editarpartidamodal" class="modal fade " role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar partida</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- <img src="" class="img-fluid mx-auto d-block" data-src="<?= Yii::app()->baseurl ?>/images/" -->
                        <img src="" class="img-fluid mx-auto d-block" data-src="<?= Yii::app()->baseurl ?>/archivos/"
                            data-imgno="default-no-image.png" id="editarpartida_img" style="max-height: 300px;">
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Nombre</label>
                                <input type="text" class="form-control form-control-sm" id="editarpartida_nombre"
                                    disabled="true">
                            </div>
                            <div class="col-md-12">
                                <label>Clave</label>
                                <input type="text" class="form-control form-control-sm" id="editarpartida_clave"
                                    disabled="true">
                            </div>
                            <div class="col-md-12">
                                <label>Unitario *</label>
                                <input type="number" class="form-control form-control-sm" id="editarpartida_unitario"
                                    onchange="Obtenertotal(2)">
                            </div>
                            <div class="col-md-12">
                                <label>Cantidad *</label>
                                <input type="number" class="form-control form-control-sm" id="editarpartida_cantidad"
                                    onchange="Obtenertotal(2)">
                            </div>
                            <div class="col-md-12">
                                <label>Total</label>
                                <input type="number" class="form-control form-control-sm" id="editarpartida_total"
                                    disabled="true">
                            </div>
                            <input type="hidden" name="id_ordencompra" id="id_ordencompra">
                            <input type="hidden" name="id_partida" id="id_partida">
                            <input type="hidden" name="id_producto" id="id_producto">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <hr>
                        <button type="submit" class="btn btn-success" onclick="Actualizarproducto();"><i
                                class="fa fa-check-circle"></i> Actualizar concepto</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                class="fa fa-times-circle"></i> Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // funcion actualizar partida
    function Actualizarproducto() {
        var unitarioproducto = $('#editarpartida_unitario').val();
        var cantidadproducto = $('#editarpartida_cantidad').val();
        var id_partida = $('#id_partida').val();
        var id_producto = $('#id_producto').val();
        if (unitarioproducto == '' || cantidadproducto == '' || id_producto == '' || cantidadproducto == 0) {
            toastr.warning('Favor de llenar todos los campos', { timeOut: 500 })
            return false;
        }
        // enviamos la peticion para insertar el producto
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("ordenescompra/actualizarproductooc"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id_partida: id_partida,
                id_producto: id_producto,
                unitarioproducto: unitarioproducto,
                cantidadproducto: cantidadproducto,
                id: <?= $ordencompra['id'] ?>
            },
            success: function (Response) {
                if (Response.requestresult == 'ok') {
                    // limpiamos el campo
                    toastr.success(Response.message, { timeOut: 500 })
                    ObtenerdatosOC();
                    $('#editarpartidamodal').modal('hide');
                } else {
                    toastr.warning(Response.message, { timeOut: 500 })
                }
            },
            error: function (e) {
                toastr.warning('Ocurrio un error inesperado', { timeOut: 500 })
            }
        });
    }
</script>