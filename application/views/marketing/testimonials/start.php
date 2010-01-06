
<h2 class="header">Get PlusPanda <span style="color:#396033">Free</span>, Now!</h2>

<?php if(isset($errors)) echo val_form::show_error_box($errors);?>
  
<div id="signup-form">      
  <form action="/start" method="POST">
    <?php
    $fields = array(
      'email'     => array('Email','input','text_req','',''),
      'password'  => array('Password','password','text_req','',''),
      'password2' => array('Confirm Password','password','text_req','',''),
    );
    if(!isset($values)) $values = array();
    if(!isset($errors) OR !is_array($errors)) $errors = array();
    ?>
    <?php echo val_form::generate_fields($fields, $values, $errors);?>
    <fieldset class="buttons" style="text-align:center; margin-top:15px;">  
      <button type="submit" name="Submit" id="button" class="positive">Sign Up!</button>
    </fieldset>
  </form>
</div>

<div class="twitter-action">
  <h4>Not ready to sign up?</h4>
  <a href="http://twitter.com/pluspanda"><img src="/static/images/marketing/followme.png" alt="follow me on twitter"></a>
  You can still be in the loop by following <a href="http://twitter.com/pluspanda">@pluspanda</a> on twitter.
  <br/>Twitter followers get a <em>free plus account for life</em> too - of course!
</div>

<div class="signup-wrapper">

  <h3>FREE PLUS ACCOUNT FOR LIFE.</h3>
  Pluspanda is in beta which means we appreciate your help
  in building a service <em>you</em> love. So lock in your free
  account and help us make a great service!
  <br/><br/>
  
  <h3>100% Happiness Guarantee.</h3>
  We are committed to leading by example and being genuinely
  interested in your happiness! Enjoy a 30 day full money-back
  return policy. If you pay yearly, you can cancel <em>anytime</em>
  and receive a refund for any full, unused months remaining.
  <br/><br/>
  
  <h3>Full-Time Email Support.</h3>
  Our customer support team responds to all emails within one hour of receiving them, during business hours.
  Emails sent outside of normal business hours are handled first thing next business morning!
    

</div>

<div class="left-copy">
  <img src="/static/images/marketing/tags.png" />
  

</div>

<div style="clear:both"></div>