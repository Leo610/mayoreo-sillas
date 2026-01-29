<!-- Modal -->
<div id="stockserie" class="modal fade " role="dialog">
   <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Serie y lote del producto</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tablstockserie" class="table table-sm table-hover table-responsive table-striped" style="font-size: 11px;">
                            <thead>
                                <tr>
                                    <th>Sucursal</th>
                                    <th>Producto</th>
                                    <th>Serie</th>
                                    <th>Lote</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <hr>
                        <button type="button" class="btn btn-default btn-block" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function Obtenerstockserie(id_producto,id_sucursal)
    {
        // enviamos la petición ajaxx
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("inventario/stockserie"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                id_producto:id_producto,
                id_sucursal:id_sucursal
            },
            success : function(Response) {
                if (Response.requestresult == 'ok') {
                    toastr.success(Response.message, {timeOut: 500});
                    $('#tablstockserie tbody').empty().append(Response.html)
                    $('#stockserie').modal('show');
                }else{
                   toastr.warning(Response.message, {timeOut: 500});
                }
            },
            error: function(e){
                    toastr.warning('Ocurrio un error inesperado', {timeOut: 500})
                }
        });
    }
</script>