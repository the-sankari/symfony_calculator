<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface; // Correct Namespace
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class CalculatorController extends AbstractController
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    #[Route('/calculator', name: 'app_calculator')]
    public function index(Request $request): Response
    {
        $result = null;
        
        // Create the form using the injected form factory
        $form = $this->formFactory->createBuilder()
            ->add('number1', NumberType::class, [
                'required' => true,
                'label' => 'First Number'
            ])
            ->add('number2', NumberType::class, [
                'required' => true,
                'label' => 'Second Number'
            ])
            ->add('operation', ChoiceType::class, [
                'choices'  => [
                    'Addition' => 'add',
                    'Subtraction' => 'subtract',
                    'Multiplication' => 'multiply',
                    'Division' => 'divide',
                ],
                'label' => 'Operation'
            ])
            ->add('calculate', SubmitType::class, [
                'label' => 'Calculate'
            ])
            ->getForm();

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $number1 = $data['number1'];
            $number2 = $data['number2'];
            $operation = $data['operation'];

            switch ($operation) {
                case 'add':
                    $result = $number1 + $number2;
                    break;
                case 'subtract':
                    $result = $number1 - $number2;
                    break;
                case 'multiply':
                    $result = $number1 * $number2;
                    break;
                case 'divide':
                    if ($number2 != 0) {
                        $result = $number1 / $number2;
                    } else {
                        $result = 'Error: Division by zero';
                    }
                    break;
            }
        }

        return $this->render('calculator/index.html.twig', [
            'form' => $form->createView(),
            'result' => $result,
        ]);
    }
}
