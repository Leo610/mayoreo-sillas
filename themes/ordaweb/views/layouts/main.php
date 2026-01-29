<!DOCTYPE html>
<html>

	<head>
		<?php
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerCoreScript('jquery.ui');
		?>

		<!-- Basic -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title>
			<?php echo CHtml::encode($this->pageTitle); ?> -
			<?php echo Yii::app()->name; ?> - ADMINISTRACION
		</title>

		<meta name="description" content="<?php echo CHtml::encode($this->pageDescription); ?>">


		<!-- Favicon -->
		<link rel="shortcut icon" href="<?php echo Yii::app()->baseUrl; ?>/images/favicon.png" type="image/x-icon" />
		<!-- <link rel="shortcut icon" href="<?php echo Yii::app()->baseUrl; ?>/images/favicon.ico" type="image/x-icon" /> -->

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light"
			rel="stylesheet" type="text/css">

		<!-- iconos botstrap -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


		<!-- Vendor CSS -->
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/bootstrap/css/bootstrap.css">
		<link rel="stylesheet"
			href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/font-awesome/css/font-awesome.css">
		<link rel="stylesheet"
			href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/simple-line-icons/css/simple-line-icons.css">
		<link rel="stylesheet"
			href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/owl.carousel/assets/owl.carousel.min.css">
		<link rel="stylesheet"
			href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/owl.carousel/assets/owl.theme.default.min.css">
		<link rel="stylesheet"
			href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/magnific-popup/magnific-popup.css">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/theme.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/theme-elements.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/theme-blog.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/theme-shop.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/theme-animate.css">

		<!-- Current Page CSS -->
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/css/settings.css"
			media="screen">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/css/layers.css"
			media="screen">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/css/navigation.css"
			media="screen">

		<!-- Current Page CSS -->
		<link rel="stylesheet"
			href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/circle-flip-slideshow/css/component.css"
			media="screen">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/nivo-slider/nivo-slider.css"
			media="screen">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/nivo-slider/default/default.css"
			media="screen">

		<link rel="stylesheet"
			href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/circle-flip-slideshow/css/component.css"
			media="screen">

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/css/skins/default.css">

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/css/customadmin.css">

		<!-- Head Libs -->
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/modernizr/modernizr.js"></script>


		<!--[if IE]>
			<link rel="stylesheet" href="css/ie.css">
		<![endif]-->

		<!--[if lte IE 8]>
			<script src="vendor/respond/respond.js"></script>
			<script src="vendor/excanvas/excanvas.js"></script>
		<![endif]-->

	</head>

	<body>
		<div class="body">
			<!-- INICIA ENCABEZADO -->
			<?php include_once 'header.php' ?>
			<!-- TERMINA ENCABEZADO -->

			<div role="main" class="main">

				<!-- INICIA HEADER PAGINA  -->
				<?php if (isset($this->breadcrumbs) and count($this->breadcrumbs) > 0): ?>
					<section class="page-header" style="height:40px;">
						<div class="col-md-12">
							<?php $this->widget(
								'zii.widgets.CBreadcrumbs',
								array(
									'homeLink' => CHtml::link('Inicio', array('/site/index')),
									'links' => $this->breadcrumbs,
								)
							); ?><!-- breadcrumbs -->
						</div>
						<!--
							<div class="row">
								<div class="col-md-12">
									<h1><?php echo CHtml::encode($this->pageTitle); ?></h1>
								</div>
							</div>
							-->
					</section>
				<?php endif // end if(isset($this->breadcrumbs) and count($this->breadcrumbs)>0): ?>
				<div class="container-fluid">
					<!-- <h1 class="page-title"><?= $this->pageTitle; ?><?= (isset($this->opcionestitulo) && $this->opcionestitulo != '') ? ' | ' . $this->opcionestitulo : ''; ?></h1> -->
					<?php foreach (Yii::app()->user->getFlashes() as $key => $message) {
						?>
						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-<?= $key; ?> alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<strong>
										<?= $message; ?>
									</strong>
								</div>
							</div>
						</div>
					<?php } // end foreach(Yii::app()->user->getFlashes() as $key => $message) ?>

					<!-- TERMINA HEADER PAGINA -->

					<!-- contenido -->

					<?php echo $content; ?>
					<!-- termina contenido -->
				</div>
			</div>
			<!-- INCIA FOOTER -->
			<?php include_once 'footer.php' ?>
			<!-- TERMINA FOOTER -->
		</div>

		<!-- Vendor -->
		<!--[if lt IE 9]>
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<![endif]-->
		<!--[if gte IE 9]><!-->

		<!--<![endif]-->
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jquery.appear/jquery.appear.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jquery.easing/jquery.easing.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jquery-cookie/jquery-cookie.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/common/common.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jquery.validation/jquery.validation.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jquery.stellar/jquery.stellar.js"></script>
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jquery.gmap/jquery.gmap.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jquery.lazyload/jquery.lazyload.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/isotope/jquery.isotope.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/owl.carousel/owl.carousel.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/vide/vide.js"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/theme.js"></script>

		<!-- Specific Page Vendor and Views -->
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/js/extensions/revolution.extension.actions.min.js"></script>
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/js/extensions/revolution.extension.carousel.min.js"></script>
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/js/extensions/revolution.extension.kenburn.min.js"></script>
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js"></script>
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/js/extensions/revolution.extension.migration.min.js"></script>
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/js/extensions/revolution.extension.navigation.min.js"></script>
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/js/extensions/revolution.extension.parallax.min.js"></script>
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/js/extensions/revolution.extension.slideanims.min.js"></script>
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/rs-plugin/js/extensions/revolution.extension.video.min.js"></script>

		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/circle-flip-slideshow/js/jquery.flipshow.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/views/view.home.js"></script>

		<!-- Theme Custom -->
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/custom.js"></script>

		<!-- Current Page Vendor and Views -->
		<script
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/circle-flip-slideshow/js/jquery.flipshow.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/nivo-slider/jquery.nivo.slider.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/views/view.home.js"></script>

		<!-- <script src="<? //php echo Yii::app()->theme->baseUrl; ?>/vendor/ckeditor/ckeditor.js"></script> -->
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/ckeditorroscator/ckeditor/ckeditor.js"></script>

		<!-- NOTIFY -->
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/notify/notify.min.js"></script>

		<!-- SELECT 2 -->
		<link rel="stylesheet" type="text/css"
			href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/select2/dist/css/select2.min.css" />
		<link rel="stylesheet" type="text/css"
			href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/select2/dist/css/select2-bootstrap.min.css" />
		<script type="text/javascript"
			src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/select2/dist/js/select2.full.min.js"></script>



		<!-- Data Table -->
		<!--<script type="text/javascript" src="https://cdn.datatables.net/s/dt/pdfmake-0.1.18,dt-1.10.10,b-1.1.0,b-html5-1.1.0,b-print-1.1.0/datatables.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/pdfmake-0.1.18,dt-1.10.10,b-1.1.0,b-html5-1.1.0,b-print-1.1.0/datatables.min.css"/>-->



		<link href="https://cdn.datatables.net/v/dt/dt-1.13.6/b-2.4.1/b-html5-2.4.1/b-print-2.4.1/datatables.min.css"
			rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
		<script
			src="https://cdn.datatables.net/v/dt/dt-1.13.6/b-2.4.1/b-html5-2.4.1/b-print-2.4.1/datatables.min.js"></script>

		<!-- encabezado fijo -->
		<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.0/css/fixedHeader.dataTables.min.css">
		<script src="https://cdn.datatables.net/fixedheader/3.2.0/js/dataTables.fixedHeader.min.js"></script>


		<!-- Theme Initialization Files -->
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/theme.init.js"></script>
		<!--<link id="skinStyle" rel='stylesheet' href='<?php echo Yii::app()->theme->baseUrl; ?>/assets/skins/indigo.min.css'>-->

	</body>

</html>