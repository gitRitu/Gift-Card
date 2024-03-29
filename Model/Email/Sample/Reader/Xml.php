<?php

namespace Dotsquare\Giftcard\Model\Email\Sample\Reader;

use Magento\Framework\Config\Reader\Filesystem;
use Magento\Framework\Config\FileResolverInterface;
use Dotsquare\Giftcard\Model\Email\Sample\Converter\Xml as ConverterXml;
use Dotsquare\Giftcard\Model\Email\Sample\SchemaLocator;
use Magento\Framework\Config\ValidationStateInterface;
use Magento\Framework\Config\Dom as ConfigDom;

/**
 * Class Xml
 *
 * @package Dotsquare\Giftcard\Model\Email\Sample\Reader
 */
class Xml extends Filesystem
{
    /**
     * @param FileResolverInterface $fileResolver
     * @param ConverterXml $converter
     * @param SchemaLocator $schemaLocator
     * @param ValidationStateInterface $validationState
     * @param string $fileName
     * @param array $idAttributes
     * @param string $domDocumentClass
     * @param string $defaultScope
     */
    public function __construct(
        FileResolverInterface $fileResolver,
        ConverterXml $converter,
        SchemaLocator $schemaLocator,
        ValidationStateInterface $validationState,
        $fileName = 'sample_email_templates.xml',
        $idAttributes = [],
        $domDocumentClass = ConfigDom::class,
        $defaultScope = 'global'
    ) {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            $fileName,
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
    }
}
