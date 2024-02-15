<?php
namespace Api\src\Controllers;
use Api\Response\Response;
use Api\src\Database\MysqlDatabase;
use Api\src\Entities\Contact;

class ContactController
{
    private MysqlDatabase $database;

    public function __construct()
    {
        $this->database = new MysqlDatabase($GLOBALS['config']['db']);
    }

    public function list(): Response
    {
        $contacts = Contact::all($this->database);

        return new Response(200, $contacts);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $body = file_get_contents('php://input');

        if (empty($body)) {
            return new Response(400, 'No Data Provided', ['No Data Provided']);
        }
        $data = json_decode($body, true);

        $errors = Contact::validate($data);

        if (!empty($errors)) {
            return new Response(400, implode(', ', $errors), $errors);
        }

        $contact = new Contact($this->database, $data['name'], $data['email_address']);
        $contact->save();

        return new Response(201, 'Contact Created');
    }

    /**
     * @param $arguments
     * @return Response
     */
    public function delete($arguments): Response
    {
        if (isset($arguments[0][1])) {
            $contact = Contact::load($arguments[0][1], $this->database);
            if ($contact) {
                $contact->delete();
            }

            return new Response(200, 'Contact Deleted');
        }
        return new Response(404, 'Invalid id', ['Invalid id']);
    }
}
