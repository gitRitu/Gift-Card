<?php

namespace Dotsquare\Giftcard\Api;

/**
 * Interface PoolManagementInterface
 * @api
 */
interface PoolManagementInterface
{
    /**
     * Generate codes for pool
     *
     * @param int $poolId
     * @param int $qty
     * @return \Dotsquare\Giftcard\Api\Data\Pool\CodeInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function generateCodesForPool($poolId, $qty);

    /**
     * Import codes to pool
     *
     * @param int $poolId
     * @param mixed $codesRawData
     * @return \Dotsquare\Giftcard\Api\Data\Pool\CodeInterface[]
     * @throws \Dotsquare\Giftcard\Api\Exception\ImportValidatorExceptionInterface
     */
    public function importCodesToPool($poolId, $codesRawData);

    /**
     * Pull code from pool
     *
     * @param int $poolId
     * @param bool $generateNew
     * @return string|null
     */
    public function pullCodeFromPool($poolId, $generateNew = true);
}
