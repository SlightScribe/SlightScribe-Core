<?php

namespace SlightScribeBundle\Task;


use SlightScribeBundle\Entity\BaseRunFile;
use SlightScribeBundle\Entity\CommunicationAttachment;
use SlightScribeBundle\Entity\RunCommunicationAttachment;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class MakeFileTask
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

    public function getFileName(BaseRunFile $runCommunicationFile) {

        $filename = tempnam(sys_get_temp_dir(), 'simpleletter');

        if ($runCommunicationFile->getFile()->isTypeLetterText()) {

            file_put_contents($filename, $runCommunicationFile->getLetterContent());

        } else if ($runCommunicationFile->getFile()->isTypeLetterPdf()) {

            $pdf = new \FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial','',14);
            $pdf->MultiCell(0,10,$runCommunicationFile->getLetterContent());
            $pdf->Output('F', $filename);

        } else {

            throw new \Exception('Type Not Known');
        }

        return $filename;

    }



}