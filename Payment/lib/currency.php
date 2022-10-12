<?php
use Df\Core\Exception as DFE;
use Df\Payment\Currency as C;
use Df\Payment\Method as M;
use Magento\Quote\Model\Quote as Q;
use Magento\Sales\Model\Order as O;
/**
 * 2017-10-12               
 * @used-by dfpex_from_doc()
 * @used-by \Df\Payment\ConfigProvider::currency()
 * @used-by \Df\Payment\Method::cPayment()
 * @used-by \Df\Payment\Method::isAvailable()
 * @used-by \Df\Payment\Operation\Source::currencyC()
 * @param string|object $m
 * @return C
 */
function dfp_currency($m) {return C::f($m);}

/**
 * 2017-04-08
 * @used-by dfpex_from_doc()
 * @param mixed $a0
 * @param mixed|null $a1 [optional]
 * @return array(M, O|Q)  
 * @throws DFE
 */
function dfpex_args($a0, $a1 = null) {return ($a1
	? ($a0 instanceof M ? [$a0, df_oq($a1)] : (df_is_oq($a0) ? [df_ar($a1, M::class), $a0] : []))
	: ($a0 instanceof M ? [$a0, $a0->o()] : (df_is_oq($a0) ? [dfpm($a0), $a0] : []))) ?: df_error(
		'dfpex_args(): invalid first argument: %s.', df_type($a0)
	)
;}

/**
 * 2017-04-08
 * Converts $a from a sales document currency to the payment currency.
 * The payment currency is usually set here: «Mage2.PRO» → «Payment» → <...> → «Payment Currency».
 * @used-by \Df\Payment\Operation\Source::cFromDoc()
 * @param float $a
 * @param mixed ...$args
 * @return float
 */
function dfpex_from_doc($a, ...$args) {
	# 2020-03-02
	# The square bracket syntax for array destructuring assignment (`[…] = […]`) requires PHP ≥ 7.1:
	# https://github.com/mage2pro/core/issues/96#issuecomment-593392100
	# We should support PHP 7.0.
	list($m, $doc) = dfpex_args(...$args); /** @var M $m */ /** @var Q|O $doc */
	return dfp_currency($m)->fromOrder($a, $doc);
}