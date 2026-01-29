<?php

class CrmdetallesController extends Controller
{/**
   * @return array action filters
   */
  public function filters()
  {
    return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules()
  {
    return array(
      array('allow', // allow authenticated users to access all actions
          'users'=>array('@'),
            ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }
	public function actionObtenereventos()
	{
		  $items = array();
      // Obtenemos todos los eventos de lo usuarios relacionados
      $usuarioshijos = $this->ObtenerHijosUsuario(Yii::app()->user->id,'');
      $parametros = 'crm_detalles_usuario_alta in ('.$usuarioshijos.') and crm_detalles_estatus=1';
      $result = Crmdetalles::model()->findAll($parametros);
      // TERMINA 

      foreach ($result as $value) {
         $items[]=array(
             'title'=>$value->rl_cliente->cliente_nombre.' '.$value->rl_crmaccion->crm_acciones_nombre,
             'start'=>$value->crm_detalles_fecha,
             //'end'=>date('Y-m-d', strtotime('+1 day', strtotime($value->Producto_FechaAlta))),
             'url'=>$this->createUrl('/Administracion/crmver/'.$value->id_oportunidad),
             //'color'=>'#CC0000',
             //'allDay'=>true,
             //
             
         );
      }
      echo CJSON::encode($items);
      Yii::app()->end();
    
	}

  public function actionCrearaccion()
  {
   
    //Agregamos el detalle, verificamos que la accion no sea para fechas anteriores al dia de hoy.
    $fechahoy = date('Y-m-d H:i:s');
    $fechaaccion = date('Y-m-d H:i:s',strtotime($_POST['Crmdetalles']['crm_detalles_fecha']));
    $id_oportunidad = $_POST['Crmdetalles']['id_oportunidad'];

    $DatosOportunidad = CrmOportunidades::model()->findbypk($id_oportunidad);
    
    if($fechaaccion>$fechahoy)
    { 
      $agregarcrm=new Crmdetalles;
      $agregarcrm->attributes=$_POST['Crmdetalles'];
      $agregarcrm->crm_detalles_fecha = $fechaaccion;
      $agregarcrm->crm_detalles_fecha_alta=date('Y-m-d H:i:s');
      $agregarcrm->crm_detalles_estatus=1;
      $agregarcrm->id_cliente=$DatosOportunidad->id_cliente;
      $agregarcrm->crm_detalle_ultima_modificacion=date('Y-m-d H:i:s');
      $agregarcrm->crm_detalles_usuario_alta=Yii::app()->user->id;
      if($agregarcrm->save())
        {
          Yii::app()->user->setFlash('success', "Se agrego con exito");    
        }else{
          print_r($agregarcrm->geterrors());
          Yii::app()->user->setFlash('warning', "Ocurrio un error inesperado");

        }
      
    }else{
      Yii::app()->user->setFlash('warning', "No se pueden agregar acciones antes de la fecha del dia de hoy ".date('Y-m-d H:i:s'));
    }
    // Redireccionamos de la pagina de donde viene
    $urlfrom = Yii::app()->getRequest()->getUrlReferrer();
    $this->redirect($urlfrom);

  }

  public function actionObtenerinformacion()
  {
    // Obtenemos el id
    $id = $_POST['id_oportunidad'];
    $id_op_detalle = $_POST['id_op_detalle'];

    $Datos= Crmdetalles::model()->findbypk($id_op_detalle);

    echo CJSON::encode(array(
      'requestresult' => 'ok',
      'Datos' => $Datos,
      'usuariorealizo'=>$Datos['rl_usuario_realizo']['Usuario_Nombre'],
      'message'=>'Datos obtenido con exito'
    ));
  }

  public function actionActualizar()
  {
      $estatus = $_POST['estatus'];
      $comentario_realizado = $_POST['comentario_realizado'];
      $id_crm_detalle = $_POST['id_crm_detalle'];

      $Datos= Crmdetalles::model()->findbypk($id_crm_detalle);

      if(!empty($Datos))
      {
        if($estatus == "REALIZADO")
        {
          $Datos->estatus = $estatus;
          $Datos->fecha_realizado = date('Y-m-d H:i:s');
          $Datos->id_usuario_realizado =Yii::app()->user->id;
          $Datos->comentario_realizado = $comentario_realizado;
        }else{
          $Datos->crm_detalles_comentarios = $comentario_realizado;
        }
        $Datos->crm_detalle_ultima_modificacion = date('Y-m-d H:i:s');

        $Datos->save();

        echo CJSON::encode(array(
          'requestresult' => 'ok',
          'message'=>'Datos actualizados con exito'
        ));
        
      }else{
         echo CJSON::encode(array(
          'requestresult' => 'fail',
          'message'=>'Sin datos para modificar'
         ));
      }

  }
  

  
}