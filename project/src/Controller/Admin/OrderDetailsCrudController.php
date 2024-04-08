<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetails;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderDetails::class;
    }

    function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('productName'),
            MoneyField::new('productPrice')->setNumDecimals(2)->setCustomOption('storedAsCents', false)->setCurrency('EUR'),
            IntegerField::new('quantity'),
            MoneyField::new('subTotalTTC')->setNumDecimals(2)->setCustomOption('storedAsCents', false)->setCurrency('EUR'),
            MoneyField::new('subTotalHT')->setNumDecimals(2)->setCustomOption('storedAsCents', false)->setCurrency('EUR'),
            MoneyField::new('taxe')->setNumDecimals(2)->setCustomOption('storedAsCents', false)->setCurrency('EUR'),
            AssociationField::new('orders'),
            MoneyField::new('carrierPrice')->setNumDecimals(2)->setCustomOption('storedAsCents', false)->setCurrency('EUR'),
            TextField::new('carrierName'),
        ];
    }

}
