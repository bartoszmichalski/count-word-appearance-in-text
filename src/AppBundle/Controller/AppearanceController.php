<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Appearance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Appearance controller.
 *
 * @Route("appearance")
 */
class AppearanceController extends Controller
{
    /**
     * Lists all appearance entities.
     *
     * @Route("/", name="appearance_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $appearances = $em->getRepository('AppBundle:Appearance')->findBy([], ['count' => 'DESC']);

        return $this->render('appearance/index.html.twig', array(
            'appearances' => $appearances,
        ));
    }

    /**
     * Creates a new appearance entity.
     *
     * @Route("/new", name="appearance_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {        
        $text = file_get_contents('http://www.wkrakowie.pl/tekstownik.txt');        
        $words = explode(' ', $this->replaceOtherCharacters($text));               
        $wordsAppearanaceList = $this->countAppearances($words);

        foreach ($wordsAppearanaceList as $key => $value) {
            $appearance = new Appearance($key, $value);
            $em = $this->getDoctrine()->getManager();
            $em->persist($appearance);
            $em->flush();             
        }
        
        return $this->redirectToRoute('appearance_index');
    }
    
    public function countAppearances($words)
    {
        $length = count($words);
        $wordsCounted = [];
        for ($i = 0; $i < $length; $i++){
            $word = trim(strtolower($words[$i]));
            if ($word != ''){
                if (isset($wordsCounted[$word])) {
                    $wordsCounted[$word]++;
                } else {
                    $wordsCounted[$word] = 1;
                }
            }
        }
        
        return $wordsCounted;
    }
    
    public function replaceOtherCharacters($text)
    {
        $charactersToReplace = array (',', '.', '?', '!', '\'', '(', ')', '"', ';', '\\');
 
        return str_replace($charactersToReplace, ' ', $text);
    }
}
