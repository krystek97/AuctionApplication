<?php
/**
 * Created by PhpStorm.
 * User: Maciek
 * Date: 2019-01-06
 * Time: 11:22
 */

namespace AppBundle\Controller;


use AppBundle\Entity\auctions;
use AppBundle\Form\AuctionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuctionController extends Controller
{
    /**
     * @Route("/" , name="index_auction")
     * @return Response
     */
    public function indexAction(){
       $entityManager = $this->getDoctrine()->getManager();
       $auctions = $entityManager->getRepository(Auctions::class)->findAll();

        return $this->render("Auction/index.html.twig" , ["auctions" =>$auctions]);
    }

    /**
     * @Route("/{id}" , name="auction_details")
     * @param auctions $auctions
     *
     * @return Response
     */
    public function detalisAction(auctions $auctions){


        return $this->render("Auction/details.html.twig" , ["auctions"=>$auctions]);
    }

    /**
     * @Route("/auction/add" , name="auction_add")
     * @return Response
     */
    public function addAction(Request $request){
        $auctions = new auctions();

        $form = $this->createForm(AuctionType::class , $auctions);

        if($request->isMethod("post")){
            $form->handleRequest($request);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($auctions);
            $entityManager->flush();

            return $this->redirectToRoute("index_auction");
        }

        return $this->render("Auction/add.html.twig" , ["form"=>$form->createView()]);
    }
}