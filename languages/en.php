<?php

namespace hypeJunction\Discovery;

$english = array(

	'admin:discovery' => 'Discovery',
	'menu:page:header:discovery' => 'Discovery',
	'admin:discovery:settings' => 'Settings',
	'admin:discovery:site' => 'Site Profile',

	'discovery:settings:bypass_access' => 'Bypass access system',
	'discovery:settings:bypass_access:help' => 'Bypass the access system to allow any content saved for "Logged in users" to be visible remotely. This will only expose the title, description and icon. This is helpful when your site is in a walled garden mode, but you would still like to generate traffic to your site',

	'discovery:settings:discovery_type_subtype_pairs' => 'Conent that can be shared with the outside world',
	'discovery:settings:discovery_type_subtype_pairs:help' => 'Note the Bypass access system setting and the effect it might have on privacy',

	'discovery:settings:embed_type_subtype_pairs' => 'Content that can be embedded on other sites',

	'discovery:settings:providers' => 'Outbound',
	'discovery:settings:providers:help' => 'Please which providers can be used for outbound sharing',
	
	/**
	 * PROVIDERS
	 */
	'discovery:provider:facebook' => 'Facebook',
	'discovery:provider:twitter' => 'Twitter',
	'discovery:provider:linkedin' => 'LinkedIn',
	'discovery:provider:pinterest' => 'Pinterest',
	'discovery:provider:googleplus' => 'Google+',

	/**
	 * UI
	 */
	'discovery:og:help' => 'Use this form to improve how your content appears on social sites when shared. For best results, upload an image that\'s larger than 1200x630 pixels. For optimum results, your image should not be smaller than 600 x 315px',
	'discovery:share' => 'Share on %s',
	'discovery:entity:settings' => 'Content discovery',
	'discovery:entity:share' => 'Share',
	'discovery:entity:permalink' => 'Permalink',
	'discovery:entity:embed_code' => 'Embed Code',
	'discovery:og:site_image' => 'Site Image',
	'discovery:og:site_image:help' => 'For best results, upload an image that\'s larger than 1200x630 pixels. For optimum results, your image should not be smaller than 600 x 315px',
	'discovery:og:site_name' => 'Site Name',
	'discovery:og:title:default' => 'Default Page Title (when it can\'t be identified otherwise)',
	'discovery:og:description' => 'Site Description',
	'discovery:og:image' => 'Content Image',
	'discovery:og:title' => 'Title',
	'discovery:og:description' => 'Description',
	'discovery:og:keywords' => 'Keywords',
	'discovery:og:discoverable' => 'Enable discovery',
	'discovery:og:embeddable' => 'Allow embedding',
	'discovery:fb:app_id' => 'Facebook App ID',
	'discovery:twitter:site' => 'Twitter Site Account (e.g. @hypeJunction)',
	'discovery:edit' => 'Edit discovery settings',
	
	/**
	 * ACTIONS
	 */
	'discovery:share:error:no_url' => 'Sorry, it seems you can\'t share this content',
	'discovery:og_image:upload_fail' => 'Your image failed to upload',
	'discovery:og_image:resize_fail' => 'Resizing failed for %s image. Minimum width of the image should be %spx',
	'discovery:og_image:size:_og' => 'the default',
	'discovery:og_image:size:_og_large' => 'the large',
	'discovery:og_image:size:_og_high' => 'the high resolution',
	'discovery:site:success' => 'Discovery details for your site have been updated',
	'discovery:site:error' => 'Discovery details for your site failed to update',
);

add_translation('en', $english);
