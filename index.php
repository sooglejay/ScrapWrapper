<?php

/**
 * Created by PhpStorm.
 * User: jianwei
 * Date: 2017/7/16
 * Time: 下午1:13
 */
// not use
echo "not use! Please refer to ScrapWeb/cliScriptScrap.php";
//class Test
//{
//    public function getBoughtSecurityInfo()
//    {
//        assert($this->webDriver instanceof RemoteWebDriver);
//        $boughtSecInfo = array();
//        $url = 'http://www.63si.com.cn:8888/lswtqt/112888/Q2003.jhtml';
//        $this->webDriver->get($url);
//        // Wait for at most 20s and retry every 500ms if it the title is not correct.
//        $this->webDriver->wait()->until(
//            WebDriverExpectedCondition::titleIs('个人办事-参保信息查询')
//        );
//        $infoListElems = $this->webDriver->findElements(WebDriverBy::className('slick-grid-canvas'));
//        $currentInfoElems = $infoListElems[0];// //img[@class="itemImage"]/@src
//        $historyInfoElems = $infoListElems[1];
//        $currentInfo = $currentInfoElems->findElements(WebDriverBy::cssSelector("div.ui-widget-content > div.slick-cell"));
//        $historyInfo = $historyInfoElems->findElements(WebDriverBy::cssSelector("div.ui-widget-content > div.slick-cell"));
//        $currentInfoFormattedArray = array();
//        $historyInfoFormattedArray = array();
//        for ($i = 0; $i < count($currentInfo); $i++) {
//            $itemFormattedArray = array();
//            for ($j = $i; $j < $i + 8; $j++) {
//                $itemFormattedArray[] = $currentInfo[$j]->getText();
//            }
//            $i += 7;
//            $currentInfoFormattedArray[] = $itemFormattedArray;
//        }
//        for ($i = 0; $i < count($historyInfo); $i++) {
//            $itemFormattedArray = array();
//            for ($j = $i; $j < $i + 8; $j++) {
//                $itemFormattedArray[] = $historyInfo[$j]->getText();
//            }
//            $i += 7;
//            $historyInfoFormattedArray[] = $itemFormattedArray;
//        }
//        $boughtSecInfo["historyArrayInfo"] = $historyInfoFormattedArray;
//        $boughtSecInfo["currentArrayInfo"] = $currentInfoFormattedArray;
//        var_dump($boughtSecInfo);
//        return $boughtSecInfo;
//    }
//}
