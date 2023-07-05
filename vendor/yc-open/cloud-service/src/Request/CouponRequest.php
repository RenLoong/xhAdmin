<?php
namespace YcOpen\CloudService\Request;

use YcOpen\CloudService\Request;
use YcOpen\CloudService\Validator;

/**
 * 优惠券相关接口
 * Class CouponRequest
 * @package YcOpen\CloudService\Request
 */
class CouponRequest extends Request
{
    /**
     * 获取可用优惠券
     * @return CouponRequest
     */
    public function getAvailableCoupon()
    {
        $this->setUrl('Coupon/getAvailableCoupon');
        $validator=new Validator;
        $validator->rules([
            'type'=>'required'
        ]);
        $this->validator=$validator;
        return $this;
    }
    public function getCouponList()
    {
        $this->setUrl('Coupon/getCouponList');
        return $this;
    }
}