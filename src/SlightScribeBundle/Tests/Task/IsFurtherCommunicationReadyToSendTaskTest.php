<?php

namespace SlightScribeBundle\Tests\Repository;



use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\RunHasCommunication;
use SlightScribeBundle\Task\IsFurtherCommunicationReadyToSendTask;
use SlightScribeBundle\Tests\BaseTest;



/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class IsFurtherCommunicationReadyToSendTaskTest extends BaseTest {



    function dataForTestSet() {
        return array(
            array(new \DateTime('2016-02-01 10:00:00'), 15, new \DateTime('2016-01-25 10:00:00'), false),
            array(new \DateTime('2016-02-01 10:00:00'), 15, new \DateTime('2016-01-01 10:00:00'), true),
        );
    }

    /**
     * @dataProvider dataForTestSet
     */

    public function testGo($now, $daysBefore, $lastOneSent, $result) {

        $task = new IsFurtherCommunicationReadyToSendTask($now);

        $nextCommunication = new Communication();
        $nextCommunication->setDaysBefore($daysBefore);

        $lastRunCommunication = new RunHasCommunication();
        $lastRunCommunication->setSentAt($lastOneSent);

        $this->assertEquals($result, $task->go($nextCommunication, $lastRunCommunication));
    }








}