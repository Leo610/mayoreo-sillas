<!DOCTYPE html>
<html>

    <head>
        <title>Transferencia #
            <?= $transferencia['id']; ?>
        </title>
        <style>
            * {
                margin: 0;
                padding: 0;
                font-family: 'Open Sans", Arial, sans-serif';
                font-size: 9pt;
                color: #000;
            }

            body {
                width: 100%;
                font-family: 'Open Sans", Arial, sans-serif';
                font-size: 9pt;
                margin: 0;
                padding: 0;
                text-transform: uppercase;
            }

            tr,
            td,
            table,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            p {
                text-transform: uppercase;
            }

            p {
                margin: 0;
                padding: 0;
            }

            #wrapper {
                width: 210mm;
                margin: 5 5mm;
            }

            .page {
                height: 297mm;
                width: 210mm;
                page-break-after: always;
            }

            table {
                border-left: 1px solid #ccc;
                border-top: 1px solid #ccc;

                border-spacing: 0;
                border-collapse: collapse;

            }

            table td {
                border-right: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                padding: 2mm;
            }

            table.heading {
                height: 50mm;
            }

            h1.heading {
                font-size: 13pt;
                color: #8c6635;
                font-weight: normal;
            }

            h2.heading {
                font-size: 10pt;
                color: #000;
                font-weight: 900;
            }

            hr {
                color: #ccc;
                background: #ccc;
            }

            #invoice_body {
                min-height: 149mm;
            }

            #invoice_body,
            #invoice_total {
                width: 100%;
            }

            #invoice_body table,
            #invoice_total table {
                width: 100%;
                border-left: 1px solid #ccc;
                border-top: 1px solid #ccc;

                border-spacing: 0;
                border-collapse: collapse;

                margin-top: 5mm;
            }

            #invoice_body table td,
            #invoice_total table td {
                text-align: center;
                font-size: 8pt;
                border-right: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                padding: 2mm 0;
            }

            #invoice_body table td.mono,
            #invoice_total table td.mono {
                text-align: right;
                padding-right: 3mm;
                font-size: 10pt;
            }

            #footer {
                width: 180mm;
                margin: 0 15mm;
                padding-bottom: 3mm;
            }

            #footer table {
                width: 100%;
                border-spacing: 0;
            }

            #footer table td {
                width: 25%;
                text-align: center;
                font-size: 8pt;
            }

            .img-responsive {
                height: 20mm !important;
            }
        </style>
    </head>

    <body>
        <div id="wrapper">
            <br>
            <table class="heading" style="width:100%; border:0;">
                <tr>
                    <td style="width:40mm;border:0; text-align:right;  vertical-align: bottom;">
                        <?php
                        if ($transferencia['idUsuariocrea']['bodega'] == '402') {
                            $path = Yii::app()->basePath . '/../companias/LOGO_ROSCATOR.png';
                            $direccion = 'Segunda Avenida No 321, Colonia Guerra, Guadalupe Nuevo León C.P. 67140';
                            $direccionDatos = 'E-MAIL: ventas3@ritubulares.mx WEB: www.ritubulares.mx Cel. 81-34027209, Tel. 81-81358299';
                            $tituloEmpresa = 'ROSCATOR INDUSTRIAL, <br>SA DE CV';
                            $subtitle = 'Lideres en Proyectos Industriales y Mobiliario';
                        } else {
                            $path = Yii::app()->basePath . '/../companias/2IND_OVI_MED.png';
                            $direccion = $DatosConfiguracion['direccion'];
                            $direccionDatos = 'E-MAIL: ' . $DatosConfiguracion['correo'] . 'WEB: ' . $DatosConfiguracion['web'] . 'Tels: ' . $DatosConfiguracion['telefonos'];
                            $tituloEmpresa = $DatosConfiguracion['nombre_compania'];
                            $subtitle = 'Excelencia e Innovación Industrial';
                        }

                        $img = file_get_contents($path);
                        // Encode the image string data into base64
                        $data = base64_encode($img);
                        ?>
                        <img src="data:image/jpeg;base64,<?= $data ?>" alt="" style="width: 130px; height: auto;">

                    </td>

                    <td style="width:120mm;border:0;">
                        <h1 style="text-align: center;word-break: break-all;"><b style="font-size: 30px;">
                                <?= $tituloEmpresa ?>
                            </b></h1><br>
                        <b>
                            <p style="text-align: center; color: #8c663b; font-size: 16px; font-weight: 900;">
                                <?= $subtitle ?>
                            </p>
                        </b>
                    </td>

                    <td class="logotipo" style="width:40mm;border:0;">
                        <!-- <?= $this->ObtenerLogotipo(); ?> -->
                        <?php
                        $img = file_get_contents(Yii::app()->basePath . '/../companias/8131-2f766e5e-b985-4241-89d7-dc2842c14757.jpeg');
                        // Encode the image string data into base64
                        $data = base64_encode($img);
                        ?>
                        <img src="data:image/jpeg;base64,<?= $data ?>" alt="" style="width: 155px; height: auto;">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:center; border:0">
                        <b>
                            <p style=" font-weight: 900; font-size:12px;">
                                <?= $direccion ?>
                            </p>
                        </b>
                        <b>
                            <p style="font-weight: 900; font-size:12px;">
                                <?= $direccionDatos ?>
                            </p>
                        </b>

                    </td>
                </tr>
                <br>

                <tr>
                    <td colspan="2" style="border: 0;">

                    </td>

                    <td colspan="1" style="border: 0;">


                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center; border: 0;">


                    </td>
                </tr>
            </table>
            <div id="content" style="margin-right: 25px;">
                <div id="invoice_body">
                    <h1>Transferencia #
                        <?= $transferencia['id'] ?> de
                        <?= $transferencia['idSucursalorigen']['nombre'] ?> a
                        <?= $transferencia['idSucursaldestino']['nombre'] ?>.
                    </h1>

                    <table>
                        <tr>
                            <td style="background-color: #eee;font-weight: bold"># Transferencia</td>
                            <td style="background-color: #eee;font-weight: bold">Sucursal de Origen</td>
                            <td style="background-color: #eee;font-weight: bold">Sucursal de Destino</td>
                            <td style="background-color: #eee;font-weight: bold">Fecha</td>
                            <td style="background-color: #eee;font-weight: bold">Generó</td>
                            <td style="background-color: #eee;font-weight: bold">Usuario Solicita</td>
                            <td style="background-color: #eee;font-weight: bold">Estatus</td>
                            <td style="background-color: #eee;font-weight: bold">Prioridad</td>

                        </tr>
                        <tr>
                            <td class="text-danger font-weight-bold">
                                <?= $transferencia['id'] ?>
                            </td>
                            <td class="font-weight-bold">
                                <?= $transferencia['idSucursalorigen']['nombre'] ?>
                            </td>
                            <td class="font-weight-bold">
                                <?= $transferencia['idSucursaldestino']['nombre'] ?>
                            </td>
                            <td class="font-weight-bold">
                                <?= $transferencia['fecha_solicitud'] ?>
                            </td>
                            <td class="font-weight-bold">
                                <?= $transferencia['idUsuariocrea']['Usuario_Nombre'] ?>
                            </td>
                            <td class="font-weight-bold">
                                <?= $transferencia['idUsuariosolicita']['Usuario_Nombre'] ?>
                            </td>
                            <td class="font-weight-bold">
                                <?php
                                $estatus = $this->EstatusTR($transferencia['estatus']);
                                echo $estatus['badge']
                                    ?>
                            </td>
                            <td class="font-weight-bold">
                                <?php if ($transferencia['tipo'] == 0) {
                                    echo '<span class="badge badge-default">NORMAL</span>';
                                } elseif ($transferencia['tipo'] == 1) {
                                    echo '<span class="badge badge-danger">URGENTE</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #eee;font-weight: bold">Comentarios</td>
                            <td width="100%" colspan="7">
                                <?= $transferencia['comentarios'] ?>
                            </td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <th style="background-color: #eee;">Producto(s)</th>
                            <th style="background-color: #eee;">Clave</th>

                            <th style="background-color: #eee;">Unitario</th>
                            <th style="background-color: #eee;">IVA</th>
                            <th style="background-color: #eee;">Subtotal</th>
                            <th style="background-color: #eee;">Cantidad</th>
                            <th style="background-color: #eee;">Total</th>
                            <th style="background-color: #eee;">C. por salir</th>
                            <th style="background-color: #eee;">C. por recibir</th>
                        </tr>
                        <?php foreach ($conceptos as $rows) { ?>
                            <tr>
                                <td>
                                    <?= $rows['idProducto']['producto_nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['idProducto']['producto_clave'] ?>
                                </td>
                                <td>$
                                    <?= number_format($rows['unitario'], 2) ?>
                                </td>
                                <td>$
                                    <?= number_format($rows['iva'], 2) ?>
                                </td>
                                <td>$
                                    <?= number_format($rows['subtotal_unitario'], 2) ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= $rows['cantidad'] ?>
                                </td>
                                <td>$
                                    <?= number_format($rows['total'], 2) ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= $rows['cantidad_por_salir'] ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= $rows['cantidad_pendiente'] ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                    <table>
                        <tr>
                            <td width="50%" align="center">
                                <?= $this->numtoletras($transferencia['total']); ?>
                            </td>
                            <td width="50%" style="border-left: 1px solid #0000">
                                <table id="tabladetotales" style="border: 0px solid #000000;" width="100%"
                                    cellspacing="0" cellpadding="5">
                                    <tr>
                                        <th colspan="2" class="text-center" style="background-color: #eee;">Totales</th>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #eee;">SubTotal</td>
                                        <td>$
                                            <?= number_format($transferencia['subtotal'], 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #eee;">IVA</td>
                                        <td>$
                                            <?= number_format($transferencia['iva'], 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #eee;">Total</td>
                                        <td>$
                                            <?= number_format($transferencia['total'], 2) ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
    </body>

</html>