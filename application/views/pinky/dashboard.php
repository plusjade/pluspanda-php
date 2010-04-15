
<style type="text/css">
  table td{padding:8px;border-bottom:1px dashed #ccc;}
  table tr{}
</style>

<ul>
  <li>Total owners: <b><?php echo $total?></b></li>
  <li>Saved owners: <b><?php echo $saved?></b></li>
  <li>Rate: <?php echo number_format(($saved/$total)*100, 2)?>%</li>
  <li><a href="/pinky/delete_all">Delete Unsaved</a></li>
</ul>


<table class="owner-data">
  <thead>
    <tr>
      <th>email</th>
      <th>apikey</th>
      <th>logins</th>
      <th>last login</th>
      <th>created</th>
      <th>#t.</th>
      <th>x</th>
    </tr>
  </thead>
  <tbody>
<?php foreach($owners as $owner):?>
    <tr>
      <td><?php echo $owner->email?></td>
      <td><?php echo $owner->apikey?></td>
      <td><?php echo $owner->logins?></td>
      <td><?php echo common_build::timeago($owner->last_login)?></td>
      <td><?php echo common_build::timeago($owner->created)?></td>
      <td><?php #echo $owner->tstmls?></td>
      <td><a href="/pinky/delete?id=<?php echo $owner->id?>">[x]</a></td>
    </tr>
<?php endforeach;?>
  </tbody>
</table>

<script type="text/javascript">
  $('abbr.timeago').timeago();
  
  $("table.owner-data").tablesorter({
    headers:{
      5:{sorter:false}
    }
  });
</script>









