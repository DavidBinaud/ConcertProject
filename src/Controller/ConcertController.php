<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Form\ConcertType;
use App\Repository\ConcertRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/concert")
 */
class ConcertController extends AbstractController
{
    /**
     * @Route("/", name="concert_index", methods={"GET"})
     * @isGranted("ROLE_ADMIN")
     */
    public function index(ConcertRepository $concertRepository): Response
    {
        return $this->render('concert/index.html.twig', [
            'concerts' => $concertRepository->findAll(),
        ]);
    }

    /**
     * @Route("/next/{page}", name="concert_next", requirements={"page"="\d+"}, methods={"GET"})
     */
    public function list_next(int $page = 1): Response
    {
        $concertRepository = $this->getDoctrine()->getRepository(Concert::class);
        $limit = 5;
        $count = $concertRepository->findCountAllNext();
        $totalPages = ceil($count/$limit);
        return $this->render('concert/next.html.twig', [
            'concerts' => $concertRepository->findNextWithOffset($limit*($page-1),$limit),
            'count' => $count,
            'limit' => $limit,
            'page' => $page,
            'totalPages' => $totalPages,
            'limitPages' => 5
        ]);
    }

    /**
     * @Route("/previous", name="concert_previous")
     */
    public function list_previous(ConcertRepository $concertRepository): Response
    {
        return $this->render('concert/previous.html.twig', [
            'concerts' => $concertRepository->findAllPrevious(),
        ]);
    }

    /**
     * @Route("/new", name="concert_new", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager,  FileUploader $fileUploader): Response
    {
        $concert = new Concert();
        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('picture')->getData();
            if ($file) {
                $pictureFileName = $fileUploader->upload($file, 'concert');
                $concert->setPictureFilename($pictureFileName);
            }

            $entityManager->persist($concert);
            $entityManager->flush();

            return $this->redirectToRoute('concert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('concert/new.html.twig', [
            'concert' => $concert,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="concert_show", methods={"GET"})
     */
    public function show(Concert $concert): Response
    {
        return $this->render('concert/show.html.twig', [
            'concert' => $concert,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="concert_edit", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Concert $concert, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('picture')->getData();
            if ($file) {
                $oldFilename = $concert->getPictureFilename();
                $pictureFileName = $fileUploader->upload($file, 'concert');
                $concert->setPictureFilename($pictureFileName);
            }

            $entityManager->flush();

            if ($file) {
                $filesystem = new Filesystem();
                if ($oldFilename){
                    try {
                        $filesystem->remove($this->getParameter('kernel.project_dir') . "/public/images/concert/$oldFilename");
                    } catch (IOExceptionInterface $exception) {
                        echo "An error occurred while deleting your file: " . $exception->getPath();
                    }
                }
            }

            return $this->redirectToRoute('concert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('concert/edit.html.twig', [
            'concert' => $concert,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="concert_delete", methods={"POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Concert $concert, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$concert->getId(), $request->request->get('_token'))) {
            $entityManager->remove($concert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('concert_index', [], Response::HTTP_SEE_OTHER);
    }
}
