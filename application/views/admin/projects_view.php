          <div id="content_main" class="clearfix">
          <div id="main_panel_container">
        <div class="dashboard">
        
        <table class="table">
        <tr>
         <th>Проект</th>
         <th>Партнеров</th>
         <th>Площадок</th>
         <th>Кликов</th>
         <th>Регистраций</th>
         <th>Активных на сегодня</th>
         <th>Активных на день регистрации</th>
         <th>%</th>
         <th>Выплачено</th>
         <th>К выплате</th>
        </tr> 

         <?foreach($projects as $p):?>
                  <tr>
                  <td><a href="admin/projectc/view/<?=$p->id?>"><?=$stats[$p->id]['name']?></a></td>
                  <td><a href="admin/projectc/view/<?=$p->id?>#partners"><?=$stats[$p->id]['partners']?></a></td>
                  <td><a href="admin/sites/view/proj/<?=$p->id?>"><?=$stats[$p->id]['sites']?></td>
                  <td><?=$stats[$p->id]['clicks']?></td>
                  <td><a href="admin/stats"><?=$stats[$p->id]['referals']?></a></td>
                  <td><?=$stats[$p->id]['active']?></td>
                  <td><?=$stats[$p->id]['active_on_reg_day']?></td>
                  <td><?=$stats[$p->id]['percent']?></td>
                  <td><?=$stats[$p->id]['payed']?></td>
                  <td><?=$stats[$p->id]['to_pay']?></td>
                  </tr>   
         <?endforeach?>
    
        </table>
        
        </div>
        </div>
        </div>