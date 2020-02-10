<?php

namespace App\Controller\Admin;

use App\Entity\Figures;
use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/video")
 */
class AdminVideoController extends AbstractController
{
	/**
	 * @Route("/{idFigure}/new", name="admin.video.new", methods={"GET","POST"})
	 */
	public function new(Request $request): Response
	{
		$params = $request->attributes->get('_route_params');
		$idFigure = $params['idFigure'];
		$figure = $this->getDoctrine()
			->getRepository(Figures::class)
			->find($idFigure);

		$video = new Video();
		$form = $this->createForm(VideoType::class, $video);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$video->setFigure($figure);
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($video);
			$entityManager->flush();

			$this->addFlash('success', 'La vidéo a bien été ajoutée');
			return $this->redirectToRoute('home');
		}

		return $this->render('admin/video/new.html.twig', [
			'video' => $video,
			'form' => $form->createView(),
			'current_menu' => 'admin.video.new',
			'idFigure' => $idFigure
		]);
	}

	/**
	 * @Route("/{id}/edit", name="admin.video.edit", methods={"GET","POST"})
	 */
	public function edit(Request $request, Video $video): Response
	{
		$idFigure = $video->getFigure()->getId();

		$form = $this->createForm(VideoType::class, $video);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDoctrine()->getManager()->flush();

			$this->addFlash('success', 'La vidéo a bien été modifiée');
			return $this->redirectToRoute('home');
		}

		return $this->render('admin/video/edit.html.twig', [
			'video' => $video,
			'form' => $form->createView(),
			'current_menu' => 'admin.video.edit',
			'idFigure' => $idFigure
		]);
	}

	/**
	 * @Route("/{id}", name="admin.video.delete", methods={"DELETE"})
	 */
	public function delete(Request $request, Video $video): Response
	{
		if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($video);
			$entityManager->flush();
			$this->addFlash('success', 'La vidéo a bien été supprimée');
			return $this->redirectToRoute('home');
		}

		$this->addFlash('error', 'Un problème est survenu');
		return $this->redirectToRoute('home');
	}
}
