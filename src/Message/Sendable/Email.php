<?php
namespace Skn036\Gmail\Message\Sendable;

use Skn036\Gmail\Gmail;
use Skn036\Gmail\Facades\Gmail as GmailFacade;
use Skn036\Gmail\Helper\GmailHelpers;
use Skn036\Gmail\Message\GmailMessage;
use Skn036\Gmail\Message\GmailMessageResponse;

class Email extends Sendable
{
    use GmailHelpers;

    /**
     * Summary of __construct
     *
     * @param Gmail|GmailFacade $client
     * @param GmailMessage|null $replyToMessage
     *
     */
    public function __construct(Gmail|GmailFacade $client, GmailMessage|null $replyToMessage = null)
    {
        parent::__construct($client, $replyToMessage);
    }

    /**
     * Sends the email
     *
     * @param array $optParams
     * @return GmailMessage
     */
    public function send(array $optParams = [])
    {
        $postBody = $this->setGmailMessageBody();
        $service = $this->client->initiateService();

        $message = $this->executeRequest(
            $service->users_messages->send('me', $postBody, $optParams),
            $this->client,
            'Google_Service_Gmail_Message'
        );
        return (new GmailMessageResponse($this->client))->get($message->getId());
    }

    /**
     * Sets proper headers, subject and thread id to the replyable/forwardable message
     * @return static
     */
    protected function createReplyOrForward()
    {
        return $this->addMessageToSameThread();
    }

    /**
     * Sets proper headers, subject and thread id to the replyable message
     * @return static
     */
    public function createReply()
    {
        return $this->createReplyOrForward();
    }

    /**
     * Sets proper headers, subject and thread id to the forwardable message
     * @return static
     */
    public function createForward()
    {
        return $this->createReplyOrForward();
    }
}
