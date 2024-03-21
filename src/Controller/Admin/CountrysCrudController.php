<?php

namespace App\Controller\Admin;

use App\Entity\Countrys;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CountrysCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Countrys::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
          
            TextField::new('country'),
            
        ];
    }
    
}
