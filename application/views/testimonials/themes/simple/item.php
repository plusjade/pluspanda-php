
<div id="t-' + item.id + '" class="t-single">
  <div class="content">
    <span class="arrow"></span>
    <div class="head">
      <div class="t-rating">
        <span class="panda-rating-stars s'+item.rating+ '" title="Rating: '+item.rating+ ' stars"></span>
      </div>
      <div class="t-details-meta">
        <span class="t-name">'+ item.name +',</span>
        <span class="t-position">'+ item.position +', ' + item.company + '</span>
        <a href="'+ item.url +'" class="t-website" target="_blank">'+ item.url +'</a>
      </div>
    </div>
    <div class="t-text">
      <span class="p1"></span>
      <p>' +item.body+ '</p>
    </div>
  </div>
</div>