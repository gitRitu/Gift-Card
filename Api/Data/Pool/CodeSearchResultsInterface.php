<?php

namespace Dotsquare\Giftcard\Api\Data\Pool;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for pool search results
 * @api
 */
interface CodeSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pool code list
     *
     * @return \Dotsquare\Giftcard\Api\Data\Pool\CodeInterface[]
     */
    public function getItems();

    /**
     * Set pool code list
     *
     * @param \Dotsquare\Giftcard\Api\Data\Pool\CodeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
