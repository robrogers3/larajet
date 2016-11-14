<?php

namespace robrogers\Larajet;

use Illuminate\Mail\TransportManager as BaseTransportManager;

class TransportManager extends BaseTransportManager
{
    /**
     * Create an instance of the Mailjet Swift Transport driver.
     *
     * @return MailJetTransport
     */
    protected function createMailjetDriver()
    {
        $config = $this->app['config']->get('services.mailjet', array());

        return new MailJetTransport($config, 'guzzle', [], $config['public_key'], $config['private_key']);
    }

}
