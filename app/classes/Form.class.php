<?php

    class Form{

        public static function open($url, $attr = array()){

            $attr_text = "";

            if(!isset($attr['method'])) $attr['method'] = "POST";
            if(isset($attr['files'])) {
                $attr['enctype'] = "multipart/form-data";
            }

            if($attr){
                foreach($attr as $k => $v){
                    if($k != "files"){
                        $attr_text .= " {$k}=\"{$v}\" ";
                    }
                }
            }

            return "<form action=\"{$url}\" {$attr_text} />";

        }

        public static function close($data = array()){

            $csrf_fields = "";

            if(@$_SESSION['csrf_name']) {
                $csrf_fields .= "<input type=\"hidden\" value=\"".$_SESSION['csrf_name']."\" name=\"csrf_name\" />";
            }
            if($_SESSION['csrf_value']) {
                $csrf_fields .= "<input type=\"hidden\" value=\"".$_SESSION['csrf_value']."\" name=\"csrf_value\" />";
            }

            return $csrf_fields."</form>";

        }

        protected static function _render_input_field($type, $name, $value, $attr_array = array()){

            $attr_text = "";
            if(!isset($attr_array['id'])) $attr_array['id'] = $name;

            if($attr_array){
                foreach($attr_array as $k => $v){
                    $attr_text .= " {$k}=\"{$v}\" ";
                }
            }

            return "<input type=\"{$type}\" value=\"{$value}\" name=\"{$name}\" {$attr_text} />";
        }

        public static function text($name, $value, $attr = array()){

            if(!isset($attr['class'])) $attr['class'] = 'form-control1';
            return self::_render_input_field('text', $name, $value, $attr);

        }

        public static function file($name, $attr = array()){
            return self::_render_input_field('file', $name, "", $attr);
        }

        public static function hidden($name, $value, $attr = array()){

           return self::_render_input_field('hidden', $name, $value, $attr);

        }

        public static function password($name, $value, $attr = array()){

            if(!isset($attr['class'])) $attr['class'] = 'form-control1';
            return self::_render_input_field('password', $name, $value, $attr);

        }

        public static function submit($value, $attr = array(), $name = 'form_submit'){

           return self::_render_input_field('submit', $name, $value,
               $attr = array('type'=>"button", 'class'=>"btn btn-block btn-warning warning_11"));

        }

        public static function reset($value, $attr = array(), $name = 'form_submit'){

           return self::_render_input_field('reset', $name, $value,
               $attr = array('type'=>"button", 'class'=>"btn btn-warning warning_11"));

        }

        public static function error($field, $errors){

            if(@$errors[$field]){
                return "<p class=\"alert-danger error-p\">".$errors[$field]."</p>";
            } else {
                return null;
            }

        }

        public static function FieldValue($field, $obj, $data){
            if(@$data[$field]){
                return $data[$field];
            } else {
                return $obj->$field;
            }
        }

        public static function select($name, $selected, $values = array(), $attr_array = array()){

            $attr_text = "";
            if(!isset($attr_array['class'])) $attr_array['class'] = 'form-control1';
            if(!isset($attr_array['id'])) $attr_array['id'] = $name;

            if($attr_array){
                foreach($attr_array as $k => $v){
                    $attr_text .= " {$k}=\"{$v}\" ";
                }
            }

            $html = "<select name=\"{$name}\" {$attr_text}>";
            if($values){
                foreach($values as $k => $v){
                    $html .= "<option value=\"{$k}\"";
                    if($k == $selected) $html .= " selected=\"selected\" ";
                    $html .= ">{$v}</option>";
                }
            }
            $html .= "</select>";

            return $html;
        }

        public static function select2($name, $selected, $values = array(), $attr_array = array()){

            $attr_text = "";
            if(!isset($attr_array['class'])) $attr_array['class'] = 'form-control1';
            if(!isset($attr_array['id'])) $attr_array['id'] = $name;

            if($attr_array){
                foreach($attr_array as $k => $v){
                    $attr_text .= " {$k}=\"{$v}\" ";
                }
            }

            $html = "<select name=\"{$name}\" {$attr_text}>";
            if($values){
                foreach($values as $main_k => $main_v){
                    $html .= "<optgroup label=\"{$main_k}\">";
                        foreach($main_v as $k => $v){
                            $html .= "<option value=\"{$k}\"";
                            if($k == $selected) $html .= " selected=\"selected\" ";
                            $html .= ">{$v}</option>";
                        }
                    $html .= "</optgroup>";
                }
            }
            $html .= "</select>";

            return $html;
        }

        public static function textarea($name, $value, $attr_array = array()){

            $attr_text = "";
            if(!isset($attr_array['class'])) $attr_array['class'] = 'form-control1';
            if(!isset($attr_array['id'])) $attr_array['id'] = $name;

            if($attr_array){
                foreach($attr_array as $k => $v){
                    $attr_text .= " {$k}=\"{$v}\" ";
                }
            }

            return "<textarea name=\"{$name}\" {$attr_text}>{$value}</textarea>";

        }

    }

?>