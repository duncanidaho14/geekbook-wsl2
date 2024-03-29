<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('reference'),
            AssociationField::new('users'),
            TextField::new('fullName'),
            IntegerField::new('quantity'),
            MoneyField::new('subTotalTTC')->setCurrency('EUR'),
            AssociationField::new('orderDetails'),
            TextField::new('carrierName'),
            TextField::new('deliveryAddress'),
            BooleanField::new('isPaid'),
            MoneyField::new('carrierPrice')->setCurrency('EUR'),
            TextEditorField::new('moreInformation'),
            DateTimeField::new('createdAt'),
            MoneyField::new('subTotalHT')->setCurrency('EUR'),
            MoneyField::new('taxe')->setCurrency('EUR'),
            MoneyField::new('price')->setCurrency('EUR'),
            MoneyField::new('unitPrice')->setCurrency('EUR'),
            TextField::new('stripeSessionId'),
            AssociationField::new('books')
        ];
    }

}
