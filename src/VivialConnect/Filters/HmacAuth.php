<?php

namespace VivialConnect\Filters;

use Optimus\Onion\LayerInterface;
use VivialConnect\Resources\Resource;


class HmacAuth implements LayerInterface
{
    const AUTH_PREFIX = 'HMAC';
    const X_AUTH_DATE = "Ymd\THis\Z";
    const HTTP_DATE_FORMAT = 'D, d M Y H:i:s T';

    static function computeSignature($endpointUrl,
        $httpMethod,
        array $headers = NULL,
        array $queryParameters = NULL,
        $body,
        $apiKey,
        $apiSecret,
        $requestTimeStamp)
    {
        $body = $body == NULL ? '' : $body;
        $bodyHash = hash('sha256', $body);

        $canonHeaderNames = HmacAuth::getCanonicalizeHeaderNames($headers);
        $canonHeaders = HmacAuth::getCanonicalizedHeaderString($headers);
        $canonQueryParameters = HmacAuth::getCanonicalizedQueryString($queryParameters);
        $canonicalRequest = HmacAuth::getCanonicalRequest(
            $endpointUrl, $httpMethod,
            $canonQueryParameters, $canonHeaderNames,
            $canonHeaders, $bodyHash, $requestTimeStamp);

        $signature = HmacAuth::sign($canonicalRequest, $apiSecret, 'sha256');
        return HmacAuth::AUTH_PREFIX . ' ' . $apiKey . ':' . $signature;
    }

    static function getTimeStamp($datenow) {
        return $datenow->format(HMACAuth::X_AUTH_DATE);
    }

    static function getCanonicalizeHeaderNames(array $headers) {
        if (empty($headers)) {
            return '';
        }
        $sortedHeaders = array_map('strtolower', array_keys($headers));
        sort($sortedHeaders);
        return implode(';', $sortedHeaders);
    }

    static function getCanonicalizedHeaderString(array $headers) {
        if (empty($headers)) {
            return '';
        }
        // step1: sort the headers by case-insensitive order
        $sortedHeaders = array_keys($headers);
        sort($sortedHeaders);
        // step2: form the canonical header:value entries in sorted order. 
        // Don't add linebreak to last header
        $result = [];
        foreach ($sortedHeaders as &$key) {
            array_push($result, strtolower($key) . ':' . $headers[$key]);
        }
        return implode("\n", $result);
    }

    static function getCanonicalRequest($endpoint,
        $httpMethod,
        $canonQueryParameters,
        $canonHeaderNames,
        $canonHeaders,
        $bodyHash,
        $requestTimeStamp)
    {
        return $httpMethod . "\n" .
            $requestTimeStamp . "\n" .
            HmacAuth::getCanonicalizedResourcePath($endpoint) . "\n" .
            $canonQueryParameters . "\n" .
            $canonHeaders . "\n" .
            $canonHeaderNames . "\n" .
            $bodyHash;
    }

    static function getCanonicalizedResourcePath($endpoint)
    {
        if (empty($endpoint)) {
            return '/';
        }

        $path = parse_url($endpoint, PHP_URL_PATH);
        if (empty($path)) {
            return '/';
        }
        
        $encodedPath = implode('/', array_map('rawurlencode', explode('/', $path)));
        if (strpos($encodedPath, '/') === 0) {
            return $encodedPath;
        } else {
            return '/' . $encodedPath;
        }
    }

    static function getCanonicalizedQueryString(array $parameters = NULL)
    {
        if (empty($parameters)) {
            return '';
        }

        // step1: sorted parameter keys
        $sortedKeys = array_keys($parameters);
        sort($sortedKeys);

        $result = [];
        foreach ($sortedKeys as &$key) {
            array_push($result, rawurlencode($key) . '=' . rawurlencode($parameters[$key]));
        }

        return implode('&', $result);
    }

    static function sign($stringData, $secret, $algorithm)
    {
        return hash_hmac($algorithm, utf8_encode($stringData), $secret, false);
    }

    /**
    *
    *  @param \VivialConnect\Resources\Request $object
    */
    public function peel($object, \Closure $next)
    {
        date_default_timezone_set('GMT');
        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone('GMT'));
        $timestamp = HmacAuth::getTimeStamp($now);

        $headers = [];
        $headers['Date'] = $now->format(HmacAuth::HTTP_DATE_FORMAT);
        $headers['Accept'] = 'application/json';

        $authorization = HmacAuth::computeSignature($object->getUrl(), 
                $object->getMethod(),
                $headers,
                $object->getQueries(), // Query parameters
                $object->getBody(),
                Resource::getCredentialToken(Resource::API_KEY),
                Resource::getCredentialToken(Resource::API_SECRET),
                $timestamp);

        $authHeaders = HmacAuth::getCanonicalizeHeaderNames($headers);

        // Add the HMAC headers
        $object->setHeader('X-Auth-SignedHeaders', $authHeaders);
        $object->setHeader('X-Auth-Date', $timestamp);
        $object->setHeader('Authorization', $authorization);
        foreach ($headers as $key => $value) {
            $object->setHeader($key, $value);
        } 

        // Send the request off to the next layer
        $response = $next($object);

        // Return the response
        return $response;
    }
}
