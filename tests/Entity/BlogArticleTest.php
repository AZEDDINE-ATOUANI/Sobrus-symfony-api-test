<?php

namespace App\Tests\Entity;

use App\Entity\BlogArticle;
use App\Enum\Status;
use PHPUnit\Framework\TestCase;

class BlogArticleTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $article = new BlogArticle();

        $article->setAuthorId(1);
        $this->assertSame(1, $article->getAuthorId());

        $article->setTitle('Test Title');
        $this->assertSame('Test Title', $article->getTitle());

        $publicationDate = new \DateTime();
        $article->setPublicationDate($publicationDate);
        $this->assertSame($publicationDate, $article->getPublicationDate());

        $creationDate = new \DateTime();
        $article->setCreationDate($creationDate);
        $this->assertSame($creationDate, $article->getCreationDate());

        $article->setContent('Test Content');
        $this->assertSame('Test Content', $article->getContent());

        $keywords = ['test', 'blog'];
        $article->setKeywords($keywords);
        $this->assertSame($keywords, $article->getKeywords());

        $article->setSlug('test-title');
        $this->assertSame('test-title', $article->getSlug());

        $article->setCoverPictureRef('path/to/picture.jpg');
        $this->assertSame('path/to/picture.jpg', $article->getCoverPictureRef());

        $article->setStatus(Status::DRAFT);
        $this->assertSame(Status::DRAFT, $article->getStatus());
    }
}
