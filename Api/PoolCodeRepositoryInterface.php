<?php

namespace Dotsquare\Giftcard\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * PoolCode CRUD interface
 * @api
 */
interface PoolCodeRepositoryInterface
{
    /**
     * Retrieve code by id
     *
     * @param int $codeId
     * @return \Dotsquare\Giftcard\Api\Data\Pool\CodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($codeId);

    /**
     * Retrieve pool codes matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Dotsquare\Giftcard\Api\Data\Pool\CodeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete pool code
     *
     * @param \Dotsquare\Giftcard\Api\Data\Pool\CodeInterface $code
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Dotsquare\Giftcard\Api\Data\Pool\CodeInterface $code);

    /**
     * Delete pool code by ID
     *
     * @param int $codeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($codeId);
}
