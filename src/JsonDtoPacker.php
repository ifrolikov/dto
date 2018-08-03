<?php
declare(strict_types=1);

namespace IFrol\RESTTools;

/**
 * Class JsonDtoPacker
 * @package IFrol\RESTTools\Dto
 */
class JsonDtoPacker extends AbstractDtoPacker
{

    /**
     * @param array $data
     * @return string
     */
    function packInternal(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}