<?php

namespace Application\Delegator;

use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Resources;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

class TranslatorDelegator implements DelegatorFactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $translator = $callback();

        $translator->addTranslationFilePattern(
            'phpArray',
            Resources::getBasePath(),
            Resources::getPatternForValidator()
        );
        $translator->addTranslationFilePattern(
            'phpArray',
            Resources::getBasePath(),
            Resources::getPatternForCaptcha()
        );

        return $translator;
    }
}
