# Magento 2 show product image in sidebar compare list
Kashyap CompareImageSidebar extension allows you to display product images in the sidebar. Default Magento 2 does not display the product image. In Magento 2 only display product name.

### Installation
- Download zip file of this extension
- Place all the files of the extension in your Magento 2 installation in the folder app/code/Kashyap/CompareImageSidebar
- Enable the extension: php bin/magento --clear-static-content module:enable Kashyap_CompareImageSidebar
- Upgrade db scheme: php bin/magento setup:upgrade
- Deploy Static Content: php bin/magento setup:static-content:deploy -f Developer Mode
- Deploy Static Content: php bin/magento setup:static-content:deploy Production Mode

