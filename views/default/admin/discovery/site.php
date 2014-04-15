<?php

namespace hypeJunction\Discovery;

$vars['entity'] = elgg_get_site_entity();

$sticky_value = elgg_get_sticky_values('discovery/site');
if (is_array($sticky_values)) {
	$vars = array_merge($vars, $sticky_values);
}

echo elgg_view_form('discovery/site', array(
	'enctype' => 'multipart/form-data',
), $vars);