<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use App\Entity\ResetPasswordRequest;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ResetPasswordRequestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ResetPasswordRequest::class;
    }

    function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user'),
        ];
    }

}
