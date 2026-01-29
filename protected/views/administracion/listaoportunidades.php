<?php
/* @var $this AdministracionController */
$this->pageTitle='Lista de Oportunidades';

$this->breadcrumbs=array(
	'Administracion'=>array('/administracion'),
	'Lista de Oportunidades' ,
);
?>
<script type="text/javascript">
$( document ).ready(function() {
    // Funcion para ordenar la lista de resultados
    $('#listaprospectos').DataTable();   
    // Funcion para mostrar el modal
    $( ".abrirmodal" ).click(function() {
        var modal = $(this).data('idmodal');
        $(modal).modal('show');
    }); 
 });

</script>
<div class="row">
	<div class="col-md-9">
		<h1>Lista de Oportunidades</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
        <hr>
        <div class="table-responsive">
        <table id="listaprospectos" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Fecha Alta</th>
                    <th>Agente</th>
                    <th>Etapa</th>
                    <th></th>   
                </tr>
            </thead>
            <tbody>
            		<?php 
            		foreach ($listaoportunidades as $rows){ ?>
                <tr>
                    <td><?=$rows->id?></td>
                    <td><?=$rows->nombre?></td>
                    <td><?=$rows->rl_clientes->cliente_nombre?></td>
                    <td><?=$rows->rl_clientes->cliente_telefono?></td>
                    <td><?=$rows->fecha_alta?></td>
                    <td><?=$rows->rl_usuario->Usuario_Nombre?></td>
                    <td><?=$rows->rl_catalogo->nombre?></td>
                   	<td class="">
                        <?php 
														// Administracion/crmver/27
                   	       
													echo CHtml::link('<i class="fa fa-search-plus"></i>
 Ver',array('Administracion/crmver/'.$rows['id']),array('class'=>"btn btn-primary"));
    									?>
                   </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

