
<h2 class="header">Get PlusPanda <span style="color:#396033">Free</span>, Now!</h2>

<div class="signup-wrapper">
	<div class="hurry">
		Hurry! Get a Plus account <span>FREE FOR LIFE</span> during December.
		We need 100 accounts so be sure and let your buddies know!
	</div>
	<div id="signup-form">	
		<?php if(isset($alert)) echo $alert?>	
		<form action="/start" method="POST">			
			<label>Username</label>
			<input name="username" type="text" class="input large" value="<?php echo $values['username']?>" />
			<br/>
			<label>Email</label>
			<input name="email" type="text" class="input large" value="<?php echo $values['email']?>" />
					
			<label>Password</label>
			<input name="password" type="password" class="input large" value="<?php echo $values['password']?>" />
			<br/>
			<label>Confirm Password</label>
			<input name="password2" type="password" class="input large"/>
			
			<p class="buttons" style="margin-top:15px;">	
				<button type="submit" name="Submit" id="button" class="positive">Sign Up!</button>
			</p>
		</form>
	</div>
	<br/><br/>
	<h2>All Plans Come With &#8594;</h2>
</div>

<div class="left-copy">
	<img src="/static/home/images/tags.png" />
	
	<br/><br/>
	<h3>100% Happiness Guarantee.</h3>
	We are committed to leading by example and being genuinely
	interested in your happiness! Enjoy a 30 day full money-back
	return policy. If you pay yearly, you can cancel <em>anytime</em>
	and receive a refund for any full, unused months remaining.

	<h3>Full-Time Email Support.</h3>
	Our customer support team responds to all emails within one hour of receiving them, during business hours.
	Emails sent outside of normal business hours are handled first thing next business morning!
	
</div>

<div style="clear:both"></div>