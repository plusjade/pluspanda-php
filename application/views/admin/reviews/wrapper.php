

<h2>Manage Reviews</h2>

<?php if(isset($response))echo $response?>

<form action="/admin/reviews/manage" method="GET" style="text-align:center">
  Categories: <?php echo $categories?>
  <br/>
  Show <?php echo $ratings?> ratings.
  
  Timespan: <?php echo $range?>
  <br/><button type="submit">Submit</button>
</form>


<form class="flag-review" action="/admin/reviews/moderate" method="POST">
  Flagging this review will send a friendly email
  to the reviewer asking him/her to confirm the validity of the review.
  <br/>
  
  id:<input type="text" name="review_id" value="" style="width:40px" READONLY> 
  Reason: <select name="reason">
    <option value="spam">Spam</option>
    <option value="Inappropriate">Inappropriate</option>
    <option value="invalid">Not a valid Review</option>
  </select>
   <button type="submit">Flag Review</button>
</form>


<div class="admin-reviews-list">  
  <?php echo $reviews?>
</div>


<script type="text/javascript">
  
</script>