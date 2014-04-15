<?php

namespace hypeJunction\Discovery;

$entity = elgg_extract('entity', $vars);

if ($entity->og_icontime) {
	$icon = elgg_view('framework/discovery/icon', array(
		'entity' => $entity,
		'size' => '_og',
		'img_class' => 'elgg-photo mam',
	));
}

$form = '<div>';
$form .= '<label>' . elgg_echo('discovery:og:site_image') . '</label>';
$form .= '<div class="elgg-text-help">' . elgg_echo('discovery:og:site_image:help') . '</div>';
$form .= elgg_view('input/file', array(
	'name' => 'og_image',
	'value' => ($entity->og_icontime),
));
$form .= '</div>';

$form .= '<div>';
$form .= '<label>' . elgg_echo('discovery:og:site_name') . '</label>';
$form .= elgg_view('input/text', array(
	'name' => 'og_site_name',
	'value' => ($entity->og_site_name) ? $entity->og_site_name : elgg_get_config('sitename'),
));
$form .= '</div>';

$form .= '<div>';
$form .= '<label>' . elgg_echo('discovery:og:description') . '</label>';
$form .= elgg_view('input/text', array(
	'name' => 'og_description',
	'value' => ($entity->og_description) ? $entity->og_description : elgg_get_config('sitedescription'),
));
$form .= '</div>';

$form .= '<div>';
$form .= '<label>' . elgg_echo('discovery:fb:app_id') . '</label>';
$form .= elgg_view('input/text', array(
	'name' => 'fb_app_id',
	'value' => $entity->fb_app_id,
));
$form .= '</div>';

$form .= '<div>';
$form .= '<label>' . elgg_echo('discovery:twitter:site') . '</label>';
$form .= elgg_view('input/text', array(
	'name' => 'twitter_site',
	'value' => $entity->twitter_site,
));
$form .= '</div>';

$form .= '<div class="elgg-foot">';
$form .= elgg_view('input/submit', array(
	'value' => elgg_echo('save')
));
$form .= '</div>';

if ($icon) {
	$form = '<fieldset>' . $form . '</fieldset>';
	echo elgg_view_image_block($icon, $form);
} else {
	echo $form;
}