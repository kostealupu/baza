<?php
namespace Df\Framework\Plugin\App;
use Df\Security\BlackList as B;
use Magento\Framework\App\Http as Sb;
use Magento\Framework\App\ResponseInterface as IResponse;
# 2021-09-16
# "Implement an ability to temporary ban visitors with a particular IP address":
# https://github.com/mage2pro/core/issues/159
final class Http {
	/**
	 * 2021-04-19
	 * @param Sb $sb
	 * @param \Closure $f
	 * @return IResponse
	 */
	function aroundLaunch(Sb $sb, \Closure $f) {return B::has() ? df_403() : $f();}
}