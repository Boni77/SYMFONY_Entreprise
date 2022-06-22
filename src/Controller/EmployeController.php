<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    /**
     * @Route("/employe", name="app_employe")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $employes = $doctrine->getRepository(Employe::class)->findAll();
        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
        ]);;
    }

    /**
     * @Route("/employe/add", name="add_employe")
     * @Route("/employe/update/{id}", name="update_employe")
     */
    public function add(ManagerRegistry $doctrine, Employe $employe = null, Request $request): Response
    {

        if(!$employe){
            $employe = New Employe();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $employe = $form->getData();
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('app_employe');
        }

        return $this->render('employe/add.html.twig', [
            'formEmploye' => $form->createView()
        ]);
    }

    /**
     * @Route("/employe/delete/{id}", name="delete_employe")
     */
    public function delete(ManagerRegistry $doctrine, Employe $employe)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($employe);
        $entityManager->flush();
        return $this->redirectToRoute("app_employe");
    }

    /**
     * @Route("/employe/{id}", name="show_employe")
     */
    public function show(Employe $employe)
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe,
        ]);;
    }
}
