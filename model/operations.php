<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
function connect()
{
    if($_SERVER['HTTP_HOST'] == 'localhost') {
    return mysqli_connect("localhost", "root", "", "busia");
}else{
    return mysqli_connect("localhost", "vesencom_busia", "8j23@$nP&YEv2tN", "vesencom_busia");
}
}
function security($name) //handling and sanitizing form data
{
    if(!isset($_POST[$name])){
        return "";
    }
    $conn = connect();
    $string = trim($_POST[$name]);

    if ($name == 'email') {
        $email = filter_var($string, FILTER_SANITIZE_EMAIL);
        $string = filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    $value = escape($string);

    return mysqli_real_escape_string($conn, $value); //protects from sql injection
}
function security_get($name)
{
    if(!isset($_GET[$name])){
        return "";
    }
    $conn = connect();
    $string = trim($_GET[$name]);

    if ($name == 'email') {
        $email = filter_var($string, FILTER_SANITIZE_EMAIL);
        $string = filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    $value = escape($string);

    return mysqli_real_escape_string($conn, $value); //protects from sql injection
}
function escape($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8'); //protects from xss
}

function select_rows($sql)
{
    $conn = connect();
    $res = mysqli_query($conn, $sql);
    $result = array();
    while ($row = $res->fetch_assoc()) {
        $result[] = $row;
    }
    return $result;
}



function insert_delete_edit($sql)
{
    $conn = connect();
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    echo mysqli_error($conn);
    return false;
}



function delete($table, $id)
{
    $conn = connect();
    $sql = "delete from `$table` where id = '$id'";
    if (insert_delete_edit($sql)) {
        return true;
    } else {
        return false;
    }
}

function build_sql_insert($table, $elements)
{
    $sql = "insert into `$table` ( ";
    $i = sizeof($elements);
    $j = 0;
    foreach ($elements as $key => $value) {
        $j++;
        if ($i != $j) {
            $sql .= "`$key`" . ",";
        } else {
            $sql .= "`$key`";
        }
    }
    $j = 0;
    $sql .= ") values (";
    foreach ($elements as $value) {
        $j++;
        if ($i != $j) {
            $sql .= "'$value'" . ",";
        } else {
            $sql .= "'$value'";
        }
    }
    $sql .= ")";
    return insert_delete_edit($sql);
}

function build_sql_edit($table, $elements, $id)
{
    $sql = "update `$table` set ";
    $i = sizeof($elements);
    $j = 0;
    foreach ($elements as $key => $value) {
        $j++;
        if ($i != $j) {
            $sql .= "`$key`" . " = " . "'$value',";
        } else {
            $sql .= "`$key`" . " = " . "'$value'";
        }
    }

    $sql .= "where `id` = '$id'";
    return insert_delete_edit($sql);
}

function input($label, $name, $type = "text", $required = false,$decimal = false)
{
?>
    <div class="form-group">
        <label><?php echo $label ?></label>
        <input type="<?php echo $type ?>"
               <?php
               if($decimal){
               ?>
                   step = "0.01"
                       <?php } ?>

               id="<?php echo $name ?>" id="<?php echo $name ?>" class="form-control" <?php echo $required ? 'required' : '' ?> name="<?php echo $name ?>">
    </div>
<?php
}

function input_hybrid($label, $name, $row = array(), $required = true, $type = "text",$decimal = false)
{
?>
    <div class="form-group">
        <label><?php echo $label ?></label>
        <input id="<?php echo $name ?>"
            <?php
            if($decimal){
                ?>
                step = "0.01"
            <?php } ?>
               type="<?php echo $type ?>" <?php echo $required ? 'required' : '' ?> value="<?php echo !empty($row) ? $row[$name] : '' ?>" class="form-control" name="<?php echo $name ?>">
    </div>
<?php
}

function input_array($label, $name, $type = "text")
{
?>
    <div class="form-group">
        <label><?php echo $label ?></label>
        <input type="<?php echo $type ?>" class="form-control" id="<?= $name ?>" name="<?php echo $name ?>[]">
    </div>
<?php
}

function submit()
{
?>
    <div class="card-footer text-right">
        <button class="btn btn-primary">submit</button>
    </div>
<?php
}

function nextw()
{
?>
    <div class="card-footer text-right">
        <button class="btn btn-primary" onclick="mynext()" type="button">next</button>
    </div>
    <?php
}

function upload($name2)
{
    if (isset($_FILES[$name2]["name"]) && $_FILES[$name2]["name"] != '') {

        $target_dir = "../uploads/";
        $rand = rand(1000, 9000);
        $name = basename($_FILES[$name2]["name"]);
        $tmpname = $_FILES[$name2]["tmp_name"];
        $extension = explode(".", $name);
        $extension = end($extension);
        $filename = date("Ymjhis") . $rand;
        $image_path = $target_dir . $filename . "." . $extension;
        if (check_ext($extension)) {
            if (move_uploaded_file($tmpname, $image_path)) {
                return $filename . "." . $extension;
            } else {
                echo error_get_last()['message'];
                exit();
            }
        } else {
            return false;
        }
    } else {

        return false;
    }
}

function upload_docs($name2)
{
    if (isset($_FILES[$name2]["name"]) && $_FILES[$name2]["name"] != '') {

        $target_dir = "../documents/";
        $rand = rand(1000, 9000);
        $name = basename($_FILES[$name2]["name"]);
        $tmpname = $_FILES[$name2]["tmp_name"];
        $extension = explode(".", $name);
        $extension = end($extension);
        $filename = date("Ymjhis") . $rand;
        $image_path = $target_dir . $filename . "." . $extension;
        if (check_ext_doc($extension)) {
            if (move_uploaded_file($tmpname, $image_path)) {
                return $filename . "." . $extension;
            } else {
                echo error_get_last()['message'];
                exit();
            }
        } else {
            return false;
        }
    } else {

        return false;
    }
}
function check_ext($ext)
{
    if ($ext == "png" || $ext == "jpg" || $ext == "jpeg") {
        return true;
    } else {
        return false;
    }
}
function check_ext_doc($ext)
{
    $doc_array = [
            'pdf','xls','png','jpeg','png','docs','docx','xlxs','csv','ppt','txt'
    ];
    if (in_array($ext,$doc_array)) {
        return true;
    } else {
        return false;
    }
}
function insert_edit_form($table_name)
{
    if (isset($_POST)) {
        $arr = array();
        foreach ($_POST as $key => $value) {
            $arr[$key] = security($key);
        }
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            unset($_POST['id']);
            return   build_sql_edit($table_name, $arr, $id);

        } else {
            return build_sql_insert($table_name, $arr);
        }
    }
    return false;
}

function input_id($row)
{
    if (!empty($row)) {
    ?>
        <input type="hidden" value="<?= $row['id'] ?>" name="id">
    <?php
    }
}


function displayTableRows($table, $arr = array(), $sql = false)
{
    if (!$sql) {
        $sql = "SELECT * FROM $table";
    }

    $result = select_rows($sql);


    echo '<table class="table" id="tb1">';
    echo '<thead>';
    $field_names = $result[0];
    foreach ($field_names as $field => $item) {
        if ($field == "password") {
        } else {
            echo '<th>' . str_replace('_', ' ', $field) . '</th>';
        }
    }
    if (!empty($arr)) {
        echo '<th>Action</th>';
    }
    echo '</thead>';
    echo '<tbody>';

    foreach ($result as $key => $item) {
        echo '<tr>';
        foreach ($item as $key2 => $value) {
            if ($key2 == "password") {
            } else {
                echo '<td>' . $value . '</td>';
            }
        }
        if (!empty($arr)) {
            echo '<td>';
            foreach ($arr as $link) {
                $id = $item[$link['link_id']];
                if (isset($link['check'])) {
                    $ch = $link['check'];
                    $link['disabled'] = $item[$ch['col']] == $ch['val'] ? false : true;
                }
                $disable = $link['disabled'] ? "disabled" : "";

                echo '<a href="' . $link['link'] . '?id=' . $id . '" class="btn ' . $link['class'] . ' m-1 ' . $disable . '">' . $link['contains'] . '</a>';
            }
            echo '</td>';
        }
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}

/**
 * @throws Exception
 */
function last_id($table)
{
    $sql = "select * from $table order by id desc";
    $row = select_rows($sql);
    if (!empty($row)) {
        return $row[0]['id'];
    } else {
        throw new Exception("it seems something went wrong");
    }
}


function upload_custom($table_name, $name, $col_name, $cid)
{
    $image = upload($name);
    if ($image) {
        $arr['name'] = security($col_name);
        $arr['image'] = $image;
        $arr['cid'] = $cid;
        build_sql_insert($table_name, $arr);
    }
}


function textarea_input($label, $name, $row = array(), $required = true, $readonly = false)
{
    ?>
    <div class="form-group">
        <label for="<?= $name ?>"><?= $label ?><?= $required ? '<span class="text-danger">*</span>' : null ?></label>
        <textarea for="<?= $name ?>" class="form-control" style="min-width: 100%; max-width: 100%;" <?= $readonly == true ? 'readonly="readonly" ' : null ?> name="<?= $name ?>" id="<?= $name ?>" rows="5" <?= $required ? 'required' : '' ?>><?= !empty($row) ? $row[$name] : '' ?></textarea>
    </div>
<?php
}


function input_select($text, $name, $required = false, $sql = "", $array = array(),  $row = array(),  $class = "")
{
?>
    <div class="form-group <?= $class ?>">
        <label for="<?= $name ?>"><?= ucfirst($text) ?><?= !empty($row) ? ' : ' . ucfirst($row[$name]) : '' ?><?= $required ? '<span class="text-danger">*</span>' : null ?></label>
        <Select id="<?= $name ?>" <?= $required ? 'required' : '' ?> class="form-control" name="<?= $name ?>">
            <?= empty($row) || $row[$name] === null ? '<option  hidden selected disabled>' . $text . '</option>' : '' ?>
            <?php
            if ($sql != "") {
                $array = select_rows($sql);
            }
            ?>

            <?php foreach ($array as $value) {
                $selected = false;
                if (!empty($row) && ($row[$name] === $value)) {
                    $selected = true;
                } ?>
                <option <?= $selected ? 'selected disabled' : '' ?> value="<?= $value ?>"><?= ucfirst($value) ?></option>
            <?php } ?>
        </Select>
    </div>
<?php
}



function create_title($title)
{
?>
    <div class="card-header">
        <h3 class="card-title"><?= $title ?></h3>
    </div>
    <?php
}



function login($email, $password, $table, $type, $return_url)
{
    $sql = "select * from $table where email = '$email' and  password = '$password'";
    $row = select_rows($sql);
    if (!empty($row)) {
        $row = $row[0];
        $_SESSION['user'] = $row['id'];
        $_SESSION['type'] = $type;
        header($return_url);
        exit();
    }
}


function get_input_id($row)
{
    if (!empty($row)) {
    ?>
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
<?php
    }
}


function getday($month = null)
{
    $year = date('Y');
    if ($month === null) {
        $month = date('m');
    }
    $firstDay1 = new DateTime($year . '-' . $month . '-01');
    $lastDay1 = new DateTime($year . '-' . $month . '-' . date('t', strtotime($year . '-' . $month . '-01')));

    $firstDay = $firstDay1->format('Y-m-d');
    $lastDay = $lastDay1->format('Y-m-d');


    $firstDay2 = $firstDay1->format('YmdHis');
    $lastDay2 = $lastDay1->format('YmdHis');

    return [
        'first_day' => $firstDay,
        'last_day' => $lastDay,
        'first_day1' => $firstDay2,
        'last_day1' => $lastDay2,
    ];
}
function phone_check($phoneNumber)
{
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
    $length = strlen($phoneNumber);

    if ($length === 12 && strpos($phoneNumber, '254') === 0) {
        return substr($phoneNumber, 3);
    } elseif ($length === 10 && $phoneNumber[0] === '0') {
        return substr($phoneNumber, 1);
    } elseif ($length === 9 && is_numeric($phoneNumber)) {
        return $phoneNumber;
    } elseif ($length === 13 && strpos($phoneNumber, '+254') === 0) {
        return substr($phoneNumber, 4);
    } else {
        return false;
    }
}
function changetozero($a){
    if($a == null){
        $a = 0;
    }
    return $a;
}
function get_delete_link($link){
    $link = "?delete&link=".$link;
    foreach ($_GET as $key=>$item){
        $link.="&".$key."=".$item;
    }
    ?>
    <a href="<?= $link ?>" class="btn btn-danger">
        <i class="fa fa-trash"></i>
    </a>
<?php
}
function get_edit_link($link){
    foreach ($_GET as $key=>$item){
        $link.="&".$key."=".$item;
    }
    ?>
    <a href="<?= $link ?>" class="btn btn-success">
        <i class="fa fa-pen"></i>
    </a>
    <?php
}