<?php
use Df\Core\Exception as DFE;
use Df\Zf\Validate\StringT\IntT;

/**
 * @see df_is_int()
 * @used-by df_product_id()
 * @used-by dfa_key_int()
 * @used-by \Dfe\Color\Image::palette()
 * @used-by \Inkifi\Pwinty\API\Entity\Shipment::items()
 * @used-by \TFC\GoogleShopping\Att\Brand::v() (tradefurniturecompany.co.uk, https://github.com/tradefurniturecompany/google-shopping/issues/8)
 * @param mixed|mixed[] $v
 * @param bool $allowNull [optional]
 * @return int|int[]
 * @throws DFE
 */
function df_int($v, $allowNull = true) {/** @var int|int[] $r */
	if (is_array($v)) {
		$r = df_map(__FUNCTION__, $v, $allowNull);
	}
	elseif (is_int($v)) {
		$r = $v;
	}
	elseif (is_bool($v)) {
		$r = $v ? 1 : 0;
	}
	elseif ($allowNull && (is_null($v) || ('' === $v))) {
		$r = 0;
	}
	elseif (!IntT::s()->isValid($v)) {
		df_error(IntT::s()->getMessage());
	}
	else {
		$r = (int)$v;
	}
	return $r;
}

/**
 * 2015-04-13
 * 1) It does not validate item types (unlike @see df_int() )
 * 2) It works only with arrays.
 * 3) Keys are preserved: http://3v4l.org/NHgdK
 * @see dfa_key_int()
 * @used-by df_fetch_col_int()
 * @used-by df_products_update()
 * @used-by \Dfe\Color\Plugin\Swatches\Block\Adminhtml\Attribute\Edit\Options\Visual::afterGetJsonConfig()
 * @param mixed[] $v
 * @return int[]
 */
function df_int_simple(array $v) {return array_map('intval', $v);}

/**
 * @used-by \Justuno\M2\Controller\Cart\Add::execute()
 * @used-by \Justuno\M2\Controller\Cart\Add::product()
 * @see df_is_nat()
 * @param mixed $v
 * @param bool $allow0 [optional]
 * @return int
 * @throws DFE
 */
function df_nat($v, $allow0 = false) {/** @var int $r */
	$r = df_int($v, $allow0);
	$allow0 ? df_assert_ge(0, $r) : df_assert_gt0($r);
	return $r;
}

/**
 * @param mixed $v
 * @return int
 * @throws DFE
 */
function df_nat0($v) {return df_nat($v, $allow0 = true);}