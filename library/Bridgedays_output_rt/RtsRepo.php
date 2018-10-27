<?php

namespace Icinga\Module\Bridgedays_output_rt;

use Icinga\Repository\IniRepository;

class RtsRepo extends IniRepository
{
    protected $queryColumns = ['rt' => ['name', 'url']];

    protected $triggers = ['rt'];

    protected $configs = ['rt' => [
        'name'      => 'modules/bridgedays_output_rt/rts',
        'keyColumn' => 'name',
    ]];
}
