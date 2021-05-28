<?php

namespace App\Form;

use App\Entity\Song;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('album')
            ->add('release_date')
            ->add('length')
            //->add('cover_image')
            ->add('cover_image' , FileType::class, array('data_class' => null,
            'label' => 'Choose an image :',
            'required' => false))



           
           // ->add('genre')
           // ->add('participants')
           // ->add('artiste')


           ->add('artiste', EntityType::class, array (
            'class' => 'App\Entity\Artiste',
           'label' => 'Who is the Artiste of the song',
           'choice_label' => 'name'))

            ->add('genre', EntityType::class, array (
                'class' => 'App\Entity\Genre',
               'label' => 'Choose the Style of the song',
               'choice_label' => 'name'))
            
 
           ->add('participants', EntityType::class, array (
            'class' => 'App\Entity\Participant',
            'label' => 'Choose the participant of this song ',
             'expanded' => true,
            'multiple' => true,
            'choice_label' => 'last_name'.'first_name' ))





        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
        ]);
    }
}
