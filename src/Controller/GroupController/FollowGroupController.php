<?php


namespace App\Controller\GroupController;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\GroupService\FollowGroupService;
use App\Service\UsersService\GetUserInSecurityService;
use App\Service\JsonResponseService\CreateJsonResponseService;


class FollowGroupController extends AbstractController
{

    private  $followGroup;
    private $getUserInService;
    private $jsonResponseService;

    public function __construct(CreateJsonResponseService $jsonResponseService,GetUserInSecurityService $getUserInService, FollowGroupService $followGroup)
    {
        $this->jsonResponseService = $jsonResponseService;
        $this->followGroup = $followGroup;
        $this->getUserInService = $getUserInService;
    }

    #[Route('/api/follow/group{id}', name: 'FollowGroupCommand', methods: ['POST'])]
    public function follow(int $id, Request $request)
    {
        $subscriber = $this->getUserInService->getSender();

        try{
            $IsFollow = $this->followGroup->followGroup($subscriber, $id);

            if($IsFollow){
                $accToken = $IsFollow['acc'];
                return $this->jsonResponseService->createJson($IsFollow, 200, ['X-Acc-Token', $accToken]);
            }

            return new JsonResponse('не удалось подписаться', 400);
        }catch (\Exception $e){
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }

    }

}