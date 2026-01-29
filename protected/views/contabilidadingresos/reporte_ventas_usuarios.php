<?php
$this->pageTitle = 'Reporte de ventas de usuarios';

$this->breadcrumbs = array(
    'Reporte de ventas de usuarios'
);
?>

<div class="row">
    <div class="col-md-8">
        <h1>
            <?= $this->pageTitle ?>
        </h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <fieldset>
                <legend>Filtros </legend>
                <div style="display: flex;align-items: end;">
                    <div class="col-md-3">
                        <p><b>Año</b></p>
                        <select class="form-control" name="anio-selected" style="background-color: #eee;">
                            <?php foreach ($aniosPedidos as $anioo) { ?>
                                <option value="<?php echo $anioo['anio'] ?>" <?php echo ($anioo['anio'] == $anioSeleccionado) ? 'selected' : '' ?>>
                                    <?php echo $anioo['anio'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <p><b>Usuario</b></p>
                        <select name="usuario_selected" class="form-control" style="background-color: #eee;">
                            <?php foreach ($usuarios as $usuario) { ?>
                                <option value="<?= $usuario['ID_Usuario'] ?>" <?php echo ($usuario['ID_Usuario'] == $idUsuario) ? 'selected' : '' ?>>
                                    <?= $usuario['Usuario_Nombre'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>

    <div class="col-md-12">
        <div style="display:inline-block;width: 100%;height:105vh">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>


<script src='<?= Yii::app()->baseUrl ?>/assets/chartjs/Chart.js'></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo",
            "Junio", "Julio", "Augusto", "Septiembre", "Octubere", "Noviembre", "Diciembre"
        ];

        let myChart = undefined;

        let anioSelect = document.querySelector("[name='anio-selected']")
        let usuarioSelect = document.querySelector("[name='usuario_selected']")

        anioSelect.addEventListener('change', actualizarDatosGrafica);
        usuarioSelect.addEventListener('change', actualizarDatosGrafica);

        function actualizarDatosGrafica() {
            let id_usuario = usuarioSelect.value
            let anio_seleccionado = anioSelect.value
            $.ajax({
                url: "<?php echo $this->createUrl("contabilidadingresos/reporteventasusuarios"); ?>",
                type: "POST",
                dataType: "json",
                timeout: (120 * 1000),
                data: {
                    ajax: 1,
                    id_usuario,
                    anio_seleccionado
                },
                success: function (Response, newValue) {
                    if (Response.requestresult == 'ok') {
                        crearChartBarStacked(Response.data, "myChart")
                        $.notify('Datos obtenidos con exito', 'success');
                    } else {
                        $.notify('Ocurrio un error inesperado', 'error');
                    }
                },
                error: function (e) {
                    $.notify('Ocurrio un error inesperado', 'error');
                }
            });
        }

        // creamos la grafica al iniciar la vista
        crearChartBarStacked(<?= $productosStackedPorMes ?>, "myChart")

        // @jl 23/05
        function crearChartBarStacked(arrReporte, selector) {
            const date = new Date();
            // const nombreMeses = meses.slice(0, 12)
            const nombreMeses = meses.slice(0, date.getMonth() + 1)

            let datasetsAct = [];
            for (let key in arrReporte) {
                let x = Math.floor(Math.random() * 256);
                let y = Math.floor(Math.random() * 256);
                let z = Math.floor(Math.random() * 256);
                let RGBColorBackground = "rgb(" + x + "," + y + "," + z + ", 0.2)";
                let RGBColorBorder = "rgb(" + x + "," + y + "," + z + ", 2)";

                let datasetcant = Object.values(arrReporte[key]).map(Number);
                datasetsAct.push({
                    label: key,
                    data: datasetcant,
                    backgroundColor: RGBColorBackground,
                    borderColor: RGBColorBorder,
                    borderWidth: 4
                });
            }

            //Generamos la gráfica
            let ctx = document.getElementById(selector).getContext('2d');
            if (myChart != undefined) {
                myChart.destroy();
            }
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: nombreMeses,
                    datasets: datasetsAct,
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true
                        }
                    },
                    plugins: {
                        tooltip: {
                            dispayColor: true,
                            mode: 'point',
                            callbacks: {
                                label: function (tooltipItem) {
                                    let producto = tooltipItem.dataset.label
                                    let dataIndex = tooltipItem.dataIndex
                                    let valor = tooltipItem.dataset.data[dataIndex];
                                    let chartInstance = this._chart;
                                    let total = 0;
                                    for (let i = 0; i < datasetsAct.length; i++) {
                                        if (chartInstance.isDatasetVisible(i)) {
                                            total += datasetsAct[i].data[dataIndex];
                                        }
                                    }
                                    return [producto + ": " + valor, "Total de esta barra : " + total];
                                }
                            }
                        },
                        legend: {
                            display: true,
                            position: 'left',
                            fullWidth: true,
                        },
                    }
                }
            });
        }
    })
</script>