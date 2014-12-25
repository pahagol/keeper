<!DOCTYPE html>
<html>
    <head>
        <base href="<?= base_url(); ?>"/>
        <meta charset="utf-8" />
        <title>Partnerka 2.0</title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="robots" content="index,follow" />
        <link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
        <link rel="Stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.20.custom.css"  />
        <!--<link rel="stylesheet" type="text/css" href="markitup/skins/markitup/style.css" />
        <link rel="stylesheet" type="text/css" href="markitup/sets/default/style.css" /> -->
        <!-- JavaScript -->
        <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>
        <script type="text/javascript" src="js/my.js"></script>
        <script>
            jQuery(document).ready(function($){
                $(".filter_form").submit(function(e){
                    e.preventDefault();
                    var url="<?= isset($submit_url) ? $submit_url : site_url() . $this->uri->slash_segment(1) . $this->uri->segment(2) ?>";
                    var arr=new Array();
                    $(this).find("select,input[type=text],input[type=checkbox]:checked,input[type=hidden]").each(function(i){
                        if($(this).val()!=""){
                            arr.push($(this).attr("name"));
                            arr.push($(this).val());
                        }
                    });
                    var param_str=arr.join("/");
                    location.href=url+"/"+param_str;
                });
                
                //$("span.editable").after("");
                $("select.combobox").combobox();
                
                $("<input type='button' value='Ред.'/>").insertAfter("span.editable").bind("click",set_edit);
                
                function set_edit(){
                    var defval=$(this).prev("span.editable").text();
                    $(this).prev("span.editable").hide();
                    $(this).before("<input type='text' value='"+defval+"'/>");
                    //$("<input type='button' value='Отм.'/>").insertAfter($(this)).bind("click",function());
                    $(this).val("Сохр.");
                    $(this).unbind("click").bind("click",{defval:defval},set_save);
                }
                
                function set_save(e){
                    var defval=e.data.defval;
                    var curval=$(this).prev("input").val();
                    if(defval!=curval){
                        $(this).prev("input").attr("disabled", "disabled");
                        var curel=$(this); 
                        var form=$(this).parents("form.editable_form");
                        var post_path=$("input[name='post_path']",form).val();
                        var id=$("input[name='id']",form).val();
                        var elname=$(this).prevAll("span.editable").attr("name");
                        $.post(post_path,{id:id,name:elname,val:curval},function(d){
                            //alert(d);
                            curel.val("Ред.");
                            curel.prev("input").remove();
                            curel.prev("span.editable").text(curval).show();
                            curel.unbind("click").bind("click",set_edit);
                            curel.prev("input").removeAttr("disabled");
                        });
                    }else{
                        $(this).val("Ред.");
                        $(this).prev("input").remove();
                        $(this).prev("span.editable").text(curval).show();
                        $(this).unbind("click").bind("click",set_edit);
                    }
                }
                
                

            });
        </script>
    </head>
    <body>
        <div id="bannerContainer" ></div>
        <div class="container" id="container">
            <div id="header">
                <div id="profile_info" class="r3"> <img src="img/avatar.jpg" id="avatar" alt="avatar" />
                    <p>Добро пожаловать <strong>
                            <?= $this->user->name ?>
                        </strong>. <a href="login/logout">Выйти?</a></p>
                    <? if ($this->user->is_admin()): ?>
                        
                        <? if ($this->user->simulate): ?><p>Симуляция: <?=$this->user->info()->name?> <a href="admin/userc/disable_simulate">Отключить</a></p>
                            <?else:?>
                        <p><a href="admin">Админ Панель</a></p>
                            <? endif; ?>
                    <? else: ?>
                        <p><!--Сообщений: 0. <a href="#">Читать?</a>-->Приятного дня <?=$this->user->info()->full_name?></p>
                    <? endif; ?>
                    <? if (!($this->user->simulate)): ?><p class="last_login"><!--Дата последнего входа: 21:03 12.05.2009--><a href="help">Помощь</a></p><?else:?><p>Полное имя: <?=$this->user->info()->full_name?></p><?endif;?>
                </div>
                <div id="logo">
                    <h1><a href=""></a></h1>
                </div>
                <div class="clear"></div>
            </div>
            <!-- end header -->
            <div id="content" >
                <div id="top_menu" class="clearfix">
                    <ul class="sf-menu">
                        <!-- DROPDOWN MENU -->
                        <? if (!$this->user->is_admin() || $this->user->simulate): ?>
                            <li class="current"> <a href="">Главная</a></li>
                            <li> <a href="projectc/view/1">Проект</a></li>
                            <li> <a href="finance">Финансы</a> </li>
                            <li> <a href="sites">Площадки</a></li>
                            <li> <a href="loyalty">Лояльность</a> </li>
                            <li><a href="stats">Статистика</a></li>
                            <li><a href="refs">Рефералы</a></li>
                            
                        <? else: ?>
                            <li><a href="admin">Главная</a></li>
                            <li><a href="admin/stats">Статистика</a></li>
                            <li><a href="admin/projectc/view/id/1">Проект</a></li>
                            <li><a href="admin/sites">Площадки</a></li>
                            <li><a href="admin/frauds">Фрод</a></li>
							<!--li><a href="admin/spam">Рассылка</a></li-->
							<li><a href="admin/banner">Баннера</a></li>
                        <? endif; ?>
                    </ul>
                    <!--<a href="#" id="visit" class="right">Visit site</a> -->
                </div>
                <? $msg = $msg = $this->session->flashdata("msg"); ?>
                <?
                if ($msg):
                    if ($msg['type'] == "error")
                        $back = "red";else
                        $back = "green";
                    ?>
                    <div style="background:<?= $back ?>;color:white;padding:10px;">
                        <?= $msg['text'] ?> 
                    </div>
                <? endif; ?>
                <div id="content_data">
                    <?= $cont ?>
                </div>
            </div>
            <!-- end #content -->

            <div  id="footer" class="clearfix">
                <p class="left"></p>
                <p class="right">© 2011 aratog</p>
            </div>
            <!-- end #footer --> 
        </div>
        <!-- end container -->

    </body>
</html>
