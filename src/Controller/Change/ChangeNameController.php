<?php


namespace App\Controller\Change;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Change\ChangeNameService;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class ChangeNameController extends AbstractController
{

    private $changeNameService;

    public function __construct(ChangeNameService $changeNameService)
    {
        $this->changeNameService = $changeNameService;
    }

    #[Route('/api/change/name', name: 'ChangeName', methods: ['POST'])]
    public function changeName(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $newName = $data['newName'] ?? '';

        try{
            $isChange = $this->changeNameService->changeNameService($newName);
            if($isChange){
                return new JsonResponse($isChange, 201);
            }
        }catch (\Exception $e)
        {
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }


    }

}