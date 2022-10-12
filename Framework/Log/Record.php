<?php
namespace Df\Framework\Log;
use Df\Core\O;
use Exception as E;
# 2021-09-08
final class Record {
	/**
	 * 2021-09-08
	 * @used-by \Df\Framework\Log\Dispatcher::handle()
	 * @param array(string => mixed) $d
	 */
	function __construct(array $d) {$this->_d = new O($d);}

	/**
	 * 2021-09-08
	 * @used-by \Df\Framework\Log\Dispatcher::handle()
	 * @used-by \Df\Framework\Log\Handler\NoSuchEntity::_p()
	 * @param string|null $e [optional]
	 * @return E|null|bool
	 */
	function e($e = null) {
		$r = $this->d('context/exception'); /** @var E|null $r */
		return !$r || !$e ? $r : $r instanceof $e;
	}

	/**
	 * 2021-09-08
	 * @used-by \Df\Framework\Log\Handler\Cookie::_p()
	 * @used-by \Df\Framework\Log\Handler\PayPal::_p()
	 * @param string|string[]|null $s [optional]
	 * @return string|bool
	 */
	function msg($s = null) {
		$r = $this->d('message'); /** @var string $r */
		return null === $s ? $r : df_starts_with($r, $s);
	}

	/**
	 * 2021-09-08
	 * @used-by e()
	 * @used-by msg()
	 * @param string|string[]|null $k [optional]
	 * @param string|null $d [optional]
	 * @return array(string => mixed)|mixed|null
	 * @return O
	 */
	private function d($k = null, $d = null) {return $this->_d->a($k, $d);}

	/**
	 * 2021-09-08
	 * @used-by __construct()
	 * @used-by d()
	 * @var O
	 */
	private $_d;
}