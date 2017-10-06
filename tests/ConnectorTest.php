<?php

// to run test:  php phpunit.phar  --bootstrap vendor/autoload.php  tests/ConnectorTest.php
// make sure compser.json is updated to include phpunit
use VivialConnect\Resources\Resource;
use VivialConnect\Resources\Connector;
use VivialConnect\Transport\ConnectionManager;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;

require_once 'TestJson.php';

/**
 * @covers Connector
 */

final class ConnectorTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
      global $findJson;
      global $sendJson;
      global $destroyJson;
      global $allJson;
      global $attachmentJson;
      global $attachmentsJson;
      global $connectorCreate;
      global $connectorAddNumber;
      global $connectorDeleteNumber;
      global $connectorAddNumbers;
      global $connectorAddCallback;
      global $connectorDeleteCallback;
      global $connectorAddCallbacks;

      $mock = new MockHandler([
          new Response(200, ['X-Foo' => 'Bar'], $connectorCreate),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], $connectorAddNumber),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], $connectorDeleteNumber),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], $connectorAddNumbers),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], $connectorDeleteNumber),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], $connectorAddCallback),
          new Response(200, ['X-Foo' => 'Bar'], $connectorDeleteCallback),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], $connectorAddCallbacks),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], ""),
          new Response(200, ['X-Foo' => 'Bar'], $connectorDeleteCallback),
      ]);

      $handler = HandlerStack::create($mock);

      $mock_client = new Client(['handler' => $handler]);

      Resource::setCredentialToken(Resource::API_KEY, "FAKEKEY");
      Resource::setCredentialToken(Resource::API_SECRET, "FAKEsEcRet");
      Resource::setCredentialToken(Resource::API_ACCOUNT_ID, "09827");
      Resource::init([], 'default', $mock_client);
    }

    public function testConnectorCreate()
    {
        $connector = new Connector;
        $connector->name = "My First Connector";
        $connector->save();

        $this->assertEquals(
          "My First Connector",
          $connector->name
        );

        $this->assertEquals(
          230,
          $connector->id
        );

        $this->assertEquals(
          [],
          $connector->phone_numbers
        );

        $this->assertEquals(
          [],
          $connector->callbacks
        );

        $this->assertEquals(
          true,
          $connector->active
        );

        $this->assertEquals(
          false,
          $connector->more_numbers
        );
      }

      public function testConnectoraddNumber()
      {
        $connector = new Connector;
        $connector->addNumber("+15555555550", 1);

        $this->assertEquals(
          "+15555555550",
          $connector->phone_numbers[0]->phone_number
        );

        $this->assertEquals(
          "1",
          $connector->phone_numbers[0]->phone_number_id
        );

      }

      public function testConnectordeleteNumber()
      {
        $connector = new Connector;
        $connector->deleteNumber("+15555555550", 1);

        $this->assertEquals(
          0,
          count($connector->phone_numbers)
        );
      }

      public function testConnectorAddNumbers()
      {
        $connector = new Connector;
        $connector->addNumbers([["+15555555550", 1], ["+15555555551", 2]]);
        

        $this->assertEquals(
          "+15555555550",
          $connector->phone_numbers[0]->phone_number
        );

        $this->assertEquals(
          "1",
          $connector->phone_numbers[0]->phone_number_id
        );


        $this->assertEquals(
          "+15555555551",
          $connector->phone_numbers[1]->phone_number
        );

        $this->assertEquals(
          "2",
          $connector->phone_numbers[1]->phone_number_id
        );
      }

      public function testConnectorDeleteNumbers()
      {
        $connector = new Connector;
        $connector->deleteNumbers([["+15555555550", 1], ["+15555555551", 2]]);

        $this->assertEquals(
          0,
          count($connector->phone_numbers)
        );

      }

      public function testConnectoraddCallback()
      {
        $connector = new Connector;
        $connector->addCallback("incoming", "text", "path/to/sms/callback1", "POST");

        $this->assertEquals(
          "incoming",
          $connector->callbacks[0]->event_type
        );

        $this->assertEquals(
          "text",
          $connector->callbacks[0]->message_type
        );

        $this->assertEquals(
          "path/to/sms/callback1",
          $connector->callbacks[0]->url
        );

        $this->assertEquals(
          "POST",
          $connector->callbacks[0]->method
        );
      }

      public function testConnectordeleteCallback()
      {
        $connector = new Connector;
        $connector->deleteCallback("incoming", "text", "path/to/sms/callback1", "POST");

        $this->assertEquals(
          0,
          count($connector->callbacks)
        );

      }

      public function testConnectorAddCallbacks()
      {
        $connector = new Connector;
        $connector->addCallbacks([["status", "voice", "http://www.mydomain.com/callback2", "POST"], ["status", "text", "path/to/sms/status1", "GET"]]);
        
        $this->assertEquals(
          "incoming",
          $connector->callbacks[0]->event_type
        );

        $this->assertEquals(
          "text",
          $connector->callbacks[0]->message_type
        );

        $this->assertEquals(
          "path/to/sms/callback1",
          $connector->callbacks[0]->url
        );

        $this->assertEquals(
          "POST",
          $connector->callbacks[0]->method
        );

        $this->assertEquals(
          "status",
          $connector->callbacks[1]->event_type
        );

        $this->assertEquals(
          "text",
          $connector->callbacks[1]->message_type
        );

        $this->assertEquals(
          "path/to/sms/status1",
          $connector->callbacks[1]->url
        );

        $this->assertEquals(
          "GET",
          $connector->callbacks[1]->method
        );

      }

      public function testConnectorDeleteCallbacks()
      {
        $connector = new Connector;
        $connector->addCallbacks([["status", "voice", "http://www.mydomain.com/callback2", "POST"], ["status", "text", "path/to/sms/status1", "GET"]]);

        $this->assertEquals(
          0,
          count($connector->phone_numbers)
        );

      }
}