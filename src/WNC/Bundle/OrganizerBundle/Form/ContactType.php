<?php

namespace WNC\Bundle\OrganizerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
             ->add('name', 'text', array('attr' => array('placeholder' => 'Sender name'), 'label' => 'From'))
             ->add('email', 'email', array('attr' => array('placeholder' => 'example@acme.com'), 'label' => 'Email address'))
             ->add('topic', 'text', array('attr' => array('placeholder' => 'Example Subject'), 'label' => 'Subject'))
             ->add('message', 'textarea', array('attr' => array('rows' => '6', 'placeholder' => 'Some text here'), 'label' => 'Body'))
//             ->add('captcha', 'genemu_captcha', array(
//                 'width' => '500',
//                 'height' => '100',
//                 'font_size' => '30'
//             ));

        ;
    }

    public function getName()
    {
        return 'wnc_organizerbundle_contacttype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
//        $collectionConstraint = new Collection(array(
//            'name' => new MinLength(array('message' => 'Message is to short. Use {{ limit }} chars or more', 'limit' => 3)),
//            'topic' => new MinLength(array('message' => 'Message is to short. Use {{ limit }} chars or more', 'limit' => 5)),
//            'message' => new MinLength(array('message' => 'Message is to short. Use {{ limit }} chars or more', 'limit' => 10)),
//            'email' => new Email(array('message' => 'Invalid email address')),
////            'captcha' => new NotBlank()
//        ));
//
//        $resolver->setDefaults(array(
//            'validation_constraint' => $collectionConstraint
//        ));
    }
}
