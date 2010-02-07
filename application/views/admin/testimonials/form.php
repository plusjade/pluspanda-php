

<div class="public-link">
  <span>Your Public Form Link:</span> <a href="<?php echo url::site("testimonials/add/{$this->site->apikey}")?>"><?php echo url::site("testimonials/add/{$this->site->apikey}")?></a>
  <input type="text" style="width:450px;padding:5px;" value="<?php echo url::site("testimonials/add/{$this->site->apikey}")?>"/>
</div>


<form class="common-ajax" action="/admin/testimonials/form/save" method="POST">
  <div class="round-box-tabs buttons">
    <button type="submit" class="positive">Save Settings</button>
  </div>
  <div class="round-box-top">Configure Form Settings.</div>
  <div class="round-box-body">
    <fieldset>
      <label>Custom Header Message.</label>
      <br/><textarea name="tstml_msg" style="width:100%;height:100px"><?php echo $this->site->tstml_msg?></textarea>
    </fieldset>
    
    <br/>A customer's full name is always required. You can optionally require 3 more fields:

    <fieldset>
      <label>Access Key</label>
      <input type="text" name="key" value="<?php echo $settings->require_key?>" />
    </fieldset>
    
    <fieldset>
      <label>Require Email (Yes)</label>
      <input type="checkbox" name="email" <?php if($settings->email) echo 'CHECKED'?>/>
    </fieldset>
    
    <fieldset>
      <label>Define Meta Data Name</label>
      <input type="text" name="meta" value="<?php echo $settings->meta?>" />
    </fieldset> 
  </div>
</form>

<div class="indent">
  <h4>Customize Header Message.</h4>
  <div class="indent">
    This text will display at the top of your public form.
    Place your company name here, offer incentives for submitting testimonials,
    or just give a nice thank you.
  </div>
  
  <h4>Require Access Key.</h4>
  <div class="indent">
    New testimonials cannot be created without a valid access key.
  </div>
  
  <h4>Require Email Address.</h4>
  <div class="indent">
    Customers are required to enter a valid email address.
    Use this option if you wish to openly collect testimonials from anyone interested in writing one.
    This way you can maintain contact with people you may not know.
  </div>
  
  <h4>Require Additional Meta Data.</h4>
  <div class="indent">
    Customers are required to enter specific meta data defined below.
    <br/><b>Example:</b> If you wish to collect testimonials from only customers that have 
    made purchases, you can place the link onto your order confirmation page. Rather than using
    an email identifier of which you already have, you can pass the order confirmation code or customer_id as meta data.
  </div>

  <h4>Pre-populating The Public Form.</h4>
  <div class="indent">
    You can easily pre-populate known fields by passing them in the url string.
    <br/><input style="margin:7px 0; padding:5px; width:100%" type="text" value="<?php echo url::site("testimonials/add/{$this->site->apikey}")?>?name=your_customers_name&key=you_private_key&email=customer_email&meta=custom_meta_data" />
    <br/><b>See it in action:</b> <a href="<?php echo url::site("testimonials/add/{$this->site->apikey}")?>?name=your_customers_name&key=you_private_key&email=customer_email&meta=custom_meta_data">View your pre-populated public form</a>
  </div>
</div>

<script type="text/javascript">
  // override the normal submit event
  $('form.common-ajax').submit(function(){
    $('form.common-ajax button').click();
    return false;
  });
</script>