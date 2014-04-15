<?php

namespace hypeJunction\Discovery;

/**
 * Handle incoming discovery traffic
 * 
 * @param array $page
 * @param string $handler
 * @return boolean
 */
function page_handler_permalink($page, $handler) {

	switch ($page[0]) {

		default :
			$view_type = $page[0];
			$user_hash = $page[1];
			$guid = $page[2];

			if (!$user_hash || !$guid || !in_array($view_type, elgg_get_config('view_types'))) {
				return false;
			}

			elgg_set_viewtype($view_type);
			
			$ia = elgg_set_ignore_access(true);
			$entity = get_entity($guid);

			if (!is_discoverable($entity)) {
				elgg_set_ignore_access($ia);
				return false;
			} else {
				$forward_url = false;
				if (elgg_is_logged_in() || ($entity->access_id == ACCESS_PUBLIC && !elgg_get_config('walled_garden'))) {
					$forward_url = $entity->getURL();
				}

				$forward_url = elgg_trigger_plugin_hook('entity:referred', $entity->getType(), array(
					'entity' => $entity,
					'user_hash' => $user_hash,
					'referrer' => $_SERVER['HTTP_REFERER'],
						), $forward_url);

				if ($forward_url && $view_type == 'default') {
					forward($forward_url);
				} else {
					$title = get_discovery_title($entity);
					$content = elgg_view('framework/discovery/public', array(
						'entity' => $entity,
					));
				}
			}
			break;

		case 'image' :

			$guid = $page[1];
			$size = $page[2];

			$ia = elgg_set_ignore_access(true);
			$entity = get_entity($guid);

			if (is_discoverable($entity)) {
				set_input('guid', $guid);
				set_input('size', $size);
				require_once dirname(dirname(__FILE__)) . '/pages/og_image.php';
			}

			elgg_set_ignore_access($ia);
			return false;
	}

	if ($content) {
		if (elgg_is_xhr()) {
			echo $content;
		} else {
			$layout = elgg_view_layout('default', array(
				'title' => $title,
				'content' => $content,
			));
			echo elgg_view_page($title, $layout);
		}
		return true;
	}
	return false;
}

/**
 * Handle discovery
 *
 * @param array $page
 * @param string $handler
 * @return boolean
 */
function page_handler_opengraph($page, $handler) {

	switch ($page[0]) {

		case 'edit' :
			$guid = $page[1];
			$entity = get_entity($guid);

			if (!elgg_instanceof($entity) || !$entity->canEdit() || !is_discoverable_type($entity)) {
				return false;
			}

			$title = elgg_echo('discovery:entity:settings');
			$content = elgg_view('framework/discovery/edit', array(
				'entity' => $entity
			));
			$sidebar = false;
			$filter = false;
			break;

		case 'share' :
			$guid = $page[1];
			$entity = get_entity($guid);

			if (!$entity) {
				return false;
			}
			
			$title = elgg_echo('discovery:entity:share');
			$content = elgg_view('forms/discovery/share', array(
				'entity' => $entity
			));
			$sidebar = false;
			$filter = false;
			break;
	}

	if ($content) {
		if (elgg_is_xhr()) {
			echo $content;
		} else {
			$layout = elgg_view_layout('content', array(
				'title' => $title,
				'content' => $content,
				'filter' => $filter,
				'sidebar' => $sidebar,
			));
			echo elgg_view_page($title, $layout);
		}
		return true;
	}
	return false;
}
