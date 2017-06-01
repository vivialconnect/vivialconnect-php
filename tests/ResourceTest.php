<?php

// to run test:  php phpunit.phar  --bootstrap vendor/autoload.php  tests/ResourceTest.php
// make sure compser.json is updated to include phpunit
use VivialConnect\Resources\Resource;
use VivialConnect\Resources\Message;
use VivialConnect\Resources\Attachment;
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
 * @covers Message / Resource
 */


final class ResourceTest extends PHPUnit_Framework_TestCase
{


    public static function setUpBeforeClass()
    {
      global $find_json;
      global $send_json;
      global $destroy_json;
      global $all_json;
      global $attachment_json;
      global $attachments_json;

      $mock = new MockHandler([
          new Response(200, ['X-Foo' => 'Bar'], $find_json),
          new Response(400, ['X-Foo' => 'Bar'], "error message"),
          new Response(200, ['X-Foo' => 'Bar'], $send_json),
          new Response(400, ['X-Foo' => 'Bar'], "error message"),
          new Response(200, ['X-Foo' => 'Bar'], $find_json),
          new Response(200, ['X-Foo' => 'Bar'], $destroy_json),
          new Response(200, ['X-Foo' => 'Bar'], $all_json),
          new Response(400, ['X-Foo' => 'Bar'], "error message"),
          new Response(200, ['X-Foo' => 'Bar'], $find_json),
          new Response(200, ['X-Foo' => 'Bar'], $attachment_json),
          new Response(200, ['X-Foo' => 'Bar'], $find_json),
          new Response(400, ['X-Foo' => 'Bar'], "error message"),
          new Response(200, ['X-Foo' => 'Bar'], $find_json),
          new Response(200, ['X-Foo' => 'Bar'], $attachments_json),
          new Response(200, ['X-Foo' => 'Bar'], $find_json),
          new Response(400, ['X-Foo' => 'Bar'], "error message"),
          new RequestException("Error Communicating with Server", new Request('GET', 'test'))
      ]);

      $handler = HandlerStack::create($mock);

      $mock_client = new Client(['handler' => $handler]);

      Resource::setCredentialToken(Resource::API_KEY, "FAKEKEY");
      Resource::setCredentialToken(Resource::API_SECRET, "FAKEsEcRet");
      Resource::setCredentialToken(Resource::API_ACCOUNT_ID, "09827");
      Resource::init([], 'default', $mock_client);
        
    }


    public function testMessageFind()
    {

        // happy path
        $message = Message::find(22075);

        $this->assertEquals(
          22075,
          $message->id
        );

        $this->assertEquals(
          '+14132134918',
          $message->from_number
        );

        $this->assertEquals(
          '+16084218409',
          $message->to_number
        );

        $this->assertEquals(
          0,
          $message->num_media
        );


        // exception path 
        $message = Message::find(22075);

        $this->assertNotEquals(
          22075,
          $message->id
        );

        // not object boolean false
        $this->assertEquals(
          0,
          $message
        );

  
        
      }

    public function testMessageSend()
    {

      //happy path
      $message = new Message;
      $message->body = "test message";
      $message->from_number = "+14132134918";
      $message->to_number = "16084218409";
      $message->send();


      $this->assertEquals(
        "2017-06-01T15:23:30+00:00",
        $message->date_created
      );


      $this->assertEquals(
        22123,
        $message->id
      );

      // exception path -- in this case the object is not updated with response information
      $message = new Message;
      $message->body = "test message";
      $message->from_number = "+14132134918";
      $message->to_number = "16084218409";
      $message->send();

      $this->assertNotEquals(
        22123,
        $message->id
      );

      $this->assertNotEquals(
        "2017-06-01T15:23:30+00:00",
        $message->date_created
      );

    }

    public function testMessageDestroy()
    {

      // This doesn't test anything now because the object is not affected by deletion
      // and our API only returns 200 and "{}" json on delete.
      // Leaving it as a stub in case that changes. Also, you can't delete messages.
      $message = Message::find(22075);
      $message->destroy();

    }

    public function testMessageAll()
    {

      // happy path
      $messages = Message::all();

      $this->assertEquals(
        6,
        $messages->count()
      );

      $this->assertEquals(
        'VivialConnect\Resources\Collection',
        get_class($messages)
      );


      // exception path
      $messages = Message::all();

      // not object boolean false
      $this->assertEquals(
        0,
        $message
      );

    }

    public function testFindThrough()
    {
      // happy path
      $message = Message::find(22075);

      $attachment = Attachment::findThrough($message, 264);

      $this->assertEquals(
        264,
        $attachment->id
      );

      $this->assertEquals(
        "image/jpeg",
        $attachment->content_type
      );

      $this->assertEquals(
        "jea-0007-mn-united-vs-bournemouth.jpg",
        $attachment->file_name
      );

      // exception path
      $message = Message::find(22075);

      $attachment = Attachment::findThrough($message, 264);

      // not object boolean false
      $this->assertEquals(
        0,
        $attachment
      );

    }

    public function testAllThrough()
    {
      // happy path 
      $message = Message::find(22075);
      $attachments = Attachment::allThrough($message);

      $this->assertEquals(
        3,
        $attachments->count()
      );

      $this->assertEquals(
        'VivialConnect\Resources\Collection',
        get_class($attachments)
      );

      // exception path
      $message = Message::find(22075);
      $attachments = Attachment::allThrough($message);

      // not object boolean false
      $this->assertEquals(
        0,
        $attachments
      );
    }

    
}