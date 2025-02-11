<?php

use Matcha\Api\Controllers\Auth\VerifyTokenController;
use Matcha\Api\Controllers\AuthenticatedSessionController;
use Matcha\Api\Controllers\ChatController;
use Matcha\Api\Controllers\EmailController;
use Matcha\Api\Controllers\ForgotController;
use Matcha\Api\Controllers\HistoryController;
use Matcha\Api\Controllers\LikeController;
use Matcha\Api\Controllers\LocalisationController;
use Matcha\Api\Controllers\Notifications\NotificationsController;
use Matcha\Api\Controllers\Profile\PreferencesController;
use Matcha\Api\Controllers\Profile\ProfileController;
use Matcha\Api\Controllers\Profile\ProfileSuggestionController;
use Matcha\Api\Controllers\Profile\UserBlockController;
use Matcha\Api\Controllers\RefreshTokenController;
use Matcha\Api\Controllers\RegisterController;
use Matcha\Api\Controllers\SearchProfileController;
use Matcha\Api\Controllers\TagsController;
use Matcha\Api\Controllers\ViewController;
use Matcha\Api\Middleware\AuthMiddleware;

Flight::route('GET /', function () {
    $content = file_get_contents(dirname(__DIR__) . '/composer.json');
    $data = json_decode($content, true);

    Flight::json([
        "version" => $data["version"],
        "authors" => $data["authors"],
    ]);
});

Flight::group('/auth', function () {
    Flight::route('POST /register', [RegisterController::class, 'store']);
    Flight::route('POST /login', [AuthenticatedSessionController::class, 'store']);
    Flight::route('POST /refresh', [RefreshTokenController::class, 'store']);
    Flight::route('POST /verify-token', [VerifyTokenController::class, 'store']);
});

Flight::group('/email', function () {
    Flight::route('POST /verif', [EmailController::class, 'emailVerif']);
    Flight::route('POST /token', [EmailController::class, 'verifToken']);
});

Flight::group('/forgot', function () {
    Flight::route('POST /credencial', [ForgotController::class, 'forgotCredencial']);
    Flight::route('POST /token-verify', [ForgotController::class, 'tokenVerify']);
    Flight::route('POST /change-password', [ForgotController::class, 'changePwd']);
});

Flight::group('/users', function () {

    Flight::group('/@username:[a-zA-Z0-9\.]{5,25}', function () {
        Flight::route('GET /', [ProfileController::class, 'show']);
        Flight::route('POST /like', [LikeController::class, 'store']);
        Flight::route('DELETE /unlike', [LikeController::class, 'destroy']);

        Flight::route('POST /block', [UserBlockController::class, 'store']);
        Flight::route('DELETE /unblock', [UserBlockController::class, 'destroy']);

        Flight::route('POST /view', [ViewController::class, 'store']);
    });

    Flight::group('/me', function () {
        Flight::route('PUT|PATCH /localisation', [LocalisationController::class, 'update']);

        Flight::route('GET /', [ProfileController::class, 'index']);
        Flight::route('PUT|PATCH /', [ProfileController::class, 'update']);
        Flight::route('DELETE /', ProfileController::class . 'destroy');

        Flight::route('PUT|PATCH /preferences', [PreferencesController::class, 'update']);
        Flight::route('GET /preferences', [PreferencesController::class, 'index']);

        Flight::route('GET /history', [HistoryController::class, 'index']);
        Flight::route('GET /suggestions', [ProfileSuggestionController::class, 'index']);
        Flight::route('GET /blocks', [UserBlockController::class, 'index']);

        Flight::route('POST /notifications', [NotificationsController::class, 'store']);
        Flight::route('GET /notifications', [NotificationsController::class, 'index']);

        Flight::group('/matches', function () {
            Flight::route('GET /', [ChatController::class, 'index']);
            Flight::route('GET /@username', [ChatController::class, 'show']);
            Flight::route('POST /@username', [ChatController::class, 'store']);
            Flight::route('DELETE /@username/@id', [ChatController::class, 'delete']);
        });
    });

}, [AuthMiddleware::class]);

Flight::route('GET /tags', [TagsController::class, 'index']);

Flight::group('/search', function () {
    Flight::route('GET /users', [SearchProfileController::class, 'index']);
}, [AuthMiddleware::class]);

Flight::group('/medias', function () {
    Flight::route('GET /p/@name', function (string $name) {
        header('Content-Type: image/png');

        if (!is_dir(BASE_PATH . "/storage/photos")) {
            mkdir(BASE_PATH . "/storage/photos", true);
        }

        echo file_get_contents(BASE_PATH . "/storage/photos/" . $name . ".png");
    });
});

// 404 route
Flight::map('notFound', function () {
    Flight::json([
        "message" => "Not found"
    ], 404);
});
