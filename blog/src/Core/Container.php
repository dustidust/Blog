<?php

namespace App\Core;

use PDO;
use Exception;
use PDOException;

use App\Post\PostsRepository;
use App\Post\CommentsRepository;
use App\Post\PostsController;
use App\Post\PostsAdminController;
use App\User\UserRepository;
use App\User\LoginController;
use App\User\LoginService;

class Container
{

  private $receipts = [];
  private $instances = [];

  public function __construct()
  {
    $this->receipts = [
      'postsAdminController' => function(){
        return new PostsAdminController(
          $this->make('postsRepository'),
          $this->make('loginService')
        );
      },
        'loginService' => function(){
          return new LoginService(
            $this->make("userRepository")
          );
        },
      'loginController' => function(){
        return new LoginController(
          $this->make("loginService")
        );
      },
      'postsController' => function() {
        return new PostsController(
          $this->make('postsRepository'),
          $this->make('commentsRepository')
        );
      },
      'userRepository' => function(){
        return new UserRepository(
          $this->make('pdo')
        );
      },
      'postsRepository' => function() {
        return new PostsRepository(
          $this->make("pdo")
        );
      },
      'commentsRepository' => function() {
        return new CommentsRepository(
          $this->make("pdo")
        );
      },
      'pdo' => function() {
        try {
          $pdo = new PDO(
            'mysql:host=localhost;dbname=blog;charset=utf8',
            'root',
            ''
          );
        } catch (PDOException $e) {
          echo "Verbindung zur Datenbank fehlgeschlagen";
          die();
        }
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
      }
    ];
  }

  public function make($name)
  {
    if (!empty($this->instances[$name]))
    {
      return $this->instances[$name];
    }

    if (isset($this->receipts[$name])) {
      $this->instances[$name] = $this->receipts[$name]();
    }

    return $this->instances[$name];
  }
}
 ?>
