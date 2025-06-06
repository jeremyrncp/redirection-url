<?php

namespace App\Controller\Admin;

use App\Entity\Statistic;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StatisticCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Statistic::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('uri'),
            TextField::new('ip'),
            DateField::new('date'),
        ];
    }
}
