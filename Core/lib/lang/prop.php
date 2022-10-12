<?php
/**
 * 2019-09-08
 * @used-by df_n_get()
 * @used-by df_n_set()
 * @used-by \CanadaSatellite\Bambora\Session::failedCount() (canadasatellite.ca, https://github.com/canadasatellite-ca/bambora/issues/14)
 * @used-by \Df\API\Client::logging()
 * @used-by \Df\API\FacadeOptions::resC()
 * @used-by \Df\API\FacadeOptions::silent()
 * @used-by \Df\Checkout\Session::customer()
 * @used-by \Df\Checkout\Session::messages()
 * @used-by \Df\Customer\Session::needConfirm()
 * @used-by \Df\Customer\Session::ssoId()
 * @used-by \Df\Customer\Session::ssoProvider()
 * @used-by \Df\Customer\Session::ssoRegistrationData()
 * @used-by \Dfe\Sift\API\Client::cfg()
 * @used-by \Dfe\TBCBank\Session::data()
 * @used-by \Frugue\Core\Session::country()
 * @used-by \Frugue\Core\Session::redirected()
 * @used-by \Inkifi\Pwinty\API\Entity\Image::attributes()
 * @used-by \Inkifi\Pwinty\API\Entity\Image::copies()
 * @used-by \Inkifi\Pwinty\API\Entity\Image::sizing()
 * @used-by \Inkifi\Pwinty\API\Entity\Image::type()
 * @used-by \Inkifi\Pwinty\API\Entity\Image::url()
 * @used-by \Inkifi\Pwinty\API\Entity\Order::magentoOrder()
 * @used-by \Wolf\Filter\Customer::categoryPath()
 * @used-by \Wolf\Filter\Customer::garage()
 */
const DF_N = 'df-null';

/**
 * @used-by \Df\Payment\Init\Action::preconfigured()
 * @param mixed|string $v
 * @return mixed|null
 */
function df_n_get($v) {return DF_N === $v ? null : $v;}

/**
 * @used-by \Dfe\FacebookLogin\Customer::_dob()
 * @param mixed|null $v
 * @return mixed|string
 */
function df_n_set($v) {return is_null($v) ? DF_N : $v;}

/**
 * 2019-04-05
 * 2019-09-08 Now it supports static properties.
 * @used-by \CanadaSatellite\Bambora\Response::authCode() (canadasatellite.ca, https://github.com/canadasatellite-ca/bambora/issues/1)
 * @used-by \CanadaSatellite\Bambora\Response::avsResult() (canadasatellite.ca, https://github.com/canadasatellite-ca/bambora/issues/1)
 * @used-by \CanadaSatellite\Bambora\Response::errorType() (canadasatellite.ca, https://github.com/canadasatellite-ca/bambora/issues/1)
 * @used-by \CanadaSatellite\Bambora\Response::messageId() (canadasatellite.ca, https://github.com/canadasatellite-ca/bambora/issues/1)
 * @used-by \CanadaSatellite\Bambora\Response::messageText (canadasatellite.ca, https://github.com/canadasatellite-ca/bambora/issues/1)
 * @used-by \CanadaSatellite\Bambora\Response::trnApproved() (canadasatellite.ca, https://github.com/canadasatellite-ca/bambora/issues/1)
 * @used-by \CanadaSatellite\Bambora\Response::trnId() (canadasatellite.ca, https://github.com/canadasatellite-ca/bambora/issues/1)
 * @used-by \CanadaSatellite\Bambora\Session::failedCount() (canadasatellite.ca, https://github.com/canadasatellite-ca/bambora/issues/14)
 * @used-by \Df\API\Client::logging()
 * @used-by \Df\API\FacadeOptions::resC()
 * @used-by \Df\API\FacadeOptions::silent()
 * @used-by \Df\Checkout\Session::customer()
 * @used-by \Df\Checkout\Session::messages()
 * @used-by \Df\Customer\Session::needConfirm()
 * @used-by \Df\Customer\Session::ssoId()
 * @used-by \Df\Customer\Session::ssoProvider()
 * @used-by \Df\Customer\Session::ssoRegistrationData()
 * @used-by \Dfe\Sift\API\Client::cfg()
 * @used-by \Dfe\TBCBank\Session::data()
 * @used-by \Frugue\Core\Session::country()
 * @used-by \Frugue\Core\Session::redirected()
 * @used-by \Inkifi\Pwinty\API\Entity\Image::attributes()
 * @used-by \Inkifi\Pwinty\API\Entity\Image::copies()
 * @used-by \Inkifi\Pwinty\API\Entity\Image::sizing()
 * @used-by \Inkifi\Pwinty\API\Entity\Image::type()
 * @used-by \Inkifi\Pwinty\API\Entity\Image::url()
 * @used-by \Inkifi\Pwinty\API\Entity\Order::magentoOrder()
 * @used-by \Wolf\Filter\Customer::categoryPath()
 * @used-by \Wolf\Filter\Customer::garage()
 * @param object|null|\ArrayAccess $o
 * @param mixed|string $v [optional]
 * @param string|mixed|null $d [optional]
 * @param string|null $type [optional]
 * @return mixed|object|\ArrayAccess|null
 */
function df_prop($o, $v = DF_N, $d = null, $type = null) {/** @var object|mixed|null $r */
	/**
	 * 2019-09-08
	 * 1) My 1st solution was comparing $v with `null`,
	 * but it is wrong because it fails for a code like `$object->property(null)`.
	 * 2) My 2nd solution was using @see func_num_args():
	 * «How to tell if optional parameter in PHP method/function was set or not?»
	 * https://stackoverflow.com/a/3471863
	 * It is wrong because the $v argument is alwaus passed to df_prop()
	 */
	$isGet = DF_N === $v; /** @vae bool $isGet */
	if ('int' === $d) {
		$type = $d; $d = null;
	}
	/** @var string $k */
	if (is_null($o)) { # 2019-09-08 A static call.
		$k = df_caller_m();
		static $s; /** @var array(string => mixed) $s */
		if ($isGet) {
			$r = dfa($s, $k, $d);
		}
		else {
			$s[$k] = $v;
			$r = null;
		}
	}
	else {
		$k = df_caller_f();
		if ($o instanceof \ArrayAccess) {
			if ($isGet) {
				$r = !$o->offsetExists($k) ? $d : $o->offsetGet($k);
			}
			else {
				$o->offsetSet($k, $v);
				$r = $o;
			}
		}
		else {
			$a = '_' . __FUNCTION__; /** @var string $a */
			if (!isset($o->$a)) {
				$o->$a = [];
			}
			if ($isGet) {
				$r = dfa($o->$a, $k, $d);
			}
			else {
				$prop = &$o->$a;
				$prop[$k] = $v;
				$r = $o;
			}
		}
	}
	return $isGet && 'int' === $type ? intval($r) : $r;
}