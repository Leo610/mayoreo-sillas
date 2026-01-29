<nav class="navbar navbar-default mb-none">
   <div class="container-fluid">
   <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Menu</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
     <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="<?=($opmenu==1)?'active':'';?>"><a href="<?=Yii::app()->createurl('administracion/index')?>">Oportunidades <span class="sr-only">Oportunidades</span></a></li>
        <li class="<?=($opmenu==2)?'active':'';?>"><a href="<?=Yii::app()->createurl('administracion/listaactividades')?>">Tareas <span class="sr-only">Tareas</span></a></li>
        <li class="<?=($opmenu==3)?'active':'';?>"><a href="<?=Yii::app()->createurl('administracion/lista')?>">Prospectos <span class="sr-only">Prospectos</span></a></li>
        <li class="<?=($opmenu==4)?'active':'';?>"><a href="<?=Yii::app()->createurl('administracion/calendario')?>">Calendario <span class="sr-only">Calendario</span></a></li>
        <!--
        <li class="<?=($opmenu==6)?'active':'';?>"><a href="<?=Yii::app()->createurl('administracion/rutas')?>">Rutas <span class="sr-only">Rutas</span></a></li>
        -->
        <li class="<?=($opmenu>=5)?'active':'';?>"><a href="<?=Yii::app()->createurl('administracion/enviarmensaje')?>">Enviar Mensaje <span class="sr-only">Enviar Mensaje</span></a></li>
        <!--
        <li class="<?=($opmenu==7)?'active':'';?>"><a href="<?=Yii::app()->createurl('administracion/mensajesrecibidos')?>">Mensajes recibidos <span class="sr-only">Mensajes recibidos</span></a></li>
        <li class="<?=($opmenu==8)?'active':'';?>"><a href="<?=Yii::app()->createurl('administracion/mensajesenviados')?>">Mensajes enviados <span class="sr-only">Mensajes enviados</span></a></li>
        -->

      </ul>
    </div><!-- /.navbar-collapse -->
    </div>
 </nav>