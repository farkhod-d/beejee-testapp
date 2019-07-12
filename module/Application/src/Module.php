<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Http\Headers;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Helper\Asset;
use Zend\View\Helper\HeadLink;
use Zend\View\Helper\HeadMeta;
use Zend\View\Helper\HeadScript;
use Zend\View\Helper\InlineScript;
use Zend\Validator\AbstractValidator;
use Zend\Session\SessionManager;

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
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, [$this, 'translateConfig'], 100);
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, [$this, 'sessionConfig'], 100);
    }

    public function onDispatch(MvcEvent $e) {
        /**
         * Просим пауков не индексировать
         * @var Headers $headers
         */
        $headers = $e->getResponse()->getHeaders();
        $headers->addHeaders(['X-Robots-Tag' => 'noindex']);

        /**
         * настройки EVENT_RENDER
         */
        //$eventManager = $e->getApplication()->getEventManager();
        //$eventManager->attach(MvcEvent::EVENT_RENDER, [$this, 'setLayoutParams']);
    }

    public function translateConfig(MvcEvent $e)
    {
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();

        // Установка переводчика по умолчанию
        $translator = $serviceManager->get('translator');
        AbstractValidator::setDefaultTranslator($translator);
    }

    public function sessionConfig(MvcEvent $e) {
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();

        /**
         * Следующая строка создает экземпляр SessionManager и автоматически делает SessionManager "стандартным",
         * чтобы избежать передачи диспетчера сеансов в качестве зависимости от других моделей.
         * @var \Zend\Session\SessionManager $sessionManager
         */
        $sessionManager = $serviceManager->get(SessionManager::class);
    }

    /**
     * @param MvcEvent $e
     * @todo Включить
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
            ->appendName('robots', 'noindex, nofollow')
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
            ->appendHttpEquiv('imagetoolbar', 'no')
            ->appendName('msapplication-TileColor', '#2b5797')
            ->appendName('msapplication-TileImage', '/assets/img/favicons/apple-touch-icon-144x144.png')
            ->appendName('msapplication-config', '/assets/img/favicons/browserconfig.xml')
            ->appendName('theme-color', '#ffffff');
        //endregion
        //region Favicons
        $headLink()
            ->headLink([
                'rel' => 'icon shortcut',
                'href' => '/assets/img/favicons/favicon.ico',
            ])
            ->headLink([
                'rel' => 'apple-touch-icon',
                'href' => '/assets/img/favicons/apple-touch-icon-57x57.png',
            ])
            ->headLink([
                'rel' => 'apple-touch-icon',
                'sizes' => '114x114',
                'href' => '/assets/img/favicons/apple-touch-icon-114x114.png',
            ])
            ->headLink([
                'rel' => 'apple-touch-icon',
                'sizes' => '72x72',
                'href' => '/assets/img/favicons/apple-touch-icon-72x72.png',
            ])
            ->headLink([
                'rel' => 'apple-touch-icon',
                'sizes' => '144x144',
                'href' => '/assets/img/favicons/apple-touch-icon-144x144.png',
            ])
            ->headLink([
                'rel' => 'apple-touch-icon',
                'sizes' => '60x60',
                'href' => '/assets/img/favicons/apple-touch-icon-60x60.png',
            ])
            ->headLink([
                'rel' => 'apple-touch-icon',
                'sizes' => '120x120',
                'href' => '/assets/img/favicons/apple-touch-icon-120x120.png',
            ])
            ->headLink([
                'rel' => 'apple-touch-icon',
                'sizes' => '76x76',
                'href' => '/assets/img/favicons/apple-touch-icon-76x76.png',
            ])
            ->headLink([
                'rel' => 'apple-touch-icon',
                'sizes' => '152x152',
                'href' => '/assets/img/favicons/apple-touch-icon-152x152.png',
            ])
            ->headLink([
                'rel' => 'apple-touch-icon',
                'sizes' => '180x180',
                'href' => '/assets/img/favicons/apple-touch-icon-180x180.png',
            ])
            ->headLink([
                'rel' => 'apple-touch-icon-precomposed',
                'href' => '/assets/img/favicons/apple-touch-icon-180x180.png',
            ])
            ->headLink([
                'rel' => 'icon',
                'type' => 'image/png',
                'sizes' => '192x192',
                'href' => '/assets/img/favicons/favicon-192.png',
            ]);
        //endregion
        //region HeadLink
        $headLink
            ->appendStylesheet($asset('fonts.css'), 'screen', true, [
                'crossorigin' => 'anonymous',
            ])
            ->appendStylesheet($asset('payment.style.css'), 'screen', true, [
                'crossorigin' => 'anonymous',
            ]);
        //endregion

        $headMeta->setIndent(4);
        $headLink->setIndent(4);
        $headScript->setIndent(4);
        $inlineScript->setIndent(4);
    }
}
