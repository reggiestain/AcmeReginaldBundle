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
state of the xml read Feature. 

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

The Config.xml file location has been passed to the service 
contruct method to be reed.

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

### Argument-Usage

On every check you can give arguments to the check if you want to specify
the check. The Arguments for a Flag can be definied by an array on the validation
method. The Keys must be named like the condition itself. Please Note that if the
Condition does not support the Arguments they would be ignored.

``` php
# src/AcmeBundle/Resources/views/Index/index.html.twig
{% if has_feature('FooBarFeature', {'device': 'tablet'}) %}
    <p>Lorem Ipsum Dolor ...</p>
{% endif %}
```

### Creating a Condition

At first the Condition must be created. The Condition must implement the
ConditionInterface. There is a general context available.

``` php
<?php
# src/AcmeBundle/FeatureFlags/Condition/Foo.php
namespace AcmeBundle\FeatureFlags\Condition;

use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;

class Foo extends AbstractCondition implements ConditionInterface
{
    public function validate($config, $argument = null)
    {
        // [..] Implement your Methods to Validate the Feature

        return true;
    }

    public function __toString()
    {
        return 'Foo';
    }
}
```

After the Class was created it must be defined as a Tagged-Service. With this
Tag and the Alias the Condition would be loaded. At this point there is many
space to extend the Condition by adding calls or arguments.

``` yaml
# src/AcmeBundle/Resources/config/services.yml
services:
    acme.feature_flags.condition.fo:
        class: DZunke\FeatureFlagsBundle\Toggle\Conditions\Foo
        calls:
            - [setContext, [@dz.feature_flags.context]]
        tags:
            -  { name: dz.feature_flags.toggle.condition, alias: foo }
```

## Configuration

### Example

``` yaml
d_zunke_feature_flags:
    flags:
        FooFeature: # feature will always be disabled
            default: false
        BarFeature: # feature will only be enabled for a list of special ClientIps
            conditions_config:
                ip_address: [192.168.0.1]
        BazFeature: # the feature will be enabled for the half of the users
            conditions_config:
                percentage:
                    percentage: 50
                    cookie: ExampleCookieForFeature
                    lifetime: 3600
        FooBarFeature:
            conditions_config:
                device:
                    tablet: "/ipad|playbook|android|kindle|opera mobi|arm|(^.*android(?:(?!mobile).)*$)/i"
                    mobile: "/iphone|ipod|bb10|meego|blackberry|windows\\sce|palm|windows phone|((android.*mobile))|mobile/i"
```

## Available Conditions

``` yaml
hostname: [example.local, www.example.local]
```

``` yaml
ip_address: [192.168.0.1, 192.168.0.2]
```

``` yaml
percentage:
  cookie: NameThisCookieForTheUser # Default: 84a0b3f187a1d3bfefbb51d4b93074b1e5d9102a
  percentage: 29 # Default: 100
  lifetime: 3600 # Default: 86400 - 1 day
```

``` yaml
device:
  name: regex # give regex for each valid device
```

``` yaml
# See php.net/datetime
date:
  start_date: "2016-09-01" # Start date, accepts DateTime constructor values. Defaults to "now".
  end_date: "2016-09-03" # End date, accepts DateTime constructor values. Defaults to "now".
```

### Reference

``` yaml
d_zunke_feature_flags:
    # the default state to return for non-existent features
    default:              true
    # feature flags for the built system
    flags:
        # Prototype
        feature:
            # general active state for the flag - if conditions used it would be irrelevant
            default:              false
            # list of configured conditions which must be true to set this flag active
            conditions_config:    []
```

## License

FeatureFlagsBundle is licensed under the MIT license.
