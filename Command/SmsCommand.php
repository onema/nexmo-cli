<?php
/*
 * This file is part of the Onema nexmo-cli Package.
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace Onema\NexmoCli\Command;

use Guzzle\Http\Exception\ClientErrorResponseException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
/**
 * SmsCommand - Description.
 *
 * @author Juan Manuel Torres <kinojman@gmail.com>
 * @copyright (c) 2014, onema.io
 */

class SmsCommand extends BaseCommand
{

    protected function configure()
    {
        parent::configure();
        $this
            ->setName('nexmo:sms')
            ->setDescription('Send an SMS using the Nexmo API.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = $input->getArgument('text');
        $phone = $input->getArgument('phone');
        $client = $this->getClient();
        $config = $this->getConfiguration();
        $request = $client->post('sms/json', null, array(
            'api_key' => $config['api_key'],
            'api_secret' => $config['api_secret'],
            'from' => $config['account_from_number'],
            'to' => $phone,
            'text' => $text,
        ));

        $request->addHeader('Content-Type', 'application/x-www-form-urlencoded');

        try {
            $data = $request->send()->json();
            $output->writeln('<info>Just sent and SMS with the following message: '.$text.'</info>');
            $output->writeln('<info>API response: '.print_r($data, true).'</info>');
        } catch (ClientErrorResponseException $e) {
            $output->writeln('<error>ERROR: '.$e->getMessage().'</error>');
        }
    }
}