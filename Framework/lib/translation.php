<?php
use Magento\Framework\Phrase as P;
/**
 * 2016-07-14
 * @param P|string $s
 * @return P
 */
function df_phrase($s) {return $s instanceof P ? $s : __($s);}

/**
 * 2015-09-29
 * @used-by df_map_to_options_t()
 * @param string[] $strings
 * @param bool $now [optional]
 * @return string[]
 */
function df_translate_a($strings, $now = false) {
	$r = array_map('__', $strings); /** @var string[] $r */
	return !$now ? $r : array_map('strval', $r);
}

/**
 * 2017-02-09
 * It does the same as @see \Magento\Framework\Filter\TranslitUrl::filter(), but without lower-casing:
 * '歐付寶 all/Pay' => 'all-Pay'
 * If you need lower-casing, then use @see df_translit_url_lc() instead.
 *
 * Example #1: '歐付寶 all/Pay':
 * @see df_fs_name => 歐付寶-allPay
 * @see df_translit =>  all/Pay
 * @see df_translit_url => all-Pay
 * @see df_translit_url_lc => all-pay
 *
 * Example #2: '歐付寶 O'Pay (allPay)':
 * @see df_fs_name => 歐付寶-allPay
 * @see df_translit =>  allPay
 * @see df_translit_url => allPay
 * @see df_translit_url_lc => allpay
 *
 * @used-by df_translit_url_lc()
 * @used-by \Df\Sentry\Client::tags()
 * @param string $s
 * @return string
 */
function df_translit_url($s) {return trim(preg_replace('#[^0-9a-z]+#i', '-', df_translit($s)), '-');}

/**
 * 2016-10-31
 * 2017-02-09
 * В настоящее время никем не используется.
 * '歐付寶 all/Pay' => 'all-pay'
 *
 * Пример №1: '歐付寶 all/Pay':
 * @see df_fs_name => 歐付寶-allPay
 * @see df_translit =>  all/Pay
 * @see df_translit_url => all-Pay
 * @see df_translit_url_lc => all-pay
 *
 * Пример №2: '歐付寶 O'Pay (allPay)':
 * @see df_fs_name => 歐付寶-allPay
 * @see df_translit =>  allPay
 * @see df_translit_url => allPay
 * @see df_translit_url_lc => allpay
 *
 * @param string $s
 * @return string
 */
function df_translit_url_lc($s) {return strtolower(df_translit_url($s));}