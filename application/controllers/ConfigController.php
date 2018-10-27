<?php

namespace Icinga\Module\Bridgedays_output_rt\Controllers;

use Icinga\Exception\NotFoundError;
use Icinga\Module\Bridgedays_output_rt\Forms\ConfigForm;
use Icinga\Module\Bridgedays_output_rt\RtsRepo;
use Icinga\Web\Controller;
use Icinga\Web\Url;
use Icinga\Web\Widget\Tabs;

class ConfigController extends Controller
{
    public function indexAction()
    {
        $this->assertPermission('config/modules');

        $this->view->repo = (new RtsRepo)->select(['name', 'url'])->order('name');
        $this->view->tabs = $this->Module()->getConfigTabs()->activate('rts');
    }

    public function addAction()
    {
        $this->assertPermission('config/modules');

        $this->processForm($this->mkForm()->add());

        $this->mkTabs('add', 'plus', $this->translate('Add RT'), $this->translate('Add RT instance'));
    }

    public function editAction()
    {
        $this->assertPermission('config/modules');

        $this->processForm($this->mkForm()->edit($this->params->getRequired('name')));

        $this->mkTabs('edit', 'edit', $this->translate('Edit RT'), $this->translate('Edit RT instance'));
    }

    public function removeAction()
    {
        $this->assertPermission('config/modules');

        $this->processForm($this->mkForm()->remove($this->params->getRequired('name')));

        $this->mkTabs('remove', 'trash', $this->translate('Remove RT'), $this->translate('Remove RT instance'));
    }

    protected function mkForm()
    {
        return (new ConfigForm)
            ->setRepository(new RtsRepo)
            ->setRedirectUrl('bridgedays_output_rt/config');
    }

    protected function processForm(ConfigForm $form)
    {
        try {
            $form->handleRequest();
        } catch (NotFoundError $_) {
            $this->httpNotFound($this->translate('No such RT instance'));
        }

        $this->view->form = $form;
    }

    protected function mkTabs($id, $icon, $label, $title)
    {
        $this->view->tabs = (new Tabs)->add($id, [
            'label'  => $label,
            'title'  => $title,
            'icon'   => $icon,
            'url'    => Url::fromRequest(),
            'active' => true
        ]);
    }
}
