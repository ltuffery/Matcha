<?php

namespace Matcha\Api\Model;

use Firebase\JWT\JWT;
use Flight;
use Matcha\Api\Builder\JoinBuilder;
use Matcha\Api\Exceptions\AutoLikeException;
use Matcha\Api\Exceptions\AutoPassException;
use Matcha\Api\Resources\ProfileResource;
use Matcha\Api\Validator\Asserts\Email;
use Matcha\Api\Validator\Asserts\Minimum;
use Matcha\Api\Validator\Asserts\NotBlank;
use Matcha\Api\Validator\Asserts\Regex;
use PDO;
use DateTimeImmutable;

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

        return "https://" . trim(getenv('APP_HOST') ?? 'localhost', '/') . "/api/medias/p/" . $photo->name;
    }

    /**
     * Create a pass from the user to the user passed as a parameter
     *
     * @param User $user
     * @return void
     */
    public function pass(User $user): void
    {
        if ($this->id === $user->id) {
            throw new AutoPassException();
        }

        $pass = new Pass();
        $pass->user_id = $this->id;
        $pass->target_id = $user->id;

        $pass->save();
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

        return array_map(fn (Photo $photo) => "https://" . trim($host, '/') .  "/api/medias/p/" . $photo->name, $photos);
    }

    public function getAge(): ?int
    {
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
    /**
     * Suggested profiles for this user, based on their stored preferences.
     *
     * @param string|null $sort  distance|fame|common_tags|age, null for the default order
     * @param string $order      asc|desc (ignored when $sort is null)
     * @return array
     */
    public function suggestions(?string $sort, string $order, int $offset, int $limit): array
    {
        $prefs = Preference::find(['user_id' => $this->id]);

        // --- Paramètres calculés une seule fois ---
        $distMax = (int) $prefs->distance_maximum;
        $lat     = (float) $prefs->lat;
        $lon     = (float) $prefs->lon;

        $today    = new DateTimeImmutable('today');
        $ageMin   = (int) ($prefs->age_minimum ?? 18);
        $ageMax   = (int) ($prefs->age_maximum ?? 100);
        $birthMax = $today->modify("-{$ageMin} years")->format('Y-m-d');
        $birthMin = $today->modify('-' . ($ageMax + 1) . ' years')->format('Y-m-d');

        $deltaLat = $distMax / 111.2;
        $deltaLon = $distMax / (111.2 * max(cos(deg2rad($lat)), 0.01));

        // fame_gap = 0 signifie "désactivé"
        $fameGap = (int) $prefs->fame_gap > 0 ? (int) $prefs->fame_gap : 1000000;

        $fetch = $limit + 1;

        $dir = $order === 'asc' ? 'ASC' : 'DESC';
        $orderBy = match ($sort) {
            'distance'    => "distance_km $dir, id ASC",
            'fame'        => "fame_rating $dir, id ASC",
            'common_tags' => "common_tags $dir, id ASC",
            'age'         => 'birthday ' . ($order === 'asc' ? 'DESC' : 'ASC') . ', id ASC',
            default       => $prefs->by_tags
                ? 'tier ASC, common_tags DESC, fame_rating DESC, id ASC'
                : 'tier ASC, fame_rating DESC, id ASC',
        };

        $sql = "
            WITH candidates AS (
                SELECT
                    u.*,
                    ST_Distance_Sphere(POINT(p.lon, p.lat), POINT(:me_lon, :me_lat)) / 1000 AS distance_km,
                    (SELECT COUNT(*) FROM user_tags ut
                       JOIN user_tags mine ON mine.tag_id = ut.tag_id AND mine.user_id = :me_id_tags
                      WHERE ut.user_id = u.id) AS common_tags
                FROM users u
                JOIN preferences p ON p.user_id = u.id
                WHERE u.id <> :me_id
                  AND u.email_verified = 1
                  AND (:me_pref = 'A' OR :me_pref2 = u.gender)
                  AND (p.sexual_preferences = 'A' OR p.sexual_preferences = :me_gender)
                  AND u.birthday <= :birth_max
                  AND u.birthday >  :birth_min
                  AND p.lat BETWEEN :lat_min AND :lat_max
                  AND p.lon BETWEEN :lon_min AND :lon_max
                  AND ABS(u.fame_rating - :me_fame) <= :fame_gap
                  AND NOT EXISTS (SELECT 1 FROM likes l
                                   WHERE l.user_id = :me_id_l AND l.liked_id = u.id)
                  AND NOT EXISTS (SELECT 1 FROM passes ps
                                   WHERE ps.user_id = :me_id_p AND ps.target_id = u.id
                                     AND ps.created_at > NOW() - INTERVAL 7 DAY)
                  AND NOT EXISTS (SELECT 1 FROM user_blocked b
                                   WHERE (b.user_id = :me_id_b1 AND b.blocked_id = u.id)
                                      OR (b.user_id = u.id AND b.blocked_id = :me_id_b2))
            )
            SELECT
                *,
                ROUND(distance_km) AS distance_km,
                CASE WHEN distance_km < 10 THEN 0
                     WHEN distance_km < 50 THEN 1
                     ELSE 2 END AS tier
            FROM candidates
            WHERE distance_km <= :dist_max
            ORDER BY $orderBy
            LIMIT $fetch OFFSET $offset
        ";

        $stmt = Flight::db()->prepare($sql);
        $stmt->execute([
            'me_lon'      => $lon,
            'me_lat'      => $lat,
            'me_id_tags'  => $this->id,
            'me_id'       => $this->id,
            'me_pref'     => $prefs->sexual_preferences,
            'me_pref2'    => $prefs->sexual_preferences,
            'me_gender'   => $this->gender,
            'birth_max'   => $birthMax,
            'birth_min'   => $birthMin,
            'lat_min'     => $lat - $deltaLat,
            'lat_max'     => $lat + $deltaLat,
            'lon_min'     => $lon - $deltaLon,
            'lon_max'     => $lon + $deltaLon,
            'me_fame'     => $this->fame_rating,
            'fame_gap'    => $fameGap,
            'me_id_l'     => $this->id,
            'me_id_p'     => $this->id,
            'me_id_b1'    => $this->id,
            'me_id_b2'    => $this->id,
            'dist_max'    => $distMax,
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if (empty($rows)) {
            return [];
        }

        $hasMore = count($rows) > $limit;
        $rows = array_slice($rows, 0, $limit);

        $rows = array_map(fn ($r) => User::morph($r), $rows);

        return ['profiles' => ProfileResource::collection($rows), 'has_more' => $hasMore];
    }
}
