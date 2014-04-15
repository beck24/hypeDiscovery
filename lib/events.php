<?php

namespace hypeJunction\Discovery;

/**
 * Register menus
 */
function pagesetup() {

	if (elgg_is_admin_logged_in()) {
		elgg_register_menu_item('page', array(
			'name' => 'discovery:settings',
			'href' => 'admin/plugin_settings/' . PLUGIN_ID,
			'text' => elgg_echo('admin:discovery:settings'),
			'context' => 'admin',
			'section' => 'discovery'
		));

		elgg_register_menu_item('page', array(
			'name' => 'discovery:site',
			'href' => 'admin/discovery/site',
			'text' => elgg_echo('admin:discovery:site'),
			'context' => 'admin',
			'section' => 'discovery'
		));
	}
}

/**
 * Store temp user hash
 *
 * @param string $event		Equals 'login'
 * @param string $type		Equals 'user'
 * @param ElggUser $user
 */
function save_temp_user_hash($event, $type, $user) {

	if (isset($_SESSION['discovery_hash'])) {
		create_metadata($user->guid, 'discovery_temp_hash', $_SESSION['discovery_hash'], '', $user->guid, ACCESS_PUBLIC);
		unset($_SESSION['discovery_hash']);
	}

	return true;
}
