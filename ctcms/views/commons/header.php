<!DOCTYPE html>
<html >

    <head>
        <meta charset="utf-8">
        <title><?php echo $page_title . " &middot; " . SITE_NAME; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link href="<?php echo $this->config->base_url(); ?>assets/css/msgnot.css" rel="stylesheet">
        <link href="<?php echo $this->config->base_url(); ?>assets/css/<?php echo THEME; ?>.css" rel="stylesheet">
        <script>
            var active = false;
            function changeVar()
            {
                active = false;
            }

            function set_interval()
            {
                timer = setInterval("changeVar()",<?php echo USER_ACTIVE_INTERVAL; ?>);
                //console.log(timer);
            }

            function reset_interval() {
                if (timer != 0) {
                    clearInterval(timer);
                    timer = 0;
                    active = true;
                    timer = setInterval("changeVar()",<?php echo USER_ACTIVE_INTERVAL; ?>);
                }
            }
        </script>
        <script src="<?php echo $this->config->base_url(); ?>assets/js/jquery.js"></script>

        <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/js/functions.js"></script>

        <script>
            var active = true;
            var timer = 0;
            $(document).ready(function() {
                $('#messages_btn').click(function() {
                    if ($('#mid').html() != 0) {
                        window.location = "<?php echo $this->config->site_url() ?>?module=messaging&view=get&inbox=1&unread=1";
                    }
                    else
                    {
                        window.location = "<?php echo $this->config->site_url() ?>?module=messaging&view=get&inbox=1&unread=0";
                    }

                });
<?php if (SERVER_CHK_INTERVAL > 0) { ?>
                    alerted = false;
                    window.setInterval(function() {
                        $.ajax({
                            type: 'GET',
                            url: '<?php echo $this->config->site_url(); ?>?module=messaging&view=check_msg&async=true',
                            cache: false,
                            data: {useractive: active},
                            dataType: "html",
                            success: function(data, textStatus, XMLHttpRequest) {
                                //console.log(textStatus);
                                if ((data != 0) && (data != $('#mid').html())) {
                                    if (!document.hasFocus()) {
                                        $("#soundNewNotification")[0].play();
                                    }
                                    $('#mid').attr('class', "notifications-number");
                                    $('#mid').html(data);
                                }
                                //console.log(data);
                                var element = document.getElementById('online_status').firstChild;
                                element.setAttribute("class", "btn btn-success hbtn");
                                element.innerText = element.textContent = "Online";
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                var element = document.getElementById('online_status').firstChild;
                                element.setAttribute("class", "btn btn-danger hbtn");
                                if (!alerted)
                                    bootbox.alert("Network error!,Server is unreachable.");
                                element.setAttribute("href", "<?php echo $this->config->site_url(); ?>");
                                element.innerText = element.textContent = "Offline";
                                alerted = true;
                            }
                        });
                    }, <?php echo SERVER_CHK_INTERVAL; ?>);
<?php } ?>
                $("#about").click(function() {
                    $("#aboutdiv").empty();
                    var total, tax_value, tax_value2, total_discount, count;
                    var twt = (total + tax_value + tax_value2) - total_discount;
                    count = count - 1;
                    twt = parseFloat(twt).toFixed(2);
                    console.log('clicked');
                    $('#paymentModal').modal();
                });

            });</script>
        <script>
            $(function() {
                $('input.tip, select.tip').tooltip({placement: "right", trigger: "focus"});
                $('.tip').tooltip();
                $(".chzn-select").on("liszt:showing_dropdown", function() {
                    $(this).parents("div").css("overflow", "visible");
                });
                $(".chzn-select").on("liszt:hiding_dropdown", function() {
                    $(this).parents("div").css("overflow", "");
                });
                $("form select").chosen({no_results_text: "<?php echo $this->lang->line('no_results_matched'); ?>", disable_search_threshold: 5, allow_single_deselect: true});
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
                if ($(document).width() > 980) {
                    $('#col_1').show();
                }
            });</script>
    <audio id="soundNewNotification">
        <source src="<?php echo $this->config->base_url(); ?>assets/sounds/soundNotification.ogg" type="audio/ogg">
        <source src="<?php echo $this->config->base_url(); ?>assets/sounds/soundNotification.mp3" type="audio/mpeg">
        <source src="<?php echo $this->config->base_url(); ?>assets/dolphin/sounds/soundNotification.wav" type="audio/wav">
    </audio>
    <!--[if lt IE 9]>
          <script src="<?php echo $this->config->base_url(); ?>assets/js/html5shiv.js"></script>
    <![endif]-->
</head>

<body background="<?php echo $this->config->base_url(); ?>assets/img/bg.gif"  onload="set_interval()"
      onmousemove="reset_interval()"
      onclick="reset_interval()"
      onkeypress="reset_interval()"
      onscroll="reset_interval()">

    <div id="wrap">
        <div id="paymentModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="paymentModalLabel"><?php echo $this->lang->line('about_soft'); ?></h3>
            </div>
            <div class="modal-body">
                <div>
                    <img src="<?php echo $this->config->base_url(); ?>assets/img/core_it.png">
                    <div >This Content Management system was made/modified by
                        CoreIT.<br>Copyright CoreIT  © 2014</div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line('close'); ?></button>
                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><?php echo $this->lang->line('ok'); ?></button>
            </div>
        </div>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <button type="button" class="btn btn-navbar menu-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="brand" href="<?php echo $this->config->base_url(); ?>"><img src="<?php echo $this->config->base_url(); ?>assets/img/<?php echo LOGO; ?>" alt="<?php echo SITE_NAME; ?>" /></a> 
                <ul class="hidden-desktop nav pull-right"><li><a class="hdate"> <?php echo date('l, F d, Y'); ?> </a></li></ul>
                <div class="nav-collapse collapse">
                    <ul class="nav pull-right">
                        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hi, <?php echo FIRST_NAME; ?>! <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=auth&amp;view=change_password"><?php echo $this->lang->line('change_password'); ?></a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=auth&amp;view=logout"><?php echo $this->lang->line('logout'); ?></a></li>
                            </ul>
                        </li>
                        <li class="divider-vertical"></li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="<?php echo base_url(); ?>assets/img/<?php echo LANGUAGE; ?>.png" style="margin-top:-1px" align="middle"></a>
                            <ul class="dropdown-menu pull-right" style="min-width: 60px;" role="menu" aria-labelledby="dLabel">
                                <?php
                                if ($handle = opendir('ctcms/language/')) {
                                    while (false !== ($entry = readdir($handle))) {
                                        if ($entry != "." && $entry != ".." && $entry != "index.html") {
                                            ?>
                                            <li><a href="<?php echo site_url('module=home&view=language&lang=' . $entry); ?>"><img src="<?php echo base_url(); ?>assets/img/<?php echo $entry; ?>.png" class="language-img"> &nbsp;&nbsp;<?php
                                                    if ($entry == 'bportuguese') {
                                                        echo "Brazilian Portuguese";
                                                    } elseif ($entry == 'eportuguese') {
                                                        echo "European Portuguese";
                                                    } else {
                                                        echo ucwords($entry);
                                                    }
                                                    ?></a></li><?php
                                        }
                                    }
                                    closedir($handle);
                                }
                                ?></ul></li>
<!--<li class="visible-desktop"><a href="http://www.tecdiary.net/support/sma-guide/" target="_blank"><i class="icon-question-sign icon-white"></i></a></li>-->
                    </ul>
                    <ul class="nav pull-right">
                        <li class="visible-desktop"><a class="hdate"><?php echo date('l, j F Y'); ?></a></li>
                        <li><a href="index.php?module=home"><?php echo $this->lang->line('home'); ?></a></li>
                        <li><a href="index.php?module=calendar"><?php echo $this->lang->line('calendar'); ?></a></li>
                        <?php if (SERVER_CHK_INTERVAL > 0) { ?>
                            <li id="online_status"><a href="#"  class="btn btn-success hbtn">Online</a></li>	

                            <?php if (ENABLE_MSG) { ?>
                                <li>
                                    <a id="messages_btn">
                                        <div class="menu_btn"  title="Messages">
                                            <img src="<?php echo $this->config->base_url(); ?>assets/img/message.png">     
                                            <span class="notificatons-number-container">
                                                <span id='mid'></span>
                                            </span>
                                        </div>
                                    </a>
                                </li>
                                <?php
                            }
                        }
                        ?>
                        <li class="divider-vertical"></li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="col_1">
            <div id="mainmanu">
                <?php if ($this->ion_auth->in_group(array('owner', 'admin', 'configuration', 'project'))) { ?>
                    <ul class="menu nav nav-tabs nav-stacked">
                        <li class="dropdown"><a class="showSubMenus" href="#customersMenu"><i class="icon-random icon-white"></i> <?php echo $this->lang->line('customers'); ?> <b class="caret"></b></a>
                            <ul class="nav nav-tabs nav-stacked sub-menu" id="customersMenu">
                                <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=customers"><?php echo $this->lang->line('list_customers'); ?></a></li>
                                <?php if (!$this->ion_auth->in_group('configuration')) { ?>
                                    <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=customers&amp;view=add"><?php echo $this->lang->line('new_customer'); ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="dropdown"><a class="showSubMenus" href="#branchMenu"><i class="icon-barcode icon-white"></i> <?php echo $this->lang->line('branches'); ?> <b class="caret"></b></a>
                            <ul class="nav nav-tabs nav-stacked sub-menu" id="branchMenu">
                                <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=branches"><?php echo $this->lang->line('list_branches'); ?></a></li>
                                <?php if (!$this->ion_auth->in_group('configuration')) { ?>
                                    <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=branches&amp;view=add"><?php echo $this->lang->line('add_branch'); ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php if (!$this->ion_auth->in_group(array('configuration', 'project'))) { ?>
                            <li class="dropdown"><a class="showSubMenus" href="#peopleMenu"><i class="icon-user  icon-white"></i> <?php echo $this->lang->line('people'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="peopleMenu">
                                    <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=auth&amp;view=users"><?php echo $this->lang->line('list_users'); ?></a></li>                                   
                                    <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=auth&amp;view=create_user"><?php echo $this->lang->line('new_user'); ?></a></li>

                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (ENABLE_MSG) { ?>
                            <li class="dropdown"><a class="showSubMenus" href="#msgMenu"><i class="icon-envelope  icon-white"></i> <?php echo $this->lang->line('messaging'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="msgMenu">                                                               
                                    <li class="divider"></li>

                                    <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=messaging"><?php echo $this->lang->line('send_msg'); ?></a></li>
                                    <li><a href="<?php echo $this->config->site_url() ?>?module=messaging&view=get&inbox=1&unread=0"><?php echo $this->lang->line('inbox'); ?></a></li>
                                    <li><a href="<?php echo $this->config->site_url() ?>?module=messaging&view=get&inbox=0&unread=0"><?php echo $this->lang->line('outbox'); ?></a></li>
                                    <?php if ($this->ion_auth->in_group(array('admin', 'owner'))) { ?>
                                        <li><a href="<?php echo $this->config->site_url() ?>?module=messaging&view=clear"><?php echo $this->lang->line('clr_msg'); ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (ENABLE_LOGGING) { ?>
                            <li class="dropdown"><a class="showSubMenus" href="#logMenu"><i class="icon-info-sign  icon-white"></i> <?php echo $this->lang->line('logging'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="logMenu">
                                    <li><a id="loglist" href="<?php echo $this->config->base_url(); ?>index.php?module=logging&view=list_logs"><?php echo $this->lang->line('list_logs'); ?></a></li>
                                    <li><a id="loglist" href="<?php echo $this->config->base_url(); ?>index.php?module=logging&view=clear_logs"><?php echo $this->lang->line('clr_logs'); ?></a></li>
                                    <li><a id="loglist" href="<?php echo $this->config->base_url(); ?>index.php?module=logging&view=overview"><?php echo $this->lang->line('log_stat'); ?></a></li>

                                </ul>
                            </li>
                        <?php } ?> 
                        <?php if ($this->ion_auth->in_group(array('owner', 'admin'))) { ?>
                            <li class="dropdown"><a class="showSubMenus" href="#settingsMenu"><i class="icon-cog  icon-white"></i> <?php echo $this->lang->line('settings'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="settingsMenu">
                                    <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=settings&amp;view=system_setting"><?php echo $this->lang->line('system_setting'); ?></a></li>
                                    <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=settings&amp;view=change_logo"><?php echo $this->lang->line('chnage_logo'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=settings&amp;view=backup_database"><?php echo $this->lang->line('backup_database'); ?></a></li>
                                </ul>
                            </li>

                        <?php } ?>


                        <li class="dropdown"><a class="showSubMenus" href="#loggingMenu"><i class="icon-tasks  icon-white"></i> <?php echo $this->lang->line('about'); ?> <b class="caret"></b></a>
                            <ul class="nav nav-tabs nav-stacked sub-menu" id="loggingMenu">
                                <li  class="pg"><a id="about" href="#aboutdiv"><?php echo $this->lang->line('about_soft'); ?></a></li>
                            </ul>
                        </li>
                    </ul>
                <?php } ?>

                <?php if ($this->ion_auth->in_group('viewer')) { ?>
                    <ul class="menu nav nav-tabs nav-stacked">
                        <li class="dropdown"><a class="showSubMenus" href="#userMenu"><i class="icon-tasks  icon-white"></i> <?php echo $this->lang->line('menus'); ?> <b class="caret"></b></a>
                            <ul class="nav nav-tabs nav-stacked sub-menu" id="userMenu" style="display:block;">
                                <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=branches"><?php echo $this->lang->line('list_branches'); ?></a></li>
                                <li><a href="<?php echo $this->config->base_url(); ?>index.php?module=customers"><?php echo $this->lang->line('list_customers'); ?></a></li>

                            </ul>
                        </li>
                    </ul>
                <?php } ?>
            </div>
        </div>
        <div id="contenitore_col_2">
            <div id="col_2">
                <div class="main-content">
                    <div class="row-fluid">
