<?php

declare(strict_types=1);

namespace Spingo\SpingoFrontendUi\ViewModel;

use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * @api
 */
class SpingoProductViewButtonViewModel implements ArgumentInterface
{
    /**
     * @var Repository
     */
    private $assetRepository;

    public function __construct(Repository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    public function getButtonImage(): string
    {
        return $this->assetRepository->getUrl('Spingo_SpingoFrontendUi::images/spingo_button.png');
    }

    public function getImageTitle(): string
    {
        return (string)__('Spingo - defer payment');
    }
}
