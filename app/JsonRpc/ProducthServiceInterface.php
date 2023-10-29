<?php

namespace App\JsonRpc;
interface ProducthServiceInterface
{
    public function index($limit = 10,$offset = 0,$search = "",$isDesc = "desc");
    public function show($id = null);
    public function create($name = null,$description = null,$price = null,$amount = null);
    public function update($id = null,$name = null,$description = null,$price = null);
    public function delete($id = null);
}
?>