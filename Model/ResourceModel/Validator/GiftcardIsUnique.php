<?php

namespace Dotsquare\Giftcard\Model\ResourceModel\Validator;

use Dotsquare\Giftcard\Api\Data\GiftcardInterface;
use Dotsquare\Giftcard\Api\Data\Pool\CodeInterface as PoolCodeInterface;
use Dotsquare\Giftcard\Model\Source\YesNo;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\EntityManager\MetadataPool;

/**
 * Class GiftcardIsUnique
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel\Validator
 */
class GiftcardIsUnique
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        MetadataPool $metadataPool,
        ResourceConnection $resourceConnection
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Check unique Gift Card code
     *
     * @param string $code
     * @return bool
     */
    public function validate($code)
    {
        $giftcardMetaData = $this->metadataPool->getMetadata(GiftcardInterface::class);
        $connection = $this->resourceConnection->getConnectionByName($giftcardMetaData->getEntityConnectionName());

        $bind = ['code' => $code];
        $select = $connection->select()
            ->from($this->resourceConnection->getTableName($giftcardMetaData->getEntityTable()))
            ->where('code = :code');
        if ($connection->fetchRow($select, $bind)) {
            return false;
        }

        $bind = [
            'code' => $code,
            'used' => YesNo::NO
        ];
        $poolCodeMetaData = $this->metadataPool->getMetadata(PoolCodeInterface::class);
        $connection = $this->resourceConnection->getConnectionByName($poolCodeMetaData->getEntityConnectionName());
        $select = $connection->select()
            ->from($this->resourceConnection->getTableName($poolCodeMetaData->getEntityTable()))
            ->where('code = :code')
            ->where('used = :used');
        if ($connection->fetchRow($select, $bind)) {
            return false;
        }
        return true;
    }
}
