<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
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
            IdField::new('id'),
            TextField::new('reference'),
            AssociationField::new('users'),
            TextField::new('fullName'),
            IntegerField::new('quantity'),
            MoneyField::new('subTotalTTC')->setNumDecimals(2)->setCustomOption('storedAsCents', false)->setCurrency('EUR'),
            TextField::new('carrierName'),
            TextField::new('deliveryAddress'),
            BooleanField::new('isPaid'),
            MoneyField::new('carrierPrice')->setNumDecimals(2)->setCustomOption('storedAsCents', false)->setCurrency('EUR'),
            TextEditorField::new('moreInformation'),
            DateTimeField::new('createdAt'),
            MoneyField::new('subTotalHT')->setNumDecimals(2)->setCustomOption('storedAsCents', false)->setCurrency('EUR'),
            MoneyField::new('taxe')->setNumDecimals(2)->setCustomOption('storedAsCents', false)->setCurrency('EUR'),
            MoneyField::new('price')->setNumDecimals(2)->setCustomOption('storedAsCents', false)->setCurrency('EUR'),
            MoneyField::new('unitPrice')->setNumDecimals(2)->setCustomOption('storedAsCents', false)->setCurrency('EUR'),
            TextField::new('stripeSessionId'),
            AssociationField::new('orderDetails'),
            AssociationField::new('books')
        ];
    }

}
