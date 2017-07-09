<?php

namespace Acme\ReginaldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use Acme\ReginaldBundle\Entity\Output;

class DefaultController extends Controller {

    /**
     * @Route("/")
     */
    public function indexAction() {
        
      return $this->render('AcmeReginaldBundle:Default:index.html.twig'); 
    }
    
    /**
     * @Route("/default/add", name="course_create")
     */
    public function addAction(Request $request) {       
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
