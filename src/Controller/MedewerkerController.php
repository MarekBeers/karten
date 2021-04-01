<?php

namespace App\Controller;


use App\Entity\Activiteiten;
use App\Entity\Soortactiviteiten;
use App\Form\ActiviteitType;
use App\Form\SoortactiviteitenType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class MedewerkerController extends AbstractController
{
    /**
     * @Route("/admin/activiteiten", name="activiteitenoverzicht")
     */
    public function activiteitenOverzichtAction()
    {

        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteiten')
            ->findAll();

        return $this->render('medewerker/activiteiten.html.twig', [
            'activiteiten'=>$activiteiten
        ]);
    }

    /**
     * @Route("/admin/details/{id}", name="details")
     */
    public function detailsAction($id)
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteiten')
            ->findAll();
        $activiteit=$this->getDoctrine()
            ->getRepository('App:Activiteiten')
            ->find($id);

        $deelnemers=$this->getDoctrine()
            ->getRepository('App:User')
            ->getDeelnemers($id);


        return $this->render('medewerker/details.html.twig', [
            'activiteit'=>$activiteit,
            'deelnemers'=>$deelnemers,
            'aantal'=>count($activiteiten)
        ]);
    }

    /**
     * @Route("/admin/beheer", name="beheer")
     */
    public function beheerAction()
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteiten')
            ->findAll();

        return $this->render('medewerker/beheer.html.twig', [
            'activiteiten'=>$activiteiten
        ]);
    }

    /**
     * @Route("/admin/add", name="add")
     */
    public function addAction(Request $request)
    {
        // create a user and a contact
        $a=new Activiteiten();

        $form = $this->createForm(ActiviteitType::class, $a);
        $form->add('save', SubmitType::class, array('label'=>"voeg toe"));
        //$form->add('reset', ResetType::class, array('label'=>"reset"));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($a);
            $em->flush();

            $this->addFlash(
                'notice',
                'activiteit toegevoegd!'
            );
            return $this->redirectToRoute('beheer');
        }
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteiten')
            ->findAll();
        return $this->render('medewerker/add.html.twig',array('form'=>$form->createView(),'naam'=>'toevoegen','aantal'=>count($activiteiten)
        ));
    }

    /**
     * @Route("/admin/update/{id}", name="update")
     */
    public function updateAction($id,Request $request)
    {
        $a=$this->getDoctrine()
            ->getRepository('App:Activiteiten')
            ->find($id);

        $form = $this->createForm(ActiviteitType::class, $a);
        $form->add('save', SubmitType::class, array('label'=>"aanpassen"));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the contact (no queries yet)
            $em->persist($a);


            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            $this->addFlash(
                'notice',
                'activiteit aangepast!'
            );
            return $this->redirectToRoute('beheer');
        }

        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteiten')
            ->findAll();

        return $this->render('medewerker/add.html.twig',array('form'=>$form->createView(),'naam'=>'aanpassen','aantal'=>count($activiteiten)));
    }

    /**
     * @Route("/admin/delete/{id}", name="delete")
     */
    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $a= $this->getDoctrine()
            ->getRepository('App:Activiteiten')->find($id);
        $em->remove($a);
        $em->flush();

        $this->addFlash(
            'notice',
            'activiteit verwijderd!'
        );
        return $this->redirectToRoute('beheer');

    }

    /**
     * @Route("/admin/soort", name="soortactiviteiten_index", methods={"GET"})
     */
    public function index(): Response
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteiten')
            ->findAll();
        $soortactiviteitRepository=$this->getDoctrine()->getRepository(Soortactiviteiten::class);
        return $this->render('soortactiviteiten/index.html.twig', [
            'soortactiviteitens' => $soortactiviteitRepository->findAll(),
            'activiteiten' => $activiteiten,
        ]);
    }

    /**
     * @Route("/admin/soort/new", name="soortactiviteiten_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $soortactiviteiten = new Soortactiviteiten();
        $form = $this->createForm(SoortactiviteitenType::class, $soortactiviteiten);
        $form->handleRequest($request);
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteiten')
            ->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($soortactiviteiten);
            $entityManager->flush();

            return $this->redirectToRoute('soortactiviteiten_index');
        }

        return $this->render('soortactiviteiten/new.html.twig', [
            'soortactiviteiten' => $soortactiviteiten,
            'activiteiten' => $activiteiten,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/soort/{id}", name="soortactiviteiten_show", methods={"GET"})
     */
    public function show(Soortactiviteiten $soortactiviteiten): Response
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteiten')
            ->findAll();
        return $this->render('soortactiviteiten/show.html.twig', [
            'soortactiviteiten' => $soortactiviteiten,
            'activiteiten' => $activiteiten,
        ]);
    }

    /**
     * @Route("/admin/soort/{id}/edit", name="soortactiviteiten_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Soortactiviteiten $soortactiviteiten): Response
    {
        $form = $this->createForm(SoortactiviteitenType::class, $soortactiviteiten);
        $form->handleRequest($request);

        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteiten')
            ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('soortactiviteiten_index');
        }

        return $this->render('soortactiviteiten/edit.html.twig', [
            'soortactiviteiten' => $soortactiviteiten,
            'activiteiten' => $activiteiten,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/soort/{id}", name="soortactiviteiten_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Soortactiviteiten $soortactiviteiten): Response
    {
        if ($this->isCsrfTokenValid('delete'.$soortactiviteiten->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($soortactiviteiten);
            $entityManager->flush();
        }

        return $this->redirectToRoute('soortactiviteiten_index');
    }
}
