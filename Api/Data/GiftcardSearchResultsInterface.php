<?php

namespace Dotsquare\Giftcard\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for Gift Card search results
 * @api
 */
interface GiftcardSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Gift Card list
     *
     * @return \Dotsquare\Giftcard\Api\Data\GiftcardInterface[]
     */
    public function getItems();

    /**
     * Set Gift Card list
     *
     * @param \Dotsquare\Giftcard\Api\Data\GiftcardInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
