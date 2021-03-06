
<div id="t-' + item.id + '" class="t-wrapper '+ item.alt +'">
  <div class="t-details">
    <div class="image"><img src="'+ item.image +'"/></div>
    <div class="t-details-meta">
      <div class="t-name">
        <span>'+ item.name +'</span>
      </div>
      <div class="t-position">
        <span>'+ item.position +'</span>
      </div>  
      <div class="t-company">
        <span>'+ item.company +'</span>
      </div>
      <div class="t-location">
        <span>'+ item.location +'</span>
      </div>      
      <div class="t-url">
        <a href="'+ item.url +'" target="_blank">'+ item.url +'</a>
      </div>
    </div>
  </div>
  <div class="t-content">
    <div class="t-rating _' +item.rating+ '" title="Rating: '+item.rating+ ' stars">&#160;</div>
    <div class="t-body">' +item.body+ '</div>
    <div class="t-date"><abbr class="timeago">' + item.created +'</abbr></div>
    <div class="t-tag"><span>' +item.tag_name+ '</span></div>
  </div>
</div>