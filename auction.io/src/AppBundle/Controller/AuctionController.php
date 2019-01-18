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
use AppBundle\Form\BidType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
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
       $auctions = $entityManager->getRepository(auctions::class)->findBy(["status" => auctions::STATUS_ACTIVE]);

        return $this->render("Auction/index.html.twig" , ["auctions" =>$auctions]);
    }


    /**
     * @Route("auction/details/{id}" , name="auction_details")
     * @param auctions $auctions
     *
     * @return Response
     */
    public function detalisAction(auctions $auctions){

        if($auctions->getStatus() === $auctions::STATUS_FINISHED){
            return $this->render("Auction/finished.html.twig" , ["auctions" => $auctions]);
        }


        $buyForm = $this->createFormBuilder()
            ->setAction($this->generateUrl("offer_buy" , ["id" => $auctions->getId()]))
            ->add("submit" , SubmitType::class , ["label" => "Kup"])
            ->getForm();


        $bidForm = $this->createForm(
            BidType::class ,
            null ,
            ["action" => $this->generateUrl("offer_bid" , ["id" => $auctions->getId()])]
            );

        return $this->render(
            "Auction/details.html.twig" ,
            [
                "auctions"=>$auctions ,
                "buyForm" => $buyForm->createView(),
                "bidForm" => $bidForm->createView(),
            ]
        );
    }

    /**
     * @Route("/auction/add" , name="auction_add")
     * @return Response
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $auctions = new auctions();

        $form = $this->createForm(AuctionType::class, $auctions);

        if ($request->isMethod("post")) {
            $form->handleRequest($request);

            if($auctions->getStartingPrice() >= $auctions->getPrice()){
                $form->get("StartingPrice")->addError(new FormError('Cena wywoławcza nie może być wyższa od ceny kup teraz'));
            }

            if($form->isValid()){
                $auctions
                    ->setStatus(auctions::STATUS_ACTIVE)
                    ->setOwner($this->getUser());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($auctions);
                $entityManager->flush();

                $this->addFlash("success" , "Aukcja {$auctions->getTitle()} została dodana");

                return $this->redirectToRoute("auction_details" , ["id" => $auctions->getId()]);
            }

            $this->addFlash("warning" , "Nie udało się dodać Aukcji !");
        }

        return $this->render("Auction/add.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/auction/edit/{id}" , name="auction_edit")
     * @param Request $request
     * @param auctions $auctions
     * @return Response
     */
    public function editAction(Request $request , auctions $auctions){
        $this->denyAccessUnlessGranted("ROLE_USER");

        if($this->getUser() !== $auctions->getOwner()){
            throw new AccessDeniedException();
        }

        $form = $this->createForm(AuctionType::class,$auctions);

        if($request->isMethod("post")){
            $form->handleRequest($request);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($auctions);
            $entityManager->flush();

            $this->addFlash("success" , "Aukcja {{$auctions->getTitle()}} została wyedytowana");

            return $this->redirectToRoute("auction_details" , ["id" => $auctions->getId()]);
        }

        return $this->render("Auction/edit.html.twig" , ["form" => $form->createView()]);
    }

    /**
     * @Route("auction/remove/{id}" , name="auction_remove" , methods={"DELETE"})
     * @param auctions $auctions
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(auctions $auctions){
        if($this->getUser() !== $auctions->getOwner()){
            throw new AccessDeniedException();
        }

        $entityManaget = $this->getDoctrine()->getManager();
        $entityManaget->remove($auctions);
        $entityManaget->flush();

        $this->addFlash("success" , "Aukcja {$auctions->getTitle()} została usunięta");

        return $this->redirectToRoute("index_auction");
    }

    /**
     * @Route("/auction/finish/{id}" , name="auction_finish" , methods={"POST"})
     * @param auctions $auctions
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function finishAction(auctions $auctions){
        if($this->getUser() !== $auctions->getOwner()){
            throw new AccessDeniedException();
        }

        $form = $this->createForm(AuctionType::class);

        $auctions
            ->setExpiresAt(new \DateTime())
            ->setStatus(auctions::STATUS_FINISHED);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($auctions);
        $entityManager->flush();

        $this->addFlash("success" , "Aukcja {$auctions->getTitle()} została zakończona");

        return $this->redirectToRoute("auction_details" , ["id" => $auctions->getId()]);
    }


    /**
     * @Route("/auction/comment" , name="auction_comment")
     * @return Response
     */
    public function getCommentAction(){


        return $this->render('Auction/comment.html.twig');
    }

    /**
     * @Route("/auction/info" , name="info_auction")
     * @return Response
     */
    public function infoAction(){
        return $this->render("Auction/info.html.twig");
    }

}