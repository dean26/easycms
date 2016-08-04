<?php

    class Model {

        protected $_medoo;
        protected $slim;
        protected $db_name;

        private $data = array();

        public function __construct($ins)
        {
            $this->slim = $ins;
            $this->_medoo = $ins->get('medoo');
        }

        public function getTableName(){
            return $this->db_name;
        }

        public function isError()
        {
            $error = $this->_medoo->error();
            if(isset($error[2])){
                $this->slim->logger->addError("Błąd bazy danych. ".$error[2], array(
                    'code' => $error[0],  'ip' => $_SERVER['REMOTE_ADDR']
                ));
            }
        }

        public function lastQuery()
        {
            return $this->_medoo->last_query();
        }

        public function info()
        {
            return $this->_medoo->info();

        }

        public function set_medoo($ins){
            $this->_medoo = $ins;
        }

        public function __set($name, $value)
        {
            $this->data[$name] = $value;
        }

        public function __get($name)
        {
            if (array_key_exists($name, $this->data)) {
                return $this->data[$name];
            } else{
                return null;
            }

        }

        public function __call($method, $args){

            if(!method_exists($this, $method)){
                $this->slim->logger->addError("Nie mogłem wywołać metody ".$method." klasy ".get_class($this), array(
                    'ip' => $_SERVER['REMOTE_ADDR']
                ));
            }

            $retval = call_user_func_array(array($this, $method), $args);

            $this->isError();

            return $retval;
        }

        private function select($pola, $warunki){

            $datas = $this->_medoo->select($this->db_name, $pola, $warunki);

            $buf = array();

            foreach($datas as $dat){
                $buf[] = $this->convertToObject($dat);
            }

            return $buf;

        }

        private function save(){

            if((int)@$this->data["id"] > 0){
                //update
                $this->updated_at = date('Y-m-d H:i:s');
                $this->_medoo->update($this->db_name, $this->data, ["id" => $this->data["id"]]);
            } else {
                //create
                $this->created_at = date('Y-m-d H:i:s');
                $this->updated_at = date('Y-m-d H:i:s');
                $last_user_id = $this->_medoo->insert($this->db_name, $this->data);

                $this->id = $last_user_id;
            }

        }


        private function delete($warunki){

            return $this->_medoo->delete($this->db_name, $warunki);

        }

        private function count($warunki){

            return $this->_medoo->count($this->db_name, $warunki);

        }

        protected function convertToObject($data){

            $obj = new $this();
            foreach($data as $k => $v){
                $obj->$k = $v;
            }


            return $obj;

        }

        public function getObj($class_name){

            $obj = new $class_name();

            $zmienne_obiektu = get_object_vars($this);

            foreach($zmienne_obiektu as $k => $v){
                if($v) $obj->$k = $v;
            }
            return $obj;

        }

        public function loadData($id){

            $rec = $this->_medoo->get($this->db_name, "*", ["id" => $id]);

            foreach($rec as $k => $v){
                if($v) $this->$k = $v;
            }

        }

    }

?>