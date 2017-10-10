<?php

namespace VivialConnect\Resources;

use VivialConnect\Transport\Connection;
use VivialConnect\Transport\ConnectionManager;
use VivialConnect\Common\ResponseException;
use VivialConnect\Common\Utility;
use VivialConnect\Common\Inflector;


abstract class Resource
{
    /**
     * The unique key field for the resource
     *
     * @var string $identifierName
     */
    protected $identifierName = 'id';

    /**
     * The name of the resource URI - defaults to the lowercase name of the Resource class
     *
     * @var string $resourceName
     */
    protected $resourceName = null;

    /**
     * The connection name to use for this resource
     *
     * @var string $connectionName
     */
    protected $connectionName = 'default';

    /**
     * Accounts prefix.
     *
     */
    const API_ACCOUNT_PREFIX = 'accounts/%s/';

    /**
     * API secret token.
     *
     * Type: string
     * Default: null
     */
    const API_SECRET = 'apiSecret';

    /**
     * API key token.
     *
     * Type: string
     * Default: null
     */
    const API_KEY = 'apiKey';

    /**
     * API account ID.
     *
     * Type: string
     * Default: null
     */
    const API_ACCOUNT_ID = 'apiAccountId';

    private $singularName = '_singular';
    private $pluralName = '_plural';

    /**
     * Plural form of the element name
     */
    private $_plural = null;

    /**
     * Singular form of the element name
     */
    private $_singular = null;

    /** @var array  */
    static protected $creds = [
        self::API_SECRET => null,
        self::API_KEY => null,
        self::API_ACCOUNT_ID => null,
    ];

    private $resourceIdentifier = null;
    private $attributes = [];
    private $dirty = [];
    private $dependentResources = [];

    /** @var Response */
    private $response = null;

    /** @var Error */
    private $error = null;

    public function __construct($data = null)
    {
        $name = Utility::getClassName(strtolower(get_class($this)));
        $this->_plural = Inflector::pluralize($name);
        $this->_singular = Inflector::singularize($name);

        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    /**
     * Get the ID of the resource
     *
     * @return mixed|null
     */
    public function getId()
    {
        return $this->resourceIdentifier;
    }

    /**
     * Get the identifier property name (defaults to "id")
     *
     * @return string
     */
    public function getIdentifierName()
    {
        return $this->identifierName;
    }

    /**
     * Get VivialConnect resource name.
     *
     * @return null|string
     */
    public function getResourceName()
    {
        if (empty($this->resourceName)) {
            return $this->_plural;
        }
        return $this->resourceName;
    }

    /**
     * Get the full resource URI
     *
     * @return string
     */
    public function getResourceUri()
    {
        $uri = sprintf(self::API_ACCOUNT_PREFIX,
            self::getCredentialToken(self::API_ACCOUNT_ID));
        if (($dependencies = $this->getDependencies())) {
           $uri .= "{$dependencies}/";
        }
        $uri .= $this->getResourceName();
        if (($id = $this->getId())) {
            $uri .= "/{$id}";
        }
        $uri .= ".json";
        return $uri;
    }

    /**
     * Get the Response object
     *
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get the error object
     *
     * @return Error|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Save the entity
     *
     * @param array $queryParams
     * @param array $headers
     *
     * @return bool
     */
    public function save(array $queryParams = [], array $headers = [], $isSubresource = False)
    {
        $connection = $this->getConnection();
        $data = array_merge($this->attributes, $this->dirty);
        $data = $this->wrapAttributes($data, $this->_singular);

        // No id, new (POST) resource instance
        if ($isSubresource == False && empty($this->resourceIdentifier)){
            $this->response = $connection->post($this->getResourceUri(), $queryParams, $data, $headers);
        }
        // Existing resource, update (PUT/PATCH) resource instance
        else {
            // Can we just send the dirty fields?
            if ($this->getConnection()->getOption(Connection::OPTION_UPDATE_DIFF)) {
                $data = $this->dirty;
                $data = $this->wrapAttributes($data, $this->_singular);
            }

            // Get the update method (usually either PUT or PATCH)
            $method = $connection->getUpdateMethod();

            // Do the update
            $this->response = $connection->{$method}($this->getResourceUri(), $queryParams, $data, $headers);
        }

        // Looks like a good response, re-hydrate object, and reset the dirty fields
        if ($this->response->isSuccessful()) {
            $data = $this->parseFind($this->response->getPayload());
            $this->error = null;
            $this->hydrate($data);
            $this->dirty = [];
            return true;
        }

        // Set the error
        $errorClass = $connection->getErrorClass();
        $this->error = new $errorClass($this->response);

        if ($this->response->isThrowable()) {
            throw new ResponseException($this->error);
        }

        return false;
    }


    /**
     * Destroy (delete) the resource
     *
     * @param array $queryParams
     * @param array $headers
     *
     * @return bool
     */
    public function destroy(array $queryParams = [], array $headers = [], $isSubresrouce = False)
    {
        $connection = $this->getConnection();
        if ($isSubresrouce == True) {
            $body = array_merge($this->attributes, $this->dirty);
            $body = $this->wrapAttributes($body, $this->_singular);
        }
        $this->response = $connection->delete($this->getResourceUri(), $queryParams, $body, $headers);
        if ($this->response->isSuccessful()) {
            $this->error = null;
            return true;
        }

        // Set the error
        $errorClass = $connection->getErrorClass();
        $this->error = new $errorClass($this->response);

        if ($this->response->isThrowable()) {
            throw new ResponseException($this->error);
        }

        return false;
    }


    /**
     * Mass assign properties with an array of key/value pairs
     *
     * @param array $data
     */
    public function fill(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Build a Collection of included resources in response payload.
     *
     * @param string $model
     * @param array $data
     *
     * @return Collection
     */
    public function includesMany($model, array $data)
    {
        if (empty($data)) {
            return new Collection($model, []);
        }

        return new Collection($model, $data);
    }

    /**
     * Build a single instance of an included resource in response payload.
     *
     * @param string $model
     * @param $data
     * @return Resource
     */
    public function includesOne($model, $data)
    {
        if (empty($data) || (!is_object($data) && !is_array($data))) {
            return $data;
        }

        return new $model($data);
    }

    /**
     * Set dependent resources to prepend to URI. You can call this method multiple times to prepend additional dependent
     * resources.
     *
     * For example, if the API only allows you create a new comment on a post *through* the post's URI:
     *  POST /posts/1234/comment
     *
     * $comment = new Comment;
     * $comment->through('posts/1234');
     * $comment->body = "This is a comment";
     * $comment->save();
     *
     * OR
     *
     * $post = Post::find(1234);
     * $comment = new Comment;
     * $comment->through($post);
     * $comment->body = "This is a comment";
     * $comment->save();
     *
     * @param Resource|string $resource
     */
    public function through($resource)
    {
        if ($resource instanceof Resource) {
            $this->dependentResources[] = $resource->getResourceUri();
        }
        else {
            $this->dependentResources[] = $resource;
        }
    }


    /**
     * Magic getter
     *
     * @param $property
     * @return mixed|null
     */
    public function __get($property)
    {
        // Check singular and plural properties
        if ($property == $this->singularName) {
            return $this->_singular;
        }
        if ($property == $this->pluralName) {
            return $this->_plural;
        }
        // Continue with getter
        if (array_key_exists($property, $this->dirty)) {
            return $this->dirty[$property];
        }
        elseif (array_key_exists($property, $this->attributes)) {
            return $this->attributes[$property];
        }
        return null;
    }

    /**
     * Magic setter
     *
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        // Check singular and plural properties
        if ($property == $this->singularName) {
            $this->_singular = $value;
            return;
        }
        if ($property == $this->pluralName) {
            $this->_plural = $value;
            return;
        }
        // Continue with setter
        if ($property == $this->identifierName) {
            $this->resourceIdentifier = $value;
        }
        $this->dirty[$property] = $value;
    }

    /**
     * Get the original value of a property (before it was modified).
     *
     *
     * @param $property
     * @return mixed|null
     */
    public function original($property)
    {
        if (array_key_exists($property, $this->attributes)) {
            return $this->attributes[$property];
        }
        return null;
    }

    /**
     * Reset all modified properties, reset response, reset error
     *
     * @return void
     */
    public function reset()
    {
        $this->dirty = [];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $attributes = array_merge($this->attributes, $this->dirty);

        foreach ($attributes as $property => $value) {
            if ($value instanceof Resource) {
                $attributes[$property] = $value->toArray();
            }
            elseif ($value instanceof \StdClass) {
                $attributes[$property] = (array)$value;
            }
            else {
                $attributes[$property] = $value;
            }
        }

        return $attributes;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Get the connection for the model
     *
     * @return Connection
     */
    public function getConnection()
    {
        return ConnectionManager::get($this->connectionName);
    }

    /**
     * Is this entity dirty?
     *
     * @return int
     */
    protected function isDirty()
    {
        return count($this->dirty);
    }

    /**
     * Get any resource dependencies
     *
     * @return string
     */
    protected function getDependencies()
    {
        return implode('/', $this->dependentResources);
    }

    /**
     * Where to find the single resource data from the response payload.
     *
     * You should overwrite this method in your model class to suite your needs.
     *
     * @param $payload
     * @return mixed
     */
    protected function parseFind($payload)
    {
        return Utility::removeRoot($payload);
    }

    /**
     * Where to find the array of data from the response payload.
     *
     * You should overwrite this method in your model class to suit your needs.
     *
     * @param $payload
     * @return mixed
     */
    protected function parseAll($payload)
    {
        return Utility::removeRoot($payload);
    }

    /**
     * Wraps attributes into VivialConnect compatible payload.
     *
     * @param $attributes
     * @param $root
     * @return array
     */
    protected function wrapAttributes(array $attributes = [], $root = 'object')
    {
        return [$root => $attributes];
    }

    /**
     * Hydrate
     *
     * @param $data
     *
     * @throws ResourceException
     *
     * @return boolean
     */
    protected function hydrate($data)
    {

        if (empty($data)) {
            return true;
        }

        // Convert array based data into object
        if (is_array($data)) {
            $data = (object)$data;
        }
        // Process the data payload object
        if (is_object($data)) {
            foreach (get_object_vars($data) as $key => $value) {

                if ($key == $this->identifierName) {
                    $this->resourceIdentifier = $value;
                }
                // is there some sort of filter method on this property?
                if (method_exists($this, $key)) {
                    $this->attributes[$key] = $this->{$key}($value);
                }
                else {
                    $this->attributes[$key] = $value;
                }
            }

            return true;
        }

        throw new ResourceException('Failed to hydrate. Invalid payload data format.');
    }


    /**
     * Find (GET) a specific resource by its ID (optional)
     *
     * This method assumes the payload contains a *SINGLE* resource instance. This method will call the
     * parseFind method on the Resource instance to know where to look in the payload to get the resource data.
     *
     * @param integer|string|null $id
     * @param array $queryParams
     * @param array $headers
     *
     * @throws ResponseException
     *
     * @return Resource|boolean
     */
    public static function find($id = null, array $queryParams = [], array $headers = [])
    {
        $className = get_called_class();

        /** @var self $instance */
        $instance = new $className;
        $instance->{$instance->getIdentifierName()} = $id;

        $response = $instance->getConnection()->get($instance->getResourceUri(), $queryParams, $headers);

        if ($response->isSuccessful()) {
            $instance->response = $response;
            $data = $instance->parseFind($response->getPayload());
            $instance->hydrate($data);
            return $instance;
        }

        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }

        return false;
    }

    /**
     * Get ALL resources
     *
     * This method assumes the payload contains an ARRAY of resource instances. This method will call the
     * parseAll method on the Resource instance to know where to look in the payload to get the array of resource data.
     *
     * @param array $queryParams
     * @param array $headers
     *
     * @throws ResponseException
     *
     * @return Collection|boolean
     */
    public static function all(array $queryParams = [], array $headers = [])
    {
        $className = get_called_class();
        /** @var self $instance */
        $instance = new $className;
        $response = $instance->getConnection()->get($instance->getResourceUri(), $queryParams, $headers);
        if ($response->isSuccessful()) {
            $data = $instance->parseAll($response->getPayload());
            return new Collection($className, $data, $response);
        }
        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }
        return false;
    }

    /**
     * Delete a resource
     *
     * @param $id
     * @param array $queryParams
     * @param array $headers
     *
     * @throws ResponseException
     *
     * @return bool
     */
    public static function delete($id, array $queryParams = [], array $headers = [])
    {
        $className = get_called_class();

        /** @var self $instance */
        $instance = new $className;
        $instance->{$instance->getIdentifierName()} = $id;

        $response = $instance->getConnection()->delete($instance->getResourceUri(), $queryParams, $headers);

        if ($response->isSuccessful()) {
            return true;
        }
        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }

        return false;
    }

    /**
     * Find a single instance *through* a dependent resource. It prepends the resource URI with the given dependent
     * resource URI. For example:
     *  API URI: [GET] /posts/1234/comments/5678
     *
     *  $comment = Comment::findThrough('posts/1234', 5678);
     *
     *  OR
     *
     * $post = Post::find(1234);
     * $comment = Comment::findThrough($post, 5678);
     *
     * @param Resource|string $resource
     * @param integer|string|null $id
     * @param array $queryParams
     * @param array $headers
     *
     * @throws ResponseException
     *
     * @return Resource|bool
     */
    public static function findThrough($resource, $id = null, array $queryParams = [], array $headers = [])
    {
        $className = get_called_class();

        /** @var self $instance */
        $instance = new $className;
        $instance->{$instance->getIdentifierName()} = $id;
        $instance->through($resource);

        $response = $instance->getConnection()->get($instance->getResourceUri(), $queryParams, $headers);

        if ($response->isSuccessful()) {
            $instance->response = $response;
            $data = $instance->parseFind($response->getPayload());
            $instance->hydrate($data);
            return $instance;
        }

        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }

        return false;
    }

    /**
     * Find all instances *through* a dependent resource. It prepends the resource URI with the given dependent
     * resource URI. For example:
     *
     *  API URI: [GET] /posts/1234/comments
     *
     *  $comments = Comment::allThrough('posts/1234');
     *
     *  OR
     *
     * $post = Post::find(1234);
     * $comments = Comment::allThrough($post);
     *
     * @param Resource|string $resource
     * @param array $queryParams
     * @param array $headers
     *
     * @throws ResponseException
     *
     * @return Collection|bool
     */
    public static function allThrough($resource, array $queryParams = [], array $headers = [])
    {
        $className = get_called_class();

        /** @var self $instance */
        $instance = new $className;
        $instance->through($resource);

        $response = $instance->getConnection()->get($instance->getResourceUri(), $queryParams, $headers);

        if ($response->isSuccessful()) {
            $data = $instance->parseAll($response->getPayload());
            return new Collection($className, $data, $response);
        }
        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }

        return false;
    }

    /**
     * Get the connection
     *
     * @return Connection
     */
    public static function connection(){

        $className = get_called_class();

        /** @var self $instance */
        $instance = new $className;

        return $instance->getConnection();
    }

    /**
     * Set credentials token.
     *
     * @param $name
     * @param $value
     */
    public static function setCredentialToken($name, $value)
    {
        if (array_key_exists($name, self::$creds)) {
            self::$creds[$name] = $value;
        }
    }

    /**
     * Gets credentials token.
     *
     * @return string
     */
    public static function getCredentialToken($name)
    {
        if (array_key_exists($name, self::$creds)) {
            return self::$creds[$name];
        }
        return null;
    }

    /**
     * Initilize HTTP connection object.
     */
    public static function init(array $options = [], $connectionName = 'default', $client = null)
    {
        if ($client){
            $connection = new Connection($options, $client);
        }else{
            $connection = new Connection($options);
        }

        ConnectionManager::add($connectionName, $connection);
    }

    /**
     * Gets a connection by its name.
     */
    public static function getConnectionByName($connectionName = 'default')
    {
        return ConnectionManager::get($connectionName);
    }
}
