<?php

namespace hypeJunction\Discovery;

$entity = elgg_extract('entity', $vars);

echo '<div>';
echo '<label>' . elgg_echo('discovery:settings:bypass_access') . '</label>';
echo '<div class="elgg-text-help">' . elgg_echo('discovery:settings:bypass_access:help') . '</div>';
echo elgg_view('input/dropdown', array(
	'name' => 'params[bypass_access]',
	'value' => $entity->bypass_access,
	'options_values' => array(
		false => elgg_echo('option:no'),
		true => elgg_echo('option:yes')
	)
));
echo '</div>';

$registered_entities = elgg_get_config('registered_entities');

foreach ($registered_entities as $type => $subtypes) {
	if (sizeof($subtypes) == 0) {
		$str = elgg_echo("item:$type");
		$chbx_options[$str] = "$type::default";
	} else {
		foreach ($subtypes as $subtype) {
			$str = elgg_echo("item:$type:$subtype");
			$chbx_options[$str] = "$type::$subtype";
		}
	}
}

echo '<div class="clearfix">';

echo '<div class="elgg-col elgg-col-1of2">';
$discovery_type_subtype_pairs_setting = isset($entity->discovery_type_subtype_pairs) ? unserialize($entity->discovery_type_subtype_pairs) : array();
echo '<div>';
echo '<label>' . elgg_echo('discovery:settings:discovery_type_subtype_pairs') . '</label>';
echo '<div class="elgg-text-help">' . elgg_echo('discovery:settings:discovery_type_subtype_pairs:help') . '</div>';
echo elgg_view('input/checkboxes', array(
	'name' => 'params[discovery_type_subtype_pairs]',
	'value' => $discovery_type_subtype_pairs_setting,
	'options' => $chbx_options
));
echo '</div>';
echo '</div>';

echo '<div class="elgg-col elgg-col-1of2">';
$embed_type_subtype_pairs_setting = isset($entity->embed_type_subtype_pairs) ? unserialize($entity->embed_type_subtype_pairs) : array();
echo '<div>';
echo '<label>' . elgg_echo('discovery:settings:embed_type_subtype_pairs') . '</label>';
echo elgg_view('input/checkboxes', array(
	'name' => 'params[embed_type_subtype_pairs]',
	'value' => $embed_type_subtype_pairs_setting,
	'options' => $chbx_options
));
echo '</div>';
echo '</div>';

echo '</div>';

$provider_options = array(
	elgg_echo('discovery:provider:facebook') => 'facebook',
	elgg_echo('discovery:provider:twitter') => 'twitter',
	elgg_echo('discovery:provider:linkedin') => 'linkedin',
	elgg_echo('discovery:provider:pinterest') => 'pinterest',
	elgg_echo('discovery:provider:googleplus') => 'googleplus',
);

$provider_setting = isset($entity->providers) ? unserialize($entity->providers) : array();
echo '<div>';
echo '<label>' . elgg_echo('discovery:settings:providers') . '</label>';
echo '<div class="elgg-text-help">' . elgg_echo('discovery:settings:providers:help') . '</div>';
echo elgg_view('input/checkboxes', array(
	'name' => 'params[providers]',
	'value' => $provider_setting,
	'options' => $provider_options
));
echo '</div>';
