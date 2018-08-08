<?php
declare(strict_types=1);

namespace ifrolikov\dto;

/**
 * Class JsonDtoPacker
 * @package ifrolikov\dto\Dto
 */
class JsonDtoPacker extends AbstractDtoPacker
{

    /**
     * @param array $data
     * @return string
     */
    protected function packInternal(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}