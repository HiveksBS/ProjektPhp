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

class KlawiaturyController extends AbstractController
{
    #[Route('/klawiatury', name: 'klawiatury_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Category::class)->findOneBy(['category_name' => 'klawiatury']);
        
        if (!$category) {
            throw $this->createNotFoundException('Nie znaleziono kategori "klawiatury".');
        }

        $products = $entityManager->getRepository(Product::class)->findBy(['category' => $category]);

        return $this->render('klawiatury/list.html.twig', [
            'products' => $products,
        ]);
    }

    #wyszukuje daną klawiaturę po idiku i wyświetla tylko ją 
    #[Route('/klawiatury/{id}', name: 'klawiatury_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        
        if (!$product || $product->getCategory()->getCategoryName() !== 'klawiatury') {
            throw $this->createNotFoundException('Nie ma produktu w kategori "klawiatury');
        }

        return $this->render('klawiatury/detail.html.twig', [
            'product' => $product,
            'reviews' => $product->getReviews(),
        ]);
    }


    #[Route('/klawiatury/{id}/addreview', name: 'add_review', methods: ['GET', 'POST'])]
    public function addReview(Product $product, Request $request, EntityManagerInterface $entityManager, UserInterface $user ): Response
    {
        if (!$user) {
            $this->addFlash('error', 'Zalogój się aby dodać recenzję');
            return $this->redirectToRoute('klawiatury_detail', ['id' => $product->getId()]);
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
            return $this->redirectToRoute('klawiatury_detail', ['id' => $product->getId()]);
        }

        return $this->render('klawiatury/add_review.html.twig', [
            'product' => $product,
            'reviewForm' => $form->createView(),
        ]);
    }
}
