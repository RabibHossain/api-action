### Api Action Structure
            
            app
            └── ApiActionPackages
            │   └── {{ActionName}}   
            │   │    └── Actions
            │   │    │   ├── CreatePost
            │   │    │   ├── DeletePost
            │   │    │   ├── FindPost
            │   │    │   ├── ListPost
            │   │    │   └── UpdatePost
            │   │    └── Helpers
            │   │        └── {{ActionName}}Helper
            │   └── BaseHelper
            │   │   └── BaseHelper
            │   └── Traits
            │       └── ApiResponse
            └── Http
            │   └── Controllers
            │   │   └── {{ActionName}}Controller
            │   └── Requests
            │       └── {{ActionName}}Request
            └── Models
            │   └── {{ActionName}}Model
            └── database
                └── migrations
                    └── create_{{ActionName}}_table
                 
          
<p align="center">
<a href="https://packagist.org/packages/rabibgalib/api-action"><img src="https://img.shields.io/packagist/dt/rabibgalib/api-action" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/rabibgalib/api-action"><img src="https://img.shields.io/packagist/v/rabibgalib/api-action" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/rabibgalib/api-action"><img src="https://img.shields.io/packagist/l/rabibgalib/api-action" alt="License"></a>
</p>

# api-action

## Installation
  ```sh
  composer require rabibgalib/api-action
  ```

## Configuration
Add the provider to your `config/app.php` into `provider` section if using lower version of laravel,

  ```sh
  \Rabibgalib\ApiAction\ApiActionServiceProvider::class,
  ```
If you face `419 (page expired)` error or `CORS` or `XSRF` issue for a new project or have not fixed the issue,
then update `App/Http/Middleware/VerifyCsrfToken.php` as -
  ```sh
    protected $except = [
        "*"
    ];
  ```

## Run Command

After the installation & configuration run the command as -
  ```sh
    php artisan make:api-action ActionName
  ```

# Example 
If you want to create a **Post action api**. Please write command as -

  ```sh
    php artisan make:api-action Api/Post
  ```
This command will create 
- an API PostController 
- Action directory  
  - Action classes
  - Helper class  
  - Trait 
- Form Request
- Model  
- Migration <br>
to perform a feature wise service for your application.

![api-action-post.PNG](https://raw.githubusercontent.com/RabibHossain/images/main/api-action-post.PNG?token=GHSAT0AAAAAACC6LYYKRCSLCTZ5FAMW6J2WZH56IUA)

Now put below codes inside `Post` Model as -
  ```sh
  protected $fillable = [
        'author',
        'title',
        'description'
    ];
   ```

Now put below codes inside `posts` Migration as -
  ```sh
  $table->string('author')->nullable();
  $table->string('title')->nullable();
  $table->string('description')->nullable();
   ```
After migration command, set up the routes/web.php as -
  ```sh
use App\Http\Controllers\Api\PostController;
Route::get('posts', [PostController::class, 'index']);
Route::post('post', [PostController::class, 'create']);
Route::get('post/{id}', [PostController::class, 'find']);
Route::put('post/{id}', [PostController::class, 'update']);
Route::delete('post/{id}', [PostController::class, 'delete']);
   ```
Now, you can test the APIs in Postman or Insomnia easily.

![post-api-action.gif](https://raw.githubusercontent.com/RabibHossain/images/main/post-api-action.gif?token=GHSAT0AAAAAACC6LYYLGBMI72SUADS3R2DMZH552MQ)

### Have fun. Happy Coding.
