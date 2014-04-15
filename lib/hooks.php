<?php

namespace hypeJunction\Discovery;

use ElggMenuItem;

/**
 * Add discovery pages to public domain
 * 
 * @param string $hook		Equals 'public_pages'
 * @param string $type		Equals 'walled_garden'
 * @param array $return		Array of public pages
 * @return array
 */
function public_pages($hook, $type, $return) {

	$return[] = PAGEHANDLER_PERMALINK . '/.*';
	$return[] = 'action/discovery/share';
	return $return;
}

/**
 * Setup entity menu
 *
 * @param type $hook
 * @param type $type
 * @param array $return
 * @param type $params
 * @return type
 */
function entity_menu_setup($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	if ($entity->canEdit() && is_discoverable_type($entity)) {
		elgg_load_js('lightbox');
		elgg_load_css('lightbox');
		$return[] = ElggMenuItem::factory(array(
					'name' => 'discovery:edit',
					'text' => elgg_view_icon('eye'),
					'href' => PAGEHANDLER_OPENGRAPH . '/edit/' . $entity->guid,
					'title' => elgg_echo('discovery:edit'),
					'class' => 'elgg-lightbox',
					'priority' => 700,
		));
	}


	if (is_discoverable($entity)) {
		elgg_load_js('lightbox');
		elgg_load_css('lightbox');
		$return[] = ElggMenuItem::factory(array(
					'name' => 'discovery:share',
					'text' => elgg_view_icon('share'),
					'href' => PAGEHANDLER_OPENGRAPH . '/share/' . $entity->guid,
					'title' => elgg_echo('discovery:entity:share'),
					'class' => 'elgg-lightbox',
					'priority' => 700,
		));
	}

	return $return;
}

/**
 * Setup extras menu
 *
 * @param string $hook		Equals 'register'
 * @param string $type		Equals 'menu:extras'
 * @param array $return		Current menu
 * @param array $params		Additional params
 * @return array			Updated menu
 */
function extras_menu_setup($hook, $type, $return, $params) {

	$entity = get_entity_from_url(current_page_url());
	if (!is_discoverable($entity)) {
		return $return;
	}

	$providers = elgg_get_config('discovery_providers');
	foreach ($providers as $provider) {
		$return[] = ElggMenuItem::factory(array(
					'name' => "discovery:$provider",
					'text' => '<span class="webicon ' . $provider . ' small"></span>',
					'href' => get_share_action_url($provider, $entity->guid, current_page_url()),
					'is_action' => true,
					'title' => elgg_echo('discovery:share', array(elgg_echo("discovery:provider:$provider"))),
						//'section' => 'discovery',
		));
	}

	return $return;
}

/**
 * OG image url
 *
 * @param string $hook		Equals 'entity:icon:url'
 * @param string $type		'site', 'object', 'user' or 'group'
 * @param string $return	Current URL
 * @param array $params		Additional params
 * @return string			Updated url
 */
function entity_icon_url($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	$size = elgg_extract('size', $params);

	$og_sizes = array('_og', '_og_large', '_og_high');

	$icontime = ($entity->og_icontime) ? $entity->og_icontime : $entity->icontime;
	if (!$icontime) {
		$icontime = $entity->time_created;
	}
	if (elgg_instanceof($entity) && in_array($size, $og_sizes)) {
		$segments = array(
			PAGEHANDLER_PERMALINK,
			'image',
			$entity->guid,
			$size,
			"{$icontime}.jpg"
		);

		return elgg_normalize_url(implode('/', $segments));
	}

	return $return;
}

/**
 * Get exportable representation of an entity for oEmbed
 *
 * @param string $hook		Equals 'export:entity'
 * @param string $type		Equals 'oembed'
 * @param array $return		Current exportable values
 * @param array $params		Additional params
 * @return array
 */
function oembed_entity_export($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	$maxwidth = elgg_extract('maxwidth', $params);
	$maxheight = elgg_extract('maxheight', $params);
	$height = ($maxheight) ? $maxheight : 480;
	$width = ($maxwidth) ? $maxwidth : 640;

	if (!is_discoverable($entity)) {
		return $return;
	}

	$return['type'] = 'rich';
	$return['thumbnail_url'] = $entity->getIconURL('_og');

	$iframe_attrs = elgg_format_attributes(array(
		'src' => elgg_http_add_url_query_elements(get_entity_permalink($entity, 'oembed')),
		'frameborder' => 0,
		'height' => $height,
		'width' => $width,
		'scrolling' => 'auto',
		'seamless' => true,
	));

	$return['html'] = "<iframe $iframe_attrs></iframe>";
	$return['width'] = $width;
	$return['height'] = $height;

	return $return;
}

/**
 * Header metatags
 * 
 * @param string $hook		Equals 'metatags'
 * @param string $type		Equals 'discovery'
 * @param array $return		An array of tags
 * @param array $params		Additional params
 * @return array			
 */
function discovery_header_metatags($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	$url = elgg_extract('url', $params);

	$site = elgg_get_site_entity();
	$site_tags = array(
		'og:type' => 'website',
		'og:site_name' => $site->og_site_name,
		'og:image' => get_discovery_image_url($site),
		'og:url' => $url,
		'og:description' => get_discovery_description($site),
		'fb:app_id' => $site->fb_app_id,
		'twitter:card' => 'summary',
		'twitter:site' => $site->twitter_site,
	);

	$return = array_merge($return, $site_tags);

	if (!is_discoverable($entity)) {
		return $return;
	}

	$type = $entity->getType();
	$subtype = $entity->getSubtype();

	$image_url = get_discovery_image_url($entity);
	if (file_exists($image_url)) {
		$image_size = getimagesize($image_url);
		$image_width = $image_size[0];
		$image_height = $image_size[1];
	}

	switch ($type) {

		default :
		case 'object' :
			$owner = $entity->getOwnerEntity();
			$entity_tags = array(
				'og:type' => 'article',
				'og:title' => get_discovery_title($entity),
				'og:image' => $image_url,
				'og:image:width' => $image_width,
				'og:image:height' => $image_height,
				'og:url' => get_entity_permalink($entity),
				'og:description' => get_discovery_description($entity),
				'article:published_time' => date("Y-m-d", $entity->time_created),
				'article:author' => ($owner) ? $owner->getURL() : '',
				'article:tags' => get_discovery_keywords($entity),
				'twitter:creator' => ($owner) ? $owner->twitter : '',
			);
			break;

		case 'user' :
			$entity_tags = array(
				'og:type' => 'profile',
				'og:title' => get_discovery_title($entity),
				'og:image' => $image_url,
				'og:image:width' => $image_width,
				'og:image:height' => $image_height,
				'og:url' => get_entity_permalink($entity),
				'og:description' => get_discovery_description($entity),
				'profile:username' => $entity->username,
				'twitter:creator' => $entity->twitter,
			);
			break;

		case 'site' :
			$entity_tags = array();
			break;
	}

	$return = array_merge($return, $entity_tags);

	return $return;
}
