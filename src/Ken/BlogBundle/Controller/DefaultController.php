<?php

namespace Ken\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Ken\BlogBundle\Entity\Post;
use Ken\BlogBundle\Entity\Author;
use Ken\BlogBundle\Entity\Tag;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use JMS\Serializer\SerializerBuilder as SerializerBuilder;

/**
 * @Route("/")
 * @Cache(expires="tomorrow")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
       $em = $this->getDoctrine()->getManager();

       $query = $this->getDoctrine()
           ->getRepository('KenBlogBundle:Post')
           ->createQueryBuilder('p')
           ->orderBy('p.date', 'DESC')
           ->getQuery();
       $posts = $query->getResult();

       $query = $this->getDoctrine()
           ->getRepository('KenBlogBundle:Author')
	   ->createQueryBuilder('a')
	   ->orderBy('a.name')
	   ->getQuery();
       $authors = $query->getResult();

       $query = $this->getDoctrine()
           ->getRepository('KenBlogBundle:Tag')
	   ->createQueryBuilder('t')
	   ->orderBy('t.tag')
	   ->getQuery();
       $tags = $query->getResult();

        return $this->render('KenBlogBundle:Default:index.html.twig', array('posts' => $posts, 'authors' => $authors, 'tags' => $tags));
    }

    /**
     * @Route("/author/add")
     */
    public function addAuthorAction()
    {
       $request = $this->get('request');
       if ($request->getMethod() == 'POST')
       {
          $name = $request->request->get('name');
	  $author = new Author();
	  $author->setName($name);
	  $author->setCreated(new \DateTime("now"));

	  $em = $this->getDoctrine()->getManager();
	  $em->persist($author);
	  $em->flush();

          return $this->redirect('/');
       }
    }

    /**
     * @Route("/tag/add")
     */
    public function addTagAction()
    {
       $request = $this->get('request');
       if ($request->getMethod() == 'POST')
       {
          $content = $request->request->get('tag');
          $tag = new Tag();
          $tag->setTag($content);

          $em = $this->getDoctrine()->getManager();
          $em->persist($tag);
          $em->flush();

          return $this->redirect('/');
       }
    }

    /**
     * @Route("/post/add")
     */
    public function addPostAction()
    {
       $request = $this->get('request');
       if ($request->getMethod() == 'POST')
       {
          $title     = $request->request->get('title');
          $content   = $request->request->get('content');
          $author_id = $request->request->get('author');
          $tag_ids   = $request->request->get('tags');

          $query = $this->getDoctrine()
              ->getRepository('KenBlogBundle:Tag')
              ->createQueryBuilder('t')
	      ->where('t.id IN (' . implode(',', $tag_ids) . ')' )
              ->orderBy('t.tag', 'DESC')
              ->getQuery();
          $tags = new ArrayCollection($query->getResult());

          $author = $this->getDoctrine()
              ->getRepository('KenBlogBundle:Author')
	      ->find($author_id);

          $post = new Post();
          $post->setTitle($title);
          $post->setContent($content);
	  $post->setDate(new \DateTime("now"));
	  $post->setAuthor($author);
	  $post->setTags($tags);

          $em = $this->getDoctrine()->getManager();
          $em->persist($post);
          $em->flush();

          return $this->redirect('/');
       }
    }

    /**
     * @Route("/ajax/posts")
     */
    public function ajaxPostsAction()
    {
       $query = $this->getDoctrine()
           ->getRepository('KenBlogBundle:Post')
           ->createQueryBuilder('p')
           ->orderBy('p.date', 'DESC')
           ->getQuery();
       $posts = $query->getResult();

       $serializer = SerializerBuilder::create()->build();
       $json = $serializer->serialize($posts, 'json');
       return new JsonResponse($json);
    }
}
