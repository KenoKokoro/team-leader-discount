<?php


namespace Discount\V1\Modules\Calculator\Data;


use App\Generics\SingleDataObject;

class DiscountData extends SingleDataObject
{
    public function __construct(array $attributes)
    {
        $attributes = $this->mutateValues($attributes);
        parent::__construct($attributes);
    }

    private function mutateValues(array $attributes): array
    {
        $result = [];
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->mutateValues($value);
                continue;
            }

            if (is_float($value)) {
                $result[$key] = (string)number_format(round($value, 2, PHP_ROUND_HALF_DOWN), 2);
                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}