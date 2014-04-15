<?php

namespace hypeJunction\Discovery;

$entity = elgg_extract('entity', $vars);

if (!is_discoverable($entity)) {
	return;
}

$providers = elgg_get_config('discovery_providers');
if (!count($providers)) {
	return;
}

$buttonbank = '';
foreach ($providers as $provider) {
	$buttonbank .= '<li>' . elgg_view('output/url', array(
				'text' => '<span class="webicon ' . $provider . '"></span>',
				'href' => get_share_action_url($provider, $entity->guid, current_page_url()),
				'is_action' => true,
				'class' => 'svg',
				'title' => elgg_echo('discovery:share', array(elgg_echo("discovery:provider:$provider"))),
				'target' => '_blank',
			)) . '</li>';
}
$buttonbank = '<ul class="discovery-buttonbank svg">' . $buttonbank . '</ul>';

echo $buttonbank;
