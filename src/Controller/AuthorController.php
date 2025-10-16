<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Author;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AuthorType;
use Dom\Entity;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/{name}', name: 'show_author')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/authorlist', name: 'app_author_list')]
    public function listAuthors(): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha-Hussein.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
            );
        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/authorDetails/{id}', name: 'author_details')]
    public function authorDetails($id): Response
    {   
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha-Hussein.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
            );
            foreach ($authors as $author) {
                if ($author['id'] == $id) {
                    return $this->render('author/showAuthor.html.twig', [
                        'id' => $id,
                        'author' => $author,
                    ]);
                }
            }
            // Return a 404 response if author not found
            return new Response('Author not found', 404);
        }



#[Route('/listeAuthors', name: 'liste_authors')]
public function listeAuthors(EntityManagerInterface $entityManager): Response
{
    $Authors = $entityManager->getRepository(Author::class)->findAll();

    // Redirect to the existing author list route so this action always returns a Response
    return $this->render('author/list.html.twig', [
        'controller_name' => 'AuthorController',
        'authors' => $Authors,
    ]); 
}

#[Route('/addAuthor', name: 'add_author')]
public function addAuthor(Request $request,EntityManagerInterface $entityManager): Response
{
    $author = new Author();
    $form = $this->createForm(AuthorType::class, $author);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($author);
        $entityManager->flush();

        return $this->redirectToRoute('liste_authors');
    }

    return $this->render('author/add.html.twig', [
        'form' => $form->createView(),
    ]);
}


#[Route('/updateAuthor/{id}', name: 'update_author')]
public function updateAuthor(int $id,EntityManagerInterface $entityManager, Request $request): Response
{
$author = $entityManager->getRepository(Author::class)->find($id);
if (!$author) {
    throw $this->createNotFoundException(
        'No author found for id '.$id
    );

}
//cree le formulaire
$form = $this->createForm(AuthorType::class, $author);
$form->handleRequest($request);

//validation du formualire
if ($form->isSubmitted() && $form->isValid()) {
    //modifier l'auteur
    $author->setUsername($form->get('Username')->getData());
    $author->setEmail($form->get('email')->getData());

    $entityManager->flush();

    return $this->redirectToRoute('liste_authors');
}
return $this->render('author/edit.html.twig', [
    'form' => $form->createView(),
    'author' => $author,        
]);

}

#[Route('/deleteAuthor/{id}', name: 'delete_author')]
public function deleteAuthor(int $id,EntityManagerInterface $entityManager): Response
{   
    $author = $entityManager->getRepository(Author::class)->find($id);
    if (!$author) {
        throw $this->createNotFoundException(
            'No author found for id '.$id
        );
    
    }
    $entityManager->remove($author);
    $entityManager->flush();
    return $this->redirectToRoute('liste_authors'); 
}


















}
    



















































