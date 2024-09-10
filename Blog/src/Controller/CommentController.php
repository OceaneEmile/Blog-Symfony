<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    // **GET** `/api/comments` : Retrieve all comments.
    #[Route('/api/comments', name: 'get_comments', methods: ['GET'])]
    public function getAllComments(CommentRepository $commentRepo): JsonResponse
    {
        $comments = $commentRepo->findAll();
        return $this->json($comments, 200, [], ['groups' => 'comment:read']);
    }

    // **GET** `/api/comments/{id}` : Retrieve one specific comment with id.
    #[Route('/api/comment/{id}', name: 'get_comment', methods: ['GET'])]
    public function getCommentById(int $id, CommentRepository $commentRepo): JsonResponse
    {
        $comment = $commentRepo->find($id);
        if (!$comment) {
            return $this->json(['message' => 'Comment not found'], 404);
        }
        return $this->json($comment, 200, [], ['groups' => 'comment:read']);
    }

    // **POST** `/api/comments` : Add new comment
    #[Route('/api/comments', name: 'create_comment', methods: ['POST'])]
    public function createComment(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['content'], $data['author'], $data['article_id'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $articleRepo = $em->getRepository(Article::class);
        $article = $articleRepo->find($data['article_id']);
        if (!$article) {
            return $this->json(['message' => 'Article not found'], 404);
        }

        $comment = new Comment();
        $comment->setContent($data['content']);
        $comment->setAuthor($data['author']);
        $comment->setArticle($article);
        $comment->setCreatedAt(new \DateTime());

        $em->persist($comment);
        $em->flush();

        return $this->json($comment, 201, [], ['groups' => 'comment:read']);
    }

    // **PUT** `/api/comments/{id}` : Update a comment
    #[Route('/api/comments/{id}', name: 'update_comment', methods: ['PUT'])]
    public function updateComment(int $id, Request $request, CommentRepository $commentRepo, EntityManagerInterface $em): JsonResponse
    {
        $comment = $commentRepo->find($id);
        if (!$comment) {
            return $this->json(['message' => 'Comment not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $comment->setContent($data['content'] ?? $comment->getContent());
        $comment->setAuthor($data['author'] ?? $comment->getAuthor());

        $em->flush();

        return $this->json($comment, 200, [], ['groups' => 'comment:read']);
    }

    // **DELETE** `/api/comments/{id}` : Delete a comment
    #[Route('/api/comments/{id}', name: 'delete_comment', methods: ['DELETE'])]
    public function deleteComment(int $id, CommentRepository $commentRepo, EntityManagerInterface $em): JsonResponse
    {
        $comment = $commentRepo->find($id);
        if (!$comment) {
            return $this->json(['message' => 'Comment not found'], 404);
        }

        $em->remove($comment);
        $em->flush();

        return $this->json(['message' => 'Comment deleted successfully']);
    }

    // **GET** `/api/articles/{articleId}/comments` : Retrieve comments by article.
    #[Route('/api/articles/{articleId}/comments', name: 'get_comments_by_article', methods: ['GET'])]
    public function getCommentsByArticle(int $articleId, ArticleRepository $articleRepo, CommentRepository $commentRepo): JsonResponse
    {
        $article = $articleRepo->find($articleId);
        if (!$article) {
            return $this->json(['message' => 'Article not found'], 404);
        }

        $comments = $commentRepo->findBy(['article' => $article]);
        return $this->json($comments, 200, [], ['groups' => 'comment:read']);
    }
}

