<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Hotel;
use App\Form\ClientType;
use App\Form\HotelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    #[Route('/main', name: 'home')]
    public function index(): Response
    {
        $clients= $this->getDoctrine()->getRepository(Client::class)->findAll();

    
        return  $this->render('client/index.html.twig',['clients' => $clients]);  

    }

/**
     * @Route("/client/{id}", name="client_show")
     */
    public function show($id) {
        $client = $this->getDoctrine()->getRepository(Client::class)->find($id);
  
        return $this->render('client/show.html.twig', array('client' => $client));
      }
  

/**
 * @Route("/Ajouter", name="add_client")
 */
public function ajouter(Request $request)
{
    $clients = new Client();
    $fb = $this->createFormBuilder($clients)
        ->add('nom', TextType::class)
        ->add('nbrpersonne', NumberType::class, ['label' => 'Nombre de personnes'])
        ->add('email', TextType::class)
        ->add('hotel', EntityType::class, [
            'class' => hotel::class,
            'choice_label' => 'nomHotel',
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Ajouter client', // Set the label explicitly
            'attr' => ['class' => 'btn btn-primary'], // Add Bootstrap classes if needed
        ]);

    // Generate the form from the FormBuilder
    $form = $fb->getForm();

    // Injection into the database
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($clients);
        $em->flush();
        return $this->redirectToRoute('home');
    }

    return $this->render('client/ajouter.html.twig', ['f' => $form->createView()]);
}
/**
 * @Route("/supp/{id}", name="client_delete")
*/
    public function delete(Request $request, $id): Response
    {
        $c = $this->getDoctrine()
            ->getRepository(Client::class)
            ->find($id);
        if (!$c) {
            throw $this->createNotFoundException(
                'No client found for id '.$id
            );
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($c);

        $entityManager->flush();
        return $this->redirectToRoute('home');
    }
/**
 * @Route("/edit/{id}", name="edit_user")
 * Method({"GET","POST"})
 */
public function edit(Request $request, $id)
{
    $client = $this->getDoctrine()
        ->getRepository(Client::class)
        ->find($id);

    if (!$client) {
        throw $this->createNotFoundException(
            'No client found for id ' . $id
        );
    }

    $fb = $this->createFormBuilder($client)
        ->add('nom', TextType::class)
        ->add('nbrpersonne', NumberType::class)
        ->add('email', TextType::class)
        ->add('hotel', EntityType::class, [
            'class' => hotel::class,
            'choice_label' => 'nomHotel',
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Ajouter Client', // Set the label explicitly
            'attr' => ['class' => 'btn btn-primary'], // Add Bootstrap classes if needed
        ]);

    // générer le formulaire à partir du FormBuilder
    $form = $fb->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }

    return $this->render('client/ajouter.html.twig', ['f' => $form->createView()]);
}


#[Route('/home1',name:'home1')]

    public function home1()
    {
  
      $hotels= $this->getDoctrine()->getRepository(hotel::class)->findAll();
      return  $this->render('hotel/index.html.twig',['hotels' => $hotels]);  
    }
    /**
     * @Route("/hotel/{id}", name="hotel_show")
     */
    public function showhotel($id) {
        $hotel= $this->getDoctrine()->getRepository(Hotel::class)->find($id);
  
        return $this->render('hotel/show.html.twig', array('hotel' => $hotel));
      }


/**
     *
     * @Route("/add",name="ajout_hotel")
     */
    public function ajouter2(Request $request)
    {
        $hotel = new Hotel();
        $form = $this->createForm("App\Form\HotelType",$hotel);
        $form -> handleRequest($request);
        if ($form->isSubmitted()) {      
          
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($hotel);
            $em->flush();
        
            return $this->redirectToRoute('home1');
        }
        return $this->render('hotel/ajouter.html.twig',
            ['f'=>$form->createView()]);
    }
/**
         * @Route("/delete/{id}", name="hotel_deleteh")
         */
        public function deleteh(Request $request, $id)
        {
            $hotel = $this->getDoctrine()
                ->getRepository(Hotel::class)
                ->find($id);
        
            if (!$hotel) {
                throw $this->createNotFoundException('No hotel found for id ' . $id);
            }
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hotel);
            $entityManager->flush();
        
            return $this->redirectToRoute('home1');
        }


        /**
 * @Route("/edith/{id}", name="edith_user")
 * Method({"GET", "POST"})
 */
public function edith(Request $request, Hotel $hotel)
{
    $form = $this->createForm(HotelType::class, $hotel);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('home1');
    }

    return $this->render('hotel/ajouter.html.twig', ['f' => $form->createView()]);
}
}