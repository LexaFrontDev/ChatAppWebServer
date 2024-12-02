<?php


namespace App\Controller\Create;


use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;
use App\Command\Create\CreateGroupCommand;
use App\Service\TokenService;

class createGroupController extends AbstractController
{

    private Security $security;
    private CreateGroupCommand $createGroup;
    private TokenService $token;

    public function __construct( TokenService $token,CreateGroupCommand $createGroup ,Security $security)
    {
        $this->token = $token;
        $this->createGroup = $createGroup;
        $this->security = $security;
    }


    #[Route('/api/create/group', name: 'CreateGroup', methods: ['POST'])]
    public function createGroup(Request $request)
    {

        $data = json_decode($request->getContent(), true);
        $groupName = $data['groupName'] ?? '';

        $creator = $this->security->getUser();
        if (!$creator instanceof Users) {new JsonResponse("Пользователь не аутентифицирован", 400);}

        try{
            $IsCreate = $this->createGroup->create($creator->getId(), $groupName);

            if($IsCreate){
                $accToken = $this->token->createToken($IsCreate);
                $response = new JsonResponse('Группа успешно создан', 201);
                $response->headers->set('X-Acc-Token', $accToken);
                return $response;
            }

            return new JsonResponse('Не удалость создать группу', 406);
        }catch (\Exception $e){
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }

    }
}