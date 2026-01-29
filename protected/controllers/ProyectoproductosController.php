<?php

class ProyectoproductosController extends Controller
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
	/**
	 *	METODO PARA AGREGAR UN EMPLEADO AL PROYECTO
	 *	CREADO POR DANIEL VILLARRAEL EL 17 DE MARZO DEL 2016
	 */
	public function actionAgregarproducto()
	{
		$agregar = new Proyectosproductos;
		$agregar->id_proyecto = $_POST['id_proyecto'];
		$agregar->id_producto = $_POST['id_producto'];
		$agregar->id_almacen = $_POST['id_almacen'];
		$agregar->proyectos_productos_cantidad_surtida = 0;
		$agregar->proyectos_productos_cantidad = $_POST['cantidad'];

		if($agregar->save())
		{
			// Obtenemos la fila para agregarla a la tabla
			$tr = $this->Obtenertablaproductos($agregar->id_proyectos_productos);
			echo CJSON::encode(array(
	      	'requestresult' => 'ok',
	      	'message' => 'Se guardo con exito.',
	      	'fila' => $tr
      	));
		}else{
			echo CJSON::encode(array(
	      	'requestresult' => 'fail',
      		'message' => 'Ocurrio un error inesperado'
      	));
		}
	}

	public function Obtenertablaproductos($id)
	{
		// Obtenemos la lista de empleados
		$Datosproducto = Proyectosproductos::model()->findbypk($id);

		$tr = '
		 <tr id="'.$Datosproducto->id_proyectos_productos.'" data-idprod="'.$Datosproducto->id_producto.'">
     		<td>'.$Datosproducto->rl_producto->producto_nombre.'</td>
  			<td>'.$Datosproducto->rl_almacen->almacen_nombre.'</td>
  			<td>
     			<select name="id_almacenes_ubicaciones" class="calcularstock form-control" data-idprod="'.$Datosproducto->id_producto.'" data-idproyprod="'.$Datosproducto->id_proyectos_productos.'" id="id_almacenes_ubicaciones'.$Datosproducto->id_proyectos_productos.'">
     				<option value="">-- Seleccione ubicación --</option>';
     				
     				$ubicacionesalmacen = Almacenesubicaciones::model()->findall('id_almacen='.$Datosproducto->id_almacen);
  					foreach ($ubicacionesalmacen as $Datosproductoa)
     				{
     					$tr .= '<option value="'.$Datosproductoa->id_almacenes_ubicaciones.'">
     						'.$Datosproductoa->almacenesubicaciones_nombre.'
     					</option>';
     				}
     	$tr .= '			
     			</select>
  			</td>
  			
  			<td>
  				<input type="text" name="necesario" id="necesario'.$Datosproducto->id_proyectos_productos.'" class="form-control calcularstock" value="'.$Datosproducto->proyectos_productos_cantidad.'" data-idprod="'.$Datosproducto->id_producto.'" data-idproyprod="'.$Datosproducto->id_proyectos_productos.'" readonly="TRUE">
  			</td>
  			<td>
  				<input type="text" readonly="true" name="stock" id="stock'.$Datosproducto->id_proyectos_productos.'" class="form-control">
  			</td>
  			<td>
  				<input type="text" readonly="true" name="faltante" id="faltante'.$Datosproducto->id_proyectos_productos.'" class="form-control" value="">
  			</td>
  			<td>
  				<select name="id_proveedor" class="form-control calcularprecio" data-idprod="'.$Datosproducto->id_producto.'" data-idproyprod="'.$Datosproducto->id_proyectos_productos.'">
     				<option value="">-- Seleccione proveedor --</option>';
     				
     				$productosproveedores = Productosproveedores::model()->findall('id_producto='.$Datosproducto->id_producto);
  					foreach ($productosproveedores as $Datosproductop)
     				{
     					$tr .=  '<option value="'.$Datosproductop->id_proveedor.'">
     						'.$Datosproductop->rl_proveedor->proveedor_razonsocial.'
     					</option>';
     				}
     		$tr .= '		
     			</select>		              				
  			</td>
  			<td><input type="text" name="preciounitario" id="preciounitario'.$Datosproducto->id_proyectos_productos.'" class="form-control"  readonly="true"></td>
  			<td><input type="text" name="preciototal" id="preciototal'.$Datosproducto->id_proyectos_productos.'" class="form-control" readonly="true"></td>
  			<td>
  				<button type="button" class="btn btn-danger btn-sm eliminarproducto" data-id="'.$Datosproducto->id_proyectos_productos.'" id="botoneliminar_'.$Datosproducto->id_proyectos_productos.'">Eliminar </button>
  			</td>
    </tr>';

    return $tr;
	}


	/**
	 *	METODO PARA ELIMINAR UN EMPLEADO AL PROYECTO
	 *	CREADO POR DANIEL VILLARRAEL EL 17 DE MARZO DEL 2016
	 */
	public function actionEliminarproducto()
	{
		$id = $_POST['id_proyectos_productos'];
		$eliminar = Proyectosproductos::model()->findbypk($id);
		if(!empty($eliminar) && $eliminar->abierto==1)
		{
			// Si lo podemos eliminar
			if($eliminar->delete())
			{
				echo CJSON::encode(array(
		      	'requestresult' => 'ok',
		      	'message' => 'Se elimino con exito.'
	      	));
			}else{
				echo CJSON::encode(array(
		      	'requestresult' => 'fail',
	      		'message' => 'Ocurrio un error inesperado'
	      	));
			}
			
		}
		
	}
	/*
	 *	METODO QUE REGRESA UNA LISTA JSON DE LOS PRODUCTOS DE UN PROYECTO, QUE AFECTEN ALMACEN
	 *
	 */
	public function actionProductosproyectojs()
	{
		// Formateamos para obtener la variable que buscamos
		// almacenes_movimiento_identificador%5D=Proyecto+-+4
		$Identificador = explode("-",$_POST['Almacenesmovimientos']['almacenes_movimiento_identificador']); 


		echo '<option value="">-- Seleccione --</option>';
		switch(trim($Identificador['0']))
		{
			case 'Proyecto':
			// Obtenemos el id del proyecto
				$data=Proyectosproductos::model()->findAll('id_proyecto=:id_proyecto and abierto = 0 and proyectos_productos_cantidad >proyectos_productos_cantidad_surtida', 
		            array(':id_proyecto'=>(int) $Identificador['1']));
				foreach($data as $rows)
				{
					if($rows->rl_producto->productoafectaalmacen==1)
					{
				  		echo CHtml::tag('option',
				             array('value'=>$rows->id_proyectos_productos),CHtml::encode($rows->rl_producto->producto_nombre.' - '.$rows->proyectos_productos_cantidad),true);
				 	}
				}
			break;
			case 'Traspaso A Almacen':
			// Obtenemos el id del almacen de origen y almacen desetino
				$id_almacen = $_POST['Almacenesmovimientos']['id_almacen'];
				$almacenes_movimientos_referencia = (int) $Identificador['1'];
				$data=Almacenes::model()->findall('almacen_estatus=1 and id_almacen !='.$id_almacen);
				foreach($data as $rows)
				{
					if ($almacenes_movimientos_referencia == $rows->id_almacen){
			        echo CHtml::tag('option',array('value' => $rows->id_almacen, 'selected' => 'selected'),CHtml::encode($rows->almacen_nombre),true);
					}else{
			        echo CHtml::tag('option',array('value' => $rows->id_almacen),CHtml::encode($rows->almacen_nombre),true);
					}
				 	
				}
			break;

		}

	}
	/**
	 *	METODO PARA OBTENER LOS DATOS DEL PRODUCTO
	 *	CREADO POR DANIEL VILLARRAEL EL 29 DE MARZO DEL 2016
	 */
	public function actionDatosproducto()
	{
		$id = $_POST['id_proyectos_productos'];
		$datos = Proyectosproductos::model()->findbypk($id);
		if(!empty($datos))
		{
				echo CJSON::encode(array(
		      	'requestresult' => 'ok',
		      	'message' => 'Se encontro con exito.',
		      	'id_producto'=>$datos->rl_producto->id_producto
	      	));
			}else{
				echo CJSON::encode(array(
		      	'requestresult' => 'fail',
	      		'message' => 'Ocurrio un error inesperado'
	      	));
			}
			
	}
	
}