<?php

/**
 * MessageSize
 *
 * Plugin to limit the overall size of a message by restricting
 * the cumulative attachment size
 *
 * @version 1.1
 * @author Timo Kousa
 * @modified by Philip Weir
 */
class messagesize extends rcube_plugin
{
	public $task = 'mail';

	function init()
	{
		$this->add_hook('attachment_upload', array($this, 'check_size'));
		$this->load_config();
	}

	function check_size($args)
	{
		$limit = parse_bytes(rcmail::get_instance()->config->get('max_message_size', '10MB'));
		$total = $args['size'];

		if ($_SESSION['compose'] && $_SESSION['compose']['attachments']) {
			foreach ($_SESSION['compose']['attachments'] as $attachment)
				$total += $attachment['size'];
		}

		if ($total > $limit) {
			$this->add_texts('localization/');
			$args['error'] = sprintf($this->gettext('overallsizeerror'), show_bytes(parse_bytes($limit)));
			$args['abort'] = true;
		}

		return $args;
	}
}

?>