<?php
declare(strict_types=1);

namespace Application\Form;

use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\Stdlib\InitializableInterface;

class LoginForm extends Form implements InitializableInterface
{
    /**
     * IssueForm constructor.
     */
    public function __construct()
    {
        parent::__construct('login-form');

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
                'name' => 'login',
                'options' => [
                    'label' => 'Логин',
                ],
                'attributes' => [
                    'id' => 'login',
                ],
            ])
            ->add([
                'type' => Password::class,
                'name' => 'password',
                'options' => [
                    'label' => 'Пароль',
                ],
                'attributes' => [
                    'id' => 'password',
                ],
            ]);

        // Add "remember_me" field
        $this->add([
            'type' => 'checkbox',
            'name' => 'remember_me',
        ]);
        // Add the CSRF field
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
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
            'name' => "login",
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
        ]);
        $inputFilter->add([
            'name' => "password",
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
        ]);

        // Add input for "remember_me" field
        $inputFilter->add([
            'name' => 'remember_me',
            'required' => false,
            'filters' => [
            ],
            'validators' => [
                [
                    'name' => 'InArray',
                    'options' => [
                        'haystack' => [0, 1],
                    ]
                ],
            ],
        ]);
    }
}
