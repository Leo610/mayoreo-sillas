<?php
$this->pageTitle = 'Reporte de ingresos';

$this->breadcrumbs = array(
    'Reporte de ingresos'
);
?>

<div class="row">
    <div class="col-md-8">
        <h1>
            Reporte de ingresos
        </h1>
    </div>
    <div class="col-md-12">
        <fieldset>
            <legend>Filtros</legend>
            <div class="row" style="display: flex;align-items: end;">
                <div class="col-md-3">
                    <p><b>Año</b></p>
                    <select class="form-control" name="anio-selected" id="">
                        <?php foreach ($aniosIngresos as $anioo) { ?>
                            <option value="<?php echo $anioo['anio'] ?>" <?php echo ($anioo['anio'] == $anioSelected) ? 'selected' : '' ?>><?php echo $anioo['anio'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="col-md-12">
        <div style="display:inline-block;width: 100%;height:65vh">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>


<script src='<?= Yii::app()->baseUrl ?>/assets/chartjs/Chart.js'></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo",
            "Junio", "Julio", "Augusto", "Septiembre", "Octubere", "Noviembre", "Diciembre"
        ];

        let myChartIngresos = undefined;
        document.querySelector("[name='anio-selected']").addEventListener('change', function() {
            let anioSelected = this.value;
            var jqxhr = $.ajax({
                url: "<?php echo $this->createUrl("contabilidadingresos/ReporteIngresosCostos"); ?>",
                type: "POST",
                dataType: "json",
                timeout: (120 * 1000),
                data: {
                    anioSelected,
                    ajax: 1
                },
                success: function(Response, newValue) {
                    if (Response.requestresult == 'ok') {
                        crearChartBar(Response.data, "myChart", ["Ingresos", "Costos"])
                        $.notify('Datos obtenidos con exito', 'success');
                    } else {
                        $.notify('Ocurrio un error inesperado', 'error');
                    }
                },
                error: function(e) {
                    $.notify('Ocurrio un error inesperado', 'error');
                }
            });
        })

        crearChartBar(<?= $data ?>, "myChart", ["Ingresos", "Costos"])
        // @jl 05/12/23 


        function crearChartBar(data, selector, labels) {
            const date = new Date();
            const nombreMesesServicios = meses.slice(0, date.getMonth() + 1)

            let arrayBackgroundColores = [];
            let arrayBordersColores = [];

            for (let key in data) {
                let x = Math.floor(Math.random() * 256);
                let y = Math.floor(Math.random() * 256);
                let z = Math.floor(Math.random() * 256);
                var RGBColorBackground = "rgb(" + x + "," + y + "," + z + ", 0.2)";
                var RGBColorBorder = "rgb(" + x + "," + y + "," + z + ", 2)";
                arrayBackgroundColores.push(RGBColorBackground)
                arrayBordersColores.push(RGBColorBorder)
            }

            if (myChartIngresos != undefined) {
                myChartIngresos.destroy();
            }

            let ctx = document.getElementById(selector).getContext('2d');
            myChartIngresos = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: nombreMesesServicios,
                    datasets: data.map((elem, index) => {
                        return {
                            label: labels[index],
                            data: elem,
                            backgroundColor: arrayBackgroundColores[index],
                            borderColor: arrayBordersColores[index],
                            borderWidth: 1
                        }
                    }),
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    tooltips: {
                        dispayColor: true,
                        callback: {
                            mode: 'x'
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    })
</script>