<?php

class Main_model extends CI_Model
{
   public function __construct()
   {
      parent::__construct();
   }

   /**
    * Devuelve la información del cliente
    */
   public function getClient(string $token): array
   {
      $this->db->select('*');
      $this->db->from('client');
      $this->db->where('token', $token);
      $query = $this->db->get();
      $data = $query->row_array();
      return ($data) ? $data : [];
   }
}
