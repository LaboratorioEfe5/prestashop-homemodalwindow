{if isset($content) AND $content}
<!-- MODULE home modal window -->
<script type="text/javascript" src="js/jquery/jquery.fancybox-1.3.4.js"></script>
<link href="css/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" media="screen" />
{literal}

<script type="text/javascript">
jQuery(document).ready(function() {
	$.fancybox(
		'{/literal}{$content}{literal}',
		{
			'width'			    : 650,
			'width'			    : 400,
			'autoScale'  		: true,
			'scrolling'  		: 'no'
		});
});
</script>

{/literal}

{/if}<!-- /MODULE home modal window -->
