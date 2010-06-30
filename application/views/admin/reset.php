
<div id="admin_wrapper">
  <h1>[= PlusPanda</h1>
  
  <?php 
  echo (isset($alert))
    ? $alert
    : '<div class="attention">Reset Your Password</div>';
  ?>
  
  <b>NOTE: This will reset your current password to a new auto-generated password
  which will be emailed to you.</b>
  <form action="/admin/login/reset" method="POST">
    <p><label>Email</label>
      <input name="email" type="text" class="input large" value="<?php if(isset($_POST['email'])) echo $_POST['email']?>" />
    </p>    
    <p class="buttons" style="margin-top:15px;">
      <button type="submit" name="Submit" id="button" class="positive">RESET PASSWORD</button>
    </p>
  </form>
</div>
