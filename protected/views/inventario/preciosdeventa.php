<?php
$this->pageTitle='Precios de venta';
$this->pageDescription = '';
$this->breadcrumbs=array(
    $this->pageTitle
);
$op_menu = 2;
#include 'menu.php';
$this->renderpartial('//productos/menu_top',array('op_menu'=>8.4));

?>
<div class="panel">
    <form method="get">
        <div class="row">
            <div class="col-md-2">
                <select name="id_listaprecios" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Lista precios --</option>
                    <?php foreach ($listasprecios as $rows) { ?>
                        <option value="<?=$rows['id']?>" <?=($rows['id']==$id_lista_precios)?'selected':'';?> ><?=$rows['nombre']?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="familia" data-plugin="select2" onchange="Obtenercategorias(this.value,'categoria')" class="form-control">
                    <option value="">-- Familia --</option>
                    <?php foreach ($familias as $rows) { ?>
                        <option value="<?=$rows['id']?>" <?=($rows['id']==$cadena["Familia_id"])?'selected':'';?> ><?=$rows['nombre']?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" data-plugin="select2" id="categoria" name="categoria" onchange="Obtenercategorias(this.value,'subcategoria')">
                     <option value="0">-- Categoria --</option>
                    <?php foreach($categorias as $rows){ ?>
                        <option value="<?=$rows['id']?>" <?=($rows['id']==$cadena["Categoria_id"])?'selected':'';?> ><?=$rows['nombre']?></option>
                    <?php } ?>     
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" data-plugin="select2" id="subcategoria" name="subcategoria">
                    <option value="0">-- Subcategoria --</option>
                    <?php foreach($subcategorias as $rows){ ?>
                        <option value="<?=$rows['id']?>" <?=($rows['id']==$cadena["Subcategoria_id"])?'selected':'';?> ><?=$rows['nombre']?></option>
                    <?php } ?>            
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success" style="">Filtrar</button>
            </div>
            
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <table id="resultado" class="table table-sm table-hover datatable table-responsive table-striped">
                <thead>
                    <tr>
                        <th>Lista precio</th>
                        <th>Producto</th>
                        <th>Clave</th>
                        <th>Familia</th>
                        <th>Categoria</th>
                        <th>Subcategoria</th>
                        <th>Precio venta</th>
                        <!-- <th>Precio USD</th> -->
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista as $rows){ 
                        $cadena = $this->Cadenacategorias($rows['idProducto']['id_categoria']);
                        ?>
                    <tr>
                        <td><?=$rows['idListaprecios']['nombre']?> <?=$rows['cantidad_desde']?></td>
                        <td>
                            <a href="<?=Yii::app()->baseurl?>/productos/editarproducto/<?=$rows['id_producto']?>">
                                <?=$rows['idProducto']['nombre']?> 
                            </a>
                        </td>
                        <td><?=$rows['idProducto']['clave']?> </td>
                        <td><?=$cadena['Familia']?></td>
                        <td><?=$cadena['Categoria']?></td>
                        <td><?=$cadena['Subcategoria']?></td>
                       
                        <td data-order="<?=$rows['precio']?>" data-search="<?=$rows['precio']?>">
                            $ <?=number_format($rows['precio'],2)?>
                        </td>
                        <!-- <td data-order="<?=$rows['precio_usd']?>" data-search="<?=$rows['precio_usd']?>">
                            <input type="number" min="0" value="<?=$rows['precio_usd']?>" data-id="<?=$rows['id']?>" class="form-control actualizarcampo" data-campo="precio_usd" data-model="ProductosPrecios">
                        </td> -->
                        
                        <td>
                            <a href="<?=Yii::app()->baseurl?>/inventario/eliminarprecioproducto?id_producto=<?=$rows['id_producto']?>&id=<?=$rows['id']?>" onclick="return confirm('Favor de confirmar')" class="btn btn-danger btn-xs">Eliminar</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        $( "body" ).on( "change", ".actualizarcampo", function() {

            var valor = $(this).val();
            var id = $(this).data('id');
            var campo = $(this).data('campo');
            var model = $(this).data('model');

            var jqxhr = $.ajax({
                url: "<?php echo $this->createUrl("productos/actualizarajaxatributos"); ?>",
                type: "POST",
                dataType : "json",
                timeout : (120 * 1000),
                data: {
                    valor:valor,
                    id:id,
                    campo:campo,
                    model:model,
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
    function Obtenercategorias(idcategoria,idselect)
    {
        if(idcategoria=='')
        {
            return false;
        }

        // enviamos la petición ajaxx
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("categorias/obtenercategoriashijas"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                id_categoria_padre:idcategoria,
            },
            success : function(Response) {
                if (Response.requestresult == 'ok') {
                    toastr.success(Response.message, {timeOut: 500})
                   $('#'+idselect).empty().append(Response.options);
                }else{
                   toastr.warning(Response.message, {timeOut: 500})
                }
            },
            error: function(e){
                    toastr.warning('Ocurrio un error inesperado', {timeOut: 500})
                }
        });
    }
</script>