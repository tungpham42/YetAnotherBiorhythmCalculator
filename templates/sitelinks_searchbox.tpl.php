<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "WebSite",
	"url": "https://<?php echo $_SERVER['SERVER_NAME']; ?>/",
	"potentialAction": {
		"@type": "SearchAction",
		"target": "https://<?php echo $_SERVER['SERVER_NAME']; ?>/?q={search_term_string}",
		"query-input": "required name=search_term_string"
	}
}
</script>