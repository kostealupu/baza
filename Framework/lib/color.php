<?php
/**
 * 2019-08-21
 * https://stackoverflow.com/a/15202130
 * @param string $hex #ff9900
 * @return int[]
 */
function df_hex2rgb($hex) {return sscanf($hex, "#%02x%02x%02x");}

/**
 * 2015-11-29
 * @uses dechex()
 * http://php.net/manual/function.dechex.php
 * http://stackoverflow.com/a/15202156
 * @param int[] $rgb
 * @param string $prefix [optional]
 * @return string
 */
function df_rgb2hex(array $rgb, $prefix = '') {return
	$prefix . df_pad0(6, implode(array_map('dechex', df_int($rgb))))
;}

