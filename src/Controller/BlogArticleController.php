<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\BlogArticle;
use App\Enum\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BlogArticle",
 *     type="object",
 *     @OA\Property(property="authorId", type="integer"),
 *     @OA\Property(property="title", type="string", maxLength=100),
 *     @OA\Property(property="publicationDate", type="string", format="date-time"),
 *     @OA\Property(property="creationDate", type="string", format="date-time"),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="keywords", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="slug", type="string", maxLength=255),
 *     @OA\Property(property="coverPictureRef", type="string", maxLength=255),
 *     @OA\Property(property="status", type="string", enum={"draft", "published", "deleted"}),
 * )
 */
class BlogArticleController extends AbstractController
{

    /**
     * @OA\Get(
     *     path="/api/blog-articles",
     *     summary="List all blog articles",
     *     @OA\Response(response="200", description="A list of blog articles"),
     * )
     */
    #[Route('/blog-articles', name: 'list_blog_articles', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $articles = $em->getRepository(BlogArticle::class)->findAll();
        return $this->json($articles);
    }


    /**
     * @OA\Post(
     *     path="/api/blog-articles",
     *     summary="Create a new blog article",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BlogArticle")
     *     ),
     *     @OA\Response(response="201", description="Article created"),
     *     @OA\Response(response="400", description="Invalid input")
     * )
     */
    #[Route('/blog-articles', name: 'create_blog_article', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $blogArticle = new BlogArticle();

        $errors = $validator->validate($blogArticle);

        if (count($errors) > 0) {
            return new JsonResponse([
                'status' => 'error',
                'errors' => (string) $errors,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }


        $blogArticle->setAuthorId($data['authorId']);
        $blogArticle->setTitle($data['title']);
        $blogArticle->setPublicationDate(new \DateTime($data['publicationDate']));
        $blogArticle->setCreationDate(new \DateTime());
        $blogArticle->setContent($data['content']);
        $topKeywords = $this->topThreeWords($data['content'], ['the', 'and', 'or']);
        $blogArticle->setKeywords($topKeywords);
        $blogArticle->setSlug($data['slug']);
        $blogArticle->setStatus(Status::from($data['status']));

        if (isset($data['coverPictureRef'])) {
            $blogArticle->setCoverPictureRef($data['coverPictureRef']);
        }

        $em->persist($blogArticle);
        $em->flush();

        return new JsonResponse(['status' => 'Blog article created'], JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/blog-articles/{id}",
     *     summary="Get a single blog article",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Blog article found"),
     *     @OA\Response(response="404", description="Blog article not found")
     * )
     */
    #[Route('/blog-articles/{id}', name: 'get_blog_article', methods: ['GET'])]
    public function get(BlogArticle $blogArticle): JsonResponse
    {
        return $this->json($blogArticle);
    }

    /**
     * @OA\Patch(
     *     path="/api/blog-articles/{id}",
     *     summary="Update a blog article",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BlogArticle")
     *     ),
     *     @OA\Response(response="200", description="Blog article updated"),
     *     @OA\Response(response="404", description="Blog article not found")
     * )
     */
    #[Route('/blog-articles/{id}', name: 'update_blog_article', methods: ['PATCH'])]
    public function update(BlogArticle $blogArticle, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $blogArticle->setTitle($data['title']);
        }
        if (isset($data['content'])) {
            $topKeywords = $this->topThreeWords($data['content'], ['the', 'and', 'or']);
            $blogArticle->setKeywords($topKeywords);
            $blogArticle->setContent($data['content']);
        }
        if (isset($data['status'])) {
            $blogArticle->setStatus(Status::from($data['status']));
        }
        // if (isset($data['keywords'])) {
        //     $blogArticle->setKeywords($data['keywords']);
        // }

        $em->flush();

        return new JsonResponse(['status' => 'Blog article updated!']);
    }


    /**
     * @OA\Delete(
     *     path="/api/blog-articles/{id}",
     *     summary="Delete a blog article",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Blog article deleted"),
     *     @OA\Response(response="404", description="Blog article not found")
     * )
     */
    #[Route('/blog-articles/{id}', name: 'delete_blog_article', methods: ['DELETE'])]
    public function delete(BlogArticle $blogArticle, EntityManagerInterface $em): JsonResponse
    {
        $blogArticle->setStatus(Status::DELETED);
        $em->flush();

        return new JsonResponse(['status' => 'Blog article deleted!']);
    }


    private function topThreeWords(string $text, array $banned): array {
        $words = preg_split('/\W+/', strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
        $wordCount = [];
    
        foreach ($words as $word) {
            if (!in_array($word, $banned)) {
                $wordCount[$word] = ($wordCount[$word] ?? 0) + 1;
            }
        }
    
        arsort($wordCount);
        return array_slice(array_keys($wordCount), 0, 3);
    }

}
