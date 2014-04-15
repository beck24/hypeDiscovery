<?php

namespace hypeJunction\Discovery;

$provider = get_input('provider');
$guid = get_input('guid');
$referrer = get_input('referrer');

$ia = elgg_set_ignore_access(true);

$entity = get_entity($guid);

$forward_url = REFERRER;

if (!is_discoverable($entity)) {
	$error = true;
} else {
	$forward_url = get_provider_url($provider, $entity, $referrer);
	$forward_url = elgg_trigger_plugin_hook('entity:share', $entity->getType(), array(
		'provider' => $provider,
		'entity' => $entity,
		'referrer' => $referred,
			), $forward_url);
}

elgg_set_ignore_access($ia);

if (!$forward_url || $error) {
	register_error(elgg_echo('discovery:share:error:no_url'));
	forward(REFERER);
}

forward($forward_url);

