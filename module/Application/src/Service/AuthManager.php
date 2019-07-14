<?php
declare(strict_types=1);


namespace Application\Service;

use Zend\Authentication\Result;

class AuthManager
{
    /**
     * Authentication service.
     * @var \Zend\Authentication\AuthenticationService
     */
    private $authService;

    /**
     * Session manager.
     * @var \Zend\Session\SessionManager
     */
    private $sessionManager;

    /**
     * Contents of the 'access_filter' config key.
     * @var array
     */
    private $config;

    /**
     * AuthManager constructor.
     * @param \Zend\Authentication\AuthenticationService $authService
     * @param \Zend\Session\SessionManager $sessionManager
     * @param array $config
     */
    public function __construct(
        \Zend\Authentication\AuthenticationService $authService,
        \Zend\Session\SessionManager $sessionManager,
        array $config
    ) {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
        $this->config = $config;
    }


    /**
     * Совершает попытку входа на сайт. Если значение аргумента $rememberMe равно true, сессия
     * будет длиться один месяц (иначе срок действия сессии истечет через один час).
     * @param $login
     * @param $password
     * @param $rememberMe
     * @return \Zend\Authentication\Result
     * @throws \Exception
     */
    public function login($login, $password, $rememberMe)
    {
        // Check if user has already logged in. If so, do not allow to log in
        // twice.
        if ($this->authService->getIdentity() != null) {
            throw new \Exception('Already logged in');
        }

        // Authenticate with login/password.
        $authAdapter = $this->authService->getAdapter();
        $authAdapter->setLogin($login);
        $authAdapter->setPassword($password);
        $result = $this->authService->authenticate();

        // If user wants to "remember him", we will make session to expire in
        // one month. By default session expires in 1 hour (as specified in our
        // config/global.php file).
        if ($result->getCode() == Result::SUCCESS && $rememberMe) {
            // Session cookie will expire in 1 month (30 days).
            $this->sessionManager->rememberMe(60 * 60 * 24 * 30);
        }

        return $result;
    }

    /**
     * Проверка если авторизация
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->authService->hasIdentity();
    }

    /**
     * Performs user logout.
     * @throws \Exception
     */
    public function logout()
    {
        // Allow to log out only when user is logged in.
        if ($this->authService->getIdentity() == null) {
            throw new \Exception('The user is not logged in');
        }

        // Remove identity from session.
        $this->authService->clearIdentity();
    }
}
