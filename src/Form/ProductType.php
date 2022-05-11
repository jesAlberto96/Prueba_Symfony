<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Category;

class ProductType extends AbstractType
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        // parent::__construct($doctrine, Product::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $categorias = $doctrine->getRepository(Category::class)->findAll();
        $categorias = $this->getCategorias();


        $builder
            ->add('code', TextType::class, ['required' => true])
            ->add('name')
            ->add('description')
            ->add('brand')
            ->add('price')
            ->add('categoria', ChoiceType::class, ['choices' => $categorias])
            ->add('save', SubmitType::class, ['label' => 'Guardar'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    public function getCategorias(): array
    {
        $categorias_format = [];
        $categorias = $this->doctrine->getRepository(Category::class)->findAllSomeFields("name, id");

        foreach ($categorias as $clave => $valor) {
            $cty = [];

            foreach ($valor as $c => $v) {
                switch ($c) {
                    case 'name':
                        $name = $v;
                        break;
                    default:
                        $value = $v;
                        break;
                }
            }
            $categorias_format["{$name}"] = $value;
        }

        return $categorias_format;
    }

}
