<?php

namespace hypeJunction\Discovery;

$title = elgg_extract('title', $vars);

$entity = get_entity_from_url($url);

if (!is_embeddable($entity)) {
	return;
}

$origin = get_entity_permalink($entity);

$json_endpoint = elgg_http_add_url_query_elements(elgg_normalize_url('services/api/rest/oembed/'), array(
	'method' => 'oembed',
	'origin' => $origin,
	'format' => 'json',
));

$json_attrs = elgg_format_attributes(array(
	'rel' => 'alternate',
	'type' => 'application/json+oembed',
	'href' => $json_endpoint,
	'title' => $title,
));

$xml_endpoint = elgg_http_add_url_query_elements(elgg_normalize_url('services/api/rest/oembed/'), array(
	'method' => 'oembed',
	'origin' => $origin,
	'format' => 'xml',
));

$xml_attrs = elgg_format_attributes(array(
	'rel' => 'alternate',
	'type' => 'application/xml+oembed',
	'href' => $xml_endpoint,
	'title' => $title,
));

echo "<link $json_attrs />";
echo "<link $xml_attrs />";