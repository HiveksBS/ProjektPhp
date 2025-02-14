<?php

namespace App\Controller;

use App\Entity\Reviews;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewsModerationController extends AbstractController
{
    #[Route('/moderation', name: 'moderation_reviews')]
    // Wyświetla wszystkie recenzje do moderacji
    public function listPendingReviews(EntityManagerInterface $entityManager): Response
    {
        $pendingReviews = $entityManager->getRepository(Reviews::class)->findBy(['status' => 1]);

        return $this->render('moderation/reviews.html.twig', [
            'reviews' => $pendingReviews,
        ]);
    }

    #[Route('/moderation/approve/{id}', name: 'moderation_review_approve')]
    public function approveReview(Reviews $review, EntityManagerInterface $entityManager): Response
    {
        $review->setStatus(2); 
        $entityManager->flush();

        $this->addFlash('success', 'Recenzja zaakceptowana');
        return $this->redirectToRoute('moderation_reviews');
    }

    #[Route('/moderation/reject/{id}', name: 'moderation_review_reject')]
    public function rejectReview(Reviews $review, EntityManagerInterface $entityManager): Response
    {
        $review->setStatus(3);
        $entityManager->flush();

        $this->addFlash('error', 'Recenzja odrzucona');
        return $this->redirectToRoute('moderation_reviews');
    }
}