<?php
namespace App\Controller;

use Slim\Views\PhpRenderer;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Model\User;
use Respect\Validation\Validator as V;
use App\Helper\Validation\Validator;
use Illuminate\Database\Capsule\Manager;
use App\Helper\Image;
use Monolog\Logger;

class UserController
{
    private $db;
    private $renderer;
    private $imageHelper;
    private $validator;
    private $logger;

    public function __construct(Manager $db, PhpRenderer $renderer, Image $imageHelper, Validator $validator, Logger $logger)
    {
        $this->db = $db;
        $this->renderer = $renderer;
        $this->imageHelper = $imageHelper;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    public function index(Request $request, Response $response, $args) 
    {
        $countries = $this->db->table('countries')->get();
        $this->renderer->render($response, 'index.phtml', array('countries' => $countries));
        return $response;
    }

    public function register($request, $response, $args)
    {
        $countries = $this->db->table('countries')->get();

        $responseData = [];
        $countryCode = $request->getParam('country_code') != 'undefined' ? $request->getParam('country_code') : null;

        if ($countryCode) {
            $countryValidation = V::notEmpty()->postalCode($countryCode);
        } else {
            $countryValidation = V::notEmpty();
        }

        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['profile_picture'];

        $validation = $this->validator->validate($request, [
            'email' => V::noWhitespace()->notEmpty()->email(),
            'name' => V::notEmpty()->alpha(),
            'first_address' => V::notEmpty(),
            'country' => V::notBlank(),
            'eircode' => $countryValidation,
            'password' => V::noWhitespace()->alnum()->notEmpty()->length(6, null),
            'confirm_password'  => V::notEmpty()->confirmPassword($request->getParam('password')),
            'profile_picture' => V::existsUploadImage($uploadedFile)->sizesUploadImage($uploadedFile, $this->imageHelper->imageSettings['image_sizes'])
        ]);

        $partialRegistrationForm = $this->renderer->fetch('register-form.phtml', ['errors' => $_SESSION['errors'], 'countries' => $countries, 'params' => $request->getParams()]);

        if ($validation->failed()) {
            $responseData = array('success' => false, 'message' => 'The form has errors!', 'form' => $partialRegistrationForm);
            
            return $response
                ->withHeader('Content-type','application/json')
                ->write(json_encode($responseData));
        }
  
        $imageFilename = $this->imageHelper->saveNewImage($uploadedFile);

        try {
            $user = User::firstOrCreate([
                'email' => $request->getParam('email')
            ],
            [
                'name' => $request->getParam('name'),
                'first_address' => $request->getParam('first_address'),
                'second_address' => $request->getParam('second_address'),
                'eircode' => $request->getParam('eircode'),
                'country_id' => $request->getParam('country'),
                'password' => password_hash($request->getParam('password'), PASSWORD_BCRYPT)
            ]);

            if ($user) {
                $image = $user->images()->create([
                    'filename' => $imageFilename,
                    'profile_picture' => true
                ]);
            }

            $partialRegistrationForm = $this->renderer->fetch('register-form.phtml', ['errors' => null, 'countries' => $countries]);

            $responseData = array('success' => true, 'message' => 'You were registered successfully!', 'form' => $partialRegistrationForm);
            $this->logger->info("The user with id $user->id was registered successfully.");
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
            $responseData = array('success' => false, 'message' => 'Something went wrong with the registration. Please try again!', 'form' => $partialRegistrationForm, 'params' => $request->getParams());
        }

        return $response
            ->withHeader('Content-type','application/json')
            ->write(json_encode($responseData));
    }
}