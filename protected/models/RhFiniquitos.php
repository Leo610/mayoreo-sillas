<?php

/**
 * Model class for table "rh_finiquitos".
 * Replica exacta de Hoja4.cls del Excel VBA.
 * Calcula DOS finiquitos: IMSS y REAL, mas COMPENSACION (diferencia).
 *
 * VBA Key formulas:
 * C18 (IMSS semanal) = BUSCARV(C4,EMPLEADOS!B$1:AA107,26,FALSO)*7  → INTEGRADO * 7
 * M18 (REAL semanal)  = BUSCARV(M4,EMPLEADOS!B$1:Z100,19,FALSO)     → SUELDO SEMANAL REAL
 * C20 (dias aguinaldo) = (fecha_renuncia - fecha_aguinaldo) * (15/365)
 * C26 (dias vacaciones) = (valorBT/365)*diasProporcionales + valorQ (pendientes)
 * valorBT = lookup antiguedad en tabla Art.76 LFT
 * valorQ  = EMPLEADOS columna Q (dias pendientes)
 */
class RhFiniquitos extends RActiveRecord
{
	public function getDbConnection()
	{
		return self::getAdvertDbConnection();
	}

	public function tableName()
	{
		return 'rh_finiquitos';
	}

	public function rules()
	{
		return array(
			array('id_empleado, finiquito_fecha_renuncia, finiquito_fecha_aguinaldo', 'required'),
			array('id_empleado, id_usuario, finiquito_dias_proporcionales', 'numerical', 'integerOnly' => true),
			array('finiquito_sueldo_diario, finiquito_sueldo_semanal_real, finiquito_sueldo_semanal_imss, finiquito_aguinaldo_dias, finiquito_aguinaldo, finiquito_aguinaldo_imss, finiquito_dias_vacaciones, finiquito_dias_pendientes, finiquito_pago_vacaciones, finiquito_vacaciones_imss, finiquito_prima_vacacional, finiquito_prima_imss, finiquito_total, finiquito_total_imss, finiquito_compensacion', 'numerical'),
			array('finiquito_observaciones, finiquito_fecha_registro', 'safe'),
			array('id_finiquito, id_empleado, finiquito_fecha_renuncia', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'rl_empleado' => array(self::BELONGS_TO, 'Empleados', 'id_empleado'),
			'rl_usuario' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id_finiquito' => 'ID',
			'id_empleado' => 'Empleado',
			'finiquito_fecha_renuncia' => 'Fecha de Renuncia',
			'finiquito_fecha_aguinaldo' => 'Fecha Inicio Aguinaldo',
			'finiquito_dias_proporcionales' => 'Dias Proporcionales',
			'finiquito_sueldo_diario' => 'Sueldo Diario Real',
			'finiquito_sueldo_semanal_real' => 'Sueldo Semanal Real',
			'finiquito_sueldo_semanal_imss' => 'Sueldo Semanal IMSS',
			'finiquito_aguinaldo_dias' => 'Dias de Aguinaldo',
			'finiquito_aguinaldo' => 'Aguinaldo Real',
			'finiquito_aguinaldo_imss' => 'Aguinaldo IMSS',
			'finiquito_dias_vacaciones' => 'Dias de Vacaciones',
			'finiquito_dias_pendientes' => 'Dias Pendientes Acumulados',
			'finiquito_pago_vacaciones' => 'Vacaciones Real',
			'finiquito_vacaciones_imss' => 'Vacaciones IMSS',
			'finiquito_prima_vacacional' => 'Prima Vacacional Real',
			'finiquito_prima_imss' => 'Prima Vacacional IMSS',
			'finiquito_total' => 'Total Finiquito Real',
			'finiquito_total_imss' => 'Total Finiquito IMSS',
			'finiquito_compensacion' => 'Compensacion',
			'finiquito_observaciones' => 'Observaciones',
			'id_usuario' => 'Usuario',
			'finiquito_fecha_registro' => 'Fecha Registro',
		);
	}

	/**
	 * Obtener dias de la tabla Art.76 LFT para un anio dado.
	 * Usa la misma logica de reforma LFT 2023 del modelo Empleados.
	 */
	private function getDiasTabla($anio, $fechaIngreso)
	{
		return Empleados::getDiasVacPorAnio($anio, $fechaIngreso);
	}

	/**
	 * Calcular finiquito completo basado en formulas VBA Hoja4.cls.
	 * Calcula IMSS y REAL, igual que las 2 columnas del Excel.
	 *
	 * VBA: IMSS semanal = INTEGRADO * 7 (columna 26 de EMPLEADOS)
	 * VBA: REAL semanal = SUELDO SEMANAL (columna 19 de EMPLEADOS)
	 *
	 * @param float|null $diasPendientes Dias pendientes de vacaciones (override).
	 *                                    Si es null, se calcula automaticamente.
	 */
	public function calcularFiniquito($diasPendientes = null)
	{
		$empleado = Empleados::model()->findByPk($this->id_empleado);
		if (empty($empleado)) return false;

		$fechaRenuncia = new DateTime($this->finiquito_fecha_renuncia);
		$fechaIngreso = new DateTime($empleado->empleado_fecha_ingreso);
		$fechaAguinaldo = new DateTime($this->finiquito_fecha_aguinaldo);

		// =============================================
		// SUELDOS (VBA formulas exactas)
		// C18 = BUSCARV(C4,EMPLEADOS!B$1:AA107,26,FALSO)*7 → INTEGRADO * 7
		// M18 = BUSCARV(M4,EMPLEADOS!B$1:Z100,19,FALSO)    → SUELDO SEMANAL REAL
		// =============================================
		$sueldoSemanalImss = round((float)$empleado->empleado_integrado * 7, 2);
		$sueldoSemanalReal = (float)$empleado->empleado_sueldo_semanal_real;

		$this->finiquito_sueldo_semanal_imss = $sueldoSemanalImss;
		$this->finiquito_sueldo_semanal_real = $sueldoSemanalReal;

		// VBA: C19 = C18/7, M19 = M18/7
		$diarioImss = $sueldoSemanalImss / 7;
		$diarioReal = $sueldoSemanalReal / 7;
		$this->finiquito_sueldo_diario = round($diarioReal, 2);

		// =============================================
		// 1. ULTIMO ANIVERSARIO Y DIAS PROPORCIONALES
		// VBA: ultimoAnioCumplido = DateSerial(Year(renuncia), Month(ingreso), Day(ingreso))
		// VBA: diferenciaDias = fechaRenuncia - ultimoAnioCumplido
		// =============================================
		$mesIngreso = (int)$fechaIngreso->format('m');
		$diaIngreso = (int)$fechaIngreso->format('d');
		$anioRenuncia = (int)$fechaRenuncia->format('Y');

		$diaReal = $diaIngreso;
		if ($mesIngreso == 2 && $diaIngreso == 29 && !checkdate(2, 29, $anioRenuncia)) {
			$diaReal = 28;
		}

		$ultimoAniversario = new DateTime($anioRenuncia . '-' . sprintf('%02d', $mesIngreso) . '-' . sprintf('%02d', $diaReal));
		if ($ultimoAniversario > $fechaRenuncia) {
			$anioAnterior = $anioRenuncia - 1;
			if ($mesIngreso == 2 && $diaIngreso == 29 && !checkdate(2, 29, $anioAnterior)) {
				$diaReal = 28;
			}
			$ultimoAniversario = new DateTime($anioAnterior . '-' . sprintf('%02d', $mesIngreso) . '-' . sprintf('%02d', $diaReal));
		}

		$diasProporcionales = (int)$fechaRenuncia->diff($ultimoAniversario)->days;
		$this->finiquito_dias_proporcionales = $diasProporcionales;

		// =============================================
		// 2. AGUINALDO
		// VBA: C20 = (E13 - C13) * (15/365)
		// VBA: C31 = C20 * C19 (dias × diario)
		// =============================================
		$diasAguinaldoPeriodo = (int)$fechaRenuncia->diff($fechaAguinaldo)->days;
		$aguinaldoDias = $diasAguinaldoPeriodo * (15 / 365);
		$this->finiquito_aguinaldo_dias = round($aguinaldoDias, 4);

		// Aguinaldo: dias × diario (sin redondear diario intermedio, como Excel)
		$this->finiquito_aguinaldo = round($aguinaldoDias * $diarioReal, 2);
		$this->finiquito_aguinaldo_imss = round($aguinaldoDias * $diarioImss, 2);

		// =============================================
		// 3. VACACIONES
		// VBA: antiguedad = EMPLEADOS columna M
		// VBA: valorBT = lookup antiguedad en BT:BU (tabla Art.76)
		// VBA: valorSumar = (valorBT / 365) * diferenciaDias
		// VBA: valorQ = EMPLEADOS columna Q (dias pendientes)
		// VBA: C26 = valorSumar + valorQ
		// =============================================
		$antiguedadAnios = (int)$fechaRenuncia->diff($fechaIngreso)->y;

		// Dias pendientes: usar override si se proporcionó, si no calcular dinamicamente
		// VBA: valorQ = EMPLEADOS columna Q (DIAS PTES = VACACIONES - DIAS TOMADOS)
		if ($diasPendientes !== null) {
			$pendientes = (float)$diasPendientes;
		} else {
			// Calcular acumuladas a la fecha de renuncia y restar tomados
			$vacAcumuladas = 0;
			for ($y = 1; $y <= $antiguedadAnios; $y++) {
				$vacAcumuladas += Empleados::getDiasVacPorAnio($y, $empleado->empleado_fecha_ingreso);
			}
			$pendientes = $vacAcumuladas - ((float)$empleado->empleado_dias_tomados + $empleado->getDiasTomados());
			if ($pendientes < 0) $pendientes = 0;
		}
		$this->finiquito_dias_pendientes = $pendientes;

		// valorBT: tabla para el SIGUIENTE anio de servicio (el que esta cursando)
		// Usa logica de reforma LFT 2023
		$valorBT = $this->getDiasTabla($antiguedadAnios + 1, $empleado->empleado_fecha_ingreso);

		// VBA: valorSumar = (valorBT / 365) * G12 (diferenciaDias)
		$proporcionalVac = ($valorBT / 365) * $diasProporcionales;

		// VBA: C26 = valorSumar + valorQ
		$diasVacaciones = round($proporcionalVac + $pendientes, 2);
		$this->finiquito_dias_vacaciones = $diasVacaciones;

		// VBA: C32 = C26 * C25 (dias × diario)
		$this->finiquito_pago_vacaciones = round($diasVacaciones * $diarioReal, 2);
		$this->finiquito_vacaciones_imss = round($diasVacaciones * $diarioImss, 2);

		// =============================================
		// 4. PRIMA VACACIONAL (25%)
		// VBA: C33 = C32 * 0.25
		// =============================================
		$this->finiquito_prima_vacacional = round($this->finiquito_pago_vacaciones * 0.25, 2);
		$this->finiquito_prima_imss = round($this->finiquito_vacaciones_imss * 0.25, 2);

		// =============================================
		// 5. TOTALES
		// VBA: C35 = SUMA(C31:C34) = aguinaldo + vacaciones + prima
		// =============================================
		$this->finiquito_total = round(
			$this->finiquito_aguinaldo + $this->finiquito_pago_vacaciones + $this->finiquito_prima_vacacional, 2
		);
		$this->finiquito_total_imss = round(
			$this->finiquito_aguinaldo_imss + $this->finiquito_vacaciones_imss + $this->finiquito_prima_imss, 2
		);

		// =============================================
		// 6. COMPENSACION = REAL - IMSS
		// VBA: J27 = SUMA(J24:J26) donde J24=M31-C31, J25=M32-C32, J26=M33-C33
		// =============================================
		$this->finiquito_compensacion = round($this->finiquito_total - $this->finiquito_total_imss, 2);

		return true;
	}

	protected function beforeSave()
	{
		if ($this->isNewRecord) {
			$this->finiquito_fecha_registro = date('Y-m-d H:i:s');
		}
		return parent::beforeSave();
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
