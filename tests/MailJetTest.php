<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;

class MailJetTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    use MailTracking;
    /**
     * @test
     * @return void
     */
    public function testMailJet()
    {
        $this->visit('/sendmail')
            ->seeEmailWasSent();
    }
}

class TestMaiListener implements Swift_Events_EventListener
{
    protected $test;

    public function __construct($test)
    {
        $this->test = $test;
    }

    /**
     * @param \Swift_Events_SendEvent $event
     */
    public function beforeSendPerformed($event)
    {
        $this->test->addEmail($event->getMessage());
    }

}

trait MailTracking
{
    protected $emails = [];

    /**
     * @before
     */
    public function setUpMailTracking()
    {
        Mail::getSwiftMailer()
            ->registerPlugin(new TestMaiListener($this));
    }

    protected function seeEmailWasSent()
    {
        $this->assertNotEmpty(
            $this->emails, 'No emails have been sent.'
        );

        return $this;
    }

    public function addEmail(Swift_Message $email)
    {
        $this->emails[] = $email;
    }

    protected function lastEmail()
    {
        return end($this->emails);
    }

}
