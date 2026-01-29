<?php
$id = (isset($_GET['id_cliente'])) ? $_GET['id_cliente'] : '';
?>

<div style="padding-bottom: 10px;" class="container-fluid mb-2">
    <ul class="nav nav-pills">
        <li role="presentation" class="<?= ($opcionmenu == 1) ? 'active' : ''; ?>">
            <a href="<?php echo $this->createUrl('clientes/detalles?id_cliente=' . $id); ?>">Detalles del cliente</a>
        </li>
        <li role="presentation" class="<?= ($opcionmenu == 2) ? 'active' : ''; ?>">

            <a href="<?php echo $this->createUrl('cotizaciones/lista?id_cliente=' . $id); ?>">Cotizaciones</a>
        </li>
        <li role="presentation" class="<?= ($opcionmenu == 3) ? 'active' : ''; ?>">

            <a href="<?php echo $this->createUrl('proyectos/lista?id_cliente=' . $id); ?>">Pedidos</a>
        </li>
        <li role="presentation" class="<?= ($opcionmenu == 4) ? 'active' : ''; ?>">

            <a href="<?php echo $this->createUrl('contabilidadingresos/pendientespago?id_cliente=' . $id); ?>">Cuentas
                por cobrar</a>
        </li>
        <!-- <li role="presentation" class="<? //= ($opcionmenu == 5) ? 'active' : ''; ?>">

            <a href="<? //php echo $this->createUrl('contabilidadingresos/index?id_cliente=' . $id); ?>">Ingresos
                del cliente</a>
        </li> -->
    </ul>
</div>