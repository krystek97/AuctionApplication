<?php
/**
 * Created by PhpStorm.
 * User: Maciek
 * Date: 2019-01-13
 * Time: 21:02
 */

namespace AppBundle\Controller;


use AppBundle\Entity\auctions;
use AppBundle\Entity\Offer;
use AppBundle\Form\BidType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends Controller
{

    /**
     * @Route("/auction/buy/{id}" , name="offer_buy" , methods={"POST"})
     * @param auctions $auctions
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function buyAction(auctions $auctions){
        $offer = new Offer();

        $offer
            ->setAuction($auctions)
            ->settype(Offer::TYPE_BUY)
            ->setPrice($auctions->getPrice());

        $auctions
            ->setStatus(auctions::STATUS_FINISHED)
            ->setExpiresAt(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($auctions);
        $entityManager->persist($offer);
        $entityManager->flush();

        return $this->redirectToRoute("auction_details" , ["id" => $auctions->getId()]);
    }

    /**
     * @Route("/auction/bid/{id}" , name="offer_bid" , methods={"POST"})
     * @param Request $request
     * @param auctions $auctions
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function bidAction(Request $request , auctions $auctions){
        $offer = new Offer();
        $bidForm = $this->createForm(BidType::class , $offer);

        $bidForm->handleRequest($request);

        $offer
            ->setType(Offer::TYPE_BID)
            ->setAuction($auctions);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offer);
        $entityManager->flush();

        return $this->redirectToRoute("auction_details" , ["id" => $auctions->getId()]);

    }

}