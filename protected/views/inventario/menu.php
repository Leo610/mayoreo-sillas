<?php
$menuadicional ='
<ul class="nav nav-tabs navrecurrente">
  <li class="nav-item" role="presentation">
    <a href="'.Yii::app()->createurl('sucursales/index').'" class="nav-link text-center '.(($op_menu==13)?'active':'').'">
      Almacenes
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a href="'.Yii::app()->createurl('reportes/inventariototal').'" class="nav-link text-center '.(($op_menu==1)?'active':'').'">
      Inventario
    </a>
  </li>
  </li><li class="nav-item d-none" role="presentation">
    <a href="'.Yii::app()->createurl('proveedores/index').'" class="nav-link text-center '.(($op_menu==9)?'active':'').' ">
      Proveedores
    </a>
   </li>
  </li><li class="nav-item d-none" role="presentation">
    <a href="'.Yii::app()->createurl('ordenescompra/index').'" class="nav-link text-center '.(($op_menu==6)?'active':'').'">
      Ordenes de Compra
    </a>
  </li>
  </li><li class="nav-item d-none" role="presentation">
    <a href="'.Yii::app()->createurl('ordenescompra/recibir').'" class="nav-link text-center '.(($op_menu==10)?'active':'').'">
      Recibir OC
    </a>
  </li>
  </li><li class="nav-item" role="presentation">
    <a href="'.Yii::app()->createurl('transferencias/index').'" class="nav-link text-center '.(($op_menu==5)?'active':'').'">
      Transferencias
    </a>
  </li>
  
  </li><li class="nav-item" role="presentation">
    <a href="'.Yii::app()->createurl('transferencias/salida').'" class="nav-link text-center '.(($op_menu==11)?'active':'').'">
      Salida TRX
    </a>
  </li>
  </li><li class="nav-item" role="presentation">
    <a href="'.Yii::app()->createurl('transferencias/recibir').'" class="nav-link text-center '.(($op_menu==12)?'active':'').'">
      Recibir TRX
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a href="'.Yii::app()->createurl('inventario/movimientos').'" class="nav-link text-center '.(($op_menu==4)?'active':'').'">
      Movimientos
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a href="'.Yii::app()->createurl('inventario/movimientoajuste').'" class="nav-link text-center '.(($op_menu==3)?'active':'').'">
      Movimiento de ajuste
    </a>
  </li>
  ';
$menuadicional.='</ul>'; 
$this->menuadicional = $menuadicional;