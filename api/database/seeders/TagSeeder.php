<?php

namespace Matcha\Database\Seeders;

use Matcha\Api\Model\User;
use Matcha\Api\Model\Tag;
use Matcha\Api\Model\UserTag;

class TagSeeder implements SeederInterface
{
    private function fillTagsTable(): void
    {
        $tags = json_decode(file_get_contents(dirname(__DIR__)."/data_preset/tags.json"));

        foreach ($tags as $tag){
            $newTag = new Tag();
            $newTag->name = $tag;
            $newTag->save();
        }
    }

    public function run(): void
    {
        $users = User::all();
        $tags = Tag::all();

        if (count($tags) == 0){
            $this->fillTagsTable();
            $tags = Tag::all();
        }

        for ($i = 0; $i < count($users); $i++) {
            $elements = faker()->randomElements($tags, rand(0, count($tags)));
            foreach ($elements as $el){
                $newTag = new UserTag();
                $newTag->user_id = $users[$i]->id;
                $newTag->tag_id = $el->id;
                try{
                    $newTag->save();
                }catch(\Exception){}
            }
        }
    }
}
