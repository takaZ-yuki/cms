<?php

// src/Controller/ArticlesController.php

namespace App\Controller;

use Cake\Controller\Controller;

class ArticlesController extends Controller
{
    public function index()
    {
        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }
}
?>
