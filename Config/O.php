<?php
namespace Df\Config;
use Df\Core\Exception as DFE;
use Df\Framework\Form\Element\Checkbox;
/**
 * 2017-01-24
 * @see \Df\Config\ArrayItem
 * @see \Df\Typography\Font
 * Наследуемся от @see \Df\Core\O,
 * потому что метод @see \Df\Config\O::v()
 * использует метод @see \Df\Core\O::a()
 */
class O extends \Df\Core\O {
	/**
	 * 2016-08-02
	 * @used-by \Df\Config\Backend\Serialized::validate()
	 * @see \Df\Typography\Font::validate()
	 * @see \Dfe\AllPay\InstallmentSales\Plan\Entity::validate()
	 * @throws DFE
	 */
	function validate() {}

	/**
	 * 2015-12-30
	 * @used-by \Df\Typography\Font::bold()
	 * @used-by \Df\Typography\Font::enabled()
	 * @used-by \Df\Typography\Font::italic()
	 * @used-by \Df\Typography\Font::underline()
	 * @used-by \Dfe\CurrencyFormat\O::delimitSymbolFromAmount()
	 * @used-by \Dfe\CurrencyFormat\O::showDecimals()
	 * @param bool|callable $d [optional]
	 * @param string|null $k [optional]
	 * @return bool
	 */
	final protected function b($d = false, $k = null) {return $this->filter(
		function($v) use($d) {return Checkbox::b($v, $d);}, $d, $k
	);}

	/**
	 * 2016-08-10
	 * @used-by \Df\Typography\Font::scale_horizontal()
	 * @used-by \Df\Typography\Font::scale_vertical()
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::fee()
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::rate()
	 * @used-by \Doormall\Shipping\Partner\Entity::fee()
	 * @uses df_float()
	 * @param float|callable $d [optional]
	 * @param string|null $k [optional]
	 * @return float
	 */
	final protected function f($d = 0.0, $k = null) {return $this->filter('df_float', $d, $k);}

	/**
	 * 2016-08-10
	 * @uses df_int()
	 * @param int|callable $d [optional]
	 * @param string|null $k [optional]
	 * @return int
	 */
	final protected function i($d = 0, $k = null) {return $this->filter('df_int', $d, $k);}

	/**
	 * 2016-08-10
	 * @uses df_nat()
	 * @param int|callable $d [optional]
	 * @param string|null $k [optional]
	 * @return int
	 */
	final protected function nat($d = null, $k = null) {return $this->filter('df_nat', $d, $k);}

	/**
	 * 2016-08-10
	 * @uses df_nat0()
	 * @param int|callable $d [optional]
	 * @param string|null $k [optional]
	 * @return int
	 */
	final protected function nat0($d = 0, $k = null) {return $this->filter('df_nat0', $d, $k);}

	/**
	 * 2015-12-30
	 * @used-by v0()
	 * @used-by \Df\Typography\Font::color()
	 * @used-by \Df\Typography\Font::letter_case()
	 * @used-by \Dfe\CurrencyFormat\O::code()
	 * @used-by \Dfe\CurrencyFormat\O::decimalSeparator()
	 * @used-by \Dfe\CurrencyFormat\O::symbolPosition()
	 * @used-by \Dfe\CurrencyFormat\O::thousandsSeparator()
	 * @used-by \Dfe\Sift\PM\Entity::id()
	 * @used-by \Dfe\Sift\PM\Entity::sGateway()
	 * @used-by \Dfe\Sift\PM\Entity::sType()
	 * @used-by \Doormall\Shipping\Partner\Entity::id()
	 * @used-by \Doormall\Shipping\Partner\Entity::title()
	 * @param mixed|callable $d [optional]
	 * @param string|null $k [optional]
	 * @return mixed
	 */
	final protected function v($d = null, $k = null) {
		$k = $k ?: df_caller_f();
		return $this->a(df_const($this, $k, $k), $d);
	}

	/**
	 * 2020-02-05
	 * It returns `null` if a backend user did not chose a value of a dropdown
	 * (in this case the frontend part returns the "0" string
	 * which is associated with a label like "-- select a value --").
	 * @used-by \Dfe\Sift\PM\Entity::sGateway()
	 * @used-by \Dfe\Sift\PM\Entity::sType()
	 * @param mixed|null $d
	 * @param string|null $k
	 * @return string|null
	 */
	final protected function v0($d = null, $k = null) {return '0' !== ($r = $this->v(null, $k ?: df_caller_f())) ? $r : $d;}

	/**
	 * 2016-08-10
	 * @used-by b()
	 * @used-by f()
	 * @used-by i()
	 * @used-by nat()
	 * @used-by nat0()
	 * @param callable $f
	 * @param mixed|null $d [optional]
	 * @param string|null $k [optional]
	 * @return mixed
	 */
	private function filter(callable $f, $d = null, $k = null) {return 
		dfc($this, function($f, $d, $k) {return
			call_user_func($f, $this->v($d, $k))
		;}, [$f, $d, $k ?: df_caller_f(1)])
	;}
}