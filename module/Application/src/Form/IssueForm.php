<?php
declare(strict_types=1);

namespace Application\Form;

use Application\Entity\Issues;
use Zend\Form\Element\Email;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\Stdlib\InitializableInterface;

use Zend\Hydrator\ClassMethods;

class IssueForm extends Form implements InitializableInterface
{
    /**
     * IssueForm constructor.
     */
    public function __construct()
    {
        parent::__construct('issue-form');
        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new Issues());

        // Добавляем элементы формы
        $this->addElements();

        // Добавляем валидаторы
        $this->addInputFilter();
    }

    public function init()
    {
    }

    private function addElements()
    {
        $this
            ->add([
                'type' => Text::class,
                'name' => 'userName',
                'options' => [
                    'label' => 'Ваше Имя',
                ],
                'attributes' => [
                    'id' => 'userName',
                ],
            ])
            ->add([
                'type' => Email::class,
                'name' => 'userEmail',
                'options' => [
                    'label' => 'Ваш Email',
                ],
                'attributes' => [
                    'id' => 'userEmail',
                ],
            ])
            ->add([
                'type' => Textarea::class,
                'name' => 'note',
                'options' => [
                    'label' => 'Задача',
                ],
                'attributes' => [
                    'id' => 'note',
                    "rows" => 3,
                ],
            ]);
    }

    /**
     * Этот метод создает фильтр входных данных
     * (используемый для фильтрации/валидации).
     */
    private function addInputFilter()
    {
        $inputFilter = $this->getInputFilter();
        $inputFilter->add([
            'name' => "userName",
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
        ]);

        $inputFilter->add([
            'name' => "userEmail",
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => "note",
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 4096
                    ],
                ],
            ],
        ]);
    }
}
