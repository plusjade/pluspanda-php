
<h1 style="text-align:center">Create Account</h1>
	
<div id="admin_wrapper">
	<?php if(isset($alert)) echo $alert?>
	
	<form action="/start" method="POST">
		<p><label>Username</label>
	  	<input name="username" type="text" class="input large" value="<?php echo $values['username']?>" />
	  </p>
		
		<p><label>Email</label>
	  	<input name="email" type="text" class="input large" value="<?php echo $values['email']?>" />
	  </p>
		
		<p><label>Password</label>
		<input name="password" type="password" class="input large" value="<?php echo $values['password']?>" />
	  </p>

		<p><label>Confirm Password</label>
		<input name="password2" type="password" class="input large"/>
	  </p>
	  
	  <p class="buttons" style="margin-top:15px;">	
			<button type="submit" name="Submit" id="button" class="positive">Create Account</button>
		</p>
	</form>
</div>
<!--
	<a href="/admin">Login</a>
-->