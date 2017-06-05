<?php

namespace VivialConnect\Resources;

/**
* Abstract class that the other resource classes inherit from
*/

abstract class Resource
{
    /**
    * 
    * API secret token.
    *
    * Type: string
    * Default: null
    */
    const API_SECRET = 'apiSecret';

    /**
    *
    * API key token.
    *
    * Type: string
    * Default: null
    */
    const API_KEY = 'apiKey';

    /**
    * 
    * API account ID.
    *
    * Type: string
    * Default: null
    */
    const API_ACCOUNT_ID = 'apiAccountId';

    /**
    * Initilize HTTP connection object.
    *
    * Resource::setCredentialToken(Resource::API_KEY, "ApIkEy"); <br>
    * Resource::setCredentialToken(Resource::API_SECRET, "ApISeCrEt"); <br>
    * Resource::setCredentialToken(Resource::API_ACCOUNT_ID, "1"); <br>
    *
    * Resource::init();
    *
    * @param array $options
    * @param string|null $connectionName
    * @package GuzzleHttp\Client $client
    */
    public static function init(array $options = [], $connectionName = 'default', $client = null)
    {}

    /**
    * Set credentials token.
    * 
    * Resource::setCredentialToken(Resource::API_KEY, "ApIkEy");
    *
    * @param $name
    * @param $value
    */
    public static function setCredentialToken($name, $value)
    {}

    /**
    * Save or Update the entity
    *
    * resource.save(); 
    *
    * @param array $queryParams
    * @param array $headers
    *
    * @return bool
    */
    public function save(array $queryParams = [], array $headers = [])
    {}

    /**
    * Find (GET) a specific resource by its ID (optional)
    *
    * This method assumes the payload contains a *SINGLE* resource instance. This method will call the
    * parseFind method on the Resource instance to know where to look in the payload to get the resource data.
    *
    * Message::find(1);
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
    {}

    /**
    * Get ALL resources
    *
    * This method assumes the payload contains an ARRAY of resource instances. This method will call the
    * parseAll method on the Resource instance to know where to look in the payload to get the array of resource data.
    *
    * Optional parameters
    *
    *
    * page  | Page number within the returned list of text messages. Default value: 1.
    *
    * limit | Number of results to return per page. Default value: 50.
    *
    * $qs = ['page' => 1, 'limit' => 20]
    *
    * Messsage::all(); 
    *
    * @param array $queryParams
    * @param array $headers
    *
    * @throws ResponseException
    *
    * @return Collection|boolean
    */
    public static function all(array $queryParams = [], array $headers = [])
    {}

    /**
    * Destroy (delete) the resource
    *
    * attachment->destroy();
    *
    * @param array $queryParams
    * @param array $headers
    *
    * @return bool
    */
    public function destroy(array $queryParams = [], array $headers = [])
    {}

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
    {}

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
    * $post = Post::find(1234);<br>
    * $comments = Comment::allThrough($post);<br>
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
    {}
}