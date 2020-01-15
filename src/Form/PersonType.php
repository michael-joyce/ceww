<?php

namespace App\Form;

use App\Entity\Alias;
use App\Entity\Person;
use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class PersonType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('fullName', null, array(
            'label' => 'Full Name',
            'required' => true,
            'attr' => array(
                'help_block' => 'Person\'s full name',
            ),
        ));

        $builder->add('sortableName', null, array(
            'label' => 'Sortable Name',
            'required' => true,
            'attr' => array(
                'help_block' => 'Name listed last name, first name (lowercase). Sortable name will not be displayed to the public.',
            ),
        ));

        $builder->add('gender', ChoiceType::class, array(
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Female' => Person::FEMALE,
                'Male' => Person::MALE,
                'Unknown' => null,
            ),
        ));

        $builder->add('canadian', ChoiceType::class, array(
            'label' => 'Canadian',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
                'Unknown' => null,
            ),
            'required' => true,
            'placeholder' => false,
            'attr' => array(
                'help_block' => 'Is the person a Canadian?',
            ),
        ));

        $builder->add('aliases', Select2EntityType::class, array(
            'label' => 'Alternate Names',
            'multiple' => true,
            'remote_route' => 'alias_typeahead',
            'class' => Alias::class,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
                'help_block' => 'Alternate names or aliases including birth name or married names',
            ),
        ));

        $builder->add('description', TextareaType::class, array(
            'label' => 'Notes (for the public)',
            'required' => false,
            'attr' => array(
                'help_block' => 'This description is public',
                'class' => 'tinymce',
            ),
        ));

        $builder->add('birthDate', TextType::class, array(
            'label' => 'Birth Year',
            'required' => false,
            'attr' => array(
                'help_block' => 'Date ranges (1901-1903) and circas (c1902) are supported here'
            ),
        ));

        // birthPlace is a typeahead thing.
        $builder->add('birthPlace', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'place_typeahead',
            'class' => Place::class,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
                'help_block' => 'Geotagged location for birth place',
            ),
        ));

        $builder->add('deathDate', TextType::class, array(
            'label' => 'Death Year',
            'required' => false,
            'attr' => array(
                'help_block' => 'Date ranges (1901-1903) and circas (c1902) are supported here'
            )
        ));

        // deathPlace is a typeahead thing.
        $builder->add('deathPlace', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'place_typeahead',
            'class' => Place::class,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
                'help_block' => 'Geotagged location for death place',
            ),
        ));

        $builder->add('residences', Select2EntityType::class, array(
            'multiple' => true,
            'remote_route' => 'place_typeahead',
            'class' => Place::class,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
                'help_block' => 'List of known residences',
            ),
        ));

        $builder->add('urlLinks', CollectionType::class, array(
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'delete_empty' => true,
            'entry_type' => UrlType::class,
            'entry_options' => array(
                'label' => false,
            ),
            'label' => 'Links',
            'required' => false,
            'attr' => array(
                'class' => 'collection collection-simple',
                'help_block' => 'List of URLs associated with the person',
            ),
        ));

        $builder->add('notes', TextType::class, array(
            'label' => 'Research Notes (for editors/admins)',
            'required' => false,
            'attr' => array(
                'help_block' => 'Notes are only available to logged-in users',
                'class' => 'tinymce',
            ),
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Person'
        ));
    }

}
