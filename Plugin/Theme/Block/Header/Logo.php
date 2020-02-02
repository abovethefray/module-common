<?php
/**
 * Copyright © Above The Fray Design, Inc. All rights reserved.
 * See ATF_COPYING.txt for license details.
 */

namespace ATF\Common\Plugin\Theme\Block\Header;

class Logo extends \Magento\Theme\Block\Html\Header\Logo
{
    /**
     * Get logo image URL
     *
     * @return string
     */
    public function getLogoAltSrc()
    {
        if (empty($this->_data['logo_alt_src'])) {
            $this->_data['logo_alt_src'] = $this->_getLogoAltUrl();
        }
        return $this->_data['logo_alt_src'];
    }


    /**
     * Retrieve logo image URL
     *
     * @return string
     */
    protected function _getLogoAltUrl()
    {
        $folderName = \Magento\Config\Model\Config\Backend\Image\Logo::UPLOAD_DIR;
        $storeLogoPath = $this->_scopeConfig->getValue(
            'design/header/logo_alt_src',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $path = $folderName . '/' . $storeLogoPath;
        $logoUrl = $this->_urlBuilder
                ->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;

        if ($storeLogoPath !== null && $this->_isFile($path)) {
            $url = $logoUrl;
        } elseif ($this->getLogoFile()) {
            $url = $this->getViewFileUrl($this->getLogoFile());
        } else {
            $url = $this->getViewFileUrl('images/logo-alt.svg');
        }
        return $url;
    }
}
