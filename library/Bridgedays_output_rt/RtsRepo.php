<?php

namespace Icinga\Module\Bridgedays_output_rt;

use Icinga\Repository\IniRepository;

class RtsRepo extends IniRepository
{
    protected $queryColumns = ['rt' => [
        'name',
        'url',
        'queues',
        'cfHolidayStart',
        'cfHolidayEnd',
        'cfHolidayDays',
        'cfHolidayDateFormat',
        'cfs'
    ]];

    protected $triggers = ['rt'];

    protected $configs = ['rt' => [
        'name'      => 'modules/bridgedays_output_rt/rts',
        'keyColumn' => 'name',
    ]];
}
