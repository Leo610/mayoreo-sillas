<?php
/* @var $this ProductosController */
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.btn-empacar').click(function (e) {
            console.log('id? ', e.target.dataset.producto_id);

            $("#id_producto_proyecto_ipt").val(e.target.dataset.producto_id);
            $('#formmodal').modal("show")
            $.ajax({
                url: "<?php echo $this->createUrl('productos/RegresarEstatusEmpaqueProyectoProducto'); ?>",
                type: "POST",
                dataType: "json",
                timeout: (120 * 1000),
                data: {
                    idPproducto: e.target.dataset.producto_id,
                },
                success: function (Response) {
                    if (Response.requestresult == 'ok') {
                        $.notify(Response.message, "success");
                        $("#modal_empacado_body").empty();
                        let text = ""
                        if (Response.data.empacado == 1) {
                            text = "¿Deseas desempacar este producto?"
                            $("#formModalLabel").text("Desempacar");
                        } else {
                            text = "¿Deseas empacar este producto?"
                            $("#formModalLabel").text("Empacar");
                        }
                        $("#modal_empacado_body").text(text)
                        $("#modal_proyecto_producto_nombre").text(Response.data.txtNombre)
                        $("#modal_id_pedido").text(Response.data.txtPedido)
                    } else {
                        $.notify(Response.message, "error");
                        $('#formmodal').modal("hide")
                    }
                },
                error: function (e) {
                    $('#formmodal').modal("hide")
                    $.notify(Response.message, "error");
                }
            });
        });


        $('.actualizar_empacado').click(function (e) {
            let idProducto = $("#id_producto_proyecto_ipt").val();
            var tdseleccionado = document.getElementById('td-' + idProducto);
            console.log(tdseleccionado);
            if (idProducto == "") {
                $('#formmodal').modal("hide")
                return
            }

            $.ajax({
                url: "<?php echo $this->createUrl('productos/actualizarEstatusEmpaque'); ?>",
                type: "POST",
                dataType: "json",
                timeout: (120 * 1000),
                data: {
                    idPproducto: idProducto,
                },
                success: function (Response) {

                    if (Response.requestresult == 'ok') {

                        console.log(tdseleccionado.style.background);
                        // solo si fue actualizado modificamos el html
                        if (Response.data.empacado == 1) {
                            $(`#proyecto_producto_empaque_${idProducto}`).text('Desempacar')
                            $(`#proyecto_producto_empaque_${idProducto}`).addClass('btn-danger')
                            $(`#proyecto_producto_empaque_${idProducto}`).removeClass('btn-success')

                            $(tdseleccionado).addClass('ama');
                            $(tdseleccionado).removeClass('naranja');


                        } else {
                            $(`#proyecto_producto_empaque_${idProducto}`).text('Empacar')
                            $(`#proyecto_producto_empaque_${idProducto}`).addClass('btn-success')
                            $(`#proyecto_producto_empaque_${idProducto}`).removeClass('btn-danger')


                            $(tdseleccionado).removeClass('ama');
                            if (Response.cambio == 'no vacio') {
                                $(tdseleccionado).addClass('naranja');

                            }
                        }
                        $('#formmodal').modal("hide")
                        $.notify(Response.message, "success");
                    } else {
                        $.notify(Response.message, "error");
                    }
                },
                error: function (e) {
                    $('#formmodal').modal("hide")
                }
            });
        })
    });
</script>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="formModalLabel">

                </h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <h3 class="text-center" id="modal_empacado_body">
                    </h3>
                    <p class="text-center" style="margin: 0;" id="modal_proyecto_producto_nombre"></p>
                    <p class="text-center" style="margin: 0;">
                        <b id="modal_id_pedido"></b>
                    </p>
                    <input type="hidden" id="id_producto_proyecto_ipt">
                </div>
                <div class="row buttons">
                    <div class="col-md-12 mt-md center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-success actualizar_empacado">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>