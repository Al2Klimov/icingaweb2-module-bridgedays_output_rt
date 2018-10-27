<?php

/** @var \Icinga\Application\Modules\Module $this */

$this->provideConfigTab('rts', [
    'url'   => 'config',
    'label' => $this->translate('RTs'),
    'title' => $this->translate('RT instances'),
    'icon'  => 'dashboard'
]);
