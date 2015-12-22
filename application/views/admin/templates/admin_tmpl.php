<!DOCTYPE html>
<html>
    <head>
        <title><?php echo SITE_TITLE;?></title>

        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="author" content="A.K.M. NAZMUL ISLAM">
        <meta name="description" content="Social">
        <meta name="keywords" content=""/>
        <meta charset="UTF-8">

        <link href="<?php echo base_url();?>resources/images/money.ico" rel="icon" type="image/x-icon" id="page_favicon">
        <link href="<?php echo base_url();?>resources/css/datepicker.css" rel="stylesheet">
        <link href="<?php echo base_url();?>resources/css/chosen.css" rel="stylesheet">
        <link href="<?php echo base_url();?>resources/css/recharge.css" rel="stylesheet">
        <link href="<?php echo base_url();?>resources/css/menu.css" rel="stylesheet">
        <link href="<?php echo base_url();?>resources/css/styles.css" rel="stylesheet">
        <link href="<?php echo base_url();?>resources/css/bootstrap.min.css" rel="stylesheet">

        <script src="<?php echo base_url();?>resources/js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>resources/js/chosen.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>resources/js/datepicker.js" type="text/javascript"></script>    
        <script src="<?php echo base_url();?>resources/js/ajax_req.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>resources/js/smscounter.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>resources/js/recharge.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>resources/js/bootstrap.min.js" type="text/javascript"></script>


    </head>
    <body>
        <div id="wrap">
            <!-- Fixed navbar -->
            <?php $this->load->view('admin/templates/sections/header');?>
            <!--<div class="clrGap">&nbsp;</div>-->
            <div class="container-fluid mybody">
                <?php $this->load->view('admin/templates/sections/left_pane');?>

                <div class="main">
                    <?php echo $contents;?>
                </div>
                <div class="ieclear">&nbsp;</div>
            </div>
        </div><!--/end Wrap-->
        <?php $this->load->view('admin/templates/sections/footer');?>
    </body>
</html>