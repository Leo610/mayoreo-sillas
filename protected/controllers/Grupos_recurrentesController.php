<?php

class Grupos_recurrentesController extends Controller
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
	/*
	*	METODO PARA LA ADMINISTRACION DE LOS CATALOGOS RECURRENTES
	*
	*/
	public function actionIndex($id)
	{
		// Obtenemos los datos del grupo recurrente
		$Datos = GruposRecurrentes::model()->findbypk($id);

		// Obtenemos la lista de catalogos recurrentes
		$Lista = CatalogosRecurrentes::model()->findall('id_grupo_recurrente='.$id);

		// Modelo para agregar un nuevo registro y validar
		$model =  new CatalogosRecurrentes;

		// Renderizamos la vista
		$this->render('index',
			array(
				'Datos'=>$Datos,
				'Lista'=>$Lista,
				'model'=>$model
				));
	}
}