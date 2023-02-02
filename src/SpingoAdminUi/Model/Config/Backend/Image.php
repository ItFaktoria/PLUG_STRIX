<?php

declare(strict_types=1);

namespace Spingo\SpingoAdminUi\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\Image as BaseImage;
use Spingo\SpingoApi\Api\SpingoImageConfigInterface;

class Image extends BaseImage
{
    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(SpingoImageConfigInterface::UPLOAD_DIR));
    }

    protected function _addWhetherScopeInfo()
    {
        return true;
    }

    protected function _getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'gif', 'png', 'svg'];
    }

    protected function getTmpFileName()
    {
        $tmpName = is_array($this->getValue()) ? $this->getValue()['tmp_name'] : null;
        if (isset($_FILES['groups']))
        {
            $tmpName = $_FILES['groups']['tmp_name'][$this->getGroupId()]['fields'][$this->getField()]['value'];
        }

        return $tmpName;
    }

    public function beforeSave()
    {
        $value = $this->getValue();
        $deleteFlag = is_array($value) && !empty($value['delete']);
        if ($this->isTmpFileAvailable($value) && $imageName = $this->getUploadedImageName($value))
        {
            $fileTmpName = $this->getTmpFileName();
            if ($this->getOldValue() && ($fileTmpName || $deleteFlag))
            {
                $this->_mediaDirectory->delete(SpingoImageConfigInterface::UPLOAD_DIR . '/' . $this->getOldValue());
            }
        }

        return parent::beforeSave();
    }

    private function isTmpFileAvailable($value): bool
    {
        return is_array($value) && isset($value[0]['tmp_name']);
    }

    private function getUploadedImageName($value): string
    {
        if (is_array($value) && isset($value[0]['name']))
        {
            return $value[0]['name'];
        }

        return '';
    }
}
