<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Concert;
use App\Form\BandType;
use App\Repository\BandRepository;
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
 * @Route("/band")
 */
class BandController extends AbstractController
{
    /**
     * @Route("/", name="band_index", methods={"GET"})
     * @isGranted("ROLE_ADMIN")
     */
    public function index(BandRepository $bandRepository): Response
    {
        return $this->render('band/index.html.twig', [
            'bands' => $bandRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="band_new", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $band = new Band();
        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('picture')->getData();
            if ($file) {
                $pictureFileName = $fileUploader->upload($file, 'band');
                $band->setPictureFilename($pictureFileName);
            }


            $entityManager->persist($band);
            foreach ($form->get('concerts')->getData() as $concert){
                $concert->addBand($band);
            }


            $entityManager->flush();

            return $this->redirectToRoute('band_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('band/new.html.twig', [
            'band' => $band,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="band_show", methods={"GET"})
     */
    public function show(Band $band): Response
    {
        return $this->render('band/show.html.twig', [
            'band' => $band
        ]);
    }

    /**
     * @Route("/{id}/edit", name="band_edit", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Band $band, EntityManagerInterface $entityManager,  FileUploader $fileUploader): Response
    {
        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('picture')->getData();
            if ($file) {
                $oldFilename = $band->getPictureFilename();
                $pictureFileName = $fileUploader->upload($file, 'band');
                $band->setPictureFilename($pictureFileName);
            }

            foreach ($form->get('concerts')->getData() as $concert){
                $concert->addBand($band);
            }


            $entityManager->flush();

            if ($file) {
                $filesystem = new Filesystem();

                try {
                    $filesystem->remove($this->getParameter('kernel.project_dir') . "/public/images/band/$oldFilename");
                } catch (IOExceptionInterface $exception) {
                    echo "An error occurred while deleting your file: " . $exception->getPath();
                }
            }

            return $this->redirectToRoute('band_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('band/edit.html.twig', [
            'band' => $band,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="band_delete", methods={"POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Band $band, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$band->getId(), $request->request->get('_token'))) {
            $entityManager->remove($band);
            $entityManager->flush();
        }

        return $this->redirectToRoute('band_index', [], Response::HTTP_SEE_OTHER);
    }
}
