<?php
/**
 * Copyright © Above The Fray Design, Inc. All rights reserved.
 * See ATF_COPYING.txt for license details.
 */

namespace ATF\Common\Helper;

use ATF\Common\ThirdParty\Inflect;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** @var ScopeConfigInterface */
    protected $_scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Asset service
     *
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $_assetRepo;

    /**
     * @var RequestInterface
     */
    protected $_request;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Repository $_assetRepo,
        RequestInterface $request
    ) {
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        $this->_assetRepo = $_assetRepo;
        $this->_request = $request;
        parent::__construct($context);
    }

    public function pluralize($word)
    {
        $lowerCaseWord = strtolower($word);
        $finalWord = ucfirst(Inflect::singularize($lowerCaseWord) == $lowerCaseWord ?
            Inflect::pluralize(strtolower($lowerCaseWord)) : $lowerCaseWord);

        return $finalWord;
    }

    /**
     * Retrieve url of a view file
     *
     * @param string $fileId
     * @param array $params
     * @return string
     */
    public function getViewFileUrl($fileId, array $params = [])
    {
        try {
            $params = array_merge(['_secure' => $this->getRequest()->isSecure()], $params);
            return $this->_assetRepo->getUrlWithParams($fileId, $params);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->_logger->critical($e);
            return $this->_getNotFoundUrl();
        }
    }

    /**
     * Get request
     *
     * @return \Magento\Framework\App\RequestInterface
     */
    public function getRequest()
    {
        return $this->_request;
    }

    public function getImageConfig($path, $scopeType = ScopeInterface::SCOPE_STORE)
    {
        $media_dir = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        $image = $this->getConfig($path, $scopeType);
        return !$image ? null : $media_dir . 'atf_image/' . $image;
    }

    public function getConfig($path, $scopeType = ScopeInterface::SCOPE_STORE)
    {
        return $this->_scopeConfig->getValue($path, $scopeType);
    }

    public function getConfigArray($path, $scopeType = ScopeInterface::SCOPE_STORE)
    {
        if ($value = $this->getConfig($path, $scopeType)) {
            return explode(',', $value);
        }

        return [];
    }

    public function underscoreToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

    public function truncateDescription($description)
    {
        return $this->truncate($description, 255, ['html' => true]);
    }

    /**
     * Truncates text.
     *
     * Cuts a string to the length of $length and replaces the last characters
     * with the ending if the text is longer than length.
     *
     * ### Options:
     *
     * - `ending` Will be used as Ending and appended to the trimmed string
     * - `exact` If false, $text will not be cut mid-word
     * - `html` If true, HTML tags would be handled correctly
     *
     * @param string $text String to truncate.
     * @param integer $length Length of returned string, including ellipsis.
     * @param array $options An array of html attributes and options.
     * @return string Trimmed string.
     * @access public
     * @link http://book.cakephp.org/view/1469/Text#truncate-1625
     */
    public function truncate($text, $length = 100, $options = [])
    {
        $html = '';
        $ending = '';
        $exact = true;

        $default = ['ending' => '...', 'exact' => true, 'html' => false];
        $options = array_merge($default, $options);
        extract($options);

        if ($html) {
            if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            $totalLength = mb_strlen(strip_tags($ending));
            $openTags = [];
            $truncate = '';

            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
            foreach ($tags as $tag) {
                if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                    if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                        array_unshift($openTags, $tag[2]);
                    } elseif (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                        $pos = array_search($closeTag[1], $openTags);
                        if ($pos !== false) {
                            array_splice($openTags, $pos, 1);
                        }
                    }
                }
                $truncate .= $tag[1];

                $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                if ($contentLength + $totalLength > $length) {
                    $left = $length - $totalLength;
                    $entitiesLength = 0;
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entitiesLength <= $left) {
                                $left--;
                                $entitiesLength += mb_strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }

                    $truncate .= mb_substr($tag[3], 0, $left + $entitiesLength);
                    break;
                } else {
                    $truncate .= $tag[3];
                    $totalLength += $contentLength;
                }
                if ($totalLength >= $length) {
                    break;
                }
            }
        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
            }
        }
        if (!$exact) {
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                if ($html) {
                    $bits = mb_substr($truncate, $spacepos);
                    preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                    if (!empty($droppedTags)) {
                        foreach ($droppedTags as $closingTag) {
                            if (!in_array($closingTag[1], $openTags)) {
                                array_unshift($openTags, $closingTag[1]);
                            }
                        }
                    }
                }
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }
        $truncate .= $ending;

        if ($html) {
            foreach ($openTags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
