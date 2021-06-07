<?php
// src/ControllerCategoryController.php
namespace App\Controller;
use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 
 * @Route("/categories", name="category")
 * @return Response
 */
class CategoryController extends AbstractController
{
    
    /**
     * @Route("/", name="_index" )
     * @return Response
     */
    public function index():Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render('category/index.html.twig',[
            'categories' =>$categories,
            
        ]);
       
    }
       
    /**
     * @Route("/{categoryName}", name="_show" )
     * @return Response
     */
    public function show(string $categoryName): Response
    {
        $categoryName = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findBy(['name' => $categoryName]);

    if (!$categoryName) {
        throw $this->createNotFoundException(
            'No categories with categoryName : '.$categoryName.' found in category\'s table.'
        );
    }
        $programCategory = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $categoryName],['id'=> 'ASC'],3,0);
        return $this->render('category/show.html.twig',[
            
            'categoryName' => $categoryName,
            'programCategory' => $programCategory

        ]);


    }

}