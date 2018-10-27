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

        foreach ((new RtsRepo)->select(['name', 'url']) as $rt) {
            $inputs[] = new RtOutput($rt->name, $rt->url);
        }

        return $inputs;
    }
}
