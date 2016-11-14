<?php

namespace robrogers\Larajet;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Mail\Transport\Transport;

use Swift_Attachment;
use Swift_Encoding;
use Swift_Events_EventListener;
use Swift_Mime_Message;
use Swift_Mime_SimpleMimeEntity;
use Swift_Transport;

class MailJetTransport extends Transport implements Swift_Transport
{
    /**
     * Guzzle client instance.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * The Mailjet public API key.
     *
     * @var string
     */
    protected $publicKey;

    /**
     * The Mailjet private API key.
     *
     * @var string
     */
    protected $privateKey;

    protected $apiUrl;

    /**
     * MailJetTransport constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->client = new Client($config['guzzle']); //get guzzle config
        $this->apiUrl = $config['api_url'];
        $this->publicKey = $config['public_key'];
        $this->privateKey = $config['private_key'];
    }


    /**
     * {@inheritdoc}
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $from = $this->getFrom($message);
        $recipients = $this->getRecipients($message);

        $message->setBcc([]);

        $options = [
            'auth' => [$this->publicKey, $this->privateKey],
            'headers' => [
                'Headers' => ['Reply-To' => $this->getReplyTo($message)],
            ],
            'json' => [
                'FromEmail' => $from['email'],
                'FromName' => $from['name'],
                'Subject' => $message->getSubject(),
                'Text-part' => $message->toString(),
                'Html-part' => $message->getBody(),
                'Recipients' => $recipients,
            ],
        ];

        /**
         * @var Swift_Mime_SimpleMimeEntity[] $attachments
        */
        if ($attachments = $message->getChildren()) {
            $options['json']['Attachments'] = array_map(
                function ($attachment) {
                    return [
                    'Content-type' => $attachment->getContentType(),
                    //'Filename' => $attachment->getFileName(),
                    'content' => Swift_Encoding::getBase64Encoding()->encodeString($attachment->getBody()),
                    ];
                }, $attachments
            );
        }
        return $this->client->post($this->apiUrl, $options);
    }

    /**
     * Get all the addresses this message should be sent to.
     *
     * @param \Swift_Mime_Message $message
     *
     * @return array
     */
    protected function getRecipients(Swift_Mime_Message $message)
    {
        $to = [];

        if ($message->getTo()) {
            $to = array_merge($to, $message->getTo());
        }

        if ($message->getCc()) {
            $to = array_merge($to, $message->getCc());
        }

        if ($message->getBcc()) {
            $to = array_merge($to, $message->getBcc());
        }

        $recipients = [];
        foreach ($to as $address => $name) {
            $recipients[] = ['Email' => $address, 'Name' => $name];
        }

        return $recipients;
    }

    /**
     * Get the "from" contacts in the format required by Mailjet.
     *
     * @param Swift_Mime_Message $message
     *
     * @return array
     */
    protected function getFrom(Swift_Mime_Message $message)
    {
        return array_map(
            function ($email, $name) {
                return compact('name', 'email');
            }, array_keys($message->getFrom()), $message->getFrom()
        )[0];
    }

    /**
     * Get the 'reply_to' headers and format as required by Mailjet.
     *
     * @param Swift_Mime_Message $message
     *
     * @return string
     */
    protected function getReplyTo(Swift_Mime_Message $message)
    {
        if (is_array($message->getReplyTo())) {
            return current($message->getReplyTo()) . ' <' . key($message->getReplyTo()) . '>';
        }
    }

    /**
     * Get the public API key being used by the transport.
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set the public API key being used by the transport.
     *
     * @param string $publicKey
     *
     * @return string
     */
    public function setPublicKey($publicKey)
    {
        return $this->publicKey = $publicKey;
    }

    /**
     * Get the private API key being used by the transport.
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Set the private API key being used by the transport.
     *
     * @param string $privateKey
     *
     * @return string
     */
    public function setPrivateKey($privateKey)
    {
        return $this->publicKey = $privateKey;
    }
}


