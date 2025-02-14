<?php


namespace App\Controller;

use App\Entity\Reviews;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;



class AccountDitailsController extends AbstractController
{
    #[Route('/account', name: 'user_account')]
    public function userReviews(EntityManagerInterface $entityManager, UserInterface $user,Security $security): Response
    {

        $reviews = $entityManager->getRepository(Reviews::class)->findBy(['user_id' => $user]);//chciałem wszystkie review urzytkownika
        //a brałem  review id zamiast user id 
        // aktualnie zalogowany user 
        //dump($reviews); die;

        return $this->render('account/accountditails.html.twig', [
            'reviews' => $reviews,
        ]);
    }
}