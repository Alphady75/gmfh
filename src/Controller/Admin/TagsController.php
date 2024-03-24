<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{
    
    public function __construct(private EntityManagerInterface $entityManager, private TagRepository $tagRepository, private ArticleRepository $articleRepository)
    {
    }

    #[Route('/admin/tags/ajax/add/{label}/{articleID}', name: 'user_tag_add', methods: ['POST'])]
    public function add(string $label, $articleID): JsonResponse
    {
        $check = $this->tagRepository->findOneBy([
            'name' => strtolower($label),
        ]);

        if ($check == null) {
            $tag = new Tag();
            $tag->setName(strtolower($label));
            $tag->setSlug(strtolower($label));
            $tag->setArticle($this->articleRepository->find($articleID));
            $this->entityManager->persist($tag);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $tag->getId()
            ]);
        } 
    }
}
