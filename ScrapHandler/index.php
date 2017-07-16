<?php
// An example of using php-webdriver.
namespace Facebook\WebDriver;
require_once('./../Communication/ComHandler.php');

use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Interactions\Touch\WebDriverTouchScreen;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Support\Events\EventFiringWebDriver;
use Facebook\WebDriver\Support\Events\EventFiringWebElement;

require_once('vendor/autoload.php');

class ScrapHandle
{
    private $userName;
    private $password;
    private $validateCode;
    public $validateImgElem;
    public $validateNumberElem;
    public $userNameElem;
    public $passwordElem;
    public $loginElem;

    private $loginURL = 'http://www.63si.com.cn:8888/lswtqt/toLogin.jhtml';
    private $host = 'http://localhost:4444/wd/hub'; // this is the default
    private $capabilities;
    private $webDriver;
    private $timeOut = 5000;


    /**
     * config web driver,
     * at first, scrap handler send validate code image to web view
     * and then, you have to post user name\password\validateCode to here
     */
    private function initWebDriver()
    {
        $this->capabilities = DesiredCapabilities::chrome();
        $this->webDriver = RemoteWebDriver::create($this->host, $this->capabilities, $this->timeOut);
        $this->webDriver->get($this->loginURL);

        $this->userNameElem = $this->webDriver->findElement(WebDriverBy::id('username'));
//        $this->userNameElem->sendKeys(\ComHandler::$userName);

        $this->passwordElem = $this->webDriver->findElement(WebDriverBy::id('password'));
//        $this->passwordElem->sendKeys(\ComHandler::$password);

        $this->validateNumberElem = $this->webDriver->findElement(WebDriverBy::id('captcha_gr'));
//        $this->validateNumberElem->sendKeys(\ComHandler::$validateCode);

        $this->webDriver->wait(3);
        $url = $this->validateNumberElem->getText();
        $curl = curl_init($url);
        $filename = date("Ymdhis") . ".jpg";
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $imageData = curl_exec($curl);
        curl_close($curl);
        $tp = @fopen($filename, 'a');
        fwrite($tp, $imageData);
        fclose($tp);

    }

    /**
     * get validate code image link and show to scrap web view
     * @return null
     */
    public function getValidateCodeLinkStr()
    {
        $validateCodeLinkStr = null;
        try {
            $this->validateImgElem = $this->webDriver->findElement(WebDriverBy::id('codeimg'));
        } catch (\Exception $e) {
        } finally {
            if (!is_null($this->validateImgElem)) {
                $validateCodeLinkStr = $this->validateImgElem->getAttribute("src");
            }
        }
        return $validateCodeLinkStr;
    }

    /**
     * ScrapHandle constructor.
     */
    public function __construct()
    {
        $this->initWebDriver();
        assert($this->webDriver instanceof RemoteWebDriver);
        $this->webDriver->wait(3);
        $this->doLogin();
        $this->getBoughtSecurityInfo();
        file_put_contents("./../validateImgLink.txt", $this->getValidateCodeLinkStr());


    }

    /**
     * do Login
     * you have to input validate code and wait some seconds for js download completely
     */
    public function doLogin()
    {
        assert($this->webDriver instanceof RemoteWebDriver);

        try {
            if ($this->webDriver instanceof JavascriptExecutor) {
                $this->webDriver->executeScript("login();");
            }
        } catch (\Exception $e) {
            echo "error 108 dologin";
        }

        // Wait for at most 20s and retry every 500ms if it the title is not correct.
        $this->webDriver->wait()->until(
            WebDriverExpectedCondition::titleIs('个人用户中心')
        );

    }

    public function getBoughtSecurityInfo()
    {
        assert($this->webDriver instanceof RemoteWebDriver);
        $boughtSecInfo = array();
        $url = 'http://www.63si.com.cn:8888/lswtqt/112888/Q2003.jhtml';
        $this->webDriver->get($url);
        // Wait for at most 20s and retry every 500ms if it the title is not correct.
        $this->webDriver->wait()->until(
            WebDriverExpectedCondition::titleIs('个人办事-参保信息查询')
        );
        $infoListElems = $this->webDriver->findElements(WebDriverBy::className('slick-grid-canvas'));
        $currentInfoElems = $infoListElems[0];// //img[@class="itemImage"]/@src
        $historyInfoElems = $infoListElems[1];
        $currentInfo = $currentInfoElems->findElements(WebDriverBy::cssSelector("div.ui-widget-content > div.slick-cell"));
        $historyInfo = $historyInfoElems->findElements(WebDriverBy::cssSelector("div.ui-widget-content > div.slick-cell"));
        $currentInfoFormattedArray = array();
        $historyInfoFormattedArray = array();
        for ($i = 0; $i < count($currentInfo); $i++) {
            $itemFormattedArray = array();
            for ($j = $i; $j < $i + 8; $j++) {
                $itemFormattedArray[] = $currentInfo[$j]->getText();
            }
            $i += 7;
            $currentInfoFormattedArray[] = $itemFormattedArray;
        }
        for ($i = 0; $i < count($historyInfo); $i++) {
            $itemFormattedArray = array();
            for ($j = $i; $j < $i + 8; $j++) {
                $itemFormattedArray[] = $historyInfo[$j]->getText();
            }
            $i += 7;
            $historyInfoFormattedArray[] = $itemFormattedArray;
        }
        $boughtSecInfo["historyArrayInfo"] = $historyInfoFormattedArray;
        $boughtSecInfo["currentArrayInfo"] = $currentInfoFormattedArray;
        var_dump($boughtSecInfo);
        return $boughtSecInfo;
    }

    public function getData()
    {

    }

    public function doPostToOtherSite($urlToPost, $dataArray)
    {
        // where are we posting to?
        $url = $urlToPost;

// what post fields?
        $fields = $dataArray;

// build the urlencoded data
        $postvars = http_build_query($fields);

// open connection
        $ch = curl_init();

// set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);

// execute post
        $result = curl_exec($ch);

// close connection
        curl_close($ch);
    }

}

$scrapHandler = new ScrapHandle();

?>




