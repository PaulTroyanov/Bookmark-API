<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Bookmark;
use AppBundle\Entity\Comment;

/**
 * This is API controller which contains methods to work with Bookmarks.
 *
 * Class BookmarkController
 * @package AppBundle\Controller
 */
class BookmarkController extends FOSRestController
{
    /**
     * This action get 10 latest inserted bookmarks
     *
     * @Rest\Get("/get/bookmark")
     * @return View | array
     */
    public function getBookmarksAction()
    {
        $result = [];
        $bookmarks = $this->getDoctrine()->getRepository('AppBundle:Bookmark')->findBy([], ["id" => "DESC"], 10);

        if (!$bookmarks) {
            return new View("there are no bookmarks exist", Response::HTTP_NOT_FOUND);
        }

        foreach ($bookmarks as $bookmark) {
            $result[] = [
                "id" => $bookmark->getId(),
                "created_at" => $bookmark->getCreatedAt(),
                "url" => $bookmark->getUrl()
            ];

        }

        return $result;
    }

    /**
     * This action insert bookmark to database.
     * If specified bookmark is exists nothing happens
     *
     * @Rest\Post("/insert/bookmark/{url}")
     * @return View | array
     */
    public function insertBookmarkByUrlAction($url)
    {
        if(empty($url))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }

        $doctrine = $this->getDoctrine();
        $bookmark = $doctrine->getRepository('AppBundle:Bookmark')->findBy(['url' => $url]);

        if (!$bookmark) {
            $bookmark = new Bookmark();
            $bookmark->setUrl($url);

            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();
            $em->persist($bookmark);
            $em->flush();
        }

        return ["id" => $bookmark->getId()];
    }

    /**
     * This action get bookmark by specified url
     * with comments to this bookmark
     *
     * @Rest\Get("/get/bookmark/{url}")
     * @return View | array
     */
    public function getBookmarkByUrlAction($url)
    {
        $bookmark = $this->getDoctrine()->getRepository('AppBundle:Bookmark')->findOneBy(["url" => $url]);
        if (!$bookmark) {
            return new View("there are no bookmarks exist", Response::HTTP_NOT_FOUND);
        }

        $result = [
            "id" => $bookmark->getId(),
            "created_at" => $bookmark->getCreatedAt(),
            "url" => $bookmark->getUrl(),
            "comments" => []
        ];

        $comments = $bookmark->getComments();

        foreach ($comments as $comment) {
            $result['comments'][] = [
                'id' => $comment->getId(),
                'created_at' => $comment->getCreatedAt(),
                'ip' => $comment->getIp(),
                'text' => $comment->getText()
            ];
        }

        return $result;
    }

    /**
     * This action insert comment to specified bookmark
     *
     * @Rest\Post("/insert/bookmark/{id}/comment/{text}")
     * @return View | array
     */
    public function insertCommentByBookmarkIdAction($id, $text, Request $request)
    {
        if(empty($id) || empty($text))
        {
            return new View("null values are not allowed", Response::HTTP_NOT_ACCEPTABLE);
        }

        $doctrine = $this->getDoctrine();
        $bookmark = $doctrine->getRepository('AppBundle:Bookmark')->find($id);

        if (!$bookmark) {
            return new View("there is no bookmark exist with specified id", Response::HTTP_NOT_FOUND);
        }

        $comment = new Comment();

        $comment->setText($text);
        $comment->setBookmarkId($id);
        $comment->setBookmark($bookmark);
        $comment->setIp($request->getClientIp());

        $em = $doctrine->getManager();
        $em->persist($comment);
        $em->flush();

        return ["id" => $comment->getId()];
    }
}