<?php
namespace Df\Zf\Validate\StringT;
final class FloatT extends Parser {
	/**
	 * @override
	 * @see \Zend_Validate_Interface::isValid()
	 * @used-by df_float()
	 * @param string $v
	 * @return bool
	 */
	function isValid($v) {
		$this->prepareValidation($v);
		/**
		 * 1) Избавляет от сбоев типа
		 * «Система не смогла распознать значение «368.» типа «string» как вещественное число.»
		 * http://magento-forum.ru/topic/4648/
		 * Другими словами, думаю, что будет правильным
		 * конвертировать строки типа «368.» в вещественные числа без сбоев.
		 * 2) 368.0 === floatval('368.'), поэтому функция @see df_float()
		 * сконвертирует строку «368.» в вещественное число без проблем.
		 */
		if (is_string($v) && df_ends_with($v, '.') && ('.' !== $v)) {
			$v .= '0';
		}
		return $this->getZendValidator('en_US')->isValid($v) || $this->getZendValidator('ru_RU')->isValid($v);
	}

 	/**
	 * @override
	 * @see \Df\Zf\Validate\Type::expected()
	 * @used-by \Df\Zf\Validate\Type::_message()
	 * @return string
	 */
	protected function expected() {return 'a float';}

	/**
	 * @override
	 * @return string
	 */
	protected function getZendValidatorClass() {return 'Zend_Validate_Float';}

	/**
	 * @used-by df_float()
	 * @return self
	 */
	static function s() {static $r; return $r ? $r : $r = new self;}
}