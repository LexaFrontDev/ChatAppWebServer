<?php


namespace App\Controller\GroupController;


use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;
use App\Command\Create\CreateGroupCommand;
use App\Service\AuthService\TokenService;
use App\Service\UsersService\GetUserInSecurityService;
use App\Service\JsonResponseService\CreateJsonResponseService;

class createGroupController extends AbstractController
{

    private Security $security;
    private CreateGroupCommand $createGroup;
    private TokenService $token;
    private $getUsersInService;
    private $sendJson;


    public function __construct(CreateJsonResponseService $sendJson,GetUserInSecurityService $getUsersInService,TokenService $token,CreateGroupCommand $createGroup ,Security $security)
    {
        $this->sendJson = $sendJson;
        $this->getUsersInService = $getUsersInService;
        $this->token = $token;
        $this->createGroup = $createGroup;
        $this->security = $security;
    }


    #[Route('/api/create/group', name: 'CreateGroup', methods: ['POST'])]
    public function createGroup(Request $request)
    {

        $data = json_decode($request->getContent(), true);
        $groupName = $data['groupName'] ?? '';
        $description = $data['description'] ?? '';


        $creator = $this->getUsersInService->getSender();

        try{
            $IsCreate = $this->createGroup->create($creator->getId(), $groupName, $description);

            if($IsCreate){
                $accToken = $this->token->createToken($IsCreate);
                return $this->sendJson->createJson(['Группа успешно создан'], 201, ['X-Acc-Token' => $accToken]);
            }

            return new JsonResponse('Не удалость создать группу', 406);
        }catch (\Exception $e){
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }

    }
}