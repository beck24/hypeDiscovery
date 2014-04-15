<?php

namespace hypeJunction\Discovery;

$entity = elgg_extract('entity', $vars);

if (is_discoverable($entity)) {
	echo elgg_view('framework/discovery/buttonbank', $vars);
}

$permalink = get_entity_permalink($entity);

if ($permalink) {
	echo '<div>';
	echo '<label>' . elgg_echo('discovery:entity:permalink') . '</label>';
	echo elgg_view('input/text', array(
		'value' => $permalink,
	));
	echo '</div>';

	if (is_embeddable($entity)) {
		$response = elgg_trigger_plugin_hook('export:entity', 'oembed', array(
			'origin' => $permalink,
			'entity' => $entity,
			'maxwidth' => elgg_extract('maxwidth', $vars, 640),
			'maxheight' => elgg_extract('maxheight', $vars, 480),
		));

		if ($response['html']) {
			echo '<div>';
			echo '<label>' . elgg_echo('discovery:entity:embed_code') . '</label>';
			echo elgg_view('input/text', array(
				'value' => $response['html'],
			));
			echo '</div>';
		}
	}
}