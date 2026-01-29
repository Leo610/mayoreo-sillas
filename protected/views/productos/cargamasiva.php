<?php
$this->pageTitle = 'Cargas masivas del sistema';

$this->pageDescription = '';
$this->breadcrumbs = array(
    $this->pageTitle
);
?>

<div class="panel">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <p class="lead mb-0">Carga masiva de productos, para conocer el formato es necesario descargar el formato
                base.</p>

            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="archivocsv" class="form-control"><br>
                <button type="submit" class="btn btn-success btn-block">Cargar archivo</button>
            </form>
        </div>
    </div>
</div>