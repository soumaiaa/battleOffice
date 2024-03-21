<?php

namespace App\Controller;
use Stripe\Stripe;
use App\Entity\Clients;
use App\Entity\Orders;
use App\Entity\Product;
use App\Entity\Shipping;
use App\Form\ClientsType;
use App\Repository\ClientsRepository;
use App\Repository\OrdersRepository;
use App\Repository\PaymentRepository;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    public function calculateTotalPrice(float $originalPrice, float $price): float
    {
        return $originalPrice - $price;
    }
    #[Route('/', name: 'landing_page')]
    public function index(Request $request, ProductRepository $ProductRepository, EntityManagerInterface $entityManager, PaymentRepository $paymentRepository): Response
    {
        $clients = new Clients();
        $products = $ProductRepository->findAll();
        $form = $this->createForm(ClientsType::class, $clients);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            // Récupérer les données de la requête directement
            $requestData = $request->request->all();

            // Accéder aux données de l'ordre du formulaire
            $orderData = $requestData['order'];
            $productId = $orderData['cart']['cart_products'];
            $paymentMethod = $orderData['payment_method'];
            // Maintenant, vous pouvez utiliser $formData, $requestData, $orderData comme vous le souhaitez
            // dd( $productId);   
            $address2 = $form->get('address2')->getData();
            $clients->setAddress2($address2);
            if (!$address2) {
                $shippingData = new Shipping();
                $shippingData->setAddress($clients->getAddress1());
                $shippingData->setComplementAdr($clients->getComplementAdr1());
                $shippingData->setCity($clients->getCity());
                $shippingData->setNom($clients->getLastName());
                $shippingData->setPrenom($clients->getFirstName());
                $shippingData->setCountry($clients->getCountry());
                $shippingData->setCodePostal($clients->getCodePostal());
                $shippingData->setPhone($clients->getPhone());
                $clients->setAddress2($shippingData);
            }
            // dd($address2);
            $product = $ProductRepository->findOneBy(['id' => $productId]);
            // dd($product);         
            $entityManager->persist($clients);
            $entityManager->flush();
            $this->addFlash('success', 'Profile updated successfully.');

            $orders = new Orders();
            $orders->setClient($clients);
            $orders->setProducts($product);
            $orders->setStatus('WAITING');
       
            $payment = $paymentRepository->findOneBy(['payementMethod' => $paymentMethod]);
            $orders->setPayment($payment);
            // ajouter method payment plus tard;
            $entityManager->persist($orders);
            $entityManager->flush();

            
            

            return $this->redirectToRoute('process_payment', ['id' => $orders->getId()], Response::HTTP_SEE_OTHER);
        }
        $productTotalPrices = [];
        foreach ($products as $product) {
            $originalPrice = (float) $product->getOriginalPrice();
            $price = (float) $product->getPrice();
            $productTotalPrices[$product->getId()] = $this->calculateTotalPrice($originalPrice, $price);
        }


        return $this->render('landing_page/index_new.html.twig', [
            'clients' => $clients,
            'form' => $form,
            'products' => $products,
            'productTotalPrices' => $productTotalPrices,
        ]);
    }
   

    #[Route('/confirmation', name: 'confirmation')]
    public function confirmation(): Response
    {   
        return $this->render('landing_page/confirmation.html.twig');
    }

    #[Route('/process-payment/{id}', name: 'process_payment')]
    public function processPayment(Orders $orders, Request $request)
    {
        // dd($orders);
        // Convertir le montant du produit en centimes
        $unitAmount = (int) round($orders->getProducts()->getPrice() * 100);
     
          Stripe::setApiKey($this->getParameter('stripeSecretKey'));
  
          $YOUR_DOMAIN = 'http://127.0.0.1:8000/';
  
          $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'unit_amount' => $unitAmount,
                        'product_data' => [
                            'name' => $orders->getProducts()->getName()
                        ],
                        'currency' => 'eur',
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/',
            'cancel_url' => $YOUR_DOMAIN . '/',
        ]);
          return $this->redirect($checkout_session->url);
        }

    public function Commande(Orders $orders): Response
    {
        // dd($orders);
        // Instancier le client Guzzle
        $client = new \GuzzleHttp\Client();
        // Préparer le header avec le token d'authentification
        $headers = [
            'Authorization' => 'Bearer mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX',
        ];
        // Définir une méthode de paiement par défaut si elle n'est pas définie
        $paymentMethod = $orders->getPayment() ?? 'stripe'; // Par exemple, utiliser 'paypal' par défaut

        // Envoyer la requête POST à l'API Centrale
        $response = $client->request('POST', 'https://api-commerce.simplon-roanne.com/order', [
            'headers' => $headers,
            'json' => [
                'order' => [
                    'id' =>  $orders->getId(),
                    'product' =>  $orders->getProducts()->getName(),
                    // 'payment_method' => $orders->getPayment()->getPayementMethod(),
                    'payment_method' => $paymentMethod,
                    'status' => $orders->getStatus(),
                    'client' => [
                        'firstname' => $orders->getClient()->getFirstName(),
                        'lastname' => $orders->getClient()->getLastName(),
                        'email' => $orders->getClient()->getAddress1(),
                    ],
                    'addresses' => [
                        'billing' => [
                            'address_line1' => $orders->getClient()->getFirstName(),
                            'address_line2' => $orders->getClient()->getComplementAdr1(),
                            'city' => $orders->getClient()->getCity(),
                            'zipcode' => $orders->getClient()->getCity(),
                            'country' => $orders->getClient()->getCountry()->getCountry(),
                            // 'country' => $defaultBillingCountry,
                            'phone' => $orders->getClient()->getPhone(),
                        ],
                        'shipping' => [
                            'address_line1' => $orders->getClient()->getAddress2()->getAddress(),
                            'address_line2' => $orders->getClient()->getAddress2()->getComplementAdr(),
                            'city' => $orders->getClient()->getAddress2()->getCity(),
                            'zipcode' => $orders->getClient()->getAddress2()->getCodePostal(),
                            'country' => $orders->getClient()->getAddress2()->getCountry()->getCountry(),
                            'phone' => $orders->getClient()->getAddress2()->getPhone(),
                        ]
                    ]
                ]
            ]
        ]);

        // Rediriger vers la page de paiement avec l'ID de l'Order provenant de l'API
        return $this->redirectToRoute('confirmation');
    }
   
}
