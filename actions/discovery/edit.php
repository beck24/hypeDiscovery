<?php

namespace hypeJunction\Discovery;

use ElggFile;

elgg_make_sticky_form('discovery/edit');

$guid = get_input('guid');
$entity = get_entity($guid);

if (!elgg_instanceof($entity) || !$entity->canEdit()) {
	register_error(elgg_echo('discovery:site:error'));
	forward(REFERER);
}

$entity->og_title = get_input('og_title');
$entity->og_description = get_input('og_description');
$entity->og_keywords = string_to_tag_array(get_input('og_keywords', ''));
$entity->discoverable = (bool)get_input('discoverable', false);
$entity->embeddable = (bool)get_input('embeddable', false);

$icontime = time();
$icon_sizes = elgg_get_config('og_icon_sizes');

if (isset($_FILES['og_image']['name']) && $_FILES['og_image']['error'] != UPLOAD_ERR_NO_FILE) {

	unset($entity->og_icontime);

	if ($_FILES['og_image']['error'] != 0) {
		register_error(elgg_echo('discovery:og_image:upload_fail'));
	} else {
		$files = array();
		foreach ($icon_sizes as $name => $size_info) {
			$imgsize = getimagesize($_FILES['og_image']['tmp_name']);
			$icon_md = "og_icontime{$name}";
			unset($entity->$icon_md);

			if ($imgsize[0] >= $size_info['w']) {
				$resized = get_resized_image_from_uploaded_file('og_image', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);

				if ($resized) {
					$file = new ElggFile();
					$file->owner_guid = $entity->owner_guid;
					$file->setFilename("og_image/{$entity->guid}/{$name}.jpg");
					$file->open('write');
					$file->write($resized);
					$file->close();
					$entity->og_icontime = $icontime;
					$entity->$icon_md = $icontime;
				} else {
					system_message(elgg_echo('discovery:og_image:resize_fail', array(elgg_echo('discovery:og_image:size:' . $name), $size_info['w'])));
				}
			} else {
				system_message(elgg_echo('discovery:og_image:resize_fail', array(elgg_echo('discovery:og_image:size:' . $name), $size_info['w'])));
			}
		}
	}
}

if ($entity->save()) {
	elgg_clear_sticky_form('discovery/edit');
	system_message(elgg_echo('discovery:site:success'));
} else {
	register_error(elgg_echo('discovery:site:error'));
}

forward(REFERER);
