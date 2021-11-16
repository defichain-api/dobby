<?php

if (!function_exists('str_truncate_middle')) {
	function str_truncate_middle(string $text, int $maxChars = 25, string $filler = '...'): string
	{
		$length = strlen($text);
		$fillerLength = strlen($filler);

		return ($length > $maxChars)
			? substr_replace($text, $filler, ($maxChars - $fillerLength) / 2, $length - $maxChars + $fillerLength)
			: $text;
	}
}
