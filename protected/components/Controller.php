<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	public function init()
	{

	}
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();
	public $opcionestitulo;
	public $menuadicional;
	/*
	 * META DESCRIPTION DE LA PAGINA
	 */
	public $pageDescription;

	/**

																							  **/
	/**

																							  **/
	/*
	 * FUNCION PARA OBTENER EL TIPO DE LINK DEL SLIDER
	 * c = categoria, p = producto y l = link
	 */
	public static function ObtenerLink($Slider_Link)
	{
		$TipoLink = $Slider_Link[0]; //1 Obtenemos la primer letra
		$ValorLink = substr($Slider_Link, 2); // eliminamos las 2 primeras letras  
		// Tipos de links categoria,producto y link
		switch ($TipoLink) {
			case 'c':
				$Link = Categorias::model()->findByPk($ValorLink);
				return 'categoria/' . $Link->Categoria_SEO;
				break;
			case 'p':
				$Link = Productos::model()->findByPk($ValorLink);
				return 'productos/' . $Link->Producto_SEO;
				break;
			case 'l':
				return $ValorLink;
				break;

			default:
				# code...
				break;
		} // end switch ($TipoLink) {

	}

	/**
	 * Obtenemos la categoria abuela, padre e hija
	 * Nivel1 = Abuelo, Nivel2 = Padre y Nivel3 = Hijo
	 */
	public function ObtenerNivelesCat($ID_Categoria)
	{
		if ($ID_Categoria != '') {
			$Datos = Categorias::model()->findByPk($ID_Categoria);
			// Verificamos que el padre de la categoria obtenida es mayor a 0, entonces tiene padre, en caso contrario es categoria Nivel1.
			if ($Datos->Categoria_Padre) {
				// Verificamos que la categoria padre, no cuente con otra categoria padre.
				$DatosDos = Categorias::model()->findByPk($Datos->Categoria_Padre);
				if ($DatosDos->Categoria_Padre) {
					// Verificamos que la categoria padre, no cuente con otra categoria padre.
					$DatosTres = Categorias::model()->findByPk($DatosDos->Categoria_Padre);
					$respuesta = array(
						'Nivel1' => $DatosTres->Categoria_SEO,
						'Nivel2' => $DatosDos->Categoria_SEO,
						'Nivel3' => $Datos->Categoria_SEO,
						'Nivel1Nombre' => $DatosTres->Categoria_Nombre,
						'Nivel2Nombre' => $DatosDos->Categoria_Nombre,
						'Nivel3Nombre' => $Datos->Categoria_Nombre
					);
				} else {
					$respuesta = array(
						'Nivel1' => $DatosDos->Categoria_SEO,
						'Nivel2' => $Datos->Categoria_SEO,
						'Nivel3' => '',
						'Nivel1Nombre' => $DatosDos->Categoria_Nombre,
						'Nivel2Nombre' => $Datos->Categoria_Nombre,
						'Nivel3Nombre' => ''
					);
				}
			} else {
				$respuesta = array(
					'Nivel1' => $Datos->Categoria_SEO,
					'Nivel2' => '',
					'Nivel3' => '',
					'Nivel1Nombre' => $Datos->Categoria_Nombre,
					'Nivel2Nombre' => '',
					'Nivel3Nombre' => ''
				);
			}

			return $respuesta;
		}

	}

	/*
																								  Metodo para regresar una cadena limpia, 1 variable de entrada y 1 variable de salida.*/
	public function HacerCadenaSEO($string)
	{
		$string = strtolower(trim($string));

		$string = str_replace(
			array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
			array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
			$string
		);

		$string = str_replace(
			array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
			array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
			$string
		);

		$string = str_replace(
			array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
			array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
			$string
		);

		$string = str_replace(
			array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
			array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
			$string
		);

		$string = str_replace(
			array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
			array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
			$string
		);

		$string = str_replace(
			array('ñ', 'Ñ', 'ç', 'Ç'),
			array('n', 'N', 'c', 'C', ),
			$string
		);

		$string = str_replace(
			array(' '),
			array('-'),
			$string
		);

		//Esta parte se encarga de eliminar cualquier caracter extraño
		$string = str_replace(
			array(
				"\\",
				"¨",
				"º",
				"~",
				"#",
				"@",
				"|",
				"!",
				"\"",
				"·",
				"$",
				"%",
				"&",
				"/",
				"(",
				")",
				"?",
				"'",
				"¡",
				"¿",
				"[",
				"^",
				"`",
				"]",
				"+",
				"}",
				"{",
				"¨",
				"´",
				">",
				"< ",
				";",
				",",
				":",
				"¿",
				"¡"
			),
			'',
			$string
		);
		$string = str_replace(
			array('---'),
			array('-'),
			$string
		);
		$string = str_replace(
			array('--'),
			array('-'),
			$string
		);
		$string = str_replace(
			array('...'),
			array('.'),
			$string
		);
		$string = str_replace(
			array('..'),
			array('.'),
			$string
		);
		$string = str_replace(
			array('…'),
			array(''),
			$string
		);

		// Se agrega esta linea debido a que si en el SEO no debe estar = s-e
		$string = str_replace(
			array('s-e'),
			array('se'),
			$string
		);


		return strtolower($string);
	}
	/*
																								  Metodo para deshacer una cadena limpia, 1 variable de entrada y 1 variable de salida.*/
	public function DeshacerSEO($string)
	{
		$string = str_replace(
			array('-'),
			array(' '),
			$string
		);
		return strtolower($string);
	}

	public function Obtenerprecio($id_producto, $id_listaprecio, $campo)
	{
		$datos = Productosprecios::model()->find('id_producto=' . $id_producto . ' and id_lista_precio=' . $id_listaprecio);
		if (empty($datos)) {
			return 0;
		}
		return $datos[$campo];
	}




	//------    CONVERTIR NUMEROS A LETRAS         ---------------
	//------    Máxima cifra soportada: 18 dígitos con 2 decimales
	//------    999,999,999,999,999,999.99
	// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE BILLONES
	// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE MILLONES
	// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE PESOS 99/100 M.N.
	//------    Creada por:                        ---------------
	//------             ULTIMINIO RAMOS GALÁN     ---------------
	//------            uramos@gmail.com           ---------------
	//------    10 de junio de 2009. México, D.F.  ---------------
	//------    PHP Version 4.3.1 o mayores (aunque podría funcionar en versiones anteriores, tendrías que probar)
	public function numtoletras($xcifra, $moneda = '')
	{
		if (isset($moneda) and $moneda != '') {

		} else {
			$moneda = '';
		}

		$moneda = strtoupper($moneda . 's');
		$xarray = array(
			0 => "Cero",
			1 => "UN",
			"DOS",
			"TRES",
			"CUATRO",
			"CINCO",
			"SEIS",
			"SIETE",
			"OCHO",
			"NUEVE",
			"DIEZ",
			"ONCE",
			"DOCE",
			"TRECE",
			"CATORCE",
			"QUINCE",
			"DIECISEIS",
			"DIECISIETE",
			"DIECIOCHO",
			"DIECINUEVE",
			"VEINTI",
			30 => "TREINTA",
			40 => "CUARENTA",
			50 => "CINCUENTA",
			60 => "SESENTA",
			70 => "SETENTA",
			80 => "OCHENTA",
			90 => "NOVENTA",
			100 => "CIENTO",
			200 => "DOSCIENTOS",
			300 => "TRESCIENTOS",
			400 => "CUATROCIENTOS",
			500 => "QUINIENTOS",
			600 => "SEISCIENTOS",
			700 => "SETECIENTOS",
			800 => "OCHOCIENTOS",
			900 => "NOVECIENTOS"
		);
		//
		$xcifra = trim($xcifra);
		$xlength = strlen($xcifra);
		$xpos_punto = strpos($xcifra, ".");
		$xaux_int = $xcifra;
		$xdecimales = "00";
		if (!($xpos_punto === false)) {
			if ($xpos_punto == 0) {
				$xcifra = "0" . $xcifra;
				$xpos_punto = strpos($xcifra, ".");
			}
			$xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
			$xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
		}

		$XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
		$xcadena = "";
		for ($xz = 0; $xz < 3; $xz++) {
			$xaux = substr($XAUX, $xz * 6, 6);
			$xi = 0;
			$xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
			$xexit = true; // bandera para controlar el ciclo del While
			while ($xexit) {
				if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
					break; // termina el ciclo
				}

				$x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
				$xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
				for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
					switch ($xy) {
						case 1: // checa las centenas
							if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas

							} else {
								$key = (int) substr($xaux, 0, 3);
								if (TRUE === array_key_exists($key, $xarray)) { // busco si la centena es número redondo (100, 200, 300, 400, etc..)
									$xseek = $xarray[$key];
									$xsub = $this->subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
									if (substr($xaux, 0, 3) == 100)
										$xcadena = " " . $xcadena . " CIEN " . $xsub;
									else
										$xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
									$xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
								} else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
									$key = (int) substr($xaux, 0, 1) * 100;
									$xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
									$xcadena = " " . $xcadena . " " . $xseek;
								} // ENDIF ($xseek)
							} // ENDIF (substr($xaux, 0, 3) < 100)
							break;
						case 2: // checa las decenas (con la misma lógica que las centenas)
							if (substr($xaux, 1, 2) < 10) {

							} else {
								$key = (int) substr($xaux, 1, 2);
								if (TRUE === array_key_exists($key, $xarray)) {
									$xseek = $xarray[$key];
									$xsub = $this->subfijo($xaux);
									if (substr($xaux, 1, 2) == 20)
										$xcadena = " " . $xcadena . " VEINTE " . $xsub;
									else
										$xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
									$xy = 3;
								} else {
									$key = (int) substr($xaux, 1, 1) * 10;
									$xseek = $xarray[$key];
									if (20 == substr($xaux, 1, 1) * 10)
										$xcadena = " " . $xcadena . " " . $xseek;
									else
										$xcadena = " " . $xcadena . " " . $xseek . " Y ";
								} // ENDIF ($xseek)
							} // ENDIF (substr($xaux, 1, 2) < 10)
							break;
						case 3: // checa las unidades
							if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada

							} else {
								$key = (int) substr($xaux, 2, 1);
								$xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
								$xsub = $this->subfijo($xaux);
								$xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
							} // ENDIF (substr($xaux, 2, 1) < 1)
							break;
					} // END SWITCH
				} // END FOR
				$xi = $xi + 3;
			} // ENDDO

			if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
				$xcadena .= " DE";

			if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
				$xcadena .= " DE";

			// ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
			if (trim($xaux) != "") {
				switch ($xz) {
					case 0:
						if (trim(substr($XAUX, $xz * 6, 6)) == "1")
							$xcadena .= "UN BILLON ";
						else
							$xcadena .= " BILLONES ";
						break;
					case 1:
						if (trim(substr($XAUX, $xz * 6, 6)) == "1")
							$xcadena .= "UN MILLON ";
						else
							$xcadena .= " MILLONES ";
						break;
					case 2:
						if ($xcifra < 1) {
							$xcadena = "CERO $moneda  $xdecimales/100 ";
						}
						if ($xcifra >= 1 && $xcifra < 2) {
							$xcadena = "UN $moneda  $xdecimales/100  ";
						}
						if ($xcifra >= 2) {
							$xcadena .= " $moneda  $xdecimales/100 "; //
						}
						break;
				} // endswitch ($xz)
			} // ENDIF (trim($xaux) != "")
			// ------------------      en este caso, para México se usa esta leyenda     ----------------
			$xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
			$xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
			$xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
			$xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
			$xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
			$xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
			$xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
		} // ENDFOR ($xz)
		return trim($xcadena);
	}

	// END FUNCTION

	public function subfijo($xx)
	{ // esta función regresa un subfijo para la cifra
		$xx = trim($xx);
		$xstrlen = strlen($xx);
		if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
			$xsub = "";
		//
		if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
			$xsub = "MIL";
		//
		return $xsub;
	}

	// END FUNCTION
	/**
	 * metodo para obrener el primer dia de dos meses al que estamos 
	 * lars 06/02/24
	 */

	public function _data_last_three_month_day()
	{
		// Obtén la fecha actual
		$fechaActual = new DateTime();

		// Resta dos meses a la fecha actual
		$fechaDosMesesAtras = $fechaActual->sub(new DateInterval('P2M'));

		// Establece el día al primero del mes
		$fechaDosMesesAtras->setDate($fechaDosMesesAtras->format('Y'), $fechaDosMesesAtras->format('m'), 1);

		// Imprime la fecha resultante
		return $fechaDosMesesAtras->format('Y-m-d');
	}


	/*
	 *	METODO QUE REGRESA ULTIMO DIA DEL MES
	 */
	public function _data_last_month_day()
	{
		$month = date('m');
		$year = date('Y');
		$day = date("d", mktime(0, 0, 0, $month + 1, 0, $year));
		return date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
	}

	/**
	 * METODO PARA SUMAR DIAS A UNA FECHA
	 */
	public function Sumardiasfecha($fecha, $dias)
	{
		$nuevafecha = strtotime('+' . $dias . ' day', strtotime($fecha));
		$nuevafecha = date('Y-m-d', $nuevafecha);

		return $nuevafecha;
	}

	/*
	 *	METODO QUE REGRESA PRIMER DIA DEL MES
	 */
	public function _data_first_month_day()
	{
		$month = date('m');
		$year = date('Y');
		return date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
	}
	/*
	 *	METODO PARA OBTENER EL IDENTIFICADOR Y EL NOMBRE
	 *	REALIZADO EL 5 DE FEBRERO DEL 2016 POR DANIEL VILLARREAL
	 */
	public function ObtenerIdentificadorMovimientos($Identificador)
	{
		//Traspaso a Almacen - 5
		$Identificador = explode("-", $Identificador);
		//print_r($Identificador);
		//$Identificador['0'] = El primer elemento del arreglo, tipo de identificador
		//$Identificador['1'] = El segundo elemento del arreglo, el id del identificador

		switch (trim($Identificador['0'])) {
			case 'Stock Inicial':
				// El movimiento es stock inicial
				$rspt = 'Stock Inicial';
				break;
			case 'Traspaso A Almacen':
				// El movimiento es traspaso a almacen, salida
				$DatosAlmacen = Almacenes::model()->findByPk(trim($Identificador['1']));
				$rspt = 'Traspaso A Almacen ' . $DatosAlmacen->almacen_nombre;
				break;
			case 'Traspaso De Almacen':
				// El movimiento es traspaso de almacen, entrada
				$DatosAlmacen = Almacenes::model()->findByPk(trim($Identificador['1']));
				$rspt = 'Traspaso De Almacen ' . $DatosAlmacen->almacen_nombre;
				break;
			case 'Orden De Compra':
				$Datosoc = Ordenesdecompra::model()->findbypk(trim($Identificador['1']));
				$rspt = 'Referencia ' . $Datosoc->ordendecompra_numero_factura . ' - OC ' . $Datosoc->id_orden_de_compra;
				// El movimiento es entrada de orden de compra
				break;
			case 'Proyecto':
				$DatosProy = Proyectos::model()->findBypk(trim($Identificador['1']));
				$rspt = 'Proyecto ' . $DatosProy->id_proyecto . ' ' . $DatosProy->proyecto_nombre;
				// El movimiento es salida de proyecto
				break;
		}

		// regresamos la respuesta
		return $rspt;
	}


	/**	
																							 FUNCION PARA REGRESAR EL ORGIEN DEL MOVIMIENTO
																							 **/
	public function Regresarorigenmov($tipo_identificador, $id_identificador = 0)
	{
		//Traspaso a Almacen - 5
		$Identificador = $id_identificador;
		//print_r($Identificador);
		//$Identificador['0'] = El primer elemento del arreglo, tipo de identificador
		//$Identificador['1'] = El segundo elemento del arreglo, el id del identificador

		switch ($tipo_identificador) {

			case 1:
				$rspt = 'Entrada de Orden de compra # ' . $id_identificador;
				break;
			case 2:
				$rspt = 'Entrada de transferencia # ' . $id_identificador;
				break;
			case 3:
				$rspt = 'Entrada de movimiento de ajuste';
				break;
			case 4:
				$rspt = 'Entrada por cancelación de ticket #' . $id_identificador;
				break;
			case 5:
				$rspt = 'Entrada por eliminar concepto, ticket #' . $id_identificador;
				break;
			case 6:
				$rspt = 'Entrada por compra de pedido, ticket #' . $id_identificador;
				break;
			case 7:
				$rspt = 'Entrada a separación, ticket #' . $id_identificador;
				break;
			case 21:
				$rspt = 'Salida de transferencia # ' . $id_identificador;
				break;
			case 22:
				$rspt = 'Salida de movimiento de ajuste';
				break;
			case 23:
				$rspt = 'Salida por pedido, pedido #' . $id_identificador;
				break;
			case 24:
				$rspt = 'Salida por separación #' . $id_identificador;
				break;
			case 25:
				$rspt = 'Salida por garantia Ticket #' . $id_identificador;
				break;
			default:
				$rspt = $tipo_identificador . ' ' . $id_identificador;
		}
		// regresamos la respuesta
		return $rspt;
	}

	// Obtenemos los datos del proveedor en base a al identificador de la orden de compra
	public function ObtenerProveedor($identificador)
	{
		$identificador = explode("-", $identificador);
		switch (trim($identificador['0'])) {
			case 'Orden De Compra':
				// Obtenemos los datos de la orden de compra
				$DatosOrden = Ordenesdecompra::model()->findBypk($identificador['1']);
				// Obtenemos los datos
				$DatosProveedor = Proveedores::model()->findbypk($DatosOrden->id_proveedor);
				return array('referencia' => $DatosProveedor->proveedor_razonsocial, 'identificador' => $DatosOrden->ordendecompra_numero_factura);
				break;
			case 'Empleado':
				$Datos = Proyectosempleados::model()->findbypk($identificador['1']);
				$Empleado = Empleados::model()->findbypk($Datos->id_empleado);
				return array('referencia' => $Empleado->empleado_nombre, 'identificador' => $Datos->id_proyecto . ' ' . $Datos->rl_proyecto->proyecto_nombre);
				break;
		}



	}

	// Obtenemos los datos del cliente, en base al identificador del proyecto
	public function ObtenerCliente($identificador)
	{
		$identificador = explode("-", $identificador);
		switch (trim($identificador['0'])) {
			case 'Proyecto':
				// Obtenemos los datos del proyecto
				$DatosProyecto = Proyectos::model()->findbypk($identificador['1']);

				break;
		}

		$DatosCliente = Clientes::model()->findbypk($DatosProyecto['id_cliente']);
		// echo "<pre>";
		// print_r($DatosCliente);
		// echo "</pre>";
		// exit();

		return $DatosCliente['cliente_razonsocial'];

	}
	// Obtenemos los datos del cliente, en base al identificador del proyecto
	public function ObtenerCliente2($identificador)
	{
		$identificador = explode("-", $identificador);
		switch (trim($identificador['0'])) {
			case 'Pedido':
				// Obtenemos los datos del proyecto
				$DatosProyecto = Proyectos::model()->findbypk($identificador['1']);

				break;
		}

		$DatosCliente = Clientes::model()->findbypk($DatosProyecto['id_cliente']);
		// echo "<pre>";
		// print_r($DatosCliente);
		// echo "</pre>";
		// exit();

		return $DatosCliente['cliente_nombre'];

	}

	/**
																							  METODO QUE REGRESA EL COSTO DE COMPRA DE UN PRODUCTO 
																							  **/
	public function Costocompra($idproducto, $idsucursal, $idproveedor = 0)
	{
		// echo 'id_producto: ' . $idproducto . ' sucursal ' . $idsucursal . ' proveedor ' . $idproveedor;
		// exit;

		$datosproducto = Productos::model()->findByPk($idproducto);
		// $datossucursal = Sucursales::model()->findByPk($idsucursal);


		$datos = Productosprecios::model()->find(
			array(
				'condition' => 'id_producto = :id_producto',
				'params' => array(':id_producto' => $idproducto),
			)
		);

		// $precio_compra = $datos['costo'];
		$precio_compra = $datos['costo'];
		$porcentaje_iva = 16;
		// if ($datossucursal['porcentaje_iva'] != '') {
		// $porcentaje_iva = $datossucursal['porcentaje_iva'];
		// }

		$iva = ($porcentaje_iva / 100) * $precio_compra;

		// echo "<pre>";
		// print_r($datos);
		// echo "</pre>";
		// exit();
		return array(
			'preciocompra' => $precio_compra,
			'iva' => $iva,
			'unitarioconiva' => $precio_compra + $iva,
		);

	}
	/**
																							 METODO QUE REGRESA LOS ESTATUS DE LAS OC 
																							 **/
	public function EstatusOClista()
	{
		return array(
			'1' => 'Requisiciones',
			'2' => 'Abiertas',
			'3' => 'Por autorizar',
			'4' => 'Autorizadas',
			'5' => 'Cerradas',
			'9' => 'Canceladas',
		);
	}

	/*
																							  *	METODO PARA VERIFICAR SI TIENE ACCESO AL MODULO
																							  /*
																							  *	METODO PARA VERIFICAR SI TIENE ACCESO AL MODULO
																							  *	CREADA EL 13 DE FEBRERO DEL 2016
																							  */
	public function VerificarAcceso($id_actividad = 0, $id_usuario)
	{
		// Obtenemos los datos del usuario
		$User = Usuarios::model()->findBypk($id_usuario);
		if ($id_usuario == 99999) {
			// Es el usuario administrador de todo
			return 1;
		} else {
			$perfil = $User->id_perfil;
		}

		$Permiso = PerfilesPermisos::model()->find('id_actividad=' . $id_actividad . ' and id_perfil=' . $User->id_perfil . ' and valor = 1');
		if (empty($Permiso)) {
			// Registramos el movimiento en historial mov
			$RegMov = new PerfilesHistorialMov;
			$RegMov->id_usuario = $id_usuario;
			$RegMov->id_actividad = $id_actividad;
			$RegMov->permiso = 'NO PERMITIDO';
			$RegMov->fecha_hora = date('Y-m-d H:i:s');
			$RegMov->id_perfil = $User->id_perfil;
			$RegMov->id_sucursal = Yii::app()->user->getstate('sucursal');
			$RegMov->save();

			return 0;
		} else {
			// Registramos el movimiento en historial mov
			$RegMov = new PerfilesHistorialMov;
			$RegMov->id_usuario = $id_usuario;
			$RegMov->id_actividad = $id_actividad;
			$RegMov->permiso = 'PERMITIDO';
			$RegMov->fecha_hora = date('Y-m-d H:i:s');
			$RegMov->id_perfil = $User->id_perfil;
			$RegMov->id_sucursal = Yii::app()->user->getstate('sucursal');
			$RegMov->save();
			return $Permiso->valor;
		}

	}
	/*
	 *	METODO PARA VERIFICAR SI TIENE ACCESO AL MODULO
	 *	CREADA EL 13 DE FEBRERO DEL 2016
	 * SOLO UTILIZADA PARA EL CATALOGO DE PERFILES NO UTILIZAR
	 */
	public function VerificarAccesoP($id_actividad, $id_perfil)
	{
		$Permiso = PerfilesPermisos::model()->find('id_actividad=' . $id_actividad . ' and id_perfil=' . $id_perfil . ' and valor = 1');
		if (empty($Permiso)) {

			return 0;
		} else {

			return $Permiso->valor;
		}

	}

	/*
	 * METODO QUE REGRESA UNA LISTA DEL PRODUCTO CON SUS PAQUETES RETURN ARRAY,
	 * REALIZADA POR DANIEL VILLARREAL 1 DE FEBRERO DEL 2016
	 */
	public function Productosnecesarios($id_producto, $cantidad)
	{
		$respuesta = '';
		$DatosProducto = Productos::model()->findByPk($id_producto);
		// Verificamos que el producto sea paquete y obtenemos sus productos
		if ($DatosProducto->producto_tipo == 1) {
			foreach (Productospaquetes::model()->findall('id_producto=' . $id_producto . ' and pp_estatus=1') as $productos) {
				$respuesta .= '
				<tr>
					<td>' . $productos->id_productoincluido . '</td>
					<td>' . $productos->rl_productoincluido->producto_nombre . '</td>
					<td>' . $productos->rl_productoincluido->rl_unidadesdemedida->unidades_medida_abreviatura . '</td>
					<td></td>
					';

				if ($productos->rl_productoincluido->producto_horashombre == 1) {
					$respuesta .= '<td>' . $productos->pp_cantidad * $cantidad . ' (' . ($productos->pp_cantidad * $productos->rl_productoincluido->cantidad_horas) * $cantidad . ' horas )</td>';
				} else {
					$respuesta .= '<td>' . $productos->pp_cantidad * $cantidad . '</td>';
				}

				$respuesta .= '<td></td>
				</tr>
        ';
				$respuesta .= $this->Productosnecesarios($productos->id_productoincluido, $cantidad);
			}
		}
		return $respuesta;
	}

	/*
	 * METODO QUE REGRESA UNA LISTA DEL PRODUCTO CON SUS PAQUETES RETURN NOMBRE-DESC,
	 * REALIZADA POR DANIEL VILLARREAL 1 DE FEBRERO DEL 2016
	 */
	public function ProductosnecesariosPDF($id_producto, $cantidad)
	{
		$respuesta = '';
		$DatosProducto = Productos::model()->findByPk($id_producto);
		// Verificamos que el producto sea paquete y obtenemos sus productos
		if ($DatosProducto->producto_tipo == 1) {
			foreach (Productospaquetes::model()->findall('id_producto=' . $id_producto . ' and pp_estatus=1') as $productos) {
				$respuesta .= $productos->rl_productoincluido->producto_nombre . '<br>' . $productos->rl_productoincluido->producto_descripcion;
				$respuesta .= $this->ProductosnecesariosPDF($productos->id_productoincluido, $cantidad);
			}
		}
		return $respuesta;
	}

	/*
	 * METODO QUE REGRESA UNA INSERT DE TODOS LOS PRODUCTOS DE UNA COT A PROYECTO
	 * REALIZADA POR DANIEL VILLARREAL 14 DE FEBRERO DEL 2016
	 */
	public function AgregarProductosProyecto($id_proyecto, $id_producto, $cantidad, $id_almacen)
	{
		$DatosProducto = Productos::model()->findByPk($id_producto);
		$DatosProyecto = Proyectos::model()->findByPk($id_proyecto);
		// Verificamos que el producto sea paquete y obtenemos sus productos
		if ($DatosProducto->producto_tipo == 1) {
			foreach (Productospaquetes::model()->findall('id_producto=' . $id_producto . ' and pp_estatus=1') as $productos) {
				// Insertamos el producto al proyecto
				if ($productos->rl_productoincluido->producto_horashombre == 0) {
					$Proyectoproductos = new Proyectosproductos;
					$Proyectoproductos->id_proyecto = $id_proyecto;
					$Proyectoproductos->id_producto = $productos->id_productoincluido;
					$Proyectoproductos->proyectos_productos_cantidad = $productos->pp_cantidad * $cantidad;
					$Proyectoproductos->id_almacen = $id_almacen;
					$Proyectoproductos->save();
				} else {

					// Es horas hombre
					// calculamos la cantidad
					$cantidadhoras = ($productos->pp_cantidad * $productos['rl_productoincluido']['cantidad_horas']) * $cantidad;

					$Proyectoempleado = new Proyectosempleados;
					$Proyectoempleado->id_proyecto = $id_proyecto;
					$Proyectoempleado->id_producto = $productos->id_productoincluido;
					$Proyectoempleado->id_empleado = 0;
					$Proyectoempleado->proyectos_empleados_fecha_alta = date('Y-m-d H:i:s');
					$Proyectoempleado->proyectos_empleados_cantidad = $productos->pp_cantidad * $cantidad;
					$Proyectoempleado->proyectos_empleados_cantidad = $cantidadhoras;
					$Proyectoempleado->proyectos_empleados_ultima_modif = date('Y-m-d H:i:s');
					$Proyectoempleado->referencia = $productos->rl_productoincluido->producto_nombre . ' - CANTIDAD: ' . $productos->pp_cantidad;
					$Proyectoempleado->save();
				}

				$this->AgregarProductosProyecto($id_proyecto, $productos->id_productoincluido, $cantidad, $id_almacen);
			}
		}
		//return $respuesta;
	}

	/**
	 * 	metodo que regresa las categorias hijas para utilzrlas dentro de un in 
	 */
	public function ObtenerHijosCat($id_categoria = 0, $respuesta = '')
	{
		if ($id_categoria == '') {
			$id_categoria = 0;
		}
		global $respuesta;
		// Obtenemos todas las categorias hijas
		foreach (Categorias::model()->findall('id_categoria_padre=' . $id_categoria . ' and eliminado = 0 and estatus = 1') as $rows) {
			$respuesta .= $rows->id . ',';
			$this->ObtenerHijosCat($rows->id, $respuesta);
		}
		return $respuesta . '' . $id_categoria;
	}

	/**
	 * metodo que regresa un arreglo de categorias anidando las hijas
	 */
	public function Listacategorias($id_categoria_padre = 0, $separador = '', $array = array())
	{
		global $array;
		$separador .= '>';
		// Obtenemos todas las categorias con el id padre 
		foreach (Categorias::model()->findall(array('condition' => 'id_categoria_padre=' . $id_categoria_padre . ' and eliminado = 0 and estatus = 1', 'order' => 'nombre asc')) as $rows) {
			$array[$rows->id] = $separador . '' . strtoupper($rows->nombre);
			$this->Listacategorias($rows->id, $separador, $array);
		}
		return $array;
	}

	/**	
																							  metodo para generar la cadena de la categoria
																							  **/
	public function Cadenacategorias($id_categoria)
	{
		if ($id_categoria != '') {
			$Datos = Categorias::model()->findByPk($id_categoria);
			if (empty($Datos)) {
				return $respuesta = array(
					'Nivel1' => 0,
					'Nivel2' => 0,
					'Nivel3' => 0,
					'Familia' => 0,
					'Categoria' => 0,
					'Subcategoria' => 0,
					'Familia_id' => 0,
					'Categoria_id' => 0,
					'Subcategoria_id' => 0,
					'cadena' => 0
				);
			}
			// Verificamos que el padre de la categoria obtenida es mayor a 0, entonces tiene padre, en caso contrario es categoria Nivel1.
			if ($Datos->id_categoria_padre) {
				// Verificamos que la categoria padre, no cuente con otra categoria padre.
				$DatosDos = Categorias::model()->findByPk($Datos->id_categoria_padre);
				if ($DatosDos->id_categoria_padre) {
					// Verificamos que la categoria padre, no cuente con otra categoria padre.
					$DatosTres = Categorias::model()->findByPk($DatosDos->id_categoria_padre);
					$respuesta = array(
						'Nivel1' => $DatosTres->seo,
						'Nivel2' => $DatosDos->seo,
						'Nivel3' => $Datos->seo,
						'Familia' => $DatosTres->nombre,
						'Categoria' => $DatosDos->nombre,
						'Subcategoria' => $Datos->nombre,
						'Familia_id' => $DatosTres->id,
						'Categoria_id' => $DatosDos->id,
						'Subcategoria_id' => $Datos->id,
						'cadena' => $DatosTres->nombre . ' > ' . $DatosDos->nombre . ' > ' . $Datos->nombre
					);
				} else {
					$respuesta = array(
						'Nivel1' => $DatosDos->seo,
						'Nivel2' => $Datos->seo,
						'Nivel3' => '',
						'Familia' => $DatosDos->nombre,
						'Categoria' => $Datos->nombre,
						'Subcategoria' => '',
						'Familia_id' => $DatosDos->id,
						'Categoria_id' => $Datos->id,
						'Subcategoria_id' => '',
						'cadena' => $DatosDos->nombre . ' > ' . $Datos->nombre
					);
				}
			} else {
				$respuesta = array(
					'Nivel1' => $Datos->seo,
					'Nivel2' => '',
					'Nivel3' => '',
					'Familia' => $Datos->nombre,
					'Categoria' => '',
					'Subcategoria' => '',
					'Familia_id' => $Datos->id,
					'Categoria_id' => '',
					'Subcategoria_id' => '',
					'cadena' => $Datos->nombre
				);
			}

			return $respuesta;
		}
	}

	// Estatus de las ordenes de compra
	// 0 = Cerrada
	// 1 = Abierta
	// 2 = Finalizada
	// 4 = Cerrada
	// 3 = Cancelada
	public function ObtenerEstatusOC($id_estatus)
	{
		switch ($id_estatus) {
			case 0:
				return 'Pendiente';
				break;
			case 1:
				return 'Abierta';
				break;
			case 2:
				return 'Finalizada';
				break;
			case 3:
				return 'Cancelada';
				break;
			case 4:
				return 'Cerrada';
				break;

			default:
				# code...
				break;
		}
	}


	/*
	 * METODO PARA OBTENER EL COSTO DE HORA DE UN EMPLEADO
	 * REALIZADO POR DANIEL VILLARREAL
	 */
	public function Empleadohora($id_empleado, $id_tipohora)
	{
		$Verificarsiexiste = Empleadoscostoshoras::model()->find('id_empleado =' . $id_empleado . ' and id_tipohora=' . $id_tipohora);
		if (!empty($Verificarsiexiste)) {
			$horaprecio = $Verificarsiexiste->empleadocostohora_costo;
		} else {
			// Si no existe registro, regresamos la hora por default
			$Datosempleado = Empleados::model()->findbypk($id_empleado);
			$horaprecio = $Datosempleado->empleado_costo_hora;
		}
		return $horaprecio;
	}



	/**
	 * FUNCION PARA OBTENER LAS OPORTUNIDADES POR ETAPA
	 */
	public function ObtenerOportunidades($id_etapa, $tipo = 0)
	{

		// Obtenemos todos los usuarios relacionados
		$usuarioshijos = $this->ObtenerHijosUsuario(Yii::app()->user->id, '');
		$parametros = 'id_usuario in (' . $usuarioshijos . ') and id_etapa=' . $id_etapa . ' and tipo_oportunidad=' . $tipo . ' and estatus="SEGUIMIENTO" 
		';
		// Obtenemos todas las oportunidades dela tabla crm_oportunidades involucrados, de ese agente
		$oportunidadesinvolucrado = CrmOportunidadesInvolucrados::model()->findall('eliminado = 0 and id_usuario=' . Yii::app()->user->id);
		$id_op = '';
		foreach ($oportunidadesinvolucrado as $rows) {
			$id_op .= $rows['id_oportunidad'] . ',';
		}
		$id_op = rtrim($id_op, ",");
		if (!empty($id_op)) {
			$parametros .= ' OR id in (' . $id_op . ') and id_etapa=' . $id_etapa . ' and tipo_oportunidad=' . $tipo . ' and estatus="SEGUIMIENTO"';
		}
		// TERMINA 
		$oportunidades = CrmOportunidades::model()->findall($parametros);

		return $oportunidades;
	}

	/**
	 * METODO PARA REGRESAR EL LOGOTIPO
	 */
	public function ObtenerLogotipo()
	{
		// Si no esta declarada la variable de sesion de logotipo la obtenemos
		if (!isset(Yii::app()->session['logotipo']) || !isset(Yii::app()->session['directorio'])) {
			$DatosEmpresa = Configuracion::model()->findbypk(1);
			Yii::app()->session['logotipo'] = $DatosEmpresa->logotipo;
			Yii::app()->session['directorio'] = $DatosEmpresa->directorio;
		}
		if (Yii::app()->session['logotipo'] != '') {
			#$rspt = '<img alt="' . Yii::app()->name . '" class="img-responsive" src="' . Yii::app()->baseUrl . '/companias/' . Yii::app()->session['directorio'] . '/' . Yii::app()->session['logotipo'] . '">';
			$rspt = '<img alt="' . Yii::app()->name . '" class="img-responsive" src="' . Yii::app()->baseUrl . '/companias/' . Yii::app()->session['logotipo'] . '">';
		} else {
			$rspt = '<img alt="' . Yii::app()->name . '" class="img-responsive" src="' . Yii::app()->baseUrl . '/images/logo.png">';
		}
		return $rspt;
	}

	/**
																							  INSERTAR EL LOG DE ORDENES DE COMPRA
																							  **/
	public function Insertarlogoc($objeto)
	{
		$log = new OrdenesCompraLog;
		$log->id_orden_compra = $objeto['id_orden_compra'];
		$log->estatus_anterior = $objeto['estatus_anterior'];
		$log->estatus_final = $objeto['estatus_final'];
		$log->comentarios = $objeto['comentarios'];
		$log->id_usuario = $objeto['id_usuario'];
		$log->fecha_alta = $objeto['fecha_alta'];
		$log->total = $objeto['total'];
		$log->save();
	}

	/**
																							  INSERTAR EL LOG DE TRANSFERENCIAS
																							  **/
	public function Insertarlogtr($objeto)
	{
		$log = new TransferenciasLog;
		$log->id_transferencia = $objeto['id_transferencia'];
		$log->estatus_anterior = $objeto['estatus_anterior'];
		$log->estatus_final = $objeto['estatus_final'];
		$log->comentarios = $objeto['comentarios'];
		$log->id_usuario = $objeto['id_usuario'];
		$log->fecha_alta = $objeto['fecha_alta'];
		$log->save();
	}
	/**
																							  FUNCION QUE REGRESA EL ESTATUS DE LA OC
																							  **/
	public function EstatusOC($id)
	{
		/*
																																																1= NUEVA REQUISICION
																																																2= ORDEN DE COMPRA ABIERTA
																																																3= POR AUTORIZAR
																																																4= ORDEN DE COMPRA AUTORIZADA
																																																5 = CERRADA Y LIBERADA A PAGOS
																																																9 = CANCELADA
																																															*/
		$estatus = array(
			'1' => array(
				'nombre' => 'REQUISICIÓN',
				'badge' => '<span class="badge badge-warning">NUEVA REQUISICIÓN</span>',
				'nombrelista' => 'REQUISICIONES',
				'boton' => '<i class="fa fa-pencil "></i> Editar'
			),
			'2' => array(
				'nombre' => 'ABIERTA',
				'badge' => '<span class="badge badge-default">ABIERTA</span>',
				'nombrelista' => 'ABIERTAS',
				'boton' => '<i class="fa fa-pencil "></i> Editar'
			),
			'3' => array(
				'nombre' => 'POR AUTORIZAR',
				'badge' => '<span class="badge badge-info">POR AUTORIZAR</span>',
				'nombrelista' => 'POR AUTORIZAR',
				'boton' => '<i class="fa fa-pencil "></i> Editar'
			),
			'4' => array(
				'nombre' => 'AUTORIZADA',
				'badge' => '<span class="badge badge-success">AUTORIZADA</span>',
				'nombrelista' => 'AUTORIZADAS',
				'boton' => '<i class="fa fa-search "></i> Ver'
			),
			'5' => array(
				'nombre' => 'TERMINADA',
				'badge' => '<span class="badge badge-info">TERMINADA</span>',
				'nombrelista' => 'TERMINADAS',
				'boton' => '<i class="fa fa-search "></i> Ver'
			),
			'9' => array(
				'nombre' => 'CANCELADA',
				'badge' => '<span class="badge badge-danger">CANCELADA</span>',
				'nombrelista' => 'CANCELADAS',
				'boton' => '<i class="fa fa-search "></i> Ver'
			),
		);

		return $estatus[$id];
	}

	/**
																							  FUNCION QUE REGRESA EL ESTATUS DE LA OC
																							  **/
	public function EstatusTR($id)
	{
		/*
																																															1= transferencia abierta
																																															2= transferencia cerrada
																																															3= transferencia en curso
																																															4= transferencia recibida
																																															9=transferencia cancelada
																																															*/
		$estatus = array(
			'1' => array(
				'nombre' => 'ABIERTA',
				'badge' => '<span class="badge badge-warning">ABIERTA</span>',
				'nombrelista' => 'ABIERTAS',
				'boton' => '<i class="fa fa-pencil "></i> Editar'
			),
			'2' => array(
				'nombre' => 'PREPARADA',
				'badge' => '<span class="badge badge-default">PREPARADA</span>',
				'nombrelista' => 'PREPARADA',
				'boton' => '<i class="fa fa-search "></i> Ver'
			),
			'3' => array(
				'nombre' => 'EN CURSO',
				'badge' => '<span class="badge badge-info">EN CURSO</span>',
				'nombrelista' => 'EN CURSO',
				'boton' => '<i class="fa fa-search "></i> Ver'
			),
			'4' => array(
				'nombre' => 'RECIBIDA',
				'badge' => '<span class="badge badge-success">RECIBIDA</span>',
				'nombrelista' => 'RECIBIDAS',
				'boton' => '<i class="fa fa-search "></i> Ver'
			),
			'9' => array(
				'nombre' => 'CANCELADA',
				'badge' => '<span class="badge badge-danger">CANCELADA</span>',
				'nombrelista' => 'CANCELADAS',
				'boton' => '<i class="fa fa-search "></i> Ver'
			),
		);
		return $estatus[$id];
	}

	/**
																							  METODO QUE REGRESA LOS ESTATUS DE LAS TR 
																							  **/
	public function EstatusTRlista()
	{
		return array(
			'1' => 'Abiertas',
			'2' => 'Cerradas',
			'3' => 'En curso',
			'4' => 'Recibidas',
			'9' => 'Canceladas',
		);
	}

	/**
	 * OBTENEMOS EL TIEMPO DE ALTA EN ESA ETAPA
	 */
	public function ObtenerTiempoEtapa($id_etapa, $id_oportunidad)
	{
		$Fecha = Crmtiempos::model()->find(
			array(
				'condition' => 'id_etapa=' . $id_etapa . ' and id_oportunidad=' . $id_oportunidad,
				'order' => 'id desc'
			)
		);
		if (!empty($Fecha)) {
			return $Fecha->fecha_alta;
		} else {
			return date('Y-m-d H:i:s');
		}

	}

	/**
	 * METODO PARA OBTENER EL TIPO DE PRECIO
	 */
	public function ObtenerTipoPrecio($id)
	{
		switch ($id) {
			case 1:
				return array('label' => 'Precio lista', 'campo' => 'precio');
				break;
			case 2:
				return array('label' => '50 - 99', 'campo' => 'precio_ppp');
				break;
			case 3:
				return array('label' => '100 - 199', 'campo' => 'precio_oferta');
				break;
			case 4:
				return array('label' => '200 - 299', 'campo' => 'precio_cambio');
				break;
			case 5:
				return array('label' => '300 o mas', 'campo' => 'precio_extra');
				break;
			case 6:
				return array('label' => 'Distribuidores', 'campo' => 'costo');
				break;

			default:
				# code...
				break;
		}
	}
	/**
	 * METODO PARA OBTENER EL SIGUIENTE DIA EN BASE AL NOMBRE Y A UNA FECHA
	 */
	public function ObtenerSigDia($NombreDia, $Fecha)
	{

		switch ($NombreDia) {
			case 'LUNES':
				$NombreDia = 'monday';
				break;
			case 'MARTES':
				$NombreDia = 'tuesday';
				break;
			case 'MIERCOLES':
				$NombreDia = 'wednesday';
				break;
			case 'JUEVES':
				$NombreDia = 'thursday';
				break;
			case 'VIERNES':
				$NombreDia = 'friday';
				break;
			case 'SABADO':
				$NombreDia = 'saturday';
				break;
			case 'DOMINGO':
				$NombreDia = 'sunday';
				break;
		}

		return date('Y-m-d', strtotime('next ' . $NombreDia, strtotime($Fecha)));
	}

	/**
	 * METODO PARA OBTENER LA SIGUIENTE ACCION DE UNA OPORTUNIDAD
	 */
	public function ObtenerSigAccion($oportunidad)
	{
		// Obtenemos todas las acciones de la oportunidad
		$CrmDetalles = Crmdetalles::model()->find(
			array(
				'condition' => 'id_oportunidad=' . $oportunidad,
				'order' => 'crm_detalles_fecha desc'
			)
		);

		return $CrmDetalles;
	}

	/**
	 * METODO PARA OBTENER TODOS LOS HIJOS DE ESE USUARIO
	 */
	public function ObtenerHijosUsuario($id_usuario, $respuesta = '')
	{
		global $respuesta;
		// Obtenemos todos los usuarios con 0 
		if (empty($respuesta)) {
			// Obtenemos si el usuario es principal
			$UsuarioActual = Usuarios::model()->find(' ID_Usuario=' . $id_usuario);
			// echo "<pre>";
			// print_r($UsuarioActual);
			// echo "</pre>";
			// exit();
			if ($UsuarioActual->id_usuario_padre == 0 || $UsuarioActual->id_usuario_padre == '') {
				foreach (Usuarios::model()->findall('id_usuario_padre in(0,"") and ID_Usuario!=' . $id_usuario) as $rows) {
					$respuesta .= $rows->ID_Usuario . ',';
				}
			}
		}
		// Obtenemos todos los hijos del usuario
		foreach (Usuarios::model()->findall('id_usuario_padre=' . $id_usuario) as $rows) {
			$respuesta .= $rows->ID_Usuario . ',';
			#$this->ObtenerHijosUsuario($rows->ID_Usuario,$respuesta);
		}
		return $respuesta . '' . $id_usuario;
	}

	/**
	 * METODO PARA REGISTRAR UN NUEVO REGISTRO CUANDO ESTE NO EXISTE CON AUTOCOMPLETE
	 */
	public function GenerarRegistroAutoComplete($Modelo, $campo, $valor, $camporeturn, $categoria = 0)
	{
		$agregar = new $Modelo;
		$agregar->$campo = $valor;
		if ($categoria > 0) {
			$agregar->id_grupo_recurrente = $categoria;
		}
		if ($agregar->save()) {

		} else {
			/*print_r($agregar->getErrors());*/
		}
		return $agregar->$camporeturn;
	}


	/**
	 * METODO PARA OBTENER TODOS LOS HIJOS DE ESA OPORTUNIDAD
	 */
	public function ObtenerHijosOportunidad($id_oportunidad, $respuesta = '')
	{
		global $respuesta;
		// Obtenemos todos los hijos de la oportunidad
		foreach (CrmOportunidadesInvolucrados::model()->findall('id_oportunidad =' . $id_oportunidad) as $rows) {
			$respuesta .= $rows->id_usuario . ',';
			$this->ObtenerHijosUsuario($rows->id_usuario, $respuesta);
		}
		return $respuesta;
	}

	/**
	 * METODO PARA OBTENER LA CADENA DE ERRORES 
	 */
	public function ObtenerError($array)
	{
		$rspt = '';
		foreach ($array as $key => $value) {
			foreach ($value as $key2 => $error) {
				/*$rspt.= $key.' '.$error;*/
				$rspt .= $error;
			}
		}
		return $rspt;
	}

	// metodo para poner el mugre mes en español jajaja :s :(
	// lars 22/11//23

	public function Fechaespañol($fecha)
	{
		// meses en español
		$meses_espanol = array(
			'January' => 'Enero',
			'February' => 'Febrero',
			'March' => 'Marzo',
			'April' => 'Abril',
			'May' => 'Mayo',
			'June' => 'Junio',
			'July' => 'Julio',
			'August' => 'Agosto',
			'September' => 'Septiembre',
			'October' => 'Octubre',
			'November' => 'Noviembre',
			'December' => 'Diciembre'
		);
		$fecha_es = date("d ", strtotime($fecha)) . $meses_espanol[date("F", strtotime($fecha))] . date(" Y", strtotime($fecha));
		return $fecha_es;
	}
	// metodo para poner el mugre mes en español y solo las primeras 3 letras jajaja :s :(
	// lars 22/11//23

	public function Fechacortada($fecha)
	{
		// meses en español
		$meses_espanol = array(
			'January' => 'Ene',
			'February' => 'Feb',
			'March' => 'Mar',
			'April' => 'Abr',
			'May' => 'May',
			'June' => 'Jun',
			'July' => 'Jul',
			'August' => 'Ago',
			'September' => 'Sep',
			'October' => 'Oct',
			'November' => 'Nov',
			'December' => 'Dic'
		);
		$fecha_es = date("d ", strtotime($fecha)) . $meses_espanol[date("F", strtotime($fecha))] . date(" Y", strtotime($fecha));
		return $fecha_es;
	}

	public function Fechacortadaslash($fecha)
	{
		// meses en español
		$meses_espanol = array(
			'January' => '/Ene/',
			'February' => '/Feb/',
			'March' => '/Mar/',
			'April' => '/Abr/',
			'May' => '/May/',
			'June' => '/Jun/',
			'July' => '/Jul/',
			'August' => '/Ago/',
			'September' => '/Sep/',
			'October' => '/Oct/',
			'November' => '/Nov/',
			'December' => '/Dic/'
		);
		$fecha_es = date("d", strtotime($fecha)) . $meses_espanol[date("F", strtotime($fecha))] . date("Y", strtotime($fecha));
		return $fecha_es;
	}

}