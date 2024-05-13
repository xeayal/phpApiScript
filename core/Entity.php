<?php 
namespace core;

class Entity extends Database{
    public $table;

    /**
     * @return array
     */
    public function all()
    {
        return $this->select($this->table);
    }

    /**
     * @return array
     */
    public function find($id)
    {
        return $this->select($this->table, ['id' => $id]);
    }

    /**
     * @return array
     */
    public function findBy($condition)
    {
        return $this->select($this->table, $condition);
    }

    /**
     * @return single array | null
     */
    public function findOneBy($condition)
    {
        return $this->select($this->table, $condition)[0];
    }

    /**
     * @return boolean
     */
    public function save($data)
    {
        try {
            if(isset($data['id'])){
                $id = $data['id'];
                unset($data['id']);
                return $this->update($this->table, $data, ['id' => $id]);
            }else {
                return $this->insert($this->table, $data);
            }
        }catch (\Exception $e){
            print_r($e);
        }
    }
}