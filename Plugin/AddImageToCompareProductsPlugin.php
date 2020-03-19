<?php
/**
 * @author Kashyap Team
 * @copyright Copyright (c) 2018 Kashyap (http://kashyapsoftware.com/)
 * @package Kashyap_CompareImageSidebar
*/

namespace Kashyap\CompareImageSidebar\Plugin;

use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Helper\Product\Compare;
use Magento\Catalog\Model\ProductRepository;

class AddImageToCompareProductsPlugin
{
    protected $helper;

    protected $imageHelperFactory;

    protected $productRepository;

    public function __construct(
        Compare $helper,
        ImageFactory $imageHelperFactory,
        ProductRepository $productRepository
    )
    {
        $this->helper = $helper;
        $this->imageHelperFactory = $imageHelperFactory;
        $this->productRepository = $productRepository;
    }

    public function afterGetSectionData(\Magento\Catalog\CustomerData\CompareProducts $subject, $result)
    {

        $images = [];

        foreach ($this->helper->getItemCollection() as $item) {

            $imageHelper = $this->imageHelperFactory->create();

            try {
                $product = $this->productRepository->getById($item->getId());

                $images[$item->getId()] = $imageHelper->init($product, 'recently_compared_products_grid_content_widget')->getUrl();
            } catch (\Exception $ex) {
                $images[$item->getId()] = $imageHelper->getDefaultPlaceholderUrl();
            }

        }

        $items = $result['items'];

        foreach ($items as &$item) {
            $item['image_src'] = $images[$item['id']];
        }

        $result['items'] = $items;

        return $result;
    }
}