<?php

class RhNominaDetalles extends RActiveRecord
{
	public function getDbConnection()
	{
		return self::getAdvertDbConnection();
	}

	public function tableName()
	{
		return 'rh_nomina_detalles';
	}

	public function rules()
	{
		return array(
			array('id_periodo, id_empleado', 'required'),
			array('dias_trabajados, faltas, retardos, minutos_retardo, horas_lv, horas_sabado, horas_domingo, total_horas', 'safe'),
			array('sueldo_nominal, premio_asistencia, premio_puntualidad, premio_productividad, bono_condicional, prima_vacacional, total_percepciones', 'safe'),
			array('isr, imss, infonavit, otra_deduccion, otra_deduccion_desc, total_deducciones', 'safe'),
			array('vales, efectivo, total_neto, bono_condicional_aprobado, nomina_observaciones', 'safe'),
		);
	}

	public function relations()
	{
		return array(
			'rl_empleado' => array(self::BELONGS_TO, 'Empleados', 'id_empleado'),
			'rl_periodo' => array(self::BELONGS_TO, 'RhNominaPeriodos', 'id_periodo'),
		);
	}

	/**
	 * Tablas de horas proporcionales (equivalente a L_A_V, HORAS_SABADO, HORAS_DOMINGO del Excel)
	 * Para semana de 5 dias laborales: 56 horas totales (5×8 + proporcional sab + dom)
	 * Sabado y Domingo proporcionales = (dias_trabajados / max_dias) × 8
	 */
	public static function getHorasLV($diasTrabajados)
	{
		return $diasTrabajados * 8;
	}

	public static function getHorasSabado($diasTrabajados, $maxDias = 5)
	{
		if ($maxDias <= 0) return 0;
		return ($diasTrabajados / $maxDias) * 8;
	}

	public static function getHorasDomingo($diasTrabajados, $maxDias = 5)
	{
		if ($maxDias <= 0) return 0;
		return ($diasTrabajados / $maxDias) * 8;
	}

	/**
	 * Calcula la nomina de este empleado para el periodo
	 * Logica basada en OrganizarAsistenciaFormatoConRetardos del Excel VBA
	 */
	public function calcularNomina()
	{
		$empleado = $this->rl_empleado;
		if (empty($empleado)) return;

		$id_periodo = $this->id_periodo;
		$id_empleado = $this->id_empleado;

		// Obtener registros de asistencia del periodo
		$asistencias = RhAsistencia::model()->findAll(
			'id_periodo = :per AND id_empleado = :emp',
			array(':per' => $id_periodo, ':emp' => $id_empleado)
		);

		// Contar dias laborales (no DESCANSO ni FESTIVO), faltas y retardos
		$maxDiasTrabajo = 0;
		$faltas = 0;
		$retardos = 0;
		$minutosRetardo = 0;

		foreach ($asistencias as $a) {
			if ($a->asistencia_tipo == 'DESCANSO' || $a->asistencia_tipo == 'FESTIVO') {
				continue;
			}
			// Es un dia laboral (NORMAL, FALTA, VACACION, JUSTIFICADO)
			$maxDiasTrabajo++;

			if ($a->asistencia_tipo == 'FALTA') {
				$faltas++;
			}
			if ($a->asistencia_tipo == 'NORMAL' && $a->asistencia_retardo) {
				$retardos++;
				$minutosRetardo += $a->asistencia_minutos_retardo;
			}
		}

		// VBA: dias_trabajados = 5 - faltas (o maxDiasTrabajo - faltas si hay festivos)
		$diasTrabajados = $maxDiasTrabajo - $faltas;

		// Horas proporcionales (L_A_V, HORAS_SABADO, HORAS_DOMINGO del Excel)
		$horasLV = self::getHorasLV($diasTrabajados);
		$horasSab = self::getHorasSabado($diasTrabajados, $maxDiasTrabajo);
		$horasDom = self::getHorasDomingo($diasTrabajados, $maxDiasTrabajo);
		$totalHoras = $horasLV + $horasSab + $horasDom;

		// Guardar asistencia
		$this->dias_trabajados = $diasTrabajados;
		$this->faltas = $faltas;
		$this->retardos = $retardos;
		$this->minutos_retardo = $minutosRetardo;
		$this->horas_lv = round($horasLV, 2);
		$this->horas_sabado = round($horasSab, 2);
		$this->horas_domingo = round($horasDom, 2);
		$this->total_horas = round($totalHoras, 2);

		// === PERCEPCIONES ===
		// VBA: sueldo_diario = sueldo_semanal / 7
		$sueldoDiario = $empleado->empleado_sueldo_semanal / 7;
		// VBA: sueldo_por_hora = sueldo_diario / 8
		$sueldoPorHora = $sueldoDiario / 8;

		// Tarifas por hora de bonos (VBA: bono / 56 siempre)
		$horasTotalesRef = 56;
		$asistPorHora = $horasTotalesRef > 0 ? $empleado->empleado_bono_asistencia / $horasTotalesRef : 0;
		$puntPorHora = $horasTotalesRef > 0 ? $empleado->empleado_bono_puntualidad / $horasTotalesRef : 0;
		$prodPorHora = $horasTotalesRef > 0 ? $empleado->empleado_bono_productividad / $horasTotalesRef : 0;

		// VBA: sueldo_nominal = total_horas × sueldo_por_hora
		$this->sueldo_nominal = round($totalHoras * $sueldoPorHora, 2);

		// VBA: premios = total_horas × tasa_por_hora
		$premioAsistencia = round($totalHoras * $asistPorHora, 2);
		$premioPuntualidad = round($totalHoras * $puntPorHora, 2);
		$premioProductividad = round($totalHoras * $prodPorHora, 2);

		// === LOGICA DE REBAJAS (VBA: columna 24 EMPLEADOS) ===
		$aplicaRebaja = ($empleado->empleado_rebaja_bono != 'NO');

		if ($aplicaRebaja) {
			if ($faltas > 0) {
				// Con faltas: rebaja automatica de $100 en asistencia y puntualidad
				$premioAsistencia = max(0, $premioAsistencia - 100);
				$premioPuntualidad = max(0, $premioPuntualidad - 100);
			} elseif ($retardos > 0) {
				// Con retardos sin faltas: depende de aprobacion del bono condicional
				if ($this->bono_condicional_aprobado !== 'SI') {
					// No aprobado o sin respuesta: rebaja de $100
					$premioAsistencia = max(0, $premioAsistencia - 100);
					$premioPuntualidad = max(0, $premioPuntualidad - 100);
				}
				// Si aprobado = 'SI': se mantienen los premios originales
			}
			// Sin faltas ni retardos: premios originales (sin cambio)
		}

		$this->premio_asistencia = $premioAsistencia;
		$this->premio_puntualidad = $premioPuntualidad;
		$this->premio_productividad = $premioProductividad;

		// VBA: bono_condicional siempre inicia en 0
		$this->bono_condicional = 0;

		// === PRIMA VACACIONAL (VBA columna 71) ===
		// Se paga cuando el aniversario de ingreso cae dentro del periodo
		// Formula: (sueldo_semanal / 7) × dias_vacaciones_por_antiguedad × 0.25
		$this->prima_vacacional = 0;
		$periodo = $this->rl_periodo;
		if (!empty($periodo) && !empty($empleado->empleado_fecha_ingreso)) {
			$fechaIngreso = $empleado->empleado_fecha_ingreso;
			$mesDiaIngreso = substr($fechaIngreso, 5); // MM-DD
			$anioInicio = (int)date('Y', strtotime($periodo->periodo_fecha_inicio));
			$anioFin = (int)date('Y', strtotime($periodo->periodo_fecha_fin));

			// Verificar aniversario en cada año que cubre el periodo
			$aniversarioCae = false;
			$aniosAntiguedad = 0;
			for ($anio = $anioInicio; $anio <= $anioFin; $anio++) {
				$fechaAniversario = $anio . '-' . $mesDiaIngreso;
				// Validar que la fecha sea real (ej: 29 feb en año no bisiesto)
				if (!strtotime($fechaAniversario)) continue;
				if ($fechaAniversario >= $periodo->periodo_fecha_inicio && $fechaAniversario <= $periodo->periodo_fecha_fin) {
					$ingDt = new DateTime($fechaIngreso);
					$anivDt = new DateTime($fechaAniversario);
					$diff = $anivDt->diff($ingDt);
					$aniosAntiguedad = $diff->y;
					if ($aniosAntiguedad >= 1) {
						$aniversarioCae = true;
					}
					break;
				}
			}

			if ($aniversarioCae) {
				$tablaVac = RhVacacionesTabla::model()->find(
					'anios_antiguedad = :anios',
					array(':anios' => $aniosAntiguedad)
				);
				$diasVac = !empty($tablaVac) ? (int)$tablaVac->dias_vacaciones : 32;
				$this->prima_vacacional = round(($empleado->empleado_sueldo_semanal / 7) * $diasVac * 0.25, 2);
			}
		}

		// Total percepciones = nominal + asist + punt + prod + bono_condicional + prima_vacacional
		$this->total_percepciones = $this->sueldo_nominal
			+ $this->premio_asistencia
			+ $this->premio_puntualidad
			+ $this->premio_productividad
			+ $this->bono_condicional
			+ $this->prima_vacacional;

		// === DEDUCCIONES ===
		// VBA: ISR = VLOOKUP(sueldo,ISR,2,0) / 7 * BA  donde BA = total_horas / 8
		// BA = 7 para semana completa (56/8), 5.6 con 1 falta, etc.
		$diasProrrateo = $totalHoras > 0 ? $totalHoras / 8 : 0;
		$this->isr = round(($empleado->empleado_isr / 7) * $diasProrrateo, 2);
		// VBA: IMSS = VLOOKUP(B,EMPLEADOS,23,0) / 7 * BA
		$this->imss = round(($empleado->empleado_imss / 7) * $diasProrrateo, 2);
		// VBA: INFONAVIT = monto fijo (sin prorrateo)
		$this->infonavit = $empleado->empleado_infonavit;

		$this->total_deducciones = round((float)$this->isr + (float)$this->imss + (float)$this->infonavit + (float)$this->otra_deduccion, 2);

		// VBA: efectivo = total_percepciones - total_deducciones
		$this->efectivo = round((float)$this->total_percepciones - (float)$this->total_deducciones, 2);
		// VBA: total_neto = efectivo (menos vales si aplica)
		$this->total_neto = round((float)$this->efectivo - (float)$this->vales, 2);
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
