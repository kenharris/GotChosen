namespace Ken\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/blog")
 */
class BlogController
{
   /**
    * @Route("/")
    */
    public function indexAction()
    {
        return $this->render(
            'AcmeHelloBundle:Blog:index.html.twig',
            array('name' => $name)
        );
    }
}
