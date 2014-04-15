<?php

namespace hypeJunction\Discovery;

$metatags = get_discovery_metatags(current_page_url());

if ($metatags) {

	if (!isset($metatags['og:title']) && isset($vars['title'])) {
		$metatags['og:title'] = $vars['title'];
	}

	foreach ($metatags as $property => $tags) {

		if (!$tags) {
			continue;
		}

		if (!is_array($tags)) {
			$tags = array($tags);
		}

		foreach ($tags as $content) {

			if (!$content) {
				continue;
			}

			$attrs = elgg_format_attributes(array(
				'property' => $property,
				'content' => $content
			));
			echo "<meta $attrs />";
		}
	}
}