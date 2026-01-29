<?php
/* @var $this AdministracionController */

$this->pageTitle='Inicio';
$this->breadcrumbs=array(
	'Administracion',
);
?>
<script type="text/javascript">
$(document).ready(function() {
    // Funcion para mostrar el modal
    $( ".abrirmodal" ).click(function() {
        var modal = $(this).data('idmodal');
        $(modal).modal('show');
    });   
});
</script>
<?php 
$opmenu=4;
include 'menu/menu.php'; ?>
<div class="row mt-md">
	<div class="col-md-12">
	<?php $this->widget('ext.fullcalendar.EFullCalendarHeart', array(
        //'themeCssFile'=>'cupertino/jquery-ui.min.css',
        'options'=>array(
            'header'=>array(
                'left'=>'prev,next,today',
                'center'=>'title',
                'right'=>'month,agendaWeek,agendaDay',
            ),
            'events'=>$this->createUrl('crmdetalles/Obtenereventos'), // URL to get event
            'lang'=>'es', // languaje
            /*'dayClick'=> "js:function(date) {
                    alert(date);
                }"
                */
        )));
    ?>
	</div>
</div>