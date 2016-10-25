<?php

namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\BlockEmail;
use SlightScribeBundle\Entity\BlockIP;
use SlightScribeBundle\Entity\RunCommunicationAttachment;



/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class ProcessDataForBlocksTask
{

    protected $container;

    /**
     * CreateLetterChainInstanceTask constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param $ip
     * @return bool True if a block is applied and everything should be halted. False if all fine.
     */
    public function process($ip, $email) {


        $doctrine = $this->container->get('doctrine')->getManager();

        // IP?
        $anti_spam_all_projects_block_ip_after_attempts =
            $this->container->hasParameter('anti_spam_all_projects_block_ip_after_attempts') ? intval($this->container->getParameter('anti_spam_all_projects_block_ip_after_attempts')) : 0;
        $anti_spam_all_projects_block_ip_after_attempts_in_seconds =
            $this->container->hasParameter('anti_spam_all_projects_block_ip_after_attempts_in_seconds') ? intval($this->container->getParameter('anti_spam_all_projects_block_ip_after_attempts_in_seconds')) : 0;
        if ($anti_spam_all_projects_block_ip_after_attempts > 0 && $anti_spam_all_projects_block_ip_after_attempts_in_seconds > 0) {
            $blocks = $doctrine->getRepository('SlightScribeBundle:BlockIP')->countRunsFromIPInLastSeconds($ip, $anti_spam_all_projects_block_ip_after_attempts_in_seconds);
            if ($blocks > $anti_spam_all_projects_block_ip_after_attempts) {

                $blockIP = new BlockIP();
                $blockIP->setIp($ip);
                $doctrine->persist($blockIP);
                $doctrine->flush($blockIP);

                if ($this->container->hasParameter('on_block_notify_email')) {

                    $fromEmail = $this->container->hasParameter('from_email') ? $this->container->getParameter('from_email') : 'hello@example.com';
                    $fromEmailName = $this->container->hasParameter('from_email_name') ? $this->container->getParameter('from_email_name') : 'Hello';
                    $installName = $this->container->hasParameter('router.request_context.host') ? $this->container->getParameter('router.request_context.host') : 'SlightScribe';

                    $message = \Swift_Message::newInstance()
                        ->setSubject('IP Address Block put in place on ' . $installName)
                        ->setFrom(array($fromEmail => $fromEmailName))
                        ->setTo($this->container->getParameter('on_block_notify_email'))
                        ->setBody('IP Address block put in place.');

                    $this->container->get('mailer')->send($message);

                }

                return true;
            }
        }


        // Email
        $emailCleaned = $email;

        // Email already blocked?
        // test blocks
        if ($doctrine->getRepository('SlightScribeBundle:BlockEmail')->isEmailBlocked($emailCleaned)) {
            return true;
        }


        // Email blocking now?
        $anti_spam_all_projects_block_email_after_attempts =
            $this->container->hasParameter('anti_spam_all_projects_block_email_after_attempts') ? intval($this->container->getParameter('anti_spam_all_projects_block_email_after_attempts')) : 0;
        $anti_spam_all_projects_block_email_after_attempts_in_seconds =
            $this->container->hasParameter('anti_spam_all_projects_block_email_after_attempts_in_seconds') ? intval($this->container->getParameter('anti_spam_all_projects_block_email_after_attempts_in_seconds')) : 0;
        if ($anti_spam_all_projects_block_email_after_attempts > 0 && $anti_spam_all_projects_block_email_after_attempts_in_seconds > 0) {
            $blocks = $doctrine->getRepository('SlightScribeBundle:BlockEmail')->countRunsFromEmailInLastSeconds($emailCleaned, $anti_spam_all_projects_block_email_after_attempts_in_seconds);
            if ($blocks > $anti_spam_all_projects_block_email_after_attempts) {

                $blockEmail = new BlockEmail();
                $blockEmail->setEmail($email);
                $blockEmail->setEmailClean($emailCleaned);
                $doctrine->persist($blockEmail);
                $doctrine->flush($blockEmail);


                if ($this->container->hasParameter('on_block_notify_email')) {

                    $fromEmail = $this->container->hasParameter('from_email') ? $this->container->getParameter('from_email') : 'hello@example.com';
                    $fromEmailName = $this->container->hasParameter('from_email_name') ? $this->container->getParameter('from_email_name') : 'Hello';
                    $installName = $this->container->hasParameter('router.request_context.host') ? $this->container->getParameter('router.request_context.host') : 'SlightScribe';

                    $message = \Swift_Message::newInstance()
                        ->setSubject('Email Address Block put in place on ' . $installName)
                        ->setFrom(array($fromEmail => $fromEmailName))
                        ->setTo($this->container->getParameter('on_block_notify_email'))
                        ->setBody('Email Address block put in place.');

                    $this->container->get('mailer')->send($message);

                }

                return true;
            }
        }


        // No new blocks! Woot!
        return false;
    }
}