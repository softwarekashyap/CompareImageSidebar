<?php

namespace Kashyap\CompareImageSidebar\Plugin;

use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Helper\Product\Compare;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Pricing\Helper\Data;

class AddImageToCompareProductsPlugin
{
    protected $helper;

    protected $imageHelperFactory;

    protected $productRepository;

    public function __construct(
        Compare $helper,
        ImageFactory $imageHelperFactory,
        Data $priceHelper,
        ProductRepository $productRepository
    )
    {
        $this->helper = $helper;
        $this->_priceHelper = $priceHelper;
        $this->imageHelperFactory = $imageHelperFactory;
        $this->productRepository = $productRepository;
    }

    public function afterGetSectionData(\Magento\Catalog\CustomerData\CompareProducts $subject, $result)
    {
        $images = [];
        $price = [];

        foreach ($this->helper->getItemCollection() as $item) {

            $imageHelper = $this->imageHelperFactory->create();

            try {
                $product = $this->productRepository->getById($item->getId());

                $images[$item->getId()] = $imageHelper->init($product, 'recently_compared_products_grid_content_widget')->getUrl();
                $finalPrice = $product->getFinalPrice();
                $formattedCurrencyValue = $this->_priceHelper->currency($finalPrice, true, false);

                $priceHtml = "<div class='compare-price'>".$formattedCurrencyValue."</div>";
                $price[$item->getId()] = $priceHtml;
            } catch (\Exception $ex) {
                $images[$item->getId()] = $imageHelper->getDefaultPlaceholderUrl();
                $price[$item->getId()] = $priceHtml;
            }

        }

        $items = $result['items'];

        foreach ($items as &$item) {
            $item['image_src'] = $images[$item['id']];
            $item['product_price'] = $price[$item['id']];
        }

        $result['items'] = $items;

        return $result;
    }
}