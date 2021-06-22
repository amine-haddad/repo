<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * 
 * @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController

{

    /**
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Séries',
            'programs' => $programs

        ]);
    }
    /**
     *Getting a program by id
     * 
     *@Route("_show/{id}", name="show", methods={"GET"},requirements={"id"="\d+"})
     *@return Response
     */
    public function show(int $id): Response
    {   
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['id' => $id]);
        $seasons= $program->getSeasons();
        
        if (!$program && !$seasons) {
            throw $this->createNotFoundException(
                'No program with id : ' . $id . ' found in program\'s table.'
            );
        }     
        return $this->render('program/show.html.twig', [
            'id' => $id,
            'program' => $program, 
            'seasons'=>$seasons  
        ]);
    }
    /**
     *Getting a season by id
     * 
     *@Route("/{programId}/seasons/{seasonId}", name="season_show", methods={"GET"},requirements={"id"="\d+"})
     *@entity("season", expr="repository.find(seasonId)")
     *@return Response
     */
    public function showSeason(int $programId, int $seasonId){
        $seasonId = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['season' => $seasonId]);
        if (!$seasonId) {
            throw $this->createNotFoundException(
                'No season with program : '.$seasonId.' found in season\'s table.'
            );
        }
        $programId = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['id' => $programId]);
        
        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findall();
            

        return $this->render('program/season_show.html.twig', [
            'program' => $programId,
            'season' => $programId->getProgram(),  
            'episodes' => $episodes 
        ]);
    }
}