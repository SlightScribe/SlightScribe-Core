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

                return true;
            }
        }


        // No new blocks! Woot!
        return false;
    }
}