<?php

namespace App\Model;

use App\connect\bdd;

class ContactsModel
{
    public function getAllContacts($page=1, $rowcount=10){

        $offset=($rowcount*$page)-$rowcount;
        $pdo= (new bdd)->connect();
        $sql = $pdo->prepare("select contacts.name AS Name,email AS Email,phone AS Phone, companies.name AS Company ,contacts.created_at AS `Created at` from contacts  LEFT JOIN companies ON contacts.company_id = companies.id  LIMIT :limit OFFSET :offset");
        $sql->bindParam(':limit', $rowcount, \PDO::PARAM_INT);
        $sql->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $sql->execute();
        $sqlrow = $pdo->prepare("SELECT COUNT(id) as Count FROM contacts");
        $sqlrow->execute();
        $pdo = null;
        return array("name" => "All contacts", "datas" => $sql->fetchAll(\PDO::FETCH_CLASS), "rows" => $sqlrow->fetch());
    }

    public function getInvoiceById($id){
        $pdo= (new bdd)->connect();
        $sql = $pdo->prepare('SELECT * FROM invoices LEFT JOIN companies ON invoices.id_company WHERE invoices.id = :id');
        $sql->bindParam(':id', $id, \PDO::PARAM_INT);
        $sql->execute();
        $pdo=null; //close the connection before return
        return $sql->fetch();
    }
}