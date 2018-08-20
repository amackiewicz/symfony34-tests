<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;

class PostController extends FOSRestController
{
    /**
     * @Rest\Get("api/posts")
     */
    public function fetchPostsListingAction()
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->fetchPosts();
        return ['posts' => $posts];
    }

    /**
     * @Rest\Get("api/posts/{postId}")
     */
    public function fetchPostDetails(string $postId)
    {
        $Post = $this->fetchOnePost($postId);
        if ($Post === null) {
            return JsonResponse::create(null, Response::HTTP_NOT_FOUND);
        }

        return ['post' => $Post];
    }

    /**
     * @Rest\Post("api/posts")
     */
    public function addPost(Request $Request)
    {
        $RequestBody = json_decode($Request->getContent());

        $errors = [];
        if (empty($RequestBody->text)) {
            $errors[] = '"text" param cannot be empty'; /// probably need to implement some error codes here
        }
        if (!empty($errors)) {
            return JsonResponse::create(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $Post = new Post();
        $Post->setText($RequestBody->text ?? '');

        $Doctrine = $this->getDoctrine()->getManager();
        $Doctrine->persist($Post);
        $Doctrine->flush();

        return ['post' => ['id' => $Post->getId()]];
    }

    /**
     * @Rest\Put("api/posts/{postId}")
     */
    public function changePost(
        string $postId,
        Request $Request
    ) {
        $Post = $this->fetchOnePost($postId);
        if ($Post === null) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        $Body = json_decode($Request->getContent());

        if (isset($Body->text)) {
            $Post->setText($Body->text);
        }

        $Doctrine = $this->getDoctrine()->getManager();
        $Doctrine->flush();
    }

    /**
     * @Rest\Post("api/posts/{postId}/comments")
     */
    public function addComment(
        string $postId,
        Request $Request
    )
    {
        $Post = $this->fetchOnePost($postId);
        if ($Post === null) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $RequestBody = json_decode($Request->getContent());
        $errors = [];
        if (empty($RequestBody->text)) {
            $errors[] = '"text" param cannot be empty'; /// probably need to implement some error codes here
        }
        if (empty($RequestBody->author_nickname)) {
            $errors[] = '"author_nickname" param cannot be empty'; /// probably need to implement some error codes here
        }
        if (!empty($errors)) {
            return JsonResponse::create(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $Comment = new Comment();
        $Comment->setText($RequestBody->text ?? '');
        $Comment->setAuthorNickname($RequestBody->author_nickname ?? '');
        $Comment->setPost($Post);

        $Doctrine = $this->getDoctrine()->getManager();
        $Doctrine->persist($Comment);
        $Doctrine->flush();

        return ['comment' => ['id' => $Comment->getId()]];
    }

    /**
     * @Rest\GET("api/posts/{postId}/comments")
     */
    public function fetchPostComments(Request $Request)
    {
        $Post = $this->fetchOnePost($Request->get('postId'));
        if ($Post === null) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $comments = $Post->getComments();
        return ['comments' => $comments];
    }

    private function fetchOnePost(
        string $postId
    ): ?Post
    {
        return $this->getDoctrine()->getRepository('AppBundle:Post')->find($postId);
    }


}
