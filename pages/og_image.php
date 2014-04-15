<?php

namespace hypeJunction\Discovery;

use ElggFile;

$entity_guid = get_input('guid');
$entity = get_entity($entity_guid);

if (!is_discoverable($entity)) {
	exit;
}

$size = strtolower(get_input('size'));

$og_sizes = elgg_get_config('og_icon_sizes');
if (!array_key_exists($size, $og_sizes)) {
	$size = '_og';
}

$filename = "og_image/" . $entity->guid . '/' . $size . ".jpg";

$filehandler = new ElggFile();
$filehandler->owner_guid = ($entity->owner_guid) ? $entity->owner_guid : $entity->guid;
$filehandler->setFilename($filename);
$filestorename = $filehandler->getFilenameOnFilestore();

if (!file_exists($filestorename)) {
	$filestorename = elgg_trigger_plugin_hook('entity:icon:filestore', $entity->getType(), array(
		'entity' => $entity,
		'size' => 'large',
			), $entity->getIconURL('large'));
}

if (!file_exists($filestorename)) {
	$site = elgg_get_site_entity();
	$filename = "og_image/" . $site->guid . '/' . $size . ".jpg";

	$filehandler = new ElggFile();
	$filehandler->owner_guid = $site->guid;
	$filehandler->setFilename($filename);
	$filestorename = $filehandler->getFilenameOnFilestore();
}

if (!file_exists($filestorename)) {
	exit;
}

header("Content-Type: image/jpeg");
header('Expires: ' . date('r', time() + 864000));
header("Content-Length: " . filesize($filestorename));

ob_clean();
flush();
readfile($filestorename);
