<?php

namespace Icinga\Module\Bridgedays_output_rt;

use DateTime;
use Exception;
use Icinga\Module\Bridgedays\Forms\ImportForm;
use Icinga\Module\Bridgedays\Intrface\Output;
use Icinga\Web\Notification;
use stdClass;

class RtOutput implements Output
{
    protected $name;
    protected $url;
    protected $queues;
    protected $cfHolidayStart;
    protected $cfHolidayEnd;
    protected $cfHolidayDays;
    protected $cfHolidayDateFormat;
    protected $cfs;

    public function __construct(stdClass $rt)
    {
        $this->name = $rt->name;
        $this->url = $rt->url;
        $this->queues = (array)json_decode($rt->queues);
        $this->cfHolidayStart = $rt->cfHolidayStart;
        $this->cfHolidayEnd = $rt->cfHolidayEnd;
        $this->cfHolidayDays = $rt->cfHolidayDays;
        $this->cfHolidayDateFormat = $rt->cfHolidayDateFormat;
        $this->cfs = (array)json_decode($rt->cfs);

        $this->queues = array_combine($this->queues, $this->queues);

        foreach ($this->cfs as &$cf) {
            $cf = array_flip((array)$cf);
        }
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
        $queues = $this->queues;

        if (count($queues) !== 1) {
            $queues[''] = '';
        }

        asort($queues);

        $cfs = [];

        foreach ($this->cfs as $name => $options) {
            if (count($options) !== 1) {
                $options[''] = '';
            }

            asort($options);

            $cfs[] = [
                'select',
                'cfX' . md5($name),
                [
                    'label'        => $name,
                    'required'     => true,
                    'multiOptions' => $options
                ]
            ];
        }

        return array_merge(
            [
                [
                    'text',
                    'username',
                    [
                        'label'       => mt('bridgedays_output_rt', 'Username'),
                        'description' => mt('bridgedays_output_rt', 'RT login username'),
                        'required'    => true
                    ]
                ],
                [
                    'password',
                    'password',
                    [
                        'label'       => mt('bridgedays_output_rt', 'Password'),
                        'description' => mt('bridgedays_output_rt', 'RT login password (will disappear from memory as soon as possible)'),
                        'required'    => true
                    ]
                ],
                [
                    'select',
                    'queue',
                    [
                        'label'        => mt('bridgedays_output_rt', 'Queue'),
                        'description'  => mt('bridgedays_output_rt', 'Queue of the newly created RT tickets'),
                        'required'     => true,
                        'multiOptions' => $queues
                    ]
                ],
                [
                    'text',
                    'owner',
                    [
                        'label'       => mt('bridgedays_output_rt', 'Ticket owner'),
                        'description' => mt('bridgedays_output_rt', 'Owner of the newly created RT tickets'),
                        'required'    => true
                    ]
                ]
            ],
            $cfs
        );
    }

    public function export(ImportForm $form, array $bridgedays)
    {
        $url = sprintf(
            '%s/REST/1.0/ticket/new?user=%s&pass=%s',
            rtrim($this->url, '/'),
            rawurlencode($form->getValue('username')),
            rawurlencode($form->getValue('password'))
        );

        $curl = new Curl;

        foreach ($bridgedays as $from => $to) {
            $cfs = [];

            if ((string)$this->cfHolidayStart !== '') {
                $cfs[$this->cfHolidayStart] = DateTime::createFromFormat('Y-m-d', $from)
                    ->format($this->cfHolidayDateFormat);
            }

            if ((string)$this->cfHolidayEnd !== '') {
                $cfs[$this->cfHolidayEnd] = DateTime::createFromFormat('Y-m-d', $to)
                    ->format($this->cfHolidayDateFormat);
            }

            if ((string)$this->cfHolidayDays !== '') {
                $cfs[$this->cfHolidayDays] = DateTime::createFromFormat('Y-m-d', $to)->diff(DateTime::createFromFormat('Y-m-d', $from))->days + 1;
            }

            foreach ($this->cfs as $name => $_) {
                $cfs[$name] = $form->getValue('cfX' . md5($name));
            }

            $ticket = sprintf(
                <<<EOF
id: ticket/new
Queue: %s
Owner: %s
Status: new
Text: Created by RT output for Bridge Days (Icinga Web 2 module).

EOF
                ,
                $form->getValue('queue'),
                $form->getValue('owner')
            );

            foreach ($cfs as $k => $v) {
                $ticket .= "CF-$k: $v\n";
            }

            $res = $curl->request(
                'POST',
                $url,
                ['User-Agent: Mozilla/4.0', 'Content-Type: application/x-www-form-urlencoded'],
                'content=' . rawurlencode($ticket)
            );

            $matches = [];

            if (preg_match('~\ART/\S+ (\d+) (.+)$~m', $res, $matches) && $matches[1] !== '200') {
                throw new Exception(sprintf(mt('bridgedays_output_rt', 'RT: %s'), $matches[2]));
            }
        }

        Notification::success(sprintf(mt('bridgedays_output_rt', 'Created %d holiday requests'), count($bridgedays)));
    }
}
