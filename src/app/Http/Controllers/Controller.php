<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Petshop Ecommerce",
 *      description="Petshop Ecommerce OpenApi Swagger Documentation",
 *      @OA\Contact(
 *          email="juntyms@gmail.com"
 *      ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     ),
 *  ),
 *
 *  @OA\SecurityScheme(
 *       type="http",
 *       description="Authorization with JWT",
 *       name="Authorization",
 *       in="header",
 *       scheme="bearer",
 *       bearerFormat="JWT",
 *       securityScheme="bearerToken"
 *  ),
 *
 *  @OA\Tag(name="Admin", description="Admin Endpoint"),
 *  @OA\Tag(name="User", description="User API Endpoint"),
 *  @OA\Tag(name="Currency Exchange Rate", description="Level 3 - Challenge")
 */

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
