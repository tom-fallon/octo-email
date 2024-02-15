<?php

namespace Api\src\Entities;

use Api\src\Database\DbConnectionInterface;
use Api\src\Database\MysqlDatabase;
use Api\src\Messages\ErrorMessages;
use JsonSerializable;

class Contact implements JsonSerializable
{
    private int|null $id;
    private DbConnectionInterface $database;
    private string $email;
    private string $name;

    private string $created_at;


    public function __construct(DbConnectionInterface $dbConnection, string $name, string $email, int $id = null, $createdAt = null)
    {
        $this->database = $dbConnection;
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
        $this->created_at = $createdAt;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        $vars = get_object_vars($this);

        unset($vars['database']);
        return $vars;
    }

    /**
     * @param mixed $id
     * @param DbConnectionInterface|null $dbConnection
     * @return Contact|bool
     */
    public static function load(mixed $id, DbConnectionInterface $dbConnection = null): Contact|bool
    {
        if ($dbConnection == null) {
            $dbConnection = new MysqlDatabase($GLOBALS['config']['db']);
        }
        $contact = $dbConnection->load('list_contact', $id);
        if ($contact) {
            return new Contact($dbConnection, $contact['name'], $contact['email_address'], $id, $contact['created_at']);
        } else {
            return false;
        }
    }

    /**
     * @param DbConnectionInterface|null $dbConnection
     * @return array<Contact>
     */
    public static function all(DbConnectionInterface $dbConnection = null): array
    {
        if ($dbConnection == null) {
            $dbConnection = new MysqlDatabase($GLOBALS['config']['db']);
        }
        $contacts = $dbConnection->list('list_contact');
        $contactArray = [];
        foreach ($contacts as $contact) {
            $contactArray[] = new Contact($dbConnection, $contact['name'], $contact['email_address'], $contact['id'], $contact['created_at']);
        }
        return $contactArray;
    }

    /**
     * @param $data
     * @param DbConnectionInterface|null $dbConnection
     * @return array
     */
    public static function validate($data, DbConnectionInterface $dbConnection = null): array
    {
        if ($dbConnection == null) {
            $dbConnection = new MysqlDatabase($GLOBALS['config']['db']);
        }

        $errors = [];
        if (!isset($data['name'])) {
            $errors[] = 'Name is required';
        } elseif ($data['name'] == '') {
            $errors[] = 'Name is required';
        }

        if (!isset($data['email_address'])) {
            $errors[] = 'Email address is required';
        } elseif ($data['email_address'] == '') {
            $errors[] = 'Email address is required';
        } elseif (!filter_var($data['email_address'], FILTER_VALIDATE_EMAIL)) {
            $errors[] =  'Invalid email address';
        }

        $existingContact = $dbConnection->find('list_contact', 'email_address', $data['email_address']);

        if ($existingContact) {
            $errors[] = 'Email address already exists';
        }
        return $errors;
    }

    /**
     * @return string
     */
    public function save(): string
    {
        $entry = $this->database->insert('list_contact', ['name' => $this->name, 'email_address' => $this->email]);
        if ($entry == 'Success') {
            return 'Success';
        } elseif ($entry == 'Duplicate entry') {
            return ErrorMessages::duplicateEntryMessage();
        } else {
            return ErrorMessages::fatalErrorMessage();
        }
    }

    /**
     * @return string
     */
    public function delete(): string
    {
        $entry = $this->database->delete('list_contact', $this->id);
        if ($entry == 'Success') {
            return 'Success';
        } else {
            return ErrorMessages::fatalErrorMessage();
        }
    }
}
