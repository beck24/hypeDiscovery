<?php

namespace hypeJunction\Discovery;

use ElggFile;

elgg_make_sticky_form('discovery/site');

$site = elgg_get_site_entity();
$site->og_site_name = get_input('og_site_name');
$site->og_description = get_input('og_description');
$site->fb_app_id = get_input('fb_app_id');
$site->twitter_site = get_input('twitter_site');

if ($site->save()) {

	$icontime = time();
	$icon_sizes = elgg_get_config('og_icon_sizes');

	if (isset($_FILES['og_image']['name'])) {

		unset($site->og_icontime);

		if ($_FILES['og_image']['error'] != 0) {
			register_error(elgg_echo('discovery:og_image:upload_fail'));
		} else {
			$files = array();
			foreach ($icon_sizes as $name => $size_info) {
				$imgsize = getimagesize($_FILES['og_image']['tmp_name']);
				$icon_md = "og_icontime{$name}";
				unset($site->$icon_md);

				if ($imgsize[0] >= $size_info['w']) {
					$resized = get_resized_image_from_uploaded_file('og_image', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);

					if ($resized) {
						$file = new ElggFile();
						$file->owner_guid = $site->guid;
						$file->setFilename("og_image/{$site->guid}/{$name}.jpg");
						$file->open('write');
						$file->write($resized);
						$file->close();
						$site->og_icontime = $icontime;
						$site->$icon_md = $icontime;
					} else {
						system_message(elgg_echo('discovery:og_image:resize_fail', array(elgg_echo('discovery:og_image:size:' . $name), $size_info['w'])));
					}
				} else {
					system_message(elgg_echo('discovery:og_image:resize_fail', array(elgg_echo('discovery:og_image:size:' . $name), $size_info['w'])));
				}
			}
		}
	}

	elgg_clear_sticky_form('discovery/site');
	system_message(elgg_echo('discovery:site:success'));
} else {
	register_error(elgg_echo('discovery:site:error'));
}

forward(REFERER);
