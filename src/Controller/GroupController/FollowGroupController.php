<?php


namespace App\Controller\GroupController;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Group\FollowGroupService;


class FollowGroupController extends AbstractController
{

    private FollowGroupService $followGroup;
    private Security $security;

    public function __construct(FollowGroupService $followGroup,  Security $security)
    {
        $this->followGroup = $followGroup;
        $this->security = $security;
    }

    #[Route('/api/follow/group{id}', name: 'FollowGroupCommand', methods: ['POST'])]
    public function follow(int $id, Request $request)
    {
        $subscriber = $this->security->getUser();
        if (!$subscriber instanceof Users) {new JsonResponse("Пользователь не аутентифицирован", 400);}


        try{
            $IsFollow = $this->followGroup->followGroup($subscriber, $id);

            if($IsFollow){
                $accToken = $IsFollow['acc'];
                $response = new JsonResponse($IsFollow, 200);
                $response->headers->set('X-Acc-Token', $accToken);
                return $response;
            }

            return new JsonResponse('не удалось подписаться', 400);
        }catch (\Exception $e){
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }

    }

}