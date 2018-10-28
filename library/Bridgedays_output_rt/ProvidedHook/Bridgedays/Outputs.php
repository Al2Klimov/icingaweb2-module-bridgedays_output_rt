<?php

namespace Icinga\Module\Bridgedays_output_rt\ProvidedHook\Bridgedays;

use Icinga\Module\Bridgedays\Hook\OutputsHook;
use Icinga\Module\Bridgedays_output_rt\RtOutput;
use Icinga\Module\Bridgedays_output_rt\RtsRepo;

class Outputs extends OutputsHook
{
    public function getOutputs()
    {
        $inputs = [];

        foreach ((new RtsRepo)->select([
            'name',
            'url',
            'queues',
            'cfHolidayStart',
            'cfHolidayEnd',
            'cfHolidayDays',
            'cfHolidayDateFormat',
            'cfs'
        ]) as $rt) {
            $inputs[] = new RtOutput($rt);
        }

        return $inputs;
    }
}
