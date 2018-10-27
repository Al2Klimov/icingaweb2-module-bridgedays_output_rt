<?php

namespace Icinga\Module\Bridgedays_output_rt\Forms;

use Icinga\Data\Filter\Filter;
use Icinga\Forms\RepositoryForm;

class ConfigForm extends RepositoryForm
{
    protected function createCommonElements()
    {
        $this->addElement('text', 'url', [
            'label'       => $this->translate('URL'),
            'description' => $this->translate('RT instance URL'),
            'required'    => true,
        ]);
    }

    protected function createInsertElements(array $formData)
    {
        $this->addElement('text', 'name', [
            'label'       => $this->translate('Name'),
            'description' => $this->translate('RT instance name'),
            'required'    => true,
        ]);

        $this->createCommonElements();
        $this->setSubmitLabel($this->translate('Add'));
    }

    protected function createUpdateElements(array $formData)
    {
        $this->createCommonElements();
        $this->setSubmitLabel($this->translate('Save'));
    }

    protected function createDeleteElements(array $formData)
    {
        $this->setSubmitLabel($this->translate('Remove'));
    }

    protected function createFilter()
    {
        return Filter::where('name', $this->getIdentifier());
    }

    protected function getInsertMessage($success)
    {
        return $success
            ? $this->translate('Successfully added RT instance')
            : $this->translate('Failed to add RT instance');
    }

    protected function getUpdateMessage($success)
    {
        return $success
            ? $this->translate('Successfully changed RT instance')
            : $this->translate('Failed to change RT instance');
    }

    protected function getDeleteMessage($success)
    {
        return $success
            ? $this->translate('Successfully removed RT instance')
            : $this->translate('Failed to remove RT instance');
    }
}
