<?php

namespace Qweb\ImageVariants\Fusion;

/*
 * This file is part of the Neos.Neos package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\Image;
use Neos\Media\Domain\Model\ImageVariant;
use Neos\Media\Domain\Model\ThumbnailConfiguration;
use Neos\Neos\Fusion\ImageUriImplementation;

/**
 * Render an AssetInterface: object. Accepts the same parameters as the uri.image ViewHelper of the Neos.Media package:
 * asset, width, maximumWidth, height, maximumHeight, allowCropping, allowUpScaling.
 *
 */
class VariantImageUriImplementation extends ImageUriImplementation
{
    /**
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * Variant
     *
     * @return string
     */
    public function getVariant()
    {
        return $this->fusionValue('variant');
    }

    /**
     * Returns a processed image path
     *
     * @return string
     * @throws \Exception
     */
    public function evaluate()
    {
        $asset = $this->getAsset();
        $variantIdentifier = $this->getVariant();

        if (!$asset instanceof AssetInterface) {
            throw new \Exception('No asset given for rendering.', 1415184217);
        }

        if ($asset instanceof ImageVariant) {
            $asset = $asset->getOriginalAsset();
        }

        if (!$asset instanceof Image) {
            throw new \Exception('No image given as asset.');
        }

        /**
         * @var $asset Image
         */

        $imageVariants = $asset->getVariants();
        $variant = null;

        /**
         * @var $imageVariant ImageVariant
         */
        foreach ($imageVariants as $imageVariant) {
            if ($imageVariant->getPresetVariantName() === $variantIdentifier) {
                $variant = $imageVariant;
                break;
            }
        }

        if (is_null($variant)) {
            throw new \Exception('No variant found with name ' . $variantIdentifier . ' for asset.');
        }

        $thumbnailConfiguration = new ThumbnailConfiguration(
            $this->getWidth(),
            $this->getMaximumWidth(),
            $this->getHeight(),
            $this->getMaximumHeight(),
            $this->getAllowCropping(),
            $this->getAllowUpScaling(),
            $this->getAsync(),
            $this->getQuality(),
            $this->getFormat()
        );

        $request = $this->getRuntime()->getControllerContext()->getRequest();
        $thumbnailData = $this->assetService->getThumbnailUriAndSizeForAsset(
            $variant,
            $thumbnailConfiguration,
            $request
        );

        if ($thumbnailData === null) {
            return '';
        }
        return $thumbnailData['src'];
    }
}
