<?php
require_once(dirname(__FILE__) . "/includes/initialize.php");
$regid = 0;
session_start();
if (isset($_GET['regid'])) {
    $regid = $_GET['regid'];
    $sql = "SELECT q.*,a.answer,a.ansid FROM Question_Master q LEFT JOIN (SELECT * FROM  answers WHERE reg_id = {$regid} ) As a ON a.qid = q.qid ";
    $questionList = Query::executeQuery($sql);
}

$errors = array();
if (isset($_POST['submit'])) {
    //var_dump($_POST);
    $regid = $_POST['regid'];
    $sql = "SELECT * FROM registration WHERE id = {$regid} ";
    $user = Query::executeQuery($sql);

    $message = '<html><body><style>table, th, td {
                                border: 1px solid black;
                                border-collapse: collapse;
                            }
                            th, td {
                                backgroud:#ddd;
                                padding: 5px;
                            }</style>';

    if (!empty($user)) {
        $user = array_shift($user);

        $message .= "<h1>Quiz Result For " . $user->name . "</h1>";
    }

    $message .= "<table style='border-color: #666; cellpadding='10' ><tr><th>Question No</th><th>Answer</th><th>Correct Answer</th></tr>";
    $answer = new Answer();
    $correctanswer = 0;

    ///Checking Whether all questions are submitted or not
    for ($i = 0; $i < count($_POST['questions']); $i++) {

        $questionid = $_POST['questions'][$i];
        if (isset($_POST['q' . $questionid])) {
            
        } else {
            array_push($errors, 1);
        }
    }

    ///Saving Of POST Data
    for ($i = 0; $i < count($_POST['questions']); $i++) {

        $questionid = $_POST['questions'][$i];
        if (isset($_POST['q' . $questionid]) && empty($errors)) {
            $data['qid'] = $questionid;
            $data['reg_id'] = $_POST['regid'];
            $data['answer'] = $_POST['q' . $questionid];
            $data['created_at'] = date('Y-m-d H:i:s');

            $message .='<tr><td>' . $data['qid'] . '</td><td>' . $data['answer'] . '</td><td>' . $_POST['correctanswer'][$i] . '</td></tr>';

            if ($_POST['correctanswer'][$i] == $_POST['q' . $questionid]) {
                $correctanswer++;
            }
            if ((int) $_POST['ansid'][$i] > 0) {
                $data = array('ansid' => (int) $_POST['ansid'][$i]) + $data;
                //var_dump($data);
//                $answer->update($data);
            } elseif ((int) $_POST['ansid'][$i] == 0) {
                //var_dump($data);
                $answer->create($data);
            }
        }
    }


    if (empty($errors)) {

        $message.="</table>";
        $message .= '</body></html>';
        $sendmail = Answer::sendmail('zypedia@gmail.com', $message, 'Zypedia Quiz');
        flashMessage($correctanswer . " Answers Are Correct", 'Error');
        redirect_to('Quiz.php?regid=' . $_POST['regid']);
    } else {
        flashMessage("Please  Answer All questions", 'Error');
    }
}
?>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<form method="post" action="Quiz.php">
    <div class="col-lg-12 col-md-12 col-xs-12" style="margin-top: 1em">
        <div class="panel panel-default">
            <div class="panel-body" style="font-size: 16px">
                <input type="hidden" name="regid" value="<?php echo $regid; ?>">
<?php
if (isset($questionList) && !empty($questionList)) {
    $correctAnswer = 0;
    foreach ($questionList as $Question) {
        ?>
                        <label style="margin-top: 10px"><?php echo $Question->question; ?></label>
                        <div class="form-group">

                        <?php
                        $answered = false;
                        $options = explode(",", $Question->name);
                        $count = 18;

                        if (!is_null($Question->ansid) && $Question->ansid > 0) {
                            $answered = true;
                            echo '<input type="hidden" name="ansid[]" value="' . $Question->ansid . '" >';
                        } else {
                            echo '<input type="hidden" name="ansid[]" value="0" >';
                        }
                        echo '<input type="hidden" name="correctanswer[]" value="' . $Question->correctanswer . '" >';


                        foreach ($options as $option) {


                            if ($Question->answer == $option) {
                                if ($Question->correctanswer == $Question->answer) {
                                    $bg = 'style="background : green" ';
                                } else {

                                    $bg = '';
                                }

                                echo '<div ' . $bg . ' class="col-xs-12"><input type = "radio" name="q' . $Question->qid . '" value="' . $option . '" checked > ' . $option . '</div>';
                            } else {
                                echo '<div  class="col-xs-12"><input type = "radio" name="q' . $Question->qid . '" value="' . $option . '" > ' . $option . '</div>';
                            }

                            $count++;
                        }
                        ?>
                        </div>
                        <?php
                        echo '<input type="hidden" name="questions[]" value="' . $Question->qid . '" >';
                    }
                }
                ?>
            </div>
            <div class="panel-footer">
                <input type="submit" name="submit" class="btn btn-primary" value="Submit">
            </div>
        </div>
    </div>
</form>
<div class="col-lg-12 col-md-12 col-xs-12" >
    <?php
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>
</div>
