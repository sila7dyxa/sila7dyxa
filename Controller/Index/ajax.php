<?php

namespace Dev101\SpecialPrice\Controller\Index;

class ajax extends \Magento\Framework\App\Action\Action
{
    protected $resultJsonFactory;
    protected $localeDate;
    protected $view;
    protected $remaining;
    protected $jsonHelper;
    protected $resultPageFactory;
    protected $_productRepository;
    protected $rule;
    protected $dateHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Catalog\Block\Product\View $view,
        \Dev101\SpecialPrice\Block\Custom $remaining,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\CatalogRule\Model\ResourceModel\Rule $rule,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateHelper,
        array $data= []
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->localeDate=$localeDate;
        $this->view=$view;
        $this->remaining=$remaining;
        $this->jsonHelper=$jsonHelper;
        $this->_productRepository = $productRepository;
        $this->rule = $rule;
        $this->dateHelper = $dateHelper;

        parent::__construct($context);
    }

    public function getRemainingTime($id)
    {
        $getId=$this->_productRepository->getById($id);
        $currentDate = $this->localeDate->date()->format('Y-m-d H:i:s');
        $currentDateTs=strtotime($currentDate);
        $finalTimeSpecial=$getId->getSpecialToDate();

        $productId=$id;
        $websiteId=[$getId->getWebsiteIds()][0];
        $customerGroupId = '0';
        $rules = $this->rule->getRulesFromProduct($currentDateTs, $websiteId, $customerGroupId, $productId);
        $finalTimeCatalogRule= $this->dateHelper->date('Y-m-d H:i:s', ($rules[0]['to_time']));

        $finalTimeSpecialTs=strtotime($finalTimeSpecial);
        $finalTimeCatalogRuleTs=strtotime($finalTimeCatalogRule);
        $remTimeCatRulTs=$finalTimeCatalogRuleTs-$currentDateTs;
        $remTimeSpecPrice=($finalTimeSpecialTs-$currentDateTs);
        $earlyEnd=min($remTimeCatRulTs, $remTimeSpecPrice);

        if ($earlyEnd==$remTimeCatRulTs) {
            $earlyEndDF=date('Y/m/d H:i:s', $finalTimeCatalogRuleTs);
        } else {
            $earlyEndDF=date('Y/m/d H:i:s', $finalTimeSpecialTs);
        }
        $earlyEndDFTs=strtotime($earlyEndDF);
        $remainingTimeSeconds=($earlyEndDFTs-$currentDateTs);
        $remainingTimeDays=$remainingTimeSeconds/60/60/24;
        $remainingTimeDays1=(int)($remainingTimeDays) . ' Days ';
        $remainingTimeHours=($remainingTimeDays-(int)($remainingTimeDays))*24;
        $remainingTimeHours1=(int)($remainingTimeHours) . ' hours ';
        $remainingTimeMinutes=($remainingTimeHours-(int)($remainingTimeHours))*60;
        $remainingTimeMinutes1=(int)($remainingTimeMinutes) . ' minutes ';
        $remainingTimeSec=($remainingTimeMinutes-(int)($remainingTimeMinutes))*60;
        $remainingTimeSec1=(int)($remainingTimeSec) . ' sec ';
        $remaining=$remainingTimeDays1 . $remainingTimeHours1 . $remainingTimeMinutes1;
        $this->remaining=$remaining;
        return  $remaining;
    }
    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            //_______________________________________________________
            $result = $this->resultJsonFactory->create();
            $currentProductId=$this->getRequest()->getParam('currentproduct');
            $remainingTime =$this->getRemainingTime($currentProductId);

            $data = $remainingTime;
            $result->setData($data);
            return $result;

            //_______________________________________________________
        }
        $text= "Error 404 page Wrong Call";
        echo  $text;
        exit;
    }
}
