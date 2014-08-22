<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo SITE_NAME; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo $this->config->base_url(); ?>assets/css/<?php echo THEME; ?>.css" rel="stylesheet">
        <link href="<?php echo $this->config->base_url(); ?>assets/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
        <script src="<?php echo $this->config->base_url(); ?>assets/js/jquery.js"></script>
        <script>
            $(function () {
                $('input.tip, select.tip').tooltip({ placement: "right", trigger: "focus" });
                $('.tip').tooltip();
                $(".chzn-select").on("liszt:showing_dropdown", function () {
                    $(this).parents("div").css("overflow", "visible");
                });
                $(".chzn-select").on("liszt:hiding_dropdown", function () {
                    $(this).parents("div").css("overflow", "");
                });
                $("form select").chosen({no_results_text: "<?php echo $this->lang->line('no_results_matched'); ?>", disable_search_threshold: 5, allow_single_deselect:true });
                $('#note').redactor({
                    buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'image', 'video', 'link', '|', 'html'],
                    formattingTags: ['p', 'pre', 'h3', 'h4'],
                    imageUpload: '<?php echo site_url('module=home&view=image_upload'); ?>',
                    imageUploadErrorCallback: function(json)
                    {
                        bootbox.alert(json.error);
                    },
                    minHeight: 100
                });
                $('#internal_note').redactor({
                    buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'image', 'video', 'link', '|', 'html'],
                    formattingTags: ['p', 'pre', 'h3', 'h4'],
                    imageUpload: '<?php echo site_url('module=home&view=image_upload'); ?>',
                    imageUploadErrorCallback: function(json)
                    {
                        bootbox.alert(json.error);
                    },
                    minHeight: 100,
                    placeholder: '<?php echo $this->lang->line('internal_note'); ?>'
                });
                $('.redactor_toolbar a').tooltip({container: 'body'});
                $('.showSubMenus').click(function() {
                    var sub_menu = $(this).attr('href');
                    $('.sub-menu').slideUp('fast');
                    $('.menu').find("b").removeClass('caret-up').addClass('caret');
				
                    if ($(sub_menu).is(":hidden")) {
                        $(sub_menu).slideDown("slow");
                        $(this).find("b").removeClass('caret').addClass('caret-up');
                    } else {
                        $(sub_menu).slideUp();
                        $(this).find("b").removeClass('caret-up').addClass('caret');
                    }
                    return false;
                });
                $('.menu-collapse').click(function() {
                    $('#col_1').slideToggle();
                });

            });
            $(window).resize(function() {
                if($(document).width() > 980) {
                    $('#col_1').show();
                }
            });
        </script>
        <!--[if lt IE 9]>
              <script src="<?php echo $this->config->base_url(); ?>assets/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body background="<?php echo $this->config->base_url(); ?>assets/img/bg.gif">
        <div id="wrapper">
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner" style="padding-left:10px;">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                    <button type="button" class="btn btn-navbar menu-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                    <a class="brand" href="<?php echo $this->config->base_url(); ?>"><img src="<?php echo $this->config->base_url(); ?>assets/img/<?php echo LOGO; ?>" alt="<?php SITE_NAME?>" /></a>
                    <div class="nav-collapse collapse">
                        <ul class="nav pull-right">
                            <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hi, User! <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=auth&amp;view=login">Login</a></li>
                                </ul>
                            </li>
                            <li class="visible-desktop"><a class="external" href="http://www.tecdiary.net/support/pos-module/" target="_blank"><i class="icon-question-sign icon-white"></i></a></li>
                        </ul>
                        <ul class="nav pull-right">
                            <li><a class="hdate"><spam id="theTime"></span></a></li>
                            <li class="visible-desktop"><a href="index.php?module=home">Home</a></li>                    
                            <li class="divider-vertical"></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">

            <div class="row-fluid">
                <div class="span12">
                    <div id="artcar" class="carousel slide">
                        <ol class="carousel-indicators">
                            <li data-target="#artcar" data-slide-to="0" class="active"></li>
                            <li data-target="#artcar" data-slide-to="1" ></li>
                            <li data-target="#artcar" data-slide-to="2" ></li>
                            <li data-target="#artcar" data-slide-to="3" ></li>
                            <li data-target="#artcar" data-slide-to="4" ></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="item active"><img src="<?php echo $this->config->base_url(); ?>assets/img/a.png">
                                <div class="carousel-caption">
                                    <h4><?php echo $this->lang->line("welcome");?></h4>
                                    <p><?php echo SITE_NAME . "!";?></p>
                                </div>
                            </div>
                            <div class="item"><img src="<?php echo $this->config->base_url(); ?>assets/img/b.png">
                               <div class="carousel-caption">
                                    <h4><?php echo $this->lang->line("welcome");?></h4>
                                    <p><?php echo SITE_NAME . "!";?></p>
                                </div>
                            </div>
                            <div class="item"><img src="<?php echo $this->config->base_url(); ?>assets/img/c.png">
                               <div class="carousel-caption">
                                    <h4><?php echo $this->lang->line("welcome");?></h4>
                                    <p><?php echo SITE_NAME . "!";?></p>
                                </div>
                            </div>
                            <div class="item"><img src="<?php echo $this->config->base_url(); ?>assets/img/d.png">
                               <div class="carousel-caption">
                                    <h4><?php echo $this->lang->line("welcome");?></h4>
                                    <p><?php echo SITE_NAME . "!";?></p>
                                </div>
                            </div>
                            <div class="item"><img src="<?php echo $this->config->base_url(); ?>assets/img/e.png">
                               <div class="carousel-caption">
                                    <h4><?php echo $this->lang->line("welcome");?></h4>
                                    <p><?php echo SITE_NAME . "!";?></p>
                                </div>
                            </div>
                        </div>
                        <a class="left carousel-control" href="#artcar" data-slide="prev">
                            &lsaquo;
                        </a>
                        <a class="right carousel-control" href="#artcar" data-slide="next">
                            &rsaquo;
                        </a>                        
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div id="footer">
            <div class="container">
                <p class="credit">Copyright &copy; <?php echo date('Y'); ?> | <?php echo SITE_NAME; ?>
                <!--<a href="http://tecdiary.net/support/sma-guide/" target="_blank" class="tip" title="<?php echo $this->lang->line('help'); ?>"><i class="icon-question-sign"></i></a>-->
                </p>   
            </div>
        </div>

        <?php if (THEME == 'rtl') { ?>
            <script src="<?php echo $this->config->base_url(); ?>assets/js/bootstrap-rtl.js"></script> 
        <?php } else { ?>
            <script src="<?php echo $this->config->base_url(); ?>assets/js/bootstrap.min.js"></script> 
        <?php } ?>
        <script src="<?php echo $this->config->base_url(); ?>assets/js/bootstrap-fileupload.js"></script> 
        <script src="<?php echo $this->config->base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/js/jquery.dataTables.min.js"></script> 
        <script type="text/javascript" charset="utf-8" src="<?php echo $this->config->base_url(); ?>assets/media/js/ZeroClipboard.js"></script> 
        <script type="text/javascript" charset="utf-8" src="<?php echo $this->config->base_url(); ?>assets/media/js/TableTools.min.js"></script> 
        <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/js/DT_bootstrap.js"></script>
        <script src="<?php echo $this->config->base_url(); ?>assets/js/chosen.jquery.js"></script>
        <script src="<?php echo $this->config->base_url(); ?>assets/js/respond.js"></script>
        <script src="<?php echo $this->config->base_url(); ?>assets/js/rwd-table.js"></script>
        <script src="<?php echo $this->config->base_url(); ?>assets/js/redactor.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/js/bootbox.min.js"></script>
        <script>
        $('#artcar').carousel({
            interval: 5000,
            cycle: true
        });
        </script>

    </body></html>