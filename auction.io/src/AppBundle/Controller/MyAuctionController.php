<?php
/**
 * Created by PhpStorm.
 * User: Maciek
 * Date: 2019-01-18
 * Time: 10:09
 */

namespace AppBundle\Controller;


use AppBundle\Entity\auctions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MyAuctionController extends Controller
{
    /**
     * @Route("/my" , name="my_auction_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(){
        $this->denyAccessUnlessGranted("ROLE_USER");

        $entityManager = $this->getDoctrine()->getManager();

        $auctions = $entityManager
            ->getRepository(auctions::class)
            ->findBy(["owner" => $this->getUser()]);

        return $this->render("MyAuction/index.html.twig" , ["auctions" => $auctions]);
    }

    /**
     * @Route("/my/auction/details/{id}" , name="my_auction_details")
     * @param auctions $auctions
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detalisAction(auctions $auctions){

        if($auctions->getStatus() === $auctions::STATUS_FINISHED){
            return $this->render("Auction/finished.html.twig" , ["auctions" => $auctions]);
        }

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl("auction_remove" , ["id" => $auctions->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->add("submit" , SubmitType::class , ["label" => "Usuń Aukcję"])
            ->getForm();

        $finishForm = $this->createFormBuilder()
            ->setAction($this->generateUrl("auction_finish" , ["id" => $auctions->getId()]))
            ->add("submit" , SubmitType::class , ["label" => "Zakończ Aukcję"])
            ->getForm();



        return $this->render(
            "MyAuction/details.html.twig" ,
            [
                "auctions"=>$auctions ,
                "deleteForm" => $deleteForm->createView() ,
                "finishForm" => $finishForm->createView() ,
            ]
        );
    }
}