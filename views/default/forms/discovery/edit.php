<?php

namespace hypeJunction\Discovery;

$entity = elgg_extract('entity', $vars);

echo '<iframe src="https://hypejunction.com/permalink/oembed/2274b9e950fe42c638c9ff0bbc3aa66c/322/open-graph-protocol?" frameborder="0" height="480" width="640" scrolling="auto" seamless="seamless"></iframe>';

$col1 = elgg_view('framework/discovery/icon', array(
	'entity' => $entity,
	'size' => '_og',
	'img_class' => 'elgg-photo mam',
		));

if (is_discoverable_type($entity)) {
	$col1 .= '<div class="mal">';
	$col1 .= '<label>' . elgg_echo('discovery:og:discoverable') . '</label>';
	$col1 .= elgg_view('input/dropdown', array(
		'name' => 'discoverable',
		'value' => ($entity->discoverable) ? $entity->discoverable : is_discoverable($entity),
		'options_values' => array(
			0 => elgg_echo('option:no'),
			1 => elgg_echo('option:yes'),
		)
	));
	$col1 .= '</div>';
}

if (is_embeddable_type($entity)) {
	$col1 .= '<div class="mal">';
	$col1 .= '<label>' . elgg_echo('discovery:og:embeddable') . '</label>';
	$col1 .= elgg_view('input/dropdown', array(
		'name' => 'embeddable',
		'value' => ($entity->embeddable) ? $entity->embeddable : is_embeddable($entity),
		'options_values' => array(
			0 => elgg_echo('option:no'),
			1 => elgg_echo('option:yes'),
		)
	));
	$col1 .= '</div>';
}

$col2 .= '<div class="elgg-text-help">';
$col2 .= elgg_echo('discovery:og:help');
$col2 .= '</div>';

$col2 .= '<div>';
$col2 .= '<label>' . elgg_echo('discovery:og:image') . '</label>';
$col2 .= elgg_view('input/file', array(
	'name' => 'og_image',
	'value' => ($entity->og_icontime),
		));
$col2 .= '</div>';

$col2 .= '<div>';
$col2 .= '<label>' . elgg_echo('discovery:og:title') . '</label>';
$col2 .= elgg_view('input/text', array(
	'name' => 'og_title',
	'value' => get_discovery_title($entity),
		));
$col2 .= '</div>';

$col2 .= '<div>';
$col2 .= '<label>' . elgg_echo('discovery:og:description') . '</label>';
$col2 .= elgg_view('input/text', array(
	'name' => 'og_description',
	'value' => get_discovery_description($entity),
		));
$col2 .= '</div>';

$col2 .= '<div>';
$col2 .= '<label>' . elgg_echo('discovery:og:keywords') . '</label>';
$col2 .= elgg_view('input/tags', array(
	'name' => 'og_keywords',
	'value' => get_discovery_keywords($entity),
		));
$col2 .= '</div>';

$col2 .= '<div class="elgg-foot">';
$col2 .= elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $entity->guid
		));

$col2 .= elgg_view('input/submit', array(
	'value' => elgg_echo('save')
		));
$col2 .= '</div>';

$col1 = '<fieldset>' . $col1 . '</fieldset>';
$col2 = '<fieldset>' . $col2 . '</fieldset>';

echo elgg_view_image_block($col1, $col2);
