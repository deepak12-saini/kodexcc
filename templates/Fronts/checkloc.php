<script src="https://kodexglobalcc.com/theme/js/jquery.js"></script>
<p id="ip"></p>
<p id="address"></p>
<p id="details"></p>
<script>
$.get("https://ipinfo.io/json", function (response) {
	
	var opeurl = '<?php echo SITEURL ?>fronts/analytics?ip='+response.ip+'&city='+response.city+'&state='+response.region+'&country='+response.country+'&loc='+response.loc+'&postal='+response.postal;

     $.ajax({url: opeurl, success: function(result){
		 console.log(result);
	  }});
  
}, "jsonp");
</script>