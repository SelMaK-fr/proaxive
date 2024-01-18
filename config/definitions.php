<?php
declare(strict_types=1);

use App\Interface\GeneratePdfInterface;
use Awurth\Validator\Assertion\DataCollectorAsserter;
use Awurth\Validator\StatefulValidator;
use Envms\FluentPDO\Query;
use Monolog\Formatter\LineFormatter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Pagerfanta\Twig\Extension\PagerfantaExtension;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Selmak\Proaxive2\Settings\SettingsInterface;
use Selmak\Proaxive2\TwigExtension\AppExtension;
use Selmak\Proaxive2\TwigExtension\BasePathExtension;
use Selmak\Proaxive2\TwigExtension\FrontFunctionTwig;
use Selmak\Proaxive2\TwigExtension\TwigCsrfExtension;
use Selmak\Proaxive2\TwigExtension\TwigMessageExtension;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Selmak\Proaxive2\Settings\Settings;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },
    'parameters' => function () {
        $readJson = file_get_contents(dirname(__DIR__) . '/config/parameters.json');
        return json_decode($readJson, false);
    },
    // Return Setting Proaxive application
    SettingsInterface::class => function (ContainerInterface $container) {
        return new Settings($container->get('settings'));
    },

    //Init/Create Slim App
    App::class => function (ContainerInterface $container) {
        return AppFactory::createFromContainer($container);
    },

    // Return RouteParser in the Container
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },

    // Response Factory
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    // Config Database (do not edited here, view /config/settings.php for configuration
    // Return settings for FluentPDO instance
    PDO::class => function (ContainerInterface $container) {
        $settings = $container->get(SettingsInterface::class)->get('db');
        $pdo = new PDO('mysql:host=' . $settings['host'] . ';charset=utf8mb4;dbname=' . $settings['dbname'],
            $settings['user'], $settings['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    },

    // Load FluentPDO in the Container
    Query::class => function (ContainerInterface $container){
        return new Query($container->get(PDO::class));
    },

    // Key for TwigMiddleware
    'view' => function (ContainerInterface $container) {
        return $container->get(Twig::class);
    },

    LoggerInterface::class => function (ContainerInterface $container) {
        $setting = $container->get(SettingsInterface::class);
        $loggerSettings = $setting->get('logger');
        $filename = sprintf('%s/app.log', $loggerSettings['path']);
        $logger = new \Monolog\Logger($loggerSettings['name']);
        $handler = new \Monolog\Handler\RotatingFileHandler($filename, 3, $loggerSettings['level'], true, 0777);
        $handler->setFormatter(new LineFormatter(null, null, false, true));
        $logger->pushHandler($handler);
        return $logger;
    },

    'logger' => function(ContainerInterface $container) {
      return $container->get(LoggerInterface::class);
    },

    DataCollectorAsserter::class => function () {
        return DataCollectorAsserter::create();
    },

    StatefulValidator::class => function (ContainerInterface $container) {
        return StatefulValidator::create($container->get(DataCollectorAsserter::class));
    },

    // Load Twig Template
    Twig::class => function (ContainerInterface $container) {
        $settings = $container->get('settings');
        $options = [
            'debug' => $settings['app']['debug'],
            'cache' => $settings['view']['cache'],
        ];
        $twig = Twig::create($settings['view']['path'], $options);
        $twig->getEnvironment()->addGlobal('getSession', $container->get(SessionInterface::class));
        $twig->getEnvironment()->addGlobal('appRoot', $container->get(SettingsInterface::class)->get('app'));// App Name etc
        $twig->getEnvironment()->addGlobal('config', $container->get('parameters'));
        $twig->addExtension($container->get(TwigMessageExtension::class));
        $twig->addExtension($container->get(TwigCsrfExtension::class));
        $twig->addExtension($container->get(DebugExtension::class));
        $twig->addExtension($container->get(BasePathExtension::class));
        $twig->addExtension(new \Awurth\Validator\Twig\LegacyValidatorExtension($container->get(StatefulValidator::class), $container->get(DataCollectorAsserter::class)));
        $twig->addExtension(new AppExtension());
        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new FrontFunctionTwig());
        return $twig;
    },

    // Session by Odan for Slim
    SessionManagerInterface::class => function (ContainerInterface $container) {
        return $container->get(SessionInterface::class);
    },
    SessionInterface::class => function (ContainerInterface $container) {
        $options = $container->get(SettingsInterface::class)->get('session');
        return new PhpSession($options);
    },

    'breadcrumbs' => function () {
        return new Creitive\Breadcrumbs\Breadcrumbs();
    },

    'csrf' => function(ContainerInterface $container) {
        $storageCSRF = [];
        return new Guard($container->get(ResponseFactoryInterface::class), 'csrf', $storageCSRF, null, 200, 32, true);
    },

    Guard::class => function(ContainerInterface $container) {
        return $container->get('csrf');
    }

];