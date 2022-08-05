<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\VideoRepository;
use App\Entity\Video;
use App\Form\VideoType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class VideoController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(VideoRepository $repo): Response
    {
        return $this->render('video/index.html.twig', ['video' => $repo->findAll()]);
    }
    /**
     * @Route("/video/{id<[0-9]+>}", name="app_video_show", methods="GET")
     */
    public function show(Video $video, User $user): Response
    {
        if (!$this->getUser() && $video->isIspremium()) {
            $this->addFlash('error', 'You have to be connected to see a premium video');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('video/show.html.twig', compact('video', 'user'));
    }

    /**
     * @Route("/video/create", name="app_video_create", methods= {"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('error', 'You have to be connected to create a video');
            return $this->redirectToRoute('app_home');
        }
        $video = new Video;
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $video->setUser($this->getUser());
            $em->persist($video);
            $em->flush();
            $this->addFlash('success', 'Video successfully created !');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('video/create.html.twig', ['monForm' => $form->createView()]);
    }

    /**
     * @Route("/video/{id<[0-9]+>}/edit", name="app_video_edit", methods= {"GET","POST"})
     */
    public function edit(Request $request, Video $video, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Video successfully updated !');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('video/edit.html.twig', [
            'video' => $video,
            'monForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/video/{id<[0-9]+>}/delete", name="app_video_delete")
     */
    public function delete(Video $video, EntityManagerInterface $em): Response
    {
        $em->remove($video);
        $em->flush();
        $this->addFlash('info', 'Video successfully deleted !');
        return $this->redirectToRoute('app_home');
    }
}
