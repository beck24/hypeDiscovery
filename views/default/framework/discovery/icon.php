<?php

namespace hypeJunction\Discovery;

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity)) {
	return;
}

$class = elgg_extract('img_class', $vars, '');

if (isset($entity->name)) {
	$title = $entity->name;
} else {
	$title = $entity->title;
}
$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8', false);

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$icon_sizes = elgg_get_config('og_icon_sizes');
$size = $vars['size'];

if (!isset($vars['width'])) {
	$vars['width'] = $size == '_og' ? $icon_sizes[$size]['w'] : null;
}
if (!isset($vars['height'])) {
	$vars['height'] = $size == '_og' ? $icon_sizes[$size]['h'] : null;
}

$img_params = array(
	'src' => $entity->getIconURL($vars['size']),
	'alt' => $title,
);

if (!empty($class)) {
	$img_params['class'] = $class;
}

if (!empty($vars['width'])) {
	$img_params['width'] = $vars['width'];
}

if (!empty($vars['height'])) {
	$img_params['height'] = $vars['height'];
}

$img = elgg_view('output/img', $img_params);

if ($url) {
	$params = array(
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
	);
	$class = elgg_extract('link_class', $vars, '');
	if ($class) {
		$params['class'] = $class;
	}

	echo elgg_view('output/url', $params);
} else {
	echo $img;
}
