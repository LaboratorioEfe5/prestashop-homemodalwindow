{if isset($content) AND $content}
<!-- MODULE home modal window -->

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
