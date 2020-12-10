<?php
namespace App\Post;

use App\Core\AbstractController;
use App\User\LoginService;

class PostsAdminController extends AbstractController 
{
    public function __construct(
        PostsRepository $postsRepository,
        LoginService $loginService
        )
    {
        $this->postsRepository = $postsRepository;
        $this->loginService = $loginService;
    }

    public function index()
    {
        $this->loginService->check();
        $all = $this->postsRepository->all();
        $this->render("post/admin/index", [
            'all' => $all
        ]);
    }

    public function edit()
    {
       // hole mir den eintrag aus der URL des $_GET arrays
       $id = $_GET['id'];
       // hier sage ich: der Eintrag($entry) den ich bearbeiten moechte ist $this->file postsRepository->file find($id) und ich uebergebe die Id als parameter
       $entry = $this->postsRepository->find($id);
       
       $savedSuccess = false;

       if (!empty($_POST['title']) && !empty($_POST['content'])) {
           $entry->title = $_POST['title'];
           $entry->content = $_POST['content'];
           $this->postsRepository->update($entry);
           $savedSuccess = true;
       }
       // als naechstes: render mir doch bitte den view und zwar den edit view und uebergebe ihm mein $entry
       $this->render("post/admin/edit", [
        'entry' => $entry,
        'savedSuccess' => $savedSuccess
    ]);
    }
}