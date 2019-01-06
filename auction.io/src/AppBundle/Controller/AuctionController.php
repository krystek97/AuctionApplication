<?php
/**
 * Created by PhpStorm.
 * User: Maciek
 * Date: 2019-01-06
 * Time: 11:22
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuctionController extends Controller
{
    /**
     * @Route("/" , name="index_auction")
     * @return Response
     */
    public function indexAction(){
        $auctions = [
            [
                "id" => 1,
                "title" => "Super Samochod",
                "description" => "Opis Super Samochodu",
                "price" => "1000 zl",
            ],
            [
                "id" => 2,
                "title" => "Pralka",
                "description" => "Opis pralki",
                "price" => "300 zl",
            ] ,
            [
                "id" => 3,
                "title" => "Rower",
                "description" => "Opis roweru",
                "price" => "500 zl",
            ],
        ];

        return $this->render("Auction/index.html.twig" , ["auctions" =>$auctions]);
    }

    /**
     * @Route("/{id}" , name="auction_details")
     * @param $id
     */
    public function detalisAction($id){
        return $this->render("Auction/details.html.twig");
    }
}