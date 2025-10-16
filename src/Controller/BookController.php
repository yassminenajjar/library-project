<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookType;


final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }


#[Route('/addBook', name: 'add_book')]
    public function addBook(EntityManagerInterface $em , Request $request): Response{

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('app_books');
        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);


    }
#[Route('/delete/{id}', name: 'app_delete_book')]
    public function deleteBook(EntityManagerInterface $em , $id): Response{

        $book = $em->getRepository(Book::class)->find($id);
        if($book){
            $em->remove($book);
            $em->flush();
        }
        return $this->redirectToRoute('app_books');
    }

#[Route('/listBooks', name: 'app_books')]
    public function listBooks(EntityManagerInterface $em): Response{

        $books = $em->getRepository(Book::class)->findAll();

        return $this->render('book/list.html.twig', [
            'books' => $books,
        ]);
    }




#[Route('/updateBook/{id}', name: 'update_book')]
    public function updateBook(EntityManagerInterface $em , Request $request, $id): Response{

        $book = $em->getRepository(Book::class)->find($id);
        if($book){
            $form = $this->createForm(BookType::class, $book);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $em->flush();
                return $this->redirectToRoute('app_books');
            }

            return $this->render('book/update.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('app_books');
    }


    #[Route('/books', name: 'app_all_books')]
    public function countPublishedBooks(EntityManagerInterface $em ): Response{
        $publishedBooksCount = $em->getRepository(Book::class)->findBy(['published' => true]);
            $publishedBooksCount = count($publishedBooksCount);
        return $this->render('book/list.html.twig', [
            'publishedBookCount' => $publishedBooksCount,
        ]);
    }
    




    

































































}
