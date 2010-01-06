


<h2 class="head">Display Settings</h2>


<h4>Saved Theme : <?php echo $this->site->theme?></h4>

<div class="buttons" style="float:right">
  <button id="save-theme" type="submit" class="positive">Save Theme</button>
</div>


<h4>Choose a Theme</h4>
<select name="theme" class="switch-theme">
<?php 
$themes = array('left','right','grid','gallery');
  foreach($themes as $theme):?>
    <option value="<?php echo $theme?>"><?php echo ucfirst($theme)?></option>
  <?php endforeach;?>
</select>

<div style="height:3px;background:#ccc;margin:25px"></div>

<?php echo $embed_code?>



<script type="text/javascript">
  $('select.switch-theme').change(function(){
    var url = '<?php echo t_paths::css($this->site->apikey, 'url')?>/';
    var theme = $('select.switch-theme option:selected').val();
    window.location.hash = theme;
    $('head link#pandaTheme').attr('href', url + theme + '.css');
    $('#panda-select-tags ul a:first').click();
  });
</script>