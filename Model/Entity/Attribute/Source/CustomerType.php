<?php

namespace MageSuite\BusinessCheckout\Model\Entity\Attribute\Source;

class CustomerType extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const PRIVATE = 'private';
    const BUSINESS = 'business';

    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['value' => self::PRIVATE, 'label' => __('Private')],
                ['value' => self::BUSINESS, 'label' => __('Business')]
            ];
        }
        return $this->_options;
    }
}
