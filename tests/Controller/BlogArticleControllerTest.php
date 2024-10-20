<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BlogArticleControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testCreateBlogArticle(): void
    {
        $this->client->request('POST', '/api/blog-articles', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'authorId' => 1,
            'title' => 'Test Article',
            'publicationDate' => '2024-10-20T00:00:00Z',
            'creationDate' => '2024-10-20T00:00:00Z',
            'content' => 'This is a test article.',
            'keywords' => ['test', 'article'],
            'slug' => 'test-article',
            'status' => 'draft',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetBlogArticles(): void
    {
        $this->client->request('GET', '/api/blog-articles');

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetSingleBlogArticle(): void
    {
        // Assuming an article with ID 1 exists
        $this->client->request('GET', '/api/blog-articles/1');

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testUpdateBlogArticle(): void
    {
        // Assuming an article with ID 1 exists
        $this->client->request('PATCH', '/api/blog-articles/1', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Updated Test Article',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testDeleteBlogArticle(): void
    {
        // Assuming an article with ID 1 exists
        $this->client->request('DELETE', '/api/blog-articles/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
