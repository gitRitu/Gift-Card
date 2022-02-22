<?php

namespace Dotsquare\Giftcard\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for pool search results
 * @api
 */
interface PoolSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pool list
     *
     * @return \Dotsquare\Giftcard\Api\Data\PoolInterface[]
     */
    public function getItems();

    /**
     * Set pool list
     *
     * @param \Dotsquare\Giftcard\Api\Data\PoolInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
