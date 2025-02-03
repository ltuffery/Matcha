<?php

namespace Matcha\Api\Model;

use Firebase\JWT\JWT;
use Flight;
use PDO;

/**
 * @method static User find(array $data)
 * @method static User[] where(array $object, ?int $limit = null)
 * @method static User morph(array $object)
 * @method static User[] all()
 * @method User save()
 * @method User create()
 * @method User update()
 */
class User extends Model
{
    protected string $table = 'users';
    protected array $uniques = [
        'username', 'email'
    ];

    public string $username;
    public string $password;
    public string $email;
    public string|null $birthday;
    public string|null $first_name;
    public string|null $last_name;
    public string|null $gender;
    public string|null $sexual_preferences;
    public string|null $biography;
    public string $created_at;
    public bool $email_verified;
    public string|null $temporary_email_token;
    public float|null $lat;
    public float|null $lon;
    public int $fame_rating = 0;

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
    public function getAvatar(): string|null
    {
        /** @var Photo $photo */
        $photo = Photo::where([
            ['user_id', '=', $this->id],
        ], 1);

        if ($photo == null) {
            return null;
        }

        return "http://" . (getenv('APP_HOST') ?? 'localhost') . ":3000/medias/p/" . $photo[0]->name;
    }

    /**
     * Create a like from the user to the user passed as a parameter
     *
     * @param User $user
     * @return void
     */
    public function like(User $user): void
    {
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

    /**
     * Get all user matches
     *
     * @return User[]
     */
    public function matches(): array
    {
        $stmt = Flight::db()->prepare("
            SELECT *
            FROM users u
            JOIN likes l1 ON u.id = l1.liked_id
            JOIN likes l2 ON l1.user_id = l2.liked_id AND l2.user_id = u.id
            WHERE l1.user_id = :user_id
        ");

        $stmt->execute(['user_id' => $this->id]);
        $users =  array_map(fn (array $obj) => User::morph($obj), $stmt->fetchAll(PDO::FETCH_ASSOC));

        return array_filter($users, fn ($user) => !$this->isBlocking($user));
    }

    /**
     * Has a correspondence with another user
     *
     * @param string username
     * @return bool
     */
    public function hasMatche(string $username): bool
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

    public function getAge(): int|null
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
