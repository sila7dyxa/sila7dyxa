<?php
namespace Dev101\SpecialPrice\Block;

class Custom extends \Magento\Catalog\Block\Product\View
{
    protected $_customHelper;
    protected $localeDate;
    protected $remaining;
    protected $product;
    protected $rules;
    protected $rule;
    protected $dateHelper;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Dev101\SpecialPrice\Helper\Custom $customHelper,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Review\Block\Product\View\ListView $product,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateHelper,
        \Magento\CatalogRule\Model\Rule $rules,
        \Magento\CatalogRule\Model\ResourceModel\Rule $rule,
        array $data = []
    ) {
        $this->_customHelper = $customHelper;
        $this->localeDate = $localeDate;
        $this->product = $product;
        $this->dateHelper = $dateHelper;
        $this->rules = $rules;
        $this->rule = $rule;
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
    }

    /**
     * Get passed product discount end date at the passed format
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Db_Statement_Exception
     * **/

    public function getSpecPrice()
    {
        $currentProduct= $this->getProduct();
        $textSpecPrice=" $";
        $valueOfSpecial=round(($currentProduct->getFinalPrice()), 2);
        return  $valueOfSpecial . $textSpecPrice;
    }
    public function getCurrentDate()
    {
        return $this->localeDate->date()->format('Y-m-d H:i:s');
    }

    public function getTimeSpecPrice()
    {
        $currentProduct= $this->getProduct();
        $finalTimeSpecial=$currentProduct->getSpecialToDate();
        $finalTimeSpecialTs=strtotime($finalTimeSpecial);
        $finalTimeSpecialDF=date('Y/m/d H:i:s', $finalTimeSpecialTs);

        return $finalTimeSpecialDF;
    }

    public function whoEarlyStops()
    {
        $currentProduct= $this->getProduct();
        $currentDate = $this->localeDate->date()->format('Y-m-d H:i:s');
        $currentDateTs=strtotime($currentDate);
        $finalTimeSpecial=$currentProduct->getSpecialToDate();
        $finalTimeSpecialTs=strtotime($finalTimeSpecial);
        $finalTimeCatalogRule= $this->getTimeCatalogRule();
        $finalTimeCatalogRuleTs=strtotime($finalTimeCatalogRule);
        $remTimeCatRulTs=$finalTimeCatalogRuleTs-$currentDateTs;
        $remTimeSpecPrice=($finalTimeSpecialTs-$currentDateTs);
        $earlyEnd=min($remTimeCatRulTs, $remTimeSpecPrice);
        if($earlyEnd==$remTimeCatRulTs){
        $earlyEndDF=date('Y/m/d H:i:s', $finalTimeCatalogRuleTs);
        } else {
            $earlyEndDF=date('Y/m/d H:i:s', $finalTimeSpecialTs);
        }
        return $earlyEndDF;
    }

    public function getTimeCatalogRule()
    {
        $currentDate = $this->localeDate->date()->format('Y-m-d H:i:s');
        $date=strtotime($currentDate);
        $currentProduct= $this->getProduct();
        $productId=$currentProduct->getId();
        $websiteId=[$currentProduct->getWebsiteIds()][0];
        $customerGroupId = '0';
        $rules = $this->rule->getRulesFromProduct($date, $websiteId, $customerGroupId, $productId);
        $finalTimeCatalogRule= $this->dateHelper->date('Y-m-d H:i:s', ($rules[0]['to_time']));

        return $finalTimeCatalogRule;
    }

    public function getTimeFrom()
    {
        $currentProduct= $this->getProduct();
        $currentDate = $this->localeDate->date()->format('Y-m-d H:i:s');
        $currentDateTs=strtotime($currentDate);
        $startTimeSpecial=$currentProduct->getSpecialFromDate();
        $startTimeSpecialTS=strtotime($startTimeSpecial);
        $startTimeSeconds=($startTimeSpecialTS-$currentDateTs);
        return $startTimeSeconds;
    }
    public function getTimeTo()
    {
        $currentProduct= $this->getProduct();
        $currentDate = $this->localeDate->date()->format('Y-m-d H:i:s');
        $currentDateTs=strtotime($currentDate);
        $finalTimeSpecial=$currentProduct->getSpecialToDate();
        $finalTimeSpecialTs=strtotime($finalTimeSpecial);
        $remainingTimeSeconds=($finalTimeSpecialTs-$currentDateTs);
        return $remainingTimeSeconds;
    }

    public function getTheId()
    {
        return $this->product->getProductId();
    }

    public function isAvailable()
    {
        $currentProduct = $this->getProduct();
        $specPrice=$currentProduct->getSpecialPrice();
        $from=$this->getTimeFrom();
        $to=$this->whoEarlyStops();

        return $this->_customHelper->validateProductBySP($specPrice, $from, $to);
    }
}
