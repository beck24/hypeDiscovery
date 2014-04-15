<?php

namespace hypeJunction\Discovery;

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity)) {
	return;
}

$sticky_value = elgg_get_sticky_values('discovery/edit');
if (is_array($sticky_values)) {
	$vars = array_merge($vars, $sticky_values);
}

echo elgg_view_form('discovery/edit', array(
	'enctype' => 'multipart/form-data',
), $vars);