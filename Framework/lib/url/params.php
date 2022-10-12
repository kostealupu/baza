<?php
use Magento\Framework\App\ActionInterface as IA;

/**
 * @used-by df_url()
 * @used-by df_url_backend()
 * @used-by df_url_frontend()
 * @return array(string => bool)
 */
function df_nosid() {return ['_nosid' => true];}

/**
 * 2020-01-19
 * @see \Magento\Store\App\Response\Redirect::_getUrl()
 * @used-by \Frugue\Store\Switcher::params()
 * @param string|null $u [optional]
 * @return array(string => string)
 */
function df_url_param_redirect($u = null) {return [IA::PARAM_NAME_URL_ENCODED => df_url_h()->getEncodedUrl($u)];}