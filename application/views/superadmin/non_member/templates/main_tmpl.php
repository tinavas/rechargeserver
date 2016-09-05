<!DOCTYPE html>
<html lang="en" class="js no-flexbox canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Nazmul Hasan, Alamgir Kabir, Rashida sultana, Salma Khatun ">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content=""/>
        <title>Admin API</title>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>resources/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>resources/css/main.css">

        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php $this->load->view("superadmin/templates/sections/header"); ?>
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                </div>
                <div class="col-md-10 column">
                    <div class="row">
                        <?php echo $contents; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>