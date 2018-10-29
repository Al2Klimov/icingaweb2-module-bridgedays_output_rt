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

        $this->addElement('text', 'queues', [
            'label'       => $this->translate('RT queues'),
            'description' => $this->translate('RT queues for holidays as JSON, e.g. ["Holiday Requests"]'),
            'required'    => true,
            'value'       => '[]'
        ]);

        $this->addElement('text', 'cfHolidayStart', [
            'label'       => $this->translate('Field for first day'),
            'description' => $this->translate('Custom field for a holiday period\'s first day')
        ]);

        $this->addElement('text', 'cfHolidayEnd', [
            'label'       => $this->translate('Field for last day'),
            'description' => $this->translate('Custom field for a holiday period\'s last day')
        ]);

        $this->addElement('text', 'cfHolidayDays', [
            'label'       => $this->translate('Field for days amount'),
            'description' => $this->translate('Custom field for a holiday period\'s amount of days')
        ]);

        $this->addElement('text', 'cfHolidayDateFormat', [
            'label'       => $this->translate('Custom fields\' date format'),
            'description' => $this->translate('The format of the dates being put into the custom fields describing a holiday period in PHP notation, e.g. "Y-m-d"'),
            'required'    => true,
            'value'       => 'Y-m-d',
        ]);

        $this->addElement('text', 'cfs', [
            'label'       => $this->translate('Other fields'),
            'description' => $this->translate('Other RT custom fields as JSON, e.g. {"Company": {"Facebook": 1, "Amazon": 2, "Netflix": 3, "Google": 4}}'),
            'required'    => true,
            'value'       => '{}'
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
