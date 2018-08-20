<?php
namespace AppBundle\Fixtures;

use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function load (ObjectManager $Manager)
    {
        for ($i=0; $i<5; $i++) {
            $Post = new Post();
            $Manager->persist($Post);
            $Post->setText('Post content ąść <>"DSD"SAD"AS '.rand(1, 1000));

            for ($j=0; $j<5; $j++) {
                $Comment = new Comment();
                $Manager->persist($Comment);
                $Comment->setAuthorNickname('nickname '.rand(1,1000));
                $Comment->setText('Comment text ąśćąś"SA" '.rand(1, 1000));
                $Comment->setPost($Post);
            }
        }

        $Manager->flush();
    }

}
