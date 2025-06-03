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

        $uri->setClic($uri->getClic()+1);

        $statistic = new Statistic();
        $statistic->setUri($uri);
        $statistic->setDate(new \DateTime());
        $statistic->setIp($_SERVER['REMOTE_ADDR'] . ";" . gethostbyaddr($_SERVER["REMOTE_ADDR"]));

        $entityManager->persist($statistic);
        $entityManager->flush();

        /** detect facebook */
        $explodeIstio = explode('istio-ingressgateway', gethostbyaddr($_SERVER["REMOTE_ADDR"]));

        if ($_SERVER['REMOTE_ADDR'] === "10.244.20.223" || $_SERVER['REMOTE_ADDR'] === "10.244.17.31") {
            $content = '
            <!doctype html>
                <html class="no-js" lang="fr">
                <head>
                <meta charset="utf-8" />
                <meta content="'. $uri->getDescription() . '" name="description" />
                <meta content="'. $uri->getRedirectUri() . '" property="og:url" />
                <meta content="article" property="og:type" />
                <meta content="http://' . $uri->getDomain()->getName() . '" property="og:site_name" />
                <meta content="'. $uri->getTitle() . '" property="og:title" />
                <meta content="'. $uri->getDescription() . '" property="og:description" />
                <meta content="' . $_ENV["URL_FRONT"] . "/images/" . $uri->getImage() . '" property="og:image" />
                <meta content="fr_FR" property="og:locale" />
                <meta content="1200" property="og:image:width" />
                <meta content="630" property="og:image:height" />
                <meta content="noarchive" name="robots" />
                <meta name="theme-color" content="#353535" />
                
                </head>
                <body>
                    <h1>' . $uri->getTitle() . '</h1>
                    <p>' . $uri->getDescription() . '</p>
                </body>
                </html>';
            return new Response($content);
        }

        return new RedirectResponse($uri->getRedirectUri(), 301);
    }
}
