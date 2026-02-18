<?php

/**
 * Model class for table "rh_empleados".
 *
 * @property integer $id_empleado
 * @property string $empleado_num_reloj
 * @property string $empleado_num_empleado
 * @property string $empleado_nombre
 * @property string $empleado_fecha_ingreso
 * @property string $empleado_estatus
 * @property double $empleado_sueldo_semanal
 * @property double $empleado_sueldo_semanal_real
 * @property double $empleado_sueldo_diario
 * @property string $empleado_seguro_social
 * @property string $empleado_rfc
 * @property string $empleado_puesto
 * @property string $empleado_tarjeta_efectivale
 * @property string $empleado_habitante_casa
 * @property double $empleado_imss
 * @property double $empleado_infonavit
 * @property double $empleado_integrado
 * @property double $empleado_costo_hora
 * @property double $empleado_bono_asistencia
 * @property double $empleado_bono_puntualidad
 * @property double $empleado_bono_productividad
 * @property double $empleado_bono_condicional
 * @property double $empleado_dias_pendientes
 * @property double $empleado_dias_tomados
 * @property integer $empleado_dias_prima_vacacional
 * @property string $empleado_fecha_baja
 * @property string $empleado_observaciones
 * @property string $empleado_fecha_alta_registro
 * @property string $empleado_ultima_modificacion
 * @property string $empleado_requiere_checador
 * @property integer $id_usuario
 */
class Empleados extends RActiveRecord
{
	public function getDbConnection()
	{
		return self::getAdvertDbConnection();
	}

	public function tableName()
	{
		return 'rh_empleados';
	}

	public function rules()
	{
		return array(
			array('empleado_nombre, empleado_fecha_ingreso', 'required'),
			array('id_usuario', 'numerical', 'integerOnly' => true),
			array('empleado_sueldo_semanal, empleado_sueldo_semanal_real, empleado_sueldo_diario, empleado_imss, empleado_infonavit, empleado_isr, empleado_integrado, empleado_costo_hora, empleado_bono_asistencia, empleado_bono_puntualidad, empleado_bono_productividad, empleado_bono_condicional, empleado_dias_pendientes, empleado_dias_tomados, empleado_dias_prima_vacacional', 'numerical'),
		array('empleado_rebaja_bono', 'in', 'range' => array('SI', 'NO')),
			array('empleado_requiere_checador', 'in', 'range' => array('SI', 'NO')),
			array('empleado_num_reloj, empleado_num_empleado, empleado_seguro_social, empleado_rfc', 'length', 'max' => 20),
			array('empleado_nombre', 'length', 'max' => 255),
			array('empleado_puesto', 'length', 'max' => 100),
			array('empleado_tarjeta_efectivale', 'length', 'max' => 50),
			array('empleado_habitante_casa', 'length', 'max' => 100),
			array('empleado_estatus', 'in', 'range' => array('ACTIVO', 'INACTIVO')),
			array('empleado_fecha_ingreso, empleado_fecha_baja, empleado_observaciones, empleado_fecha_alta_registro, empleado_ultima_modificacion, empleado_rfc, empleado_puesto, empleado_tarjeta_efectivale, empleado_habitante_casa, empleado_rebaja_bono, empleado_requiere_checador, empleado_dias_pendientes, empleado_dias_tomados, empleado_dias_prima_vacacional', 'safe'),
			array('id_empleado, empleado_num_reloj, empleado_num_empleado, empleado_nombre, empleado_fecha_ingreso, empleado_estatus, empleado_sueldo_semanal, empleado_sueldo_semanal_real, empleado_sueldo_diario, empleado_seguro_social, empleado_rfc, empleado_puesto, empleado_tarjeta_efectivale, empleado_habitante_casa, empleado_imss, empleado_infonavit, empleado_isr, empleado_rebaja_bono, empleado_requiere_checador, empleado_integrado, empleado_costo_hora, empleado_bono_asistencia, empleado_bono_puntualidad, empleado_bono_productividad, empleado_bono_condicional, empleado_dias_pendientes, empleado_dias_tomados, empleado_dias_prima_vacacional, empleado_fecha_baja, empleado_observaciones, id_usuario', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'rl_usuario' => array(self::BELONGS_TO, 'Usuarios', 'id_usuario'),
			'rl_historial' => array(self::HAS_MANY, 'RhEmpleadosHistorial', 'id_empleado', 'order' => 'historial_fecha_registro DESC'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id_empleado' => 'ID',
			'empleado_num_reloj' => 'Num. Reloj',
			'empleado_num_empleado' => 'Num. Empleado',
			'empleado_nombre' => 'Nombre',
			'empleado_fecha_ingreso' => 'Fecha de Ingreso',
			'empleado_estatus' => 'Estatus',
			'empleado_sueldo_semanal' => 'Sueldo Semanal (IMSS)',
			'empleado_sueldo_semanal_real' => 'Sueldo Semanal Real',
			'empleado_sueldo_diario' => 'Sueldo Diario (IMSS)',
			'empleado_seguro_social' => 'Seguro Social (NSS)',
			'empleado_rfc' => 'RFC',
			'empleado_puesto' => 'Puesto',
			'empleado_tarjeta_efectivale' => 'Tarjeta Efectivale',
			'empleado_habitante_casa' => 'Habitante en Casa',
			'empleado_imss' => 'IMSS',
			'empleado_infonavit' => 'INFONAVIT',
			'empleado_isr' => 'ISR Semanal',
			'empleado_rebaja_bono' => 'Aplica Rebaja de Bono',
			'empleado_integrado' => 'Salario Diario Integrado',
			'empleado_costo_hora' => 'Costo por Hora',
			'empleado_requiere_checador' => 'Requiere Checador',
			'empleado_bono_asistencia' => 'Bono Asistencia',
			'empleado_bono_puntualidad' => 'Bono Puntualidad',
			'empleado_bono_productividad' => 'Bono Productividad',
			'empleado_bono_condicional' => 'Bono Condicional',
			'empleado_dias_pendientes' => 'Dias Vac. Pendientes',
			'empleado_dias_tomados' => 'Dias Vac. Tomados',
			'empleado_dias_prima_vacacional' => 'Dias Prima Vacacional',
			'empleado_fecha_baja' => 'Fecha de Baja',
			'empleado_observaciones' => 'Observaciones',
			'empleado_fecha_alta_registro' => 'Fecha Alta Registro',
			'empleado_ultima_modificacion' => 'Ultima Modificacion',
			'id_usuario' => 'Usuario',
		);
	}

	/**
	 * Calcular antiguedad en anios completos
	 */
	public function getAntiguedad()
	{
		if (empty($this->empleado_fecha_ingreso)) return 0;
		$ingreso = new DateTime($this->empleado_fecha_ingreso);
		$hoy = new DateTime();
		$diff = $hoy->diff($ingreso);
		return $diff->y;
	}

	/**
	 * Obtener dias de vacaciones segun antiguedad (LFT Mexico)
	 */
	public function getDiasVacaciones()
	{
		$antiguedad = $this->getAntiguedad();
		if ($antiguedad < 1) return 0;
		$tabla = RhVacacionesTabla::model()->find(
			'anios_antiguedad = :anios',
			array(':anios' => $antiguedad)
		);
		if (!empty($tabla)) {
			return $tabla->dias_vacaciones;
		}
		return 32;
	}

	/**
	 * Calcular dias de vacaciones proporcionales desde ultimo aniversario
	 */
	public function getDiasVacacionesProporcionales()
	{
		$diasVac = $this->getDiasVacaciones();
		if ($diasVac == 0) return 0;
		$ingreso = new DateTime($this->empleado_fecha_ingreso);
		$hoy = new DateTime();
		$antiguedad = $this->getAntiguedad();
		$ultimoAniversario = clone $ingreso;
		$ultimoAniversario->modify('+' . $antiguedad . ' years');
		$diasDesdeAniversario = $hoy->diff($ultimoAniversario)->days;
		return round(($diasVac / 365) * $diasDesdeAniversario, 1);
	}

	/**
	 * Obtener total de dias de vacaciones tomados (aprobados)
	 */
	public function getDiasTomados()
	{
		$total = Yii::app()->db->createCommand()
			->select('COALESCE(SUM(vacacion_dias),0)')
			->from('rh_vacaciones')
			->where('id_empleado=:id AND vacacion_estatus="APROBADA"',
					array(':id' => $this->id_empleado))
			->queryScalar();
		return (int)$total;
	}

	/**
	 * Total dias tomados: importados del Excel + registrados en el sistema
	 */
	public function getTotalDiasTomados()
	{
		return (float)$this->empleado_dias_tomados + $this->getDiasTomados();
	}

	/**
	 * Dias pendientes: acumuladas - total tomados (Excel + sistema)
	 */
	public function getDiasPendientes()
	{
		$pendientes = $this->getVacacionesAcumuladas() - $this->getTotalDiasTomados();
		return $pendientes < 0 ? 0 : $pendientes;
	}

	/**
	 * Obtener dias disponibles para solicitar vacaciones
	 */
	public function getDiasDisponibles()
	{
		return $this->getDiasPendientes();
	}

	/**
	 * Dias de vacaciones para un anio de servicio especifico,
	 * considerando la reforma LFT enero 2023.
	 * Si el aniversario de ese anio cae >= 2023-01-01, usa tabla NUEVA.
	 * Si cae antes, usa tabla VIEJA (pre-reforma).
	 *
	 * VIEJA: 1=6, 2=8, 3=10, 4=12, 5=14, 6-10=16, 11-15=18, 16-20=20, 21-25=22, 26-30=24, 31+=26
	 * NUEVA: 1=12, 2=14, 3=16, 4=18, 5=20, 6-10=22, 11-15=24, 16-20=26, 21-25=28, 26-30=30, 31+=32
	 */
	public static function getDiasVacPorAnio($anioServicio, $fechaIngreso)
	{
		$ingreso = new DateTime($fechaIngreso);
		$aniversario = clone $ingreso;
		$aniversario->modify('+' . $anioServicio . ' years');

		$reforma = new DateTime('2023-01-01');
		$usarNueva = ($aniversario >= $reforma);

		if ($usarNueva) {
			if ($anioServicio <= 5) {
				$base = array(1=>12, 2=>14, 3=>16, 4=>18, 5=>20);
				return $base[$anioServicio];
			}
			if ($anioServicio <= 10) return 22;
			if ($anioServicio <= 15) return 24;
			if ($anioServicio <= 20) return 26;
			if ($anioServicio <= 25) return 28;
			if ($anioServicio <= 30) return 30;
			return 32;
		} else {
			if ($anioServicio <= 5) {
				$base = array(1=>6, 2=>8, 3=>10, 4=>12, 5=>14);
				return $base[$anioServicio];
			}
			if ($anioServicio <= 10) return 16;
			if ($anioServicio <= 15) return 18;
			if ($anioServicio <= 20) return 20;
			if ($anioServicio <= 25) return 22;
			if ($anioServicio <= 30) return 24;
			return 26;
		}
	}

	/**
	 * Total de dias de vacaciones acumulados en todos los anios completados.
	 * Usa la logica de reforma LFT 2023 (vieja vs nueva tabla segun fecha aniversario).
	 */
	public function getVacacionesAcumuladas()
	{
		$antiguedad = $this->getAntiguedad();
		if ($antiguedad < 1) return 0;
		$total = 0;
		for ($y = 1; $y <= $antiguedad; $y++) {
			$total += self::getDiasVacPorAnio($y, $this->empleado_fecha_ingreso);
		}
		return $total;
	}

	/**
	 * Dias pendientes = acumulados - tomados (calculado dinamicamente)
	 * empleado_dias_tomados es el valor historico importado del Excel.
	 */
	public function getDiasPendientesCalculado()
	{
		$acumulados = $this->getVacacionesAcumuladas();
		$tomados = (float)$this->empleado_dias_tomados;
		$pendientes = $acumulados - $tomados;
		return $pendientes > 0 ? $pendientes : 0;
	}

	/**
	 * Proporcional del anio actual, usando dias del SIGUIENTE anio de servicio
	 * con logica de reforma LFT 2023.
	 */
	public function getProporcionalActual()
	{
		if (empty($this->empleado_fecha_ingreso)) return 0;
		$antiguedad = $this->getAntiguedad();
		$siguienteAnio = $antiguedad + 1;

		$diasTabla = self::getDiasVacPorAnio($siguienteAnio, $this->empleado_fecha_ingreso);

		$ingreso = new DateTime($this->empleado_fecha_ingreso);
		$hoy = new DateTime();
		$ultimoAniversario = clone $ingreso;
		$ultimoAniversario->modify('+' . $antiguedad . ' years');
		if ($ultimoAniversario > $hoy) {
			if ($antiguedad > 0) {
				$ultimoAniversario->modify('-1 year');
			} else {
				return 0;
			}
		}
		$diasDesde = $hoy->diff($ultimoAniversario)->days;
		return round(($diasTabla / 365) * $diasDesde, 2);
	}

	/**
	 * Dias para prima vacacional = dias de vacaciones del ultimo anio completado.
	 * Se calcula dinamico con la logica de reforma LFT 2023.
	 */
	public function getDiasPrimaVacacional()
	{
		$antiguedad = $this->getAntiguedad();
		if ($antiguedad < 1) return 0;
		return self::getDiasVacPorAnio($antiguedad, $this->empleado_fecha_ingreso);
	}

	/**
	 * Paga de prima vacacional.
	 * Se calcula como: dias_prima * sueldo_diario * 0.25
	 * Solo es valida si el aniversario cae en el periodo actual de nomina (Vie-Jue).
	 */
	public function getPagaPrima()
	{
		$diasPrima = $this->getDiasPrimaVacacional();
		if ($diasPrima <= 0) return 0;
		$diario = (float)$this->empleado_sueldo_semanal_real / 7;
		return round($diasPrima * $diario * 0.25, 2);
	}

	/**
	 * Verificar si el aniversario del empleado cae en la semana actual (Vie-Jue).
	 * Usa 'today' para evitar problemas de hora en comparaciones DateTime.
	 */
	public function isAniversarioEnPeriodo()
	{
		if (empty($this->empleado_fecha_ingreso)) return false;
		$ingreso = new DateTime($this->empleado_fecha_ingreso);
		$hoy = new DateTime('today'); // medianoche, sin hora

		// Encontrar el viernes anterior (inicio del periodo Vie-Jue)
		$inicioSemana = clone $hoy;
		$diaSemana = (int)$hoy->format('N'); // 1=Lun, 5=Vie, 7=Dom
		if ($diaSemana != 5) {
			$inicioSemana->modify('last friday');
		}
		$finSemana = clone $inicioSemana;
		$finSemana->modify('+6 days'); // jueves

		// Construir fecha de aniversario en el anio actual
		$mesIngreso = (int)$ingreso->format('m');
		$diaIngreso = (int)$ingreso->format('d');
		$anioActual = (int)$hoy->format('Y');
		if (!checkdate($mesIngreso, $diaIngreso, $anioActual)) {
			return false;
		}
		$aniversario = new DateTime($anioActual . '-' . sprintf('%02d', $mesIngreso) . '-' . sprintf('%02d', $diaIngreso));

		return ($aniversario >= $inicioSemana && $aniversario <= $finSemana);
	}

	/**
	 * Verificar si la prima vacacional ya fue calculada/pagada en la nomina del periodo actual.
	 */
	public function isPrimaYaPagada()
	{
		$hoy = new DateTime('today');
		$diaSemana = (int)$hoy->format('N');
		$inicioSemana = clone $hoy;
		if ($diaSemana != 5) {
			$inicioSemana->modify('last friday');
		}
		$finSemana = clone $inicioSemana;
		$finSemana->modify('+6 days');

		$periodo = RhNominaPeriodos::model()->find(
			'periodo_fecha_inicio = :fi AND periodo_fecha_fin = :ff',
			array(':fi' => $inicioSemana->format('Y-m-d'), ':ff' => $finSemana->format('Y-m-d'))
		);
		if (!$periodo) return false;

		$det = RhNominaDetalles::model()->find(
			'id_periodo = :per AND id_empleado = :emp AND prima_vacacional > 0',
			array(':per' => $periodo->id_periodo, ':emp' => $this->id_empleado)
		);
		return !empty($det);
	}

	protected function beforeSave()
	{
		if ($this->isNewRecord) {
			$this->empleado_fecha_alta_registro = date('Y-m-d H:i:s');
		}
		$this->empleado_ultima_modificacion = date('Y-m-d H:i:s');
		return parent::beforeSave();
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id_empleado', $this->id_empleado);
		$criteria->compare('empleado_num_reloj', $this->empleado_num_reloj, true);
		$criteria->compare('empleado_num_empleado', $this->empleado_num_empleado, true);
		$criteria->compare('empleado_nombre', $this->empleado_nombre, true);
		$criteria->compare('empleado_fecha_ingreso', $this->empleado_fecha_ingreso, true);
		$criteria->compare('empleado_estatus', $this->empleado_estatus, true);
		$criteria->compare('empleado_sueldo_semanal', $this->empleado_sueldo_semanal);
		$criteria->compare('empleado_sueldo_diario', $this->empleado_sueldo_diario);
		$criteria->compare('empleado_seguro_social', $this->empleado_seguro_social, true);
		return new CActiveDataProvider($this, array('criteria' => $criteria));
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
