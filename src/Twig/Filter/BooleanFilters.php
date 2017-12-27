<?php
declare(strict_types=1);
namespace App\Twig\Filter;

use App\Twig\Enum\OnOffEnum;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BooleanFilters extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('boolToInt', [$this, 'boolToInt']),
            new TwigFilter('inversedBoolToInt', [$this, 'inversedBoolToInt']),
            new TwigFilter('onOffBoolean', [$this, 'onOffBoolean']),
        ];
    }

    /**
     * @param bool $booleanValue
     *
     * @return int
     */
    public function boolToInt(bool $booleanValue): int
    {
        return (int) $booleanValue;
    }

    /**
     * @param bool $booleanValue
     *
     * @return int
     */
    public function inversedBoolToInt(bool $booleanValue): int
    {
        return (int) !$booleanValue;
    }

    /**
     * @param mixed $booleanValue
     *
     * @return string
     */
    public function onOffBoolean($booleanValue): string
    {
        return $booleanValue ? OnOffEnum::ON : OnOffEnum::OFF;
    }
}