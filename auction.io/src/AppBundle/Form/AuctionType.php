<?php
/**
 * Created by PhpStorm.
 * User: Maciek
 * Date: 2019-01-07
 * Time: 18:51
 */

namespace AppBundle\Form;


use AppBundle\Entity\auctions;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class AuctionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title" , TextType::class , ['label' => "Tytuł"])
            ->add("description" , TextareaType::class , ['label' =>"Opis"])
            ->add("price" , NumberType::class , ['label' => "Cena"])
            ->add("startingPrice" , NumberType::class , ["label" => "Cena Wywoławcza"])
            ->add("expiresAt" , \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class , ["label" => "Data zakończenia"])
            ->add("Submit" , SubmitType::class , ['label' => "Zatwierdź Formularz"]);
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(["data_class" => auctions::class]);
    }
}