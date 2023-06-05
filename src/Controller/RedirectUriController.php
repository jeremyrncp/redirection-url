<?php

namespace App\Controller;

use App\Entity\Statistic;
use App\Entity\Uri;
use App\Repository\UriRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedirectUriController extends AbstractController
{
    #[Route('/{slug}', name: 'app_redirect_uri')]
    public function index(string $slug, UriRepository $uriRepository, EntityManagerInterface $entityManager): Response
    {
        /** @var Uri $uri */
        $uri = $uriRepository->findOneBy(['baseUri' => $slug]);

        if (is_null($uri)) {
            throw new InvalidArgumentException("Slug is invalid");
        }

        $statistic = new Statistic();
        $statistic->setUri($uri);
        $statistic->setDate(new \DateTime());
        $statistic->setIp($_SERVER['REMOTE_ADDR']);

        $entityManager->persist($statistic);
        $entityManager->flush();

        return new RedirectResponse($uri->getRedirectUri(), 301);
    }
}
