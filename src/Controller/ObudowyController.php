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


class ObudowyController extends AbstractController
{
    #[Route('/obudowy', name: 'obudowy_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Category::class)->findOneBy(['category_name' => 'obudowy']);
        
        if (!$category) {
            throw $this->createNotFoundException('Nie znaleziono kategori "obudowy".');
        }

        $products = $entityManager->getRepository(Product::class)->findBy(['category' => $category]);

        return $this->render('obudowy/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/obudowy/{id}', name: 'obudowy_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        
        if (!$product || $product->getCategory()->getCategoryName() !== 'obudowy') {
            throw $this->createNotFoundException('Nie ma produktów w kategori obudowy');
        }

        return $this->render('obudowy/detail.html.twig', [
            'product' => $product,
            'reviews' => $product->getReviews(),
        ]);
    }


    #[Route('/obudowy/{id}/addreview', name: 'obudowy_add_review', methods: ['GET', 'POST'])]
    public function addReview(Product $product, Request $request, EntityManagerInterface $entityManager, UserInterface $user ): Response
    {
        // Ensure user is logged in
        if (!$user) {
            $this->addFlash('error', 'Zalogój się aby dodać recenzję');
            return $this->redirectToRoute('obudowy_detail', ['id' => $product->getId()]);
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
            return $this->redirectToRoute('obudowy_detail', ['id' => $product->getId()]);
        }

        return $this->render('obudowy/add_review.html.twig', [
            'product' => $product,
            'reviewForm' => $form->createView(),
        ]);
    }
}