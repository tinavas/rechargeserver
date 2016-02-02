<!DOCTYPE html>
<html>
    <head>
        <title><?php echo SITE_TITLE; ?></title>

        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="author" content="Nazmul Hasan, Alamgir Kabir, Rashida Sultana, Ridoy">
        <meta name="description" content="Social">
        <meta name="keywords" content=""/>
        <meta charset="UTF-8">

        <link href="<?php echo base_url(); ?>resources/images/money.ico" rel="icon" type="image/x-icon" id="page_favicon">
        <link href="<?php echo base_url(); ?>resources/css/datepicker.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>resources/css/chosen.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>resources/css/recharge.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>resources/css/menu.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>resources/css/styles.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>resources/css/bootstrap.min.css" rel="stylesheet">


        <script src="<?php echo base_url(); ?>resources/js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>resources/js/chosen.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>resources/js/datepicker.js" type="text/javascript"></script>    
        <script src="<?php echo base_url(); ?>resources/js/ajax_req.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>resources/js/smscounter.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>resources/js/recharge.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>resources/js/wating_loader.js" type="text/javascript"></script>

        <!--<angular>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/angular/angular.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/angular/angular-animate.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/angular-bootstrap/ui-bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/bootstrap.min.js" ></script>
        <!--<angular Services>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularService/transctionService.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularService/resellerService.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularService/paymentService.js"></script>

        <!--<angular Controller>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularController/transactionController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularController/resellerController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularController/paymentController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularController/leftController.js"></script>

        <!--<angular Apps>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularApp/transctionApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularApp/resellerApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularApp/paymentApp.js"></script>

    </head>
    <body ng-app="<?php echo $app; ?>">
        <div id="wrap">

            <!-- Fixed navbar -->
            <?php $this->load->view('templates/sections/header'); ?>
            <!--<div class="clrGap">&nbsp;</div>-->
            <div class="container-fluid mybody">
                <?php $this->load->view('templates/sections/left_pane'); ?>
                <div class="main">
                    <?php echo $contents; ?>
                </div>
                <div class="ieclear">&nbsp;</div>
            </div>
        </div><!--/end Wrap-->
        <?php $this->load->view('templates/sections/footer'); ?>
    </body>
</html>
<?php $this->load->view('common/common_modal'); ?>
<?php $this->load->view('wating_loader'); ?>