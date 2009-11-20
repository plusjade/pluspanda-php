
<div id="admin_wrapper">
	<h1>[= Create Account</h1>
	
	<?php 
	echo (isset($alert))
		? $alert
		: '<div class="attention">Create a New Account!</div>';
	?>
	
	<form action="/admin?action=create" method="POST">
		<p><label>Username</label>
	  	<input name="username" type="text" class="input large" value="username" />
	  </p>
		
		<p><label>Email</label>
	  	<input name="email" type="text" class="input large" value="email" />
	  </p>
		
		<p><label>Password</label>
		<input name="password" type="password" class="input large" value="password" />
	  </p>

		<p><label>Confirm Password</label>
		<input name="password2" type="password" class="input large" value="password2" />
	  </p>
	  
	  <p class="buttons" style="margin-top:15px;">
		
			<button type="submit" name="Submit" id="button" class="positive" style="float:left;">Create Account</button>
		
			<button type="reset" name="reset" id="reset" class="positive" style="float:left; margin-left:10px;">Reset</button>
		</p>
		<a href="/admin">Login</a>
	</form>
</div>