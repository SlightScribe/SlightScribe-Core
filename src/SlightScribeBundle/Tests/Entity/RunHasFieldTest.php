<?php

namespace SlightScribeBundle\Tests\Repository;


use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\Field;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectVersion;
use SlightScribeBundle\Entity\Run;
use SlightScribeBundle\Entity\RunHasCommunication;
use SlightScribeBundle\Entity\RunHasField;
use SlightScribeBundle\Entity\User;
use SlightScribeBundle\Tests\BaseTestWithDataBase;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class RunHasFieldTest extends BaseTestWithDataBase
{


    function testHasValue1() {
        $runHasField = new RunHasField();
        $this->assertFalse($runHasField->hasValue());
    }

    function testHasValue2() {
        $runHasField = new RunHasField();
        $runHasField->setValue('CAT');
        $this->assertTrue($runHasField->hasValue());
    }

    function testHasValueDate1() {
        $field = new Field();
        $field->setType(Field::TYPE_DATE);
        $runHasField = new RunHasField();
        $runHasField->setField($field);
        $this->assertFalse($runHasField->hasValue());
    }

    function testHasValueDate2() {
        $field = new Field();
        $field->setType(Field::TYPE_DATE);
        $runHasField = new RunHasField();
        $runHasField->setField($field);
        $runHasField->setValue('2016-01-01 10:00:00');
        $this->assertTrue($runHasField->hasValue());
    }

    function testHasValueDate3() {
        $field = new Field();
        $field->setType(Field::TYPE_DATE);
        $runHasField = new RunHasField();
        $runHasField->setField($field);
        $runHasField->setValue('I LIKE CATS');
        $this->assertFalse($runHasField->hasValue());
    }

}

