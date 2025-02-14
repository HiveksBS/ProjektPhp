<?php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Reviews;
use App\Form\ReviewsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;


class SwitchController extends AbstractController
{
    #[Route('/switche', name: 'switch_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Category::class)->findOneBy(['category_name' => 'switche']);
        
        if (!$category) {
            throw $this->createNotFoundException('Nie znaleziono kategori "switche".');
        }

        $products = $entityManager->getRepository(Product::class)->findBy(['category' => $category]);

        return $this->render('switche/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/switche/{id}', name: 'switch_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        
        if (!$product || $product->getCategory()->getCategoryName() !== 'switche') {
            throw $this->createNotFoundException('Nie ma produktów w kategori switche');
        }

        return $this->render('switche/detail.html.twig', [
            'product' => $product,
            'reviews' => $product->getReviews(),
        ]);
    }


    #[Route('/switche/{id}/addreview', name: 'switch_add_review', methods: ['GET', 'POST'])]
    public function addReview(Product $product, Request $request, EntityManagerInterface $entityManager, UserInterface $user ): Response
    {
        // Ensure user is logged in
        if (!$user) {
            $this->addFlash('error', 'Zalogój się aby dodać recenzję');
            return $this->redirectToRoute('switch_detail', ['id' => $product->getId()]);
        }

        $review = new Reviews();
        
        $review->setProductId($product); //bierze produkt po id w pathie
        $review->setUserId($user); //bierze  aktualnie zalogowanego usera 

        $form = $this->createForm(ReviewsType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            #$review2 = $form->getData()
            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'Dodano recenzję do sprawdzenia');
            return $this->redirectToRoute('switch_detail', ['id' => $product->getId()]);
        }

        return $this->render('switche/add_review.html.twig', [
            'product' => $product,
            'reviewForm' => $form->createView(),
        ]);
    }
}
