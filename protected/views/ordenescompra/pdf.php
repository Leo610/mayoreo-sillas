<!DOCTYPE html>
<html>

    <head>
        <title>ORDEN DE COMPRA
            <?= $ordendecompra->id ?> /
            <?= date('Y', strtotime($ordendecompra->fecha_alta)) ?>
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
                /* border-left: 1px solid #ccc; */
                /* border-top: 1px solid #ccc; */

                /* background: #eee; */

                border-spacing: 0;
                /* border-collapse: collapse; */
            }

            #footer table td {
                width: 25%;
                text-align: center;
                font-size: 8pt;
                /* border-right: 1px solid #ccc; */
                /* border-bottom: 1px solid #ccc; */
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
                        if ($ordendecompra['idUsuariocrea']['bodega'] == '402' || $ordendecompra['idUsuariocrea']['bodega'] == '677') {
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


                        // $img = file_get_contents(Yii::app()->getbaseUrl(true) . '/companias/2IND_OVI_MED.png');
                        $img = file_get_contents($path);
                        // Encode the image string data into base64
                        $data = base64_encode($img);
                        ?>
                        <img src="data:image/jpeg;base64,<?= $data ?>" alt="" style="width: 130px; height: auto;">
                    </td>
                    <td style="width:120mm;border:0;">
                        <h1 style="text-align: center;"><b style="font-size: 30px;">
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
                        <?php if ($ordendecompra['idUsuariocrea']['bodega'] == '402' && $ordendecompra['idUsuariocrea']['bodega'] == '677') { ?>
                            <img src="data:image/jpeg;base64,<?= $data ?>" alt="" style="width: 155px; height: auto;">
                        <?php } ?>
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
            </table>
            <div id="content" style="margin-right: 25px;">
                <div id="invoice_body">
                    <h2 class="heading">Orden de Compra #
                        <?= $ordendecompra['id'] ?> de
                        <?= $ordendecompra['idProveedor']['proveedor_nombre'] ?>.
                    </h2>
                    <table id="conceptos" width="100%" style="font-size: 8pt;border: 1px solid #000000 " cellpadding="5"
                        cellspacing="0">
                        <tr>
                            <td style="background-color: #eee;font-weight: bold"># Orden de Compra</td>
                            <td class="text-danger font-weight-bold">
                                <?= $ordendecompra['id'] ?>
                            </td>
                            <td style="background-color: #eee;font-weight: bold">Sucursal</td>
                            <td class="font-weight-bold">
                                <?= $ordendecompra['idSucursal']['nombre'] ?>
                            </td>
                            <td style="background-color: #eee;font-weight: bold">Usuario Solicita</td>
                            <td class="font-weight-bold">
                                <?= $ordendecompra['idUsuariosolicita']['Usuario_Nombre'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #eee;font-weight: bold">Fecha</td>
                            <td class="font-weight-bold">
                                <?= $ordendecompra['fecha_alta'] ?>
                            </td>
                            <td style="background-color: #eee;font-weight: bold">Generó</td>
                            <td class="font-weight-bold">
                                <?= $ordendecompra['idUsuariocrea']['Usuario_Nombre'] ?>
                            </td>
                            <td style="background-color: #eee;font-weight: bold">Proveedor</td>
                            <td class="font-weight-bold">
                                <?= $ordendecompra['idProveedor']['proveedor_nombre'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #eee;font-weight: bold">Estatus</td>
                            <td class="font-weight-bold">
                                <?php
                                $estatus = $this->EstatusOC($ordendecompra['estatus']);
                                echo $estatus['badge']
                                    ?>
                            </td>
                            <td style="background-color: #eee;font-weight: bold">Prioridad</td>
                            <td class="font-weight-bold">
                                <?php if ($ordendecompra['tipo_oc'] == 0) {
                                    echo '<span class="badge badge-default">NORMAL</span>';
                                } elseif ($ordendecompra['tipo_oc'] == 1) {
                                    echo '<span class="badge badge-danger">URGENTE</span>';
                                }
                                ?>
                            </td>
                            <td style="background-color: #eee;font-weight: bold"></td>
                            <td style="background-color: #eee;font-weight: bold"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #eee;font-weight: bold">Comentarios</td>
                            <td width="100%" colspan="10">
                                <?= $ordendecompra['comentarios'] ?>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table id="listaproductos" width="100%"
                        style="font-size: 10px;    border: 1px solid #000000 ;    text-align: left;border-collapse: collapse;"
                        cellpadding="3">
                        <tr>
                            <th style="background-color: #eee;">Producto(s)</th>
                            <th style="background-color: #eee;">Clave</th>
                            <!-- <th style="background-color: #eee;">U.M.</th> -->
                            <th style="background-color: #eee;">Unitario</th>
                            <th style="background-color: #eee;">IVA</th>
                            <th style="background-color: #eee;">Subtotal</th>
                            <th style="background-color: #eee;">Cantidad</th>
                            <th style="background-color: #eee;">Total</th>
                        </tr>
                        <?php foreach ($conceptos as $rows) { ?>
                            <tr>
                                <td>
                                    <?= $rows['concepto'] ?>
                                </td>
                                <td>
                                    <?= $rows['idProducto']['producto_clave'] ?>
                                </td>
                                <!-- <td><? //=$rows['idProducto']['idUm']['unidades_medida_nombre']
                                    ?></td> -->
                                <td>$
                                    <?= number_format($rows['unitario'], 2) ?>
                                </td>
                                <td>$
                                    <?= number_format($rows['iva'], 2) ?>
                                </td>
                                <td>$
                                    <?= number_format($rows['subtotal_unitario'], 2) ?>
                                </td>
                                <td>
                                    <?= $rows['cantidad'] ?>
                                </td>
                                <td>$
                                    <?= number_format($rows['total'], 2) ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                    <br>
                    <table style="border: 1px solid #000000;" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" align="center">
                                <?= $this->numtoletras($ordendecompra['total']); ?>
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
                                            <?= number_format($ordendecompra['subtotal'], 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #eee;">IVA</td>
                                        <td>$
                                            <?= number_format($ordendecompra['iva'], 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #eee;">Total</td>
                                        <td>$
                                            <?= number_format($ordendecompra['total'], 2) ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
    </body>

</html>