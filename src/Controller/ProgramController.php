<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/programs", name="program_")
 */

class ProgramController extends AbstractController
{  

     /**
      * Correspond à la route /programs/ et au name "program_index"
      * @Route("/", name="index")
      * @return Response A response instance
      */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs,]
        );
    }

          /**
     * The controller for the program add form
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }
     /**
      * @Route("/{id}", requirements={"id"="\d+"}, methods={"GET"}, name="show")
      * @return Response
      */
    public function show(Program $program): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);

        return $this->render('program/show.html.twig', [
            'program' => $program, 
            'seasons' => $seasons,
        ]);
    }
     /**
      * @Route("/{program}/seasons/{season}", name="season_show")
      * @return Response
      */
    public function showSeason(Program $program, Season $season): Response
    {
        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season' => $season]);

        return $this->render('program/season_show.html.twig', [
            'program' => $program, 
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }
     /**
      * @Route("/{programId}/seasons/{seasonId}/episodes/{episodeId}", name="episode_show")
      * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
      * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
      * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeId": "id"}})
      * @return Response
      */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program, 
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}