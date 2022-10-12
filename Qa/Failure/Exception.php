<?php
namespace Df\Qa\Failure;
use \Exception as E;
use Df\Core\Exception as DFE;
final class Exception extends \Df\Qa\Failure {
	/**
	 * @override
	 * @see \Df\Qa\Failure::main()
	 * @used-by \Df\Qa\Failure::report()
	 * @return string
	 */
	protected function main() {
		$r = $this->_e->messageL(); /** @var string $r */
		return !$this->_e->isMessageHtml() ? $r : strip_tags($r);
	}

	/**
	 * @override
	 * @see \Df\Qa\Failure::postface()
	 * @used-by \Df\Qa\Failure::report()
	 * @return string
	 */
	protected function postface() {return $this->sections($this->sections($this->_e->comments()), parent::postface());}

	/**
	 * @override
	 * @see \Df\Qa\Failure::stackLevel()
	 * @used-by \Df\Qa\Failure::postface()
	 * @return int
	 */
	protected function stackLevel() {return $this->_e->getStackLevelsCountToSkip();}

	/**
	 * @override
	 * @see \Df\Qa\Failure::trace()
	 * @used-by \Df\Qa\Failure::postface()
	 * @return array(array(string => string|int))
	 */
	protected function trace() {return df_ef($this->_e)->getTrace();}

	/**
	 * 2021-10-04
	 * @used-by i()
	 * @used-by main()
	 * @used-by postface()
	 * @used-by stackLevel()
	 * @used-by trace()
	 * @var DFE
	 */
	private $_e;

	/**
	 * @used-by df_log_l()
	 * @param E $e
	 * @return self
	 */
	static function i(E $e) {$r = new self; $r->_e = DFE::wrap($e); return $r;}
}