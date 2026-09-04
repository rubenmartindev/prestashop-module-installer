<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ParentIdIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ParentIdTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TParentId int|string
 * @phpstan-type TParamParentId TParentId
 */
final class ParentId implements ValueObjectInterface
{
    const HIDDEN    = -1;
    const ROOT      = 0;

    /** @var TParentId */
    private $parentId;

    /**
     * @param TParamParentId $parentId
     */
    public function __construct($parentId)
    {
        $this->ensureIsIntegerOrString($parentId);

        $parentId = \is_numeric($parentId)
            ? (int) $parentId
            : \trim($parentId)
        ;

        $this->ensureIsValid($parentId);

        $this->parentId = $parentId;
    }

    /**
     * {@inheritDoc}
     *
     * @return TParentId
     */
    public function getValue()
    {
        return $this->parentId;
    }

    /**
     * @param TParamParentId $parentId
     *
     * @return void
     *
     * @throws ParentIdTypeIsInvalidException
     */
    private function ensureIsIntegerOrString($parentId)
    {
        if (true === \is_int($parentId)) {
            return;
        }

        if (true === \is_string($parentId)) {
            return;
        }

        throw new ParentIdTypeIsInvalidException('The ParentId is not a integer or string');
    }

    /**
     * @param TParamParentId $parentId
     *
     * @return void
     *
     * @throws ParentIdIsEmptyException
     */
    private function ensureIsValid($parentId)
    {
        if (true === \is_int($parentId)) {
            return;
        }

        if (true === empty($parentId)) {
            throw new ParentIdIsEmptyException('The ParentId is empty');
        }
    }
}
