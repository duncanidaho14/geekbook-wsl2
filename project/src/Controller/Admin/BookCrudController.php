<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextField::new('introduction'),
            ImageField::new('firstCover')
                ->setBasePath('build/images')
                ->setUploadDir('public/build/images'),
            TextEditorField::new('description'),
            DateTimeField::new('publishedAt'),
            SlugField::new('slug')->setTargetFieldName('title'),
            MoneyField::new('price')->setCurrency('EUR'),
            TextField::new('langue'),
            IntegerField::new('nbPages'),
            TextField::new('dimension'),
            TextField::new('isbn'),
            TextField::new('editor'),
            BooleanField::new('isInStock'),
            IntegerField::new('rating'),
            AssociationField::new('comments'),
            CollectionField::new('images'),
            AssociationField::new('command'),
            AssociationField::new('categories'),
            AssociationField::new('authors'),

        ];
    }
    
}
