<?php

namespace hypeJunction\Discovery;

$entity = elgg_extract('entity', $vars);

$icon = elgg_view('framework/discovery/icon', array(
	'entity' => $entity,
	'size' => '_og',
	'img_class' => 'elgg-photo'
		));

$summary = elgg_view('object/elements/summary', array(
	'entity' => $entity,
	'metadata' => false,
	'tags' => false,
	'title' => elgg_view('output/url', array(
		'text' => get_discovery_title($entity),
		'href' => get_entity_permalink($entity),
		'is_trusted' => true,
	)),
	'content' => get_discovery_description($entity)
		));

echo elgg_view_image_block($icon, $summary);
