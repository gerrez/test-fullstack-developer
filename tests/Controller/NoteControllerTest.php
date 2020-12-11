<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NoteControllerTest extends WebTestCase
{
    public function testGetNotes()
    {
        $client = static::createClient();

        $client->request('GET', '/api/notes');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateAndUpdateNote()
    {
        $client = static::createClient();

        $client->request('POST', '/api/notes', [], [], ['CONTENT_TYPE' => 'application/json'], '{"title": "Title note Test", "body":"Test creation note"}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertContains('title', $client->getResponse()->getContent());

        $createResponse = json_decode($client->getResponse()->getContent());
        $client->request('PUT', "/api/notes/$createResponse->id", [], [], ['CONTENT_TYPE' => 'application/json'], '{"title": "Title note Test updated", "body":"Test update note"}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $client->request('POST', '/api/notes', [], [], ['CONTENT_TYPE' => 'application/json'], '{"body":"Test creation note"}');
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testDeleteNote()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/notes/1', [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}