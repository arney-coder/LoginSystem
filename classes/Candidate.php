<?php 
class Candidate {
    private $_db;
    private $_data;

    public function __construct($userId = null) {
        $this->_db = DB::getInstance();
        
        if($userId) {
            $this->findByUserId($userId);
        }
    }

    public function create($fields = array()) {
        if(!$this->_db->insert('candidates', $fields)) {
            throw new Exception('Erro ao criar candidato');
        }
    }

    public function find($id = null) {
        if($id) {
            $data = $this->_db->get('candidates', ['id', '=', $id]);
            if($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function findByUserId($userId = null) {
        if($userId) {
            $data = $this->_db->get('candidates', ['user_id', '=', $userId]);
            if($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function update($fields = array(), $id = null) {
        if(!$id && $this->exists()) {
            $id = $this->data()->id;
        }
        
        if(!$this->_db->update('candidates', $id, $fields)) {
            throw new Exception('Erro ao atualizar candidato');
        }
    }

    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }

    public function data() {
        return $this->_data;
    }
    public function getAllWithDetails() {
      $sql = "
          SELECT 
              c.id,
              c.user_id,
              c.first_name,
              c.last_name,
              c.full_name,
              c.email,
              c.phone_number,
              c.birthday,
              c.id_number,
              c.nuit_number,
              c.has_certificate,
              u.username,
              u.name as user_display_name,
              u.joined,
              u.group as user_group,
              prov.designation as province_name,
              dist.designation as district_name,
              sch.designation as school_name,
              crs.designation as course_name,
              del.designation as delegation_name,
              reg.designation as regime_name,
              grp.designation as group_name
          FROM candidates c
          INNER JOIN users u ON c.user_id = u.id
          LEFT JOIN province prov ON c.id_province = prov.id
          LEFT JOIN district dist ON c.id_district = dist.id
          LEFT JOIN school sch ON c.id_school_provenience = sch.id
          LEFT JOIN course crs ON c.id_course = crs.id
          LEFT JOIN delegation del ON c.id_delegation = del.id
          LEFT JOIN regime reg ON c.id_regime = reg.id
          LEFT JOIN candidate_group grp ON c.id_group = grp.id
          ORDER BY c.id DESC
      ";
      
      return $this->_db->myquery($sql)->results();
  }


  public function getWithDetails($candidateId) {
      $sql = "
          SELECT 
              c.*,
              u.username,
              u.name as user_display_name,
              u.joined,
              prov.designation as province_name,
              dist.designation as district_name,
              sch.designation as school_name,
              crs.designation as course_name,
              del.designation as delegation_name,
              reg.designation as regime_name,
              grp.designation as group_name
          FROM candidates c
          INNER JOIN users u ON c.user_id = u.id
          LEFT JOIN province prov ON c.id_province = prov.id
          LEFT JOIN district dist ON c.id_district = dist.id
          LEFT JOIN school sch ON c.id_school_provenience = sch.id
          LEFT JOIN course crs ON c.id_course = crs.id
          LEFT JOIN delegation del ON c.id_delegation = del.id
          LEFT JOIN regime reg ON c.id_regime = reg.id
          LEFT JOIN candidate_group grp ON c.id_group = grp.id
          WHERE c.id = ?
      ";
      
      $result = $this->_db->myquery($sql, [$candidateId]);
      return $result->count() ? $result->first() : null;
  }

  public function getWithDetailsByUserId($userId) {
    $sql = "
        SELECT 
            c.*,
            u.username,
            u.name as user_display_name,
            u.joined,
            prov.designation as province_name,
            dist.designation as district_name,
            sch.designation as school_name,
            crs.designation as course_name,
            del.designation as delegation_name,
            reg.designation as regime_name,
            grp.designation as group_name
        FROM candidates c
        INNER JOIN users u ON c.user_id = u.id
        LEFT JOIN province prov ON c.id_province = prov.id
        LEFT JOIN district dist ON c.id_district = dist.id
        LEFT JOIN school sch ON c.id_school_provenience = sch.id
        LEFT JOIN course crs ON c.id_course = crs.id
        LEFT JOIN delegation del ON c.id_delegation = del.id
        LEFT JOIN regime reg ON c.id_regime = reg.id
        LEFT JOIN candidate_group grp ON c.id_group = grp.id
        WHERE c.user_id = ?
    ";
    
    $result = $this->_db->myquery($sql, [$userId]);
    return $result->count() ? $result->first() : null;
}

  public function delete($candidateId = null) {
    if(!$candidateId && $this->exists()) {
        $candidateId = $this->data()->id;
    }
    
    if(!$candidateId) {
        throw new Exception('ID do candidato não fornecido');
    }
    
    // Buscar user_id antes de deletar
    $candidate = $this->_db->get('candidates', ['id', '=', $candidateId]);
    if(!$candidate->count()) {
        throw new Exception('Candidato não encontrado');
    }
    
    $userId = $candidate->first()->user_id;
    
    if(!$this->_db->delete('candidates', ['id', '=', $candidateId])) {
        throw new Exception('Erro ao deletar candidato');
    }
    
    // Deletar usuário associado
    if(!$this->_db->delete('users', ['id', '=', $userId])) {
        throw new Exception('Erro ao deletar usuário');
    }
    
    // Deletar sessões do usuário
    $this->_db->delete('users_session', ['user_id', '=', $userId]);
    
    return true;
  }
}
?>