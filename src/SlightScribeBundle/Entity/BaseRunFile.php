<?php

namespace SlightScribeBundle\Entity;



/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
abstract class BaseRunFile {


    /**
     * @return mixed
     */
    public abstract function getFile();


    /**
     * @return mixed
     */
    public abstract function getLetterContent();


    /**
     * @return mixed
     */
    public abstract function getLetterContentHeaderRight();


}

