**Magento 2 ExtraCartTotalDataExample**

Example module that adds extra cart total data to render in the checkout summary. 
 
In this example sku, gender are added to the total items data. And message is added the total data.

```
vendor/magento/module-checkout/view/frontend/web/template/summary/item/details.html
```

```html

<!-- ko if: $parent.extension_attributes.sku -->
<div class="details-sku">
    <span class="label"><!-- ko i18n: 'Sku' --><!-- /ko --></span>
    <span class="value" data-bind="text: $parent.extension_attributes.sku"></span>
</div>
<!-- /ko -->

<!-- ko if: $parent.extension_attributes.gender -->
<div class="details-gender">
    <span class="label"><!-- ko i18n: 'Gender' --><!-- /ko --></span>
    <span class="value" data-bind="text: $parent.extension_attributes.gender"></span>
</div>
<!-- /ko -->

```