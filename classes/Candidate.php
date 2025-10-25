<?php 
class Candidate{
  private $_db,
          $_data;
  public function __construct(){
    $this->_db = DB::getInstance();
  }
  public function create($fields = array()){
    if(!$this->_db->insert('candidates', fields: $fields)){
      throw new Exception(message: 'Erro ao criar candidato');
    }
  } 
  public function getCandidate($id){
    
  }

  public function getCandidates(){

  }
  public function findByUser($user_id) {
    $data = $this->_db->get('candidates', ['user_id', '=', $user_id]);
    if ($data->count()) {
        $this->_data = $data->first();
        return true;
    }
    return false;
  }

  public function update($fields = array(), $user_id = null) {
      if(!$this->_db->update('candidates', $user_id, $fields, 'user_id')) {
          throw new Exception('Erro ao actualizar dados do candidato.');
      }
  }

  public function data() {
      return $this->_data;
  }

}
?>