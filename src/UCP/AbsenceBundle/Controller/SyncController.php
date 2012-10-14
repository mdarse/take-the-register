<?php

namespace UCP\AbsenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Guzzle\Http\Client;
use UCP\AbsenceBundle\Entity\KVObject;

class SyncController extends Controller
{
    /**
     * @Secure("ROLE_ADMIN")
     * @Route("/sync", name="sync_index")
     * @Template
     */
    public function indexAction()
    {
        return array(
            'calendar_name' => $this->getValue('sync.calendar_name'),
            'account_email' => $this->getValue('sync.account_email')
        );
    }

    /**
     * @Secure("ROLE_ADMIN")
     * @Route("/sync/link", name="sync_link")
     */
    public function linkAction()
    {
        $url = 'https://accounts.google.com/o/oauth2/auth?';
        $queryParams = array(
            'response_type'   => 'code',
            'client_id'       => $this->getClientId(),
            'redirect_uri'    => $this->getRedirectUri(),
            'scope'           => 'https://www.googleapis.com/auth/calendar.readonly https://www.googleapis.com/auth/userinfo.email',
            // 'state'           => 'w00t',
            'access_type'     => 'offline',
            'approval_prompt' => 'force' // Replace by 'force' to get a refresh_token 
        );

        foreach ($queryParams as $key => $value) {
            $url .= '&'.urlencode($key).'='.urlencode($value);
        }

        return $this->redirect($url);
    }

    /**
     * @Secure("ROLE_ADMIN")
     * @Route("/sync/unlink", name="sync_unlink")
     */
    public function unlinkAccount()
    {
        // Remove credentials
        $toBeCleaned = array(
            'sync.access_token',
            'sync.access_token_expiration',
            'sync.refresh_token',
            'sync.account_email'
        );
        foreach ($toBeCleaned as $key) {
            $this->setValue($key, null);
        }

        // Romove authorization from Google
        $this->revokeAccess();
    }

    /**
     * @Secure("ROLE_ADMIN")
     * @Route("/sync/callback", name="sync_auth_callback")
     */
    public function callbackAction(Request $request)
    {
        // Error handling
        if ($request->query->has('error')) {
            if ('access_denied' === $request->query->get('error')) {
                // User deliberately refused access
                $message = 'You must allow access to enable calendar sync.';
            } else {
                // Unknow error
                $message = 'An error occured duing account linking.';
            }
            $this->get('session')->setFlash('error', $message);
            return $this->redirect($this->generateUrl('sync_index'));
        }

        // Exchange authorization code with an access token
        if (!$request->query->has('code')) {
            throw new HttpException(400, 'A query parameter "code" is expected.');
        }
        $this->exchangeAuthorizationCode($request->query->get('code'));

        // From here account is linked
        $this->setValue('sync.account_email', $this->getUserEmail());
        $this->get('session')->setFlash('notice', 'Your account was linked successfully!');

        return $this->redirect($this->generateUrl('sync_index'));
    }

    private function exchangeAuthorizationCode($authorizationCode)
    {
        $client = new Client('https://accounts.google.com/o/oauth2/');

        $request = $client->post('token');
        $request->addPostFields(array(
            'code'          => $authorizationCode,
            'client_id'     => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'redirect_uri'  => $this->getRedirectUri(),
            'grant_type'    => 'authorization_code'
        ));

        try {
           $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            throw new HttpException(417, 'Authentication provider did respond with an error.', $e);
        }
        $data = json_decode($response->getBody(), true);

        // Minimal response
        $this->setValue('sync.access_token', $data['access_token']);
        $this->setvalue('sync.access_token_expiration', time() + $data['expires_in']);

        if (isset($data['refresh_token'])) {
            $this->setValue('sync.refresh_token', $data['refresh_token']);
        }
    }

    private function getUserEmail()
    {
        $client = new Client();
        $request = $client->get('https://www.googleapis.com/oauth2/v1/userinfo');
        $request->addHeader('Authorization', sprintf('Bearer %s', $this->getAccessToken()));
        try {
           $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            throw new HttpException(417, 'Unable to retrieve account info.', $e);
        }
        $data = json_decode($response->getBody(), true);

        return $data['email'];
    }

    private function getAccessToken()
    {
        if (time() >= $this->getValue('sync.access_token_expiration')) {
            // throw new \Exception("OAuth2 access token expired.");
            $this->refreshAccessToken();
        }

        $accessToken = $this->getValue('sync.access_token');
        if (!$accessToken) {
            throw new \Exception("OAuth2 access token unavailable.");
            // TODO get a token
        }

        return $accessToken;
    }

    private function refreshAccessToken()
    {
        $refreshToken = $this->getValue('sync.refresh_token');
        if (!$refreshToken) {
            throw new \Exception("OAuth2 refresh token unavailable.");
        }

        $client = new Client();
        $request = $client->post('https://accounts.google.com/o/oauth2/token');
        $request->addPostFields(array(
            'refresh_token' => $refreshToken,
            'client_id'     => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'grant_type'    => 'refresh_token'
        ));
        try {
           $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            throw new HttpException(417, 'Unable to refresh access token.', $e);
        }
        $data = json_decode($response->getBody(), true);

        $this->setValue('sync.access_token', $data['access_token']);
        $this->setValue('sync.access_token_expiration', time() + $data['expires_in']);
    }

    private function revokeAccess()
    {
        $client = new Client('https://accounts.google.com/o/oauth2');
        $request = $client->get(array('revoke?token={token}', array(
            'token' => $this->getValue('sync.access_token')
        )));
        try {
           $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            throw new HttpException(417, 'Unable to revoke token.', $e);
        }

        echo($response);
    }

    private function getValue($key)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->find('UCPAbsenceBundle:KVObject', $key);

        if (!$object) {
            $object = new KVObject($key, null);
            $em->persist($object);
            $em->flush();
        }

        return $object->getValue();
    }

    private function setValue($key, $value)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->find('UCPAbsenceBundle:KVObject', $key);

        if (!$object) {
            $object = new KVObject($key, $value);
            $em->persist($object);
        } else {
            $object->setValue($value);
        }

        $em->flush();
    }

    private function getClientId()
    {
        return '597083284747-le3i0u4j39dtijsu6tvdh8ls578q3bq9.apps.googleusercontent.com';
    }

    private function getClientSecret()
    {
        return 'eykTqAQ2lPB3vB59SpBvJ3Jv';
    }

    private function getRedirectUri()
    {
        // return $this->generateUrl('sync_auth_callback', array(), true);
        return 'http://localhost:8888' . $this->generateUrl('sync_auth_callback');
    }
}
