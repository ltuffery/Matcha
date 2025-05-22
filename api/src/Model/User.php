<?php

namespace Matcha\Api\Model;

use Firebase\JWT\JWT;
use Flight;
use Matcha\Api\Builder\JoinBuilder;
use Matcha\Api\Exceptions\AutoLikeException;
use Matcha\Api\Validator\Asserts\Email;
use Matcha\Api\Validator\Asserts\Minimum;
use Matcha\Api\Validator\Asserts\NotBlank;
use Matcha\Api\Validator\Asserts\Regex;
use PDO;

/**
 * @method static User find(array $data)
 * @method static User morph(array $object)
 * @method static User[] all()
 * @method User save()
 * @method User update()
 */
class User extends Model
{
    protected string $table = 'users';
    protected array $uniques = [
        'username', 'email'
    ];

    #[Regex('[a-zA-Z0-9\.]{5,25}')]
    public string $username;

    #[NotBlank()]
    public string $password;

    #[Email()]
    public string $email;

    #[NotBlank()]
    public string $birthday;

    #[NotBlank()]
    public string $first_name;

    #[NotBlank()]
    public string $last_name;

    #[NotBlank()]
    public string $gender;

    public ?string $biography;

    #[NotBlank()]
    public string $created_at;

    public bool $email_verified;

    public ?string $temporary_email_token;

    #[Minimum(0)]
    public int $fame_rating = 0;

    public string $last_connection;

    public function generateJWT(): string
    {
        // 600 equals 10 minutes
        return $this->encodeJWT([
            'username' => $this->username,
        ], 600);
    }

    public function generateRefreshJWT(string $ip): string
    {
        // 2629743 equals ~1 month
        return $this->encodeJWT([
            'username' => $this->username,
            'ip' => $ip,
        ], 2629743);
    }

    private function encodeJWT(array $data, int $exp): string
    {
        $time = time();
        $merge = array_merge($data, [
            'exp' => $time + $exp,
            'iat' => $time,
        ]);

        return JWT::encode($merge, getenv('SECRET_KEY'), 'HS256');
    }

    /**
     * Get user avatar (first image upload)
     */
    public function getAvatar(): ?string
    {
        $photo = Photo::where([
            ['user_id', '=', $this->id],
        ])->limit(1)->get();

        if ($photo == null) {
            return null;
        }

        return "http://" . (getenv('APP_HOST') ?? 'localhost') . ":3000/medias/p/" . $photo->name;
    }

    /**
     * Create a like from the user to the user passed as a parameter
     *
     * @param User $user
     * @return void
     * @throws AutoLikeException
     */
    public function like(User $user): void
    {
        if ($this->id === $user->id) {
            throw new AutoLikeException();
        }

        $like = new Like();

        $like->user_id = $this->id;
        $like->liked_id = $user->id;

        $like->save();
    }

    public function unlike(User $user): void
    {
        $like = Like::find([
            'user_id' => $this->id,
            'liked_id' => $user->id,
        ]);

        if (!is_null($like)) {
            $like->delete();
        }
    }

    public function likedBy(User|string $user): bool
    {
        if (is_string($user)) {
            $user = self::find($user);
        }

        $like = Like::find([
            'user_id' => $user->id,
            'liked_id' => $this->id,
        ]);

        return !is_null($like);
    }

    /**
     * Get all the likes that the user has made
     *
     * @return Like[]
     */
    public function likes(): array
    {
        return Like::all([
            'user_id' => $this->id,
        ]);
    }

    public function view(User $user): void
    {
        $view = new View();

        $view->user_id = $this->id;
        $view->viewed_id = $user->id;

        $view->save();
    }

    public function report(User $user, string $raison): void
    {
        $report = new Report();

        $report->user_id = $this->id;
        $report->reported_id = $user->id;
        $report->raison = $raison;

        $report->save();
    }

    public function hasReport(User $user): bool
    {
        $report = Report::find([
            'user_id' => $this->id,
            'reported_id' => $user->id,
        ]);

        return !is_null($report);
    }

    public function views(): array
    {
        return View::all([
            'user_id' => $this->id,
        ]);
    }

    /**
     * Get all user matches
     *
     * @return User[]
     */
    public function matches(): array
    {
        $users = $this::where(['l1.user_id', '=', $this->id])
            ->join('likes l1', function (JoinBuilder $builder) {
                $builder->and('users.id', '=', 'l1.liked_id');
            })
            ->join('likes l2', function (JoinBuilder $builder) {
                $builder
                    ->and('l1.user_id', '=', 'l2.liked_id')
                    ->and('l2.user_id', '=', 'users.id');
            })
            ->get(array: true);

        return array_filter($users, fn ($user) => !$this->isBlocking($user));
    }

    /**
     * Has a correspondence with another user
     *
     * @param string username
     * @return bool
     */
    public function hasMatch(string $username): bool
    {
        $matches = $this->matches();

        $filter = array_filter($matches, fn (User $value) => $value->username == $username);

        return count($filter) > 0;
    }

    public function getPhotosUrl(): array
    {
        $photos = Photo::all([
            'user_id' => $this->id,
        ]);

        $host = getenv('APP_HOST') ?: "localhost";

        return array_map(fn (Photo $photo) => "http://" . $host .  ":3000/medias/p/" . $photo->name, $photos);
    }

    public function getAge(): ?int
    {
        if (is_null($this->birthday)) {
            return null;
        }

        $birthDate = explode("-", $this->birthday);

        return (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md")
            ? ((date("Y") - $birthDate[0]) - 1)
            : (date("Y") - $birthDate[0]));
    }

    /**
     * Add new tag
     * @param string $name
     * @return void
     */
    public function addTag(string $name): void
    {
        $stmt = Flight::db()->prepare("
            INSERT INTO user_tags(`user_id`, `tag_id`)
            VALUES (:user_id, (SELECT tags.id FROM tags WHERE tags.name = :name));
        ");

        $stmt->execute(['user_id' => $this->id, 'name' => $name]);
    }

    public function removeTag(string $name): void
    {
        $stmt = Flight::db()->prepare("
            DELETE FROM user_tags
            WHERE `user_id` = :user_id 
              AND `tag_id` = (SELECT tags.id FROM tags WHERE tags.name = :name);
        ");

        $stmt->execute(['user_id' => $this->id, 'name' => $name]);
    }

    public function getTags(): array
    {
        $stmt = Flight::db()->prepare("
            SELECT ut.tag_id, t.name
            FROM user_tags ut
            JOIN tags t ON ut.tag_id = t.id
            WHERE ut.user_id = :user_id;
        ");

        $stmt->execute(['user_id' => $this->id]);

        $tags = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $tag) {
            $tags[] = $tag['name'];
        }

        return $tags;
    }

    public function block(User $user): void
    {
        $block = new Block();

        $block->user_id = $this->id;
        $block->blocked_id = $user->id;

        $block->save();
    }

    public function unblock(User $user): bool
    {
        $block = Block::find([
            'user_id' => $this->id,
            'blocked_id' => $user->id,
        ]);

        if (!is_null($block)) {
            $block->delete();
            return true;
        }

        return false;
    }

    public function isBlocking(User $user): bool
    {
        $block = Block::find([
            'user_id' => $this->id,
            'blocked_id' => $user->id,
        ]);

        return !is_null($block);
    }

    public function getPreferences(): Preference
    {
        return Preference::find([
            'user_id' => $this->id,
        ]);
    }


    public function getNotifications(): array
    {
        return Notification::all([
            'user_id' => $this->id,
        ]);
    }

    public static function authenticate(string $username, string $password): User|false
    {
        $user = User::find([
            'username' => $username,
        ]);

        if (!is_null($user) && password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }
}
