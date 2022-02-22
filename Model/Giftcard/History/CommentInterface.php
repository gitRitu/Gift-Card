<?php

namespace Dotsquare\Giftcard\Model\Giftcard\History;

use Dotsquare\Giftcard\Api\Data\Giftcard\History\EntityInterface as HistoryEntityInterface;

/**
 * Interface CommentInterface
 *
 * @package Dotsquare\Giftcard\Model\Giftcard\History
 */
interface CommentInterface
{
    /**
     * Retrieve comment type
     *
     * @return int
     */
    public function getType();

    /**
     * Retrieve comment label
     *
     * @param [] $arguments
     * @return string
     */
    public function getLabel($arguments = []);

    /**
     * Render comment
     *
     * @param HistoryEntityInterface[] $arguments
     * @param string $label
     * @param bool $renderingUrl
     * @return string
     */
    public function renderComment(
        $arguments,
        $label = null,
        $renderingUrl = false
    );
}
