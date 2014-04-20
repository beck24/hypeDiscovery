<?php

namespace hypeJunction\Discovery;

use ElggUser;
use UFCOE\Elgg\Url;

/**
 * Check if the entity can be shared
 * @param ElggEntity $entity
 * @return boolean
 */
function is_discoverable($entity) {

	if (elgg_instanceof($entity, 'site')) {
		return true;
	}

	if (!elgg_instanceof($entity)) {
		return false;
	}

	if (!is_discoverable_type($entity)) {
		return false;
	}

	if ($entity->owner_guid == elgg_get_logged_in_user_guid()) {
		return true;
	}

	if (isset($entity->discoverable)) {
		if ((bool) $entity->discoverable === true) {
			return true;
		} else if ((bool) $entity->discoverable === false) {
			return false;
		}
	}

	switch ($entity->access_id) {
		case ACCESS_PUBLIC :
			return true;
		case ACCESS_LOGGED_IN :
			if (!HYPEDISCOVERY_BYPASS_ACCESS) {
				return false;
			}
			return true;
		default :
			return false;
	}
}

/**
 * Check if entity type/subtype are specified for discovery in plugin settings
 * 
 * @param ElggEntity $entity
 * @param string $type
 * @param string $subtype
 * @return boolean
 */
function is_discoverable_type($entity = null, $type = '', $subtype = '') {

	if (elgg_instanceof($entity)) {
		$type = $entity->getType();
		$subtype = $entity->getSubtype();
		$subtype = ($subtype) ? $subtype : 'default';
	}

	if (!in_array("$type::$subtype", elgg_get_config('discovery_type_subtype_pairs'))) {
		return false;
	}

	return true;
}

/**
 * Check if the entity can be embedded
 * @param ElggEntity $entity
 * @return boolean
 */
function is_embeddable($entity) {

	if (elgg_instanceof($entity, 'site')) {
		return false;
	}

	if (!elgg_instanceof($entity)) {
		return false;
	}

	if (!is_embeddable_type($entity)) {
		return false;
	}

	if (!is_discoverable($entity)) {
		return false;
	}

	if (isset($entity->embeddable) && (bool)$entity->embeddable === true) {
		return true;
	}

	return false;
}

/**
 * Check if entity type/subtype are specified for embedding in plugin settings
 *
 * @param ElggEntity $entity
 * @param string $type
 * @param string $subtype
 * @return boolean
 */
function is_embeddable_type($entity = null, $type = '', $subtype = '') {

	if (elgg_instanceof($entity)) {
		$type = $entity->getType();
		$subtype = $entity->getSubtype();
		$subtype = ($subtype) ? $subtype : 'default';
	}

	if (!in_array("$type::$subtype", elgg_get_config('discovery_embed_type_subtype_pairs'))) {
		return false;
	}

	return true;
}

/**
 * Construct an action URL
 * 
 * @param string $provider
 * @param integer $guid
 * @param string $referrer
 * @return string
 */
function get_share_action_url($provider, $guid = 0, $referrer = '') {

	return elgg_http_add_url_query_elements(elgg_normalize_url('action/discovery/share'), array(
		'provider' => $provider,
		'guid' => $guid,
		'referrer' => $referrer
	));
}

/**
 * Get the url of a share page for the given provider
 *
 * @param string $provider
 * @param ElggEntity $entity
 */
function get_provider_url($provider, $entity = null, $referrer = '') {

	if (!elgg_instanceof($entity)) {
		$permalink = ($referrer) ? $referrer : current_page_url();
		$guid = get_guid_from_url($permalink);
		if ($guid) {
			$entity = get_entity($guid);
		}
	}

	if (!is_discoverable($entity)) {
		return false;
	}

	$site = elgg_get_site_entity();

	$shared_url = get_entity_permalink($entity);
	$title = get_discovery_title($entity);
	$description = get_discovery_description($entity);
	$tags = $entity->tags;
	$owner = $entity->getOwnerEntity();

	$elements = array();

	switch ($provider) {

		case 'facebook' :
			$base_url = "https://www.facebook.com/sharer/sharer.php";
			$elements = array(
				'u' => $shared_url,
				't' => $title,
			);
			break;

		case 'twitter' :
			$base_url = "https://twitter.com/intent/tweet";
			$via = ($owner->twitter) ? $owner->twitter : $site->twitter_site;
			$elements = array(
				'url' => $shared_url,
				'hashtags' => (is_array($tags)) ? implode(',', $tags) : $tags,
				'via' => ($via) ? str_replace('@', '', $via) : false,
			);
			break;

		case 'linkedin' :
			$base_url = "http://www.linkedin.com/shareArticle";
			$elements = array(
				'mini' => true,
				'url' => $shared_url,
				'title' => $title,
				'source' => $site->og_site_name,
				'summary' => $description,
			);
			break;

		case 'pinterest' :
			$base_url = "https://pinterest.com/pin/create/button/?url";
			$elements = array(
				'url' => $shared_url,
				'media' => get_discovery_image_url($entity),
				'description' => $title,
			);
			break;

		case 'googleplus' :
			$base_url = 'https://plus.google.com/share';
			$elements = array(
				'url' => $shared_url,
				'title' => $title,
				'summary' => $description,
			);
			break;
	}

	if ($base_url) {
		return elgg_http_add_url_query_elements($base_url, $elements);
	}

	return $shared_url;
}

/**
 * Get entity permalink
 *
 * @param ElggEntity $entity
 * @return string
 */
function get_entity_permalink($entity, $viewtype = 'default') {

	if (!elgg_instanceof($entity)) {
		return current_page_url();
	}

	$user_guid = elgg_get_logged_in_user_guid();
	$user_hash = get_user_hash($user_guid);


	$title = elgg_get_friendly_title(get_discovery_title($entity));

	$segments = array(
		PAGEHANDLER_PERMALINK,
		$viewtype,
		$user_hash,
		$entity->guid,
		$title
	);

	return elgg_normalize_url(implode('/', $segments));
}

/**
 * Sniff a URL for a known entity
 *
 * @param string $url
 * @return integer|false
 */
function get_guid_from_url($url) {

	if (!class_exists('UFCOE\\Elgg\\Url')) {
		require dirname(dirname(__FILE__)) . '/classes/UFCOE/Elgg/Url.php';
	}

	$sniffer = new Url();

	return $sniffer->getGuid($url);
}

/**
 * Get entity from URL
 * 
 * @param string $url
 * @return ElggEntity|false
 */
function get_entity_from_url($url) {
	$guid = get_guid_from_url($url);
	$entity = get_entity($guid);
	return ($entity) ? $entity : elgg_get_site_entity();
}

/**
 * Identify user by assigned hash
 *
 * @param string $hash
 * @return ElggUser|false
 */
function get_user_from_hash($hash = '') {

	if (!$hash) {
		return false;
	}

	$users = elgg_get_entities_from_metadata(array(
		'types' => 'user',
		'metadata_names' => array('discovery_permanent_hash', 'discovery_temporary_hash'),
		'metadata_values' => $hash,
		'limit' => 1,
	));

	return ($users) ? $users[0] : false;
}

/**
 * Get or assign an identifying hash to the user
 *
 * @param integer $guid
 * @return null|string
 */
function get_user_hash($guid) {

	$user = get_entity($guid);
	if (!$user) {
		$_SESSION['discovery_hash'] = md5(time() . generate_random_cleartext_password());
	}

	$hash = $user->discovery_permanent_hash;
	if (!$hash) {
		$hash = md5($user->guid . time() . generate_random_cleartext_password());
		create_metadata($user->guid, 'discovery_permanent_hash', $hash, '', $user->guid, ACCESS_PUBLIC);
	}

	return $hash;
}

/**
 * Get oEmbed representation of the page
 * 
 * @param string $origin
 * @param integer $maxwidth
 * @param integer $maxheight
 */
function get_oembed_response($origin, $format = 'json', $maxwidth = 0, $maxheight = 0) {

	$ia = elgg_set_ignore_access(true);

	$origin = urldecode($origin);
	$entity = get_entity_from_url($origin);

	$response = elgg_trigger_plugin_hook('export:entity', 'oembed', array(
		'origin' => $origin,
		'entity' => $entity,
		'maxwidth' => $maxwidth,
		'maxheight' => $maxheight,
			), array(
		'type' => 'link',
		'version' => '1.0',
		'title' => get_discovery_title($entity),
	));

	elgg_set_ignore_access($ia);

	$response['format'] = $format;
	return $response;
}

/**
 * Get OpenGraph, Twitter, Iframely tags
 * @param string $url
 * @return array
 */
function get_discovery_metatags($url) {

	$entity = get_entity_from_url($url);
	return elgg_trigger_plugin_hook('metatags', 'discovery', array(
		'entity' => $entity,
		'url' => $url,
			), array());
}

/**
 * Get discoverable title
 * @param ElggEntity $entity
 * @return string
 */
function get_discovery_title($entity) {

	if (!elgg_instanceof($entity) || !is_discoverable($entity)) {
		$entity = elgg_get_site_entity();
	}

	if (isset($entity->og_title)) {
		$title = $entity->og_title;
	} else if (isset($entity->name)) {
		$title = $entity->name;
	} else {
		$title = $entity->title;
	}

	return elgg_get_excerpt($title, 70);
}

/**
 * Get discoverable description
 * @param ElggEntity $entity
 * @return string
 */
function get_discovery_description($entity) {

	if (!elgg_instanceof($entity) || !is_discoverable($entity)) {
		$entity = elgg_get_site_entity();
	}

	if (isset($entity->og_description)) {
		$description = $entity->og_description;
	} else if (isset($entity->description)) {
		$description = $entity->description;
	}

	return elgg_get_excerpt($description, 200);
}

/**
 * Get discoverable image url
 * @param ElggEntity $entity
 * @return string
 */
function get_discovery_image_url($entity) {

	if (!elgg_instanceof($entity) || !is_discoverable($entity)) {
		$entity = elgg_get_site_entity();
	}

	if ($entity->og_icontime_og_high) {
		return $entity->getIconURL('_og_high');
	} else if ($entity->og_icontime_og_large) {
		return $entity->getIconURL('_og_large');
	} else {
		return $entity->getIconURL('_og');
	}
}

/**
 * Get discoverable keywords
 * @param ElggEntity $entity
 * @return string
 */
function get_discovery_keywords($entity) {

	if (!elgg_instanceof($entity) || !is_discoverable($entity)) {
		$entity = elgg_get_site_entity();
	}

	if (isset($entity->og_keywords)) {
		return $entity->og_keywords;
	} else if ($entity->tags) {
		return $entity->tags;
	}
}
