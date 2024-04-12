
<?php

use App\Entity\Rating; // Importation manquante
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    #[Route('/rating', name: 'app_rating')]
    public function index(): Response
    {
        return $this->render('rating/index.html.twig', [
            'controller_name' => 'RatingController',
        ]);
    }

    #[Route('/ratingSupp/{id}', name: 'rating_delete', methods: ['DELETE'])]
    public function delete(Request $request, Rating $rating): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        if ($this->isCsrfTokenValid('delete' . $rating->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rating);
            $entityManager->flush();

            $this->addFlash('success', 'L\'évaluation a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_rating'); // Rediriger vers une autre page après la suppression
    }
}
