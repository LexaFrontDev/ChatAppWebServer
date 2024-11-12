<?php


namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\VeryfiMailCode;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VeryfiMailController extends AbstractController
{
    private VeryfiMailCode $veryfiMailCode;

    public function __construct(VeryfiMailCode $veryfiMailCode)
    {
        $this->veryfiMailCode = $veryfiMailCode;
    }

    #[Route('/api/veryfiEmail', name: 'veryfiEmail', methods: ['POST'])]
    public function veryfi(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $code = $data['code'] ?? '';

        if (empty($email)) {
            return new JsonResponse('Не отправлен email', 400);
        }

        if (empty($code)) {
            return new JsonResponse('Не отправлен код', 400);
        }



        try{
            $veryfi = $this->veryfiMailCode->veryfi($email, $code);
            if ($veryfi)
                return new JsonResponse('Почта подтверждена', 201);
        }catch(\InvalidArgumentException $e){
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        };




        return new JsonResponse('Неверный код или почта', 400);
    }
}
