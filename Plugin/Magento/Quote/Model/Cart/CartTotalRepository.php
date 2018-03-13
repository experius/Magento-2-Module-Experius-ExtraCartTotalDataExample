<?php

namespace Experius\ExtraCartTotalDataExample\Plugin\Magento\Quote\Model\Cart;

/**
 * Class CartTotalRepository
 * @package Experius\ExtraCartTotalDataExample\Plugin\Magento\Quote\Model\Cart
 */
class CartTotalRepository
{
    /**
     * Quote repository.
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var \Magento\Quote\Api\Data\TotalsItemExtensionFactory
     */
    private $extensionItemFactory;

    /**
     * @var \Magento\Quote\Api\Data\TotalsExtensionFactory
     */
    private $extensionFactory;

    /**
     * CartTotalRepository constructor.
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Magento\Quote\Api\Data\TotalsItemExtensionFactory $extensionItemFactory
     * @param \Magento\Quote\Api\Data\TotalsItemFactory $extensionFactory
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Quote\Api\Data\TotalsItemExtensionFactory $extensionItemFactory,
        \Magento\Quote\Api\Data\TotalsExtensionFactory $extensionFactory
    ) {
        $this->extensionItemFactory = $extensionItemFactory;
        $this->extensionFactory = $extensionFactory;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Quote\Model\Cart\CartTotalRepository $subject
     * @param callable $proceed
     * @param $cartId
     * @return \Magento\Quote\Api\Data\TotalsInterface
     */
    public function aroundGet(\Magento\Quote\Model\Cart\CartTotalRepository $subject, callable $proceed, $cartId)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        /** @var \Magento\Quote\Api\Data\TotalsInterface $quoteTotals */
        $quote = $this->quoteRepository->getActive($cartId);
        $quoteTotals = $proceed($cartId);
        $currentItems = $quoteTotals->getItems();

        foreach ($quote->getAllVisibleItems() as $index => $item) {
            if (!isset($currentItems[$index])) {
                continue;
            }

            /* @var \Magento\Quote\Model\Cart\Totals\Item $currentItem */
            $currentItem = $currentItems[$index];

            if ($currentItem->getExtensionAttributes() === null) {
                $extensionAttributes = $this->extensionItemFactory->create();
                $currentItem->setExtensionAttributes($extensionAttributes);
            }

            $currentItem->getExtensionAttributes()->setSku($item->getSku());
            $currentItem->getExtensionAttributes()->setGender($item->getProduct()->getGender());

            $items[$index] = $currentItem;
        }

        $quoteTotals->setItems($items);

        if ($quoteTotals->getExtensionAttributes() === null) {
            $extensionAttributes = $this->extensionFactory->create();
            $quoteTotals->setExtensionAttributes($extensionAttributes);
        }

        $quoteTotals->getExtensionAttributes()->setMessage('Hello Summary');
        
        return $quoteTotals;
    }
}
