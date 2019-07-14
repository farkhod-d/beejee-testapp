<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\AuthController;
use Application\Service\AuthManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Validator\AbstractValidator;
use Zend\View\Helper\Asset;
use Zend\View\Helper\HeadLink;
use Zend\View\Helper\HeadMeta;
use Zend\View\Helper\HeadScript;
use Zend\View\Helper\InlineScript;

/**
 * @method Request getRequest()
 * @method Response getResponse()
 */
class Module implements ConfigProviderInterface
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return array_merge(
            require __DIR__ . '/../config/module.config.php',
            require __DIR__ . '/../config/router.config.php'
        );
    }

    public function init(ModuleManagerInterface $manager)
    {
        $eventManager = $manager->getEventManager();
        // Register the event listener method.
        $sharedEventManager = $eventManager->getSharedManager();
    }

    /**
     * This method is called once the MVC bootstrapping is complete and allows
     * to register event listeners.
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        // Get event manager.
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        $sharedEventManager->attach(AbstractActionController::class,
                MvcEvent::EVENT_DISPATCH, [$this, 'accessFilter'], 100);

        $sessionManager = $event->getApplication()->getServiceManager()->get(SessionManager::class);
        $this->forgetInvalidSession($sessionManager);

        $this->translateConfig($event);
        $this->setLayoutParams($event);
    }

    protected function forgetInvalidSession($sessionManager)
    {
    	try {
    		$sessionManager->start();
    		return;
    	} catch (\Exception $e) {
    	}
    	/**
    	 * Session validation failed: toast it and carry on.
    	 */
    	// @codeCoverageIgnoreStart
    	session_unset();
    	// @codeCoverageIgnoreEnd
    }

    public function translateConfig(MvcEvent $e)
    {
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();

        // Установка переводчика по умолчанию
        $translator = $serviceManager->get('translator');
        AbstractValidator::setDefaultTranslator($translator);
    }


    /**
     * Метод-обработчик для события 'Dispatch'. Мы обрабатываем событие Dispatch
     * для вызова фильтра доступа. Фильтр доступа позволяет определить,
     * может ли пользователь просматривать страницу. Если пользователь не
     * авторизован, и у него нет прав для просмотра, мы перенаправляем его
     * на страницу входа на сайт.
     * @param MvcEvent $e
     * @return
     */
    public function accessFilter(MvcEvent $e)
    {
        // Получаем контроллер и действие, которому был отправлен HTTP-запрос.
        /** @var \Zend\Mvc\Controller\AbstractActionController $controller */
        $controller = $e->getTarget();
        $controllerName = $e->getRouteMatch()->getParam('controller', null);
        $actionName = $e->getRouteMatch()->getParam('action', null);

        // Конвертируем имя действия с пунктирами в имя в верблюжьем регистре.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        // Получаем экземпляр сервиса AuthManager.
        $authManager = $e->getApplication()->getServiceManager()->get(AuthManager::class);

        // Выполняем фильтр доступа для каждого контроллера кроме AuthController
        // (чтобы избежать бесконечного перенаправления).
        if ($controllerName != AuthController::class &&
            !$authManager->filterAccess($controllerName, $actionName)) {

            // Запоминаем URL страницы, к которой пытался обратиться пользователь. Мы перенаправим пользователя
            // на этот URL после успешной авторизации.
            $uri = $e->getApplication()->getRequest()->getUri();
            // Делаем URL относительным (убираем схему, информацию о пользователе, имя хоста и порт),
            // чтобы избежать перенаправления на другой домен недоброжелателем.
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();

            // Перенаправляем пользователя на страницу "Login".
            return $controller->redirect()->toRoute('login', [],
                ['query' => ['redirectUrl' => $redirectUrl]]);
        }
    }

    /**
     * @param MvcEvent $e
     */
    public function setLayoutParams($e)
    {
        // Getting the view helper manager from the application service manager
        $viewHelperManager = $e->getApplication()->getServiceManager()->get('ViewHelperManager');
        /**
         * @var HeadLink $headLink
         * @var HeadMeta $headMeta
         * @var HeadScript $headScript
         * @var InlineScript $inlineScript
         * @var Asset $assert
         */
        $headMeta = $viewHelperManager->get('headMeta');
        $headLink = $viewHelperManager->get('headLink');
        $headScript = $viewHelperManager->get('headScript');
        $inlineScript = $viewHelperManager->get('inlineScript');
        $asset = $viewHelperManager->get('asset');

        //region header meta
        $headMeta
            ->appendHttpEquiv('Content-Type', 'text/html')
            ->appendHttpEquiv('Content-Language', 'ru-RU')
            ->appendHttpEquiv('cleartype', 'on')
            ->appendHttpEquiv('expires', 'Wed, 26 Feb 1997 08:21:57 GMT')
            ->appendHttpEquiv('pragma', 'no-cache')
            ->appendHttpEquiv('Cache-Control', 'no-cache')
            ->appendName('viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no')
            ->appendHttpEquiv('X-UA-Compatible', 'IE=edge')
            ->appendName('HandheldFriendly', 'True')
            ->appendName('MobileOptimized', 'width')
            ->appendName('apple-mobile-web-app-capable', 'yes')
            ->appendName('apple-mobile-web-app-status-bar-style', 'black-translucent')
            ->appendName('application-name', 'Title')
            ->appendName('msapplication-tooltip', 'Description')
            ->appendHttpEquiv('imagetoolbar', 'no');
        //endregion
        //region Favicon
        // @formatter:off
        $headLink()
            ->headLink(['rel' => 'apple-touch-icon', "sizes" => "57x57", 'href' => '/img/favicon/apple-icon-57x57.png'])
            ->headLink(['rel' => 'apple-touch-icon', "sizes" => "60x60", 'href' => '/img/favicon/apple-icon-60x60.png'])
            ->headLink(['rel' => 'apple-touch-icon', "sizes" => "72x72", 'href' => '/img/favicon/apple-icon-72x72.png'])
            ->headLink(['rel' => 'apple-touch-icon', "sizes" => "76x76", 'href' => '/img/favicon/apple-icon-76x76.png'])
            ->headLink(['rel' => 'apple-touch-icon', "sizes" => "114x114", 'href' => '/img/favicon/apple-icon-114x114.png'])
            ->headLink(['rel' => 'apple-touch-icon', "sizes" => "120x120", 'href' => '/img/favicon/apple-icon-120x120.png'])
            ->headLink(['rel' => 'apple-touch-icon', "sizes" => "144x144", 'href' => '/img/favicon/apple-icon-144x144.png'])
            ->headLink(['rel' => 'apple-touch-icon', "sizes" => "152x152", 'href' => '/img/favicon/apple-icon-152x152.png'])
            ->headLink(['rel' => 'apple-touch-icon', "sizes" => "180x180", 'href' => '/img/favicon/apple-icon-180x180.png'])
            ->headLink(['rel' => 'icon', "type" => "image/png", "sizes" => "192x192", 'href' => '/img/favicon/android-icon-192x192.png'])
            ->headLink(['rel' => 'icon', "type" => "image/png", "sizes" => "32x32", 'href' => '/img/favicon/favicon-32x32.png'])
            ->headLink(['rel' => 'icon', "type" => "image/png", "sizes" => "96x96", 'href' => '/img/favicon/favicon-96x96.png'])
            ->headLink(['rel' => 'icon', "type" => "image/png", "sizes" => "16x16", 'href' => '/img/favicon/favicon-16x16.png'])
            ->headLink(['rel' => 'manifest', 'href' => '/img/favicon/manifest.json']);
        $headMeta->appendName('msapplication-TileColor', '#ffffff')
            ->appendName('msapplication-TileImage', '/img/favicon/ms-icon-144x144.png')
            ->appendName('theme-color', '#ffffff');
        //endregion @formatter:on
        //region HeadLink
        $headLink
            ->appendStylesheet('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', 'screen', true, ['crossorigin' => 'anonymous'])
            ->appendStylesheet('https://use.fontawesome.com/releases/v5.6.3/css/all.css', 'screen', true, ['crossorigin' => 'anonymous'])
            ->appendStylesheet('/css/main.css', 'screen', true, ['crossorigin' => 'anonymous']);
        //endregion

        // $headScript
        //     ->appendFile("js/jquery-3.1.0.min.js")
        //     ->appendFile("js/bootstrap.min.js")
        // ;

        //region inlineScript
        $inlineScript->appendFile("https://code.jquery.com/jquery-3.3.1.slim.min.js", null, ['crossorigin' => 'anonymous'])
            ->appendFile("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js", null, ['crossorigin' => 'anonymous'])
            ->appendFile("https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js", null, ['crossorigin' => 'anonymous'])
            ->appendScript('$(function () {$(\'[data-toggle="tooltip"]\').tooltip()})');
        //endregion

        $headMeta->setIndent(4);
        $headLink->setIndent(4);
        $headScript->setIndent(4);
        $inlineScript->setIndent(4);
    }
}
