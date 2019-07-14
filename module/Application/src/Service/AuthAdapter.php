<?php
declare(strict_types=1);


namespace Application\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

/**
 * Это адаптер, используемый для аутентификации пользователя. Он принимает логин (адрес эл. почты)
 * и пароль, и затем проверяет, есть ли в базе данных пользователь с такими учетными данными.
 * Если такой пользователь существует, сервис возвращает его личность (эл. адрес). Личность
 * сохраняется в сессии и может быть извлечена позже с помощью помощника представления Identity,
 * предоставляемого ZF3.
 */
class AuthAdapter implements AdapterInterface
{
    /**
     * E-mail пользователя.
     * @var string
     */
    private $login;

    /**
     * Пароль.
     * @var string
     */
    private $password;

    public function authenticate()
    {
        if ($this->getLogin() == "admin" && $this->getPassword() == "123") {
            return new Result(
                Result::SUCCESS,
                $this->getLogin(),
                ['Authenticated successfully.']);
        }

        // Если пароль не прошел проверку, возвращаем статус ошибки 'Invalid Credential'.
        return new Result(
                Result::FAILURE_CREDENTIAL_INVALID,
                null,
                ['Invalid credentials.']);

    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }



}
