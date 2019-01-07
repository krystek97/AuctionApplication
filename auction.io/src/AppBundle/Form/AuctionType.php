<?php
/**
 * Created by PhpStorm.
 * User: Maciek
 * Date: 2019-01-07
 * Time: 18:51
 */

namespace AppBundle\Form;


use AppBundle\Entity\auctions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuctionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title" , TextType::class , ['label' => "tytuł"])
            ->add("description" , TextareaType::class , ['label' =>"Opis"])
            ->add("price" , NumberType::class , ['label' => "cena"])
            ->add("Submit" , SubmitType::class , ['label' => "Zatwierdź Formularz"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(["data_class" => auctions::class]);
    }
}