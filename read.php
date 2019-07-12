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