<?php
namespace Dev101\SpecialPrice\Model;

class Model extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var \Magento\CatalogRule\Model\ResourceModel\Rule
     */
    protected $ruleResource;

    public function __construct(
        \Magento\CatalogRule\Model\ResourceModel\Rule $rule
    ) {
        $this->ruleResource = $rule;
    }

    /**
     * @param int|string $date
     * @param int $websiteId
     * @param int $customerGroupId
     * @param int $productId
     */
    public function getRules($date, $websiteId, $customerGroupId, $productId)
    {

    /** @var [] $rules catalog rules */
        $rules = $this->ruleResource->getRulesFromProduct($date, $websiteId, $customerGroupId, $productId);
    }
}
