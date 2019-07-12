<?
    $conn = mysql_connect("localhost", "root", "autoset");
    mysql_select_db("incognity",$conn);
?>
<?
//데이터 베이스 연결하기
include "db_info.php";
$id = $_GET[id];
$pass = $_POST[pass];

$result=mysql_query("SELECT pass FROM board WHERE id=$id",
$conn);
$row=mysql_fetch_array($result);

if ($pass==$row[pass] )//비밀번호 맞는지 확인함.
{
    $query = "DELETE FROM board WHERE id=$id "; //데이터 삭제하는 쿼리문
    $result=mysql_query($query, $conn);
}
else
{
    echo ("
    <script>
    alert('비밀번호가 틀립니다.');
    history.go(-1);
    </script>
    ");
    exit;
}
?>
<center>
<meta http-equiv='Refresh' content='1; URL=list.php'>
<FONT size=2 >삭제되었습니다.</font>
<html>
<head>
<title>내가 만든 게시판</title>
<style>
<!--
td { font-size : 9pt; }
A:link { font : 9pt; color : black; text-decoration : none; 
font-family: 굴림; font-size : 9pt; }
A:visited { text-decoration : none; color : black; 
font-size : 9pt; }
A:hover { text-decoration : underline; color : black; 
font-size : 9pt;}
-->
</style>
</head>

<body topmargin=0 leftmargin=0 text=#464646>
<center>
<BR>
<form action=update.php?id=<?=$_GET[id]?> method=post>
<table width=580 border=0 cellpadding=2 cellspacing=1 bgcolor=#777777>
    <tr>
        <td height=20 align=center bgcolor=#999999>
            <font color=white><B>글 수 정 하 기</B></font>
        </td>
    </tr>
<?
    //데이터 베이스 연결하기
    include "db_info.php";
    $id = $_GET[id];
    $no = $_GET[no];

    // 먼저 쓴 글의 정보를 가져온다.
    $result=mysql_query("SELECT * FROM board WHERE id=$id", $conn);
    $row=mysql_fetch_array($result);
?>
<!-- 입력 부분 -->
    <tr>
        <td bgcolor=white>&nbsp;
        <table>
            <tr>
                <td width=60 align=left >이름</td>
                <td align=left >
                    <INPUT type=text name=name size=20 
                    value="<?=$row[name]?>">
                </td>
            </tr>
            <tr>
                <td width=60 align=left >이메일</td>
                <td align=left >
                    <INPUT type=text name=email size=20 
                    value="<?=$row[email]?>">
                </td>
            </tr>
            <tr>
                <td width=60 align=left >비밀번호</td>
                <td align=left >
                    <INPUT type=password name=pass size=8> 
                    (비밀번호가 맞아야 수정가능)
                </td>
            </tr>
            <tr>
                <td width=60 align=left >제 목</td>
                <td align=left >
                    <INPUT type=text name=title size=60 
                    value="<?=$row[title]?>">
                </td>
            </tr>
            <tr>
                <td width=60 align=left >내용</td>
                <td align=left >
                    <TEXTAREA name=content cols=65 rows=15><?=$row[content]?></TEXTAREA>
                </td>
            </tr>
            <tr>
                <td colspan=10 align=center>
                    <INPUT type=submit value="글 저장하기">
                    &nbsp;&nbsp;
                    <INPUT type=reset value="다시 쓰기">
                    &nbsp;&nbsp;
                    <INPUT type=button value="되돌아가기" 
                    onclick="history.back(-1)">
                </td>
            </tr>
            </TABLE>
        </td>
    </tr>
<!-- 입력 부분 끝 -->
</table>
</form>
</center>
</body>
</html>
<?
    //데이터 베이스 연결하기
    include "db_info.php";

    $id = $_GET[id];
    $name = $_POST[name];
    $email = $_POST[email];
    $pass = $_POST[pass];
    $title = $_POST[title];
    $content = $_POST[content];
    $REMOTE_ADDR = $_SERVER[REMOTE_ADDR];

    $query = "INSERT INTO board 
    (id, name, email, pass, title, content, wdate, ip, view)
    VALUES ('', '$name', '$email', '$pass', '$title', 
    '$content', now(), '$REMOTE_ADDR', 0)";
    $result=mysql_query($query, $conn) or die(mysql_error());

    //데이터베이스와의 연결 종료
    mysql_close($conn);

    // 새 글 쓰기인 경우 리스트로..
    echo ("<meta http-equiv='Refresh' content='1; URL=list.php'>");
    //1초후에 list.php로 이동함.
?>
<center>
<font size=2>정상적으로 저장되었습니다.</font>
<?
//데이터 베이스 연결하기
include "db_info.php";

# LIST 설정
# 1. 한 페이지에 보여질 게시물의 수
$page_size=10;

# 2. 페이지 나누기에 표시될 페이지의 수
// $no는 페이지가 시작되는 첫 글의 순번
$page_list_size = 10;
$no = $_GET[no];
if (!$no || $no <0) $no=0;

// 데이터베이스에서 페이지의 첫번째 글($no)부터 
// $page_size 만큼의 글을 가져온다.
$query = "SELECT * FROM board ORDER BY id DESC LIMIT $no, $page_size";
$result = mysql_query($query, $conn);

// 총 게시물 수 를 구한다.
$result_count=mysql_query("SELECT count(*) FROM board",$conn);
$result_row=mysql_fetch_row($result_count);
$total_row = $result_row[0];
//결과의 첫번째 열이 count(*) 의 결과다.
//mysql_fetch_row 쓰면 $result_row[0] 처럼 숫자를 써서 접근을 해야한다. 

# 총 페이지 계산
# ceil는 올림
if ($total_row <= 0) $total_row = 0;
$total_page = ceil($total_row / $page_size);//1개면

# 현재 페이지 계산
# no 변수는 0부터 시작해서 +1을 해줌.
$current_page = ceil(($no+1)/$page_size);
?>

<html>
<head>
<title>내가 만든 게시판 </title>
<style>
<!--
td {font-size : 9pt;}
A:link {font : 9pt;color : black;text-decoration : none; fontfamily
: 굴림;font-size : 9pt;}
A:visited {text-decoration : none; color : black; font-size : 9pt;}
A:hover {text-decoration : underline; color : black; font-size : 9pt;}
-->
</style>
</head>
<body topmargin=0 leftmargin=0 text=#464646>
<center>
<BR>
<!-- 게시판 타이틀 -->
<font size=2>나를 찾아서~</a>
<BR>
<BR>
<!-- 게시물 리스트를 보이기 위한 테이블 -->
<table width=580 border=0 cellpadding=2 cellspacing=1
bgcolor=#777777>
<!-- 리스트 타이틀 부분 -->
<tr height=20 bgcolor=#999999>
    <td width=30 align=center>
        <font color=white>번호</font>
    </td>
    <td width=370 align=center>
        <font color=white>제 목</font>
    </td>
    <td width=50 align=center>
        <font color=white>글쓴이</font>
    </td>
    <td width=60 align=center>
        <font color=white>날 짜</font>
    </td>
    <td width=40 align=center>
        <font color=white>조회수</font>
    </td>
</tr>

<!-- 리스트 부분 시작 -->
<?
while($row=mysql_fetch_array($result))
{

?>
<!-- 행 시작 -->
<tr>
    <!-- 번호 -->
    <td height=20 bgcolor=white align=center>
        <a href="read.php?id=<?=$row[id]?>&no=<?=$no?>">
        <?=$row[id]?></a>
    </td>

    <!-- 제목 -->
    <td height=20 bgcolor=white>&nbsp;
        <a href="read.php?id=<?=$row[id]?>&no=<?=$no?>">
        <?=strip_tags($row[title], '<b><i>');?></a>
    </td>

    <!-- 이름 -->
    <td align=center height=20 bgcolor=white>
        <font color=black>
        <a href="mailto:<?=$row[email]?>"><?=$row[name]?></a>
        </font>
    </td>

    <!-- 날짜 -->
    <td align=center height=20 bgcolor=white>
        <font color=black><?=$row[wdate]?></font>
    </td>

    <!-- 조회수 -->
    <td align=center height=20 bgcolor=white>
        <font color=black><?=$row[view]?></font>
    </td>

</tr>
<!-- 행 끝 -->
<?
}
//데이터베이스와의 연결을 끝는다.
mysql_close($conn);
?>
</table>
<!-- 게시물 리스트를 보이기 위한 테이블 끝-->
<!-- 페이지를 표시하기 위한 테이블 -->
<table border=0>
<tr>
    <td width=600 height=20 align=center rowspan=4>
    <font color=gray>
    &nbsp;
<?
$start_page = floor(($current_page - 1) / $page_list_size) * $page_list_size + 1;
# floor 함수는 소수점 이하는 버림

# 페이지 리스트의 마지막 페이지가 몇 번째 페이지인지 구하는 부분이다.
$end_page = $start_page + $page_list_size - 1;

if ($total_page <$end_page) $end_page = $total_page;

if ($start_page >= $page_list_size) {
    # 이전 페이지 리스트값은 첫 번째 페이지에서 한 페이지 감소하면 된다.
    # $page_size 를 곱해주는 이유는 글번호로 표시하기 위해서이다.

    $prev_list = ($start_page - 2)*$page_size;
    echo "<a href="$PHP_SELF?no=$prev_list>◀</a> ";
}

# 페이지 리스트를 출력
for ($i=$start_page;$i <= $end_page;$i++) {
    $page= ($i-1) * $page_size;// 페이지값을 no 값으로 변환.
    if ($no!=$page){ //현재 페이지가 아닐 경우만 링크를 표시
        echo "<a href="$PHP_SELF?no=$page">";
    }
    
    echo " $i "; //페이지를 표시
    
    if ($no!=$page){ //현재 페이지가 아닐 경우만 링크를 표시하기 위해서
        echo "</a>";
    }
}

# 다음 페이지 리스트가 필요할때는 총 페이지가 마지막 리스트보다 클 때
# 리스트를 다 뿌리고도 더 뿌려줄 페이지가 남았을때 다음 버튼이 필요
if($total_page >$end_page)
{
    $next_list = $end_page * $page_size;
    echo "<a href=$PHP_SELF?no=$next_list>▶</a><p>";
}
?>
    </font>
    </td>
</tr>
</table>
<a href=write.php>글쓰기</a>
</center>
</body>
</html>
<html>
<head>
<title>내가 만든 게시판</title>
<style>
<!--
td {font-size : 9pt;}
A:link {font : 9pt;color : black;text-decoration : none;
font-family: 굴림;font-size : 9pt;}
A:visited {text-decoration : none; color : black; font-size : 9pt;}
A:hover {text-decoration : underline; color : black; 
font-size : 9pt;}
-->
</style>
</head>

<body topmargin=0 leftmargin=0 text=#464646>
<center>
<BR>

<form action=delete.php?id=<?=$_GET[id]?> method=post>
<table width=300 border=0 cellpadding=2 cellspacing=1
bgcolor=#777777>
<tr>
    <td height=20 align=center bgcolor=#999999>
        <font color=white><B>비 밀 번 호 확 인</B></font>
    </td>
</tr>
<tr>
    <td align=center >
        <font color=white><B>비밀번호 : </b>
        <INPUT type=password name=pass size=8>
        <INPUT type=submit value="확 인">
        <INPUT type=button value="취 소" onclick="history.back(-1)">
    </td>
</tr>
</table>
</html>
<html>
<head>
<title>내가 만든 게시판</title>
<style>
<!--
td {font-size : 9pt;}
A:link {font : 9pt; color : black; text-decoration : none;
font-family : 굴림; font-size : 9pt;}
A:visited {text-decoration : none; color : black; font-size : 9pt;}
A:hover {text-decoration : underline; color : black; 
font-size : 9pt;}
-->
</style>
</head>

<body topmargin=0 leftmargin=0 text=#464646>
<center>
<BR>
<?
    //데이터 베이스 연결하기
    include "db_info.php";

    $id = $_GET[id];
    $no = $_GET[no];
    // 글 정보 가져오기
    $result=mysql_query("SELECT * FROM board WHERE id=$id", $conn);
    $row=mysql_fetch_array($result);
?>
<table width=580 border=0 cellpadding=2 cellspacing=1
bgcolor=#777777>
<tr>
    <td height=20 colspan=4 align=center bgcolor=#999999>
        <font color=white><B><?=$row[title]?></B></font>
    </td>
</tr>
<tr>
    <td width=50 height=20 align=center bgcolor=#EEEEEE>글쓴이</td>
    <td width=240 bgcolor=white><?=$row[name]?></td>
    <td width=50 height=20 align=center bgcolor=#EEEEEE>이메일</td>
    <td width=240 bgcolor=white><?=$row[email]?></td>
</tr>
<tr>
    <td width=50 height=20 align=center bgcolor=#EEEEEE>
    날&nbsp;&nbsp;&nbsp;짜</td><td width=240
    bgcolor=white><?=$row[wdate]?></td>
    <td width=50 height=20 align=center bgcolor=#EEEEEE>조회수</td>
    <td width=240 bgcolor=white><?=$row[view]?></td>
</tr>
<tr>
    <td bgcolor=white colspan=4>
    <font color=black>
    <pre><?=$row[content]?></pre>
    </font>
    </td>
</tr>
<tr>
    <td colspan=4 bgcolor=#999999>
    <table width=100%>
        <tr>
            <td width=200 align=left height=20>
                <a href=list.php?no=<?=$no?>><font color=white>
                [목록보기]</font></a>
                <a href=write.php><font color=white>
                [글쓰기]</font></a>
                <a href=edit.php?id=<?=$id?>><font color=white>
                [수정]</font></a>
                <a href=predel.php?id=<?=$id?>>
                <font color=white>[삭제]</font></a>
            </td>
            <td align=right>
<?
    $query=mysql_query("SELECT id FROM board WHERE id >$id LIMIT 1", 
    $conn);
    $prev_id=mysql_fetch_array($query);

    if ($prev_id[id]) // 이전 글이 있을 경우
    {
        echo "<a href=read.php?id=$prev_id[id]>
        <font color=white>[이전]</font></a>";
    }
    else
    {
        echo "[이전]";
    }

    $query=mysql_query("SELECT id FROM board WHERE id <$id 
    ORDER BY id DESC LIMIT 1", $conn);
    $next_id=mysql_fetch_array($query);

    if ($next_id[id])
    {
        echo "<a href=read.php?id=$next_id[id]>
        <font color=white>[다음]</font></a>";
    }
    else
    {
        echo "[다음]";
    }
?>
            </td>
        </tr>
    </table>
    </b></font>
    </td>
</tr>
</table>
</center>
</body>
</html>

<?
    // 조회수 업데이트
    $result=mysql_query("UPDATE board SET view=view+1 WHERE id=$id",
    $conn);

    mysql_close($conn);
?>
<?
    //데이터 베이스 연결하기
    include "db_info.php";
    $id = $_GET[id];
    $name = $_POST[name];
    $pass = $_POST[pass];
    $email = $_POST[email];
    $title = $_POST[title];
    $content = $_POST[content];

    // 글의 비밀번호를 가져온다.
    $query = "SELECT pass FROM board WHERE id=$id";
    $result=mysql_query($query, $conn);
    $row=mysql_fetch_array($result);

    //입력된 값과 비교한다.
    if ($pass==$row[pass]) { //비밀번호가 일치하는 경우
        $query = "UPDATE board SET name='$name', email='$email',
        title='$title', content='$content' WHERE id=$id";//업데이트 쿼리문
        $result=mysql_query($query, $conn);
    }
    else { // 비밀번호가 일치하지 않는 경우
        echo ("
        <script>
        alert('비밀번호가 틀립니다.');
        history.go(-1);
        </script>
        ");
        exit;
    }

    //데이터베이스와의 연결 종료
    mysql_close($conn);

    //수정하기인 경우 수정된 글로
    echo ("<meta http-equiv='Refresh' content='1; 
    URL=read.php?id=$id'>");
?>
<center>
<font size=2>정상적으로 수정되었습니다.</font>
<html>
<head>
<title>내가 만든 게시판</title>
<style>
<!--
td { font-size : 9pt; }
A:link { font : 9pt; color : black; text-decoration : none; 
font-family : 굴림; font-size : 9pt; }
A:visited { text-decoration : none; color : black; font-size : 9pt; }
A:hover { text-decoration : underline; color : black; 
font-size : 9pt; }
-->
</style>
</head>

<body topmargin=0 leftmargin=0 text=#464646>
<center>
<BR>
<form action=insert.php method=post>
<table width=580 border=0 cellpadding=2 cellspacing=1 bgcolor=#777777>
    <tr>
        <td height=20 align=center bgcolor=#999999>
        <font color=white><B>글 쓰 기</B></font>
        </td>
    </tr>
    <!-- 입력 부분 -->
    <tr>
        <td bgcolor=white>&nbsp;
        <table>
            <tr>
                <td width=60 align=left >이름</td>
                <td align=left >
                    <INPUT type=text name=name size=20 maxlength=10>
                </td>
            </tr>
            <tr>
                <td width=60 align=left >이메일</td>
                <td align=left >
                    <INPUT type=text name=email size=20 maxlength=25>
                </td>
            </tr>
            <tr>
                <td width=60 align=left >비밀번호</td>
                <td align=left >
                    <INPUT type=password name=pass size=8 maxlength=8> 
                    (수정,삭제시 반드시 필요)
                </td>
            </tr>
            <tr>
                <td width=60 align=left >제 목</td>
                <td align=left >
                    <INPUT type=text name=title size=60 maxlength=35>
                </td>
            </tr>
            <tr>
                <td width=60 align=left >내용</td>
                <td align=left >
                    <TEXTAREA name=content cols=65 rows=15></TEXTAREA>
                </td>
            </tr>
            <tr>
                <td colspan=10 align=center>
                    <INPUT type=submit value="글 저장하기">
                    &nbsp;&nbsp;
                    <INPUT type=reset value="다시 쓰기">
                    &nbsp;&nbsp;
                    <INPUT type=button value="되돌아가기" 
                    onclick="history.back(-1)"> <!--버튼이 클릭되었을때 발생하는 이벤트로 자바스크립트를 실행함. 이전페이지로-->
                </td>
            </tr>
        </TABLE>
</td>
</tr>
<!-- 입력 부분 끝 -->
</table>
</form>
</center>
</body>
</html>
</html>