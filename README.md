# Symfony 3 AcmeReginaldBundle

The Bundle will allow you to create entries to a Database table using Elements from the provided Config.xml File.

**Use Versions Symfony 3.0**

## Documentation

## Setup

You can add the Bundle by cloning this repository

``` php
# app/AppKernel.php
public function registerBundles()
{
    $bundles = [
        // [..]
        new Acme\ReginaldBundle\AcmeReginaldBundle(),
    ];
}
```
Without any Configuration all Features will be enabled! But at this point you
can start developing.

## Usage

### Service-Container

The simplest way to use the Bundle is to get the Container and request the
state of the xml read Feature which returns an array of elements from the Config.xml File
to be save to Database table "output". It returns an error if the file could not be open.

``` php
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Acme\ReginaldBundle\Entity\Output;

class DefaultController extends Controller {

     /**
     * @Route("/default/add", name="course_create")
     */
     
    public function addAction() {       
         //xml.reader service//
        $xmlReader = $this->get('xml.reader');
        //Returned dataArray from service method
        $dataArray = $xmlReader->read();
        
        if(!empty($dataArray['success']))
            
           $Output = new Output();
        
           $Output->setTitle($dataArray['success']['title']);
           $Output->setDescription($dataArray['success']['desc']);
           $Output->setLaunchUrl($dataArray['success']['lauch_url']);
           $Output->setIconUrl($dataArray['success']['icon_url']);
           $Output->setCreateDate(new \DateTime('now'));
           $em = $this->getDoctrine()->getManager();
           $em->persist($Output);
           $em->flush();
           
           $this->addFlash('notice', 'Course Info has been saved successfully.');
           
        if(!empty($dataArray['error']))  
            
            $this->addFlash('notice', $dataArray['error']['message']);
              
        return $this->render('AcmeReginaldBundle:Default:index.html.twig');
        
        }
    }
```

### Service Parameter 

The Config.xml file location has been passed by $fileLocator to the service 
contruct method to the read Feature which reads the relative data from the file.

``` php
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


```

