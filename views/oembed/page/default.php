<?php

namespace hypeJunction\Discovery;

$format = get_input('format');

switch ($format) {

	case 'json' :
		header("Content-type: application/json");
		echo $vars['body'];
		break;

	case 'xml' :
		header("Content-type: application/xml");

		echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
		echo '<oembed>';
		echo $vars['body'];
		echo '</oembed>';
		break;

	default :
		$topbar = elgg_view('page/elements/topbar', $vars);
		$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
		$header = elgg_view('page/elements/header', $vars);
		$body = elgg_view('page/elements/body', $vars);
		$footer = elgg_view('page/elements/footer', $vars);

		header("Content-type: text/html; charset=UTF-8");

		$lang = get_current_language();
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang; ?>" lang="<?php echo $lang; ?>">
			<head>
				<?php echo elgg_view('page/elements/head', $vars); ?>
			</head>
			<body class="elgg-oembed">
				<div class="elgg-page elgg-page-default">
					<div class="elgg-page-body">
						<div class="elgg-inner">
							<?php echo $body; ?>
						</div>
					</div>
				</div>
				<?php echo elgg_view('page/elements/foot'); ?>
			</body>
		</html>
	<?php
}