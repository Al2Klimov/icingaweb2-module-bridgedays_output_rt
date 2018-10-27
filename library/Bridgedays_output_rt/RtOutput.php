<?php

namespace Icinga\Module\Bridgedays_output_rt;

use Icinga\Module\Bridgedays\Intrface\Output;

class RtOutput implements Output
{
    protected $name;
    protected $url;

    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    public function getId()
    {
        return md5($this->name);
    }

    public function getName()
    {
        return sprintf(mt('bridgedays_output_rt', 'RT: %s'), $this->name);
    }

    public function getFields()
    {
        return [];
    }
}
