<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title><?php echo $site_info['title']?></title>
        <meta name="author" content="Nazmul Hasan, Alamgir Kabir, A.K.M. NAZMUL ISLAM">
        <meta name="description" content="Social">
        <meta name="keywords" content=""/>
        <meta charset="UTF-8">
        <link href="<?php echo base_url()?>resources/images/money.ico" rel="icon" type="image/x-icon" id="page_favicon">
        <link href="<?php echo base_url()?>resources/css/main.css" rel="stylesheet">
        <link href="<?php echo base_url()?>resources/css/default.css" rel="stylesheet">
        <link href="<?php echo base_url()?>resources/css/font-awesome.css" rel="stylesheet">

        <script src="<?php echo base_url()?>resources/js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url()?>resources/js/bootstrap.min.js" type="text/javascript"></script>
    </head>
    <body id="ez-page-home" class="en ez-main-background">
        <?php $this->load->view('nonmember/templates/sections/header');?>
        <section>
            <?php echo $contents;?>
        </section>
        <?php $this->load->view('nonmember/templates/sections/footer');?>
        <script type="text/javascript">
            //<!--
            function autoResize(id) {
                var newheight;
                var newwidth;

                if (document.getElementById) {
                    newheight = document.getElementById(id).contentWindow.document.body.scrollHeight;
                    newwidth = document.getElementById(id).contentWindow.document.body.scrollWidth;
                }

                document.getElementById(id).height = (newheight) + "px";
                document.getElementById(id).width = (newwidth) + "px";
            }
            //-->
        </script>



    </body>
</html>