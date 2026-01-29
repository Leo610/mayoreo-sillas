<!DOCTYPE html>
<html style=" box-shadow:none;">

    <head>
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
        <!-- Basic -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>
            <?php echo CHtml::encode($this->pageTitle); ?> -
            <?php echo Yii::app()->name; ?>
        </title>

        <meta name="description" content="<?php echo CHtml::encode($this->pageDescription); ?>">


        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo Yii::app()->baseUrl; ?>/images/favicon.png" type="image/x-icon" />

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Web Fonts  -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light"
            rel="stylesheet" type="text/css">

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

    <body style="background:url(<?php echo Yii::app()->theme->baseUrl; ?>/img/bg_login.jpg);background-position: center center;
    background-attachment: fixed; background-size: 100%;">
        <div class="body">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-8 p-lg" style="background-color:#FFF; border:2px solid #000; margin-top:15%;">
                        <h2 class="heading-primary center"><?= $this->pageTitle ?></h2>

                        <form class="form-horizontal" role="form" id="login-form" method="POST" action="">
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
                            <div class="form-group">
                                <label for="username" class="control-label sr-only">Usuario</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="text" placeholder="Email" name="LoginForm[Usuario_Email]"
                                            class="form-control">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    </div>
                                </div>
                            </div>
                            <label for="password" class="control-label sr-only">Contraseña</label>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="password" placeholder="Contraseña"
                                            name="LoginForm[Usuario_Password]" class="form-control">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="slug" value="<?= Yii::app()->session['basededatos'] ?>">
                            <button type="submit" name="yt0" id="set_login"
                                class="btn btn-primary btn-lg btn-block btn-login"><i
                                    class="fa fa-arrow-circle-o-right"></i> Entrar</button>
                        </form>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
            </div>
        </div>
    </body>
    <html>