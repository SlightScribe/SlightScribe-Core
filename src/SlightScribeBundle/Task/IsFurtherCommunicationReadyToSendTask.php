<?php

namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\RunHasCommunication;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class IsFurtherCommunicationReadyToSendTask {

    /** @var  \DateTime */
    protected $now;

    /**
     * IsFurtherCommunicationReadyToSendTask constructor.
     * @param \DateTime $now
     */
    public function __construct(\DateTime $now)
    {
        $this->now = $now;
    }


    public function go(Communication $nextCommunication, RunHasCommunication $lastRunCommunication) {

        // This should never really be needed - if the lastRunCommunication wasn't actually sent, something in the caller should catch it.
        // Have this as a final catch.
        if (is_null($lastRunCommunication->getSentAt())) {
            return false;
        }

        $secondsDifference = abs($this->now->getTimestamp() - $lastRunCommunication->getSentAt()->getTimestamp());

        return $secondsDifference / (60*60*24) > $nextCommunication->getDaysBefore();

    }

}
