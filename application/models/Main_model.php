<?php

class Main_model extends CI_Model
{
   public function __construct()
   {
      parent::__construct();
   }

   /**
    * Devuelve la informacuón del cliente
    */
   public function getClient(string $token): array
   {
      $this->db->select('*');
      $this->db->from('clients');
      $this->db->where('token', $token);
      $query = $this->db->get();
      $data = $query->row_array();
      return ($data) ? $data : [];
   }

   /**
    * Actualizar un concepto
    * @param int $conceptId Id del concepto
    * @param array $data Datos para actualizar
    */
   public function update(int $conceptId, array $data)
   {
      $this->db->where('id', $conceptId);
      $query = $this->db->update('eventoconcepto', $data);
      return $query;
   }
}
