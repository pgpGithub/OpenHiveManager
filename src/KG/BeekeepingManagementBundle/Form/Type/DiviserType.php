<?php

/* 
 * Copyright (C) 2015 Kévin Grenèche < kevin.greneche at openhivemanager.org >
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
                            'class' => 'rucher_selector'
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
