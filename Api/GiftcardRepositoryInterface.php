<?php

namespace Dotsquare\Giftcard\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Giftcard CRUD interface
 * @api
 */
interface GiftcardRepositoryInterface
{
    /**
     * Save giftcard
     *
     * @param \Dotsquare\Giftcard\Api\Data\GiftcardInterface $giftcard
     * @return \Dotsquare\Giftcard\Api\Data\GiftcardInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Dotsquare\Giftcard\Api\Data\GiftcardInterface $giftcard);

    /**
     * Retrieve Gift Card by id
     *
     * @param int $giftcardId
     * @return \Dotsquare\Giftcard\Api\Data\GiftcardInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($giftcardId);

    /**
     * Retrieve Gift Card by code
     *
     * @param string $giftcardCode
     * @param int|null $websiteId
     * @return \Dotsquare\Giftcard\Api\Data\GiftcardInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByCode($giftcardCode, $websiteId = null);

    /**
     * Retrieve giftcards matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Dotsquare\Giftcard\Api\Data\GiftcardSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete giftcard
     *
     * @param \Dotsquare\Giftcard\Api\Data\GiftcardInterface $giftcard
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Dotsquare\Giftcard\Api\Data\GiftcardInterface $giftcard);

    /**
     * Delete giftcard by ID
     *
     * @param int $giftcardId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($giftcardId);
}
