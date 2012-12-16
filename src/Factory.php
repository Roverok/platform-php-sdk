<?php

namespace Bigpoint;

class Factory
{
    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var SessionAdapter
     */
    private $sessionAdapter;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * Create the Environment.
     *
     * @return Environment
     */
    private function createEnvironment()
    {
        if (null === $this->environment) {
            $this->environment = new Environment(
                $_GET,
                $_SERVER
            );
        }
        return $this->environment;
    }

    /**
     * Create a Request.
     *
     * @return Request
     */
    private function createRequest()
    {
        return new Request();
    }

    /**
     * Create a Response.
     *
     * @return Response
     */
    private function createResponse()
    {
        return new Response();
    }

    /**
     * Create the SessionAdapter.
     */
    private function createSessionAdapter()
    {
        if (null === $this->sessionAdapter) {
            $this->sessionAdapter = new SessionAdapter();
        }
        return $this->sessionAdapter;
    }

    /**
     * Create a Persistence.
     *
     * @return SessionPersistence
     */
    private function createSessionPersistence()
    {
        return new SessionPersistence(
            $this->createSessionAdapter()
        );
    }

    /**
     * Create a CurlAdapter.
     *
     * @return CurlAdapter
     */
    private function createCurlAdatper()
    {
        return new CurlAdapter();
    }

    /**
     * Create a HttpClient.
     *
     * @return HttpClient
     */
    private function createHttpClient()
    {
        return new CurlClient(
            new Response(),
            new CurlAdapter()
        );
    }

    /**
     * Create a Configuration.
     *
     * @return Configuration
     */
    private function createConfiguration($config)
    {
        if (null === $this->configuration) {
            $this->configuration = new Configuration(
                $this->createEnvironment(),
                $config
            );
        }
        return $this->configuration;
    }

    /**
     * Create an Oauth2Client.
     *
     * @return Oauth2Client
     */
    private function createOauth2Client($config)
    {
        return new Oauth2Client(
            $this->createEnvironment(),
            $this->createSessionPersistence(),
            $this->createHttpClient(),
            $this->createRequest(),
            $this->createConfiguration($config)
        );
    }

    /**
     * Create the Api.
     *
     * @return Api
     */
    public function createApi(array $config)
    {
        return new Api(
            $this->createOauth2Client($config),
            $this->createHttpClient(),
            $this->createRequest(),
            $this->createConfiguration($config)
        );
    }
}