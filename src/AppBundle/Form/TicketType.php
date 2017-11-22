<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TicketType
 *
 * @author mmarques
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver; 
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('Nticket', TextType::class, array('label' => 'Numero Ticket','required' => true))
            ->add('DatePesage', TextType::class, array('label' => 'Date Pesage','required' => true))
            ->add('Hentree', TextType::class, array('label' => 'Heure entree','required' => false))
            ->add('Hsortie', TextType::class, array('label' => 'Heure sortie','required' => false))
             ->add('Tournee', HiddenType::class)    
            ->add('Nvehicule',  ChoiceType::class, array( 'choices' => $options['vehicules'],

                                                        'label' =>  'Numero vehicule','required'=>false))        
            ->add('Repreneurs',  ChoiceType::class, array( 'choices' => $options['repreneurs'],

                                                            'label' =>  'Repreneurs','required'=>false))   
            ->add('Tournee',  HiddenType::class)
            ->add('hidden',  ChoiceType::class, array( 'choices' => $options['tournee'],

                                                            'label' =>  'Tournee','required'=>false))   
            ->add('Chauffeur',  ChoiceType::class, array( 'choices' => $options['chauffeur'],

                                                            'label' =>  'Chauffeurs','required'=>true))   
            ->add('CodeDechets',  ChoiceType::class, array( 'choices' => $options['code'],

                                                            'label' =>  'CodeDechets','required'=>false))
            ->add('CategorieDechets',  ChoiceType::class, array( 'choices' => $options['categorie'],

                                                            'label' =>  'CategorieDechets','required'=>false))                                                                                       
            ->add('Poids',NumberType::class, array('label' => 'Poids','required' => true))
            ->add('Tarif', MoneyType::class, array('label' => 'Tarif','required' => false))
            ->add('Facture', MoneyType::class, array('label' => 'Facture','required' => false))
            ->add('Bonification', MoneyType::class, array('label' => 'Bonification','required' => false))
            ->add('Remarque', TextareaType::class, array('label' => 'Remarque','required' => false))
            ->add('save', SubmitType::class, array('label' => 'Create Ticket'));
    }
    
    public function configureOptions(OptionsResolver $resolver)

    {

        $resolver->setDefaults(array(

            'repreneurs' => Repreneurs::class,
            'tournee' => Tournee::class,
            'chauffeur' => Chauffeurs::class,
            'vehicules' => Vehicules::class,
            'code' => Dechets::class,
            'categorie' => Dechets::class,
        ));

    }
}     