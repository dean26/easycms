<?php


    class AppHelper{

        public static $public_patch;

        public static function Config(){

            $config = array();
            $config['displayErrorDetails'] = true;
            $config['addContentLengthHeader'] = false;
            $config['database_name'] = "slim";
            $config['server'] = "localhost";
            $config['username'] = "root";
            $config['password'] = "root";
            $config['prefix'] = "slim_";

            return $config;
        }

        public static function BaseUrl($with_file_name = false){

            $hostName = $_SERVER['HTTP_HOST'];
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

            if($with_file_name == true){
                return $protocol.$hostName.$_SERVER['SCRIPT_NAME']."/";
            } else {
                return $protocol.$hostName.dirname($_SERVER['SCRIPT_NAME'])."/";
            }
        }

        public static function SetPublicPatch($src){
            self::$public_patch = $src;
        }

        public static function PublicPatch(){
            return self::$public_patch;
        }

        public static function UrlTo($link){

            $hostName = $_SERVER['HTTP_HOST'];
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

            return $protocol.$hostName.$_SERVER['SCRIPT_NAME'].$link;
        }

        public static function setSesVar($name, $value = null){
            $_SESSION[$name] = $value;
        }

        public static function getSesVar($name){
            return $_SESSION[$name];
        }

        public static function setFlash($var, $val){
            $_SESSION['flash_'.$var] = $val;
        }

        public static function getFlash($var, $default = null){

            if(isset($_SESSION['flash_'.$var])){

                $buf = $_SESSION['flash_'.$var];
                self::DeleteSesVar('flash_'.$var);
                return $buf;

            } else {
                return ($default)? $default : null;
            }
        }

        public static function isFlash($var){
            if(isset($_SESSION['flash_'.$var])){
                return true;
            } else {
                return false;
            }
        }

        public static function DeleteSesVar($key){
            unset($_SESSION[$key]);
        }

        public static function DeleteAllSes(){
            session_destroy();
            session_start();
            session_regenerate_id(true);
        }

        public static function MemoryUsage() {
           $mem_usage = memory_get_usage();

            if ($mem_usage < 1024)
                echo $mem_usage." bytes";
            elseif ($mem_usage < 1048576)
                echo round($mem_usage/1024,2)." kilobytes";
            else
                echo round($mem_usage/1048576,2)." megabytes";

        }

        public static function createSlug($str, $replace = array(), $delimiter = '-')
        {

            if (!empty($replace)) {
                $str = str_replace((array)$replace, ' ', $str);
            }

            $str = str_replace(
                array('ś', 'ć', 'ą', 'ę', 'ł', 'ó', 'ń', 'ż', 'ź', 'Ś', 'Ć', 'Ą', 'Ę', 'Ł', 'Ó', 'Ń', 'Ż', 'Ź'),
                array('s', 'c', 'a', 'e', 'l', 'o', 'n', 'z', 'z', 'S', 'ć', 'ą', 'ę', 'l', 'ó', 'ń', 'z', 'z'),
                $str
            );

            $str = str_replace(
                array('!', ')', '(', '?', ',', '.', ':', ';', '\'', '"', '>', '<', '|', '&', '^', '%', '$', '#', '@', '+'),
                array(''),
                $str
            );

            $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
            $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
            $clean = strtolower(trim($clean, '-'));
            $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

            return $clean;

        }

        public static function cke($id, $value = "")
        {
            $html = '
        <script type="text/javascript" src="' . AppHelper::BaseUrl().('public/js/ckeditor/ckeditor.js') . '"></script>';

            $html .= '
        <script type="text/javascript">
        CKEDITOR.replace(\'' . $id . '\',  {
            filebrowserBrowseUrl: "' . AppHelper::BaseUrl().('public/js/ckfinder/ckfinder.html') . '",
            filebrowserImageBrowseUrl: "' . AppHelper::BaseUrl().('public/js/ckfinder/ckfinder.html?Type=Images') . '",
            filebrowserFlashBrowseUrl: "' . AppHelper::BaseUrl().('public/js/ckfinder/ckfinder.html?Type=Flash') . '",
            filebrowserUploadUrl: "' . AppHelper::BaseUrl().('public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') . '",
            filebrowserImageUploadUrl: "' . AppHelper::BaseUrl().('public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') . '",
            filebrowserFlashUploadUrl: "' . AppHelper::BaseUrl().('public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') . '"
        });
        </script>';

            return $html;

        }

        public static function cke_save($id, $value = "")
        {
            $html = '
        <script type="text/javascript" src="' . AppHelper::BaseUrl().('public/js/ckeditor/ckeditor.js') . '"></script>';

            $html .= '
        <script type="text/javascript">
        CKEDITOR.replace(\'' . $id . '\', {
            toolbar : "Basic"
        });
        </script>';

            return $html;

        }

        public static function passwordGenerator($length) {
            $uppercase = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'W', 'Y', 'Z');
            $lowercase = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'w', 'y', 'z');
            $number = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);

            $password = NULL;

            for ($i = 0; $i < $length; $i++) {
                $password .= $uppercase[rand(0, count($uppercase) - 1)];
                $password .= $lowercase[rand(0, count($lowercase) - 1)];
                $password .= $number[rand(0, count($number) - 1)];
            }

            return substr($password, 0, $length);
        }

        public static function ReadLogs($filename, $lines = 50, $buffer = 4096)
        {
            // Open the file
            $f = fopen($filename, "rb");

            // Jump to last character
            fseek($f, -1, SEEK_END);

            // Read it and adjust line number if necessary
            // (Otherwise the result would be wrong if file doesn't end with a blank line)
            if(fread($f, 1) != "\n") $lines -= 1;

            // Start reading
            $output = '';
            $chunk = '';

            // While we would like more
            while(ftell($f) > 0 && $lines >= 0)
            {
                // Figure out how far back we should jump
                $seek = min(ftell($f), $buffer);

                // Do the jump (backwards, relative to where we are)
                fseek($f, -$seek, SEEK_CUR);

                // Read a chunk and prepend it to our output
                $output = ($chunk = fread($f, $seek)).$output;

                // Jump back to where we started reading
                fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

                // Decrease our line counter
                $lines -= substr_count($chunk, "\n");
            }

            // While we have too many lines
            // (Because of buffer size we might have read too many)
            while($lines++ < 0)
            {
                // Find first newline and remove all text before that
                $output = substr($output, strpos($output, "\n") + 1);
            }

            // Close file and return
            fclose($f);

            $output = explode("\n", $output);
            $output = array_reverse($output);
            return $output;
        }

    }

?>