<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Program;

/**
 * @Route("/categories", name="category_")
 */

 class CategoryController extends AbstractController
 {
      /**
      * Correspond à la route /categories/ et au name "category_index"
      * @Route("/", name="index")
      * @return Response A response instance
      */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'category/index.html.twig',
            ['categories' => $categories,]
        );
    }
      /**
       * @Route("/{categoryName}", name="show")
       * @return Response
       */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        
        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name : '.$categoryName.' found in categories table.'
            );
        }       
 
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category->getId()], 
            ['id' => 'DESC'], 3);

        return $this->render('category/show.html.twig', [
            'programs' => $programs
        ]);
    }
 }