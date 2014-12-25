<div id="content_main" class="clearfix">
    <div id="main_panel_container">
        <link href="public/css/flexigrid.css" rel="stylesheet" type="text/css" />
        <script src="js/flexigrid.js"></script>
        <?php
        if (!empty($js_grid))
            echo $js_grid;else
            echo"<p>Нет данных</p>";
        ?>
       <!-- <script type="text/javascript">

            function test(com,grid)
            {
                if (com=='Select All')
                {
                    $('.bDiv tbody tr',grid).addClass('trSelected');
                }
    
                if (com=='DeSelect All')
                {
                    $('.bDiv tbody tr',grid).removeClass('trSelected');
                }
    
                if (com=='Delete')
                {
                    if($('.trSelected',grid).length>0){
                        if(confirm('Delete ' + $('.trSelected',grid).length + ' items?')){
                            var items = $('.trSelected',grid);
                            var itemlist ='';
                            for(i=0;i<items.length;i++){
                                itemlist+= items[i].id.substr(3)+",";
                            }
                            $.ajax({
                                type: "POST",
                                url: "<?= site_url("/ajax/deletec"); ?>",
                                data: "items="+itemlist,
                                success: function(data){
                                    $('#flex1').flexReload();
                                    alert(data);
                                }
                            });
                        }
                    } else {
                        return false;
                    } 
                }          
            } 
        </script> -->
        <? if (!empty($prev_html)) echo $prev_html; ?>
        <table id="flex1" style="display:none"></table>
        <? if (!empty($after_html)) echo $after_html; ?>
    </div>
</div>