<?php

namespace App\RackFiles;

use OpenCloud\Rackspace;

class RackAuth
{
    /**
     * Rackspace username
     *
     * @var string
     */
    protected $username;

    /**
     * Authentication key
     *
     * @var string
     */
    protected $authKey;

    /**
     * Bucket name
     *
     * @var [type]
     */
    protected $bucketName;

    /**
     * Rackspace region
     */
    protected $region;

    /**
     * Authorized connection
     *
     * @var OpenCloud\Rackspace
     */
    private $connection;

    /**
     * Rackspace object storage
     *
     * @var object
     */
    public $objectStorage;

    /**
     * Rackspace authentication endpoint
     */
    const AUTH_URL = 'https://identity.api.rackspacecloud.com/v2.0/';

    public function __construct($userName, $authKey, $region)
    {

        $this->userName = $userName;
        $this->authKey = $authKey;
        $this->region = $region;

        $credentials = [
            'username' => $userName,
            'apiKey' => $authKey
        ];

        $this->connection = new Rackspace(self::AUTH_URL, $credentials);
        $this->objectStorage = $this->createObjectStorage();
    }

    /**
     * Create storage service object
     *
     * @return void
     */
    private function createObjectStorage()
    {
        return $this->connection->objectStoreService('cloudFiles', $this->region);
    }
}
