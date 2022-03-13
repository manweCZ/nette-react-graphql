<?php
//
//namespace App\ApiModule;
//
//use App\Api\GraphQL\Exceptions\AuthorizationFailedException;
//use App\Model\Orm\User;
//use App\Orm;
//use Exception;
//use GraphQL\Error\FormattedError;
//use Jose\Component\KeyManagement\JWKFactory;
//use Jose\Easy\Load;
//use Tracy\Debugger;
//
//abstract class ApiPrivatePresenter extends ApiBasePresenter
//{
//    public ApiUser $apiUser;
//
//    public function __construct(Orm $orm)
//    {
//        parent::__construct();
//        $this->orm = $orm;
//    }
//
//    public function startup()
//    {
//        parent::startup();
//
//        if ($this->getHttpRequest()->getHeader('Device-Type') &&
//            $this->getHttpRequest()->getHeader('Device-Type')[0] === 'mobile')
//        {
//            Debugger::$productionMode = true;
//        }
//
//        if(strpos($this->getHttpRequest()->getRawBody(), 'IntrospectionQuery ') !== false) {
//            Debugger::$productionMode = true;
//        } else {
//            if (!$this->getHttpRequest()->getHeader('Authorization') ||
//                empty($this->getHttpRequest()->getHeader('Authorization')[0]))
//            {
//                $this->sendJson(['errors' => [
//                    FormattedError::createFromException(
//                        new AuthorizationFailedException('exception.auth.authorization_header_missing')
//                    )
//                ]]);
//            } else {
//                $basicAuthentication = $this->getHttpRequest()->getHeader('Authorization');
//                list($token) = sscanf($basicAuthentication, "Bearer %s");
//                if (empty($token)) {
//                    $this->sendJson(
//                        ['errors' => [
//                            FormattedError::createFromException(
//                                new AuthorizationFailedException('exception.auth.bearer_authorization_token_expected')
//                            )
//                        ]]
//                    );
//                } else {
//                    $jwk = JWKFactory::createFromKeyFile(__APP_DIR__ . '/../secret/public.pem');
//                    try {
//
//                        $jwt = Load::jws($token) // We want to load and verify the token in the variable $token
//                        ->algs(['RS256', 'RS512']) // The algorithms allowed to be used
//                        ->iss('oshaudit') // Allowed issuer
//                        ->key($jwk) // Key used to verify the signature
//                        ->exp()
//                        ->run(); // Go!
//
//                        $userId = $jwt->claims->get('user')['userId'];
//
//                        /** @var User $user */
//                        $user = $this->orm->users->getById($userId);
//                        $userIdentity = $this->orm->users->getUserIdentity($user);
//
//                        if ($user) {
//                            $this->apiUser = new ApiUser($this->orm->users, new Acl());
//                            $this->apiUser->login($userIdentity);
//                        } else {
//                            throw new Exception("exception.auth.token_has_invalid_user");
//                        }
//
//                    } catch (Exception $exception) {
//                        $this->sendJson(
//                            ['errors' => [
//                                FormattedError::createFromException(
//                                    new AuthorizationFailedException($exception->getMessage())
//                                )
//                            ]]
//                        );
//                    }
//                }
//            }
//        }
//    }
//
//    public function beforeRender()
//    {
//        parent::beforeRender();
//    }
//}
