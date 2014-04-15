<?php

namespace hypeJunction\Discovery;

$result = $vars['result'];
$export = $result->export();

$format = $export->result->format;

switch ($format) {

	default :
	case 'json' :
		echo json_encode($export->result);
		break;

	case 'xml' :
		echo serialise_object_to_xml($export->result, 'oembed');
		break;
}