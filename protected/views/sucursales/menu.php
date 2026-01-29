<div class="btn-group btn-group-justified mb-2">
    <div class="btn-group" role="group">
      <a href="<?=yii::app()->createurl('sucursales/detalle',array('id'=>$op_menu_sucursal))?>" class="btn btn-primary <?=($op_menu==1)?'disabled':''?>">
        <i class="icon wb-info-circle" aria-hidden="true"></i>
        <br>
        <span class="text-uppercase hidden-xs">Informacion General</span>
      </a>
    </div>
    <div class="btn-group" role="group">
      <a href="<?=yii::app()->createurl('sucursales/ubicacion',array('id'=>$op_menu_sucursal))?>" class="btn btn-primary <?=($op_menu==3)?'disabled':''?>">
        <i class="fa fa-map-marker" aria-hidden="true"></i>
        <br>
        <span class="text-uppercase hidden-xs">Ubicación</span>
      </a>
    </div>
    <div class="btn-group" role="group">
      <a href="<?=yii::app()->createurl('sucursales/certificadosfacturacion',array('id'=>$op_menu_sucursal))?>" class="btn btn-primary <?=($op_menu==2)?'disabled':''?>">
        <i class="icon  fa-bell" aria-hidden="true"></i>
        <br>
        <span class="text-uppercase hidden-xs">Certificados Facturación</span>
      </a>
    </div>
</div>