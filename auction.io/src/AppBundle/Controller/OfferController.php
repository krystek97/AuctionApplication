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

        $this->addFlash("success" , "Kupiłeś przedmiot {$auctions->getTitle()} za kwotę {$offer->getPrice()} zł");

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

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offer);
        $entityManager->flush();
        $lastOffer = $entityManager
            ->getRepository(Offer::class)
            ->findOneBy(["auction" => $auctions] , ["createdAt" => "DESC"]);

        if(isset($lastOffer)){
            if($offer->getPrice() <= $lastOffer->getPrice()){
                $this->addFlash("warning" , "Twoja oferta nie może być niższa niż {$lastOffer->getPrice()} zł");

                return $this->redirectToRoute("auction_details" , ["id" => $auctions->getId()]);
            }else{
                if($offer->getPrice() < $auctions->getStartingPrice()){
                    $this->addFlash("warning" , "Twoja oferta nie może być niższa od ceny wywoławczej");

                    return $this->redirectToRoute("auction_details" , ["id" => $auctions->getId()]);
                }
            }
        }

        if($bidForm->isValid()){



            $offer
                ->setType(Offer::TYPE_BID)
                ->setAuction($auctions);



            $this->addFlash("success" , "Złożyłeś ofertę za przedmiot {$auctions->getTitle()} za cenę {$auctions->getPrice()} zł");
        }else{
            $this->addFlash("warning" , "Nie udalo sie zalicytować przedmiotu {$auctions->getTitle()}");
        }



        return $this->redirectToRoute("auction_details" , ["id" => $auctions->getId()]);

    }

}