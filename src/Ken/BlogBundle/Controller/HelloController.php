namespace Ken\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class HelloController
{
    public function indexAction($name)
    {
        return new Response('<html><body>Howdy '.$name.'!</body></html>');
    }
}
