<?php
namespace Dev101\SpecialPrice\Helper;

 class Custom extends \Magento\Framework\App\Helper\AbstractHelper
 {
     public function validateProductBySP($specPrice, $from, $to)
     {
         if (isset($specPrice)) {
             if (isset($to) and ($to>0)) {
                 return true;
             } else {
                 if (isset($from)and ($from<0)) {
                     return true;
                 }
             }
         } else {
             return false;
         }
     }
 }

