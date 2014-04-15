<?php

/**
 * Social sharing and content identity
 *
 * @package hypeJunction
 * @subpackage Discovery
 *
 * @author Ismayil Khayredinov <ismayil@hypejunction.com>
 */

namespace hypeJunction\Discovery;

const PLUGIN_ID = 'hypeDiscovery';
const PAGEHANDLER_PERMALINK = 'permalink';
const PAGEHANDLER_OPENGRAPH = 'opengraph';

define('HYPEDISCOVERY_BYPASS_ACCESS', elgg_get_plugin_setting('bypass_access', PLUGIN_ID));

$discovery_type_subtype_pairs = elgg_get_plugin_setting('discovery_type_subtype_pairs', PLUGIN_ID);
$discovery_type_subtype_pairs = ($discovery_type_subtype_pairs) ? unserialize($discovery_type_subtype_pairs) : array();
elgg_set_config('discovery_type_subtype_pairs', $discovery_type_subtype_pairs);

$embed_type_subtype_pairs = elgg_get_plugin_setting('embed_type_subtype_pairs', PLUGIN_ID);
$embed_type_subtype_pairs = ($embed_type_subtype_pairs) ? unserialize($embed_type_subtype_pairs) : array();
elgg_set_config('discovery_embed_type_subtype_pairs', $embed_type_subtype_pairs);

$providers = elgg_get_plugin_setting('providers', PLUGIN_ID);
$providers = ($providers) ? unserialize($providers) : array();
elgg_set_config('discovery_providers', $providers);

require_once __DIR__ . '/lib/config.php';
require_once __DIR__ . '/lib/functions.php';
require_once __DIR__ . '/lib/events.php';
require_once __DIR__ . '/lib/hooks.php';
require_once __DIR__ . '/lib/page_handlers.php';

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');
elgg_register_event_handler('pagesetup', 'system', __NAMESPACE__ . '\\pagesetup');

function init() {

	/**
	 * CSS & JS
	 */
	elgg_register_css('discovery.styles', '/mod/' . PLUGIN_ID . '/stylesheets/styles.css');
	elgg_load_css('discovery.styles');

	elgg_extend_view('js/elgg', 'js/framework/discovery/js');

	/**
	 * PAGE & URL HANDLING
	 */
	elgg_register_page_handler(PAGEHANDLER_PERMALINK, __NAMESPACE__ . '\\page_handler_permalink');
	elgg_register_page_handler(PAGEHANDLER_OPENGRAPH, __NAMESPACE__ . '\\page_handler_opengraph');

	/**
	 * ACTIONS
	 */
	elgg_register_action(PLUGIN_ID . '/settings/save', __DIR__ . '/actions/settings/save.php');
	elgg_register_action('discovery/site', __DIR__ . '/actions/discovery/site.php', 'admin');
	elgg_register_action('discovery/share', __DIR__ . '/actions/discovery/share.php', 'public');
	elgg_register_action('discovery/edit', __DIR__ . '/actions/discovery/edit.php');

	/**
	 * HOOKS
	 */
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', __NAMESPACE__ . '\\public_pages');
	elgg_register_plugin_hook_handler('register', 'menu:entity', __NAMESPACE__ . '\\entity_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:extras', __NAMESPACE__ . '\\extras_menu_setup');
	elgg_register_plugin_hook_handler('entity:icon:url', 'all', __NAMESPACE__ . '\\entity_icon_url');
	elgg_register_plugin_hook_handler('export:entity', 'oembed', __NAMESPACE__ . '\\oembed_entity_export');

	elgg_register_plugin_hook_handler('metatags', 'discovery', __NAMESPACE__ . '\\discovery_header_metatags');
	
	/**
	 * EVENTS
	 */
	elgg_register_event_handler('login', 'user', __NAMESPACE__ . '\\save_temp_user_hash');

	/**
	 * VIEWS
	 */
	elgg_extend_view('page/elements/head', 'framework/discovery/head/alternate');
	elgg_extend_view('page/elements/head', 'framework/discovery/head/metatags');

	/**
	 * oEmbed
	 */
	elgg_register_viewtype('oembed');
	elgg_register_viewtype_fallback('oembed');
	
	expose_function('oembed', __NAMESPACE__ . '\\get_oembed_response', array(
		'origin' => array(
			'type' => 'string',
			'required' => true,
		),
		'format' => array(
			'type' => 'string',
			'required' => true,
		),
		'maxwidth' => array(
			'type' => 'int',
			'required' => false,
		),
		'maxheight' => array(
			'type' => 'int',
			'required' => false,
		),
	));
}
