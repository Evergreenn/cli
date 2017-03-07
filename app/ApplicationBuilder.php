<?php
declare(strict_types=1);

namespace App;

use Artifakt\CLI\Command\ArtifaktCommand;
use Artifakt\CLI\Http\Factory\RequestFactory;
use Artifakt\CLI\Url\Generator\UrlGenerator;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ApplicationBuilder
 * @package App
 */
class ApplicationBuilder
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * ApplicationBuilder constructor.
     *
     * @param ContainerBuilder|null $container
     */
    public function __construct(ContainerBuilder $container = null)
    {
        if (null === $container) {
            $bag = new ParameterBag();
            if (isset($_ENV['ARTIFAKT_TOKEN'])) {
                $bag->set('artifakt_api_token', $_ENV['ARTIFAKT_TOKEN']);
            }

            $container = new ContainerBuilder($bag);
        }
        $this->container = $container;
    }

    /**
     * @return Application
     */
    public function build() : Application
    {
        $this->container
            ->register('client', Client::class)
            ->addArgument(['base_uri' => Application::URI])
            ->setPublic(false);

        $this->container
            ->register('url.generator', UrlGenerator::class)
            ->addArgument(Application::URI)
            ->setPublic(false);

        $this->container
            ->register('request.factory', RequestFactory::class)
            ->addArgument(new Reference('url.generator'))
            ->setPublic(false);

        $token = $this->container->hasParameter('artifakt_api_token')
            ? $this->container->getParameter('artifakt_api_token')
            : null;

        $this->container
            ->register('artifakt-cli', ArtifaktCommand::class)
            ->addArgument('artifakt-cli')
            ->addArgument(new Reference('client'))
            ->addArgument(new Reference('request.factory'))
            ->addArgument($token)
            ->setPublic(false);

        $this->container
            ->register('application', Application::class)
            ->addMethodCall('add', [new Reference('artifakt-cli')])
            ->addMethodCall('setDefaultCommand', ['artifakt-cli', true]);

        return $this->container->get('application');
    }
}
