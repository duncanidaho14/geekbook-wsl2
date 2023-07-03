<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetails;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class OrderDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderDetails::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('productName'),
            MoneyField::new('productPrice')->setCurrency('EUR')->setNumDecimals(2),
            IntegerField::new('quantity'),
            MoneyField::new('subTotalTTC')->setCurrency('EUR')->setNumDecimals(2),
            MoneyField::new('subTotalHT')->setCurrency('EUR'),
            MoneyField::new('taxe')->setCurrency('EUR'),
            AssociationField::new('orders'),
            MoneyField::new('carrierPrice')->setCurrency('EUR'),
            TextField::new('carrierName'),
        ];
    }
    
}
