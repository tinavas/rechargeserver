<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Nazmul Hasan, Alamgir Kabir, Rashida sultana, Salma Khatun ">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content=""/>
        <title>Admin API</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>resources/css/superadmin/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>resources/css/superadmin/custom_styles.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>resources/css/superadmin/admin_menu.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/superadmin/zebra_datePicker.css" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/superadmin/zebra_datePicker_custom.css" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/superadmin/image-crop-styles.css" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/styles.css" type="text/css">        
        
        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery-ui.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/superadmin/zebra_datepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/superadmin/zebra_datepicker_core.js"></script>

        <!--<angular>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/angular/angular.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/angular/angular-animate.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/angular-bootstrap/ui-bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/bootstrap.min.js" ></script>
        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/superadmin/ng-google-chart.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/superadmin/image-crop.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/superadmin/dirPagination.js"></script>
        <!--<angular Services>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/services/serviceService.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/services/subscriberService.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/services/transctionService.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/services/historyService.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/services/companyInfoConfigurationService.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/services/simService.js"></script>
        <!--<angular Controller>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/controllers/serviceController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/controllers/subscriberController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/controllers/transctionController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/controllers/historyController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/controllers/imageCropController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/controllers/companyInfoConfigurationController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/controllers/simController.js"></script>
        <!--<angular Apps>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/apps/serviceApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/apps/subscriberApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/apps/transctionApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/apps/historyApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/apps/simApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/apps/companyInfoConfigurationApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/superadmin/apps/LoginAttemptApp.js"></script>

    </head>
    <body ng-app="<?php echo $app; ?>">
        <?php $this->load->view("superadmin/templates/sections/header"); ?>
        <div class="container" style="padding-top: 50px">
            <div class="row">
                <div class="col-md-2" >
                    <?php $this->load->view("superadmin/templates/sections/left_panel"); ?>
                </div>
                <div class="col-md-10">
                    <?php echo $contents; ?>
                </div>
            </div>
        </div>
    </body>
</html>
<?php $this->load->view('common/common_modal'); ?>