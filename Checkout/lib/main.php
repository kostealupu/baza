<?php
use Df\Checkout\Model\Session as DfSession;
use Df\Checkout\Session as Sess;
use Df\Core\Exception as DFE;
# 2020-05-29 https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Checkout/Helper/Cart.php
use Magento\Checkout\Helper\Cart as CartH;
use Magento\Checkout\Helper\Data as DataH;
use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session;
use Magento\Framework\Phrase;
use Magento\Sales\Model\Order as O;
/**
 * 2019-04-17
 * @used-by \Justuno\M2\Controller\Cart\Add::execute()
 * @used-by \Mangoit\MediaclipHub\Controller\Index\AddToCart::execute()
 * @return Cart
 */
function df_cart() {return df_o(Cart::class);}

/**
 * 2020-05-29
 * @used-by app/design/frontend/Codazon/fastest/seavus/Magento_Checkout/templates/cart/item/default.phtml (blushme.se)
 * @return CartH
 */
function df_cart_h() {return df_o(CartH::class);}

/**
 * 2016-07-14
 * @used-by dfp_error()
 * @used-by \Df\Payment\CustomerReturn::execute()
 * @used-by \Dfe\CheckoutCom\Handler\CustomerReturn::p()
 * @param string|Phrase ...$args
 */
function df_checkout_error(...$args) {df_checkout_message(df_format($args), false);}

/**
 * 2021-05-26
 * @used-by \Interactivated\Quotecheckout\Controller\Index\Updateordermethod::execute() (canadasatellite.ca, https://github.com/canadasatellite-ca/site/issues/116)
 * @return DataH
 */
function df_checkout_h() {return df_o(DataH::class);}

/**
 * 2016-07-14 Сообщение показывается всего на 5 секунд, а затем скрывается: https://mage2.pro/t/1871
 * @used-by df_checkout_error()
 * @param string|Phrase $s
 * @param bool $success
 */
function df_checkout_message($s, $success) {
	$sess = Sess::s(); /** @var Sess $sess */
	/** 2016-07-14 @used-by https://github.com/mage2pro/core/blob/539a6c4/Checkout/view/frontend/web/js/messages.js?ts=4#L17 */
	$sess->messages(array_merge($sess->messages(), [['text' => df_phrase($s), 'success' => $success]]));
}

/**
 * 2016-05-06
 * @used-by df_order_last()
 * @used-by df_quote()
 * @used-by \Df\Customer\Observer\CopyFieldset\OrderAddressToCustomer::execute()
 * @used-by \Df\Payment\Block\Info::ii()
 * @used-by \Df\Payment\CustomerReturn::execute()
 * @used-by \Df\Payment\W\Strategy\ConfirmPending::_handle()
 * @used-by \Dfe\AlphaCommerceHub\W\Reader::reqFilter()
 * @used-by \Dfe\CheckoutCom\Handler\CustomerReturn::p()
 * @used-by \PPCs\Core\Plugin\Checkout\Controller\Onepage\Success::beforeDispatch()
 * @return Session|DfSession
 */
function df_checkout_session() {return df_o(Session::class);}

/**
 * 2018-10-06
 * @used-by df_redirect_to_payment()
 * @used-by df_redirect_to_success()
 * @used-by \Df\Payment\Block\Info::ii()
 * @used-by \Df\Payment\W\Action::execute()
 * @used-by \Dfe\AlphaCommerceHub\W\Reader::reqFilter()
 * @used-by \Dfe\CheckoutCom\Handler\CustomerReturn::p() 
 * @used-by \Justuno\M2\Block\Js::_toHtml()
 * @param bool $required [optional]
 * @return O|null
 * @throws DFE
 */
function df_order_last($required = true) {
	$s = df_checkout_session(); /** @var Session|DfSession $s */
	return $s->getLastRealOrderId() ? $s->getLastRealOrder() : (!$required ? null : df_error());
}

/**
 * 2016-07-05
 * 2017-02-28
 * @deprecated
 * В настоящее время эта функция никем не используется.
 * Раньше она использовалась модулем allPay, но теперь там намного лучше обработка возвращаения покупателя в магазин:
 * https://github.com/mage2pro/allpay/blob/1.1.31/Charge.php?ts=4#L365-L378
 * @return string
 */
function df_url_checkout_success() {return df_url('checkout/onepage/success');}