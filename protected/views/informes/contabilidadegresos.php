<?php
/* @var $this AdministracionController */
$this->pageTitle='Egresos';
$this->breadcrumbs=array(
    'Informes'=>array('/informes/lista'),
    'Reporte Egresos',
    );
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
    // Funcion para ordenar la lista de resultados, lo ordena por la columna 3, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente
    $('#contabilidadegresos').DataTable( {
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching":     false,
        dom: 'Bfrtip',
        buttons: [
        {
            extend: 'csv',
            title: 'Reporte de Egresos',
            text: 'Exportar a EXCEL'
        },
        {
            extend: 'pdfHtml5',
            title: 'Reporte de Egresos',
            text: 'Exportar a PDF'
        },
        {
            extend: 'print',
            title: 'Reporte de Egresos',
            text: 'Imprimir'
        }
        ]
    });
    
});
    </script>
    <div class="row">
     <div class="col-md-12">
        <h1>Lista de Egresos</h1>
        <div class="col-md-12">
            <fieldset>
                <legend>Filtros</legend>
                <form method="GET">
                    <div class="col-md-3">
                      <select name="id_banco" onchange="this.form.submit()" class="form-control">
                        <option value="">
                            -- Todos los bancos --
                        </option>
                        <?php
                        foreach ($ListaBancos as $rows)
                            {?>
                        <option value="<?=$rows['id_banco']?>" <?=($id_banco==$rows['id_banco'])?'selected':'';?> >
                            <?=$rows['banco_nombre']?>
                        </option>
                        <?php }
                        ?>
                    </select>
                </div><div class="col-md-3">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                  'name'=>'fechainicio',
                  'language' => 'es',                                     
                  'htmlOptions'=>array(                                           
                    'readonly'=>"readonly",
                    'class'=>'form-control'
                    ),
                  'options'=>array(                                               
                      'dateFormat'=>'yy-mm-dd',                                                       
                      ),
                  'value'=>$fechainicio
                  )); ?>
              </div>
              <div class="col-md-2">
                  <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name'=>'fechafin',                                                                
                    'language' => 'es',                                     
                    'htmlOptions'=>array(                                           
                      'readonly'=>'readonly',
                      'class'=>'form-control'
                      ),
                    'options'=>array(                                               
                        'dateFormat'=>'yy-mm-dd',

                        ),
                    'value'=>$fechafin
                    )); ?>  
                </div>
                <div class="col-md-2">
                  <select name="id_moneda" onchange="this.form.submit()" class="form-control">

                     <?php
                     foreach ($listamonedas as $rows)
                        {?>
                    <option value="<?=$rows['id_moneda']?>" <?=($id_moneda==$rows['id_moneda'])?'selected':'';?> >
                        <?=$rows['moneda_nombre']?>
                    </option>
                    <?php }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
              <?php echo CHtml::submitButton('Filtro',array('class'=>'btn btn-secondary btn-ml')); ?>
          </div>

      </form>
  </fieldset>

        <div class="table-responsive">
           <table id="contabilidadegresos" class="table  table-bordered  table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Referencia</th>
                        <th>Identificador</th>
                        <th>Forma de Pago</th>
                        <th>Banco</th>
                        <th>Moneda</th>
                        <th>Usuario</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                  <?php 
                  $Total = 0;
                  $TotalPagado = 0;
                  $TotalPendiente = 0;
                  foreach ($ListaEgresos as $rows){
                    $identificadores = $this->ObtenerProveedor($rows->contabilidad_egresos_identificador);
                    ?>
                    <tr>
                        <td><?=$rows->id_contabilidad_egresos?></td>
                        <td><?=$identificadores['referencia']?></td>
                        <td><?=$rows->contabilidad_egresos_identificador.'-'.$identificadores['identificador']?></td>
                        <td><?=$rows->rl_formasdepago->formapago_nombre?></td>
                        <td><?=$rows->rl_banco->banco_nombre?></td>
                        <td><?=$rows->rl_moneda->moneda_nombre?></td>
                        <td><?=$rows->rl_usuario->Usuario_Nombre?></td>
                        <td>$ <?=number_format($rows->contabilidad_egresos_cantidad,2)?></td>
                        <td><?=$rows->contabilidad_egresos_fechaalta?></td>

                    </tr>
                    <?php
                    $Total = $rows->contabilidad_egresos_cantidad + $Total;
                } ?>
                </tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><h3>Totales</h3></td>
                    <td></td>
                    <td><h3><?=number_format($Total,2)?></h3></td>
                    <td></td>
                </tr>
            </table>
        </div>
        </div>
    </div>
</div>