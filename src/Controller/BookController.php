<?php
namespace App\Controller;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;


class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route(' /Book/getall',name:'app_get_all_book')]
    public function getAll(BookRepository $repo){
       $books=$repo->findAll();
       return $this->render('Book/list.html.twig',[
        'books'=>$books
       ]);}
     
    #[Route('/addBook', name: 'app_book_add')]
    public function addBook(Request $req,ManagerRegistry $manager) {
        $book = new Book();
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($req);
        //$book->setRef($form->getData()->getRef());
        if($form->isSubmitted()){
            $book->setPublished(true);
        $manager->getManager()->persist($book);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_get_all_book');
        }
        return $this->render('book/add.html.twig',[
            'f'=>$form->createView()
        ]);
        
    }
 
       #[Route('/Book/update/{id}', name: 'app_book_update')]
       public function updateBook($id,BookRepository $repo,Request $req,ManagerRegistry $manager){
           $book =$repo->find($id);
           $form = $this->createForm(BookType::class,$book);
           $form->handleRequest($req);
           if($form->isSubmitted()){
           $manager->getManager()->flush();
           return $this->redirectToRoute('app_get_all_book');
           }
           return $this->render('book/add.html.twig',[
               'f'=>$form->createView()
           ]);
       }
    #[Route(' /Book/delete/{id}',name:'app_delete_book')]
  public function delete($id,ManagerRegistry $manager,BookRepository $repo)
  {$book=$repo->find($id);
    $manager->getManager()->remove($book);
    $manager->getManager()->flush();
    return $this->redirectToRoute('app_get_all_book');
  }
 
}
