<?php

namespace App\Controller\Admin;

use App\Entity\Statistic;
use App\Entity\Uri;
use App\Form\UriType;
use App\Repository\StatisticRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function dashboardAction(StatisticRepository $statisticRepository, Request  $request): Response
    {
        $statistics = null;

        $uriForm = $this->createForm(UriType::class);
        $uriForm->handleRequest($request);

        if ($uriForm->isSubmitted() && $uriForm->isValid()) {
           $statistics = $this->orderDataByDate($statisticRepository->findBy([
               'uri' => $uriForm->getData()
           ]));
        }

        return $this->render('admin_index.html.twig', [
            'form' => $uriForm->createView(),
            'statistics' => $statistics
        ]);
    }

    /**
     * @param array $statistics
     * @return void
     */
    private function orderDataByDate(array $statistics)
    {
        $dataFiltered = [];

        /** @var Statistic $statistic */
        foreach ($statistics as $statistic) {
            $date = $statistic->getDate()->format('d-m-Y');

            if (!key_exists($date, $dataFiltered)) {
                $dataFiltered[$date] = 0;
            }

            $dataFiltered[$date] ++;
        }

        return $dataFiltered;
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Redirect Url');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Statistics', 'fas fa-list', Statistic::class);
        yield MenuItem::linkToCrud('URLs', 'fas fa-list', Uri::class);
    }
}
