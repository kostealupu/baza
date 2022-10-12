<?php
use Magento\Sales\Api\OrderItemRepositoryInterface as IOIR;
use Magento\Sales\Model\Order\Item as OI;
use Magento\Sales\Model\Order\ItemRepository as OIR;
use Magento\Sales\Model\ResourceModel\Order\Item\Collection as OIC;

/**
 * 2019-02-27 @deprecated It is unused.
 * @param string|OI $v
 * @param string|null $k[optional]
 * @return OI
 */
function df_oi($v, $k = null) {
	if (df_is_oi($v)) {
		$r = $v;
	}
	elseif (is_null($k)) {
		$r = df_oi_r()->get($v);
	}
	else {
		$r = df_new_om(OI::class); /** @var OI $r */
		$r->load($v, $k);
	}
	return $r;
}

/**
 * 2019-02-27
 * @used-by df_oi()
 * @return IOIR|OIR
 */
function df_oi_r() {return df_o(IOIR::class);}

/**
 * 2019-02-24
 * @used-by \Inkifi\Mediaclip\API\Entity\Order\Item::oic()
 * @used-by \Inkifi\Mediaclip\Event::oi()
 * @return OIC
 */
function df_oic() {return df_new_om(OIC::class);}