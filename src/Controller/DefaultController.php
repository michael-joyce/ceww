<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return [];
    }

    /**
     * Search for publication entities.
     *
     * @Route("/search", name="search")
     *
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request, PublicationRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $publications = $paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'));
        } else {
            $publications = array();
        }

        return array(
            'publications' => $publications,
            'q' => $q,
        );
    }

    /**
     * @Route("/privacy", name="privacy")
     * @Template()
     */
    public function privacyAction(Request $request) {

    }

}
