<?php

namespace AppBundle\Utils;

class CleanUrl
{

	public function clean($title)
	{
		$title = mb_strtolower($title);

		$title = str_replace(
			array(
				'ä',
				'ü',
				'ö',
				'ß'
			),
			array(
				'ae',
				'ue',
				'oe',
				'ss'
			),
			$title
		);

		$title = preg_replace('~[^a-z0-9]~', ' ', $title);
		$title = preg_replace('~\s{2,}~', ' ', $title);
		$title = preg_replace('~\s~', '-', $title);

		return $title;
	}

}
