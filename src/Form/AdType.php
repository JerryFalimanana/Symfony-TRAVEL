<?php

namespace App\Form;

use App\Entity\Add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{
    /**
     * Permer d'avoir les configurations de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
            ]
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre', 'Tapez le titre de votre annonce'))
            ->add('slug', TextType::class, $this->getConfiguration('Adresse web', 'Tapez l\'adresse web (automatique)', [
                'required' => false
            ]))
            ->add('coverImage', UrlType::class, $this->getConfiguration('Url de l\'image principale', 'Donnez l\'adresse de l\'image principale de votre offre'))
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction', 'Donnez une description globale de votre annonce'))
            ->add('content', TextareaType::class, $this->getConfiguration('Description', 'Donnez une description détaillée de votre offre'))
            ->add('rooms', IntegerType::class, $this->getConfiguration('Nombre de chambres', 'Le nombre de chambres disponibles'))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix par nuit', 'Indiquez le prix que vous voulez pour une nuit'))
            ->add('images', CollectionType::class,
                   [
                       'entry_type' => ImageType::class,
                       'allow_add' => true
                   ]   
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Add::class,
        ]);
    }
}
