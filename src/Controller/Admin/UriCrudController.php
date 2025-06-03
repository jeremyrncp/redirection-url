<?php

namespace App\Controller\Admin;

use App\Entity\Uri;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UriCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Uri::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('domain'),
            TextField::new('baseUri'),
            TextField::new('redirectUri'),
            TextField::new('title'),
            TextField::new('description'),
            ImageField::new('image')->setUploadDir("public/images/")->setBasePath("/images/"),
            DateField::new('date')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $linkRedirectUri = Action::new('redirectUri', 'View URI', 'fa fa-envelope')
            ->linkToRoute('app_redirect_uri_view', function (Uri $uri): array {
                return [
                    'id' => $uri->getId()
                ];
            });

        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, $linkRedirectUri)
            ;
    }

}
