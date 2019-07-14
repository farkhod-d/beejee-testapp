<?php
declare(strict_types=1);

namespace Application\Controller;

use Application\Form\LoginForm;
use Application\Service\AuthManager;
use Zend\Authentication\Result;
use Zend\Http\PhpEnvironment\Request as HttpRequest;
use Zend\Http\PhpEnvironment\Response as HttpResponse;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class AuthController
 * @package Application\Controller
 * @method HttpRequest getRequest()
 * @method HttpResponse getResponse()
 */
class AuthController extends AbstractActionController
{
    /**
     * Менеджер аутентификации.
     * @var AuthManager
     */
    private $authManager;

    /**
     * AuthController constructor.
     * @param AuthManager $authManager
     */
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * Авторизация
     * @return \Zend\Http\Response|ViewModel
     * @throws \Exception
     */
    public function loginAction()
    {
        if ($this->identity()) {
            return $this->redirect()->toRoute("home");
        }

        $model = new ViewModel();
        $form = new LoginForm();
        $isLoginError = false;

        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();

                /** @var Result $result */
                $result = $this->authManager->login($data['login'], $data['password'], $data['remember_me']);
                if ($result->getCode() == Result::SUCCESS) {
                    return $this->redirect()->toRoute('home');
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
            }
        }

        $model->setVariables([
            "form" => $form,
            "isLoginError" => $isLoginError,
        ]);
        return $model;
    }

    /**
     * Выход
     * @return \Zend\Http\Response
     * @throws \Exception
     */
    public function logoutAction()
    {
        $this->authManager->logout();
        return $this->redirect()->toRoute('login');
    }
}
