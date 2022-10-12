<?php
use Df\Core\Exception as DFE;

/**
 * 2016-10-25 Оказалось, что в ядре нет такой функции.
 * @used-by df_bt_has()
 * @used-by df_ends_with()
 * @used-by df_handle_prefix()
 * @used-by df_modules_my()
 * @used-by df_oq_sa()
 * @used-by df_sales_email_sending()
 * @used-by df_starts_with()
 * @used-by ikf_oi_pid()
 * @used-by mnr_recurring()
 * @used-by \Df\Framework\Plugin\View\Layout::afterIsCacheable()
 * @used-by \Df\Payment\Info\Report::addAfter()
 * @used-by \Df\Payment\Method::amountFactor()
 * @used-by \Df\Payment\TM::confirmed()
 * @used-by \Dfe\Stripe\Method::cardType()
 * @used-by \Frugue\Core\Plugin\Sales\Model\Quote::afterGetAddressesCollection()
 * @used-by \Inkifi\Mediaclip\API\Entity\Order\Item::mProduct()
 * @used-by \Inkifi\Mediaclip\Event::_areAllOIAvailableForDownload()
 * @used-by \Inkifi\Mediaclip\Event::oi()
 * @used-by \TFC\Core\Plugin\Catalog\Block\Product\View\GalleryOptions::afterGetOptionsJson()
 * @param array|callable|\Traversable $a1
 * @param array|callable|\Traversable $a2
 * @param mixed|mixed[] $pAppend [optional]
 * @param mixed|mixed[] $pPrepend [optional]
 * @param int $keyPosition [optional]
 * @return mixed|null
 * @throws DFE
 */
function df_find($a1, $a2, $pAppend = [], $pPrepend = [], $keyPosition = 0) {
	# 2020-03-02
	# The square bracket syntax for array destructuring assignment (`[…] = […]`) requires PHP ≥ 7.1:
	# https://github.com/mage2pro/core/issues/96#issuecomment-593392100
	# We should support PHP 7.0.
	list($a, $f) = dfaf($a1, $a2); /** @var array|\Traversable $a */ /** @var callable $f */
	$pAppend = df_array($pAppend); $pPrepend = df_array($pPrepend);
	$r = null; /** @var mixed|null $r */
	foreach ($a as $k => $v) {/** @var int|string $k */ /** @var mixed $v */ /** @var mixed[] $primaryArgument */
		switch ($keyPosition) {
			case DF_BEFORE:
				$primaryArgument = [$k, $v];
				break;
			case DF_AFTER:
				$primaryArgument = [$v, $k];
				break;
			default:
				$primaryArgument = [$v];
		}
		if ($fr = call_user_func_array($f, array_merge($pPrepend, $primaryArgument, $pAppend))) {
			$r = !is_bool($fr) ? $fr : $v;
			break;
		}
	}
	return $r;
}

/**
 * 2020-04-25
 * @used-by dfa_r()
 * @used-by \VegAndTheCity\Core\Plugin\Mageplaza\Search\Helper\Data::afterGetProducts()
 * @param array $a
 * @param string $k
 * @param mixed $d [optional]
 * @return mixed|null
 */
function dfa_r(array $a, $k, $d = null) {/** @var mixed|null $r */
	if (isset($a[$k])) {
		$r = $a[$k];
	}
	else foreach ($a as $ak => $av) {/** @var string $ak */ /** @var mixed $av */
		if (is_array($av) && !is_null($r = dfa_r($av, $k))) {
			break;
		}
	}
	return isset($r) ? $r : $d;
}