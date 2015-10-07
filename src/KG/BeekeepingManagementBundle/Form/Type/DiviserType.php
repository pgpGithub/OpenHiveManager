<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use KG\BeekeepingManagementBundle\Entity\Reine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Form\EventListener\DeplacerEmplacementFieldSubscriber;
use Doctrine\ORM\EntityRepository;

class DiviserType extends AbstractType
{
    private $colonieMere;
    private $origine;
    
    /**
     * Constructor
     */
    public function __construct(\KG\BeekeepingManagementBundle\Entity\Colonie $colonieMere, $origine)
    {
        $this->colonieMere = $colonieMere;
        $this->origine = $origine;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToEmplacement = 'emplacement';
        $exploitation = $this->colonieMere->getRucher()->getExploitation()->getId();
        
        $colonieFille = new \KG\BeekeepingManagementBundle\Entity\Colonie();
        $colonieFille->setOrigineColonie($this->origine);
        $colonieFille->setEtat($this->colonieMere->getEtat());
        $colonieFille->setAgressivite($this->colonieMere->getAgressivite());
        $colonieFille->setReine(new Reine());
        $colonieFille->getReine()->setRace($this->colonieMere->getReine()->getRace());
        $colonieFille->setColonieMere($this->colonieMere);
        
        $builder
            ->add('rucher', 'entity', array(
                        'class'         => 'KGBeekeepingManagementBundle:Rucher',
                        'choice_label'  => 'nom',
                        'empty_value'   => '',
                        'mapped'        => false,
                        'attr'          => array(
                            'class' => 'rucher_selector',
                            'label_col' => 4, 
                            'widget_col' => 5
                        ),
                        'query_builder' => function (EntityRepository $repository) use ($exploitation) {
                            $qb = $repository->queryfindByExploitationId($exploitation);
                            return $qb;
                        }
                    ))
            ->addEventSubscriber(new DeplacerEmplacementFieldSubscriber($propertyPathToEmplacement))    
            ->add('nom',  'text', array('attr' => array('label_col' => 4, 'widget_col' => 5)))
            ->add('matiere', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Matiere',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null,
                        'attr' => array('label_col' => 4, 'widget_col' => 5)
                    ))     
            ->add('corps', new DiviserCorpsType($this->colonieMere))
            ->add('image', new ImageType(), array('required' => false))        
            ->add('colonie', new ColonieFilleType($this->colonieMere->getDateColonie()), array(
                        'data' => $colonieFille
                    ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Ruche'
        ));         
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_diviser';
    }
}
