<?php
declare(strict_types=1);

namespace Artifakt\CLI\Command;

use Artifakt\CLI\Actions\Source\ActionList;
use Artifakt\CLI\Entities\Source\EntityList;
use Artifakt\CLI\Http\Factory\RequestFactory;
use Artifakt\CLI\Http\Methods;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArtifaktCommand
 * @package Artifakt\CLI\Command
 */
class ArtifaktCommand extends Command
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var null|string
     */
    private $token;

    /**
     * ArtifaktCommand constructor.
     *
     * @param string     $name
     * @param ClientInterface $client
     * @param RequestFactory  $requestFactory
     * @param string     $token
     */
    public function __construct(
        string $name,
        ClientInterface $client,
        RequestFactory $requestFactory,
        string $token = ''
    ) {

        //Thanks symfony for strict typing we love it <3
        if(empty($name)){
            $name = null;
        }

        parent::__construct($name);

        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setDescription('Artifakt CLI')
            ->addArgument('action', InputArgument::REQUIRED, 'Action to execute')
            ->addArgument('entity', InputArgument::REQUIRED, 'Entity name')
            ->addArgument('id', InputArgument::OPTIONAL, 'The entity unique identifier')
            ->addArgument('json', InputArgument::OPTIONAL, 'Path to json file')
            ->addOption('token', 't', InputOption::VALUE_OPTIONAL, 'Artifakt API Token');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (null === $this->token && null === $this->token = $input->getOption('token')) {
            throw new \Exception('You need to provide an Artifakt API token to use this CLI.');
        }

        $builder = $this->requestFactory->createBuilder();

        $action = $input->getArgument('action');
        if (!\in_array($action, ActionList::getActions())) {
            // Throw new ActionNotAvailableException ? InvalidArgumentException ?
            throw new \Exception(sprintf('Action : "%s" is not available.', $action));
        }

        $builder->setAction($action);

        $entity = $input->getArgument('entity');
        if (!\in_array($entity, EntityList::getEntities())) {
            // Throw new EntityNotAvailableException ? InvalidArgumentException ?
            throw new \Exception(\sprintf('Entity : "%s" is not available.', $entity));
        }

        $builder
            ->setEntity($entity)
            ->setParam($input->getArgument('id'));


        switch ($action) {
            case ActionList::CREATE:
                $method = Methods::POST;
                break;
            case ActionList::LIST:
            case ActionList::GET:
                $method = Methods::GET;
                break;
            case ActionList::DELETE:
                $method = Methods::DELETE;
                break;
            case ActionList::UPDATE:
                $method = Methods::PUT;
                break;
            default:
                throw new \LogicException('Dafuq? ¯\_(ツ)_/¯');
        }

        $body = '';
        $json = $input->getArgument('json');
        if (null !== $json && \is_file($json)) {
            $body = \file_get_contents($json); // Better way?
        }

        $request = $builder
            ->setMethod($method)
            ->addHeader('ARTIFAKT-HELLO-TOKEN', $this->token)
            ->addHeader('CONTENT-TYPE', 'application/json')
            ->setBody($body)
            ->getRequest();

        $promise = $this->client->sendAsync($request);

        // Maybe use Symfony EventDispatcher to dispatch events ? To logs errors or idk...

        $promise->then(
            function (ResponseInterface $response) use ($output) {
                $output->writeln($response->getStatusCode());
                $output->writeln($response->getBody()->getContents());
            },
            function (RequestException $e) use ($output) {
                $output->writeln($e->getMessage());
                $output->writeln($e->getResponse()->getBody()->getContents());
                $output->writeln($e->getRequest()->getMethod());
            }
        );

        $promise->wait();
    }
}
