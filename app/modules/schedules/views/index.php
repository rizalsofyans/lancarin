  <!-- Live Chat Widget powered by https://keyreply.com/chat/ -->
<!-- Advanced options: -->
<!-- data-align="left" -->
<!-- data-overlay="true" -->
<style>
  .text-khusus {
    text-transform: none !important;
    font-weight: 300 !important;
    letter-spacing: normal !important;
    font-size: 14px;
}
</style>
<script data-align="right" data-overlay="false" id="keyreply-script" src="//lancarin.com/assets/js-landingpage/widgetschat.js" data-color="#3F51B5" data-apps="JTdCJTIyd2hhdHNhcHAlMjI6JTIyNjI4MTkwNTY3OTczOSUyMiwlMjJmYWNlYm9vayUyMjolMjI0NDQ2ODUwNzkyNzI0OTAlMjIlN0Q="></script>
<div class="wrap-content container schedules">
	<div class="monthly" id="mycalendar"></div>
</div>

<script type="text/javascript">
	$(function(){
		$('#mycalendar').monthly({
			mode: 'event',
  			xmlUrl: '<?=PATH?>schedules/xml',

		});
	});
</script>