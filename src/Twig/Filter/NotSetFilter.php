<?php
declare(strict_types=1);
namespace App\Twig\Filter;

use App\Twig\Enum\OnOffEnum;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NotSetFilter extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('emptyStringNotSet', [$this, 'notSetString']),
        ];
    }

    /**
     * @param string | null $value
     *
     * @return string
     */
    public function notSetString($value): string
    {
        if (!empty($value)) {
            return $value;
        }

        return 'Not set';
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