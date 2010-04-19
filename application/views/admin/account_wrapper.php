
<h2>Manage Account</h2>

Your email: <?php echo $this->owner->email?>

<br/><br/>

<h3>Change Password</h3>
 
<form action="/admin/account/change_password" class="common-ajax" method="POST">
  <fieldset>
    <label>Old Password</label> <input type="password" name="old_pw" value="<?php if(isset($_GET['old'])) echo $_GET['old']?>" />
  </fieldset>

  <fieldset>
    <label>New Password</label> <input type="text" name="new_pw" rel="length_req" alt="6"/>
  </fieldset>
  
  <fieldset>
    <label>Confirm Password</label> <input type="text" name="pw_2" rel="length_req" alt="6"/>
  </fieldset>
  
  <fieldset>
    <button type="submit">Submit</button>
  </fieldset>
</form>

