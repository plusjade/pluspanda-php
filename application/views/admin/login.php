
<div id="admin_wrapper">
  <h1>[= PlusPanda</h1>
  
  <?php 
  echo (isset($alert))
    ? $alert
    : '<div class="attention">Hello! Let\'s Get Started!</div>';
  ?>
  
  <form action="/admin/login<?php if(isset($_GET['ref'])) echo '?ref='. $_GET['ref']?>" method="POST">
    <p><label>Email</label>
      <input name="email" type="text" class="input large" value="<?php if(isset($_POST['email'])) echo $_POST['email']?>" />
    </p>
    
    <p><label>Password</label>
    <input name="password" type="password" class="input large"/>
    </p>
    
    <p class="buttons" style="margin-top:15px;">
      <button type="submit" name="Submit" id="button" class="positive">Login</button>
    </p>
  </form>
</div>