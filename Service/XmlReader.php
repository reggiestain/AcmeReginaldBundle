<?php

namespace Acme\ReginaldBundle\Service;

use Symfony\Component\HttpKernel\Config\FileLocator;

class XmlReader {

    protected $xml;

    public function __construct(FileLocator $fileLocator) {
        $this->xml = $fileLocator->locate('@AcmeReginaldBundle/Resources/uploads/config.xml');
    }

    /**
     *  Get course elements from XML File.
     *
     * @return mixed[] $items Array elements from XML File.
     */
    public function read() {
        if (file_exists($this->xml)) {

            $xml = simplexml_load_file($this->xml);

            $title = $xml->children('blti', true)->title;
            $desc = $xml->children('blti', true)->description;
            $lauchUrl = $xml->children('blti', true)->launch_url;
            $iconUrl = $xml->children('blti', true)->extensions->children('lticm', true)->property[1];
            
            $Array = ['success'=>['title'=>$title,'desc'=>$desc, 'lauch_url'=>$lauchUrl,'icon_url'=>$iconUrl]];
            
        } else {
             
            $Array = ['error'=>['message'=>'Failed to open '.$this->xml]];
        }
        
        return $Array;
    }

}
